<?php

namespace App\Http\Controllers\api\v1\auth;

use App\CPU\Helpers;
use App\CPU\CustomerManager;
use App\CPU\Convert;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Admin;
use App\Model\Assessor;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use function App\CPU\translate;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:8'
        ], [
            'f_name.required' => 'The first name field is required.'
        ]);

        if ($validator->fails()) {

            $response = [
                'token' => "",
                'status' => false,
                'message' => Helpers::single_error_processor($validator),
                'data' => [],
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }
        $temporary_token = Str::random(40);
        $user = User::create([
            'name' => $request->f_name.' '.$request->l_name,
            'f_name' => $request->f_name,
            'l_name' => $request->l_name ?? "",
            'email' => $request->email,
            'phone' => $request->phone,
            'country_code' => $request->country_code,
            'image' =>ImageManager::upload('profile/','png', $request->file('image')),
            'is_active' => 1,
            'temporary_token' => $temporary_token,
            'cm_firebase_token' => $request->cm_firebase_token
        ]);

        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            $response = [
                'token' => $temporary_token,
                'status' => true,
                'message' => "User registered success",
                'data' => $user,
                'errors' => []
            ];
            return response()->json($response, 200);
        }
        if ($email_verification && !$user->is_email_verified) {

            $response = [
                'token' => $temporary_token,
                'status' => true,
                'message' => "User registered success",
                'data' => $user,
                'errors' => []
            ];
            return response()->json($response, 200);
        }

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        if (isset($request->referral_code) && $request->referral_code != "") {
            $refered_user = User::where('referral_code', $request->referral_code)->first();
            if ($refered_user) {
                CustomerManager::create_loyalty_point_transaction($refered_user->id, $user->id, 10, 'refer_n_earn');
            }
        }

        $response = [
            'token' => $token,
            'status' => true,
            'message' => "User registered success",
            'data' => $user,
            'errors' => []
        ];

        return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {

            $response = [
                'token' => "",
                'status' => false,
                'message' => Helpers::single_error_processor($validator),
                'data' => [],
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        $user_id = $request['email'];
        if (filter_var($user_id, FILTER_VALIDATE_EMAIL)) {
            $medium = 'email';
        } else {
            $count = strlen(preg_replace("/[^\d]/", "", $user_id));
            if ($count >= 9 && $count <= 15) {
                $medium = 'phone';
            } else {
                $errors = [];
                array_push($errors, ['code' => 'email', 'message' => 'Invalid email address or phone number']);

                $response = [
                    'token' => "",
                    'status' => false,
                    'message' => "Invalid email address or phone number",
                    'data' => [],
                    'errors' => []
                ];

                return response()->json($response, 403);
            }
        }

        $data = [
            $medium => $user_id,
            'password' => $request->password
        ];

        $user = User::where([$medium => $user_id])->first();
        $max_login_hit = Helpers::get_business_settings('maximum_login_hit') ?? 5;
        $temp_block_time = Helpers::get_business_settings('temporary_login_block_time') ?? 5; //minute

        if (isset($user)) {
            $user->temporary_token = Str::random(40);
            $user->save();

            $phone_verification = Helpers::get_business_settings('phone_verification');
            $email_verification = Helpers::get_business_settings('email_verification');
            if ($phone_verification && !$user->is_phone_verified) {
                return response()->json(['temporary_token' => $user->temporary_token], 200);
            }
            if ($email_verification && !$user->is_email_verified) {
                return response()->json(['temporary_token' => $user->temporary_token], 200);
            }

            if (isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->diffInSeconds() <= $temp_block_time) {
                $time = $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                $errors = [];
                array_push($errors, ['code' => 'auth-001', 'message' => translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans()]);


                $response = [
                    'token' => "",
                    'status' => false,
                    'message' => translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'data' => [],
                    'errors' => []
                ];

                return response()->json($response, 401);
            }

            if ($user->is_active && auth()->attempt($data)) {
                $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;

                $user->login_hit_count = 0;
                $user->is_temp_blocked = 0;
                $user->temp_block_time = null;
                $user->updated_at = now();

                $user->cm_firebase_token = $request->input('fcm_id') ?? "";
                $user->save();

                $response = [
                    'token' => $token,
                    'status' => true,
                    'message' => "User logged in success",
                    'data' => [],
                    'errors' => []
                ];

                return response()->json($response, 200);
            } else {
                //login attempt check start
                if (isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->diffInSeconds() <= $temp_block_time) {
                    $time = $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                    $errors = [];
                    array_push($errors, ['code' => 'auth-001', 'message' => translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans()]);

                    $response = [
                        'token' => "",
                        'status' => false,
                        'message' => translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                        'data' => [],
                        'errors' => []
                    ];

                    return response()->json($response, 401);
                } elseif ($user->is_temp_blocked == 1 && Carbon::parse($user->temp_block_time)->diffInSeconds() >= $temp_block_time) {

                    $user->login_hit_count = 0;
                    $user->is_temp_blocked = 0;
                    $user->temp_block_time = null;
                    $user->updated_at = now();
                    $user->save();

                    $errors = [];
                    array_push($errors, ['code' => 'auth-001', 'message' => translate('credentials_do_not_match_or_account_has_been_suspended')]);

                    $response = [
                        'token' => "",
                        'status' => false,
                        'message' => translate('credentials_do_not_match_or_account_has_been_suspended'),
                        'data' => [],
                        'errors' => []
                    ];

                    return response()->json($response, 401);
                } elseif ($user->login_hit_count >= $max_login_hit &&  $user->is_temp_blocked == 0) {
                    $user->is_temp_blocked = 1;
                    $user->temp_block_time = now();
                    $user->updated_at = now();
                    $user->save();

                    $time = $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                    $errors = [];
                    array_push($errors, ['code' => 'auth-001', 'message' => translate('too_many_attempts. please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans()]);

                    $response = [
                        'token' => "",
                        'status' => false,
                        'message' => translate('too_many_attempts. please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                        'data' => [],
                        'errors' => []
                    ];

                    return response()->json($response, 401);
                } else {

                    $user->login_hit_count += 1;
                    $user->save();

                    $errors = [];
                    array_push($errors, ['code' => 'auth-001', 'message' => translate('credentials_do_not_match_or_account_has_been_suspended')]);

                    $response = [
                        'token' => "",
                        'status' => false,
                        'message' => translate('credentials_do_not_match_or_account_has_been_suspended'),
                        'data' => [],
                        'errors' => []
                    ];

                    return response()->json($response, 401);
                }
                //login attempt check end
            }
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => translate('Customer_not_found_or_Account_has_been_suspended')]);

            $response = [
                'token' => "",
                'status' => false,
                'message' => translate('Customer_not_found_or_Account_has_been_suspended'),
                'data' => [],
                'errors' => []
            ];

            return response()->json($response, 401);
        }
    }
    public function assessorLogin(Request $request)
    {
        return $this->auditorLogin($request);
    }

    public function auditorLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'token' => "",
                'status' => false,
                'message' => "Please fill required fields",
                'data' => [],
                'errors' => Helpers::error_processor($validator)
            ], 403);
        }

        $auditor = Admin::with('assessor')
            ->where('email', $request->email)
            ->where('admin_role_id', 3)
            ->first();

        if (!$auditor || !$auditor->status || !Hash::check($request->password, $auditor->password)) {
            return response()->json([
                'token' => '',
                'status' => false,
                'message' => "Invalid credential",
                'data' => []
            ], 401);
        }

        $token = $auditor->createToken('AuditorAuthApp')->accessToken;
        $responseData = [
            'id' => $auditor->id,
            'name' => $auditor->name,
            'phone' => $auditor->phone,
            'admin_role_id' => $auditor->admin_role_id,
            'image' => $auditor->image,
            'image_url' => $auditor->image ? asset('storage/app/public/admin/' . $auditor->image) : "",
            'email' => $auditor->email,
            'status' => $auditor->status,
            'apply_designation' => optional($auditor->assessor)->apply_designation,
            'highest_qualification' => optional($auditor->assessor)->highest_qualification,
            'technical_area' => optional($auditor->assessor)->technical_area,
            'experience' => optional($auditor->assessor)->experience,
            'home_address' => optional($auditor->assessor)->home_address,
        ];

        return response()->json([
            'token' => $token,
            'status' => true,
            'message' => "Auditor login successful",
            'data' => $responseData
        ], 200);
    }

    public function auditorForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Please provide a valid email",
                'errors' => Helpers::error_processor($validator)
            ], 403);
        }

        $auditor = Admin::where('email', $request->email)
            ->where('admin_role_id', 3)
            ->first();

        if (!$auditor) {
            return response()->json([
                'status' => false,
                'message' => "User not found"
            ], 404);
        }

        $token = Str::random(120);
        DB::table('password_resets')->updateOrInsert(
            ['identity' => $auditor->email, 'user_type' => 'admin'],
            [
                'token' => $token,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $resetUrl = url('/') . '/admin/auth/reset-password?token=' . $token;

        $emailServicesSmtp = Helpers::get_business_settings('mail_config');
        if (!is_array($emailServicesSmtp) || !isset($emailServicesSmtp['status']) || (int)$emailServicesSmtp['status'] === 0) {
            $emailServicesSmtp = Helpers::get_business_settings('mail_config_sendgrid');
        }

        if (is_array($emailServicesSmtp) && isset($emailServicesSmtp['status']) && (int)$emailServicesSmtp['status'] === 1) {
            Mail::to($auditor->email)->send(new \App\Mail\PasswordResetMail($resetUrl));
            return response()->json([
                'status' => true,
                'message' => "Password reset link sent to your email"
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => "Email service is not configured"
        ], 500);
    }

    public function assessorRegister(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password'=>'required',
            'phone'=>'required|unique:admins'

        ], [
            'name.required' => 'name is required!',
            'email.required' => 'Email id is Required',

        ]);

        if ($validator->fails()) {

            $response = [
                'token' => "",
                'status' => false,
                'message' => "Please fill all required fields",
                'data' => [],
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        $temporary_token = Str::random(50);

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->phone = $request->phone;
        $admin->email = $request->email;
        $admin->admin_role_id = 3;//$request->role_id;
        $admin->password = bcrypt($request->password);
        $admin->status = 1;
        $admin->image = ($request->hasFile('image')) ? ImageManager::upload('admin/', 'png', $request->file('image')): 'def.png';
        $admin->auth_token = $temporary_token;
        $admin->save();


        $assessor = new Assessor();
        $assessor->assessor_id = $admin->id;
        $assessor->profile_status = 0;
        $assessor->home_address = $request['home_address'];
        // $assessor->scheme_id = (isset($request['scheme_id']) && !empty($request['scheme_id'])) ? implode(',',$request['scheme_id']) : null;
        // $assessor->area_id = (isset($request['area_id']) && !empty($request['area_id'])) ? implode(',',$request['area_id']) : null;
        // $assessor->scope_id = (isset($request['scope_id']) && !empty($request['scope_id'])) ? implode(',',$request['scope_id']) : null;
        // $assessor->qualifications = null;//$request['qualifications'];
        $assessor->save();

        $assessor->id_number = 100000 + $assessor->id;
        $assessor->save();


        if ($admin) {
            $response = [
                'token' => $temporary_token,
                'status' => true,
                'message' => "Assessor registered success",
                'data' => $admin
            ];
        } else {
            $response = [
                'token' => '',
                'status' => false,
                'message' => "Something went wrong",
                'data' => []
            ];
        }
        
        return response()->json($response);

    }

    public function send_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:users'
        ], [
            'phone.required' => 'Mobile number required'
        ]);

        if ($validator->fails()) {

            $response = [
                'token' => "",
                'status' => false,
                'message' => Helpers::single_error_processor($validator),
                'data' => [],
                'errors' => Helpers::error_processor($validator)

            ];

            return response()->json($response, 403);
        }

        // $temporary_token = Str::random(40);

        $user = User::where(['phone' => $request->input('phone')])->first();

        if($user){
            $response = [
                'token' => "",
                'status' => false,
                'message' => "Number is Already registered",
                'data' => []
                // 'errors' =>[]
            ];

            return response()->json($response, 403);
        }


        // $token = $user->createToken('LaravelAuthApp')->accessToken;

        $otp = rand(1111, 9999);
        $dt[] = ['otp' => strval($otp), 'mobile' => $request->input('phone')];
        $response = [
            // 'token' => $temporary_token,
            'status' => true,
            'message' => "OTP sent success",
            'data' => $dt
        ];

        return response()->json($response, 200);
    }

    public function send_login_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required'
        ], [
            'phone.required' => 'Mobile number required'
        ]);

        if ($validator->fails()) {

            $response = [
                // 'token' => "",
                'status' => false,
                'message' => "Mobile number required",
                // 'data' => [],
                // 'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        $user = User::where(['phone' => $request->input('phone')])->first();

        if ($user) {

            if ($user->is_active != 1) {
                $response = [
                   
                    'status' => false,
                    'message' => "User is blocked",
                    'data' => [],
                ];

                return response()->json($response, 403);
            }

            // $user->cm_firebase_token = $request->input('fcm_id') ?? "";
            // $user->save();

            // $temporary_token = Str::random(40);

            // $token = $user->createToken('LaravelAuthApp')->accessToken;


            $otp = rand(1111, 9999);

            $user->otp = $otp;
            $user->save();

            $dt[] = ['otp' => strval($otp), 'mobile' => $request->input('phone')];

            $response = [
                // 'token' => $token,
                'status' => true,
                'message' => "OTP sent success",
                'data' => $dt,
                // 'errors' => []
            ];
        } else {
            $response = [
                // 'token' => "",
                'status' => false,
                'message' => "User not registered",
                'data' => [],
                // 'errors' => []
            ];
            return response()->json($response, 403);
        }
        return response()->json($response, 200);
    }
    public function verify_login_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required'
        ], [
            'phone.required' => 'Mobile number required'
        ]);

        if ($validator->fails()) {

            $response = [
                // 'token' => "",
                'status' => false,
                'message' => "Mobile number required",
                // 'data' => [],
                // 'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        $user = User::where(['phone' => $request->input('phone')])->first();

        if ($user) {

            if ($user->is_active != 1) {
                $response = [
                    // 'token' => "",
                    'status' => false,
                    'message' => "User is blocked",
                    // 'data' => [],
                    // 'errors' => [
                    //     'account' => ['This user account has been blocked.']
                    // ]
                ];

                return response()->json($response, 403);
            }

            if($user->otp != $request['otp']){
                $response = [
                    'status' => false,
                    'message' => "Invalid OTP",
                ];

                return response()->json($response, 403);
            }

            $user->cm_firebase_token = $request->input('fcm_id') ?? "";
            $user->save();

            // $temporary_token = Str::random(40);

            $token = $user->createToken('LaravelAuthApp')->accessToken;

            // $otp = rand(1111, 9999);

            // $dt[] = ['otp' => strval($otp), 'mobile' => $request->input('phone')];

            $response = [
                'token' => $token,
                'status' => true,
                'message' => "Login Successfully",
                'data' => $user,
                // 'errors' => []
            ];
        } else {
            $response = [
                // 'token' => "",
                'status' => false,
                'message' => "User not registered",
                'data' => [],
                // 'errors' => []
            ];
        }
        return response()->json($response, 200);
    }
}
