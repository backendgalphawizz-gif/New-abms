<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\BackEndHelper;
use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function App\CPU\translate;

class CouponController extends Controller
{

    public function index(Request $request) {

        $results = Coupon::where('start_date', '<=', date('Y-m-d'))->where('expire_date', '>=', date('Y-m-d'))->where('status', 1);
        
        if($request->user()->id) {
            $results = $results->orWhere('customer_id',$request->user()->id);
        }

        $results = $results->get();

        foreach ($results as $key => $result) {
            $result->id = $result->id ?? "";
            $result->added_by = $result->added_by ?? "";
            $result->coupon_type = $result->coupon_type ?? "";
            $result->coupon_bearer = $result->coupon_bearer ?? "";
            $result->seller_id = strval($result->seller_id ?? 0);
            $result->customer_id = $result->customer_id ?? 0;

            
            $result->title = $result->title ?? "";
            $result->code = $result->code ?? "";
            $result->start_date = $result->start_date ?? "";
            $result->expire_date = $result->expire_date ?? "";
            $result->min_purchase = (int)BackEndHelper::usd_to_currency($result->min_purchase);
            $result->max_discount = (int)BackEndHelper::usd_to_currency($result->max_discount);
            $result->discount = $result->discount_type == 'amount' ? (int)BackEndHelper::usd_to_currency($result->discount) : (int)$result->discount;
            $result->discount_type = $result->discount_type ?? "";
            $result->status = $result->status ?? 1;
            $result->created_at = $result->created_at ?? "";
            $result->updated_at = $result->updated_at ?? "";
            $result->limit = $result->limit ?? 1;
        }

        $response = ['status' => true, 'message' => 'Coupon Lists', 'data' => $results ?? []];
        return response()->json($response, 200);

    }

    public function apply(Request $request)
    {
        $couponLimit = Order::where(['customer_id'=> $request->user()->id, 'coupon_code'=> $request['code']])
            ->groupBy('order_group_id')->get()->count();

        $coupon_f = Coupon::where(['code' => $request['code']])
            ->where('status',1)
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))->first();

        if(!$coupon_f){
            return response()->json(['status' => false, 'message' => translate('invalid_coupon'), 'data' => []], 202);
        }
        if($coupon_f && $coupon_f->coupon_type == 'first_order'){
            $coupon = $coupon_f;
        }else{
            $coupon = $coupon_f->limit > $couponLimit ? $coupon_f : null;
        }

        if($coupon && $coupon->coupon_type == 'first_order'){
            $orders = Order::where(['customer_id'=> $request->user()->id])->count();
            if($orders>0){
                $response = ['status' => false, 'message' => translate('sorry_this_coupon_is_not_valid_for_this_user'), 'data' => []];
                return response()->json($response, 202);
            }
        }

        if ($coupon && (($coupon->coupon_type == 'first_order') || ($coupon->coupon_type == 'discount_on_purchase' && ($coupon->customer_id == '0' || $coupon->customer_id == $request->user()->id)))) {
            $total = 0;
            foreach (CartManager::get_cart_for_api($request) as $cart) {
                if((is_null($coupon->seller_id) && $cart->seller_is=='admin') || $coupon->seller_id == '0' || ($coupon->seller_id == $cart->seller_id && $cart->seller_is=='seller')){
                    $product_subtotal = $cart['price'] * $cart['quantity'];
                    $total += $product_subtotal;
                }
            }
            if ($total >= $coupon['min_purchase']) {
                if ($coupon['discount_type'] == 'percentage') {
                    $discount = (($total / 100) * $coupon['discount']) > $coupon['max_discount'] ? $coupon['max_discount'] : (($total / 100) * $coupon['discount']);
                } else {
                    $discount = $coupon['discount'];
                }

                $response = ['status' => true, 'message' => 'Coupon Lists', 'data' => [['coupon_discount' => BackEndHelper::usd_to_currency($discount)]]];

                return response()->json($response, 200);
            }
        }elseif($coupon && $coupon->coupon_type == 'free_delivery' && ($coupon->customer_id == '0' || $coupon->customer_id == $request->user()->id)){
            $total = 0;
            $shipping_fee = 0;
            $shippingMethod=Helpers::get_business_settings('shipping_method');
            $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';

            foreach (CartManager::get_cart_for_api($request) as $cart) {
                if(($coupon->seller_id == '0' || is_null($coupon->seller_id)) || (is_null($coupon->seller_id) && $cart->seller_is=='admin') || ($coupon->seller_id == $cart->seller_id && $cart->seller_is=='seller')) {
                    $product_subtotal = $cart['price'] * $cart['quantity'];
                    $total += $product_subtotal;
                    if (is_null($coupon->seller_id) || $coupon->seller_id == '0' || $coupon->seller_id == $cart->seller_id) {
                        $shipping_fee += $cart['shipping_cost'];
                    }
                }
                if($shipping_type == 'order_wise' && ($coupon->seller_id=='0' || (is_null($coupon->seller_id) && $cart->seller_is=='admin') || ($coupon->seller_id == $cart->seller_id && $cart->seller_is=='seller'))) {
                    $shipping_fee += CartManager::get_shipping_cost($cart->cart_group_id);
                }
            }

            if ($total >= $coupon['min_purchase']) {

                $response = ['status' => true, 'message' => 'Coupon Lists', 'data' => [['coupon_discount' => $shipping_fee]]];

                return response()->json($response, 200);
            }
        }

        $response = ['status' => false, 'message' => translate('invalid_coupon'), 'data' => [['coupon_discount' => ""]]];
        return response()->json($response, 202);
    }
}
