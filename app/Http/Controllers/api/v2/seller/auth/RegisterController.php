<?php

namespace App\Http\Controllers\api\v2\seller\auth;

use App\CPU\BackEndHelper;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Plan;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function App\CPU\translate;

class RegisterController extends Controller
{

    public function store(Request $request)
    {

        $sellerArr = [
            'name' => 'required',
            'email' => 'required|unique:sellers',
            'phone' => 'required|unique:sellers',

            // 'password' => 'required',
            // 'confirm_password' => 'required',
            'address' => 'required',
            // 'city' => 'required',
            // 'state' => 'required',
            // 'pincode' => 'required',
            // 'country' => 'required',
            // 'bank_name' => 'required',
            // 'branch_name' => 'required',
            'account_type' => 'required',
            // 'bank_address' => 'required',
            // 'account_number' => 'required',
            // 'ifsc_code' => 'required',
            'otp' => 'required'
        ];

        $validator = Validator::make($request->all(), $sellerArr);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => "Please fill all required fields.",
                'token' => "",
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        DB::beginTransaction();
        try {
            $token = Str::random(50);

            $seller = new Seller();
            $seller->f_name = $request->name;
            $seller->auth_token = $token;
            $seller->l_name = "";
            $seller->phone = $request->phone;
            $seller->email = $request->email;
            // $seller->image = ImageManager::upload('seller/', 'png', $request->file('image'));
            $seller->password = bcrypt($request->password);
            $seller->status =  $request->status == 'approved' ? 'approved': "pending";
            $seller->bank_name = $request->bank_name;
            $seller->branch = $request->branch_name;
            $seller->account_type = $request->account_type;
            $seller->micr_code = $request->micr_code;
            $seller->bank_address = $request->bank_address;
            $seller->account_no = $request->account_number;
            $seller->ifsc_code = $request->ifsc_code;
            $seller->holder_name = $request->holder_name ?? $request->name;
            $seller->save();

            $shop = new Shop();
            $shop->seller_id = $seller->id;
            $shop->name = $request->shop_name ?? "";
            $shop->address = $request->address ?? "";
            $shop->contact = $request->bussiness_phone ?? "";
            $shop->email = $request->bussiness_email ?? "";
            $shop->bussiness_type = $request->bussiness_type ?? "";
            $shop->registeration_number = $request->registeration_number ?? "";
            $shop->gst_in = $request->gst_in ?? "";
            $shop->tax_identification_number = $request->tax_identification_number ?? "";
            $shop->website_link = $request->website_link ?? "";
            $shop->city = $request->city ?? "";
            $shop->state = $request->state ?? "";
            $shop->pincode = $request->pincode ?? "";
            $shop->country = $request->country ?? "";
            $shop->refferral = 'ECOM' . str_pad(date('YHis'), 10, '0', STR_PAD_LEFT);
            $shop->friends_code = $request->referral_code ?? "";
            $shop->save();

            // $shop->image = ImageManager::upload('shop/', 'png', $request->file('logo'));
            // $shop->banner = ImageManager::upload('shop/banner/', 'png', $request->file('banner'));

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
            DB::commit();

            $response = [
                'status' => true,
                'message' => translate('Vendor registeration success! wait for admin approval.'),
                'token' => $token,
                'errors' => []
            ];

            return response()->json($response, 200);

        } catch (\Exception $e) {
            DB::rollback();
            $response = [
                'status' => false,
                'message' => $e->getMessage() ,// translate('something went wrong!') ,
                'token' => "",
                'errors' => []
            ];

            return response()->json($response, 403);
        }

    }

    public function plans(Request $request) {
        $data = Helpers::get_seller_by_token($request);
        
        $months = Plan::where('user_type', 2)->where('type', 2)->orderBy('id', 'DESC')->get();
        $years = Plan::where('user_type', 2)->where('type', 3)->orderBy('id', 'DESC')->get();
        if(!empty($months)) {
            foreach ($months as $key => $mplan) {
                $mplan->is_purchased = false;
                $mplan->price_symbol = Helpers::set_symbol(BackEndHelper::usd_to_currency($mplan->price));
                $mplan->price = BackEndHelper::usd_to_currency($mplan->price);
            }
        }

        if(!empty($years)) {
            foreach ($years as $key => $yplan) {
                $yplan->is_purchased = false;
                $yplan->price_symbol = Helpers::set_symbol(BackEndHelper::usd_to_currency($yplan->price));
                $yplan->price = BackEndHelper::usd_to_currency($yplan->price);
            }
        }

        $response = [
            'status' => false,
            'message' => "Subscription plans.",
            'data' => [['monthly' => $months, 'yearly' => $years]]
        ];

        return response()->json($response, 200);
    }

}
