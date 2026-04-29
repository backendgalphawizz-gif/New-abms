<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\BackEndHelper;
use App\CPU\CartManager;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Cart;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Seller;
use App\Model\ShippingAddress;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;
use App\Model\RefundRequest;
use App\CPU\ImageManager;
use App\Model\DeliveryMan;
use App\CPU\CustomerManager;
use App\Model\CartShipping;
use App\Model\ShippingMethod;
use App\Model\SellerSubscription;


class OrderController extends Controller
{
    use CommonTrait;
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json(OrderManager::track_order($request['order_id']), 200);
    }

    public function order_cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            $response = ['status' => false, 'message' => 'Invalid Order ID'];
            // ['errors' => Helpers::error_processor($validator)]
            return response()->json( $response, 403);
        }
        $order = Order::where(['id' => $request->order_id])->first();
        if($order) {
            // if ($order['order_status'] == 'pending') {
            OrderManager::stock_update_on_order_status_change($order, 'canceled');
            Order::where(['id' => $request->order_id])->update([
                'order_status' => 'canceled'
            ]);
            self::add_order_status_history($request->order_id, $request->user()->id, 'canceled', 'customer', $request->remarks ?? "");
            $response = ['status' => true, 'message' => translate('order_canceled_successfully')];

            if(isset($order['payment_status']) && $order['payment_status'] == 'paid') {
                CustomerManager::create_wallet_transaction($order->customer_id, Convert::default($order->order_amount), 'order_refund','order_refund');
            }

            return response()->json($response, 200);
            // }
            // $response = ['status' => false, 'message' => translate('status_not_changable_now')];
        } else {
            $response = ['status' => false, 'message' => translate('invalid_order')];
        }
        return response()->json($response, 200);
    }

    public function place_order(Request $request)
    {
        $cart_group_ids = CartManager::get_cart_group_ids($request);
        $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();

        if($request->has('group_id') && $request->input('group_id') != "") {
            // 35-XV082-1705916305
            $cart_group_ids = [$request->input('group_id')];
            $carts = Cart::where(['cart_group_id' => $request->input('group_id')])->get();
        }

        if($carts->isNotEmpty()) {

            $cartTotal = 0;
            foreach($cart_group_ids as $cart_group_id){
                $cartTotal += CartManager::cart_grand_total($cart_group_id);
            }
            $user = Helpers::get_customer($request);
            if($request->input('payment_method') == 'pay_by_wallet' && $cartTotal > $user->wallet_balance)
            {
                return response()->json(['status' => false ,'message' => 'insufficient balance in your wallet to pay for this order'], 403);
            }

            $physical_product = false;
            foreach($carts as $cart){
                if($cart->product_type == 'physical'){
                    $physical_product = true;
                }
            }
    
            if($physical_product) {
                $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');
                $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
    
                if ($request->has('billing_address_id')) {
                    $shipping_address = ShippingAddress::where(['customer_id' => $request->user()->id, 'id' => $request->input('billing_address_id')])->first();
    
                    if (!$shipping_address) {
                        $response = ['status' => false,'message' => translate('address_not_found'), 'data' => []];
                        return response()->json($response, 200);
                    } elseif ($country_restrict_status && !self::delivery_country_exist_check($shipping_address->country)) {
                        $response = ['status' => false,'message' => translate('Delivery_unavailable_for_this_country'), 'data' => []];
                        return response()->json($response, 403);
    
                    } elseif ($zip_restrict_status && !self::delivery_zipcode_exist_check($shipping_address->zip)) {
                        $response = ['status' => false,'message' => translate('Delivery_unavailable_for_this_zip_code_area'), 'data' => []];
                        return response()->json($response, 403);
                    }
                }
            }
    
            if ($request->input('payment_method') == 'cash_on_delivery') {
                
            }
    
            $unique_id = $request->user()->id . '-' . rand(000001, 999999) . '-' . time();
            $order_ids = [];
            foreach ($cart_group_ids as $group_id) {
                $data = [
                    'payment_method' => $request->input('payment_method'),
                    'order_status' => $request->input('payment_method') == 'cash_on_delivery' ? 'pending' : 'confirmed',
                    'payment_status' => $request->input('payment_method') == 'cash_on_delivery' ? 'unpaid' : 'paid', // 'unpaid',
                    'transaction_ref' => $request->input('transaction_id') ?? '',
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id,
                    'request' => $request,
                ];

                // $delivery_type = $request->input('delivery_type') ?? 0; // 0 Normal Delivery, 1 Alpha Delivery
                // $shipping_id = 2;
                // if($delivery_type == 1) {
                //     $shipping_id = 9;
                // }

                // $shipping = ShippingMethod::find($shipping_id);
                // $data['delivery_charge'] = $shipping->cost ?? 0;
                // $data['shipping_id'] = $shipping_id ?? 0;

                $subscription = SellerSubscription::where(['seller_id' => $user->id, 'type' => 1])->where('expiry_date', '>=', date('Y-m-d'))->first();
                if(!empty($subscription)) {
                    $data['delivery_charge'] = 0;
                }

                $order_id = OrderManager::generate_order($data);
    
                self::add_order_status_history($order_id, $request->user()->id, $data['payment_status']=='paid'?'confirmed':'pending', 'customer');

                $order = Order::find($order_id);
                $order->shipping_address = ($request['billing_address_id'] != null) ? $request['billing_address_id'] : $order['billing_address'];
                $order->shipping_address_data = ($request['billing_address_id'] != null) ?  ShippingAddress::find($request['billing_address_id']) : $order['billing_address_data'];
                $order->billing_address = ($request['billing_address_id'] != null) ? $request['billing_address_id'] : $order['billing_address'];
                $order->billing_address_data = ($request['billing_address_id'] != null) ?  ShippingAddress::find($request['billing_address_id']) : $order['billing_address_data'];
                $order->order_note = ($request['order_note'] != null) ? $request['order_note'] : $order['order_note'];
                $order->save();
    
                array_push($order_ids, $order_id);
            }
    
            if($request->input('payment_method') == 'pay_by_wallet') {
                CustomerManager::create_wallet_transaction($user->id, Convert::default($cartTotal), 'order_place','order payment');
            }

            if($request->has('group_id') && $request->input('group_id') != "") {
                CartShipping::whereIn('cart_group_id', $cart_group_ids)->delete();
                Cart::whereIn('cart_group_id', $cart_group_ids)->delete();
            } else {
                CartManager::cart_clean($request);
            }

    
            $response = ['status' => true,'message' => translate('order_placed_successfully'), 'data' => []];
    
            return response()->json($response, 200);
        } else {
            $response = ['status' => false,'message' => 'Empty cart. Please add product in to cart', 'data' => []];
    
            return response()->json($response, 200);
        }

    }

    public function place_order_by_offline_payment(Request $request)
    {
        $cart_group_ids = CartManager::get_cart_group_ids($request);
        $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();

        $physical_product = false;
        foreach($carts as $cart){
            if($cart->product_type == 'physical'){
                $physical_product = true;
            }
        }

        if($physical_product) {
            $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');
            $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');

            if ($request->has('billing_address_id')) {
                $shipping_address = ShippingAddress::where(['customer_id' => $request->user()->id, 'id' => $request->input('billing_address_id')])->first();

                if (!$shipping_address) {
                    return response()->json(['message' => translate('address_not_found')], 200);
                }
                elseif ($country_restrict_status && !self::delivery_country_exist_check($shipping_address->country)) {
                    return response()->json(['message' => translate('Delivery_unavailable_for_this_country')], 403);

                } elseif ($zip_restrict_status && !self::delivery_zipcode_exist_check($shipping_address->zip)) {
                    return response()->json(['message' => translate('Delivery_unavailable_for_this_zip_code_area')], 403);
                }
            }
        }

        $unique_id = $request->user()->id . '-' . rand(000001, 999999) . '-' . time();
        $order_ids = [];
        foreach ($cart_group_ids as $group_id) {
            $data = [
                'payment_method' => 'offline_payment',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'transaction_ref' => $request->transaction_ref,
                'payment_by' => $request->payment_by,
                'payment_note' => $request->payment_note,
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
                'request' => $request,
            ];
            $order_id = OrderManager::generate_order($data);

            $order = Order::find($order_id);
            $order->billing_address = ($request['billing_address_id'] != null) ? $request['billing_address_id'] : $order['billing_address'];
            $order->billing_address_data = ($request['billing_address_id'] != null) ?  ShippingAddress::find($request['billing_address_id']) : $order['billing_address_data'];
            $order->order_note = ($request['order_note'] != null) ? $request['order_note'] : $order['order_note'];
            $order->save();

            array_push($order_ids, $order_id);
        }

        CartManager::cart_clean($request);

        return response()->json(translate('order_placed_successfully'), 200);
    }

    public function place_order_by_wallet(Request $request)
    {
        $cart_group_ids = CartManager::get_cart_group_ids($request);
        $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();

        $cartTotal = 0;
        foreach($cart_group_ids as $cart_group_id){
            $cartTotal += CartManager::cart_grand_total($cart_group_id);
        }
        $user = Helpers::get_customer($request);
        if( $cartTotal > $user->wallet_balance)
        {
            return response()->json('inefficient balance in your wallet to pay for this order', 403);
        }else{
            $physical_product = false;
            foreach($carts as $cart){
                if($cart->product_type == 'physical'){
                    $physical_product = true;
                }
            }

            if($physical_product) {
                $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');
                $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');

                if ($request->has('billing_address_id')) {
                    $shipping_address = ShippingAddress::where(['customer_id' => $request->user()->id, 'id' => $request->input('billing_address_id')])->first();

                    if (!$shipping_address) {
                        return response()->json(['message' => translate('address_not_found')], 200);
                    }
                    elseif ($country_restrict_status && !self::delivery_country_exist_check($shipping_address->country)) {
                        return response()->json(['message' => translate('Delivery_unavailable_for_this_country')], 403);

                    } elseif ($zip_restrict_status && !self::delivery_zipcode_exist_check($shipping_address->zip)) {
                        return response()->json(['message' => translate('Delivery_unavailable_for_this_zip_code_area')], 403);
                    }
                }
            }


            $unique_id = $request->user()->id . '-' . rand(000001, 999999) . '-' . time();
            $order_ids = [];
            foreach ($cart_group_ids as $group_id) {
                $data = [
                    'payment_method' => 'pay_by_wallet',
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_ref' => '',
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id,
                    'request' => $request,
                ];
                $order_id = OrderManager::generate_order($data);

                $order = Order::find($order_id);
                $order->billing_address = ($request['billing_address_id'] != null) ? $request['billing_address_id'] : $order['billing_address'];
                $order->billing_address_data = ($request['billing_address_id'] != null) ?  ShippingAddress::find($request['billing_address_id']) : $order['billing_address_data'];
                $order->order_note = ($request['order_note'] != null) ? $request['order_note'] : $order['order_note'];
                $order->save();

                array_push($order_ids, $order_id);
            }

            CustomerManager::create_wallet_transaction($user->id, Convert::default($cartTotal), 'order_place','order payment');

            CartManager::cart_clean($request);

            return response()->json(translate('order_placed_successfully'), 200);
        }
    }

    public function refund_request(Request $request)
    {
        $order_details = OrderDetail::find($request->order_details_id);

        $user = $request->user();


        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        // if($loyalty_point_status == 1)
        // {
        //     $loyalty_point = CustomerManager::count_loyalty_point_for_amount($request->order_details_id);

        //     if($user->loyalty_point < $loyalty_point)
        //     {
        //         return response()->json(['message'=>translate('you have not sufficient loyalty point to refund this order!!')], 202);
        //     }
        // }

        if($order_details->delivery_status == 'delivered')
        {
            $order = Order::find($order_details->order_id);
            $total_product_price = 0;
            $refund_amount = 0;
            $data = [];
            foreach ($order->details as $key => $or_d) {
                $total_product_price += ($or_d->qty*$or_d->price) + $or_d->tax - $or_d->discount;
            }

            $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;

            $coupon_discount = ($order->discount_amount*$subtotal)/$total_product_price;

            $refund_amount = $subtotal - $coupon_discount;

            $data['product_price'] = $order_details->price;
            $data['quntity'] = $order_details->qty;
            $data['product_total_discount'] = $order_details->discount;
            $data['product_total_tax'] = $order_details->tax;
            $data['subtotal'] = $subtotal;
            $data['coupon_discount'] = $coupon_discount;
            $data['refund_amount'] = $refund_amount;

            $refund_day_limit = Helpers::get_business_settings('refund_day_limit');
            $order_details_date = $order_details->created_at;
            $current = \Carbon\Carbon::now();
            $length = $order_details_date->diffInDays($current);
            $expired = false;
            $already_requested = false;
            if($order_details->refund_request != 0)
            {
                $already_requested = true;
            }
            if($length > $refund_day_limit )
            {
                $expired = true;
            }

            OrderManager::stock_update_on_order_status_change($order, 'returned');
            Order::where(['id' => $request->order_id])->update([
                'order_status' => 'returned'
            ]);
            self::add_order_status_history($request->order_id, $request->user()->id, 'returned', 'customer', $request->remarks ?? "");

            return response()->json(['already_requested'=>$already_requested,'expired'=>$expired,'refund'=>$data], 200);
        }else{
            return response()->json(['message'=>translate('You_can_request_for_refund_after_order_delivered')], 200);
        }

    }

    public function store_refund(Request $request)
    {

        $order_details = OrderDetail::where('order_id', $request->order_id)->first();
        // $order_details = OrderDetail::find($request->order_details_id);

        $user = $request->user();


        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        // if($loyalty_point_status == 1)
        // {
        //     $loyalty_point = CustomerManager::count_loyalty_point_for_amount($request->order_details_id);

        //     if($user->loyalty_point < $loyalty_point)
        //     {
        //         $response = ['status' => false, 'message' => translate('you have not sufficient loyalty point to refund this order!!'), 'data' => []];

        //         return response()->json($response, 200);
        //     }
        // }

        if($order_details->refund_request == 0){

            $validator = Validator::make($request->all(), [
                // 'order_details_id' => 'required',
                // 'amount' => 'required',
                'refund_reason' => 'required'

            ]);
            if ($validator->fails()) {
                $response = ['status' => false, 'message' => translate('Refund reason required'), 'data' => []];

                // ['errors' => Helpers::error_processor($validator)]
                return response()->json($response, 403);
            }
            $refund_request = new RefundRequest;
            $refund_request->order_details_id = $order_details->id; // $request->order_details_id;
            $refund_request->customer_id = $request->user()->id;
            $refund_request->status = 'pending';
            $refund_request->amount = $request->amount;
            $refund_request->product_id = $order_details->product_id;
            $refund_request->order_id = $order_details->order_id;
            $refund_request->refund_reason = $request->refund_reason;

            if ($request->file('images')) {
                foreach ($request->file('images') as $img) {
                    $product_images[] = ImageManager::upload('refund/', 'png', $img);
                }
                $refund_request->images = json_encode($product_images);
            }
            $refund_request->save();
            $order = Order::find($request->order_id);
            OrderManager::stock_update_on_order_status_change($order, 'returned');
            Order::where(['id' => $request->order_id])->update([
                'order_status' => 'returned'
            ]);
            self::add_order_status_history($request->order_id, $request->user()->id, 'returned', 'customer', $request->remarks ?? "");

            $order_details->refund_request = 1;
            $order_details->save();
            $response = ['status' => true, 'message' => translate('refunded_request_updated_successfully!!'), 'data' => []];

            return response()->json($response, 200);
        }else{
            $response = ['status' => false, 'message' => translate('already_applied_for_refund_request!!'), 'data' => []];
            return response()->json($response, 302);
        }

    }
    
    public function get_refund_lists(Request $request) {
        
        $refund_requests = RefundRequest::where('customer_id', $request->user()->id)->get();

        $refund_requests = Helpers::set_refund_request_data($refund_requests);

        $response = ['status' => true, 'message' => 'Refund Request lists', 'data' => $refund_requests];

        return response()->json($response, 200);

    }

    public function refund_details(Request $request)
    {
        $order_details = OrderDetail::find($request->id);
        $refund = RefundRequest::where('customer_id',$request->user()->id)
                                ->where('order_details_id',$order_details->id )->get();
        $refund = $refund->map(function($query){
            $query['images'] = $query['images'] != "" ? json_decode($query['images']) : "";
            $query['approved_note'] = $query['approved_note'] ?? "";
            $query['rejected_note'] = $query['rejected_note'] ?? "";
            $query['payment_info'] = $query['payment_info'] ?? "";
            $query['change_by'] = $query['change_by'] ?? "";
            $query['amount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($query['amount'] ?? 0));
            return $query;
        });

        $order = Order::find($order_details->order_id);

        $total_product_price = 0;
        $refund_amount = 0;
        $data = [];
        foreach ($order->details as $key => $or_d) {
            $total_product_price += ($or_d->qty*$or_d->price) + $or_d->tax - $or_d->discount;
        }

        $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;

        $coupon_discount = ($order->discount_amount*$subtotal)/$total_product_price;

        $refund_amount = $subtotal - $coupon_discount;

        $data['product_price'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order_details->price));
        $data['quntity'] = $order_details->qty;
        $data['product_total_discount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order_details->discount));
        $data['product_total_tax'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order_details->tax));
        $data['subtotal'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($subtotal));
        $data['coupon_discount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($coupon_discount));
        $data['refund_amount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($refund_amount));
        $data['refund_request'] = $refund;

        $response = ['status' => true, 'message' => 'Refund request Details', 'data' => $data];

        return response()->json($response, 200);
    }

    public function digital_product_download(Request $request, $id)
    {
        $order_data = OrderDetail::with('order.customer')->find($id);
        $customer_id = $request->user()->id;
        if($order_data->order->customer->id != $customer_id){
            return response()->json(['message'=>translate('Invalid customer')], 202);
        }

        if( $order_data->product->digital_product_type == 'ready_product' && $order_data->product->digital_file_ready) {
            $file_path = storage_path('app/public/product/digital-product/' .$order_data->product->digital_file_ready);
        }else{
            $file_path = storage_path('app/public/product/digital-product/' . $order_data->digital_file_after_sell);
        }

        return \response()->download($file_path);
    }

    public function payment_method(Request $request) {

        // $payment_methods = [];
        // $payment_methods[] = [
        //     'title' => 'RazorPay',
        //     'slug' => 'razorpay',
        //     'details' => Helpers::get_business_settings('razor_pay')
        // ];

        // $payment_methods[] = [
        //     'title' => 'Cash On Delivery',
        //     'slug' => 'cash_on_delivery',
        //     'details' => Helpers::get_business_settings('cash_on_delivery')
        // ];

        // $response = [
        //     'status' => true,
        //     'message' => 'Payment Method List',
        //     'data' => [
        //         $payment_methods
        //     ]
        // ];
        // return response()->json($response, 200);
    }

    public function check_pincode(Request $request) {
        $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');
        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');

        if ($request->has('billing_address_id')) {
            $shipping_address = ShippingAddress::where(['customer_id' => $request->user()->id, 'id' => $request->input('billing_address_id')])->first();

            if (!$shipping_address) {
                $response = ['status' => false,'message' => translate('address_not_found'), 'data' => []];
                return response()->json($response, 200);
            } elseif ($country_restrict_status && !self::delivery_country_exist_check($shipping_address->country)) {
                $response = ['status' => false,'message' => translate('Delivery_unavailable_for_this_country'), 'data' => []];
                return response()->json($response, 403);

            } elseif ($zip_restrict_status && !self::delivery_zipcode_exist_check($shipping_address->zip)) {
                $response = ['status' => false,'message' => translate('Delivery_unavailable_for_this_zip_code_area'), 'data' => []];
                return response()->json($response, 403);
            }
        }

        $cartProducts = Cart::where('customer_id', $request->user()->id)->count();

        $availProducts = Cart::where('customer_id', $request->user()->id)
            ->whereHas('product', function ($query) {
                $query->where('current_stock', '>=', 1);
            })->count();
        if($availProducts < $cartProducts) {
            $response = ['status' => false,'message' => translate('Some products out of stock'), 'data' => []];
            return response()->json($response, 403);
        }

        $response = ['status' => true,'message' => translate('Delivery_available_for_this_zip_code_area'), 'data' => []];
        return response()->json($response, 403);
    }

}
