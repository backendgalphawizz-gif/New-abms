<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\DeliveryManTransaction;
use App\Model\DeliverymanWallet;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\OrderDeliveryManStatus;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use function App\CPU\translate;
use App\CPU\CustomerManager;
use App\CPU\Convert;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\User;

class OrderController extends Controller
{
    use CommonTrait;
    public function list(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        $order_status = $request->input('status') ?? 'pending';

        $status = [
            ['is_active' => $order_status == 'pending' ? true : false, 'title' => 'pending', 'value' => \App\CPU\translate('Pending')],
            ['is_active' => $order_status == 'confirmed' ? true : false, 'title' => 'confirmed', 'value' => \App\CPU\translate('Confirmed')],
            // ['is_active' => $order_status == 'processing' ? true : false, 'title' => 'processing', 'value' => \App\CPU\translate('Packaging')],
            ['is_active' => $order_status == 'shipped' ? true : false, 'title' => 'shipped', 'value' => \App\CPU\translate('Shipped')],
            ['is_active' => $order_status == 'out_for_delivery' ? true : false, 'title' => 'out_for_delivery', 'value' => \App\CPU\translate('out_for_delivery')],
            ['is_active' => $order_status == 'delivered' ? true : false, 'title' => 'delivered', 'value' => \App\CPU\translate('Delivered')],
            ['is_active' => $order_status == 'returned' ? true : false, 'title' => 'returned', 'value' => \App\CPU\translate('Returned')],
            ['is_active' => $order_status == 'failed' ? true : false, 'title' => 'failed', 'value' => \App\CPU\translate('Failed_to_Deliver')],
            ['is_active' => $order_status == 'canceled' ? true : false, 'title' => 'canceled', 'value' => \App\CPU\translate('Canceled')],
        ];

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            $response = ['status' => false, 'message' => translate('Your existing session token does not authorize you any more'), 'data' => [], 'order_status' => $status];
            return response()->json($response, 401);
        }

        $order_ids = OrderDetail::where(['seller_id' => $seller['id']])->pluck('order_id')->toArray();
        $orders = Order::with(['customer','shipping'])->where(['seller_is'=>'seller'])->whereIn('id', $order_ids)->get();
        $orders->map(function ($data) {
            $data['billing_address_data'] = json_decode($data['billing_address_data']);
            return $data;
        });

        $orders = Order::select('expected_delivery_date',
        'payment_method','id','customer_id','order_status','shipping_method_id', 'order_amount','created_at', 'shipping_address_data', 'discount_amount', 'billing_address_data', 'shipping_cost')->whereIn('id', $order_ids)->where(['order_status' => $order_status])
        ->withCount('items')
        ->orderBy('id', 'DESC')
        ->get();

        // dd($orders);

        $orderSections = [];
        foreach ($orders as $key => $order) {
            $orderSections[$key]['order_id'] = $order->id;
            $orderSections[$key]['order_status'] = $order->order_status;
            // $orderSections[$key]['order_status'] = $order->variant;
            $orderSections[$key]['order_date'] = date('d-F-Y', strtotime($order->created_at));
            $orderSections[$key]['order_amount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order->order_amount));
            $orderSections[$key]['expected_delivery_date'] = $order->expected_delivery_date ?? "";
            $orderSections[$key]['payment_method'] = translate($order->payment_method);
            $orderSections[$key]['is_alpha_delivery'] = $order->shipping_method_id == '9' ? true : false;
            $orderSections[$key]['shipping_address_data'] = Helpers::set_order_shipping_data_format(json_decode($order->shipping_address_data, true));
            $orderSections[$key]['billing_address_data'] = Helpers::set_order_shipping_data_format(json_decode($order->billing_address_data, true));
            

            foreach($order->details as $detail) {

                $product = !empty($detail->product) ? Helpers::set_data_format($detail->product) : [];
                
                // dd(, $detail->product->discount, $detail->product->discount_type);

                // dd($detail->product->tax_type);

                $orderSections[$key]['detail'] = $product;
                $orderSections[$key]['variant'] = $detail->variant;
                $orderSections[$key]['detail']['quantity'] = strval($detail->qty ?? 1);
                // $orderSections[$key]['variation'] = $detail->variation != '[]' ? json_decode($detail->variation) : [] ;
                $orderSections[$key]['price_detail']['mrp'] = Helpers::currency_converter($detail->product->unit_price ?? 0);
                $orderSections[$key]['price_detail']['tax'] = Helpers::currency_converter($detail->product->unit_price * $detail->product->tax / 100);
                if($detail->product->discount_type == 'percent') {
                    $orderSections[$key]['price_detail']['subtotal'] = Helpers::currency_converter($detail->product->unit_price - ($detail->product->unit_price * $detail->product->discount / 100));
                    $orderSections[$key]['price_detail']['discount'] = isset($product['discount']) ? Helpers::set_symbol(BackEndHelper::usd_to_currency(($detail->product->unit_price * $detail->product->discount / 100))) : "";
                } else {
                    $orderSections[$key]['price_detail']['subtotal'] = Helpers::currency_converter($detail->product->unit_price - $detail->product->discount);
                    $orderSections[$key]['price_detail']['discount'] = isset($product['discount']) ? Helpers::set_symbol(BackEndHelper::usd_to_currency($detail->product->discount)) : "";
                }
            }
            $orderSections[$key]['price_detail']['coupon_discount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order->shipping_cost));
            $orderSections[$key]['price_detail']['delivery_fee'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order->shipping_cost));
            $orderSections[$key]['price_detail']['total'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order->order_amount));
            $orderSections[$key]['customer'] = $order->customer->f_name ?? '';
            $orderSections[$key]['items_count'] = $order->items_count ?? 0;
        }

        $response = ['status' => true, 'message' => 'Vendor Orders', 'data' => $orderSections, 'order_status' => $status];

        return response()->json($response, 200);
    }
    public function recentList(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        $order_status = $request->input('status') ?? 'pending';

        $status = [
            ['is_active' => $order_status == 'pending' ? true : false, 'title' => 'pending', 'value' => \App\CPU\translate('Pending')],
            ['is_active' => $order_status == 'confirmed' ? true : false, 'title' => 'confirmed', 'value' => \App\CPU\translate('Confirmed')],
            // ['is_active' => $order_status == 'processing' ? true : false, 'title' => 'processing', 'value' => \App\CPU\translate('Packaging')],
            ['is_active' => $order_status == 'shipped' ? true : false, 'title' => 'shipped', 'value' => \App\CPU\translate('Shipped')],
            ['is_active' => $order_status == 'out_for_delivery' ? true : false, 'title' => 'out_for_delivery', 'value' => \App\CPU\translate('out_for_delivery')],
            ['is_active' => $order_status == 'delivered' ? true : false, 'title' => 'delivered', 'value' => \App\CPU\translate('Delivered')],
            ['is_active' => $order_status == 'returned' ? true : false, 'title' => 'returned', 'value' => \App\CPU\translate('Returned')],
            ['is_active' => $order_status == 'failed' ? true : false, 'title' => 'failed', 'value' => \App\CPU\translate('Failed_to_Deliver')],
            ['is_active' => $order_status == 'canceled' ? true : false, 'title' => 'canceled', 'value' => \App\CPU\translate('Canceled')],
        ];

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            $response = ['status' => false, 'message' => translate('Your existing session token does not authorize you any more'), 'data' => [], 'order_status' => $status];
            return response()->json($response, 401);
        }

        $order_ids = OrderDetail::where(['seller_id' => $seller['id']])->pluck('order_id')->toArray();
        $orders = Order::with(['customer','shipping'])->where(['seller_is'=>'seller'])->whereIn('id', $order_ids)->get();
        $orders->map(function ($data) {
            $data['billing_address_data'] = json_decode($data['billing_address_data']);
            return $data;
        });

        $orders = Order::select('expected_delivery_date',
        'payment_method','id','customer_id','order_status','shipping_method_id', 'order_amount','created_at', 'shipping_address_data', 'discount_amount', 'billing_address_data', 'shipping_cost')->whereIn('id', $order_ids)
        ->withCount('items')
        ->orderBy('id', 'DESC')
        ->get()
        ->take(5);


        $orderSections = [];
        foreach ($orders as $key => $order) {
            $orderSections[$key]['order_id'] = $order->id;
            $orderSections[$key]['order_status'] = $order->order_status;
            // $orderSections[$key]['order_status'] = $order->variant;
            $orderSections[$key]['order_date'] = date('d-F-Y', strtotime($order->created_at));
            $orderSections[$key]['order_amount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order->order_amount));
            $orderSections[$key]['expected_delivery_date'] = $order->expected_delivery_date ?? "";
            $orderSections[$key]['payment_method'] = translate($order->payment_method);
            $orderSections[$key]['is_alpha_delivery'] = $order->shipping_method_id == '9' ? true : false;
            $orderSections[$key]['shipping_address_data'] = Helpers::set_order_shipping_data_format(json_decode($order->shipping_address_data, true));
            $orderSections[$key]['billing_address_data'] = Helpers::set_order_shipping_data_format(json_decode($order->billing_address_data, true));
            

            foreach($order->details as $detail) {

                $product = !empty($detail->product) ? Helpers::set_data_format($detail->product) : [];
                
                // dd(, $detail->product->discount, $detail->product->discount_type);

                // dd($detail->product->tax_type);

                $orderSections[$key]['detail'] = $product;
                $orderSections[$key]['variant'] = $detail->variant;
                $orderSections[$key]['detail']['quantity'] = strval($detail->qty ?? 1);
                // $orderSections[$key]['variation'] = $detail->variation != '[]' ? json_decode($detail->variation) : [] ;
                $orderSections[$key]['price_detail']['mrp'] = Helpers::currency_converter($detail->product->unit_price ?? 0);
                $orderSections[$key]['price_detail']['tax'] = Helpers::currency_converter($detail->product->unit_price * $detail->product->tax / 100);
                if($detail->product->discount_type == 'percent') {
                    $orderSections[$key]['price_detail']['subtotal'] = Helpers::currency_converter($detail->product->unit_price - ($detail->product->unit_price * $detail->product->discount / 100));
                    $orderSections[$key]['price_detail']['discount'] = isset($product['discount']) ? Helpers::set_symbol(BackEndHelper::usd_to_currency(($detail->product->unit_price * $detail->product->discount / 100))) : "";
                } else {
                    $orderSections[$key]['price_detail']['subtotal'] = Helpers::currency_converter($detail->product->unit_price - $detail->product->discount);
                    $orderSections[$key]['price_detail']['discount'] = isset($product['discount']) ? Helpers::set_symbol(BackEndHelper::usd_to_currency($detail->product->discount)) : "";
                }
            }
            $orderSections[$key]['price_detail']['coupon_discount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order->shipping_cost));
            $orderSections[$key]['price_detail']['delivery_fee'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order->shipping_cost));
            $orderSections[$key]['price_detail']['total'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order->order_amount));
            $orderSections[$key]['customer'] = $order->customer->f_name ?? '';
            $orderSections[$key]['items_count'] = $order->items_count ?? 0;
        }

        $response = ['status' => true, 'message' => 'Vendor Orders', 'data' => $orderSections, 'order_status' => $status];

        return response()->json($response, 200);
    }

    public function details(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $details = OrderDetail::where(['seller_id' => $seller['id'], 'order_id' => $id])->get();
        foreach ($details as $det) {
            $det['product_details'] = Helpers::product_data_formatting(json_decode($det['product_details'], true));
        }

        return response()->json($details, 200);
    }

    public function assign_delivery_man(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'delivery_man_id' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['status' => false,'message' => translate('Your existing session token does not authorize you any more'), 'errors' => Helpers::error_processor($validator)]);
        }

        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'errors' => []
            ], 401);
        }

        $deliveryman_charge = 1;

        $order = Order::where(['id' => $request['order_id']])->first();
        // $order = Order::where(['seller_id' => $seller['id'], 'id' => $request['order_id']])->first();
        $order->delivery_man_id = $request->delivery_man_id;
        $order->delivery_type = 'self_delivery';
        $order->delivery_service_name = "";
        $order->third_party_delivery_tracking_id = "";
        $order->deliveryman_charge = $deliveryman_charge;
        $order->expected_delivery_date = $request->expected_delivery_date;
        $order->order_status = 'shipped';
        $order->save();

        $st = OrderDeliveryManStatus::where('order_id', $request['order_id'])->first();
        if(empty($st)) {
            $st = new OrderDeliveryManStatus;
        }
        $st->order_id = $request['order_id'];
        $st->delivery_man_id = $request->delivery_man_id;
        $st->status = 0;
        $st->save();

        $fcm_token = isset($order->delivery_man) ? $order->delivery_man->fcm_token:null;
        $value = Helpers::order_status_update_message('del_assign');
        if($value && !empty($fcm_token)) {
            try {
                $data = [
                    'title' => translate('order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];
                if ($order->delivery_man_id) {
                    self::add_deliveryman_push_notification($data, $order->delivery_man_id);
                }
                Helpers::send_push_notif_to_device($fcm_token, $data);
            } catch (\Exception $e) {
            }
        }

        return response()->json(['status' => true, 'message' => translate('order_deliveryman_assigned_successfully'), 'errors' => []], 200);
    }

    public function customers(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'errors' => []
            ], 401);
        }

        $deliveryman_charge = 1;

        $ids = Order::select(DB::raw('DISTINCT customer_id'))->where(['seller_id' => $seller['id'], 'seller_is' => 'seller'])->get()->pluck('customer_id');

        $users = User::select('id', 'f_name', 'email', 'phone', 'street_address', 'country','state','city','zip', 'image')->whereIn('id', $ids)->get();

        foreach ($users as $key => $user) {
            $user->street_address = $user->street_address ?? "";
            $user->country = $user->country ?? "";
            $user->state = $user->state ?? "";
            $user->city = $user->city ?? "";
            $user->zip = $user->zip ?? "";
            $user->image = $user->image != 'def.png' ? asset('storage/app/public/profile/' . $user->image) : "";

            $orders = Order::with('details')->where(['customer_id' => $user->id,'seller_id' => $seller['id'], 'seller_is' => 'seller'])->get();
            $productIds = [];
            foreach ($orders as $key => $order) {
                if($order->details) {
                    foreach ($order->details as $key => $detail) {
                        $productIds[] = $detail->product_id;
                    }
                }
            }
            $user->products = Helpers::product_data_formatting(Product::whereIn('id', $productIds)->get() ?? [], true);
        }

        return response()->json(['status' => true, 'message' => translate('Customers lists'), 'data' => $users, 'errors' => []], 200);
    }

    public function amount_date_update(Request $request){
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $deliveryman_charge = $request->deliveryman_charge;

        $order = Order::find($request->order_id);
        $db_expected_date  = $order->expected_delivery_date;

        $order->deliveryman_charge = $deliveryman_charge;
        $order->expected_delivery_date = $request->expected_delivery_date;

        try {
            DB::beginTransaction();

            if(!empty($request->expected_delivery_date) && $db_expected_date != $request->expected_delivery_date){
                CommonTrait::add_expected_delivery_date_history($request->order_id, $seller['id'], $request->expected_delivery_date, 'seller');
            }
            $order->save();

            DB::commit();
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json(['success' => 0, 'message' => translate('Update fail!')], 403);
        }

        if(!empty($request->expected_delivery_date) && $db_expected_date != $request->expected_delivery_date){
            $fcm_token = isset($order->delivery_man) ? $order->delivery_man->fcm_token:null;
            $value = Helpers::order_status_update_message('expected_delivery_date') . " ID: " . $order['id'];
            if($value != null && !empty($fcm_token)) {
                try {
                    $data = [
                        'title' => translate('order'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'image' => '',
                    ];

                    if ($order->delivery_man_id) {
                        self::add_deliveryman_push_notification($data, $order->delivery_man_id);
                    }
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                } catch (\Exception $e) {
                    return response()->json(['success' => 0, 'message' => translate('Update fail!')], 403);
                }
            }
        }

        return response()->json(['success' => 0, 'message' => translate('Updated successfully!')], 200);
    }

    /**
     *  Digital file upload after sell
     */
    public function digital_file_upload_after_sell(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'digital_file_after_sell' => 'required|mimes:jpg,jpeg,png,gif,zip,pdf',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $order_details = OrderDetail::find($request->order_id);
        if($order_details){
            $order_details->digital_file_after_sell = ImageManager::update('product/digital-product/', $order_details->digital_file_after_sell, $request->digital_file_after_sell->getClientOriginalExtension(), $request->file('digital_file_after_sell'));
            $order_details->save();
            return response()->json(['success' => 1, 'message' => translate('File_upload_successfully')], 200);
        }else{
            return response()->json(['success' => 0, 'message' => translate("File_upload_fail!")], 202);
        }
    }

    public function order_detail_status(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' =>false,
                'message' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $order = Order::find($request->id);
        if(empty($order->customer))
        {
            return response()->json(['status' => false, 'message' => translate("Customer account has been deleted. you can't update status!")], 202);
        }

        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');

        if($request->order_status=='delivered' && $order->payment_status !='paid'){

            return response()->json(['success' => false, 'message' => translate('Before delivered you need to make payment status paid!')],200);
        }

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => false, 'message' => translate('order is already delivered')], 200);
        }

        try {
            $fcm_token = $order->customer->cm_firebase_token;
            $value = Helpers::order_status_update_message($request->order_status);
            if ($value) {
                $notif = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $notif);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => translate("Something went wrong!")], 202);
        }

        try {
            $fcm_token_delivery_man = $order->delivery_man->fcm_token;
            if ($request->order_status == 'canceled' && $value != null && !empty($fcm_token_delivery_man)) {
                $data = [
                    'title' => translate('order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];

                if($order->delivery_man_id) {
                    self::add_deliveryman_push_notification($data, $order->delivery_man_id);
                }
                Helpers::send_push_notif_to_device($fcm_token_delivery_man, $data);
            }
        } catch (\Exception $e) {
        }

        $order->order_status = $request->order_status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'seller');
            OrderDetail::where('order_id', $order->id)->update(
                ['delivery_status'=>'delivered']
            );
        }

        $order->save();

        if ($order->delivery_man_id && $request->order_status == 'delivered') {
            $dm_wallet = DeliverymanWallet::where('delivery_man_id', $order->delivery_man_id)->first();
            $cash_in_hand = $order->payment_method == 'cash_on_delivery' ? $order->order_amount : 0;

            if (empty($dm_wallet)) {
                DeliverymanWallet::create([
                    'delivery_man_id' => $order->delivery_man_id,
                    'current_balance' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'cash_in_hand' => BackEndHelper::currency_to_usd($cash_in_hand),
                    'pending_withdraw' => 0,
                    'total_withdraw' => 0,
                ]);
            } else {
                $dm_wallet->current_balance += BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0;
                $dm_wallet->cash_in_hand += BackEndHelper::currency_to_usd($cash_in_hand);
                $dm_wallet->save();
            }

            if($order->deliveryman_charge && $request->order_status == 'delivered'){
                DeliveryManTransaction::create([
                    'delivery_man_id' => $order->delivery_man_id,
                    'user_id' => $seller->id,
                    'user_type' => 'seller',
                    'credit' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'transaction_id' => Uuid::uuid4(),
                    'transaction_type' => 'deliveryman_charge'
                ]);
            }
        }

        if($wallet_status == 1 && $loyalty_point_status == 1)
        {
            if($request->order_status == 'delivered' && $order->payment_status =='paid'){
                CustomerManager::create_loyalty_point_transaction($order->customer_id, $order->id, Convert::default($order->order_amount-$order->shipping_cost), 'order_place');
            }
        }

        CommonTrait::add_order_status_history($order->id, $seller->id, $request->order_status, 'seller');

        return response()->json(['status' => true, 'message' => translate('order_status_updated_successfully')], 200);
    }

    public function assign_third_party_delivery(Request $request)
    {

        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'errors' => []
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'delivery_service_name' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['status' => false, 'message' => [], 'errors' => Helpers::error_processor($validator)]);
        }

        $order = Order::find($request->order_id);
        $order->delivery_type = 'third_party_delivery';
        $order->delivery_service_name = $request->delivery_service_name;
        $order->third_party_delivery_tracking_id = $request->third_party_delivery_tracking_id;
        $order->delivery_man_id = null;
        $order->deliveryman_charge = 0;
        $order->expected_delivery_date = null;
        $order->save();

        return response()->json(['success' => 1, 'message' => translate('third_party_delivery_assigned_successfully')], 200);
    }

    public function update_payment_status(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id'=>'required',
            'payment_status' => 'required|in:paid,unpaid'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $order = Order::find($request['order_id']);
        if (isset($order)) {
            if(empty($order->customer))
            {
                return response()->json(['success' => 0, 'message' => translate("Customer account has been deleted. you can't update status!")], 202);
            }

            $order->payment_status = $request['payment_status'];
            $order->save();
            return response()->json(['message' => translate('Payment status updated')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('not found!')]
            ]
        ], 404);
    }

    public function transaction_list(Request $request) {

        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $due_amount_order_query = Order::whereNotIn('order_status', ['delivered', 'canceled', 'returned', 'failed'])
        ->where(['seller_is'=>'seller', 'seller_id'=>auth('seller')->id()]);
        $due_amount = self::date_wise_common_filter($due_amount_order_query, 'this_year', '', '')->sum('order_amount');

        $lists = OrderTransaction::where(['seller_id' => $seller['id']])->get();
        $dt = [];
        $amt = 0;
        foreach ($lists as $key => $list) {

            $amt += $list->order_amount;

            $dt[$key]['order_id'] = $list->order_id;
            $dt[$key]['amount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($list->order_amount));
            $dt[$key]['created_at'] = date('d M, Y', strtotime($list->created_at));
            $dt[$key]['payment_method'] = str_replace('_', ' ', ucwords($list->payment_method));
        }
        return response()->json(['status' => true, 'message' => 'Transaction History', 'total_order_amount' => Helpers::set_symbol(BackEndHelper::usd_to_currency($amt)), 'data' => $dt]);
    }

    public function order_report_chart_filter($request)
    {
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';

        if ($date_type == 'this_year') { //this year table
            $number = 12;
            $default_inc = 1;
            $current_start_year = date('Y-01-01');
            $current_end_year = date('Y-12-31');
            $from_year = Carbon::parse($from)->format('Y');

            $this_year = self::order_report_same_year($request, $current_start_year, $current_end_year, $from_year, $number, $default_inc);
            return $this_year;

        } elseif ($date_type == 'this_month') { //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d', strtotime($current_month_end));

            $this_month = self::order_report_same_month($request, $current_month_start, $current_month_end, $month, $number, $inc);
            return $this_month;

        } elseif ($date_type == 'this_week') {
            $this_week = self::order_report_this_week($request);
            return $this_week;

        } elseif ($date_type == 'custom_date' && !empty($from) && !empty($to)) {
            $start_date = Carbon::parse($from)->format('Y-m-d 00:00:00');
            $end_date = Carbon::parse($to)->format('Y-m-d 23:59:59');
            $from_year = Carbon::parse($from)->format('Y');
            $from_month = Carbon::parse($from)->format('m');
            $from_day = Carbon::parse($from)->format('d');
            $to_year = Carbon::parse($to)->format('Y');
            $to_month = Carbon::parse($to)->format('m');
            $to_day = Carbon::parse($to)->format('d');

            if ($from_year != $to_year) {
                $different_year = self::order_report_different_year($request, $start_date, $end_date, $from_year, $to_year);
                return $different_year;

            } elseif ($from_month != $to_month) {
                $same_year = self::order_report_same_year($request, $start_date, $end_date, $from_year, $to_month, $from_month);
                return $same_year;

            } elseif ($from_month == $to_month) {
                $same_month = self::order_report_same_month($request, $start_date, $end_date, $from_month, $to_day, $from_day);
                return $same_month;
            }

        }
    }

    public function order_report_same_year($request, $start_date, $end_date, $from_year, $number, $default_inc)
    {
        $orders = self::order_report_chart_common_query($start_date, $end_date)
            ->selectRaw('sum(order_amount) as order_amount, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $order_amount[$month . '-' . $from_year] = 0;
            foreach ($orders as $match) {
                if ($match['month'] == $inc) {
                    $order_amount[$month . '-' . $from_year] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }

    public function order_report_same_month($request, $start_date, $end_date, $month_date, $number, $default_inc)
    {
        $year_month = date('Y-m', strtotime($start_date));
        $month = date("F", strtotime("$year_month"));

        $orders = self::order_report_chart_common_query($start_date, $end_date)
            ->selectRaw('sum(order_amount) as order_amount, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $day = date('jS', strtotime("$year_month-$inc"));
            $order_amount[$day . '-' . $month] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $inc) {
                    $order_amount[$day . '-' . $month] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }

    public function order_report_this_week($request)
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();

        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        $orders = self::order_report_chart_common_query($start_date, $end_date)
            ->select(
                DB::raw('sum(order_amount) as order_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $order_amount[$day_name[$inc]] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $order_amount[$day_name[$inc]] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }

    public function order_report_different_year($request, $start_date, $end_date, $from_year, $to_year)
    {
        $orders = self::order_report_chart_common_query($start_date, $end_date)
            ->selectRaw('sum(order_amount) as order_amount, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $order_amount[$inc] = 0;
            foreach ($orders as $match) {
                if ($match['year'] == $inc) {
                    $order_amount[$inc] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }

    public function all_order_table_data_filter($request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';

        $orders_query = Order::withSum('details', 'tax')
            ->withSum('details', 'discount')
            ->when($search, function ($q) use ($search) {
                $q->orWhere('id', 'like', "%{$search}%");
            })
            ->where(['seller_is'=>'seller', 'seller_id'=>auth('seller')->id()]);
        $orders = self::date_wise_common_filter($orders_query, $date_type, $from, $to);

        return $orders;
    }

    public function date_wise_common_filter($query, $date_type, $from, $to)
    {
        return $query->when(($date_type == 'this_year'), function ($query) {
            return $query->whereYear('updated_at', date('Y'));
        })
            ->when(($date_type == 'this_month'), function ($query) {
                return $query->whereMonth('updated_at', date('m'))
                    ->whereYear('updated_at', date('Y'));
            })
            ->when(($date_type == 'this_week'), function ($query) {
                return $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })
            ->when(($date_type == 'custom_date' && !is_null($from) && !is_null($to)), function ($query) use ($from, $to) {
                return $query->whereDate('updated_at', '>=', $from)
                    ->whereDate('updated_at', '<=', $to);
            });
    }

    public function pie_chart_and_order_count_common_query($request, $query){
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';

        $query_f = $query->where(['seller_is'=>'seller', 'seller_id'=>auth('seller')->id()]);
        return self::date_wise_common_filter($query_f, $date_type, $from, $to);
    }

    public function order_report_export_excel(Request $request){
        $orders = self::all_order_table_data_filter($request)->latest('updated_at')->get();

        $data = array();
        foreach ($orders as $order) {
            $data[] = array(
                'Order ID' => $order->id,
                'Total Amount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->order_amount)),
                'Product Discount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->details_sum_discount)),
                'Coupon Discount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->discount_amount)),
                'Shipping Charge' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->shipping_cost)),
                'VAT/TAX' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->details_sum_tax)),
                'Commission' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->admin_commission)),
                'Status' => BackEndHelper::order_status($order->order_status)
            );
        }

        return (new FastExcel($data))->download('order_report_list.xlsx');
    }

    public function order_report_chart_common_query($start_date, $end_date)
    {
        return Order::where(['seller_is'=>'seller', 'seller_id'=>auth('seller')->id(), 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date);
    }

}
