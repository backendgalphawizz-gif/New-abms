<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\DeliveryMan;
use App\Model\DeliveryManTransaction;
use App\Model\DeliverymanWallet;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\OrderTransaction;
use App\Model\Seller;
use App\Model\Product;
use App\Traits\CommonTrait;
use App\Model\ShippingAddress;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Ramsey\Uuid\Uuid;
use function App\CPU\translate;
use App\CPU\CustomerManager;
use App\CPU\Convert;
use App\Model\OrderDeliveryManStatus;
use Rap2hpoutre\FastExcel\FastExcel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    use CommonTrait;
    public function list(Request $request, $status)
    {
        $search = $request['search'];
        $filter = $request['filter'];
        $from = $request['from'];
        $to = $request['to'];
        $key = $request['search'] ? explode(' ', $request['search']) : '';
        $delivery_man_id = $request['delivery_man_id'];

        Order::where(['checked' => 0])->update(['checked' => 1]);

        $sellers = Seller::approved()->get();

        $orders = Order::with(['customer', 'seller.shop'])
            ->when($status != 'all', function ($q) use ($status) {
                $q->where(function ($query) use ($status) {
                    $query->orWhere('order_status', $status);
                });
            })
            ->when($filter, function ($q) use ($filter) {
                $q->when($filter == 'all', function ($q) {
                    return $q;
                })
                    ->when($filter == 'POS', function ($q) {
                        $q->whereHas('details', function ($q) {
                            $q->where('order_type', 'POS');
                        });
                    })
                    ->when($filter == 'admin' || $filter == 'seller', function ($q) use ($filter) {
                        $q->whereHas('details', function ($query) use ($filter) {
                            $query->whereHas('product', function ($query) use ($filter) {
                                $query->where('added_by', $filter);
                            });
                        });
                    });
            })
            ->when($request->has('search') && $search != null, function ($q) use ($key) {
                $q->where(function ($qq) use ($key) {
                    foreach ($key as $value) {
                        $qq->where('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }
                });
            })->when(!empty($from) && !empty($to), function ($dateQuery) use ($from, $to) {
                $dateQuery->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            })->when($delivery_man_id, function ($q) use ($delivery_man_id) {
                $q->where(['delivery_man_id' => $delivery_man_id]);
            })
            ->latest('id')
            ->paginate(Helpers::pagination_limit())
            ->appends(['search' => $request['search'], 'filter' => $request['filter'], 'from' => $request['from'], 'to' => $request['to'], 'delivery_man_id' => $request['delivery_man_id']]);

        $pending_query = Order::where(['order_status' => 'pending']);
        $pending_count = $this->common_query_status_count($pending_query, 'pending', $request);

        $confirmed_query = Order::where(['order_status' => 'confirmed']);
        $confirmed_count = $this->common_query_status_count($confirmed_query, 'confirmed', $request);

        $processing_query = Order::where(['order_status' => 'processing']);
        $processing_count = $this->common_query_status_count($processing_query, 'processing', $request);

        $shipped_query = Order::where(['order_status' => 'shipped']);
        $shipped_count = $this->common_query_status_count($shipped_query, 'shipped', $request);

        $out_for_delivery_query = Order::where(['order_status' => 'out_for_delivery']);
        $out_for_delivery_count = $this->common_query_status_count($out_for_delivery_query, 'out_for_delivery', $request);

        $delivered_query = Order::where(['order_status' => 'delivered']);
        $delivered_count = $this->common_query_status_count($delivered_query, 'delivered', $request);

        $canceled_query = Order::where(['order_status' => 'canceled']);
        $canceled_count = $this->common_query_status_count($canceled_query, 'canceled', $request);

        $returned_query = Order::where(['order_status' => 'returned']);
        $returned_count = $this->common_query_status_count($returned_query, 'returned', $request);

        $failed_query = Order::where(['order_status' => 'failed']);
        $failed_count = $this->common_query_status_count($failed_query, 'failed', $request);

        return view(
            'admin-views.order.list',
            compact(
                'orders',
                'search',
                'from',
                'to',
                'status',
                'filter',
                'pending_count',
                'confirmed_count',
                'processing_count',
                'shipped_count',
                'out_for_delivery_count',
                'delivered_count',
                'returned_count',
                'failed_count',
                'canceled_count',
                'sellers'
            )
        );
    }

    public function paginate(Request $request, $status)
    {
        $orderbyName = $request->columns[$request->order[0]['column']]['name'] ?? 'orders.id';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        $search = $request->input('search');
        $filter = $request->input('filter');
        $from = $request->input('from');
        $to = $request->input('to');
        $key = $search['value'] ?? '';
        $search_by_id = $request->input('search_by_id');
        $delivery_man_id = $request->input('delivery_man_id');

        Order::where(['checked' => 0])->update(['checked' => 1]);

        // STEP 1: Build base query
        $query = Order::select('orders.*')
            ->with(['customer', 'seller.shop'])
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->join('sellers', 'orders.seller_id', '=', 'sellers.id')
            ->orderBy($orderbyName, $orderBy)
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('order_status', $status);
            })
            ->when($filter && $filter !== 'all', function ($q) use ($filter) {
                $q->whereHas('details', function ($query) use ($filter) {
                    $query->whereHas('product', function ($query) use ($filter) {
                        $query->where('added_by', 'seller')->where('seller_id', $filter);
                    });
                });
            })
            ->when($filter === 'POS', function ($q) {
                $q->whereHas('details', function ($q) {
                    $q->where('order_type', 'POS');
                });
            })
            ->when($key !== '', function ($q) use ($key) {
                $q->where(function ($subQuery) use ($key) {
                    $subQuery->where('orders.id', 'like', "%{$key}%")
                        ->orWhereHas('seller.shop', function ($q) use ($key) {
                            $q->where('name', 'like', "%{$key}%");
                        })
                        ->orWhereHas('customer', function ($q) use ($key) {
                            $q->where('f_name', 'like', "%{$key}%");
                        })
                        // ->orWhere('shipping_address_data->address', 'like', "%{$key}%")
                        ->orWhere('shipping_address_data->address1', 'like', "%{$key}%")
                        ->orWhere('shipping_address_data->area', 'like', "%{$key}%");
                });
            })
            ->when($search_by_id !== '', function ($q) use ($search_by_id) {
                $q->where(function ($subQuery) use ($search_by_id) {
                    $subQuery->where('orders.id', 'like', "%{$search_by_id}%")
                        ->orWhereHas('seller.shop', function ($q) use ($search_by_id) {
                            $q->where('name', 'like', "%{$search_by_id}%");
                        })
                        ->orWhereHas('customer', function ($q) use ($search_by_id) {
                            $q->where('f_name', 'like', "%{$search_by_id}%");
                        });
                });
            })
            ->when(!empty($from) && !empty($to), function ($q) use ($from, $to) {
                $q->whereDate('orders.created_at', '>=', $from)
                    ->whereDate('orders.created_at', '<=', $to);
            })
            ->when($delivery_man_id, function ($q) use ($delivery_man_id) {
                $q->where('delivery_man_id', $delivery_man_id);
            });

        // STEP 2: Clone query to count filtered records
        $filteredCount = (clone $query)->count();

        // STEP 3: Apply pagination
        $orders = $query
            ->offset($request->start)
            ->limit($request->length)
            ->get();

        // STEP 4: Build data array for DataTables
        $dt = [];
        foreach ($orders as $key => $order) {
            $discount = ($order->coupon_discount_bearer === 'inhouse' && !in_array($order->coupon_code, [0, null])) ? $order->discount_amount : 0;

            $customer = $order->customer
                ? "<a class='text-body text-capitalize' href='" . route('admin.orders.details', ['id' => $order->id]) . "'>
                    <strong class='title-name'>{$order->customer->f_name} {$order->customer->l_name}</strong>
               </a>
               <a class='d-block title-color' href='tel: {$order->customer->phone}'>{$order->customer->phone}</a>"
                : "<label class='badge badge-danger fz-12'>" . \App\CPU\translate('invalid_customer_data') . "</label>";

            $statusLabel = match ($order->order_status) {
                'pending' => "<span class='badge badge-soft-info fz-12'>" . \App\CPU\translate('pending') . "</span>",
                'processing' => "<span class='badge badge-soft-warning fz-12'>" . \App\CPU\translate('packaging') . "</span>",
                'out_for_delivery' => "<span class='badge badge-soft-warning fz-12'>" . \App\CPU\translate('out_for_delivery') . "</span>",
                'confirmed' => "<span class='badge badge-soft-success fz-12'>" . \App\CPU\translate('confirmed') . "</span>",
                'delivered' => "<span class='badge badge-soft-success fz-12'>" . \App\CPU\translate('delivered') . "</span>",
                'failed' => "<span class='badge badge-danger fz-12'>" . \App\CPU\translate('failed_to_deliver') . "</span>",
                default => "<span class='badge badge-soft-danger fz-12'>" . \App\CPU\translate($order->order_status) . "</span>",
            };

            $isPaid = ($order->payment_status == 'paid')
                ? "<span class='badge badge-soft-success fz-12'>" . \App\CPU\translate('paid') . "</span>"
                : "<span class='badge badge-soft-danger fz-12'>" . \App\CPU\translate('unpaid') . "</span>";

            $dt[] = [
                strval($key + 1),
                "<a class='title-color' href='" . route('admin.orders.details', ['id' => $order->id]) . "'>{$order->id}</a>",
                "<div>" . date('d M Y', strtotime($order->created_at)) . "</div><div>" . date("h:i A", strtotime($order->created_at)) . "</div>",
                $customer,
                ($order->seller_is == 'seller' && isset($order->seller->shop)) ? "<span class='store-name font-weight-medium'>{$order->seller->shop->name}</span>" : 'Admin Product',
                "<div>" . \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount + $discount)) . "</div>",
                $statusLabel,
                $isPaid,
                ucwords(str_replace('_', ' ', $order->payment_method)),
                "<div class='d-flex justify-content-center gap-2'>
                <a class='btn btn-outline--primary square-btn btn-sm mr-1' title='" . \App\CPU\translate('view') . "' href='" . route('admin.orders.details', ['id' => $order->id]) . "'>
                    <img src='" . asset('/public/assets/back-end/img/eye.svg') . "' class='svg' alt=''>
                </a>
                <a class='btn btn-outline-success square-btn btn-sm mr-1' target='_blank' title='" . \App\CPU\translate('invoice') . "' href='" . route('admin.orders.generate-invoice', [$order->id]) . "'>
                    <i class='tio-download-to'></i>
                </a>
            </div>",
            ];
        }

        // STEP 5: Return final result
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => Order::count(), // total without filters
            "recordsFiltered" => $filteredCount, // total after filters
            "data" => $dt,
        ]);
    }


    // public function paginate(Request $request, $status)
    // {

    //     $orderbyName = isset($request->columns[$request->order[0]['column']]['name']) ? $request->columns[$request->order[0]['column']]['name'] : 'orders.id';
    //     $orderBy = isset($request->order[0]['dir']) ? $request->order[0]['dir'] : 'desc';

    //     $status = $request->input('status');

    //     // $patients = Patient::orderBy($orderbyName, $orderBy)->limit($request->length)->offset($request->start);

    //     $search = $request['search'];
    //     $filter = $request['filter'];
    //     $from = $request['from'];
    //     $to = $request['to'];
    //     $key = $request['search']['value'] ? $request['search']['value'] : '';
    //     $delivery_man_id = $request['delivery_man_id'];

    //     $filterCount = false;
    //     if ($status != 'all') {
    //         $filterCount = true;
    //     }
    //     if (isset($request->search['value']) && $key != "") {
    //         $filterCount = true;
    //     }

    //     $search_by_id = $request->input('search_by_id');
    //     if ($request->has('search_by_id') && $request->input('search_by_id') != "") {
    //         $filterCount = true;
    //     }

    //     if ($filter != 'all') {
    //         $filterCount = true;
    //     }

    //     Order::where(['checked' => 0])->update(['checked' => 1]);
    //     DB::enableQueryLog();
    //     $orders = Order::select('orders.*')->with(['customer', 'seller.shop'])->orderBy($orderbyName, $orderBy)->limit($request->length)->offset($request->start)
    //         ->join('users', 'orders.customer_id', '=', 'users.id') // Join with the users table
    //         ->join('sellers', 'orders.seller_id', '=', 'sellers.id') // Join with the users table
    //         ->when($status != 'all', function ($q) use ($status) {
    //             $q->where(function ($query) use ($status) {
    //                 $query->orWhere('order_status', $status);
    //             });
    //         })
    //         ->when($filter, function ($q) use ($filter) {
    //             $q->when($filter == 'all', function ($q) {
    //                 return $q;
    //             })
    //                 ->when($filter == 'POS', function ($q) {
    //                     $q->whereHas('details', function ($q) {
    //                         $q->where('order_type', 'POS');
    //                     });
    //                 })
    //                 ->when($filter != 'all', function ($q) use ($filter) {
    //                     $q->whereHas('details', function ($query) use ($filter) {
    //                         $query->whereHas('product', function ($query) use ($filter) {
    //                             $query->where('added_by', 'seller')->where('seller_id', $filter);
    //                         });
    //                     });
    //                 });
    //         })
    //         ->when($request->has('search') && $search != null, function ($q) use ($key) {


    //             $q->where('orders.id', 'like', "%{$key}%")
    //                 ->whereHas('seller', function ($q) use ($key) {
    //                     $q->whereHas('shop', function ($q) use ($key) {
    //                         $q->orWhere('name', "%{$key}%");
    //                     });
    //                 })->whereHas('customer', function ($q) use ($key) {
    //                     $q->orWhere('f_name', 'like', "%{$key}%");
    //                 });

    //             // $q->where(function($qq) use ($key){
    //             //     $qq->where('orders.id', 'like', "%{$key}%")
    //             //         ->orWhere('order_status', 'like', "%{$key}%")
    //             //         ->orWhere('transaction_ref', 'like', "%{$key}%")->where('orders.id', 'like', "%{$key}%")->orWhere('users.f_name', 'like', "%{$key}%")->orWhere('sellers.f_name', 'like', "%{$key}%");
    //             // });
    //         })->when(!empty($from) && !empty($to), function ($dateQuery) use ($from, $to) {
    //             $dateQuery->whereDate('orders.created_at', '>=', $from)->whereDate('orders.created_at', '<=', $to);
    //         })->when($delivery_man_id, function ($q) use ($delivery_man_id) {
    //             $q->where(['delivery_man_id' => $delivery_man_id]);
    //         })
    //         ->when($request->has('search_by_id') && $request->input('search_by_id') != "",  function ($qqq) use ($search_by_id) {
    //             $qqq->where('orders.id', 'like', "%{$search_by_id}%")
    //                 ->whereHas('seller', function ($q) use ($search_by_id) {
    //                     $q->whereHas('shop', function ($q) use ($search_by_id) {
    //                         $q->orWhere('name', "%{$search_by_id}%");
    //                     });
    //                 })->whereHas('customer', function ($q) use ($search_by_id) {
    //                     $q->orWhere('f_name', 'like', "%{$search_by_id}%");
    //                 });
    //         })
    //         ->get();

    //     // dd(DB::getQueryLog());

    //     $dt = [];
    //     foreach ($orders as $key => $order):
    //         $discount = 0;
    //         if ($order->coupon_discount_bearer == 'inhouse' && !in_array($order['coupon_code'], [0, NULL])):
    //             $discount = $order->discount_amount;
    //         endif;
    //         $customer = "";
    //         if ($order->customer):
    //             $customer .= "<a class='text-body text-capitalize' href=" . route('admin.orders.details', ['id' => $order['id']]) . ">";
    //             $customer .= "<strong class='title-name'> " . $order->customer['f_name'] . " " . $order->customer['l_name'] . "</strong>";
    //             $customer .= "</a>";
    //             $customer .= "<a class='d-block title-color' href='tel: " . $order->customer['phone'] . "'>" . $order->customer['phone'] . "</a>";
    //         else:
    //             $customer .= "<label class='badge badge-danger fz-12'>" . \App\CPU\translate('invalid_customer_data') . "</label>";
    //         endif;

    //         if ($order['order_status'] == 'pending'):
    //             $status = "<span class='badge badge-soft-info fz-12'>" . \App\CPU\translate($order['order_status']) . " </span>";

    //         elseif ($order['order_status'] == 'processing' || $order['order_status'] == 'out_for_delivery'):
    //             $status = "<span class='badge badge-soft-warning fz-12'>" . str_replace('_', ' ', $order['order_status'] == 'processing' ? \App\CPU\translate('packaging') : \App\CPU\translate($order['order_status'])) . "</span>";
    //         elseif ($order['order_status'] == 'confirmed'):
    //             $status = "<span class='badge badge-soft-success fz-12'>" . \App\CPU\translate($order['order_status']) . " </span>";
    //         elseif ($order['order_status'] == 'failed'):
    //             $status = "<span class='badge badge-danger fz-12'>" . \App\CPU\translate('failed_to_deliver') . " </span>";
    //         elseif ($order['order_status'] == 'delivered'):
    //             $status = "<span class='badge badge-soft-success fz-12'>" . \App\CPU\translate($order['order_status']) . " </span>";
    //         else:
    //             $status = "<span class='badge badge-soft-danger fz-12'>" . \App\CPU\translate($order['order_status']) . " </span>";
    //         endif;

    //         $isPaid = ''; // ($order->payment_status=='paid') ? "<span class='badge text-success fz-12 px-0'>" . \App\CPU\translate('paid') . "</span>" : "<span class='badge text-danger fz-12 px-0'>" . \App\CPU\translate('unpaid') . "</span>";

    //         $dt[] = [
    //             strval($key + 1),
    //             "<a class='title-color' href='" . route('admin.orders.details', ['id' => $order['id']]) . "'>" . $order['id'] . "</a>",
    //             "<div>" . date('d M Y', strtotime($order['created_at'])) . "</div><div>" . date("h:i A", strtotime($order['created_at'])) . "</div>",
    //             $customer,
    //             $order->seller_is == 'seller' && isset($order->seller->shop) ? "<span class='store-name font-weight-medium'>" . $order->seller->shop->name . "</span>" : 'Amin Product',
    //             "<div>" . \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount + $discount)) . "</div>" . $isPaid,
    //             $status,

    //             ($order->payment_status == 'paid') ? "<span class='badge badge-soft-success fz-12'>" . \App\CPU\translate($order->payment_status) . " </span>" : "<span class='badge badge-soft-danger fz-12'>" . \App\CPU\translate($order->payment_status) . " </span>",

    //             ucwords(str_replace('_', ' ', $order->payment_method)),
    //             // $order->expected_delivery_date ?? '-',

    //             "<div class='d-flex justify-content-center gap-2'>
    //                         <a class='btn btn-outline--primary square-btn btn-sm mr-1' title='" . \App\CPU\translate('view') . "' href='" . route('admin.orders.details', ['id' => $order['id']]) . "'>
    //                         <img src='" . asset('/public/assets/back-end/img/eye.svg') . "' class='svg' alt=''>
    //                     </a>
    //                     <a class='btn btn-outline-success square-btn btn-sm mr-1' target='_blank' title='" . \App\CPU\translate('invoice') . "' href='" . route('admin.orders.generate-invoice', [$order['id']]) . "'>
    //                         <i class='tio-download-to'></i>
    //                     </a>
    //                 </div>",
    //         ];
    //     endforeach;


    //     $rows = [
    //         "draw" => (int)$request->input('draw'),
    //         "recordsTotal" => Order::count(),
    //         "recordsFiltered" =>  $filterCount ? count($orders) : Order::count(),
    //         "data" => $dt
    //     ];

    //     return response()->json($rows);
    // }

    public function common_query_status_count($query, $status, $request)
    {
        $search = $request['search'];
        $filter = $request['filter'];
        $from = $request['from'];
        $to = $request['to'];
        $key = $request['search'] ? explode(' ', $request['search']) : '';

        return $query->when($status != 'all', function ($q) use ($status) {
            $q->where(function ($query) use ($status) {
                $query->orWhere('order_status', $status);
            });
        })
            ->when($filter, function ($q) use ($filter) {
                $q->when($filter == 'all', function ($q) {
                    return $q;
                })
                    ->when($filter == 'POS', function ($q) {
                        $q->whereHas('details', function ($q) {
                            $q->where('order_type', 'POS');
                        });
                    })
                    ->when($filter == 'admin' || $filter == 'seller', function ($q) use ($filter) {
                        $q->whereHas('details', function ($query) use ($filter) {
                            $query->whereHas('product', function ($query) use ($filter) {
                                $query->where('added_by', $filter);
                            });
                        });
                    });
            })
            ->when($request->has('search') && $search != null, function ($q) use ($key) {
                $q->where(function ($qq) use ($key) {
                    foreach ($key as $value) {
                        $qq->where('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }
                });
            })->when(!empty($from) && !empty($to), function ($dateQuery) use ($from, $to) {
                $dateQuery->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            })->count();
    }

    public function details($id)
    {
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $order = Order::with('details.product_all_status', 'shipping', 'seller.shop')->where(['id' => $id])->first();

        $physical_product = false;
        foreach ($order->details as $product) {
            if (isset($product->product) && $product->product->product_type == 'physical') {
                $physical_product = true;
            }
        }

        $linked_orders = Order::where(['order_group_id' => $order['order_group_id']])
            ->whereNotIn('order_group_id', ['def-order-group'])
            ->whereNotIn('id', [$order['id']])
            ->get();

        $total_delivered = Order::where(['seller_id' => $order->seller_id, 'order_status' => 'delivered', 'order_type' => 'default_type'])->count();

        $shipping_method = Helpers::get_business_settings('shipping_method');
        // $delivery_men = DeliveryMan::where('is_active', 1)->when($order->seller_is == 'admin', function ($query) {
        //     $query->where(['seller_id' => 0]);
        // })->when($order->seller_is == 'seller' && $shipping_method == 'sellerwise_shipping', function ($query) use ($order) {
        //     $query->where(['seller_id' => $order['seller_id']]);
        // })->when($order->seller_is == 'seller' && $shipping_method == 'inhouse_shipping', function ($query) use ($order) {
        //     $query->where(['seller_id' => 0]);
        // })->get();

        $delivery_men = DeliveryMan::where('is_active', 1)->get();

        $shipping_address = ShippingAddress::find($order->shipping_address);
        // dd($order->order_type);
        if ($order->order_type == 'default_type') {
            return view('admin-views.order.order-details', compact('shipping_address', 'order', 'linked_orders', 'delivery_men', 'total_delivered', 'company_name', 'company_web_logo', 'physical_product'));
        } else {
            return view('admin-views.pos.order.order-details', compact('order', 'company_name', 'company_web_logo'));
        }
    }

    public function add_delivery_man($order_id, $delivery_man_id)
    {
        if ($delivery_man_id == 0) {
            return response()->json([], 401);
        }
        $order = Order::find($order_id);
        $order->delivery_man_id = $delivery_man_id;
        $order->delivery_type = 'self_delivery';
        $order->delivery_service_name = "";
        $order->third_party_delivery_tracking_id = "";
        $order->save();

        $fcm_token = isset($order->delivery_man) ? $order->delivery_man->fcm_token : "";
        $value = Helpers::order_status_update_message('del_assign') . " ID: " . $order['id'];

        $deliveryManStatus = OrderDeliveryManStatus::where('order_id', $order_id)->first();
        if (empty($st)) {
            $deliveryManStatus = new OrderDeliveryManStatus;
        }

        // $deliveryManStatus = new OrderDeliveryManStatus;
        $deliveryManStatus->order_id = $order_id;
        $deliveryManStatus->delivery_man_id = $delivery_man_id;
        $deliveryManStatus->status = 0;
        $deliveryManStatus->save();

        if (!empty($fcm_token)) {
            try {
                if ($value != null) {
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
                }
            } catch (\Exception $e) {
                Toastr::warning(\App\CPU\translate('Push notification failed for DeliveryMan!'));
            }
        }

        return response()->json(['status' => true], 200);
    }

    public function status(Request $request)
    {
        $user_id = auth('admin')->id();

        $order = Order::find($request->id);

        if (!isset($order->customer)) {
            return response()->json(['customer_status' => 0], 200);
        }

        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');

        if ($request->order_status == 'delivered' && $order->payment_status != 'paid') {

            return response()->json(['payment_status' => 0], 200);
        }
        $fcm_token = isset($order->customer) ? $order->customer->cm_firebase_token : null;
        $value = Helpers::order_status_update_message($request->order_status);
        if (!empty($fcm_token)) {
            try {
                if ($value) {
                    $data = [
                        'title' => translate('Order'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'image' => '',
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                    // exit;
                }
            } catch (\Exception $e) {
            }
        }

        try {
            $fcm_token_delivery_man = $order->delivery_man->fcm_token;
            if ($request->order_status == 'canceled' && $value != null) {
                $data = [
                    'title' => translate('order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];
                if ($order->delivery_man_id) {
                    self::add_deliveryman_push_notification($data, $order->delivery_man_id);
                }
                Helpers::send_push_notif_to_device($fcm_token_delivery_man, $data);
            }
        } catch (\Exception $e) {
        }

        $order->order_status = $request->order_status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);
        $order->save();

        if ($loyalty_point_status == 1) {
            if ($request->order_status == 'delivered' && $order->payment_status == 'paid') {
                CustomerManager::create_loyalty_point_transaction($order->customer_id, $order->id, Convert::default($order->order_amount - $order->shipping_cost), 'order_place');
            }
        }

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

            if ($order->deliveryman_charge && $request->order_status == 'delivered') {
                DeliveryManTransaction::create([
                    'delivery_man_id' => $order->delivery_man_id,
                    'user_id' => 0,
                    'user_type' => 'admin',
                    'credit' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'transaction_id' => Uuid::uuid4(),
                    'transaction_type' => 'deliveryman_charge'
                ]);
            }
        }

        self::add_order_status_history($request->id, 0, $request->order_status, 'admin');

        $transaction = OrderTransaction::where(['order_id' => $order['id']])->first();
        if (isset($transaction) && $transaction['status'] == 'disburse') {
            return response()->json($request->order_status);
        }

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'admin');
            OrderDetail::where('order_id', $order->id)->update(
                ['delivery_status' => 'delivered']
            );
        }

        return response()->json($request->order_status);
    }

    public function amount_date_update(Request $request)
    {
        $field_name = $request->field_name;
        $field_val = $request->field_val;
        $user_id = 0;

        $order = Order::find($request->order_id);
        $order->$field_name = $field_val;

        try {
            DB::beginTransaction();

            if ($field_name == 'expected_delivery_date') {
                self::add_expected_delivery_date_history($request->order_id, $user_id, $field_val, 'admin');
            }
            $order->save();

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['status' => false], 403);
        }

        if ($field_name == 'expected_delivery_date') {
            $fcm_token = isset($order->delivery_man) ? $order->delivery_man->fcm_token : null;
            $value = Helpers::order_status_update_message($field_name) . " ID: " . $order['id'];
            if (!empty($fcm_token)) {
                try {
                    if ($value != null) {
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
                    }
                } catch (\Exception $e) {
                    Toastr::warning(\App\CPU\translate('Push notification failed for DeliveryMan!'));
                }
            }
        }

        return response()->json(['status' => true], 200);
    }

    public function payment_status(Request $request)
    {
        if ($request->ajax()) {
            $order = Order::find($request->id);

            if (!isset($order->customer)) {
                return response()->json(['customer_status' => 0], 200);
            }

            $order = Order::find($request->id);
            $order->payment_status = $request->payment_status;
            $order->save();
            $data = $request->payment_status;
            return response()->json($data);
        }
    }

    public function generate_invoice($id)
    {
        $company_phone = BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $order = Order::with('seller')->with('shipping')->with('details')->where('id', $id)->first();
        $seller = Seller::find($order->details->first()->seller_id);
        $data["email"] = $order->customer != null ? $order->customer["email"] : \App\CPU\translate('email_not_found');
        $data["client_name"] = $order->customer != null ? $order->customer["f_name"] . ' ' . $order->customer["l_name"] : \App\CPU\translate('customer_not_found');
        $data["order"] = $order;

        $taxDetails = [];

        foreach ($order->details as $detail) {
            $product = Product::find($detail->product_id);
            if (!$product) continue;

            $variantName = $detail->variant;
            $matchedSku = '';
            $variation = json_decode($product->variation, true);
            if (is_array($variation)) {
                foreach ($variation as $var) {
                    if (isset($var['type']) && $var['type'] === $variantName) {
                        $matchedSku = $var['sku'] ?? '';
                        break;
                    }
                }
            }


            $tax = $product->tax ?? 0;


            $total_tax_amount = ($detail->price * $detail->qty) * ($tax / 100);

            $taxDetails[] = [
                'sku' => $matchedSku,
                'product_name' => $product->name,
                'tax' => $tax,
                'quantity' => $detail->qty,
                'price' => $detail->price,
                'total_tax_amount' => $total_tax_amount,
            ];
        }

        if (!empty($order->shippingAddress)) {
            $address = implode(', ', array_filter([
                $order->shippingAddress['address'] ?? '',
                $order->shippingAddress['city'] ?? '',
                $order->shippingAddress['state'] ?? '',
                $order->shippingAddress['country'] ?? '',
                $order->shippingAddress['zip'] ?? '',
            ]));

            $googleMapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);

            // Generate QR code as base64
            $qrCode = base64_encode(QrCode::format('png')->size(150)->generate($googleMapsUrl));
            //  $qrCode = null;
        } else {
            $qrCode = null;
        }

        // dd($taxDetails);

        $totalTaxAmount = array_sum(array_column($taxDetails, 'total_tax_amount'));
        $taxAmountInWords = $this->convertNumberToWords($totalTaxAmount);
        $orderAmountInWords = $this->convertNumberToWords($order->order_amount);

        $mpdf_view = View::make(
            'admin-views.order.invoice',
            compact('order', 'seller', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'taxDetails', 'taxAmountInWords', 'orderAmountInWords', 'qrCode')
        );
        Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $order->id);

        // return view('admin-views.order.invoice', compact(
        //     'order',
        //     'seller',
        //     'company_phone',
        //     'company_name',
        //     'company_email',
        //     'company_web_logo',
        //     'taxDetails',
        //     'taxAmountInWords','orderAmountInWords'
        // ));
    }



    //to display amount in words
    public function convertNumberToWords($number)
    {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;

        $units = [
            '',
            'One',
            'Two',
            'Three',
            'Four',
            'Five',
            'Six',
            'Seven',
            'Eight',
            'Nine',
            'Ten',
            'Eleven',
            'Twelve',
            'Thirteen',
            'Fourteen',
            'Fifteen',
            'Sixteen',
            'Seventeen',
            'Eighteen',
            'Nineteen'
        ];

        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $thousands = ['', 'Thousand', 'Million', 'Billion'];

        $words = '';

        if ($no == 0) {
            $words = 'Zero';
        } else {
            $place = 0;
            while ($no > 0) {
                if ($no % 1000 != 0) {
                    $words = $this->convertHundreds($no % 1000) . ' ' . $thousands[$place] . ' ' . $words;
                }
                $no = floor($no / 1000);
                $place++;
            }
        }


        if ($point > 0) {
            $words .= ' and ' . $this->convertHundreds($point) . ' Paise';
        }

        return $words . ' Rupees Only';
    }

    public function convertHundreds($number)
    {
        $units = [
            '',
            'One',
            'Two',
            'Three',
            'Four',
            'Five',
            'Six',
            'Seven',
            'Eight',
            'Nine',
            'Ten',
            'Eleven',
            'Twelve',
            'Thirteen',
            'Fourteen',
            'Fifteen',
            'Sixteen',
            'Seventeen',
            'Eighteen',
            'Nineteen'
        ];

        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        if ($number < 20) {
            return $units[$number];
        } elseif ($number < 100) {
            return $tens[floor($number / 10)] . ' ' . $units[$number % 10];
        } else {
            return $units[floor($number / 100)] . ' Hundred ' . $this->convertHundreds($number % 100);
        }
    }



    /*
     *  Digital file upload after sell
     */
    public function digital_file_upload_after_sell(Request $request)
    {
        $request->validate([
            'digital_file_after_sell'    => 'required|mimes:jpg,jpeg,png,gif,zip,pdf'
        ], [
            'digital_file_after_sell.required' => 'Digital file upload after sell is required',
            'digital_file_after_sell.mimes' => 'Digital file upload after sell upload must be a file of type: pdf, zip, jpg, jpeg, png, gif.',
        ]);

        $order_details = OrderDetail::find($request->order_id);
        $order_details->digital_file_after_sell = ImageManager::update('product/digital-product/', $order_details->digital_file_after_sell, $request->digital_file_after_sell->getClientOriginalExtension(), $request->file('digital_file_after_sell'));

        if ($order_details->save()) {
            Toastr::success('Digital file upload successfully!');
        } else {
            Toastr::error('Digital file upload failed!');
        }
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

    public function update_deliver_info(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->delivery_type = 'third_party_delivery';
        $order->delivery_service_name = $request->delivery_service_name;
        $order->third_party_delivery_tracking_id = $request->third_party_delivery_tracking_id;
        $order->delivery_man_id = null;
        $order->deliveryman_charge = 0;
        $order->expected_delivery_date = null;
        $order->save();

        Toastr::success(\App\CPU\translate('updated_successfully!'));
        return back();
    }

    public function bulk_export_data(Request $request, $status)
    {
        $search = $request['search'];
        $filter = $request['filter'];
        $from = $request['from'];
        $to = $request['to'];
        $type = $request['type'];
        $delivery_man_id = $request['delivery_man_id'];

        if ($status != 'all') {
            $orders = Order::when($filter, function ($q) use ($filter) {
                $q->when($filter == 'all', function ($q) {
                    return $q;
                })
                    ->when($filter == 'POS', function ($q) {
                        $q->whereHas('details', function ($q) {
                            $q->where('order_type', 'POS');
                        });
                    })
                    ->when($filter == 'admin' || $filter == 'seller', function ($q) use ($filter) {
                        $q->whereHas('details', function ($query) use ($filter) {
                            $query->whereHas('product', function ($query) use ($filter) {
                                $query->where('added_by', $filter);
                            });
                        });
                    });
            })
                ->with(['customer'])->where(function ($query) use ($status) {
                    $query->orWhere('order_status', $status)
                        ->orWhere('payment_status', $status);
                });
        } else {
            $orders = Order::with(['customer'])
                ->when($filter, function ($q) use ($filter) {
                    $q->when($filter == 'all', function ($q) {
                        return $q;
                    })
                        ->when($filter == 'POS', function ($q) {
                            $q->whereHas('details', function ($q) {
                                $q->where('order_type', 'POS');
                            });
                        })
                        ->when(($filter == 'admin' || $filter == 'seller'), function ($q) use ($filter) {
                            $q->whereHas('details', function ($query) use ($filter) {
                                $query->whereHas('product', function ($query) use ($filter) {
                                    $query->where('added_by', $filter);
                                });
                            });
                        });
                });
        }

        $key = $request['search'] ? explode(' ', $request['search']) : '';
        $orders = $orders->when($request->has('search') && $search != null, function ($q) use ($key) {
            $q->where(function ($qq) use ($key) {
                foreach ($key as $value) {
                    $qq->where('id', 'like', "%{$value}%")
                        ->orWhere('order_status', 'like', "%{$value}%")
                        ->orWhere('transaction_ref', 'like', "%{$value}%");
                }
            });
        })
            ->when($request->has('delivery_man_id') && $delivery_man_id, function ($query) use ($delivery_man_id) {
                $query->where('delivery_man_id', $delivery_man_id);
            })
            ->when(!empty($from) && !empty($to), function ($dateQuery) use ($from, $to) {
                $dateQuery->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            })
            ->orderBy('id', 'DESC')->get();

        if ($orders->count() == 0) {
            Toastr::warning(\App\CPU\translate('Data is Not available!!!'));
            return back();
        }

        $storage = array();

        foreach ($orders as $item) {

            $order_amount = $item->order_amount;
            $discount_amount = $item->discount_amount;
            $shipping_cost = $item->shipping_cost;
            $extra_discount = $item->extra_discount;

            $storage[] = [
                'order_id' => $item->id,
                'Customer Id' => $item->customer_id,
                'Customer Name' => isset($item->customer) ? $item->customer->f_name . ' ' . $item->customer->l_name : 'not found',
                'Order Group Id' => $item->order_group_id,
                'Order Status' => $item->order_status,
                'Order Amount' => Helpers::currency_converter($order_amount),
                'Order Type' => $item->order_type,
                'Coupon Code' => $item->coupon_code,
                'Discount Amount' => Helpers::currency_converter($discount_amount),
                'Discount Type' => $item->discount_type,
                'Extra Discount' => Helpers::currency_converter($extra_discount),
                'Extra Discount Type' => $item->extra_discount_type,
                'Payment Status' => $item->payment_status,
                'Payment Method' => $item->payment_method,
                'Transaction_ref' => $item->transaction_ref,
                'Verification Code' => $item->verification_code,
                'Billing Address' => isset($item->billingAddress) ? $item->billingAddress->address : 'not found',
                'Billing Address Data' => $item->billing_address_data,
                'Shipping Type' => $item->shipping_type,
                'Shipping Address' => isset($item->shippingAddress) ? $item->shippingAddress->address : 'not found',
                'Shipping Method Id' => $item->shipping_method_id,
                'Shipping Method Name' => isset($item->shipping) ? $item->shipping->title : 'not found',
                'Shipping Cost' => Helpers::currency_converter($shipping_cost),
                'Seller Id' => $item->seller_id,
                'Seller Name' => isset($item->seller) ? $item->seller->f_name . ' ' . $item->seller->l_name : 'not found',
                'Seller Email'  => isset($item->seller) ? $item->seller->email : 'not found',
                'Seller Phone'  => isset($item->seller) ? $item->seller->phone : 'not found',
                'Seller Is' => $item->seller_is,
                'Shipping Address Data' => $item->shipping_address_data,
                'Delivery Type' => $item->delivery_type,
                'Delivery Man Id' => $item->delivery_man_id,
                'Delivery Service Name' => $item->delivery_service_name,
                'Third Party Delivery Tracking Id' => $item->third_party_delivery_tracking_id,
                'Checked' => $item->checked,

            ];
        }

        if ($type == 'csv') {
            return (new FastExcel($storage))->download('Order_All_details.csv');
        }

        return (new FastExcel($storage))->download('Order_All_details.xlsx');
    }
}
