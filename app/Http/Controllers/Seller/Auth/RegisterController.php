<?php

namespace App\Http\Controllers\Seller\Auth;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Shop;
use App\Model\Plan;
use App\Model\Country;
use App\Model\State;
use App\Model\Area;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Session;
use function App\CPU\translate;

class RegisterController extends Controller
{
    public function create()
    {

        $business_mode = Helpers::get_business_settings('business_mode');
        $seller_registration = Helpers::get_business_settings('seller_registration');
        if ((isset($business_mode) && $business_mode == 'single') || (isset($seller_registration) && $seller_registration == 0)) {
            Toastr::warning(translate('access_denied!!'));
            return redirect('/');
        }

        Helpers::get_business_settings('web');
        $recaptcha = Helpers::get_business_settings('recaptcha');

        // $countries = Country::get();
        $states = State::where('country_id', 101)->orderBy('name', 'asc')->get();


        return view(VIEW_FILE_NAMES['seller_registration'], compact('recaptcha', 'states'));
    }

    public function validateEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:sellers|email'
        ]);

        $response = ['status' => true];
        echo json_encode($response);
        exit;
    }

    public function validateMobile(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|unique:sellers'
        ]);

        $response = ['status' => true];
        echo json_encode($response);
        exit;
    }

    public function store(Request $request)
    {

        $this->validate(
            $request,
            [
                'f_name'        => 'required',
                // 'image'         => 'required|mimes: jpg,jpeg,png,gif',
                // 'logo'          => 'required|mimes: jpg,jpeg,png,gif',
                // 'banner'        => 'required|mimes: jpg,jpeg,png,gif',
                // 'bottom_banner' => 'mimes: jpg,jpeg,png,gif',
                'email'         => 'required|unique:sellers',
                'shop_address'  => 'required',
                // 'l_name'        => 'required',
                'shop_name'     => 'required',
                'phone'         => 'required',
                // 'password'      => 'required|min:8',
            ],
            [
                'f_name.required' => 'Vendor name required'
            ]
        );

        if (false) {
            // if($request['from_submit'] != 'admin') {
            // recaptcha validation
            $recaptcha = Helpers::get_business_settings('recaptcha');
            if (isset($recaptcha) && $recaptcha['status'] == 1) {
                try {
                    $request->validate([
                        'g-recaptcha-response' => [
                            function ($attribute, $value, $fail) {
                                $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                                $response = $value;
                                $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                                $response = \file_get_contents($url);
                                $response = json_decode($response);
                                if (!$response->success) {
                                    $fail(\App\CPU\translate('ReCAPTCHA Failed'));
                                }
                            },
                        ],
                    ]);
                } catch (\Exception $exception) {
                }
            } else {
                if (strtolower($request->default_recaptcha_id_seller_regi) != strtolower(Session('default_recaptcha_id_seller_regi'))) {
                    Session::forget('default_recaptcha_id_seller_regi');
                    return back()->withErrors(\App\CPU\translate('Captcha Failed'));
                }
            }
        }

        DB::transaction(function ($r) use ($request) {

            $seller = new Seller();
            $seller->f_name = $request->f_name;
            $seller->l_name = $request->l_name ?? "";
            $seller->phone = $request->phone;
            $seller->email = $request->email;
            $seller->otp = $request->otp;
            $seller->image = ImageManager::upload('seller/', 'png', $request->file('image'));
            // $seller->password = bcrypt($request->password);
            $seller->status =  $request->status == 'approved' ? 'approved' : "pending";

            $seller->bank_name = $request->bank_name;
            $seller->branch = $request->bank_branch;
            $seller->account_type = $request->account_type;
            $seller->micr_code = $request->micr_code;
            $seller->bank_address = $request->bank_address;
            $seller->account_no = $request->account_number;
            $seller->ifsc_code = $request->ifsc_code;
            $seller->holder_name = $request->f_name;

            $seller->save();

            $shop = new Shop();
            $shop->seller_id = $seller->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->shop_address ?? "";
            $shop->contact = $request->bussiness_phone ?? '';
            $shop->email = $request->bussiness_email_id ?? '';
            $shop->image = ImageManager::upload('shop/', 'png', $request->file('logo'));
            $shop->banner = ImageManager::upload('shop/banner/', 'png', $request->file('banner'));
            $shop->bottom_banner = ImageManager::upload('shop/banner/', 'png', $request->file('bottom_banner'));


            $shop->bussiness_type = $request->bussiness_type ?? '-';
            $shop->registeration_number = $request->registeration_number ?? "";
            $shop->gst_in = $request->gst_number ?? "";
            $shop->tax_identification_number = $request->tax_identification_number ?? "";
            $shop->website_link = $request->website ?? "";
            $shop->city = $request->city ?? "";
            $shop->state = $request->state ?? "";
            $shop->pincode = $request->pincode ?? "";
            $shop->country = $request->country ?? "";
            $shop->area = $request->area ?? "";
            $shop->refferral = 'ECOM' . str_pad(date('YHis'), 10, '0', STR_PAD_LEFT);
            $shop->friends_code = $request->referral_code ?? "";

            $shop->save();

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
        });

        if ($request->status == 'approved') {
            $response = ['status' => true, 'message' => 'Vendor Register Successfully.'];
        } else {
            $response = ['status' => true, 'message' => 'Vendor Register Successfully. Wait for the admin approval.'];
        }
        return response()->json($response);
    }

    public function subscription(Request $request)
    {
        $plans = Plan::where('user_type', 2)->get();

        // dd($plans, $request->all());

        $data = $request->all();

        return view(VIEW_FILE_NAMES['seller_subscription'], compact('plans', 'data'));
    }

    // public function sendOtp(Request $request) {
    //     $phone = $request->input('phone');
    //     $otp = $request->input('otp');

    //     $seller = Seller::where('phone', $phone)->first();
    //     if(!empty($seller)) {

    //         $seller->otp = $otp;
    //         $seller->save();
    //         $response = ['status' => true, 'message' => 'OTP sent to your mobile.'];
    //     }else{
    //         $response = ['status' => false, 'message' => 'Vendor not Register.'];
    //     }
    //     return response()->json($response);
    // }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10'
        ]);

        $phone = $request->phone;
        $otp = rand(1000, 9999);

        $seller = Seller::where('phone', $phone)->first();
        if (!empty($seller)) {
            $seller->otp = $otp;
            $seller->save();


            return response()->json([
                'status' => true,
                'message' => 'OTP sent to your mobile.',
                'otp' => $otp
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Vendor not Registered.'
            ]);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'otp' => 'required|digits:4'
        ]);

        $sessionOtp = Session::get('otp_' . $request->phone);

        if ($sessionOtp && $sessionOtp == $request->otp) {
            Session::forget('otp_' . $request->phone);

            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP'
        ]);
    }


    public function loginWithOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'otp' => 'required|digits:4'
        ]);

        $phone = $request->input('phone');
        $otp = $request->input('otp');

        $seller = Seller::where('phone', $phone)->first();

        if (!empty($seller)) {
            if ($seller->otp == $otp) {  // verify OTP
                if ($seller->status == 'approved') {
                    auth('seller')->login($seller);

                    // Optionally clear OTP after successful login
                    $seller->otp = null;
                    $seller->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Vendor logged in successfully.'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Vendor registered. Wait for admin approval.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP.'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Vendor not registered.'
            ]);
        }
    }


    // public function loginWithOtp(Request $request)
    // {
    //     $phone = $request->input('phone');
    //     $otp = $request->input('otp');

    //     $seller = Seller::where('phone', $phone)->first();
    //     // if(!empty($seller)) {
    //     //     auth('seller')->login($seller);
    //     //     $response = ['status' => true, 'message' => 'Vendor Logged in success.'];
    //     // }else{
    //     //     $response = ['status' => false, 'message' => 'Vendor not Register.'];
    //     // }
    //     if (!empty($seller)) {
    //         if ($seller->status == 'approved') {
    //             auth('seller')->login($seller);
    //             $response = ['status' => true, 'message' => 'Vendor logged in successfully.'];
    //         } else {
    //             $response = ['status' => false, 'message' => 'Vendor registered. Wait for admin approval.'];
    //         }
    //     } else {
    //         $response = ['status' => false, 'message' => 'Vendor not registered.'];
    //     }

    //     return response()->json($response);
    // }
}
