<?php

namespace App\Http\Controllers\Customer\Auth;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\SMS_module;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\ProductCompare;
use App\Model\Wishlist;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Session;
use Gregwar\Captcha\PhraseBuilder;

class LoginController extends Controller
{
    public $company_name;

    public function __construct()
    {
        // Captcha must be loadable in <img> (GET) even if session state is odd; return image bytes, not a redirect.
        $this->middleware('guest:customer', ['except' => ['logout', 'captcha']]);
    }

    /**
     * Update only existing columns on users table (tenant schemas may differ).
     */
    private function updateUserColumnsIfExist(User $user, array $values): void
    {
        $connection = $user->getConnectionName() ?: config('database.default');
        $table = $user->getTable();
        $schema = Schema::connection($connection);

        $filtered = [];
        foreach ($values as $column => $value) {
            if ($schema->hasColumn($table, $column)) {
                $filtered[$column] = $value;
            }
        }

        if ($schema->hasColumn($table, 'updated_at')) {
            $filtered['updated_at'] = now();
        }

        if (empty($filtered)) {
            return;
        }

        $user->newQuery()->whereKey($user->getKey())->update($filtered);
        $user->forceFill($filtered);
    }

    public function captcha(Request $request, $tmp)
    {
        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build(100, 40, null);
        $phrase = $builder->getPhrase();

        $sessionKey = $request->input('captcha_session_id');
        if (!is_string($sessionKey) || $sessionKey === '') {
            $sessionKey = 'default_captcha';
        }
        if (Session::has($sessionKey)) {
            Session::forget($sessionKey);
        }
        Session::put($sessionKey, $phrase);

        // Return binary response so <img> receives image/jpeg, not HTML from stray output.
        return response($builder->get(90), 200)
            ->header('Content-Type', 'image/jpeg')
            ->header('Cache-Control', 'no-cache, must-revalidate');
    }

    public function login(Request $request)
    {
        session()->put('keep_return_url', url()->previous());
        if ($request->boolean('cancel_otp')) {
            session()->forget([
                'web_customer_login_otp',
                'web_customer_login_phone',
                'web_customer_login_otp_expires_at',
            ]);
        }

        return view('customer-view.auth.login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'password' => 'required'
        ]);

        //recaptcha validation start
        $recaptcha = Helpers::get_business_settings('recaptcha');
        // if (isset($recaptcha) && $recaptcha['status'] == 1) {
        //     try {
        //         $request->validate([
        //             'g-recaptcha-response' => [
        //                 function ($attribute, $value, $fail) {
        //                     $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
        //                     $response = $value;
        //                     $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
        //                     $response = \file_get_contents($url);
        //                     $response = json_decode($response);
        //                     if (!$response->success) {
        //                         $fail(\App\CPU\translate('ReCAPTCHA Failed'));
        //                     }
        //                 },
        //             ],
        //         ]);
        //     } catch (\Exception $exception) {}
        // } else {
        //     if (strtolower($request->default_recaptcha_id_customer_login) != strtolower(Session('default_recaptcha_id_customer_login'))) {
        //         if($request->ajax()) {
        //             return response()->json([
        //                 'status'=>'error',
        //                 'message'=>translate('Captcha_Failed.'),
        //                 'redirect_url'=>''
        //             ]);
        //         }else {
        //             Session::forget('default_recaptcha_id_customer_login');
        //             Toastr::error('Captcha Failed.');
        //             return back();
        //         }
        //     }
        // }
        //recaptcha validation end

        $user = User::where(['phone' => $request->user_id])->orWhere(['email' => $request->user_id])->first();
        $remember = ($request['remember']) ? true : false;

        // login attempt check start
        $max_login_hit = Helpers::get_business_settings('maximum_login_hit') ?? 5;
        $temp_block_time = Helpers::get_business_settings('temporary_login_block_time') ?? 5; //seconds
        if (isset($user) == false) {
            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('credentials_do_not_match_or_account_has_been_suspended'),
                    'redirect_url'=>''
                ]);
            }else{
                Toastr::error(translate('credentials_do_not_match_or_account_has_been_suspended'));
                return back()->withInput();
            }
        }
        //login attempt check end

        //phone or email verification check start
        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('account_phone_not_verified'),
                    'redirect_url'=>route('customer.auth.check', [$user->id]),
                ]);
            }else{
                return redirect(route('customer.auth.check', [$user->id]));
            }
        }
        if ($email_verification && !$user->is_email_verified) {
            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('account_email_not_verified'),
                    'redirect_url'=>route('customer.auth.check', [$user->id]),
                ]);
            }else{
                return redirect(route('customer.auth.check', [$user->id]));
            }
        }
        //phone or email verification check end

        if(isset($user->temp_block_time ) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time){
            $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();

            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url'=>''
                ]);
            }else{
                Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                return back()->withInput();
            }
        }

        if (isset($user) && $user->is_active && auth('customer')->attempt(['email' => $user->email, 'password' => $request->password], $remember)) {
            $cid = (int) auth('customer')->id();
            session()->put('wish_list', Wishlist::productIdsForCustomerId($cid));
            session()->put('compare_list', ProductCompare::productIdsForUserId($cid));
            Toastr::info('Welcome to ' . Helpers::get_business_settings('company_name') . '!');
            CartManager::cart_to_db();

            $this->updateUserColumnsIfExist($user, [
                'login_hit_count' => 0,
                'is_temp_blocked' => 0,
                'temp_block_time' => null,
            ]);

            if($request->ajax()) {
                return response()->json([
                    'status'=>'success',
                    'message'=>translate('login_successful'),
                    'redirect_url'=>'samepage',
                ]);
            }else{
                $target = session('keep_return_url');
                if (! is_string($target) || $target === '') {
                    $target = route('portal.home');
                }

                return redirect()->to($target);
            }

        }else{

            //login attempt check start
            if(isset($user->temp_block_time ) && Carbon::parse($user->temp_block_time)->diffInSeconds() <= $temp_block_time){
                $time= $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url'=>''
                ];
                Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());

            }elseif($user->is_temp_blocked == 1 && Carbon::parse($user->temp_block_time)->diffInSeconds() >= $temp_block_time){

                $this->updateUserColumnsIfExist($user, [
                    'login_hit_count' => 0,
                    'is_temp_blocked' => 0,
                    'temp_block_time' => null,
                ]);

                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('credentials_do_not_match_or_account_has_been_suspended'),
                    'redirect_url'=>''
                ];
                Toastr::error(translate('credentials_do_not_match_or_account_has_been_suspended'));

            }elseif($user->login_hit_count >= $max_login_hit &&  $user->is_temp_blocked == 0){
                $this->updateUserColumnsIfExist($user, [
                    'is_temp_blocked' => 1,
                    'temp_block_time' => now(),
                ]);

                $time= $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('too_many_attempts. please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url'=>''
                ];
                Toastr::error(translate('too_many_attempts. please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans());
            }else{
                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('credentials_do_not_match_or_account_has_been_suspended'),
                    'redirect_url'=>''
                ];
                Toastr::error(translate('credentials_do_not_match_or_account_has_been_suspended'));

                $this->updateUserColumnsIfExist($user, [
                    'login_hit_count' => ((int) ($user->login_hit_count ?? 0)) + 1,
                ]);
            }
            //login attempt check end

            if($request->ajax()) {
                return response()->json($ajax_message);
            }else{
                return back()->withInput();
            }
        }
    }

    public function logout(Request $request)
    {
        auth()->guard('customer')->logout();
        session()->forget('wish_list');
        Toastr::info('Come back soon, ' . '!');
        return redirect()->route('home');
    }

    public function send_otp(Request $request) {
        $mobile = $request->input('phone');
        $otp = rand(1111, 9999);

        $request->validate([
            'phone' => 'required|unique:users|min:10|max:10'
        ]);

        $response = ['status' => true, 'message' => 'OTP sent success', 'data' => ['otp' => $otp, 'mobile' => $mobile]];
        return response()->json($response);
    }

    public function send_login_otp(Request $request) {
        $mobile = $request->input('phone');
        $otp = rand(1111, 9999);
        $request->validate([
            'phone' => 'required'
        ]);
        
        $user = User::where(['phone' => $request->phone])->first();
        if(!empty($user)) {
            $response = ['status' => true, 'message' => 'OTP sent success', 'data' => ['otp' => $otp, 'mobile' => $mobile]];
        } else {
            $response = ['status' => false, 'message' => 'Invalid Mobile Or Not registered', 'data' => ['otp' => $otp, 'mobile' => $mobile]];
        }

        return response()->json($response);
    }

    public function login_with_otp(Request $request) {
        $user = User::where(['phone' => $request->phone])->orWhere(['email' => $request->phone])->first();

        // login attempt check start
        $max_login_hit = Helpers::get_business_settings('maximum_login_hit') ?? 5;
        $temp_block_time = Helpers::get_business_settings('temporary_login_block_time') ?? 5; //seconds
        if (isset($user) == false) {
            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('credentials_do_not_match_or_account_has_been_suspended'),
                    'redirect_url'=>''
                ]);
            }else{
                Toastr::error(translate('credentials_do_not_match_or_account_has_been_suspended'));
                return back()->withInput();
            }
        }
        //login attempt check end

        //phone or email verification check start
        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('account_phone_not_verified'),
                    'redirect_url'=>route('customer.auth.check', [$user->id]),
                ]);
            }else{
                return redirect(route('customer.auth.check', [$user->id]));
            }
        }
        if ($email_verification && !$user->is_email_verified) {
            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('account_email_not_verified'),
                    'redirect_url'=>route('customer.auth.check', [$user->id]),
                ]);
            }else{
                return redirect(route('customer.auth.check', [$user->id]));
            }
        }
        //phone or email verification check end

        if(isset($user->temp_block_time ) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time){
            $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();

            if($request->ajax()) {
                return response()->json([
                    'status'=>'error',
                    'message'=>translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url'=>''
                ]);
            }else{
                Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                return back()->withInput();
            }
        }

        if (isset($user) && $user->is_active) {
            auth('customer')->login($user);
            $cid = (int) auth('customer')->id();
            session()->put('wish_list', Wishlist::productIdsForCustomerId($cid));
            session()->put('compare_list', ProductCompare::productIdsForUserId($cid));
            Toastr::info('Welcome to ' . Helpers::get_business_settings('company_name') . '!');
            CartManager::cart_to_db();

            $this->updateUserColumnsIfExist($user, [
                'login_hit_count' => 0,
                'is_temp_blocked' => 0,
                'temp_block_time' => null,
            ]);

            if($request->ajax()) {
                return response()->json([
                    'status'=>'success',
                    'message'=>translate('login_successful'),
                    'redirect_url'=>'samepage',
                ]);
            }else{
                $target = session('keep_return_url');
                if (! is_string($target) || $target === '') {
                    $target = route('portal.home');
                }

                return redirect()->to($target);
            }

        }else{

            //login attempt check start
            if(isset($user->temp_block_time ) && Carbon::parse($user->temp_block_time)->diffInSeconds() <= $temp_block_time){
                $time= $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url'=>''
                ];
                Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());

            }elseif($user->is_temp_blocked == 1 && Carbon::parse($user->temp_block_time)->diffInSeconds() >= $temp_block_time){

                $this->updateUserColumnsIfExist($user, [
                    'login_hit_count' => 0,
                    'is_temp_blocked' => 0,
                    'temp_block_time' => null,
                ]);

                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('credentials_do_not_match_or_account_has_been_suspended') ,
                    'redirect_url'=>''
                ];
                Toastr::error(translate('credentials_do_not_match_or_account_has_been_suspended'));

            }elseif($user->login_hit_count >= $max_login_hit &&  $user->is_temp_blocked == 0){
                $this->updateUserColumnsIfExist($user, [
                    'is_temp_blocked' => 1,
                    'temp_block_time' => now(),
                ]);

                $time= $temp_block_time - Carbon::parse($user->temp_block_time)->diffInSeconds();

                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('too_many_attempts. please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url'=>''
                ];
                Toastr::error(translate('too_many_attempts. please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans());
            }else{
                $ajax_message = [
                    'status'=>'error',
                    'message'=> translate('credentials_do_not_match_or_account_has_been_suspended') . ' 3333333',
                    'redirect_url'=>''
                ];
                Toastr::error(translate('credentials_do_not_match_or_account_has_been_suspended'));

                $this->updateUserColumnsIfExist($user, [
                    'login_hit_count' => ((int) ($user->login_hit_count ?? 0)) + 1,
                ]);
            }
            //login attempt check end

            if($request->ajax()) {
                return response()->json($ajax_message);
            }else{
                return back()->withInput();
            }
        }
    }

    /**
     * Web login step 1: send a 4-digit OTP (session + optional SMS) for the given mobile.
     */
    public function loginRequestOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^[0-9]{10}$/'],
        ]);

        $user = User::where('phone', $request->phone)->first();
        if (! $user) {
            Toastr::error(translate('credentials_do_not_match_or_account_has_been_suspended'));

            return back()->withInput();
        }
        if (! $user->is_active) {
            Toastr::error(translate('credentials_do_not_match_or_account_has_been_suspended'));

            return back()->withInput();
        }

        $otp = (string) random_int(1000, 9999);
        session([
            'web_customer_login_otp' => $otp,
            'web_customer_login_phone' => $request->phone,
            'web_customer_login_otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            SMS_module::send($request->phone, $otp);
        } catch (\Throwable $e) {
        }

        if (config('app.debug')) {
            Toastr::info('OTP (dev only): '.$otp);
        } else {
            Toastr::success(translate('OTP_sent_successfully'));
        }

        return redirect()->route('customer.login.short');
    }

    /**
     * Web login step 2: verify OTP from session, then complete login (same rules as login_with_otp).
     */
    public function loginVerifyOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^[0-9]{10}$/'],
            'otp' => ['required', 'digits:4'],
        ]);

        $phone = $request->phone;
        $sessPhone = session('web_customer_login_phone');
        $sessOtp = session('web_customer_login_otp');
        $expires = session('web_customer_login_otp_expires_at');

        if (! $sessPhone || $sessPhone !== $phone || ! $sessOtp || ! $expires) {
            Toastr::error(translate('Verification code/ OTP mismatched'));

            return redirect()->route('customer.login.short');
        }

        if (now()->gt($expires)) {
            session()->forget(['web_customer_login_otp', 'web_customer_login_phone', 'web_customer_login_otp_expires_at']);
            Toastr::error(translate('auth_login_otp_expired'));

            return redirect()->route('customer.login.short');
        }

        if ($request->otp !== $sessOtp) {
            Toastr::error(translate('Verification code/ OTP mismatched'));

            return back()->withInput();
        }

        session()->forget(['web_customer_login_otp', 'web_customer_login_phone', 'web_customer_login_otp_expires_at']);

        $request->merge(['phone' => $phone]);

        return $this->login_with_otp($request);
    }

}
