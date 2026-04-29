<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\BackEndHelper;
use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\CartShipping;
use App\Model\Coupon;
use App\Model\Order;
use App\Model\Product;
use App\Model\ShippingMethod;
use App\Model\SellerSubscription;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

class CartController extends Controller
{

    public function cart(Request $request)
    {
        $user = Helpers::get_customer($request);

        $carts = Cart::where(['customer_id' => $user->id])->get();
        if($request->has('group_id') && $request->input('group_id')) {
            // 35-XV082-1705916305
            $carts = Cart::where(['customer_id' => $user->id, 'cart_group_id' => $request->input('group_id')])->get();
        }

        
        
        if($carts) {
            $products = [];
            $subtotal = 0;
            $couponLimit = Order::where(['customer_id'=> $user->id, 'coupon_code'=> $request['code']])
            ->groupBy('order_group_id')->get()->count();
            $couponAmount = 0;
            $coupon_process = array(
                'discount' => 0,
                'coupon_bearer' => 'inhouse',
                'coupon_code' => 0,
            );
            if((isset($request->coupan) && $request->coupan)){
                $coupon_code = $request->coupan ?? "";
                $coupon_f = Coupon::where(['code' => $coupon_code])
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
        
                        // $response = ['status' => true, 'message' => 'Coupon Lists', 'data' => [['coupon_discount' => BackEndHelper::usd_to_currency($discount)]]];
                        $couponAmount = $discount;
                        // return response()->json($response, 200);
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
        
                        $couponAmount = $shipping_fee;
                    }
                }
            }

            $delivery_type = $request->input('delivery_type') ?? 0; // 0 Normal Delivery, 1 Alpha Delivery
            $shipping_id = 2;
            if($delivery_type == 1) {
                $shipping_id = 9;                
            }
            $cart_group_ids = CartManager::get_cart_group_ids($request);
            if($request->has('group_id') && $request->input('group_id')) {
                $cart_group_ids = [$request->input('group_id')];
                // $cart_group_ids = Cart::where(['customer_id' => $user->id, 'cart_group_id' => $request->input('group_id')])->get();
            }

            $shipping = ShippingMethod::find($shipping_id);
            $delivery_charge = $shipping->cost ?? 0;

            $subscription = SellerSubscription::where(['seller_id' => $user->id, 'type' => 1])->where('expiry_date', '>=', date('Y-m-d'))->first();
            if(!empty($subscription)) {
                $delivery_charge = 0;
            }

            CartShipping::whereIn('cart_group_id', $cart_group_ids)->update(['shipping_method_id' => $shipping_id, 'shipping_cost' => $delivery_charge]);

            $tax = 0;
            $tax_amount = 0;
            $total = 0;
            $mrp = 0;
            $discount = 0;
            $discount_text = "";
            foreach($carts as $key => $value){
                $product = Product::where(['id' => $value->product_id])->first();
                $productPrice = $value->price;
                $mrp += $value->price * $value->quantity;

                $p_discount = 0;
                if($product->discount > 0) {
                    if($product->discount_type == 'percent') {
                        $p_discount = ($productPrice * ($product->discount)) / 100;
                    } else {
                        $p_discount = ($value->discount); // 15
                    }
                }

                $discount += ($p_discount * $value->quantity);

                
                
                $subtotal += $productPrice * $value->quantity;
                $tax_amount += 0;
                $tm = 0;
                if($product->tax_model == 'exclude' && $product->tax_type == 'percent') {
                    $tax_amount += ($productPrice * $product->tax) / 100;
                    $tm = ($productPrice * $product->tax) / 100;
                }

                $productPrice = ($productPrice + $tm);
                
                if (isset($product)) {
                    $product1 = Helpers::product_data_formatting($product, false, $user->id);
                }
                $product1['selected_quantity'] = strval($value->quantity);
                $product1['special_price'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($value->price - $value->discount));
                
                $product1['choices'] = $value->choices != "[]" ? [json_decode($value->choices)] : [];
                $product1['variations'] = $value->variations != "[]" ? [json_decode($value->variations)] : [];
                
                $product1['variant'] = $value->variant ?? "";
                $product1['color'] = $value->color ?? "";
                $product1['cart_id'] = strval($value->id);

                
                $products[] = $product1;
                
                $value->product = $product1;

            }

            if($discount > 0) {
                $discount_text = "You will save " . Helpers::currency_converter($discount + $couponAmount) . " in this Order";
            }
        }

        $response = [
            'status' => true,
            'message' => 'Cart List',
            'data' => [
                'delivery_type' => $delivery_type,
                'discount_text' => $discount_text,
                'total_items' => strval(count($products)),
                'subtotal' => strval(BackEndHelper::usd_to_currency(($subtotal - $couponAmount - $discount) + $delivery_charge + $tax_amount)),
                'mrp' => Helpers::currency_converter($mrp),
                'discount' => Helpers::currency_converter($discount),
                'price' => Helpers::currency_converter(($subtotal - $discount)),
                'delivery_charge' => Helpers::currency_converter($delivery_charge),
                'tax' => Helpers::currency_converter($tax_amount),
                'coupon_discount' => Helpers::currency_converter($couponAmount),
                'total' => Helpers::currency_converter(($subtotal - $couponAmount - $discount) + $delivery_charge + $tax_amount),
                'products' => $products
            ]
        ];

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

    public function buy_now(Request $request) {
        $user = Helpers::get_customer($request);
        $carts = Cart::where(['customer_id' => $user->id])->get();

        if($carts) {
            $products = [];
            $subtotal = 0;
            $delivery_type = $request->input('delivery_type') ?? 0; // 0 Normal Delivery, 1 Alpha Delivery
            $shipping_id = 2;
            if($delivery_type == 1) {
                $shipping_id = 9;
            }

            $shipping = ShippingMethod::find($shipping_id);
            $delivery_charge = $shipping->cost ?? 0;

            $tax = 0;
            $tax_amount = 0;
            $total = 0;
            $mrp = 0;
            $discount = 0;
            $discount_text = "";
            // foreach($carts as $key => $value){
                $product = Product::where(['id' => $request->product_id])->first();
                $productPrice = $product->unit_price;
                $mrp += $product->unit_price * $request->quantity;

                $p_discount = 0;
                if($product->discount > 0) {
                    if($product->discount_type == 'percent') {
                        $p_discount = ($productPrice * ($product->discount)) / 100;
                    } else {
                        $p_discount = ($product->discount); // 15
                    }
                }

                $discount += ($p_discount * $request->quantity);

                $subtotal += ($productPrice) * $request->quantity;
                $tax_amount += 0;
                $tm = 0;
                if($product->tax_model == 'exclude' && $product->tax_type == 'percent') {
                    $tax_amount += ($productPrice * $product->tax) / 100;
                    $tm = ($productPrice * $product->tax) / 100;
                }

                $productPrice = ($productPrice + $tm);
                if (isset($product)) {
                    $product1 = Helpers::product_data_formatting($product, false, $user->id);
                }
                $product1['selected_quantity'] = strval($request->quantity);
                $product1['special_price'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($productPrice - $discount));

                $product1['choices'] = $product->choices != "[]" ? [json_decode($product->choices)] : [];
                $product1['variations'] = $product->variations != "[]" ? [json_decode($product->variations)] : [];

                $product1['variant'] = $product->variant ?? "";
                $product1['color'] = $product->color ?? "";
                $product1['cart_id'] = strval($product->id);

                $products[] = $product1;
                
                // $product->product = $product1;

            // }

            if($discount > 0) {
                $discount_text = "You will save " . Helpers::currency_converter($discount) . " in this Order";
            }
        }

        $response = [
            'status' => true,
            'message' => 'Cart List',
            'data' => [
                'delivery_type' => $delivery_type,
                'discount_text' => $discount_text,
                'total_items' => strval(count($products)),
                'subtotal' => Helpers::currency_converter($subtotal),
                'discount' => Helpers::currency_converter($discount),
                'mrp' => Helpers::currency_converter($mrp),
                'delivery_charge' => Helpers::currency_converter($delivery_charge),
                'tax' => Helpers::currency_converter($tax_amount),
                'total' => Helpers::currency_converter(($subtotal - $discount) + $delivery_charge + $tax_amount),
                'products' => $products
            ]
        ];

        return response()->json($response, 200);
    }

    public function add_to_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'quantity' => 'required',
        ], [
            'id.required' => translate('Product ID is required!')
        ]);

        if ($validator->errors()->count() > 0) {

            $response = [
                'status' => false,
                'message' => 'Something went wrong',
                'group_id' => "",
                'data' => []
            ];
    
            return response()->json($response, 200);

            // return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $cart = CartManager::add_to_cart($request);

        if($cart['status'] == 1) {
            $response = [
                'status' => true,
                'message' => $cart['message'],
                'group_id' => $request->has('is_cart') && isset($cart['group_id']) ? $cart['group_id'] : "",
                'data' => []
            ];
        } else {
            $response = [
                'status' => false,
                'message' => $cart['message'],
                'group_id' => $request->has('is_cart') && isset($cart['group_id']) ? $cart['group_id'] : "",
                'data' => []
            ];
        }


        return response()->json($response, 200);

        return response()->json($cart, 200);
    }

    public function update_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'quantity' => 'required',
        ], [
            'key.required' => translate('Cart key or ID is required!')
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $stat = CartManager::update_cart_qty($request);

        $response = [
            'status' => $stat['status'] == 1 ? true : false,
            'message' => $stat['message'],
            'data' => []
        ];

        return response()->json($response);
    }

    public function remove_from_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required'
        ], [
            'key.required' => translate('Cart key or ID is required!')
        ]);

        if ($validator->errors()->count() > 0) {

            $response = [
                'status' => false,
                'message' => "Something went wrong",
                'data' => []
            ];

            return response()->json($response);
        }

        $user = Helpers::get_customer($request);

        Cart::where(['id' => $request->key, 'customer_id' => $user->id])->delete();

        $response = [
            'status' => true,
            'message' => translate('successfully_removed'),
            'data' => []
        ];

        return response()->json($response);
    }

    public function remove_all_from_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required'
        ], [
            'key.required' => translate('Cart key or ID is required!')
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $user = Helpers::get_customer($request);
        Cart::where(['customer_id' => $user->id])->delete();
        return response()->json(translate('successfully_removed'));
    }
}
