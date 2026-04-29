<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;
use App\Model\RefundRequest;
use App\Model\Order;
use App\Model\AdminWallet;
use App\Model\SellerWallet;
use App\Model\RefundTransaction;
use App\CPU\Helpers;
use App\Model\OrderDetail;
Use App\Model\RefundStatus;
use App\CPU\CustomerManager;
use App\User;
use App\CPU\Convert;

class RefundController extends Controller
{
    public function list(Request $request, $status)
    {
        $search = $request->search;
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            $refund_list = RefundRequest::whereHas('order', function ($query) {
                $query->where('seller_is', 'admin');
            });

        }else{
            $refund_list = new RefundRequest;
        }

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $refund_list = $refund_list->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('order_id', 'like', "%{$value}%")
                        ->orWhere('id', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }
        $refund_list = $refund_list->where('status',$status)->latest()->paginate(Helpers::pagination_limit());

        return view('admin-views.refund.list',compact('refund_list','search', 'status'));
    }

    public function details($id)
    {
        $refund = RefundRequest::find($id);

        return view('admin-views.refund.details',compact('refund'));
    }

    public function refund_status_update(Request $request)
    {
        $refund = RefundRequest::find($request->id);
        $user = User::find($refund->customer_id);

        if(!isset($user))
        {
            Toastr::warning(translate('This account has been deleted, you can not modify the status!!'));
            return back();
        }

        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        $loyalty_point = CustomerManager::count_loyalty_point_for_amount($refund->order_details_id);

        if( $loyalty_point_status == 1)
        {

            if($user->loyalty_point < $loyalty_point && ($request->refund_status == 'refunded' || $request->refund_status == 'approved'))
            {
                Toastr::warning(translate('Customer has not sufficient loyalty point to take refund for this order!!'));
                return back();
            }
        }

        if($request->refund_status == 'refunded' && $refund->status != 'refunded')
        {
            $order = Order::find($refund->order_id);
            if($order->seller_is == 'admin')
            {
                $admin_wallet = AdminWallet::where('admin_id',$order->seller_id)->first();
                $admin_wallet->inhouse_earning = $admin_wallet->inhouse_earning - $refund->amount;
                $admin_wallet->save();

                $transaction = new RefundTransaction;
                $transaction->order_id = $refund->order_id;
                $transaction->payment_for = 'Refund Request';
                $transaction->payer_id = $order->seller_id;
                $transaction->payment_receiver_id = $refund->customer_id;
                $transaction->paid_by = $order->seller_is;
                $transaction->paid_to = 'customer';
                $transaction->payment_method = $request->payment_method;
                $transaction->payment_status = $request->payment_method !=null?'paid':'unpaid';
                $transaction->amount = $refund->amount;
                $transaction->transaction_type = 'Refund';
                $transaction->order_details_id = $refund->order_details_id;
                $transaction->refund_id = $refund->id;
                $transaction->save();

            }else{
                $seller_wallet = SellerWallet::where('seller_id',$order->seller_id)->first();
                $seller_wallet->total_earning = $seller_wallet->total_earning - $refund->amount;
                $seller_wallet->save();

                $transaction = new RefundTransaction;
                $transaction->order_id = $refund->order_id;
                $transaction->payment_for = 'Refund Request';
                $transaction->payer_id = $order->seller_id;
                $transaction->payment_receiver_id = $refund->customer_id;
                $transaction->paid_by = $order->seller_is;
                $transaction->paid_to = 'customer';
                $transaction->payment_method = $request->payment_method;
                $transaction->payment_status = $request->payment_method !=null?'paid':'unpaid';
                $transaction->amount = $refund->amount;
                $transaction->transaction_type = 'Refund';
                $transaction->order_details_id = $refund->order_details_id;
                $transaction->refund_id = $refund->id;
                $transaction->save();
            }


        }
        if($refund->status != 'refunded')
        {
            $order_details = OrderDetail::find($refund->order_details_id);

            $refund_status = new RefundStatus;
            $refund_status->refund_request_id = $refund->id;
            $refund_status->change_by = 'admin';
            $refund_status->change_by_id = auth('admin')->id();
            $refund_status->status = $request->refund_status;

            if($request->refund_status == 'pending')
            {
                $order_details->refund_request = 1;
            }
            elseif($request->refund_status == 'approved')
            {
                $order_details->refund_request = 2;
                $refund->approved_note = $request->approved_note;

                $refund_status->message = $request->approved_note;

            }
            elseif($request->refund_status == 'rejected')
            {
                $order_details->refund_request = 3;
                $refund->rejected_note = $request->rejected_note;

                $refund_status->message = $request->rejected_note;
            }
            elseif($request->refund_status == 'refunded')
            {
                $order_details->refund_request = 4;
                $refund->payment_info = $request->payment_info;
                $refund_status->message = $request->payment_info;

                if($loyalty_point > 0 && $loyalty_point_status == 1)
                {
                    CustomerManager::create_loyalty_point_transaction($refund->customer_id, $refund->order_id, $loyalty_point, 'refund_order');
                }

                $wallet_add_refund = Helpers::get_business_settings('wallet_add_refund');

                if($wallet_add_refund==1 && $request->payment_method == 'customer_wallet')
                {
                    CustomerManager::create_wallet_transaction($refund->customer_id, Convert::default($refund->amount), 'order_refund','order_refund');
                }
            }
            $order_details->save();

            $refund->status = $request->refund_status;
            $refund->change_by = 'admin';
            $refund->save();
            $refund_status->save();


            Toastr::success(translate('refund_status_updated!!'));
            return back();

        }else{
            Toastr::warning(translate('refunded status can not be changed!!'));
            return back();
        }



    }

    public function index()
    {
        return view('admin-views.refund.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'refund_day_limit' => 'required',
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'refund_day_limit'], [
            'value' => $request['refund_day_limit']
        ]);
        Toastr::success(translate('refund_day_limit_updated!!'));
        return back();
    }

    public function inhouse_order_filter()
    {
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            session()->put('show_inhouse_orders', 0);
        } else {
            session()->put('show_inhouse_orders', 1);
        }
        return back();
    }

    public function paginate(Request $request, $status)
    {
        $search = $request->search;

        $orderbyName = isset($request->columns[$request->order[0]['column']]['name']) ? $request->columns[$request->order[0]['column']]['name'] : 'products.id';
        $orderBy = isset($request->order[0]['dir']) ? $request->order[0]['dir'] : 'desc';

        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            $refund_list = RefundRequest::with('product')->whereHas('order', function ($query) {
                $query->where('seller_is', 'admin');
            })->orderBy($orderbyName, $orderBy)->limit($request->length)->offset($request->start);

        }else{
            $refund_list = RefundRequest::with('product')->orderBy($orderbyName, $orderBy)->limit($request->length)->offset($request->start);
        }
        $refund_list = $refund_list->select('refund_requests.*')->join('users','users.id', '=', 'refund_requests.customer_id')->join('products','products.id', '=' ,'refund_requests.product_id');

        if ($request->has('search')) {
            $key = explode(' ', $request['search']['value']);
            $refund_list = $refund_list->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('refund_requests.order_id', 'like', "%{$value}%")
                        ->orWhere('refund_requests.id', 'like', "%{$value}%")
                        ->orWhere('users.f_name', 'like', "%{$value}%")
                        ->orWhere('users.phone', 'like', "%{$value}%")
                        ->orWhere('refund_requests.status', 'like', "%{$value}%")
                        ->orWhere('products.name', 'like', "%{$value}%")
                        ->orWhere('products.details', 'like', "%{$value}%")
                        ->orWhere('products.meta_title', 'like', "%{$value}%")
                        ->orWhere('products.meta_description', 'like', "%{$value}%")
                        ->orWhere('refund_requests.amount', 'like', "%{" . Helpers::convert_currency_to_usd($value) . "}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }
        $refund_list = $refund_list->where('refund_requests.status', $status)->get();

        $dt = [];
        foreach($refund_list as $key => $refund):

            if ($refund->product!=null):
                $product_detail = '<div class="d-flex flex-wrap gap-2">';
                $product_detail .= "<a href='" . route('admin.product.view',[$refund->product->id]) . "'>";
                $product_detail .= "<img onerror='this.src=" . asset('/public/assets/back-end/img/brand-logo.png') . "' src='" . \App\CPU\ProductManager::product_image_path('thumbnail') .'/'.  $refund->product->thumbnail . "' class='avatar border' alt=''></a><div class='d-flex flex-column gap-1'>";                            
                $product_detail .= "<a href='" . route('admin.product.view',[$refund->product->id]) . "' class='title-color font-weight-bold hover-c1'>"
                                . \Illuminate\Support\Str::limit($refund->product->name,35) . "</a>";
                $product_detail .= "<span class='fz-12'>" . \App\CPU\translate('QTY') .":" . $refund->order_details->qty . "</span></div>
                </div>";
            else:
                $product_detail = \App\CPU\translate('product_name_not_found');
            endif;

            $customer_detail = "";

            if ($refund->customer !=null):
                $customer_detail .= "<div class='d-flex flex-column gap-1'>";
                $customer_detail .= "<a href='" . route('admin.customer.view',[$refund->customer->id]) . "' class='title-color font-weight-bold hover-c1'>"
                        . $refund->customer->f_name. ' '.$refund->customer->l_name . "</a>";
                $customer_detail .= "<a href='tel:" . $refund->customer->phone . "' class='title-color hover-c1 fz-12'>" . $refund->customer->phone . "</a></div>";
            else:
                $customer_detail .= "<a href='#' class='title-color hover-c1'>"
                    . \App\CPU\translate('customer_not_found') . 
                "</a>";
            endif;

            $stat = "<div class='d-inline-flex flex-column gap-1'>";
            if($refund->status=='pending'):
                $stat .= "<span class='badge badge-soft--primary'>" . \App\CPU\translate($refund->status) . "</span>";
            elseif($refund->status=='approved'):
                $stat .= "<span class='badge badge-soft-success'>" . \App\CPU\translate($refund->status) . "</span>";
            elseif($refund->status=='rejected'):
                $stat .= "<span class='badge badge-soft-danger'>" . \App\CPU\translate($refund->status) . "</span>";
            else:
                $stat .= "<span class='badge badge-soft-warning'>" . \App\CPU\translate($refund->status) . "</span>";
            endif;
            $stat .= "</div>";

            $dt[] = [
                $key + 1,
                "<a href='" . route('admin.orders.details',['id'=>$refund->order_id]) . "' class='title-color hover-c1'>
                    " . $refund->order_id . "
                </a>",
                $product_detail,
                $customer_detail,
                "<div class='d-flex flex-column gap-1 text-end'><div>" . \App\CPU\Helpers::currency_converter($refund->amount) . "</div></div>",
                $stat,
                "<div class='d-flex justify-content-center'>
                    <a class='btn btn-outline--primary btn-sm'
                        title='" . \App\CPU\translate('view') . "'
                        href='" . route('admin.refund-section.refund.details',['id'=>$refund['id']]) . "'>
                        <i class='tio-invisible'></i>
                    </a>
                </div>"
            ];
        endforeach;

        $rows = [
            "draw" => (int)$request->input('draw'),
            "recordsTotal" => RefundRequest::where('status', $status)->count(),
            "recordsFiltered" =>  count($dt),
            "data" => $dt
        ];

        return response()->json($rows);

        return view('admin-views.refund.list',compact('refund_list','search'));
    }

}
