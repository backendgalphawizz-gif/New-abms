<?php

namespace App\Http\Controllers\api\v2\seller\auth;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\SellerWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function App\CPU\translate;
use Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Please fill required fields', 'token' => "",  'errors' => Helpers::error_processor($validator)], 403);
        }

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $seller = Seller::where(['email' => $request['email']])->first();
        if (isset($seller) && $seller['status'] == 'approved' && auth('seller')->attempt($data)) {
            $token = Str::random(50);
            Seller::where(['id' => auth('seller')->id()])->update(['auth_token' => $token]);
            if (SellerWallet::where('seller_id', $seller['id'])->first() == false) {
                DB::table('seller_wallets')->insert([
                    'seller_id' => $seller['id'],
                    'withdrawn' => 0,
                    'commission_given' => 0,
                    'total_earning' => 0,
                    'pending_withdraw' => 0,
                    'delivery_charge_earned' => 0,
                    'collected_cash' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            return response()->json(['status' => true, 'message' => 'Login success', 'token' => $token,  'errors' => []], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => translate('Invalid credential or account not verified yet')]);
            return response()->json([
                'status' => false,
                'message' => translate('Invalid credential or account not verified yet'),
                'token' => '',
                'errors' => $errors
            ], 401);
        }
    }

    /**
     * Register OTP
     */
    public function send_otp(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:sellers'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => "Please fill required field.", 'token' => "", 'data' => [], 'errors' => Helpers::error_processor($validator), 'otp' => ""], 403);
        }

        $otp = rand(1111,9999);

        // $seller = Seller::where(['phone' => $request['phone']])->first();

        if (true) {
            $token = Str::random(50);

            return response()->json(['status' => true, 'message' => 'OTP sent success', 'token' => $token, 'errors' => [], 'data' => [],'otp' => strval($otp)], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => translate('Invalid credential or account not verified yet')]);
            return response()->json([
                'status' => false, 
                'message' => translate('Invalid credential or account not verified yet'), 
                'token' => "",
                'errors' => $errors,
                'data' => [],'otp' => ""
            ], 401);
        }
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
            return response()->json(['status' => false, 'message' => "Please fill required field.", 'token' => "", 'data' => [], 'errors' => Helpers::error_processor($validator), 'otp' => ""], 403);
        }

        $otp = rand(1111,9999);

        $seller = Seller::where(['phone' => $request['phone'], 'status' => 'approved'])->first();

        if (!empty($seller)) {
            $token = Str::random(50);
            $token = Str::random(50);
            Seller::where(['id' => $seller->id])->update(['auth_token' => $token, 'cm_firebase_token' => $request['fcm_id']]);
            return response()->json(['status' => true, 'message' => 'OTP sent success', 'token' => $token, 'errors' => [], 'data' => [],'otp' => strval($otp)], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => translate('Invalid credential or account not verified yet')]);
            return response()->json([
                'status' => false, 
                'message' => translate('Invalid credential or account not verified yet'), 
                'token' => "",
                'errors' => $errors,
                'data' => [],
                'otp' => ""
            ], 401);
        }
    }

}
