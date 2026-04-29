<?php

namespace App\Http\Controllers\api\v2\delivery_man\auth;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\SMS_module;
use App\Http\Controllers\Controller;
use App\Model\DeliveryMan;
use App\Model\PasswordReset;
use App\Model\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function App\CPU\translate;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Please fill all required fields', 'token' => '', 'errors' => Helpers::error_processor($validator)], 403);
        }

        /**
         * checking if existing delivery man has a country code or not
         */

        $d_man = DeliveryMan::where(['email' => $request->phone])->first();

        // if($d_man && isset($d_man->country_code) && ($d_man->country_code != $request->country_code)){
        //     $errors = [];
        //     array_push($errors, ['code' => 'auth-001', 'message' => 'Invalid credential or account suspended']);
        //     return response()->json([
        //         'errors' => $errors
        //     ], 404);
        // }

        if (isset($d_man) && $d_man['is_active'] == 1 && Hash::check($request->password, $d_man->password)) {
            $token = Str::random(50);
            $d_man->auth_token = $token;
            $d_man->save();
            return response()->json(['status' => true, 'message' => 'Login success', 'token' => $token, 'errors' => []], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Invalid credential or account suspended']);

            $response = ['status' => true, 'message' => 'Login success', 'token' => '', 'errors' => $errors];

            return response()->json($response, 401);
        }
    }

    public function register(Request $request)
    {

        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:delivery_men',
            'phone' => 'required|unique:delivery_men',
            'password' => 'required',
            'license_number' => 'required',
            'license_doi' => 'required',
            'license_exp_date' => 'required',
            'license_state' => 'required',
            'bank_name' => 'required',
            'branch_name' => 'required',
            'account_type' => 'required',
            'micr_code' => 'required',
            'bank_address' => 'required',
            'account_number' => 'required',
            'ifsc_code' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'country' => 'required',
            'vehicle_type' => 'required',
            'registeration_number' => 'required',
            'issue_date' => 'required',
            'expiration_date' => 'required',
            'policy_number' => 'required',
            'coverage_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'token' => '', 'message' => 'Please fill all required fields', 'errors' => Helpers::error_processor($validator)], 403);
        }

        /**
         * checking if existing delivery man has a country code or not
         */

         $token = Str::random(50);
         
        $d_man = new DeliveryMan;
        $d_man->auth_token = $token;
        $d_man->f_name = $request['name'];
        $d_man->email = $request['email'];
        $d_man->phone = $request['phone'];
        $d_man->password = Hash::make($request['password']);
        $d_man->license_number = $request['license_number'];
        $d_man->license_doi = $request['license_doi'];
        $d_man->license_exp_date = $request['license_exp_date'];
        $d_man->license_state = $request['license_state'];
        $d_man->bank_name = $request['bank_name'];
        $d_man->branch = $request['branch_name'];
        $d_man->account_type = $request['account_type'];
        $d_man->micr_code = $request['micr_code'];
        $d_man->bank_address = $request['bank_address'];
        $d_man->account_no = $request['account_number'];
        $d_man->ifsc_code = $request['ifsc_code'];
        $d_man->address = $request['address'];
        $d_man->city = $request['city'];
        $d_man->state = $request['state'];
        $d_man->pincode = $request['pincode'];
        $d_man->country = $request['country'];
        $d_man->vehicle_type = $request['vehicle_type'];
        $d_man->registeration_number = $request['registeration_number'];
        $d_man->issue_date = $request['issue_date'];
        $d_man->expiration_date = $request['expiration_date'];
        $d_man->policy_number = $request['policy_number'];
        $d_man->coverage_date = $request['coverage_date'];

        if(isset($request['seller_code']) && $request['seller_code']) {
            $seller = Seller::where('refferral', $request['seller_code'])->first();
            if(!empty($seller)) {
                $d_man->seller_id = $seller->id;
            }
        }

        $imageName = "";
        if($request->has('license_image')) {
            $image = $request->file('license_image');
            if ($image != null) {
                $imageName = ImageManager::update('delivery-man/', $d_man->license_image, 'png', $request->file('license_image'));
            } else {
                // $imageName = $d_man->license_image;
            }
            $d_man->license_image = $imageName;
        }

        $vImageName = "";
        if($request->has('vehicle_image')) {
            $image = $request->file('vehicle_image');
            if ($image != null) {
                $vImageName = ImageManager::update('delivery-man/', $d_man->vehicle_image, 'png', $request->file('vehicle_image'));
            } else {
                // $imageName = $d_man->vehicle_image;
            }
        }
        $d_man->vehicle_image = $vImageName;

        if($d_man->save()) {
            $response = ['status' => true, 'message' => 'Driver Registered Success', 'errors' => [], 'token' => $token];
        } else {
            $response = ['status' => false, 'message' => 'Something went wrong', 'errors' => [], 'token' => $token];
        }
        return response()->json($response);
    }

    public function reset_password_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Please enter valid phone number'], 403);
        }
        /**
         * Delete previous unused reset request
         */
        PasswordReset::where(['user_type'=> 'delivery_man', 'identity'=> $request->phone])->delete();

        $delivery_man = DeliveryMan::where(['phone' => $request->phone])->first();

        if($delivery_man && isset($delivery_man->country_code) && ($delivery_man->country_code != $request->country_code)){
            return response()->json(
                ['status' => false, 'message' => translate('user_not_found'), 'otp' => '']
            , 404);
        }

        if (isset($delivery_man))
        {
            $otp = rand(1000, 9999);

            PasswordReset::insert([
                'identity' => $delivery_man->phone,
                'token' => $otp,
                'user_type' => 'delivery_man',
                'created_at' => now(),
            ]);

            $emailServices_smtp = Helpers::get_business_settings('mail_config');

            if ($emailServices_smtp['status'] == 0) {
                $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
            }
            if ($emailServices_smtp['status'] == 1) {
                Mail::to($delivery_man['email'])->send(new \App\Mail\DeliverymanPasswordResetMail($otp));
            } else {
                return response()->json(['status' => false, 'message' => translate('email_failed'), 'otp' => ''], 200);

            }

            $phone_number = $delivery_man->country_code? '+'.$delivery_man->country_code. $delivery_man->phone : $delivery_man->phone;
            SMS_module::send($phone_number, $otp);
            return response()->json(['status'=>true, 'message' => translate('OTP_sent_successfully._Please_check_your_email_or_phone'), 'otp' => strval($otp)], 200);
        }

        return response()->json(['status' => false, 'message' => translate('user_not_found'), 'otp' => ''], 404);
    }

    public function otp_verification_submit(Request $request){
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => translate('please enter otp')], 403);
        }

        $data = PasswordReset::where(['token' => $request['otp'], 'user_type'=> 'delivery_man'])->first();

        if (!$data) {
            return response()->json(['status' => false, 'message' => translate('Invalid_OTP')], 403);
        }

        $time_diff = $data->created_at->diffInMinutes(Carbon::now());

        if ($time_diff >2) {
            PasswordReset::where(['token' => $request['otp'], 'user_type'=> 'delivery_man'])->delete();

            return response()->json(['status' => false, 'message' => translate('OTP_expired')], 403);
        }

        $phone = DeliveryMan::where(['phone' => $data->identity])->pluck('phone')->first();

        return response()->json(['status' => true, 'message' => translate('OTP_verified_successfully'), 'phone'=> $phone], 200);
    }

    public function reset_password_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required|same:confirm_password|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => translate('Please enter all required fields')], 403);
        }

        DeliveryMan::where(['phone' => $request['phone']])
            ->update(['password' => bcrypt(str_replace(' ', '', $request['password']))]);

        PasswordReset::where(['identity' => $request['phone'], 'user_type'=> 'delivery_man'])->delete();

        return response()->json(['status' => true, 'message' => translate('Password_changed_successfully')], 200);

    }

    /**
     * Login OTP
     */
    public function login_otp(Request $request) {
        $mobile = $request->input('phone');
        $validator = Validator::make($request->all(), [
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false,'token' => '', 'otp' =>'', 'message' => "Please fill required field.", 'token' => "", 'data' => [], 'errors' => Helpers::error_processor($validator), 'otp' => ""], 403);
        }

        $otp = rand(111111,999999);

        $d_man = DeliveryMan::where(['phone' => $request->phone])->first();

        // if($d_man && isset($d_man->country_code) && ($d_man->country_code != $request->country_code)){
        //     $errors = [];
        //     array_push($errors, ['status' => false, 'message' => 'Invalid credential or account suspended', 'data' => [], 'errors' => []]);
        //     return response()->json([
        //         'errors' => $errors
        //     ], 404);
        // }

        if (isset($d_man) && $d_man['is_active'] == 1) {
            $token = Str::random(50);
            $d_man->auth_token = $token;
            $d_man->fcm_token = $request->fcm_id;
            $d_man->save();
            return response()->json(['status' => true, 'message' => 'OTP sent success', 'data' => [], 'otp' => strval($otp), 'errors' => [], 'token' => $token], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Invalid credential or account suspended']);
            
            $respose = ['status' => false, 'message' => 'OTP sent success','otp' =>'', 'data' => [], 'errors' => $errors, 'token' => ''];

            return response()->json($respose, 401);
        }
        
    }

    /**
     * Register OTP
     */
    public function register_otp(Request $request) {
        $mobile = $request->input('phone');
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:delivery_men'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => "Please fill required field.", 'mobile' => '', 'otp' => '', 'token' => "", 'data' => [], 'errors' => Helpers::error_processor($validator), 'otp' => ""], 403);
        }

        $otp = rand(111111,999999);

        $token = '';

        return response()->json(['status' => true, 'message' => 'OTP sent success', 'data' => [], 'mobile' => strval($mobile), 'otp' => strval($otp), 'errors' => [], 'token' => $token], 200);
    }
}
