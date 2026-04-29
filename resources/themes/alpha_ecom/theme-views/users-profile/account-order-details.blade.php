

@extends('theme-views.layouts.app')

@section('title', translate('Order_Details').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 py-3 mb-5">
    <div class="container">
        <div class="row g-3">
            <!-- Sidebar-->

            <div class="col-lg-12">
                <section class="order-details">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="top-breadcrumb">
                                    <a href="{{ route('home') }}" class="text-muted">Home</a>/<a
                                        href="{{route('account-oder')}}" class="text-muted">My Order</a>/
                                    <span class="text-primery">{{ $order->id }}</span>
                                </div>
                            </div>
                            <div class="col-lg-8 ">
                                <div class="order-delivery-address">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M10.9851 2.05508C11.5492 1.96812 12.118 1.99397 12.6797 2.06448C13.5869 2.17964 14.4519 2.44759 15.2674 2.8683C16.4661 3.48878 17.4368 4.36546 18.1842 5.48422C18.8164 6.4314 19.2066 7.47495 19.3829 8.59605C19.5168 9.44922 19.4839 10.3047 19.3382 11.1556C18.9786 13.2474 18.015 15.0712 16.8022 16.7799C15.4343 18.7072 13.8055 20.3924 11.977 21.8895C11.9323 21.9248 11.89 21.9624 11.8453 22C11.7678 22 11.6879 22 11.6103 22C11.3753 21.8002 11.1402 21.6028 10.9076 21.403C10.8676 21.2455 11.0039 21.1868 11.0862 21.1116C11.5633 20.6885 12.031 20.2537 12.4752 19.7954C13.7891 18.4322 14.9924 16.982 15.9537 15.3462C16.7646 13.9642 17.3804 12.507 17.599 10.9088C17.841 9.14603 17.559 7.4773 16.6682 5.92373C15.9232 4.62399 14.8749 3.63215 13.5564 2.93175C12.8395 2.551 12.0757 2.29011 11.2672 2.17025C11.1684 2.15614 11.0392 2.1867 10.9851 2.05508Z"
                                            fill="#EE6969" />
                                        <path
                                            d="M17.7358 9.71207C17.7288 9.33601 17.6982 8.95291 17.6418 8.57451C17.4797 7.48395 17.0942 6.48271 16.5066 5.55903C16.0436 4.83277 15.4631 4.20524 14.7838 3.67171C13.9941 3.05122 13.1151 2.60466 12.1538 2.31322C11.7683 2.1957 11.3688 2.16515 10.9833 2.05469C10.8353 2.06879 10.6848 2.07584 10.5368 2.10169C9.01846 2.36258 7.66937 2.98072 6.53416 4.02897C4.87482 5.56138 4.02635 7.46515 4.0005 9.71677C3.9864 10.9648 4.27314 12.1658 4.7338 13.3222C5.46711 15.1672 6.56471 16.7866 7.83389 18.3002C8.77168 19.4189 9.79877 20.4531 10.9058 21.4073C10.9105 21.3251 10.9763 21.2851 11.028 21.2381C11.3147 20.9749 11.6132 20.7234 11.8906 20.4507C12.3395 20.0065 12.779 19.5553 13.2115 19.0946C13.5005 18.7867 13.7802 18.4718 14.0435 18.1427C14.7063 17.3201 15.3432 16.4787 15.8838 15.5667C16.2457 14.958 16.5842 14.3375 16.8592 13.6841C17.2047 12.8639 17.4703 12.0201 17.6207 11.1411C17.7006 10.6663 17.7452 10.1868 17.7358 9.71207ZM11.7072 12.3914C10.2336 12.3797 9.04666 11.1716 9.06076 9.69797C9.07487 8.23371 10.2782 7.04209 11.7378 7.05384C13.2115 7.06324 14.4031 8.26661 14.3937 9.73792C14.3843 11.2116 13.1785 12.4032 11.7072 12.3914Z"
                                            fill="#E84B4B" />
                                    </svg>
                                    <div class="order-delivery-address-right">
                                        <h4 class="order-delivery-address-heading">Shipping address</h4>
                                        @php($shippingAdd = json_decode($order->shipping_address_data, true))
                                        <p class="address-edit mb-0">{{ $shippingAdd['contact_person_name'] }} <span>
                                                <p class="address-edit">{{ $shippingAdd['address'] }}
                                                    {{ $shippingAdd['address1'] }} {{ $shippingAdd['city'] }} -
                                                    {{ $shippingAdd['zip'] }}, {{ $shippingAdd['state'] }}
                                                    Phone number -{{ $shippingAdd['phone'] }}</p>
                                    </div>
                                </div>
                                <!-- STEP BY STEP WIDGET -->
                                @foreach ($order->details as $key=> $detail)
                                @php($product=json_decode($detail->product_details,true))
                                <div class="order-product-details mb-2">
                                    <div class="row align-items-center">
                                        <div class="col-lg-2 col-md-3">
                                            <div class="order-product-details-img">
                                                <img class="d-block img-fit"
                                                    onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                    src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                                    alt="VR Collection" width="60">
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-6 ">
                                            @if($product)
                                            <div class="order-product-details-main">
                                                <a href="{{ route('product',[$product['slug']]) }}">
                                                    <h4 class="ordered-product-name">
                                                        {{isset($product['name']) ? Str::limit($product['name'],40) : ''}}
                                                    </h4>
                                                </a>
                                                @if($detail['variant'])
                                                    <small>{{translate('variant')}} :{{$detail['variant']}} </small>
                                                @endif
                                                <h3 class="order-product-details-main-price">
                                                    {{\App\CPU\Helpers::currency_converter(($detail->qty*$detail->price)-$detail->discount)}}
                                                    <small
                                                        class="text-muted"><del>{{\App\CPU\Helpers::currency_converter($detail->price)}}</del></small>
                                                </h3>
                                                <div class="order-product-details-main-footer">
                                                    <p class="order-product-details-order-id">Order ID -
                                                        <span>{{$order['id']}}</span></p>
                                                    <p class="order-product-details-seller-id">Sold by :
                                                        <span>{{ $detail->product->seller->shop->name ?? "" }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-lg-3 col-md-3">
                                              <div class="d-flex gap-2 pe-md-3">
                                              @if($order->order_type == 'default_type')
                                                @if($order->order_status=='delivered')
                                                    <button class="btn btn-sm btn-primary w-50" data-bs-toggle="modal"
                                                    data-bs-target="#reviewModal{{$detail->id}}">{{translate('Review')}}</button>
                                                    @if($detail->refund_request !=0)
                                                    <a class="btn btn-sm btn-outline-primary text-nowrap"
                                                        href="{{route('refund-details',[$detail->id])}}">{{translate('refund_details')}}</a>
                                                    @endif
                                                    @php($order_details_date = $detail->created_at)
                                                    @php($length = $order_details_date->diffInDays($current_date))
                                                    @if( $length <= $refund_day_limit && $detail->refund_request == 0)
                                                        <button class="btn  btn-sm btn-outline-primary w-50"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#refundModal{{$detail->id}}">{{translate('Refund')}}</button>
                                                    @endif
                                                @elseif($order->order_status=='pending')
                                                    <a href="" class="proceed-to-buy w-75 mx-md-auto" data-bs-toggle="modal"
                                                        data-bs-target="#cancle-order-btn">Cancel order</a>
                                                @endif
                                            @endif
                                              </div>
                                        </div>
                                    </div>
                                </div>
                                @include('theme-views.layouts.partials.modal._review',['id'=>$detail->id,'order_details'=>$detail,])
                                @include('theme-views.layouts.partials.modal._refund',['id'=>$detail->id,'order_details'=>$detail,'order'=>$order,'product'=>$product])
                                @endforeach
                                <div class="col-12">
                                    <div class="card-body mb-xl-5 for-trak">
                                        @if ($order['order_status']!='returned' && $order['order_status']!='failed' &&
                                        $order['order_status']!='canceled')
                                        <div class="order__cancel__retrun__status">
                                            <div id="timeline">
                                                <div @if($order['order_status']=='processing' )
                                                        class="bar progress two"
                                                    @elseif($order['order_status']=='shipped' )
                                                        class="bar progress three"
                                                    @elseif($order['order_status']=='out_for_delivery' )
                                                        class="bar progress four"
                                                    @elseif($order['order_status']=='delivered' )
                                                        class="bar progress five" 
                                                    @else 
                                                        class="bar progress one" 
                                                    @endif>
                                                </div>
                                                <div class="state">
                                                    <ul class="delevery-status">
                                                        <li>
                                                            <div class="state-img">
                                                                <img width="30"
                                                                    src="{{theme_asset('assets/img/icons/order-placed.svg')}}"
                                                                    class="dark-support" alt="">
                                                            </div>
                                                            <div class="badge active">
                                                                <span>1</span>
                                                                <i class="bi bi-check"></i>
                                                            </div>
                                                            <div>
                                                                <div class="state-text">{{translate('Order_placed')}}
                                                                </div>
                                                                <div class="mt-2 fs-12">
                                                                    {{date('d M, Y h:i A',strtotime($order->created_at))}}
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="state-img">
                                                                <img width="30"
                                                                    src="{{theme_asset('assets/img/icons/packaging.svg')}}"
                                                                    class="dark-support" alt="">
                                                            </div>
                                                            <div
                                                                class="{{($order['order_status']=='shipped')||($order['order_status']=='processing') || ($order['order_status']=='processed') || ($order['order_status']=='out_for_delivery') || ($order['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                                <span>2</span>
                                                                <i class="bi bi-check"></i>
                                                            </div>
                                                            <div>
                                                                <div class="state-text">{{translate('Packaging_order')}}
                                                                </div>
                                                                @if(($order['order_status']=='processing') ||
                                                                ($order['order_status']=='processed') ||
                                                                ($order['order_status']=='out_for_delivery') ||
                                                                ($order['order_status']=='delivered'))
                                                                <div class="mt-2 fs-12">
                                                                    @if(\App\CPU\order_status_history($order['id'],'processing'))
                                                                    {{date('d M, Y h:i A',strtotime(\App\CPU\order_status_history($order['id'],'processing')))}}
                                                                    @endif
                                                                </div>
                                                                @endif

                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="state-img">
                                                                <img width="30"
                                                                    src="{{theme_asset('assets/img/icons/order-shipping.svg')}}"
                                                                    class="dark-support" alt="">
                                                            </div>
                                                            <div
                                                                class="{{($order['order_status']=='shipped'||$order['order_status']=='out_for_delivery'||$order['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                                <span>3</span>
                                                                <i class="bi bi-check"></i>
                                                            </div>
                                                            <div class="state-text">{{translate('Order_shipped')}}
                                                            </div>
                                                            @if(($order['order_status']=='shipped'))
                                                            <div class="mt-2 fs-12">
                                                                @if(\App\CPU\order_status_history($order['id'],'shipped'))
                                                                {{date('d M, Y h:i A',strtotime(\App\CPU\order_status_history($order['id'],'shipped')))}}
                                                                @endif
                                                            </div>
                                                            @endif
                                                        </li>
                                                        <li>
                                                            <div class="state-img">
                                                                <img width="30"
                                                                    src="{{theme_asset('assets/img/icons/on-the-way.svg')}}"
                                                                    class="dark-support" alt="">
                                                            </div>
                                                            <div
                                                                class="{{($order['order_status']=='out_for_delivery') || ($order['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                                <span>3</span>
                                                                <i class="bi bi-check"></i>
                                                            </div>
                                                            <div class="state-text">{{translate('Order_is_on_the_way')}}
                                                            </div>
                                                            @if(($order['order_status']=='out_for_delivery') ||
                                                            ($order['order_status']=='delivered'))
                                                            <div class="mt-2 fs-12">
                                                                @if(\App\CPU\order_status_history($order['id'],'out_for_delivery'))
                                                                {{date('d M, Y h:i A',strtotime(\App\CPU\order_status_history($order['id'],'out_for_delivery')))}}
                                                                @endif
                                                            </div>
                                                            @endif
                                                        </li>
                                                        <li>
                                                            <div class="state-img">
                                                                <img width="30"
                                                                    src="{{theme_asset('assets/img/icons/order-delivered.svg')}}"
                                                                    class="dark-support" alt="">
                                                            </div>
                                                            <div
                                                                class="{{($order['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                                <span>4</span>
                                                                <i class="bi bi-check"></i>
                                                            </div>
                                                            <div class="state-text">{{translate('Order_Delivered')}}
                                                            </div>
                                                            @if($order['order_status']=='delivered')
                                                            <div class="mt-2 fs-12">
                                                                @if(\App\CPU\order_status_history($order['id'],
                                                                'delivered'))
                                                                {{date('d M, Y h:i A',strtotime(\App\CPU\order_status_history($order['id'], 'delivered')))}}
                                                                @endif
                                                            </div>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-5 d-none">
                                            <div class="row">

                                                <div class="col-lg-6">
                                                    <address class="media gap-2 mb-0">
                                                        <img width="20"
                                                            src="{{theme_asset('assets/img/icons/location.png')}}"
                                                            class="dark-support" alt="">
                                                        <div class="media-body">
                                                            <div class=" fw-bold fs-16">
                                                                {{translate('Shipping_Address')}}</div>
                                                            @if($order->shippingAddress)
                                                            @php($shipping=$order->shippingAddress)
                                                            @else
                                                            @php($shipping=json_decode($order['shipping_address_data']))
                                                            @endif
                                                            <p> @if($shipping)
                                                                {{$shipping->address}},<br>
                                                                {{$shipping->city}}
                                                                , {{$shipping->zip}}

                                                                @endif
                                                            </p>
                                                        </div>
                                                    </address>
                                                </div>


                                                <div class="col-lg-6">
                                                    <address class="media gap-2 mb-0">
                                                        <img width="20"
                                                            src="{{theme_asset('assets/img/icons/location.png')}}"
                                                            class="dark-support" alt="">
                                                        <div class="media-body">
                                                            <div class=" fw-bold fs-16">
                                                                {{translate('Billing_Address')}}</div>
                                                            @if($order->billingAddress)
                                                            @php($billing=$order->billingAddress)
                                                            @else
                                                            @php($billing=json_decode($order['billing_address_data']))
                                                            @endif
                                                            <p>
                                                                @if($billing)
                                                                {{ $billing->address ?? '' }}, <br>
                                                                {{ $billing->city ?? '' }}
                                                                , {{ $billing->zip ?? '' }}
                                                                @else
                                                                {{ $shipping->address ?? '' }},<br>
                                                                {{ $shipping->city ?? '' }}
                                                                , {{ $shipping->zip ?? '' }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </address>
                                                </div>


                                            </div>
                                        </div>
                                        @elseif($order['order_status']=='returned')
                                        <div class="order__retrun">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <address class="media gap-2 mb-0">
                                                        <div class="media-body text-center">
                                                            <div class=" fw-bold fs-16 badge bg-info rounded-pill">
                                                                {{translate('Product_Successfully_Returned')}}</div>
                                                        </div>
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                        @elseif($order['order_status']=='canceled')
                                            <div class="order__cancel">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="align-items-center mt-2 mb-3">
                                                            <div class="d-flex gap-3 justify-content-start mt-2">
                                                                <h6>{{translate('Order_Status')}}</h6>

                                                                @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                                                <span class="badge bg-danger rounded-pill">
                                                                    {{translate($order['order_status'] =='failed' ? 'Failed To Deliver' : $order['order_status'])}}
                                                                </span>
                                                                @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' ||
                                                                $order['order_status']=='delivered')
                                                                <span class="badge bg-success rounded-pill">
                                                                    {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                                                                </span>
                                                                @else
                                                                <span class="badge bg-info rounded-pill">
                                                                    {{translate($order['order_status'])}}
                                                                </span>
                                                                @endif
                                                            </div>

                                                            <div class="d-flex gap-3 justify-content-start mt-2">
                                                                <h6>{{translate('Payment_Status')}}</h6>
                                                                <div class="{{ $order['payment_status']=='unpaid' ? 'text-danger':'text-dark' }}">
                                                                    {{ translate($order['payment_status']) }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                        <div class="mt-5">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <address class="media gap-2">
                                                        <div class="media-body text-center">
                                                            <div
                                                                class="mb-2 fw-bold fs-16 badge bg-danger rounded-pill">
                                                                {{translate('order_'.$order['order_status'].'_!_'.'Sorry_we_can`t_complete_your_order')}}
                                                            </div>
                                                        </div>
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4" >
                                <div class="down-invo {{ ($order['order_status']=='delivered') ? '' : 'd-none' }}">
                                    <a href="{{route('generate-invoice',[$order->id])}}">
                                        <div class="down-invo-r">
                                            <i class="fa-solid fa-file-invoice"></i>
                                            <span>Download Invoice</span>
                                        </div>
                                    </a>
                                    <a href="{{route('generate-invoice',[$order->id])}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path d="M12.1221 15.436L12.1221 3.39502" stroke="#040D12"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M15.0381 12.5083L12.1221 15.4363L9.20609 12.5083" stroke="#040D12"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M16.7551 8.12793H17.6881C19.7231 8.12793 21.3721 9.77693 21.3721 11.8129V16.6969C21.3721 18.7269 19.7271 20.3719 17.6971 20.3719L6.55707 20.3719C4.52207 20.3719 2.87207 18.7219 2.87207 16.6869V11.8019C2.87207 9.77293 4.51807 8.12793 6.54707 8.12793L7.48907 8.12793"
                                                stroke="#040D12" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </div>
                                <div class="order-price-details card">
                                    <div class="card-header">
                                        <p class="delivery-type d-flex justify-content-between align-items-center"><span>Expected Delivery Dates: </span> <span class="badge bg-info">
                                            @if(!empty($order->expected_delivery_date_range) && strpos($order->expected_delivery_date_range, ' - ') !== false)
                                                <?php
                                                    [$start, $end] = explode(' - ', $order->expected_delivery_date_range);

                                                    $startDate = strtotime($start);
                                                    $endDate = strtotime($end);
                                                ?>

                                                @if($startDate && $endDate)
                                                    {{ date('d M, Y', $startDate) }} - {{ date('d M, Y', $endDate) }}
                                                @else
                                                    -
                                                @endif
                                            @else
                                                -
                                            @endif

                                    </span></p>
                                    </div>
                                    <div class="card-header">
                                        <p class="delivery-type d-flex justify-content-between align-items-center"><span>Delivery Type</span> <span class="badge bg-info">{{ \App\CPU\Helpers::get_shipping_method_name($order->shipping_method_id) }}</span></p>
                                    </div>
                                    <div class="card-header">
                                        <p class="delivery-type d-flex justify-content-between align-items-center"><span>Payment Status</span> <span class="badge bg-{{ $order->payment_status == 'unpaid' ? 'warning' : 'success' }}">{{ translate($order->payment_status) }}</span></p>
                                    </div>
                                    <div class="card-header">
                                        <p class="delivery-type d-flex justify-content-between align-items-center"><span>Payment Method</span> <span class="badge bg-primary">{{ translate($order->payment_method) }}</span></p>
                                    </div>
                                    <div class="card-body">
                                        @php($summary=\App\CPU\OrderManager::order_summary($order))
                                        <?php
                                            if ($order['extra_discount_type'] == 'percent') {
                                                $extra_discount = ($summary['subtotal'] / 100) * $order['extra_discount'];
                                            } else {
                                                $extra_discount = $order['extra_discount'];
                                            }
                                        ?>
                                        <p class="delivery-type">{{translate('price_Detail')}}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="selected-delivery-type">MRP ({{$order->details->count()}} items)
                                            </p>
                                            <p class="delivery-type">
                                                {{\App\CPU\Helpers::currency_converter($summary['subtotal'])}}</p>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="selected-delivery-type">{{translate('Discount')}}
                                                {{translate('on_product')}}</p>
                                            <p class="delivery-type">
                                                -{{\App\CPU\Helpers::currency_converter($summary['total_discount_on_product'])}}
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="selected-delivery-type">{{translate('Subtotal')}}</p>
                                            <p class="delivery-type">
                                                {{\App\CPU\Helpers::currency_converter($summary['subtotal']-$summary['total_discount_on_product'])}}</p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="selected-delivery-type">{{translate('tax_fee')}}</p>
                                            <p class="delivery-type">
                                                {{\App\CPU\Helpers::currency_converter($summary['total_tax'])}}</p>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="selected-delivery-type">{{translate('Shipping')}}
                                                {{translate('Fee')}}</p>
                                            <p class="delivery-type">
                                                {{\App\CPU\Helpers::currency_converter($summary['total_shipping_cost'])}}
                                            </p>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="selected-delivery-type">{{translate('Coupon')}}
                                                {{translate('Discount')}}</p>
                                            <p class="delivery-type">
                                                -{{\App\CPU\Helpers::currency_converter($order->discount_amount)}}</p>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="delivery-type">Total Amount</p>
                                            <p class="totle-order-amount">
                                                {{ \App\CPU\Helpers::currency_converter($order->order_amount) }}</p>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                </section>

                
                <div class="card h-100 d-none">
                    <div class="card-body p-lg-4">
                        @include('theme-views.partials._order-details-head',['order'=>$order])
                        <div class="mt-4 card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    @php($digital_product = false)
                                    @foreach ($order->details as $key=>$detail)
                                    @if(isset($detail->product->digital_product_type))
                                    @php($digital_product = $detail->product->product_type == 'digital' ? true : false)
                                    @if($digital_product == true)
                                    @break
                                    @else
                                    @continue
                                    @endif
                                    @endif
                                    @endforeach
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0">{{translate('Product_Details')}}</th>
                                                <th class="border-0 text-center">{{translate('Qty')}}</th>
                                                <th class="border-0 text-end">{{translate('Unit_Price')}}</th>
                                                <th class="border-0 text-end">{{translate('Discount')}}</th>
                                                <th class="border-0 text-end"
                                                    {{ ($order->order_type == 'default_type' && $order->order_status=='delivered') ? 'colspan="2"':'' }}>
                                                    {{translate('Total')}}</th>
                                                @if($order->order_type == 'default_type' &&
                                                ($order->order_status=='delivered' || ($order->payment_status == 'paid'
                                                && $digital_product)))
                                                <th class="border-0 text-center">{{translate('Action')}}</th>
                                                @elseif($order->order_type != 'default_type' &&
                                                $order->order_status=='delivered')
                                                <th class="border-0 text-center"></th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->details as $key=>$detail)
                                            @php($product=json_decode($detail->product_details,true))
                                            @if($product)
                                            <tr>
                                                <td>
                                                    <div class="media gap-3">
                                                        <div class="avatar avatar-xxl rounded border overflow-hidden">
                                                            <img class="d-block img-fit"
                                                                onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                                src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                                                alt="VR Collection" width="60">
                                                        </div>
                                                        <div class="media-body d-flex gap-1 flex-column">
                                                            <h6>
                                                                <a href="{{route('product',[$product['slug']])}}">
                                                                    {{isset($product['name']) ? Str::limit($product['name'],40) : ''}}
                                                                </a>
                                                                @if($detail->refund_request == 1)
                                                                <small> ({{translate('refund_pending')}}
                                                                    ) </small> <br>
                                                                @elseif($detail->refund_request == 2)
                                                                <small> ({{translate('refund_approved')}}
                                                                    ) </small> <br>
                                                                @elseif($detail->refund_request == 3)
                                                                <small> ({{translate('refund_rejected')}}
                                                                    ) </small> <br>
                                                                @elseif($detail->refund_request == 4)
                                                                <small> ({{translate('refund_refunded')}}
                                                                    ) </small> <br>
                                                                @endif<br>
                                                            </h6>
                                                            @if($detail->variant)
                                                            <small>{{translate('variant')}}
                                                                :{{$detail->variant}} </small>

                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{$detail->qty}}</td>

                                                <td class="text-end">
                                                    {{\App\CPU\Helpers::currency_converter($detail->price)}} </td>
                                                <td class="text-end">
                                                    {{\App\CPU\Helpers::currency_converter($detail->discount)}}</td>

                                                <td class="text-end">
                                                    {{\App\CPU\Helpers::currency_converter(($detail->qty*$detail->price)-$detail->discount)}}
                                                </td>

                                                @php($order_details_date = $detail->created_at)
                                                @php($length = $order_details_date->diffInDays($current_date))
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        @if($detail->product && $order->payment_status == 'paid' &&
                                                        $detail->product->digital_product_type == 'ready_product')
                                                        <a href="{{ route('digital-product-download', $detail->id) }}"
                                                            class="btn btn-primary rounded-pill mb-1"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            data-bs-title="{{translate('Download')}}">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                        @elseif($detail->product && $order->payment_status == 'paid' &&
                                                        $detail->product->digital_product_type == 'ready_after_sell')
                                                        @if($detail->digital_file_after_sell)
                                                        <a href="{{ route('digital-product-download', $detail->id) }}"
                                                            class="btn btn-primary rounded-pill mb-1"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            data-bs-title="{{translate('Download')}}">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                        @else
                                                        <span class="btn btn-success mb-1 opacity-half cursor-auto"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            data-bs-title="{{translate('Product_not_uploaded_yet')}}">
                                                            <i class="bi bi-download"></i>
                                                        </span>
                                                        @endif
                                                        @endif
                                                    </div>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        @if($order->order_type == 'default_type')
                                                        @if($order->order_status=='delivered')
                                                        <button class="btn btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#reviewModal{{$detail->id}}">{{translate('Review')}}</button>
                                                        @include('theme-views.layouts.partials.modal._review',['id'=>$detail->id,'order_details'=>$detail,])
                                                        @if($detail->refund_request !=0)
                                                        <a class="btn btn-outline-primary  text-nowrap"
                                                            href="{{route('refund-details',[$detail->id])}}">{{translate('refund_details')}}</a>
                                                        @endif
                                                        @if( $length <= $refund_day_limit && $detail->refund_request ==
                                                            0)
                                                            <button class="btn btn-outline-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#refundModal{{$detail->id}}">{{translate('Refund')}}</button>
                                                            @include('theme-views.layouts.partials.modal._refund',['id'=>$detail->id,'order_details'=>$detail,'order'=>$order,'product'=>$product])
                                                            @endif

                                                            @endif
                                                            @else
                                                            <label
                                                                class="badge bg-info rounded-pill">{{translate('pos_order')}}</label>
                                                            @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                            @php($summary=\App\CPU\OrderManager::order_summary($order))
                                            <?php
                                            if ($order['extra_discount_type'] == 'percent') {
                                                $extra_discount = ($summary['subtotal'] / 100) * $order['extra_discount'];
                                            } else {
                                                $extra_discount = $order['extra_discount'];
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row justify-content-end mt-2">
                                    <div class="col-xl-6 col-lg-7 col-md-8 col-sm-10">
                                        <div class="d-flex flex-column gap-3 text-dark">
                                            <div
                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                <div>{{translate('Item')}}</div>
                                                <div>{{$order->details->count()}}</div>
                                            </div>
                                            <div
                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                <div>{{translate('Subtotal')}}</div>
                                                <div>{{\App\CPU\Helpers::currency_converter($summary['subtotal'])}}
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                <div>{{translate('tax_fee')}}</div>
                                                <div>{{\App\CPU\Helpers::currency_converter($summary['total_tax'])}}
                                                </div>
                                            </div>
                                            @if($order->order_type == 'default_type')
                                            <div
                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                <div>{{translate('Shipping')}} {{translate('Fee')}}</div>
                                                <div>
                                                    {{\App\CPU\Helpers::currency_converter($summary['total_shipping_cost'])}}
                                                </div>
                                            </div>
                                            @endif
                                            <div
                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                <div>{{translate('Discount')}} {{translate('on_product')}}</div>
                                                <div>
                                                    {{\App\CPU\Helpers::currency_converter($summary['total_discount_on_product'])}}
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                <div>{{translate('Coupon')}} {{translate('Discount')}}</div>
                                                <div>
                                                    -{{\App\CPU\Helpers::currency_converter($order->discount_amount)}}
                                                </div>
                                            </div>
                                            @if($order->order_type != 'default_type')
                                            <div
                                                class="d-flex flex-wrap justify-content-between align-`item`s-center gap-2">
                                                <div>{{translate('extra')}} {{translate('Discount')}}</div>
                                                <div>
                                                    -{{\App\CPU\Helpers::currency_converter($extra_discount)}}</div>
                                            </div>
                                            @endif
                                            <div
                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                <h4>{{translate('Total')}}</h4>
                                                <h2 class="text-primary">
                                                    {{\App\CPU\Helpers::currency_converter($order->order_amount)}}</h2>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- End Main Content -->

<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="cancle-order-btn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered model-sm">
        <div class="modal-content">
            <div class="modal-body text-ceter">
                <h3 class="mx-auto">Cancel order reason</h3>
                <div class="form-check">
                    <input class="design-new-radio-btn" type="radio" name="exampleRadios" id="radio-custom1" value="Changed My Mind" checked>
                    <label class="form-check-label fs-20 text-muted" for="radio-custom1">Changed My Mind</label>
                </div>
                <div class="form-check">
                    <input class="design-new-radio-btn" type="radio" name="exampleRadios" id="radio-custom2" value="Found a Better Deal" >
                    <label class="form-check-label fs-20 text-muted" for="radio-custom2">Found a Better Deal</label>
                </div>
                <div class="form-check">
                    <input class="design-new-radio-btn" type="radio" name="exampleRadios" id="radio-custom3" value="Delay in Delivery">
                    <label class="form-check-label fs-20 text-muted" for="radio-custom3">Delay in Delivery</label>
                </div>
                <div class="form-check">
                    <input class="design-new-radio-btn" type="radio" name="exampleRadios" id="radio-custom4" value="No Longer Need the Item">
                    <label class="form-check-label fs-20 text-muted" for="radio-custom4">No Longer Need the Item</label>
                </div>
                <div class="form-check">
                    <input class="design-new-radio-btn" type="radio" name="exampleRadios" id="radio-custom5" value="Technical Issues">
                    <label class="form-check-label fs-20 text-muted" for="radio-custom5">Technical Issues</label>
                </div>
                <div class="form-check">
                    <input class="design-new-radio-btn" type="radio" name="exampleRadios" id="radio-custom6" value="Personal Emergency">
                    <label class="form-check-label fs-20 text-muted" for="radio-custom6">Personal Emergency</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#verify-cancle-order" data-bs-dismiss="modal" aria-label="Close">DONE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="verify-cancle-order" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered model-sm">
        <div class="modal-content ">
            <div class="modal-body text-center">
                <div class="icon-verify-cancle-order">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path
                            d="M31.2638 22.5199C31.2638 19.7109 29.7698 17.2564 27.5426 15.9218C27.4939 15.8181 27.4979 15.703 27.4979 15.5776C27.499 13.7807 27.4929 11.9838 27.504 10.1878C27.5061 9.80461 27.3954 9.49947 27.1435 9.21385C25.6942 7.56691 24.2511 5.91381 22.812 4.25762C22.2656 3.62884 21.5902 3.31445 20.7625 3.31445C16.5345 3.31651 12.3066 3.31651 8.07862 3.31445C7.27223 3.31445 6.5999 3.60829 6.06468 4.22269C4.65604 5.83778 3.25552 7.46006 1.83063 9.06077C1.47822 9.45632 1.33197 9.86729 1.33401 10.3995C1.35025 15.1903 1.34619 19.9822 1.34721 24.774C1.34721 26.2566 2.02157 26.947 3.47591 26.948C7.99128 26.949 12.5066 26.949 17.022 26.949C17.2383 26.949 17.4293 26.9326 17.5948 27.1607C18.651 28.6217 20.0719 29.5556 21.7842 30.0498C21.7903 30.0487 21.7953 30.0467 21.8014 30.0456C22.4037 30.2059 23.0354 30.2912 23.6864 30.2912C27.3111 30.2912 30.3386 27.6395 31.0861 24.1791C31.0911 24.1771 31.0962 24.176 31.1013 24.174C31.1247 24.1 31.1236 24.0199 31.1328 23.9418C31.1358 23.9254 31.1389 23.9079 31.1419 23.8904C31.1531 23.8411 31.1724 23.7939 31.21 23.7507C31.2506 23.3428 31.2679 22.9452 31.2628 22.5589C31.2628 22.5445 31.2638 22.5322 31.2638 22.5199ZM15.3879 8.54912C15.4184 7.59157 15.4062 6.63299 15.3899 5.67545C15.3849 5.38263 15.4285 5.26243 15.7667 5.26654C17.3897 5.28811 19.0136 5.27886 20.6365 5.2727C20.9219 5.27167 21.1646 5.32407 21.3627 5.55318C22.2981 6.63402 23.2385 7.70869 24.1759 8.78645C24.2165 8.83371 24.2429 8.89227 24.3201 9.01762C23.9555 9.01762 23.659 9.01762 23.3624 9.01762C20.86 9.01762 18.3565 9.0094 15.8541 9.02892C15.4712 9.03097 15.3747 8.93337 15.3879 8.54912ZM7.5048 5.52955C7.66933 5.34154 7.8887 5.27167 8.14057 5.2727C9.796 5.27784 11.4504 5.28297 13.1058 5.27167C13.3922 5.26962 13.4745 5.35284 13.4704 5.6436C13.4532 6.65149 13.4572 7.6604 13.4684 8.6683C13.4715 8.92515 13.4105 9.02995 13.1333 9.02892C10.3129 9.01762 7.49262 9.01762 4.6733 9.01248C4.64588 9.01248 4.61846 8.98679 4.53925 8.94775C5.54672 7.78369 6.52373 6.65457 7.5048 5.52955ZM15.9495 24.9939C11.9257 24.9764 7.90089 24.9743 3.87707 24.9969C3.37537 25 3.2799 24.8541 3.28193 24.3723C3.30123 20.0613 3.29716 15.7502 3.28091 11.4392C3.2799 11.0694 3.37841 10.9913 3.72676 10.9933C6.8213 11.0087 9.91483 11.0128 13.0094 10.9954C13.4105 10.9933 13.4816 11.1197 13.4704 11.4865C13.444 12.4091 13.4552 13.3338 13.4623 14.2574C13.4674 14.9129 13.8564 15.3454 14.42 15.3465C14.9837 15.3475 15.3879 14.9108 15.394 14.2667C15.4031 13.2916 15.4062 12.3166 15.391 11.3416C15.3869 11.0735 15.46 10.9944 15.7271 10.9944C18.906 11.0036 22.0848 11.0026 25.2636 11.0005C25.4292 11.0005 25.5602 10.9933 25.5602 11.2255C25.5551 12.5016 25.5571 13.7786 25.5571 15.0886C24.9589 14.9355 24.3323 14.8533 23.6874 14.8533C19.5742 14.8533 16.2278 18.1698 16.1151 22.3041C16.113 22.3544 16.112 22.4048 16.111 22.4562C16.111 22.4777 16.109 22.4983 16.109 22.5199C16.109 22.5332 16.11 22.5455 16.11 22.5589C16.108 23.1846 16.1801 23.8185 16.3365 24.4493C16.4401 24.8696 16.4259 24.9959 15.9495 24.9939ZM23.6864 28.1788C22.9684 28.1788 22.2838 28.0484 21.6562 27.811C21.5587 27.7556 21.4541 27.7114 21.3546 27.66C20.3644 27.1566 19.5539 26.461 18.9882 25.4819C18.5739 24.7648 18.3149 24.0435 18.2113 23.3212C18.1869 23.0603 18.1798 22.7932 18.194 22.5199C18.3453 19.5794 20.5411 17.0664 23.6864 17.0664C24.5659 17.0664 25.3743 17.2441 26.0832 17.5698C26.1147 17.5852 26.1452 17.5996 26.1766 17.615C26.1898 17.6212 26.202 17.6273 26.2152 17.6335C27.0836 18.0856 27.8108 18.7698 28.3287 19.603C28.8101 20.4291 29.0782 21.4205 29.0782 22.5199C29.0772 25.7028 26.8317 28.1788 23.6864 28.1788Z"
                            fill="#040D12" />
                        <path
                            d="M26.6775 20.3199C26.7291 20.2693 26.7703 20.2088 26.7987 20.1419C26.827 20.0751 26.842 20.0031 26.8426 19.9303C26.8433 19.8575 26.8296 19.7853 26.8025 19.7179C26.7754 19.6505 26.7353 19.5892 26.6846 19.5377C26.6338 19.4862 26.5735 19.4455 26.5071 19.4179C26.4407 19.3903 26.3696 19.3764 26.2979 19.377C26.2261 19.3776 26.1552 19.3926 26.0893 19.4214C26.0233 19.4501 25.9637 19.4918 25.9138 19.5442L23.7481 21.7412L21.5831 19.5442C21.5337 19.4903 21.474 19.4471 21.4077 19.4171C21.3414 19.3871 21.2699 19.371 21.1973 19.3697C21.1248 19.3684 21.0527 19.382 20.9854 19.4095C20.9182 19.4371 20.857 19.4782 20.8057 19.5302C20.7544 19.5823 20.714 19.6443 20.6868 19.7126C20.6596 19.7809 20.6463 19.854 20.6475 19.9276C20.6488 20.0013 20.6647 20.0739 20.6942 20.1411C20.7238 20.2084 20.7664 20.2689 20.8194 20.3191L22.983 22.5169L20.818 24.7139C20.7226 24.8179 20.6706 24.9554 20.6731 25.0974C20.6755 25.2395 20.7322 25.375 20.8312 25.4755C20.9302 25.576 21.0638 25.6335 21.2038 25.636C21.3438 25.6385 21.4793 25.5858 21.5817 25.4889L23.7481 23.2919L25.9131 25.4897C26.0155 25.5865 26.151 25.6393 26.291 25.6368C26.431 25.6342 26.5645 25.5767 26.6635 25.4762C26.7625 25.3758 26.8192 25.2402 26.8217 25.0982C26.8242 24.9561 26.7722 24.8186 26.6768 24.7147L24.5132 22.5169L26.6775 20.3199Z"
                            fill="#040D12" />
                    </svg>
                </div>
                <h4 class="text-ceter">Are you sure?</h4>
                <p class="text-ceter text-muted">Are you sure , that you want to Cancel Order</p>
                <div class="d-flex justify-content-around align-items-center">
                    <button type="button" class="btn btn-cancle-order-stop text-bold" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                    <button type="button" class="btn btn-primary text-bold cancel-order">Cancel Order</button>
                </div>
            </div>
        </div>
    </div>

</div>





    @endsection

    @push('script')
    
    <script src="{{ theme_asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/spartan-multi-image-picker.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $(".coba").spartanMultiImagePicker({
                fieldName: 'fileUpload[]',
                maxCount: 5,
                rowHeight: '150px',
                groupClassName: 'col-md-4',
                placeholderImage: {
                    image: "{{ theme_asset('/img/image-place-holder.png ') }}",
                    width: '100%'
                },
                dropFileLabel: "{{ translate('drop_here') }}",
                onAddRow: function(index, file) {
                    console.log(index+1);
			        console.log('add new row');
                },
                onRenderedPreview: function(index) {
                    console.log(index);
			        console.log('add new row');
                },
                onRemoveRow: function(index) {
                    console.log(index);
			        console.log('add new row');
                },
                onExtensionErr: function(index, file) {
                    toastr.error('{{translate('input_png_or_jpg ')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{translate('file_size_too_big')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                }
            });
        });

        $(function() {
            $(".coba_refund").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 5,
                rowHeight: '150px',
                groupClassName: 'col-md-4',
                maxFileSize: '',
                placeholderImage: {
                    image: "{{ theme_asset('/img/image-place-holder.png ') }}",
                    width: '100%'
                },
                dropFileLabel: "{{translate('drop_here')}}",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    // toastr.error('{{translate('input_png_or_jpg ')}}', {
                    //         CloseButton: true,
                    //         ProgressBar: true
                    //     });
                },
                onSizeErr: function(index, file) {
                    // toastr.error('{{translate('file_size_too_big ')}}', {
                    //         CloseButton: true,
                    //         ProgressBar: true
                    //     });
                }
            });
        });

        $(document).on('click',' .cancel-order', function() {
            var order_id = "{{ $order->id }}"
            var reason = $('input[name=exampleRadios]:checked').val()

            $.ajax({
                type: "GET",
                url: "{{ route('order-cancel', $order->id) }}",
                data: {
                    remarks:reason
                },
                dataType: "json",
                success: function (response) {
                    if(response.status) {
                        swal.fire(`${response.message}`, '', 'success').then(function() {
                            window.location.reload()
                        })
                    } else {
                        swal.fire(`${response.message}`, '', 'error')
                    }
                }
            });

        })

    </script>
    @endpush