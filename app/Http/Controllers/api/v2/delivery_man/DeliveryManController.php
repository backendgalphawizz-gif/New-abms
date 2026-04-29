<?php

namespace App\Http\Controllers\api\v2\delivery_man;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\DeliveryHistory;
use App\Model\DeliveryMan;
use App\Model\DeliverymanNotification;
use App\Model\DeliveryManTransaction;
use App\Model\DeliverymanWallet;
use App\Model\EmergencyContact;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Review;
use App\Traits\CommonTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;
use App\CPU\OrderManager;
use App\Model\HelpTopic;
use App\Model\OrderDeliveryManStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class DeliveryManController extends Controller
{
    use CommonTrait;

    public function info(Request $request)
    {
        $d_man = $request['delivery_man'];
        $delivery_man = DeliveryMan::where(['id' => $d_man['id']])->with(['review'])->first();
        $wallet = DeliverymanWallet::where('delivery_man_id', $request['delivery_man']['id'])->first();
        $withdrawable_balance = CommonTrait::delivery_man_withdrawable_balance($request['delivery_man']['id']);
        $total_earn = CommonTrait::delivery_man_total_earn($request['delivery_man']['id']);
        $order = Order::where('delivery_man_id', $request['delivery_man']['id'])->get();
        $completed_delivery = $order->where('order_status', 'delivered')->count();
        $pause_delivery = $order->where('is_pause', 1)->count();
        $pending_delivery = $order->whereIn('order_status', ['pending', 'confirmed'])->count();
        $failed_delivery = $order->where('order_status', 'failed')->count();
        $total_deposit = DeliveryManTransaction::where(['delivery_man_id' => $request['delivery_man']['id'], 'transaction_type' => 'cash_in_hand'])->sum('credit');

        // dd($wallet);
        $request['delivery_man']['image'] = $request['delivery_man']['image'] != "" ? asset('storage/app/public/delivery-man/' . $request['delivery_man']['image']) : "";
        $request['delivery_man']['vehicle_image'] = $request['delivery_man']['vehicle_image'] != "" ? asset('storage/app/public/delivery-man/' . $request['delivery_man']['vehicle_image']) : "";
        $request['delivery_man']['license_image'] = $request['delivery_man']['license_image'] != "" ? asset('storage/app/public/delivery-man/' . $request['delivery_man']['license_image']) : "";
        $request['delivery_man']['bank_name'] = $request['delivery_man']['bank_name'] ?? "";
        $request['delivery_man']['branch'] = $request['delivery_man']['branch'] ?? "";
        $request['delivery_man']['account_no'] = $request['delivery_man']['account_no'] ?? "";
        $request['delivery_man']['holder_name'] = $request['delivery_man']['holder_name'] ?? "";

        $request['delivery_man']['seller_id'] = strval($request['delivery_man']['seller_id'] ?? "");
        $request['delivery_man']['l_name'] = $request['delivery_man']['l_name'] ?? "";
        $request['delivery_man']['country_code'] = $request['delivery_man']['country_code'] ?? "";
        $request['delivery_man']['identity_number'] = $request['delivery_man']['identity_number'] ?? "";
        $request['delivery_man']['identity_type'] = $request['delivery_man']['identity_type'] ?? "";
        $request['delivery_man']['identity_image'] = $request['delivery_man']['identity_image'] ?? "";

        $request['delivery_man']['vehicle_type'] = $request['delivery_man']['vehicle_type'] ?? "";
        $request['delivery_man']['registeration_number'] = $request['delivery_man']['registeration_number'] ?? "";
        $request['delivery_man']['issue_date'] = $request['delivery_man']['issue_date'] ?? "";
        $request['delivery_man']['expiration_date'] = $request['delivery_man']['expiration_date'] ?? "";
        $request['delivery_man']['policy_number'] = $request['delivery_man']['policy_number'] ?? "";
        $request['delivery_man']['coverage_date'] = $request['delivery_man']['coverage_date'] ?? "";

        $request['delivery_man']['withdrawable_balance'] = strval(BackEndHelper::usd_to_currency($withdrawable_balance));
        $request['delivery_man']['current_balance'] = strval(BackEndHelper::usd_to_currency($wallet?->current_balance ?? "0"));

        $request['delivery_man']['cash_in_hand'] = strval(BackEndHelper::usd_to_currency($wallet?->cash_in_hand) ?? "0");
        $request['delivery_man']['pending_withdraw'] = strval($wallet->pending_withdraw ?? "0");
        $request['delivery_man']['total_withdraw'] = strval($wallet->total_withdraw ?? "0");
        $request['delivery_man']['total_earn'] = strval($total_earn);
        $request['delivery_man']['total_delivery'] = "1"; // strval($completed_delivery);
        $request['delivery_man']['pending_delivery'] = strval($pending_delivery);
        $request['delivery_man']['completed_delivery'] = strval($completed_delivery);
        $request['delivery_man']['pause_delivery'] = strval($failed_delivery);
        // $request['delivery_man']['failed_delivery'] = strval($failed_delivery);
        $request['delivery_man']['total_deposit'] = strval($total_deposit);
        $request['delivery_man']['average_rating'] = count($delivery_man->rating) > 0 ? number_format($delivery_man->rating[0]->average, 2, '.', ' ') : "0";

        return response()->json(['status' => true, 'message' => 'Delivery Man Profile', 'data' => [$request['delivery_man']]], 200);
    }

    public function delete_account(Request $request)
    {
        $d_man = $request['delivery_man'];
        $delivery_man = DeliveryMan::where(['id' => $d_man['id']])->first();
        $delivery_man->delete();

        return response()->json(['status' => true, 'message' => 'Account deleted success', 'data' => []], 200);
    }

    public function get_current_orders(Request $request)
    {
        $d_man = $request['delivery_man'];

        $stat = $request->input('delivery_type');
        if ($stat == 'all') {
            $orders = Order::with(['shippingAddress', 'customer', 'seller.shop'])
                ->whereIn('order_status', ['pending', 'processing', 'out_for_delivery', 'confirmed', 'shipped', 'delivered'])
                ->where(['delivery_man_id' => $d_man['id']])
                ->orderBy('expected_delivery_date', 'asc')
                ->get();
        } else {
            $orders = Order::with(['shippingAddress', 'customer', 'seller.shop'])
                ->whereIn('order_status', ['pending', 'processing', 'out_for_delivery', 'confirmed', 'shipped', 'delivered'])
                ->where(['delivery_man_id' => $d_man['id']])
                ->where(['shipping_method_id' => 9])
                ->orderBy('expected_delivery_date', 'asc')
                ->get();
        }


        $dt = [];
        foreach ($orders as $key => $order) {
            $dt[] = [
                'order_id' => $order->id,
                'order_status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'expected_delivery_date' => $order->expected_delivery_date != "" ? date('d M, Y', strtotime($order->expected_delivery_date)) : "",
                'customer_name' => $order->customer ? $order->customer->f_name . ' ' . $order->customer->l_name : '',
                'total_delivery' => count($order->details),
                'shipping_address' => Helpers::set_order_shipping_data_format(json_decode($order->shipping_address_data, true))
            ];
        }

        $tab = [
            'all' => 'All Delivery',
            'alpha_delivery' => 'Alpha Delivery'
        ];

        $response = ['status' => true, 'message' => 'All Running Orders', 'tab' => $tab, 'data' => $dt];
        return response()->json($response, 200);
    }
    public function get_all_status_orders(Request $request)
    {
        $d_man = $request['delivery_man'];
        $deliveryType = $request->input('delivery_type');
        $requestedStatus = $request->input('order_status'); // Get order_status from request

        // Base query
        $query = Order::with(['shippingAddress', 'customer', 'seller.shop'])
            ->where('delivery_man_id', $d_man['id']);

        // Apply delivery_type filter
        // if ($deliveryType !== 'all') {
        //     $query->where('shipping_method_id', 9);
        // }

        $allowedStatuses = ['pending', 'processing', 'out_for_delivery', 'confirmed', 'shipped', 'delivered', 'failed'];

        if ($requestedStatus && in_array($requestedStatus, $allowedStatuses)) {
            $query->where('order_status', $requestedStatus);
        } else {
            $query->whereIn('order_status', $allowedStatuses);
        }

        $orders = $query->orderBy('expected_delivery_date', 'asc')->get();

        $dt = [];
        foreach ($orders as $order) {
            $dt[] = [
                'order_id' => $order->id,
                'order_status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'expected_delivery_date' => $order->expected_delivery_date != "" ? date('d M, Y', strtotime($order->expected_delivery_date)) : "",
                'customer_name' => $order->customer ? $order->customer->f_name . ' ' . $order->customer->l_name : '',
                'total_delivery' => count($order->details),
                'shipping_address' => Helpers::set_order_shipping_data_format(json_decode($order->shipping_address_data, true))
            ];
        }

        $tab = [
            'all' => 'All Delivery',
            'alpha_delivery' => 'Alpha Delivery'
        ];

        $response = ['status' => true, 'message' => 'Filtered Orders', 'tab' => $tab, 'data' => $dt];
        return response()->json($response, 200);
    }


    public function get_new_orders(Request $request)
    {
        $d_man = $request['delivery_man'];
        $orders = Order::with(['shippingAddress', 'customer', 'seller.shop', 'delivery_man_status'])
            ->whereHas('delivery_man_status', function ($q) {
                $q->where('status', 0);
            })
            // ->whereIn('order_status', ['pending', 'processing', 'out_for_delivery', 'confirmed'])
            ->where(['delivery_man_id' => $d_man['id']])
            ->orderBy('id', 'DESC')
            ->get();

        $dt = [];
        foreach ($orders as $key => $order) {
            $dt[] = [
                'order_id' => $order->id,
                'customer_name' => $order->customer ? $order->customer->f_name . ' ' . $order->customer->l_name : '',
                'product_name' => $order->details[0]->product->name ?? "",
                'total_delivery' => count($order->details),
                'shipping_address' => Helpers::set_order_shipping_data_format(json_decode($order->shipping_address_data, true))
            ];
        }

        $response = ['status' => true, 'message' => 'All Running Orders', 'data' => $dt];
        return response()->json($response, 200);
    }

    public function record_location_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'location' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        DB::table('delivery_histories')->insert([
            'order_id' => $request['order_id'],
            'deliveryman_id' => $d_man['id'],
            'longitude' => $request['longitude'],
            'latitude' => $request['latitude'],
            'time' => now(),
            'location' => $request['location'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['message' => 'location recorded'], 200);
    }

    public function get_order_history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $d_man = $request['delivery_man'];
        $history = DeliveryHistory::where(['order_id' => $request['order_id'], 'deliveryman_id' => $d_man['id']])->get();
        return response()->json($history, 200);
    }

    public function update_order_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'status' => 'required|in:delivered,canceled,returned,out_for_delivery'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        $cause = null;
        if ($request['status'] == 'canceled') {
            $cause = $request['cause'];
        }

        $order = Order::where(['id' => $request['order_id']])->first();

        if ($order->order_status == 'delivered') {
            return response()->json(['status' => false, 'message' => 'order is already delivered.', 'errors' => []], 200);
        }

        Order::where(['id' => $request['order_id'], 'delivery_man_id' => $d_man['id']])->update([
            'order_status' => $request['status'],
            'cause' => $cause
        ]);

        if (isset($d_man['id']) && $request['status'] == 'delivered') {

            $order = Order::find($request->input('order_id'));
            $order->payment_status = 'paid';
            $order->save();

            $dm_wallet = DeliverymanWallet::where('delivery_man_id', $d_man['id'])->first();
            $cash_in_hand = $order->payment_method == 'cash_on_delivery' ? $order->order_amount : 0;
            $cash_in_hand = $request->pay_by == 'cash_in_hand' ? $order->order_amount : 0;

            if (empty($dm_wallet)) {
                DeliverymanWallet::create([
                    'delivery_man_id' => $d_man['id'],
                    'current_balance' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'cash_in_hand' => BackEndHelper::currency_to_usd($cash_in_hand),
                    'pending_withdraw' => 0,
                    'total_withdraw' => 0,
                ]);
            } else {
                $dm_wallet->cash_in_hand += BackEndHelper::currency_to_usd($cash_in_hand);
                $dm_wallet->current_balance += BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0;
                $dm_wallet->save();
            }

            if ($order->deliveryman_charge && $request['status'] == 'delivered') {
                DeliveryManTransaction::create([
                    'delivery_man_id' => $order->delivery_man_id,
                    'user_id' => 0,
                    'user_type' => 'admin',
                    'credit' => BackEndHelper::currency_to_usd($order->deliveryman_charge),
                    'transaction_id' => Uuid::uuid4(),
                    'transaction_type' => 'deliveryman_charge'
                ]);
            }
        }

        try {
            $fcm_token = isset($order->customer) ? $order->customer->cm_firebase_token : null;
            if ($request['status'] == 'out_for_delivery') {
                $value = Helpers::order_status_update_message('ord_start');
            } elseif ($request['status'] == 'delivered') {
                $value = Helpers::order_status_update_message('delivered');
            }

            if ($value && !empty($fcm_token)) {
                $data = [
                    'title' => translate('order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];

                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        } catch (\Exception $e) {
        }

        OrderManager::stock_update_on_order_status_change($order, $request['status']);

        if ($request['status'] == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'delivery man');
            OrderDetail::where('order_id', $order->id)->update(
                ['delivery_status' => 'delivered']
            );
        }

        CommonTrait::add_order_status_history($order->id, $d_man['id'], $request['status'], 'delivery_man', $request['cause']);

        return response()->json(['status' => true, 'message' => 'Order status updated successfully!', 'errors' => []], 200);
    }

    public function update_expected_delivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'expected_delivery_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        $order = Order::where(['delivery_man_id' => $d_man['id'], 'id' => $request['order_id']])->first();

        if ($order->order_status == 'delivered') {
            return response()->json(['status' => false, 'message' => 'order is already delivered.'], 200);
        }

        $order->expected_delivery_date = $request['expected_delivery_date'];
        $order->cause = $request['cause'];
        $order->save();
        CommonTrait::add_expected_delivery_date_history($order->id, $d_man['id'], $request['expected_delivery_date'], 'delivery_man', $request['cause']);

        return response()->json(['status' => true, 'message' => 'Order status updated successfully!'], 200);
    }

    public function order_update_is_pause(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'is_pause' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        $order = Order::where(['delivery_man_id' => $d_man['id'], 'id' => $request['order_id']])->first();

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => 'order is already delivered.'], 200);
        }

        $order->is_pause = $request['is_pause'];
        $order->cause = $request['cause'];
        $order->save();

        return response()->json(['message' => 'Order status updated successfully!'], 200);
    }

    public function get_order_details(Request $request)
    {
        $d_man = $request['delivery_man'];
        $order = Order::with(['details'])->where(['delivery_man_id' => $d_man['id'], 'id' => $request['order_id']])->first();
        $response = ['status' => false, 'message' => 'Invalid Order', 'data' => []];
        $statusCode = 403;
        if ($order) {
            $details = $order->details ?? [];
            $dt = [];
            $dt['order_id'] = $order->id;
            $dt['customer_name'] = $order->customer ? $order->customer->f_name . ' ' . $order->customer->l_name : '';
            $dt['order_status'] = $order->order_status;
            $dt['payment_status'] = $order->payment_status;
            $dt['expected_delivery_date'] = $order->expected_delivery_date != "" ? date('d M, Y', strtotime($order->expected_delivery_date)) : "";
            $dt['customer_mobile'] = $order->customer->phone;
            $dt['order_status'] = translate($order->order_status);
            $dt['shipping_address'] = Helpers::set_order_shipping_data_format(json_decode($order->shipping_address_data, true));
            $productPrice = 0;
            $productDiscount = 0;
            $productTax = 0;
            $subtotal = 0;
            // dd($details);
            foreach ($details as $okey => $det) {
                // dd($det->qty);

                // $product = Helpers::set_data_format($det->product);
                $dt['detail'][$okey] = Helpers::product_data_formatting(json_decode($det['product_details'], true));;
                $dt['detail'][$okey]['item_id'] = $det->id;
                $special_price = $unit_price = $det->product->unit_price;
                $subtotal += $unit_price;
                $tax_amount = 0;
                if ($det->product->tax_model == 'exclude' && $det->product->tax_type == 'percent') {
                    $tax_amount = ($special_price * $det->product->tax) / 100;
                }
                $special_price += $tax_amount;

                $discount = $det->product->discount_type == 'flat' ? $det->product->discount : 0;
                if ($det->product->discount > 0 && $det->product->discount_type == 'percent') {
                    $special_price = $special_price - ($special_price * $det->product->discount) / 100;
                } else if ($det->product->discount > 0 && $det->product->discount_type == 'flat') {
                    $special_price = $special_price - $det->product->discount;
                }

                $productDiscount += $discount;
                $productPrice += $special_price;
                $productTax += $tax_amount;

                $det['variation'] = json_decode($det['variation']);
                $det['product_details'] = Helpers::product_data_formatting(json_decode($det['product_details'], true));
                $det['shop'] = $det->product->seller;
                $dt['detail'][$okey]['qty'] = $det->qty; 
            }

            $dt['order_history'] = $order->order_status_history;
            $dt['price_detail']['subtotal'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($subtotal));
            $dt['price_detail']['item_price'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($productPrice));
            $dt['price_detail']['discount'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($productDiscount));
            $dt['price_detail']['delivery_fee'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order->shipping_cost));
            $dt['price_detail']['tax'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($productTax));
            $dt['price_detail']['total'] = Helpers::set_symbol(BackEndHelper::usd_to_currency($order->order_amount));

            $response = ['status' => true, 'message' => 'Order Detail', 'data' => $dt];
            $statusCode = 200;
        }

        return response()->json($response, $statusCode);
    }

    public function get_all_orders(Request $request)
    {
        $delivery_man = $request['delivery_man'];

        $orders = Order::with(['shippingAddress', 'customer', 'seller.shop'])
            ->where(['delivery_man_id' => $delivery_man->id])
            ->when(!empty($request->search), function ($query) use ($request) {
                $query->where('id', 'like', "%{$request->search}%")
                    ->orWhere(function ($query) use ($request) {
                        $query->whereHas('customer', function ($query) use ($request) {
                            $query->where('phone', 'like', "%{$request->search}%");
                        });
                    });
            })
            ->when(!empty($request->status), function ($query) use ($request) {
                $query->where('order_status', $request['status']);
            })
            ->when(!empty($request->is_pause), function ($query) use ($request) {
                $query->where('is_pause', $request['is_pause']);
            })
            ->when(!empty($request->start_date) && !empty($request->end_date), function ($query) use ($request) {
                $start_date = Carbon::parse($request['start_date'])->format('Y-m-d 00:00:00');
                $end_data = Carbon::parse($request['end_date'])->format('Y-m-d 23:59:59');

                $query->whereBetween('created_at', [$start_date, $end_data]);
            })
            ->latest()->get();

        return response()->json($orders, 200);
    }

    public function get_last_location(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $last_data = DeliveryHistory::where(['order_id' => $request['order_id']])->latest()->first();
        return response()->json($last_data, 200);
    }

    public function order_payment_status_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'payment_status' => 'required|in:paid'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        $order = Order::where(['delivery_man_id' => $d_man['id'], 'id' => $request['order_id']])->first();
        if (!empty($order)) {
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

    public function update_fcm_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $d_man = $request['delivery_man'];
        DeliveryMan::where(['id' => $d_man['id']])->update([
            'fcm_token' => $request['fcm_token']
        ]);

        return response()->json(['message' => 'successfully updated!'], 200);
    }

    public function delivery_wise_earned(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'required',
            'limit' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $dateType = $request->type;
        $delivery_man = $request->delivery_man;

        $order = Order::with(['seller.shop', 'customer'])->where(['delivery_man_id' => $delivery_man->id, 'payment_status' => 'paid']);

        if (isset($request->start_date) && isset($request->end_date)) {
            $start_date = Carbon::parse($request['start_date'])->format('Y-m-d 00:00:00');
            $end_data = Carbon::parse($request['end_date'])->format('Y-m-d 23:59:59');

            $order->whereBetween('updated_at', [$start_date, $end_data]);
        } elseif ($dateType == 'TodayEarn') {
            $start_time = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $end_time = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

            $order->whereBetween('created_at', [$start_time, $end_time]);
        } elseif ($dateType == 'ThisWeekEarn') {
            $start_date = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
            $end_data = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');

            $order->whereBetween('created_at', [$start_date, $end_data]);
        } elseif ($dateType == 'ThisMonthEarn') {
            $start_date = date('Y-m-01 00:00:00');
            $end_data = date('Y-m-t 23:59:59');

            $order->whereBetween('created_at', [$start_date, $end_data]);
        }

        $orders = $order->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data['total_size'] = $orders->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['orders'] = $orders->items();
        return response()->json($data, 200);
    }

    public function search(Request $request)
    {

        $delivery_man = $request['delivery_man'];
        $order = Order::where('id', 'like', '%' . $request->input('search') . '%')
            ->where('delivery_man_id', $delivery_man->id)->get();

        if (empty(json_decode($order))) {
            $terms = explode(" ", $request->input('search'));

            $users = User::where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('f_name', 'like', '%' . $term . '%')
                        ->orWhere('l_name', 'like', '%' . $term . '%');
                }
            })->pluck('id');

            $order = Order::whereIn('customer_id', $users)->where('delivery_man_id', $delivery_man->id)->get();

            if (!empty(json_decode($order))) {
                return response()->json($order, 200);
            }
            return response()->json('No Result Found', 400);
        }

        return response()->json($order, 200);
    }

    public function profile_dashboard_counts(Request $request)
    {
        $delivery_man = $request['delivery_man'];
        $orders = Order::where('delivery_man_id', $delivery_man->id);
        $data = DeliverymanWallet::where('delivery_man_id', $delivery_man->id)->first();

        $data['total_delivery_count'] = $orders->count();
        $data['delivered_orders'] = $orders->where('order_status', 'delivered')->count();
        return response()->json($data);
    }

    public function change_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Status required', 'errors' => Helpers::error_processor($validator)], 403);
        }
        $delivery_man = $request['delivery_man'];
        $delivery_man = DeliveryMan::find($delivery_man->id);
        $delivery_man->is_active = $request->status;

        if ($delivery_man->save()) {
            return response()->json(['status' => true, 'message' => 'Status changed successfully', 'errors' => []], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Status change failed!', 'errors' => []], 403);
        }
    }

    public function update_info(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'f_name' => 'required',
                'l_name' => 'required',
                'password' => 'nullable|same:confirm_password|min:8',
            ],
            [
                'f_name.required' => 'The first name field is required.',
                'l_name.required' => 'The last name field is required.'
            ]
        );

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $delivery_man = DeliveryMan::find($request['delivery_man']->id);
        $image = $request->file('image');
        if ($image != null) {
            $imageName = ImageManager::update('delivery-man/', $delivery_man->image, 'png', $request->file('image'));
        } else {
            $imageName = $delivery_man->image;
        }

        $delivery_man->f_name = $request['f_name'];
        $delivery_man->l_name = $request['l_name'];
        $delivery_man->address = $request['address'];
        $delivery_man->image = $imageName;
        if (!empty($request->password)) {
            $delivery_man->password = bcrypt(str_replace(' ', '', $request['password']));
        }

        if ($delivery_man->save()) {
            return response()->json(['message' => translate('Profile updated successfully')], 200);
        } else {
            return response()->json(['message' => translate('Profile update failed!'), 403]);
        }
    }

    public function update_profile_image(Request $request)
    {
        $delivery_man = DeliveryMan::find($request['delivery_man']->id);
        if ($request->has('image')) {
            $image = $request->file('image');
            if ($image != null) {
                $imageName = ImageManager::update('delivery-man/', $delivery_man->image, 'png', $request->file('image'));
            } else {
                $imageName = $delivery_man->image;
            }
        } else {
            return response()->json(['status' => false, 'message' => translate('Please select image!')], 403);
        }
        $delivery_man->image = $imageName;

        if ($delivery_man->save()) {
            return response()->json(['status' => true, 'message' => translate('Profile Image updated successfully')], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('Profile update failed!')], 403);
        }
    }

    public function edit_profile(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required'
            ],
            [
                'name.required' => 'The name field is required.',
                'email.required' => 'The email field is required.',
                'phone.required' => 'The phone field is required.'
            ]
        );

        if ($validator->errors()->count() > 0) {
            return response()->json(['status' => false, 'message' => translate('Profile update failed!'), 'errors' => Helpers::error_processor($validator)]);
        }

        $delivery_man = DeliveryMan::find($request['delivery_man']->id);
        $delivery_man->f_name = $request['name'];
        $delivery_man->email = $request['email'];
        $delivery_man->phone = $request['phone'];

        if ($delivery_man->save()) {
            return response()->json(['status' => true, 'message' => translate('Profile updated successfully'), 'errors' => []], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('Profile update failed!'), 'errors' => []], 403);
        }
    }

    public function update_password(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'old_password' => 'required',
                'password' => 'required|same:confirm_password',
                'confirm_password' => 'required'
            ],
            [
                'old_password.required' => 'The name field is required.',
                'password.required' => 'The email field is required.',
                'confirm_password.required' => 'The phone field is required.'
            ]
        );

        if ($validator->errors()->count() > 0) {
            return response()->json(['status' => false, 'message' => translate('Please fill all required fields!'), 'errors' => Helpers::error_processor($validator)]);
        }

        $delivery_man = DeliveryMan::find($request['delivery_man']->id);
        if (Hash::check($request->input('old_password'), $delivery_man->password)) {
            return response()->json(['status' => false, 'message' => translate('Old Password not matched!'), 'errors' => []]);
        }
        $delivery_man->password = Hash::make($request->input('old_password'));
        if ($delivery_man->save()) {
            return response()->json(['status' => true, 'message' => translate('Password updated successfully'), 'errors' => []], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('Password update failed!'), 'errors' => []], 403);
        }
    }

    public function edit_vehicle_profile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required',
            'registeration_number' => 'required',
            'issue_date' => 'required',
            'expiration_date' => 'required',
            'policy_number' => 'required',
            'coverage_date' => 'required'
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['status' => false, 'message' => translate('Profile update failed!'), 'errors' => Helpers::error_processor($validator)]);
        }

        $delivery_man = DeliveryMan::find($request['delivery_man']->id);

        $delivery_man->vehicle_type = $request['vehicle_type'];
        $delivery_man->registeration_number = $request['registeration_number'];
        $delivery_man->issue_date = $request['issue_date'];
        $delivery_man->expiration_date = $request['expiration_date'];
        $delivery_man->policy_number = $request['policy_number'];
        $delivery_man->coverage_date = $request['coverage_date'];

        if ($request->has('vehicle_image')) {
            $image = $request->file('vehicle_image');
            if ($image != null) {
                $imageName = ImageManager::update('delivery-man/', $delivery_man->vehicle_image, 'png', $request->file('vehicle_image'));
            } else {
                $imageName = $delivery_man->vehicle_image;
            }
        }
        $delivery_man->vehicle_image = $imageName;
        if ($delivery_man->save()) {
            return response()->json(['status' => true, 'message' => translate('Vehicle Details updated successfully'), 'errors' => []], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('Something went wrong!'), 'errors' => []], 403);
        }
    }

    public function edit_driving_licence(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'license_number' => 'required',
            'license_doi' => 'required',
            'license_exp_date' => 'required',
            'license_state' => 'required'
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['status' => false, 'message' => translate('Profile update failed!'), 'errors' => Helpers::error_processor($validator)]);
        }

        $delivery_man = DeliveryMan::find($request['delivery_man']->id);

        $delivery_man->license_number = $request['license_number'];
        $delivery_man->license_doi = $request['license_doi'];
        $delivery_man->license_exp_date = $request['license_exp_date'];
        $delivery_man->license_state = $request['license_state'];

        if ($request->has('license_image')) {
            $image = $request->file('license_image');
            if ($image != null) {
                $imageName = ImageManager::update('delivery-man/', $delivery_man->license_image, 'png', $request->file('license_image'));
            } else {
                $imageName = $delivery_man->license_image;
            }
            $delivery_man->license_image = $imageName;
        }
        if ($delivery_man->save()) {
            return response()->json(['status' => true, 'message' => translate('Vehicle Details updated successfully'), 'errors' => []], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('Something went wrong!'), 'errors' => []], 403);
        }
    }

    public function edit_address(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'country' => 'required'
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['status' => false, 'message' => translate('Profile update failed!'), 'errors' => Helpers::error_processor($validator)]);
        }

        $delivery_man = DeliveryMan::find($request['delivery_man']->id);
        $delivery_man->address = $request['address'];
        $delivery_man->city = $request['city'];
        $delivery_man->state = $request['state'];
        $delivery_man->pincode = $request['pincode'];
        $delivery_man->country = $request['country'];
        if ($delivery_man->save()) {
            return response()->json(['status' => true, 'message' => translate('Address updated successfully'), 'errors' => []], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('Something went wrong!'), 'errors' => []], 403);
        }
    }

    public function edit_bank_info(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'branch_name' => 'required',
            'account_type' => 'required',
            'micr_code' => 'required',
            'bank_address' => 'required',
            'account_number' => 'required',
            'ifsc_code' => 'required'
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['status' => false, 'message' => translate('Bank Info update failed!'), 'errors' => Helpers::error_processor($validator)]);
        }

        $delivery_man = DeliveryMan::find($request['delivery_man']->id);
        $delivery_man->bank_name = $request['bank_name'];
        $delivery_man->branch = $request['branch_name'];
        $delivery_man->account_no = $request['account_number'];

        $delivery_man->account_type = $request['account_type'];
        $delivery_man->micr_code = $request['micr_code'];
        $delivery_man->bank_address = $request['bank_address'];
        $delivery_man->ifsc_code = $request['ifsc_code'];
        if ($delivery_man->save()) {
            return response()->json(['status' => true, 'message' => translate('Bank Info updated successfully'), 'errors' => []], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('Something went wrong!'), 'errors' => []], 403);
        }
    }

    public function bank_info(Request $request)
    {
        $delivery_man = $request['delivery_man'];
        $delivery_man->bank_name = $request->bank_name;
        $delivery_man->branch = $request->branch;
        $delivery_man->account_no = $request->account_no;
        $delivery_man->holder_name = $request->holder_name;

        if ($delivery_man->save()) {
            return response()->json(['message' => translate('Bank Info updated successfully')], 200);
        } else {
            return response()->json(['message' => translate('Bank Info update failed!'), 403]);
        }
    }

    public function collected_cash_history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'required',
            'limit' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $dateType = $request->type;
        $delivery_man_id = $request['delivery_man']->id;

        $collect_cash_history = DeliveryManTransaction::where(['delivery_man_id' => $delivery_man_id, 'transaction_type' => $request->transaction_type ?? 'cash_in_hand']);

        if (isset($request->start_date) && isset($request->end_date)) {
            $start_date = Carbon::parse($request['start_date'])->format('Y-m-d 00:00:00');
            $end_data = Carbon::parse($request['end_date'])->format('Y-m-d 23:59:59');

            $collect_cash_history->whereBetween('created_at', [$start_date, $end_data]);
        } elseif ($dateType == 'TodayPaid') {
            $start_time = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $end_time = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

            $collect_cash_history->whereBetween('created_at', [$start_time, $end_time]);
        } elseif ($dateType == 'ThisWeekPaid') {
            $start_date = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
            $end_data = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');

            $collect_cash_history->whereBetween('created_at', [$start_date, $end_data]);
        } elseif ($dateType == 'ThisMonthPaid') {
            $start_date = date('Y-m-01 00:00:00');
            $end_data = date('Y-m-t 23:59:59');

            $collect_cash_history->whereBetween('created_at', [$start_date, $end_data]);
        }
        $collect_cash_history = $collect_cash_history->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $collect_cash_history->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['deposit'] = $collect_cash_history->items();

        return response()->json($data, 200);
    }

    public function wallet_transaction_history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'required',
            'limit' => 'required',
        ]);
        if ($validator->fails()) {

            $delivery_man_id = $request['delivery_man']->id;

            $error = array();
            $error['total_size'] = 0;
            $error['total_amount'] = "0.00";
            $error['total_orders'] = "0";
            $error['limit'] = $request['limit'] ?? "0";
            $error['offset'] = $request['offset'] ?? "0";
            $error['data'] = [];
            $error['status'] = false;
            $error['message'] = 'Something went wrong';

            return response()->json($error, 403);
        }

        $delivery_man_id = $request['delivery_man']->id;

        $collect_cash_history = DeliveryManTransaction::where(['delivery_man_id' => $delivery_man_id, 'transaction_type' => 'deliveryman_charge']);
        $collect_cash_history = $collect_cash_history->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $lists = $collect_cash_history->items();

        foreach ($lists as $key => $list) {

            $list->credit = strval(BackEndHelper::usd_to_currency($list->credit));
            $list->debit = strval(BackEndHelper::usd_to_currency($list->debit));
            $list->transaction_type = ucwords(str_replace('_', ' ', $list->transaction_type));
            $list->created_at = date('d M,Y', strtotime($list->created_at));
        }

        $wallet = DeliverymanWallet::where('delivery_man_id', $delivery_man_id)->first();

        $data = array();
        $data['total_size'] = $collect_cash_history->total();
        $data['total_amount'] = strval(BackEndHelper::usd_to_currency(($wallet->current_balance) ?? 0));
        $data['total_orders'] = strval(Order::where('delivery_man_id', $delivery_man_id)->count() ?? 0);
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['data'] = $lists;
        $data['status'] = true;
        $data['message'] = 'Delivery man Transactions';

        return response()->json($data, 200);
    }

    public function emergency_contact_list(Request $request)
    {

        $list = EmergencyContact::where(['user_id' => $request['delivery_man']->seller_id, 'status' => 1])->get();
        $data = array();
        $data['contact_list'] = $list;

        return response()->json($data, 200);
    }

    public function review_list(Request $request)
    {
        $dm = $request['delivery_man'];
        $delivery_man = DeliveryMan::where(['id' => $dm['id']])->with(['review'])->first();

        $reviews = Review::with('customer', 'order')
            ->when($request->is_saved, function ($query) use ($request) {
                $query->where('is_saved', 1);
            })
            ->where('delivery_man_id', $dm->id)
            ->latest('updated_at')
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $datas = $reviews->items();
        $dt = [];
        foreach ($datas as $rev_key => $rev) {
            $dt[$rev_key]['order_id'] = $rev['order_id'] ?? "";
            $dt[$rev_key]['customer_name'] = $rev->customer->f_name ?? "";
            $dt[$rev_key]['rating'] = $rev['rating'] ?? 0;
            $dt[$rev_key]['comment'] = $rev['comment'] ?? "";
            $dt[$rev_key]['created_at'] = date('d M, Y', strtotime($rev['created_at']));
        }

        $data = array();
        $data['total_size'] = $reviews->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['average_rating'] = count($delivery_man->rating) > 0 ? number_format($delivery_man->rating[0]->average, 2, '.', ' ') : "0";
        $data['review'] = $dt;

        return response()->json($data, 200);
    }

    public function is_online(Request $request)
    {
        $dm = $request['delivery_man'];
        $delivery_man = '';
        if ($request->is_online == '0') {
            $delivery_man = DeliveryMan::whereHas('orders', function ($query) {
                $query->where(['order_status' => 'out_for_delivery', 'is_pause' => 0]);
            })->find($request['delivery_man']->id);
        }

        if ($request->is_online == '0' && $delivery_man) {
            return response()->json(["message" => translate("You have ongoing order. You can't go offline now!")], 403);
        } else {
            $dm->is_online = $request->is_online;
            $dm->save();
            return response()->json(["message" => translate("update successfully!")], 200);
        }
    }

    public function get_all_notification(Request $request)
    {
        $notifications = DeliverymanNotification::where(['delivery_man_id' => $request['delivery_man']->id])
            ->latest()
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = strval($notifications->total() ?? 0);
        $data['status'] = true;
        $data['message'] = "Notification Lists";
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['notifications'] = $notifications->items();

        return response()->json($data, 200);
    }

    public function distance_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin_lat' => 'required',
            'origin_lng' => 'required',
            'destination_lat' => 'required',
            'destination_lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $api_key = Helpers::get_business_settings('map_api_key_server');

        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $request['origin_lat'] . ',' . $request['origin_lng'] . '&destinations=' . $request['destination_lat'] . ',' . $request['destination_lng'] . '&key=' . $api_key);

        return response()->json($response->json(), 200);
    }

    public function is_saved(Request $request)
    {
        $dm = $request['delivery_man'];
        $get_review = Review::where(['id' => $request->review_id, 'delivery_man_id' => $dm->id])->first();

        if (!$get_review) {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'review',
                        'message' => translate('not_found!')
                    ]
                ]
            ], 404);
        }
        $get_review->is_saved = $request->is_saved;

        if ($get_review->save()) {
            return response()->json(['message' => translate('update successfully!')], 200);
        }
        return response()->json([
            'errors' => [
                [
                    'code' => 'update',
                    'message' => translate('failed!')
                ]
            ]
        ], 403);
    }

    public function order_update_status(Request $request)
    {
        $order_id = $request->input('order_id');

        $d_man = $request['delivery_man'];

        $order = Order::find($order_id);
        if ($order) {
            $order->order_status = $request->input('status');
            $order->save();

            CommonTrait::add_order_status_history($order_id, $d_man['id'], $request['status'], 'delivery_man', $request['cause']);

            return response()->json(['status' => true, 'message' => 'Order status updated successfully!'], 200);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong!'], 403);
    }

    public function static_pages(Request $request)
    {
        $setting = [
            'about_us' => Helpers::get_business_settings('about_us'),
            'privacy_policy' => Helpers::get_business_settings('driver-privacy-policy'),
            'faq' => HelpTopic::all(),
            'terms_n_conditions' => Helpers::get_business_settings('driver-terms-policy'),
            'refund_policy' => Helpers::get_business_settings('driver-refund-policy'),
            'return_policy' => Helpers::get_business_settings('driver-return-policy'),
            'cancellation_policy' => Helpers::get_business_settings('driver-cancellation-policy'),

            'shipping_delivery_policy' => Helpers::get_business_settings('driver-shipping-policy'),
            'security_policy_policy' => Helpers::get_business_settings('driver-security-policy-policy'),
            'payment_policy' => Helpers::get_business_settings('driver-payment-policy'),
            'condition_of_use_policy' => Helpers::get_business_settings('driver-condition-of-use-policy'),
            'security_information' => Helpers::get_business_settings('driver-security-information'),
            'contact_us' => Helpers::get_business_settings('contact-us')

        ];
        $response = ['status' => true, 'message' => 'Home Page Items', 'data' => $setting];

        return response()->json($response);
    }

    public function accept_reject_order(Request $request)
    {
        $order_id = $request->input('order_id');
        $o_status = $request->input('status');
        $d_man = $request['delivery_man'];

        $message = "No status updated success";
        $status = false;
        if ($o_status == 'accept') {
            $status = true;
            $order = Order::find($order_id);
            $order->order_status = "out_for_delivery";
            $order->save();
            CommonTrait::add_order_status_history($order_id, $d_man['id'], 'out_for_delivery', 'delivery_man', '');

            $stat = OrderDeliveryManStatus::where(['delivery_man_id' => $d_man['id'], 'order_id' => $order_id])->first();
            $stat->status = 1;
            $stat->save();

            $message = "Order Accepted";
        } else if ($o_status == 'reject') {
            $status = true;
            $order = Order::find($order_id);
            $order->order_status = "processing";
            $order->delivery_man_id = "";
            $order->save();

            $stat = OrderDeliveryManStatus::where(['delivery_man_id' => $d_man['id'], 'order_id' => $order_id])->first();
            $stat->status = 2;
            $stat->save();

            $message = "Order Rejected";
        }

        $response = [
            'status' => $status,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }
}
