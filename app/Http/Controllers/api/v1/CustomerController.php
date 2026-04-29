<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CustomerManager;
use App\CPU\Helpers;
use App\CPU\Convert;
use App\CPU\BackEndHelper;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\CompanyProfile;
use App\Model\DeliveryCountryCode;
use App\Model\DeliveryZipCode;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\ShippingAddress;
use App\Model\SupportTicket;
use App\Model\SupportTicketConv;
use App\Model\Wishlist;
use App\Model\Savelist;
use App\Model\Currency;
use App\Model\Product;
use App\Model\Contact;
use App\Model\OrderTransaction;
use App\Model\ShopFollower;


use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Area;
use App\Model\LoyaltyPointTransaction;
use App\Model\Plan;
use App\Model\Review;
use App\Model\SellerSubscription;
use App\Model\UserNotification;
use App\Model\Subscription;
use App\Traits\CommonTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use function App\CPU\translate;

class CustomerController extends Controller
{
    use CommonTrait;

    public function __construct(
        private ShopFollower $shop_follower,
    ) {

    }

    public function info(Request $request)
    {

        $users = $request->user();
        // dd($users);
        // $user = json_decode(json_encode($users), true);
        // foreach($user as $ukey => $u_val) {
        //     $user[$ukey] = $u_val ?? "";

        //     if($ukey == 'loyalty_point') {
        //         $user[$ukey] = strval(number_format(BackEndHelper::usd_to_currency($u_val),2));
        //     }
        //     if($ukey == 'wallet_balance') {
        //         $user[$ukey] = BackEndHelper::usd_to_currency($u_val);
        //     }

        //     if($ukey == 'image') {
        //         $user[$ukey] = asset('storage/app/public/profile/' . $u_val);
        //     }

        //     if($ukey == 'is_subscribed') {
        //         $user[$ukey] = SellerSubscription::where(['type' => 1, 'seller_id' => $user['id']])->where('expiry_date' ,'>=', date('Y-m-d'))->count() > 0 ? 1 : 0;
        //     }
        // }
       
            
        $users['image'] = asset('storage/app/public/profile/' . $users['image']);

        $users->load('company');
        
        $response = [
            'status' => true,
            'message' => "User Profile",
            'data' => $users,
            // 'latest_order' => Order::where('customer_id', $user['id'])->where('created_at', '>=', Carbon::now()->subMinutes(5))->count(),
            // 'unread_notification'  => UserNotification::where('user_id', $user['id'])->where('is_read', '=', 0)->count(),
            // 'whishlisted'  => Wishlist::where('customer_id', $user['id'])->count(),
        ];
        return response()->json($response, 200);
    }

    public function create_support_ticket(Request $request) {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'type' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $order = Order::find($request->input('order_id'));

        $request['customer_id'] = $request->user()->id;
        $request['order_id'] = $request->input('order_id');
        $request['is_vendor'] = $request->input('order_id') ? 1 : 0;
        $request['vendor_id'] = $order->seller_id ?? 0;
        $request['priority'] = $request->input('priority') ?? 'low';
        $request['status'] = 'pending';

        try {
            CustomerManager::create_support_ticket($request);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 422);
        }
        return response()->json(['status' => true, 'message' => 'Support ticket created successfully.'], 200);
    }
    
    public function account_delete(Request $request, $id)
    {
        if($request->user()->id == $id)
        {
            $user = User::find($id);

            ImageManager::delete('/profile/' . $user['image']);

            $user->delete();

            $response = [
                'status' => true,
                'message' => translate('Your_account_deleted_successfully!!'),
                'data' => []
            ];

           return response()->json($response,200);

        }else{
            $response = [
                'status' => false,
                'message' =>'access_denied!!',
                'data' => []
            ];
            return response()->json($response,403);
        }
    }

    public function reply_support_ticket(Request $request, $ticket_id)
    {
        $support = new SupportTicketConv();
        $support->support_ticket_id = $ticket_id;
        $support->admin_id = 1;
        $support->customer_message = $request['message'];
        $support->save();
        return response()->json(['status' => true, 'message' => 'Support ticket reply sent.'], 200);
    }

    public function get_support_tickets(Request $request)
    {

        $lists = SupportTicket::where('customer_id', $request->user()->id)->get();

        $lists = Helpers::set_support_model($lists);

        // "id": 3,
        // "customer_id": 35,
        // "is_vendor": 1,
        // "vendor_id": 0,
        // "order_id": 0,
        // "subject": "dsfhdfgh",
        // "type": "Website problem",
        // "priority": "Urgent",
        // "description": "testing data",
        // "reply": null,
        // "status": "close",
        // "created_at": "2024-01-02T15:33:43.000000Z",
        // "updated_at": "2024-01-02T15:37:02.000000Z"

        $response = ['status' => true, 'message' => 'Support Tickets', 'data' => $lists];

        return response()->json($response, 200);
    }

    public function get_support_ticket_conv($ticket_id)
    {

        $lists = SupportTicketConv::where('support_ticket_id', $ticket_id)->get();
        
        $lists = Helpers::set_support_conv_model($lists);

        $response = ['status' => true, 'message' => 'Support Tickets', 'data' => $lists];

        return response()->json($response, 200);
    }

    public function add_to_wishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $wishlist = Wishlist::where('customer_id', $request->user()->id)->where('product_id', $request->product_id)->first();

        if (empty($wishlist)) {
            $wishlist = new Wishlist;
            $wishlist->customer_id = $request->user()->id;
            $wishlist->product_id = $request->product_id;
            $wishlist->save();
            return response()->json(['status' => true, 'message' => translate('successfully added!'), 'data' => []], 200);
        }

        return response()->json(['status' => true, 'message' => translate('Already in your wishlist'), 'data' => []], 409);
    }

    public function remove_from_wishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => true, 'errors' => Helpers::error_processor($validator), 'data' => []], 403);
        }

        $wishlist = Wishlist::where('customer_id', $request->user()->id)->where('product_id', $request->product_id)->first();
        if (!empty($wishlist)) {
            Wishlist::where(['customer_id' => $request->user()->id, 'product_id' => $request->product_id])->delete();
            return response()->json(['status' => true, 'message' => translate('successfully removed!'), 'data' => []], 200);
        }
        return response()->json(['status' => true, 'message' => translate('No such data found!'), 'data' => []], 404);
    }

    public function wish_list(Request $request) {
        $wishlists = Wishlist::where('customer_id', $request->user()->id)->get();
        foreach($wishlists as $wishlist) {
            $product = Product::where(['id' => $wishlist->product_id])->first();
            if (isset($product)) {
                $product1 = Helpers::product_data_formatting($product, false, $request->user()->id);
            }
            $wishlist->product = $product1;
        }

        $response = [
            'status' => true,
            'message' => 'Wishlist',
            'data' => $wishlists
        ];

        return response()->json($response, 200);
    }

    public function add_to_savelist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $wishlist = Savelist::where('customer_id', $request->user()->id)->where('product_id', $request->product_id)->first();

        if (empty($wishlist)) {
            $wishlist = new Savelist;
            $wishlist->customer_id = $request->user()->id;
            $wishlist->product_id = $request->product_id;
            $wishlist->save();

            Cart::where(['customer_id' => $request->user()->id, 'product_id' => $request->product_id])->delete();

            return response()->json(['status' => true, 'message' => translate('successfully added!'), 'data' => []], 200);
        }

        return response()->json(['status' => true, 'message' => translate('Already in your wishlist'), 'data' => []], 409);
    }

    public function remove_from_savelist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => true, 'errors' => Helpers::error_processor($validator), 'data' => []], 403);
        }

        $wishlist = Savelist::where('customer_id', $request->user()->id)->where('product_id', $request->product_id)->first();
        if (!empty($wishlist)) {
            Savelist::where(['customer_id' => $request->user()->id, 'product_id' => $request->product_id])->delete();
            return response()->json(['status' => true, 'message' => translate('successfully removed!'), 'data' => []], 200);
        }
        return response()->json(['status' => true, 'message' => translate('No such data found!'), 'data' => []], 404);
    }

    public function savelist(Request $request) {
        $wishlists = Savelist::where('customer_id', $request->user()->id)->get();
        foreach($wishlists as $wishlist) {
            $product = Product::where(['id' => $wishlist->product_id])->first();
            if ($product) {
                $product1 = Helpers::product_data_formatting($product, false);
                $wishlist->product = $product1;
            }
        }

        $response = [
            'status' => true,
            'message' => 'Wishlist',
            'data' => $wishlists
        ];

        return response()->json($response, 200);
    }

    public function address_list(Request $request) {

        $address = ShippingAddress::where('customer_id', $request->user()->id)->latest()->get();

        $dt = [];
        foreach ($address as $key => $value) {
            $dt[$key] = Helpers::set_order_shipping_data_format($value);
            $dt[$key]['is_billing'] = (int)$dt[$key]['is_billing'];
            $dt[$key]['id'] = (int)$dt[$key]['id'];
            $dt[$key]['customer_id'] = (int)$dt[$key]['customer_id'];
            
        }

        $response = ['status' => true, 'message' => 'Customer address list sadf', 'data' => $dt];
        return response()->json($response, 200);
    }

    public function add_new_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'address' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        if ($validator->fails()) {
            $response = ['status' => false, 'message' => 'Please fill required fields', 'data' => [], 'errors' => Helpers::error_processor($validator)];
            return response()->json($response, 403);
        }

        $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');
        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');

        if ($country_restrict_status && !self::delivery_country_exist_check($request->input('country'))) {
            $response = ['status' => false, 'message' => translate('Delivery_unavailable_for_this_country'), 'data' => [], 'errors' => []];

            return response()->json($response, 403);
        } elseif ($zip_restrict_status && !self::delivery_zipcode_exist_check($request->input('zip'))) {
            // $response = ['status' => false, 'message' => translate('Delivery_unavailable_for_this_zip_code_area'), 'data' => [], 'errors' => []];
            // return response()->json($response, 403);
        }

        $address = new ShippingAddress;
        $address->customer_id = $request->user()->id;
        $address->address_type = $request->address_type;
        $address->contact_person_name = $request->contact_person_name;
        $address->phone = $request->phone;
        $address->alt_phone = $request->alt_phone;
        $address->address = $request->address;
        $address->address1 = $request->address1;
        $address->country = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->area = $request->area ?? $request->address1 ?? "";
        $address->zip = $request->zip;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->is_billing = $request->is_billing ?? 0;
        $address->created_at = now();
        $address->updated_at = now();
        $address->save();


        // $address = [
        //     'customer_id' => $request->user()->id,
        //     'address_type' => $request->address_type,
        //     'contact_person_name' => $request->contact_person_name,
        //     'phone' => $request->phone,
        //     'alt_phone' => $request->alt_phone,
        //     'address' => $request->address,
        //     'address1' => $request->address1,
        //     'country' => $request->country,
        //     'state' => $request->state,
        //     'city' => $request->city,
        //     'zip' => $request->zip,
        //     'latitude' => $request->latitude,
        //     'longitude' => $request->longitude,
        //     'is_billing' => $request->is_billing ?? 0,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ];
        // ShippingAddress::insert($address);

        $response = ['status' => true, 'message' => translate('successfully added!'), 'data' => [], 'errors' => []];

        return response()->json($response, 200);
    }

    public function update_address(Request $request)
    {

        $shipping_address = ShippingAddress::where(['customer_id' => $request->user()->id, 'id' => $request->id])->first();
        if (!$shipping_address) {
            $response = ['status' => false, 'message' => translate('not_found'), 'data' => [], 'errors' => []];

            return response()->json($response, 200);
        }

        $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');
        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');

        if ($country_restrict_status && !self::delivery_country_exist_check($request->input('country'))) {
            $response = ['status' => false, 'message' => translate('Delivery_unavailable_for_this_country'), 'data' => [], 'errors' => []];
            return response()->json($response, 403);

        } elseif ($zip_restrict_status && !self::delivery_zipcode_exist_check($request->input('zip'))) {
            // return response()->json(['message' => translate('Delivery_unavailable_for_this_zip_code_area')], 403);
        }

        $address = ShippingAddress::find($request->id);
        $address->address_type = $request->address_type;
        $address->contact_person_name = $request->contact_person_name;
        $address->phone = $request->phone;
        $address->alt_phone = $request->alt_phone;
        $address->address = $request->address;
        $address->address1 = $request->address1;
        $address->country = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->area = $request->area ?? $request->address1 ?? "";
        $address->zip = $request->zip;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->is_billing = $request->is_billing ?? 0;
        $address->updated_at = now();
        $address->save();
        $response = ['status' => true, 'message' => translate('update_successful'), 'data' => [], 'errors' => []];

        return response()->json($response, 200);
    }

    public function delete_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = ['status' => false, 'message' => 'Address Id not found', 'data' => []];
            return response()->json($response, 403);
        }

        if (DB::table('shipping_addresses')->where(['id' => $request['address_id'], 'customer_id' => $request->user()->id])->first()) {
            DB::table('shipping_addresses')->where(['id' => $request['address_id'], 'customer_id' => $request->user()->id])->delete();
            $response = ['status' => true, 'message' => 'Address successfully removed!', 'data' => []];
            return response()->json($response, 200);
        }
        $response = ['status' => false, 'message' => translate('No such data found!'), 'data' => []];
        return response()->json($response, 404);

    }

    public function get_order_list(Request $request)
    {

        $requestStatus = $request->input('status');
        $requestCategories = $request->input('categories');
        $search_text = $request->input('search_text');

        $orders = Order::where(['customer_id' => $request->user()->id])->orderBy('id', 'DESC')->get();
        $orderDetails = OrderDetail::select('order_details.product_id')->join('orders','orders.id', '=', 'order_details.order_id')->where(['orders.customer_id' => $request->user()->id])->get()->pluck('product_id');
        $products = Product::select('categories.id', 'categories.name')->join('categories','categories.id', '=', 'products.category_id')->whereIn('products.id', $orderDetails)->get()->pluck('name', 'id');

        $ct = [];
        $i = 0;
        foreach ($products as $key => $value) {
            $ct[$i]['title'] = $key;
            $ct[$i]['value'] = $value;
            $i++;
        }

        $status = [
            ['title' => 'pending', 'value' => \App\CPU\translate('Pending')],
            ['title' => 'confirmed', 'value' => \App\CPU\translate('Confirmed')],
            ['title' => 'processing', 'value' => \App\CPU\translate('Packaging')],
            ['title' => 'out_for_delivery', 'value' => \App\CPU\translate('out_for_delivery')],
            ['title' => 'delivered', 'value' => \App\CPU\translate('Delivered')],
            ['title' => 'returned', 'value' => \App\CPU\translate('Returned')],
            ['title' => 'failed', 'value' => \App\CPU\translate('Failed_to_Deliver')],
            ['title' => 'canceled', 'value' => \App\CPU\translate('Canceled')],
        ];

        $orders = OrderDetail::when(!empty($requestCategories), function($q) use($requestCategories) {
            $q->whereHas('product', function($query) use($requestCategories) {
                return $query->where('category_id', $requestCategories);
            });
        })->select('orders.*', 'order_details.*')->join('orders','orders.id', '=', 'order_details.order_id')->where(['orders.customer_id' => $request->user()->id])->orderBy('orders.id', 'DESC');

        if($requestStatus != "") {
            $orders = $orders->where('order_status', $requestStatus);
        }

        if($search_text != "") {
            $orders = $orders->where('product_details', 'LIKE', "%{$search_text}%");
        }

        $orders = $orders->get();

        $orders = Helpers::order_data_formatting($orders, true);

        $dt = [];
        foreach ($orders as $key => $order) {

            $dt[$key]['id'] = $order['id'];
            $dt[$key]['order_id'] = $order['order_id'];
            $dt[$key]['product_name'] = $order['product_details']['name'];
            $dt[$key]['product_slug'] = $order['product_details']['slug'];
            $dt[$key]['details'] = $order['product_details']['details'] ?? "";
            $dt[$key]['product_id'] = $order['product_id'];
            $dt[$key]['seller_id'] = $order['seller_id'];
            $dt[$key]['delivery_status'] = $order['delivery_status'];
            $dt[$key]['payment_status'] = $order['payment_status'];
            $dt[$key]['created_at'] = date('d M, Y', strtotime($order['created_at']));
            $dt[$key]['order_status'] = $order['order_status'];
            $dt[$key]['payment_method'] = $order['payment_method'];
            $dt[$key]['transaction_ref'] = $order['transaction_ref'];
            $dt[$key]['order_amount'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order['order_amount']));
            $dt[$key]['qty'] = $order['qty'];
            $dt[$key]['price'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order['price']));
            $dt[$key]['tax'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order['tax']));
            $dt[$key]['discount'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order['discount']));
            $dt[$key]['total'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency((((float)$order['price']*(float)$order['qty']) + (float)$order['tax']) - (float)$order['discount']));

            if($order->product) {
                $product = Helpers::set_data_format($order->product);
                $dt[$key]['thumbnail'] = $product['thumbnail'];
                $dt[$key]['product'] = $product;
            }
            
        }

        $response = ['status' => true, 'message' => 'My Orders', 'filters' => ['status' => $status, 'categories' => $ct], 'data' => $dt];

        return response()->json($response, 200);
    }

    public function get_order_details(Request $request) {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'errors' => Helpers::error_processor($validator), 'data' => []], 403);
        }

        $order = Order::where(['id' => $request['order_id']])->orderBy('id', 'DESC')->first();
        
        $response = ['status' => false, 'message' => 'Order details', 'data' => [], 'recommended_products' => []];

        if(!empty($order)) {
            $order_details = Helpers::set_order_products($order->details, true);
            $totalPrice = 0;
            $category_ids = "";
            foreach ($order_details as $pkey => $product) {
                $amt = (((float)$product['price']) * $product['qty']) + $product['tax'];
                $totalPrice += $amt;
                // $product['tax'];
                // $product['discount'];
    
                $order_details[$pkey]['price'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency(((float)$product['price'] - (float)$product['discount']) * $product['qty']));
                $order_details[$pkey]['discount'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($product['discount']));
                $order_details[$pkey]['tax'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($product['tax']));
                $order_details[$pkey]['order_amount'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($amt));
    
                $category_ids = $product['category_id'];
                (int)$product->unit_price;
                (int)$product->purchase_price;
            }
    
            $dt = [];
            $dt['order_id'] = $request['order_id'];
            $dt['seller'] = Helpers::set_shop_data($order->seller->shop);
            $dt['products'] = $order_details;
            $dt['order_status_history'] = Helpers::set_order_orderstatus_data_format($order->order_status_history);
            $dt['order_amount'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->order_amount));
            $dt['subtotal'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->order_amount));
            $dt['discount_amount'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->discount_amount));
            $dt['delivery_charge'] = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->shipping_cost));
            $dt['order_status'] = translate($order->order_status);
            $dt['payment_status'] = translate($order->payment_status);
            $dt['payment_method'] = translate($order->payment_method);
            $dt['expected_delivery_date'] = $order->expected_delivery_date != "" ? date('d M, Y', strtotime($order->expected_delivery_date)) : "";
            $dt['order_note'] = $order->order_note ?? "";
            $dt['shipping_address_data'] = Helpers::set_order_shipping_data_format($order->shippingAddress);
    
            $dt['order_review'] = Helpers::set_review_data(Review::where('order_id', $request['order_id'])->get() ?? []);
            
            $relatedProducts = Product::active()->limit(12)->get();
    
            $relatedProducts = Helpers::product_data_formatting($relatedProducts, true, $request->user()->id);
    
            // $dt['recommended_products'] = $relatedProducts;
    
            $response = ['status' => true, 'message' => 'Order details', 'data' => $dt, 'recommended_products' => $relatedProducts];

        }


        return response()->json($response, 200);
    }

    public function get_order_by_id(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $order = Order::where(['id' => $request['order_id']])->first();
        $order['shipping_address_data'] = json_decode($order['shipping_address_data']);
        $order['billing_address_data'] = json_decode($order['billing_address_data']);
        return response()->json($order, 200);
    }

    public function companyProfileUpdate(Request $request){

        // dd($request);
        $user = $request->user();

        $companyProfile = CompanyProfile::where('user_id',$user->id)->first();

        if($companyProfile){

            $companyProfile->name = $request->input('name', $companyProfile->name);
            $companyProfile->organization = $request->input('organization', $companyProfile->organization);
            $companyProfile->address = $request->input('address', $companyProfile->address);
            $companyProfile->address_other_language = $request->input('address_other_language', $companyProfile->address_other_language);
            $companyProfile->city = $request->input('city', $companyProfile->city);
            $companyProfile->country = $request->input('country', $companyProfile->country);
            $companyProfile->fax = $request->input('fax', $companyProfile->fax);
            $companyProfile->pincode = $request->input('pincode', $companyProfile->pincode);
            $companyProfile->website = $request->input('website', $companyProfile->website);
            $companyProfile->phone = $request->input('phone', $companyProfile->phone);
            $companyProfile->email = $request->input('email', $companyProfile->email);
            $companyProfile->status = 'pending';

            $companyProfile->contact_person_details = $request->has('contact_person_details')
                ? (is_string($request['contact_person_details'])
                    ? json_decode($request['contact_person_details'], true)
                    : $request['contact_person_details'])
                : $companyProfile->contact_person_details;

            $companyProfile->parent_organization = $request->has('parent_organization')
                ? (is_string($request['parent_organization'])
                    ? json_decode($request['parent_organization'], true)
                    : $request['parent_organization'])
                : $companyProfile->parent_organization;

            $companyProfile->invoice_address = $request->has('invoice_address')
                ? (is_string($request['invoice_address'])
                    ? json_decode($request['invoice_address'], true)
                    : $request['invoice_address'])
                : $companyProfile->invoice_address;

            $companyProfile->ownership = $request->input('ownership', $companyProfile->ownership);

            $companyProfile->ownership_details = $request->has('ownership_details')
                ? (is_string($request['ownership_details'])
                    ? json_decode($request['ownership_details'], true)
                    : $request['ownership_details'])
                : $companyProfile->ownership_details;

        } else {

            $companyProfile = new CompanyProfile();
            $companyProfile->user_id = $user->id;
            $companyProfile->name = $request['name'];
            $companyProfile->organization = $request['organization'];
            $companyProfile->address = $request['address'];
            $companyProfile->address_other_language = $request['address_other_language'];
            $companyProfile->city = $request['city'];
            $companyProfile->country = $request['country'];
            $companyProfile->fax = $request['fax'];
            $companyProfile->pincode = $request['pincode'];
            $companyProfile->website = $request['website'];
            $companyProfile->phone = $request['phone'];
            $companyProfile->email = $request['email'];
            $companyProfile->status = 'pending';

            // $companyProfile->contact_person_details = json_decode($request['contact_person_details'], true);
            // $companyProfile->parent_organization   = json_decode($request['parent_organization'], true);
            // $companyProfile->invoice_address       = json_decode($request['invoice_address'], true);
            // $companyProfile->ownership             = $request['ownership'];
            // $companyProfile->ownership_details     = json_decode($request['ownership_details'], true);

            $companyProfile->contact_person_details = is_string($request['contact_person_details'])
                ? json_decode($request['contact_person_details'], true)
                : $request['contact_person_details'];

            $companyProfile->parent_organization = is_string($request['parent_organization'])
                ? json_decode($request['parent_organization'], true)
                : $request['parent_organization'];

            $companyProfile->invoice_address = is_string($request['invoice_address'])
                ? json_decode($request['invoice_address'], true)
                : $request['invoice_address'];

            $companyProfile->ownership = $request['ownership'];

            $companyProfile->ownership_details = is_string($request['ownership_details'])
                ? json_decode($request['ownership_details'], true)
                : $request['ownership_details'];

        }

        // dd($companyProfile);

        $companyProfile->save();

        if($companyProfile){
            $response['status'] = true;
            $response['message'] = 'Company Profile Update Successfully';
            $response['data'] = $companyProfile;
        } else {
            $response['status'] = false;
            $response['message'] = 'Something went wrong';
            $response['data'] = [];
        }

        return response()->json($response);
       
    }
    public function update_profile(Request $request) {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'phone' => 'required',
        ], [
            'f_name.required' => translate('First name is required!')
        ]);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => translate('successfully updated!'),
                'errors' => Helpers::error_processor($validator),
                'data' => []
            ];

            return response()->json($response, 403);
        }

        if ($request->has('image')) {
            $imageName = ImageManager::update('profile/', $request->user()->image, 'png', $request->file('image'));
        } else {
            $imageName = $request->user()->image;
        }

        if ($request['password'] != null && strlen($request['password']) > 5) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $request->user()->password;
        }

        $userDetails = [
            'name' => $request->f_name.' '.$request->l_name,
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'phone' => $request->phone,
            // 'gender' => $request->gender,

            // 'country' => $request->country,
            // 'state' => $request->state,
            // 'city' => $request->city,
            // 'zip' => $request->pin_code,

            'country_code'=> $request->country_code,
            
            'image' => $imageName,
            // 'password' => $pass,
            'updated_at' => now(),
        ];

        if(isset($request->email) && $request->email != "") {
            $userDetails['email'] = $request->email;
        }

        User::where(['id' => $request->user()->id])->update($userDetails);

        $response = [
            'status' => true,
            'message' => translate('successfully updated!'),
            'data' => []
        ];

        return response()->json($response, 200);
    }

    public function upload_profile_image(Request $request) {
        if ($request->has('image')) {
            $imageName = ImageManager::update('profile/', $request->user()->image, 'png', $request->file('image'));
        } else {
            $imageName = $request->user()->image;
        }

        $userDetails = [
            'image' => $imageName
        ];

        User::where(['id' => $request->user()->id])->update($userDetails);
        $response = ['status' => true, 'message' => 'Upload image success'];
        return response()->json($response);
    }

    public function update_cm_firebase_token(Request $request) {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        DB::table('users')->where('id', $request->user()->id)->update([
            'cm_firebase_token' => $request['cm_firebase_token'],
        ]);

        return response()->json(['message' => translate('successfully updated!')], 200);
    }

    public function get_restricted_country_list(Request $request)
    {
        $stored_countries = DeliveryCountryCode::orderBy('country_code', 'ASC')->pluck('country_code')->toArray();
        $country_list = COUNTRIES;

        $countries = array();

            foreach ($country_list as $country) {
                if (in_array($country['code'], $stored_countries))
                {
                    $countries []= $country['name'];
                }
            }

        if($request->search){
            $countries = array_values(preg_grep('~' . $request->search . '~i', $countries));
        }

        return response()->json($countries, 200);
    }

    public function get_restricted_zip_list(Request $request)
    {
        $zipcodes = DeliveryZipCode::orderBy('zipcode', 'ASC')
            ->when($request->search, function ($query) use($request){
                $query->where('zipcode', 'like', "%{$request->search}%");
            })
            ->get();

        return response()->json($zipcodes, 200);
    }

    public function getLanguages() {
        $languages = Helpers::get_business_settings('language');

        $response = [
            'status' => true,
            'message' => "Languages lists",
            'data' => $languages
        ];
        return response()->json($response, 200);
    }

    public function getCurrency() {
        $currencies = Currency::all();

        $response = [
            'status' => true,
            'message' => "Currencies lists",
            'data' => $currencies
        ];
        return response()->json($response, 200);

    }

    public function update_language(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_id' => 'required',
        ], [
            'language_id.required' => translate('Please select language!')
        ]);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => translate('something went wrong!'),
                'errors' => Helpers::error_processor($validator),
                'data' => []
            ];

            return response()->json($response, 403);
        }

        $userDetails = [
            'language_id' => $request->language_id
        ];

        User::where(['id' => $request->user()->id])->update($userDetails);

        $response = [
            'status' => true,
            'message' => translate('successfully updated!'),
            'data' => []
        ];

        return response()->json($response, 200);
    }

    public function update_currency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency_id' => 'required',
        ], [
            'currency_id.required' => translate('Please select currency!')
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => translate('something went wrong!'),
                'errors' => Helpers::error_processor($validator),
                'data' => []
            ];
            return response()->json($response, 403);
        }

        $userDetails = [
            'currency_id' => $request->currency_id
        ];

        User::where(['id' => $request->user()->id])->update($userDetails);

        $response = [
            'status' => true,
            'message' => translate('successfully updated!'),
            'data' => []
        ];

        return response()->json($response, 200);
    }

    public function update_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|same:confirm_password'
        ]);
        
        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => translate('something went wrong!'),
                'errors' => Helpers::error_processor($validator),
                'data' => []
            ];

            return response()->json($response, 403);
        }

        $userDetails = [
            'id' => $request->user()->id,
            'password' => bcrypt($request->old_password)
        ];

        if(User::where($userDetails)->first()) {

            $userPassword = [
                'password' => bcrypt($request->password)
            ];
    
            User::where(['id' => $request->user()->id])->update($userPassword);
    
            $response = [
                'status' => true,
                'message' => translate('successfully updated!'),
                'data' => []
            ];

        } else {
            $response = [
                'status' => false,
                'message' => translate('Old password not matched!'),
                'data' => []
            ];
        }


        return response()->json($response, 200);
    }

    public function reset_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => 'required|same:confirm_password'
        ]);
        
        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => translate('password and confirm password not matched!'),
                'data' => []
            ];

            return response()->json($response, 403);
        }

        // $userDetails = [
        //     'id' => $request->user()->id,
        //     'password' => bcrypt($request->old_password)
        // ];

        if($request->user()->id) {

            $userPassword = [
                'password' => bcrypt($request->password)
            ];
    
            User::where(['id' => $request->user()->id])->update($userPassword);
    
            $response = [
                'status' => true,
                'message' => translate('successfully updated!'),
                'data' => []
            ];

        } else {
            $response = [
                'status' => false,
                'message' => translate('Old password not matched!'),
                'data' => []
            ];
        }


        return response()->json($response, 200);
    }

    // CustomerController
    public function getCountries(Request $request) {
        $currencies = Country::select('id','name')->get();

        $response = [
            'status' => true,
            'message' => "Countries lists",
            'data' => $currencies
        ];
        return response()->json($response, 200);

    }
    // CustomerController
    // public function getAreas(Request $request) {
    //     $currencies = Area::select('id','name')->get();

    //     $response = [
    //         'status' => true,
    //         'message' => "Areas lists",
    //         'data' => $currencies
    //     ];
    //     return response()->json($response, 200);

    // }

    public function getAreas(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|integer|exists:cities,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

    
        $areas = Area::where('city_id', $request->city_id)
                    ->orderBy('name', 'ASC')
                    ->get();

        return response()->json([
            'status' => true,
            'message' => "Areas lists",
            'data' => $areas
        ], 200);
    }
    
    public function getStates(Request $request) {
        $currencies = State::where('country_id', 101)->orderBy('name', 'ASC')->get();

        // $currencies = State::where('country_id', $request->country_id)->orderBy('name', 'ASC')->get();

        $response = [
            'status' => true,
            'message' => "State lists",
            'data' => $currencies
        ];
        return response()->json($response, 200);

    }
    
    // public function getCities(Request $request) {
    //     $currencies = City::where('state_id', $request->state_id)->orderBy('name', 'ASC')->get();

    //     $response = [
    //         'status' => true,
    //         'message' => "Cities lists",
    //         'data' => $currencies
    //     ];
    //     return response()->json($response, 200);

    // }
    public function getCities(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'state_id' => 'required|integer|exists:states,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

    
        $cities = City::where('state_id', $request->state_id)
                    ->orderBy('name', 'ASC')
                    ->get();

        return response()->json([
            'status' => true,
            'message' => "Cities list",
            'data' => $cities
        ], 200);
    }

    
    public function contact_store(Request $request) {

        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ], [
            'mobile_number.required' => 'Mobile Number is Empty!',
            'subject.required' => ' Subject is Empty!',
            'message.required' => 'Message is Empty!',

        ]);
        
        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => 'Please fill all required fields',
                'data' => [$validator->errors()]
            ];

            return response()->json($response, 403);
        }

        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->mobile_number = $request->mobile_number;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();

        $response = ['status' => true, 'message' => translate('Your Message Send Successfully'), 'data' => []];
        return response()->json($response, 200);

        // Toastr::success(translate('Your Message Send Successfully'));
        // return back();
    }

    public function getOrderTransaction(Request $request) {

        $transactions = OrderTransaction::where('customer_id', $request->user()->id)->get();

        $transactions = Helpers::set_order_transaction_data($transactions);

        $response = ['status' => true, 'message' => translate('Order Transaction List'), 'data' => $transactions];
        return response()->json($response, 200);
    }

    public function get_refer_n_earn(Request $request) {

        $loyalty_point_transactions = LoyaltyPointTransaction::where(['transaction_type' => 'refer_n_earn', 'user_id' => $request->user()->id])->get();
        if(!empty($loyalty_point_transactions)) {
            foreach ($loyalty_point_transactions as $key => $pointTransaction) {
                if($pointTransaction->transaction_type == 'refer_n_earn') {
                    $pointTransaction->description = User::find($pointTransaction->reference)->name;
                }
                $pointTransaction->credit = $pointTransaction->credit;
                $pointTransaction->debit = $pointTransaction->debit;
                $pointTransaction->balance = $pointTransaction->balance;
            }    
        }
        $response = ['status' => true, 'message' => 'Referral Lists', 'data' => $loyalty_point_transactions];

        return response()->json($response, 200);

    }

    public function getPlans(Request $request) {

        $user_id = $request->user()->id;
        
        $monthly = Plan::where(['user_type' => 1, 'type' => 2])->orderBy('id', 'DESC')->get(); // Monthly
        $yearly = Plan::where(['user_type' => 1, 'type' => 3])->orderBy('id', 'DESC')->get(); // Yearly
       
        foreach ($monthly as $key => $plan) {

            $subscription = SellerSubscription::where(['seller_id' => $user_id, 'type' => 1, 'plan_id' => $plan['id']])->where('expiry_date', '>=', date('Y-m-d'))->first();
            $stat = !empty($subscription) ? true : false;
            $text = "";
            if($plan['type'] == 1) {
                $text = $plan['time'] > 1 ? "Days" : "Day";
            } else if($plan['type'] == 2) {
                $text = $plan['time'] > 1 ? "Months" : "Month";
            } else if($plan['type'] == 3) {
                $text = $plan['time'] > 1 ? "Years" : "Year";
            }
            $plan->time_text = $plan['time'] .' '. $text;
            $amt = $plan->price;
            $plan->is_purchased = $stat;
            $plan->price = BackEndHelper::usd_to_currency($amt);
            $plan->price_with_currency = Helpers::set_symbol(BackEndHelper::usd_to_currency($amt));
            $plan->created_at = isset($subscription->expiry_date) ? date('d M, Y', strtotime($subscription->expiry_date)) : "";
        }
        foreach ($yearly as $key => $plan) {
            $subscription = SellerSubscription::where(['seller_id' => $user_id, 'type' => 1, 'plan_id' => $plan['id']])->where('expiry_date', '>=', date('Y-m-d'))->first();
            $stat = !empty($subscription) ? true : false;
            $text = "";
            if($plan['type'] == 1) {
                $text = $plan['time'] > 1 ? "Days" : "Day";
            } else if($plan['type'] == 2) {
                $text = $plan['time'] > 1 ? "Months" : "Month";
            } else if($plan['type'] == 3) {
                $text = $plan['time'] > 1 ? "Years" : "Year";
            }
            $plan->time_text = $plan['time'] .' '. $text;
            $amt = $plan->price;
            $plan->is_purchased = $stat;
            $plan->price = BackEndHelper::usd_to_currency($amt);
            $plan->price_with_currency = Helpers::set_symbol(BackEndHelper::usd_to_currency($amt));
            $plan->created_at = isset($subscription->expiry_date) ? date('d M, Y', strtotime($subscription->expiry_date)) : "";
        }

        $response = [
            'status' => false,
            'message' => "Subscription plans.",
            'data' => [['monthly' => $monthly, 'yearly' => $yearly]]
        ];

        return response()->json($response, 200);
    }

    public function purchaseUserPlan(Request $request) {
        $plan = Plan::find($request->input('plan_id'));

        $expiry_date = date('Y-m-d');
        if($plan->type == 1) { // Day
            $expiry_date = date('Y-m-d', strtotime($expiry_date . ' + '.$plan->time.' days'));
        } else if($plan->type == 2) { // Month
            $expiry_date = date('Y-m-d', strtotime($expiry_date . ' + '.$plan->time.' month'));
        } else if($plan->type == 3) { // Year
            $expiry_date = date('Y-m-d', strtotime($expiry_date . ' + '.$plan->time.' year'));
        }

        $subscribe = new SellerSubscription;
        $subscribe->plan_id = $request->input('plan_id');
        $subscribe->transaction_id = $request->input('transaction_id');
        $subscribe->expiry_date = $expiry_date;
        $subscribe->seller_id = $request->user()->id;
        $subscribe->type = 1;
        $subscribe->save();
        
        $response = [
            'status' => true,
            'message' => 'User subscription purchased success',
            'data' => []
        ];

        return response()->json($response, 200);
    }

    public function getVendorFollowed(Request $request) {
        $lists = $this->shop_follower->with('shop')->where(['user_id'=>auth()->user()->id])->get();
        $dt = [];
        foreach ($lists as $key => $list) {
            $dt[$key] = Helpers::set_shop_data($list->shop);
        }
        $response = ['status' => true, 'message' => 'Followed Vendor Lists', 'data' => $dt];
        return response()->json($response);
    }

}
