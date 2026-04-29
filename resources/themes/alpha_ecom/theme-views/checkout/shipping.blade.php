@extends('theme-views.layouts.app')

@section('title', translate('Shopping_Details').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/select2/css/select2.min.css') }}">
    <style>
        .select2-container--default{
            width: 100% !important;
            border-radius: 0.375rem;
        }
    </style>
@endpush
@section('content')

<style>
    .stripe-button-el {
        display: none !important;
    }

    .razorpay-payment-button {
        display: none !important;
    }
</style>

{{--stripe--}}
<script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
<script src="https://js.stripe.com/v3/"></script>
{{--stripe--}}

@php
    $shippingMethod = \App\CPU\Helpers::get_business_settings('shipping_method');
    $cart = \App\Model\Cart::where(['customer_id' => auth('customer')->id()])->get()->groupBy('cart_group_id');
    $carts = \App\Model\Cart::where(['customer_id' => auth('customer')->id()])->get();
    $coupon_dis = 0;
@endphp

@if(request('group_id') != "")
    @php($shippingMethod = \App\CPU\Helpers::get_business_settings('shipping_method'))
    @php($cart = \App\Model\Cart::where(['cart_group_id' => request('group_id')])->get()->groupBy('cart_group_id'))
    @php($carts = \App\Model\Cart::where(['cart_group_id' => request('group_id')])->get())
    @php($coupon_dis = 0)
@endif

    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3">

        <section>
            <div class="container-fluid productcontent">
                <div class="col-lg-12">
                    <div class="home-and-back-btn">
                        <p class="mb-4"><a href="{{ route('home') }}" class="me-2">Home</a>/ <a href="{{ route('shop-cart') }}" class="mx-2">Cart</a>/<a href="" class="ms-2"><span>Checkout</span></a></p>
                    </div>
                </div>
                <div class="row over-flowhidden">
                <div class="col-md-8 over-flowhidden">
                    <form id="msform">
                        <!-- progressbar -->
                      <h4 class="mb-4">Shipping Address</h4>
                        <!-- fieldsets -->
                        <div class="">
                        <fieldset>
                            <div class="card bg-light-gery  rounded shadow-sm border-0 mb-4 colorchnage">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="address-add-new w-75">
                                            <div class="i">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none">
                                                    <path
                                                        d="M10.9851 2.05508C11.5492 1.96812 12.118 1.99397 12.6797 2.06448C13.5869 2.17964 14.4519 2.44759 15.2674 2.8683C16.4661 3.48878 17.4368 4.36546 18.1842 5.48422C18.8164 6.4314 19.2066 7.47495 19.3829 8.59605C19.5168 9.44922 19.4839 10.3047 19.3382 11.1556C18.9786 13.2474 18.015 15.0712 16.8022 16.7799C15.4343 18.7072 13.8055 20.3924 11.977 21.8895C11.9323 21.9248 11.89 21.9624 11.8453 22C11.7678 22 11.6879 22 11.6103 22C11.3753 21.8002 11.1402 21.6028 10.9076 21.403C10.8676 21.2455 11.0039 21.1868 11.0862 21.1116C11.5633 20.6885 12.031 20.2537 12.4752 19.7954C13.7891 18.4322 14.9924 16.982 15.9537 15.3462C16.7646 13.9642 17.3804 12.507 17.599 10.9088C17.841 9.14603 17.559 7.4773 16.6682 5.92373C15.9232 4.62399 14.8749 3.63215 13.5564 2.93175C12.8395 2.551 12.0757 2.29011 11.2672 2.17025C11.1684 2.15614 11.0392 2.1867 10.9851 2.05508Z"
                                                        fill="#EE6969" />
                                                    <path
                                                        d="M17.7358 9.71207C17.7288 9.33601 17.6982 8.95291 17.6418 8.57451C17.4797 7.48395 17.0942 6.48271 16.5066 5.55903C16.0436 4.83277 15.4631 4.20524 14.7838 3.67171C13.9941 3.05122 13.1151 2.60466 12.1538 2.31322C11.7683 2.1957 11.3688 2.16515 10.9833 2.05469C10.8353 2.06879 10.6848 2.07584 10.5368 2.10169C9.01846 2.36258 7.66937 2.98072 6.53416 4.02897C4.87482 5.56138 4.02635 7.46515 4.0005 9.71677C3.9864 10.9648 4.27314 12.1658 4.7338 13.3222C5.46711 15.1672 6.56471 16.7866 7.83389 18.3002C8.77168 19.4189 9.79877 20.4531 10.9058 21.4073C10.9105 21.3251 10.9763 21.2851 11.028 21.2381C11.3147 20.9749 11.6132 20.7234 11.8906 20.4507C12.3395 20.0065 12.779 19.5553 13.2115 19.0946C13.5005 18.7867 13.7802 18.4718 14.0435 18.1427C14.7063 17.3201 15.3432 16.4787 15.8838 15.5667C16.2457 14.958 16.5842 14.3375 16.8592 13.6841C17.2047 12.8639 17.4703 12.0201 17.6207 11.1411C17.7006 10.6663 17.7452 10.1868 17.7358 9.71207ZM11.7072 12.3914C10.2336 12.3797 9.04666 11.1716 9.06076 9.69797C9.07487 8.23371 10.2782 7.04209 11.7378 7.05384C13.2115 7.06324 14.4031 8.26661 14.3937 9.73792C14.3843 11.2116 13.1785 12.4032 11.7072 12.3914Z"
                                                        fill="#E84B4B" />
                                                </svg>
                                            </div>
                                            <div class="content-add-new-address">
                                                <h5 class="mb-2"><b>Your Shipping Address</b></h5>
                                                <p>Upgrade your shipping experience! Let's ensure your latest address is on point for swift deliveries</p>
                                            </div>
                                        </div>
                                        <!-- <div class="btn-check-save">
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#add-new-address" class="btn-add-to-cardproduct">Edit Address</button>
                                        </div> -->
                                        @if($shipping_addresses->isEmpty())
                                            <a href="{{ route('account-address-add', ['return' => url()->current()]) }}" class="btn-login for-empty">
                                                {{ translate('Add_New_Address') }}
                                            </a>
                                        @else
                                           <?php
                                                $firstAddress = $shipping_addresses->first();
                                            ?>

                                            @if($firstAddress)
                                                <a href="{{ route('address-edit', $firstAddress->id) . '?return=' . urlencode(url()->current()) }}" class="p-0 bg-transparent border-0">
                                                    <button type="button" class="btn-add-to-cardproduct">Edit Address</button>
                                                </a>
                                            @endif

                                        @endif


                                    </div>
                                </div>
                            </div>
                        <div class="row"> 
                            <?php
                                $address = $shipping_addresses->first();
                            ?>

                            @if($address)
                                <div class="col-md-6">
                                    <div class="card mt-4 border-0 shipping_address br-12">
                                        <div class="card-header bg-light bg-body">
                                            <div class="form-check">
                                                <input type="radio" name="shipping_method_id" value="{{ $address['id'] }}" checked id="address{{ $address['id'] }}">
                                                <label class="form-check-label fs-20 fw-700 cursor-pointer" for="address{{ $address['id'] }}">{{ ucwords($address['address_type']) }}</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="address-edit"><b>{{ $address['contact_person_name'] }} <span>|</span> {{ $address['phone'] }}</b></p>
                                                    <p class="address-edit">
                                                        {{ $address['address'] }}, {{ $address['city'] }}, {{ $address['zip'] }} <br />
                                                        {{ $address['city'] }}, {{ $address['country'] }}
                                                    </p>
                                                    <address>
                                                        <dl class="mb-0 flexible-grid sm-down-1" style="display:none;">
                                                            <dt>{{ translate('name') }}</dt>
                                                            <dd class="shipping-contact-person">{{ $address['contact_person_name'] }}</dd>

                                                            <dt>{{ translate('phone') }}</dt>
                                                            <dd class="shipping-contact-phone"><a href="tel:{{ $address['phone'] }}" class="text-dark">{{ $address['phone'] }}</a></dd>

                                                            <dt>{{ translate('address') }}</dt>
                                                            <dd>{{ $address['address'] }}, {{ $address['city'] }}, {{ $address['zip'] }}</dd>
                                                            <span class="shipping-contact-address d-none">{{ $address['address'] }}</span>
                                                            <span class="shipping-contact-city d-none">{{ $address['city'] }}</span>
                                                            <span class="shipping-contact-zip d-none">{{ $address['zip'] }}</span>
                                                            <span class="shipping-contact-country d-none">{{ $address['country'] }}</span>
                                                            <span class="shipping-contact-address_type d-none">{{ $address['address_type'] }}</span>
                                                        </dl>
                                                    </address>
                                                </div>
                                                <div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <label class="custom-checkbox d-none">
                                {{ translate('Same_as_Delivery_Address') }}
                                <input type="checkbox" id="same_as_shipping_address" onclick="hide_billingAddress()"
                                        name="same_as_shipping_address" class="billing-address-checkbox" checked>
                            </label>
                        
                        </div>
                            <!-- <a type="button"  class="nextstep action-button btn-add-cart2" >Deliver Here</a> -->
                        </fieldset>
                        <fieldset id="pRelative">
                            @foreach($carts as $dt_cart)
                                <div class="card br-12 my-2" data-aos="fade-up">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="product-images-cart">
                                                <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                    src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$dt_cart['thumbnail']}}"
                                                    class="me-4 cart-list-image" alt="">
                                            </div>

                                            <div class="product-content-cart">
                                                <h6><a href="{{route('product',$dt_cart['slug'])}}">{{$dt_cart['name']}}</a></h6>
                                                <p class="item-bill"><span>{{ \App\CPU\Helpers::currency_converter(($dt_cart['price']-$dt_cart['discount'])*$dt_cart['quantity']) }} </span> <s>{{ \App\CPU\Helpers::currency_converter($dt_cart['price']*$dt_cart['quantity']) }} </s></p>

                                                @foreach(json_decode($dt_cart['variations'],true) as $key1 =>$variation)
                                                    <div class="fs-12">{{$key1}} : {{$variation}}</div>
                                                @endforeach

                                                @if ($shippingMethod=='inhouse_shipping')
                                                    <?php

                                                    $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                                    $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';

                                                    ?>
                                                @else
                                                    <?php
                                                        if ($dt_cart['seller_is'] == 'admin') {
                                                            $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                                        } else {
                                                            $seller_shipping = \App\Model\ShippingType::where('seller_id', $dt_cart['seller_is'])->first();
                                                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                                                        }
                                                    ?>
                                                @endif
                                                @if ( $shipping_type != 'order_wise')
                                                <div class="fs-12">{{ translate('shipping_cost') }} : {{ \App\CPU\Helpers::currency_converter($dt_cart['shipping_cost']) }}</div>
                                                @endif
                                                <div class="d-none align-items-center pay-and-reward">
                                                    <p>or Pay $100 + </p>
                                                    <p><img src="{{ theme_asset('assets/images/product-detail-images/reward-icon.svg')}}" alt="">20</p>
                                                </div>

                                                <div
                                                    class="d-flex align-items-center justify-content-between flex-wrap inside-cart-button">
                                                    <div class="qunty">
                                                        @php($minimum_order=\App\CPU\ProductManager::get_product($dt_cart['product_id']))
                                                        <div class="quantity quantity--style-two d-inline-flex">
                                                            <span class="quantity__minus " onclick="updateCartQuantityList('{{ $minimum_order->minimum_order_qty ?? 1 }}', '{{$dt_cart['id']}}', '-1', '{{ isset($minimum_order->minimum_order_qty) && $dt_cart['quantity'] == $minimum_order->minimum_order_qty ? 'delete':'minus' }}')">
                                                                <i class="{{ $dt_cart['quantity'] == (isset($dt_cart->product->minimum_order_qty) ? $dt_cart->product->minimum_order_qty : 1) ? 'bi bi-trash3-fill text-danger fs-10' : 'bi bi-dash' }}"></i>
                                                            </span>
                                                            <input type="text" class="quantity__qty" value="{{$dt_cart['quantity']}}" name="quantity[{{ $dt_cart['id'] }}]" id="cartQuantity{{$dt_cart['id']}}"
                                                                onchange="updateCartQuantityList('{{ $minimum_order->minimum_order_qty ?? 1 }}', '{{$dt_cart['id']}}', '0')" data-min="{{ isset($dt_cart->product->minimum_order_qty) ? $dt_cart->product->minimum_order_qty : 1 }}">
                                                            <span class="quantity__plus" onclick="updateCartQuantityList('{{ $minimum_order->minimum_order_qty ?? 1 }}', '{{$dt_cart['id']}}', '1')">
                                                                <i class="bi bi-plus"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="btn btn-flex-lep">
                                                        <a href="" class="btn-save-later" >Save for later</a> 
                                                        <a href="javascript:void(0)" class="btn-save-later" onclick="updateCartQuantityList('1', '{{$dt_cart['id']}}', '-1', 'delete')">Remove from cart</a> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <!-- <input type="button" name="previous" class="previous action-button-previous btn-add-cart2" value="Previous"/> -->
                            <!-- <a type="button" name="next" class="nextstep action-button btn-add-cart2">Next</a> -->
                        </fieldset>

                        
                        <fieldset >

                            <div class="col-lg-8 mb-3 mb-lg-0">
                                <div class="card h-100">
                                    <div class="card-body  px-sm-4">
                                        <div class="d-flex justify-content-center mb-30">
                                            <ul class="cart-step-list">
                                                <li class="done"><span><i class="bi bi-check2"></i></span> {{ translate('cart') }}</li>
                                                <li class="done"><span><i class="bi bi-check2"></i></span> {{ translate('Shipping_Details') }}</li>
                                                <li class="current"><span><i class="bi bi-check2"></i></span> {{ translate('payment') }}</li>
                                            </ul>
                                        </div>

                                        <h5 class="mb-4">{{ translate('Payment_Information') }}</h5>

                                        <div class="mb-30">
                                            <ul class="option-select-btn flex-wrap gap-3">
                                                @if(!$cod_not_show && $cash_on_delivery['status'])
                                                <li>
                                                    <form action="{{route('checkout-complete')}}" method="get">
                                                        <label>
                                                            <input type="hidden" name="payment_method" value="cash_on_delivery">
                                                            <button type="submit" class="payment-method d-flex border-0 align-iems-center gap-3">
                                                                <img width="32" src="{{ theme_asset('assets/img/icons/cash-on.png') }}" class="dark-support" alt="">
                                                                <span class="">{{ translate('Cash_on_Delivery') }}</span>
                                                            </button>
                                                        </label>
                                                    </form>
                                                </li>
                                                @endif

                                                <!--Digital payment start-->
                                                @if ($digital_payment['status']==1)
                                                        <li>
                                                            <label id="digital_payment_btn">
                                                                <input type="hidden">
                                                                <span class="payment-method d-flex align-iems-center gap-3">
                                                                <img width="30" src="{{ theme_asset('assets/img/icons/degital-payment.png') }}" class="dark-support" alt="">
                                                                <span class="">{{ translate('Digital_Payment') }}</span>
                                                            </span>
                                                            </label>
                                                        </li>

                                                    @if($wallet_status==1)
                                                    <li>
                                                        <label class="digital_payment d--none">
                                                            <button class="payment-method d-flex align-iems-center border-0 gap-3" type="submit" data-bs-toggle="modal" data-bs-target="#wallet_submit_button">
                                                                <img width="30" src="{{ theme_asset('assets/img/icons/wallet.png') }}" class="dark-support" alt="">
                                                                <span class="">{{ translate('wallet') }}</span>
                                                            </button>
                                                        </label>
                                                    </li>
                                                    @endif

                                                    @if(isset($offline_payment) && $offline_payment['status'])
                                                    <li>
                                                        <form action="{{route('offline-payment-checkout-complete')}}" method="get" class="digital_payment d--none">
                                                            <label>
                                                                <input type="hidden" name="weight" >
                                                                <span class="payment-method d-flex align-iems-center gap-3" data-bs-toggle="modal" data-bs-target="#offline_payment_submit_button">
                                                                    <img width="100" src="{{ theme_asset('assets/img/payment/pay-offline.png') }}" class="dark-support" alt="">
                                                                </span>
                                                            </label>
                                                        </form>
                                                    </li>
                                                    @endif

                                                    @if($ssl_commerz_payment['status'])
                                                    <li>
                                                        <form action="{{ url('/pay-ssl') }}" method="post" class="digital_payment d--none">
                                                            @csrf
                                                            <label>
                                                                <button class="payment-method border-0 d-flex align-iems-center gap-3">
                                                                    <img width="100" src="{{ theme_asset('assets/img/payment/sslcomz.png') }}" class="dark-support" alt="">
                                                                </button>
                                                            </label>
                                                        </form>
                                                    </li>
                                                    @endif

                                                    @if($paypal['status'])
                                                    <li>
                                                        <form action="{{route('pay-paypal')}}" method="post" id="payment-form" class="digital_payment d--none">
                                                            @csrf
                                                            <label>
                                                                <button class="payment-method border-0 d-flex align-iems-center gap-3">
                                                                    <img width="90" src="{{ theme_asset('assets/img/payment/paypal.png') }}" class="dark-support" alt="">
                                                                </button>
                                                            </label>
                                                        </form>
                                                    </li>
                                                    @endif

                                                    @if($stripe['status'])
                                                    <li>
                                                        <label class="digital_payment d--none">
                                                            <button class="payment-method border-0 d-flex align-iems-center gap-3" type="button" id="checkout-button">
                                                                <img width="70" src="{{ theme_asset('assets/img/payment/stripe.png') }}" class="dark-support" alt="">
                                                            </button>
                                                        </label>
                                                    </li>
                                                    @endif

                                                    @if(isset($inr) && isset($usd) && $razor_pay['status'])
                                                    <li>
                                                        <form action="{!! route('payment-razor') !!}" method="post" id="payment-form" class="digital_payment d--none">
                                                            @csrf
                                                            <script src="https://checkout.razorpay.com/v1/checkout.js"
                                                                data-key="{{ \Illuminate\Support\Facades\Config::get('razor.razor_key') }}"
                                                                data-amount="{{(round(\App\CPU\Convert::usdToinr($amount)))*100}}"
                                                                data-buttontext="Pay {{(\App\CPU\Convert::usdToinr($amount))*100}} INR"
                                                                data-name="{{\App\Model\BusinessSetting::where(['type'=>'company_name'])->first()->value}}"
                                                                data-description=""
                                                                data-image="{{asset('storage/app/public/company/'.\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)}}"
                                                                data-prefill.name="{{auth('customer')->user()->f_name}}"
                                                                data-prefill.email="{{auth('customer')->user()->email}}"
                                                                data-theme.color="#ff7529">
                                                            </script>
                                                            <label>
                                                                <button class="payment-method border-0 d-flex align-iems-center gap-3" type="button" onclick="$('.razorpay-payment-button').click()">
                                                                    <img width="100" src="{{ theme_asset('assets/img/payment/razor.png') }}" class="dark-support" alt="">
                                                                </button>
                                                            </label>
                                                        </form>
                                                    </li>
                                                    @endif

                                                    @if($paystack['status'])
                                                    <li>
                                                        <form method="POST" action="{{ route('paystack-pay') }}" accept-charset="UTF-8" role="form" class="digital_payment d--none">
                                                            @csrf
                                                            <input type="hidden" name="email"
                                                                value="{{auth('customer')->user()->email}}"> {{-- required --}}
                                                            <input type="hidden" name="orderID"
                                                                value="{{session('cart_group_id')}}">
                                                            <input type="hidden" name="amount"
                                                                value="{{\App\CPU\Convert::usdTozar($amount*100)}}"> {{-- required in kobo --}}
                                                            <input type="hidden" name="quantity" value="1">
                                                            <input type="hidden" name="currency"
                                                                value="{{\App\CPU\Helpers::currency_code()}}">
                                                            <input type="hidden" name="metadata"
                                                                value="{{ json_encode($array = ['key_name' => 'value',]) }}"> {{-- For other necessary things you want to add to your payload. it is optional though --}}
                                                            <input type="hidden" name="reference"
                                                                value="{{ Paystack::genTranxRef() }}"> {{-- required --}}

                                                            <label>
                                                                <button class="payment-method border-0 d-flex align-iems-center gap-3" type="submit">
                                                                    <img width="100" src="{{ theme_asset('assets/img/payment/paystack.png') }}" class="dark-support" alt="">
                                                                </button>
                                                            </label>
                                                        </form>
                                                    </li>
                                                    @endif

                                                    @if(isset($myr) && isset($usd) && $senang_pay['status'])
                                                            @php($user=auth('customer')->user())
                                                            @php($secretkey = $senang_pay['secret_key'])
                                                            @php($data = new \stdClass())
                                                            @php($data->merchantId = $senang_pay['merchant_id'])
                                                            @php($data->detail = 'payment')
                                                            @php($data->order_id = session('cart_group_id'))
                                                            @php($data->amount = \App\CPU\Convert::usdTomyr($amount))
                                                            @php($data->name = $user->f_name.' '.$user->l_name)
                                                            @php($data->email = $user->email)
                                                            @php($data->phone = $user->phone)
                                                            @php($data->hashed_string = md5($secretkey . urldecode($data->detail) . urldecode($data->amount) . urldecode($data->order_id)))
                                                        <li>
                                                            <form name="order" method="post" class="digital_payment d--none" action="https://{{env('APP_MODE')=='live'?'app.senangpay.my':'sandbox.senangpay.my'}}/payment/{{$senang_pay['merchant_id']}}">
                                                                <input type="hidden" name="detail" value="{{$data->detail}}">
                                                                <input type="hidden" name="amount" value="{{$data->amount}}">
                                                                <input type="hidden" name="order_id" value="{{$data->order_id}}">
                                                                <input type="hidden" name="name" value="{{$data->name}}">
                                                                <input type="hidden" name="email" value="{{$data->email}}">
                                                                <input type="hidden" name="phone" value="{{$data->phone}}">
                                                                <input type="hidden" name="hash" value="{{$data->hashed_string}}">

                                                                <label>
                                                                    <button class="payment-method border-0 d-flex align-iems-center gap-3" type="submit" id="checkout-button">
                                                                        <img width="100" src="{{ theme_asset('assets/img/payment/senangpay.png') }}" class="dark-support" alt="">
                                                                    </button>
                                                                </label>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    @if($paymob_accept['status'])
                                                        <li>
                                                            <form method="POST" id="payment-form-paymob" class="digital_payment d--none" action="{{route('paymob-credit')}}">
                                                                @csrf
                                                                <label>
                                                                    <button class="payment-method border-0 d-flex align-iems-center gap-3" type="submit" id="checkout-button">
                                                                        <img width="100" src="{{ theme_asset('assets/img/payment/paymob.png') }}" class="dark-support" alt="">
                                                                    </button>
                                                                </label>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    @if(isset($bkash)  && $bkash['status'])
                                                        <li>
                                                            <form method="POST" id="payment-form-paymob" action="{{route('paymob-credit')}}" class="digital_payment d--none">
                                                                @csrf
                                                                <label>
                                                                    <a class="payment-method border-0 d-flex align-iems-center gap-3" href="{{route('bkash-make-payment')}}">
                                                                        <img width="70" src="{{ theme_asset('assets/img/payment/bkash.png') }}" class="dark-support" alt="">
                                                                    </a>
                                                                </label>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    @if(isset($paytabs)  && $paytabs['status'])
                                                        <li>
                                                            <label class="digital_payment d--none">
                                                                <a class="payment-method border-0 d-flex align-iems-center gap-3" onclick="location.href='{{route('paytabs-payment')}}'">
                                                                    <img width="90" src="{{ theme_asset('assets/img/payment/paytabs.png') }}" class="dark-support" alt="">
                                                                </a>
                                                            </label>
                                                        </li>
                                                    @endif

                                                    @if(isset($mercadopago) && $mercadopago['status'])
                                                        <li>
                                                            <label class="digital_payment d--none">
                                                                <a class="payment-method border-0 d-flex align-iems-center gap-3" onclick="location.href='{{route('mercadopago.index')}}'">
                                                                    <img width="100" src="{{ theme_asset('assets/img/payment/MercadoPago_(Horizontal).svg') }}" class="dark-support" alt="">
                                                                </a>
                                                            </label>
                                                        </li>
                                                    @endif

                                                    @if(isset($flutterwave) && $flutterwave['status'])
                                                        <li>
                                                            <form method="POST" action="{{ route('flutterwave_pay') }}" class="digital_payment d--none">
                                                                @csrf
                                                                <label>
                                                                    <button type="submit" class="payment-method border-0 d-flex align-iems-center gap-3">
                                                                        <img width="100" src="{{ theme_asset('assets/img/payment/fluterwave.png') }}" class="dark-support" alt="">
                                                                    </button>
                                                                </label>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    @if(isset($paytm) && $paytm['status'])
                                                        <li>
                                                            <label class="digital_payment d--none">
                                                                <a class="payment-method border-0 d-flex align-iems-center gap-3" onclick="location.href='{{route('paytm-payment')}}'">
                                                                    <img width="100" src="{{ theme_asset('assets/img/payment/paytm.png') }}" class="dark-support" alt="">
                                                                </a>
                                                            </label>
                                                        </li>
                                                    @endif

                                                    @if(isset($liqpay) && $liqpay['status'])
                                                        <li>
                                                            <label class="digital_payment d--none">
                                                                <a class="payment-method border-0 d-flex align-iems-center gap-3" onclick="location.href='{{route('liqpay-payment')}}'">
                                                                    <img width="100" src="{{ theme_asset('assets/img/payment/liqpay4.png') }}" class="dark-support" alt="">
                                                                </a>
                                                            </label>
                                                        </li>
                                                    @endif

                                                @endif
                                                <!--Digital payment end-->
                                            </ul>



                                        <!--Modal payment start-->

                                        @if ($digital_payment['status']==1)
                                            @if($wallet_status==1)
                                                <!-- wallet modal -->
                                                <div class="modal fade" id="wallet_submit_button">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">{{\App\CPU\translate('wallet_payment')}}</h5>
                                                                <button type="button" class="btn-close outside" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            @php($customer_balance = auth('customer')->user()->wallet_balance)
                                                            @php($remain_balance = $customer_balance - $amount)
                                                            <form action="{{route('checkout-complete-wallet')}}" method="get" class="needs-validation">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-12">
                                                                            <label for="">{{\App\CPU\translate('your_current_balance')}}</label>
                                                                            <input class="form-control" type="text" value="{{\App\CPU\Helpers::currency_converter($customer_balance)}}" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-row">
                                                                        <div class="form-group col-12">
                                                                            <label for="">{{\App\CPU\translate('order_amount')}}</label>
                                                                            <input class="form-control" type="text" value="{{\App\CPU\Helpers::currency_converter($amount)}}" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-12">
                                                                            <label for="">{{\App\CPU\translate('remaining_balance')}}</label>
                                                                            <input class="form-control" type="text" value="{{\App\CPU\Helpers::currency_converter($remain_balance)}}" readonly>
                                                                            @if ($remain_balance<0)
                                                                                <label class="__color-crimson">{{\App\CPU\translate('you do not have sufficient balance for pay this order!!')}}</label>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="update_cart_button fs-16 btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('close')}}</button>
                                                                    <button type="submit" class="update_cart_button fs-16 btn btn-primary" {{$remain_balance>0? '':'disabled'}}>{{\App\CPU\translate('submit')}}</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- offline payment modal -->
                                            @if(isset($offline_payment) && $offline_payment['status'])
                                                <div class="modal fade" id="offline_payment_submit_button">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">{{\App\CPU\translate('offline_payment')}}</h5>
                                                                <button type="button" class="btn-close outside" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{route('offline-payment-checkout-complete')}}" method="post" class="needs-validation">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-12">
                                                                            <label for="">{{\App\CPU\translate('payment_by')}}</label>
                                                                            <input class="form-control" type="text" name="payment_by" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-12">
                                                                            <label for="">{{\App\CPU\translate('transaction_ID')}}</label>
                                                                            <input class="form-control" type="text" name="transaction_ref" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-12">
                                                                            <label for="">{{\App\CPU\translate('payment_note')}}</label>
                                                                            <textarea name="payment_note" id="" class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" value="offline_payment" name="payment_method">
                                                                    <button type="button" class="update_cart_button fs-16 btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('close')}}</button>
                                                                    <button type="submit" class="update_cart_button fs-16 btn btn-primary">{{\App\CPU\translate('submit')}}</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        <!--Modal payment end-->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4 border-0 border-bottom">
                                <div class="card-body ps-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="">
                                            <div class="form-check ps-0">
                                                <input class="design-new-radio-btn" type="radio" name="exampleRadios"
                                                    id="exampleRadiosUPI" value="option3" checked />
                                                <label class="form-check-label fs-20 fw-700" for="exampleRadiosUPI">
                                                    Google Pay UPI
                                                </label>
                                                <p class="address-edit mb-0 ps-3">Lorem Ipsum is simple</p>
                                            </div>
                                        </div>
                                        <div class=""></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-4 border-0 border-bottom">
                                <div class="card-body ps-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="">
                                            <div class="form-check ps-0">
                                                <input class="design-new-radio-btn" type="radio" name="exampleRadios"
                                                    id="exampleRadiosU" value="option4" />
                                                <label class="form-check-label fs-20 fw-700" for="exampleRadiosU">
                                                    UPI
                                                </label>
                                                <p class="address-edit mb-0 ps-3">Lorem Ipsum is simple</p>
                                            </div>
                                        </div>
                                        <div class=""></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-4 border-0 border-bottom">
                                <div class="card-body ps-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="">
                                            <div class="form-check ps-0">
                                                <input class="design-new-radio-btn" type="radio" name="exampleRadios"
                                                    id="exampleRadioscard" value="option5" />
                                                <label class="form-check-label fs-20 fw-700" for="exampleRadioscard">
                                                    Credit / Debit / ATM Card
                                                </label>
                                                <p class="address-edit mb-0 ps-3">Add and secure cards as per RBI guidelines</p>
                                            </div>
                                        </div>
                                        <div class=""></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-4 border-0">
                                <div class="card-body ps-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="">
                                            <div class="form-check ps-0">
                                                <input class="design-new-radio-btn" type="radio" name="exampleRadios"
                                                    id="exampleRadiocash" value="option6" />
                                                <label class="form-check-label fs-20 fw-700" for="exampleRadiocash">
                                                    Cash on Delivery
                                                </label>
                                                <p class="address-edit mb-0 ps-3">Lorem Ipsum is simples</p>
                                            </div>
                                        </div>
                                        <div class=""></div>
                                    </div>
                                </div>
                            </div>
                            <!-- <input type="button" name="previous" class="previous action-button-previous btn-add-cart2" value="Previous"/> -->
                            <a class="submitstep action-button btn-add-cart2" value="" id="Continue">
                                Continue
                            </a>

                            <div class="div-test-images">
                                <!-- Button trigger modal -->

                                <!-- Modal -->
                                <div class="modal fade" id="examplesuccessFull" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 bg-transparent">
                                            <div class="modal-body">
                                                <div class="modal-money">
                                                    <div class="modal-data1">
                                                        <div class="money-img">
                                                            <img src="images/Tick Square.png" />
                                                            <h3>Order successfully</h3>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                                industry</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        </div>
                    </form>
                </div>
                    <div class="col-md-4">
                        @php($coupon_dis = 0)
                        @php($current_url=request()->segment(count(request()->segments())))
                        @php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
                        @php($product_price_total=0)
                        @php($total_tax=0)
                        @php($total_shipping_cost=0)
                        @php($order_wise_shipping_discount=\App\CPU\CartManager::order_wise_shipping_discount())
                        @php($total_discount_on_product=0)
                        @php($cart=\App\CPU\CartManager::get_cart())
                        @if(request('group_id'))
                            @php($cart = \App\Model\Cart::where(['cart_group_id' => request('group_id')])->get())
                        @endif
                        @php($cart_group_ids=\App\CPU\CartManager::get_cart_group_ids())
                        @php($shipping_cost=\App\CPU\CartManager::get_shipping_cost(request('group_id')))
                        @if($cart->count() > 0)
                            @foreach($cart as $key => $cartItem)
                                @php($product_price_total+=$cartItem['price']*$cartItem['quantity'])
                                @php($total_tax+=$cartItem['tax_model']=='exclude' ? ($cartItem['tax']*$cartItem['quantity']):0)
                                @php($total_discount_on_product+=$cartItem['discount']*$cartItem['quantity'])
                            @endforeach

                            @php($total_shipping_cost=$shipping_cost)
                        @else
                            <span>{{ translate('empty_cart') }}</span>
                        @endif
                        <div class="card bg-light-gery border-0 br-12 colorchnage sticky-top">
                            <div class="card-body">
                                <h5 class="mb-0 fw-600">Price Detail</h5>

                               <hr>

                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-muted">Price ({{$cart->count()}} items)</p>
                                    <p class="black-text fw-700">{{\App\CPU\Helpers::currency_converter($product_price_total - $total_discount_on_product)}}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-muted">Delivery fee</p>
                                    <p class="black-text fw-700">{{\App\CPU\Helpers::currency_converter($total_shipping_cost)}}</p>
                                </div>
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <div>{{ translate('tax') }}</div>
                                    <div>{{\App\CPU\Helpers::currency_converter($total_tax)}}</div>
                                </div>
                                <!-- <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-muted mb-0">{{ translate('Product_Discount') }}</p>
                                    <p class="black-text fw-700 mb-0">{{\App\CPU\Helpers::currency_converter($total_discount_on_product)}}</p>
                                </div> -->
                                @if(session()->has('coupon_discount'))
                                    @php($coupon_discount = session()->has('coupon_discount')?session('coupon_discount'):0)
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                        <div>{{ translate('coupon_discount') }}</div>
                                        <div class="text-primary">- {{\App\CPU\Helpers::currency_converter($coupon_discount+$order_wise_shipping_discount)}}</div>
                                    </div>
                                    @php($coupon_dis=session('coupon_discount'))
                                @endif
                                <hr>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="fw-600">Total Amount</p>
                                    <p class="fw-700 text-light-green">{{\App\CPU\Helpers::currency_converter($product_price_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}</p>
                                </div>

                                <div class="d-flex  justify-content-between align-items-center gap-2 mt-4">
                                    <a href="{{ route('home') }}" class="btn-save-later"><i class="bi bi-chevron-double-left fs-10"></i> {{ translate('Continue_Shopping') }}</a>
                                    @if(!Request::is('checkout-payment'))
                                        <a {{ Request::is('shop-cart')?"onclick=checkout()":"" }} {{ Request::is('checkout-details')?"onclick=proceed_to_next()":"" }} {{ in_array($current_url, ["updateQuantity", "remove"]) ? "onclick=checkout()":"" }} class="btn btn-primary">{{ translate('Proceed_to_Next') }}</a>
                                    @endif
                                </div>
                                <!-- <a href="cart-check-out.php" class="proceed-to-buy">Proceed to buy</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </section>

        {{-- <div class="container d-none">
            <h4 class="text-center mb-3">{{ translate('Shipping_Details') }}</h4>

            <div class="row">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-body  px-sm-4">
                            <div class="d-flex justify-content-center mb-30">
                                <ul class="cart-step-list">
                                    <li class="done"><span><i class="bi bi-check2"></i></span> {{ translate('cart') }}</li>
                                    <li class="current"><span><i class="bi bi-check2"></i></span> {{ translate('Shipping_Details') }}</li>
                                    <li><span><i class="bi bi-check2"></i></span> {{ translate('payment') }}</li>
                                </ul>
                            </div>
                            <input type="hidden" id="physical_product" name="physical_product" value="{{ $physical_product_view ? 'yes':'no'}}">
                            <input type="hidden" id="billing_input_enable" name="billing_input_enable" value="{{ $billing_input_by_customer }}">

                            @if($physical_product_view)
                                <form method="post" id="address-form">
                                    <h5 class="mb-3">{{ translate('Delivery_Information_Details') }}</h5>

                                    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                                        <div class="d-flex flex-wrap gap-3 align-items-center">
                                            <h6>{{ translate('Delivery_Address') }}</h6>
                                        </div>
                                        <div class="d-flex flex-wrap gap-3 align-items-center">
                                            <a href="javascript:" type="button" data-bs-toggle="modal" data-bs-target="#shippingMapModal" class="btn-link text-primary">{{ translate('Set_Form_Map') }} <i class="bi bi-geo-alt-fill"></i></a>
                                            <a href="javascript:" type="button" data-bs-toggle="modal" data-bs-target="#shippingSavedAddressModal" class="btn-link text-primary">{{ translate('Select_From_Saved') }}</a>
                                            <!-- shipping map modal -->
                                            <div class="modal fade" id="shippingMapModal" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="product-quickview">
                                                                <button type="button" class="btn-close outside" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                <input id="pac-input" class="controls rounded __inline-46" title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}"/>
                                                                <div class="dark-support rounded w-100 __h-14rem" id="location_map_canvas"></div>
                                                                <input type="hidden" id="latitude"
                                                                       name="latitude" class="form-control d-inline"
                                                                       placeholder="Ex : -94.22213" value="{{$default_location?$default_location['lat']:0}}" required readonly>
                                                                <input type="hidden"
                                                                       name="longitude" class="form-control"
                                                                       placeholder="Ex : 103.344322" id="longitude" value="{{$default_location?$default_location['lng']:0}}" required >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal fade" id="shippingSavedAddressModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered justify-content-center">
                                            <div class="modal-content border-0">
                                                <div class="modal-header">
                                                    <h5 class="" id="contact_sellerModalLabel">{{translate('Saved Addresses')}}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body custom-scrollbar">
                                                    <div class="product-quickview">
                                                        <div class="shipping-saved-addresses {{ $shipping_addresses->count()<1 ? 'd--none':'' }}">
                                                            <div class="row gy-3 text-dark py-4">
                                                                @foreach($shipping_addresses as $key=>$address)
                                                                    <div class="col-md-12">
                                                                        <div class="card border-0">
                                                                            <div class="card-header bg-transparent gap-2 align-items-center d-flex flex-wrap justify-content-between">
                                                                                <label class="d-flex align-items-center gap-3 cursor-pointer mb-0">
                                                                                    <input type="radio" name="shipping_method_id" value="{{$address['id']}}" {{$key==0?'checked':''}}>
                                                                                    <h6>{{$address['address_type']}}</h6>
                                                                                </label>
                                                                                <div class="d-flex align-items-center gap-3">
                                                                                    <button type="button" onclick="location.href='{{ route('address-edit', ['id' => $address->id]) }}'" class="p-0 bg-transparent border-0">
                                                                                        <img src="{{ theme_asset('assets/img/svg/location-edit.svg') }}" alt="" class="svg">
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <address>
                                                                                    <dl class="mb-0 flexible-grid sm-down-1" style="--width: 5rem">
                                                                                        <dt>{{ translate('name') }}</dt>
                                                                                        <dd class="shipping-contact-person">{{$address['contact_person_name']}}</dd>

                                                                                        <dt>{{ translate('phone') }}</dt>
                                                                                        <dd class="shipping-contact-phone"><a href="tel:{{$address['phone']}}" class="text-dark">{{$address['phone']}}</a></dd>

                                                                                        <dt>{{ translate('address') }}</dt>
                                                                                        <dd>{{$address['address']}}, {{$address['city']}}, {{$address['zip']}}</dd>
                                                                                        <span class="shipping-contact-address d-none">{{ $address['address'] }}</span>
                                                                                        <span class="shipping-contact-city d-none">{{ $address['city'] }}</span>
                                                                                        <span class="shipping-contact-zip d-none">{{ $address['zip'] }}</span>
                                                                                        <span class="shipping-contact-country d-none">{{ $address['country'] }}</span>
                                                                                        <span class="shipping-contact-address_type d-none">{{ $address['address_type'] }}</span>
                                                                                    </dl>
                                                                                </address>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ translate('close') }}</button>
                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ translate('save') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body" id="collapseThree">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row mb-30">
                                                        <div class="col-sm-6">
                                                            <div class="form-group mb-3">
                                                                <label for="name">{{ translate('Contact_Person_Name')}}</label>
                                                                <input type="text" name="contact_person_name" id="name" class="form-control" placeholder="Ex: Jhon Doe" {{$shipping_addresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group mb-3">
                                                                <label for="phone">{{ translate('phone') }}</label>
                                                                <input type="tel" name="phone" id="phone" class="form-control" placeholder="Ex: +88 01000000000" {{$shipping_addresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group mb-3">
                                                                <label for="address_type">{{ translate('address')}} {{translate('Type')}}</label>
                                                                <select name="address_type" id="address_type" class="form-select">
                                                                    <option value="permanent">{{ translate('Permanent')}}</option>
                                                                    <option value="home">{{ translate('Home')}}</option>
                                                                    <option value="others">{{ translate('Others')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group mb-3">
                                                                <label for="country">{{ translate('Country') }}</label>
                                                                <select name="country" id="country" class="form-control select_picker select2">
                                                                    @forelse($countries as $country)
                                                                        <option value="{{ $country['name'] }}" data-id="{{ $country['id'] }}">{{ $country['name'] }}</option>
                                                                    @empty
                                                                        <option value="">{{ \App\CPU\translate('No_country_to_deliver') }}</option>
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group mb-3">
                                                                <label for="city">{{ translate('city') }}</label>
                                                                <input type="tel" name="city" id="city" placeholder="Ex: Dhaka" class="form-control"  {{$shipping_addresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group mb-3">
                                                                <label for="city">{{ translate('Zip_Code') }}</label>
                                                                @if($zip_restrict_status == 1)
                                                                    <select name="zip" id="zip" class="form-control select2 select_picker" data-live-search="true" required>
                                                                        @forelse($zip_codes as $code)
                                                                            <option value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                                        @empty
                                                                            <option value="">{{ \App\CPU\translate('No_zip_to_deliver') }}</option>
                                                                        @endforelse
                                                                    </select>
                                                                @else
                                                                    <input type="text" class="form-control" id="zip" name="zip" placeholder="Ex: 1216" {{$shipping_addresses->count()==0?'required':''}}>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group mb-3">
                                                                <label for="address">{{ translate('address') }}</label>
                                                                <div class="form-control focus-border rounded d-flex align-items-center">
                                                                    <input type="text" name="address" id="address" class="flex-grow-1 text-dark bg-transparent border-0 focus-input" placeholder="{{ translate('your_address') }}" {{$shipping_addresses->count()==0?'required':''}}>

                                                                    <div class="border-start ps-3 pe-1" data-bs-toggle="modal" data-bs-target="#shippingMapModal">
                                                                        <i class="bi bi-compass-fill"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <label class="custom-checkbox align-items-center" id="save_address_label">
                                                                <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="0">
                                                                <input type="checkbox" name="save_address" id="save_address">
                                                                {{ translate('Save_this_Address') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            @if($billing_input_by_customer)
                            <div class="mt-5 {{ $billing_input_by_customer ? '':'d-none' }}">
                                <div class="bg-light rounded p-3 mt-20">
                                    <div class="d-flex flex-wrap justify-content-between gap-3">
                                        <div class="d-flex gap-3 align-items-center">
                                            <h6>{{ translate('Billing_Address') }}</h6>
                                        </div>

                                        @if($physical_product_view)
                                            <label class="custom-checkbox">
                                                {{ translate('Same_as_Delivery_Address') }}
                                                <input type="checkbox" id="same_as_shipping_address" onclick="hide_billingAddress()"
                                                       name="same_as_shipping_address" class="billing-address-checkbox" checked>
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                <form method="post" id="billing-address-form">
                                    <div class="toggle-billing-address mt-3" id="hide_billing_address">
                                        <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                            </div>
                                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                                <a href="javascript:" data-bs-toggle="modal" data-bs-target="#billingMapModal" class="btn-link text-primary">{{ translate('Set_Form_Map') }} <i class="bi bi-geo-alt-fill"></i></a>
                                                <!-- billing map modal -->
                                                <div class="modal fade" id="billingMapModal" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <div class="product-quickview">
                                                                    <button type="button" class="btn-close outside" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    <input id="pac-input-billing" class="controls rounded __inline-46" title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}"/>
                                                                    <div class="dark-support rounded w-100 __h-14rem" id="billing_location_map_canvas"></div>
                                                                    <input type="hidden" id="billing_latitude"
                                                                           name="billing_latitude" class="form-control d-inline"
                                                                           placeholder="Ex : -94.22213" value="{{$default_location?$default_location['lat']:0}}" required readonly>
                                                                    <input type="hidden"
                                                                           name="billing_longitude" class="form-control"
                                                                           placeholder="Ex : 103.344322" id="billing_longitude" value="{{$default_location?$default_location['lng']:0}}" required >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="javascript:" type="button" data-bs-toggle="modal" data-bs-target="#billingSavedAddressModal" class="btn-link text-primary">{{ translate('Select_From_Saved') }}</a>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="billingSavedAddressModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered justify-content-center">
                                                <div class="modal-content border-0" style="max-width: 500px;">
                                                    <div class="modal-header">
                                                        <h5 class="" id="contact_sellerModalLabel">{{translate('Saved_Addresses')}}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body custom-scrollbar">
                                                        <div class="product-quickview">
                                                            <div class="billing-saved-addresses {{ $billing_addresses->count()<1 ? 'd--none':'' }}">
                                                                <div class="row gy-3 text-dark py-4">
                                                                    @foreach($billing_addresses as $key=>$address)
                                                                        <div class="col-md-12">
                                                                            <div class="card border-0 ">
                                                                                <div class="card-header bg-transparent gap-2 align-items-center d-flex flex-wrap justify-content-between">
                                                                                    <label class="d-flex align-items-center gap-3 cursor-pointer mb-0">
                                                                                        <input type="radio" value="{{$address['id']}}" name="billing_method_id" {{$key==0?'checked':''}}>
                                                                                        <h6>{{$address['address_type']}}</h6>
                                                                                    </label>
                                                                                    <div class="d-flex align-items-center gap-3">
                                                                                        <button type="button" onclick="location.href='{{ route('address-edit', ['id' => $address->id]) }}'" class="p-0 bg-transparent border-0">
                                                                                            <img src="{{ theme_asset('assets/img/svg/location-edit.svg') }}" alt="" class="svg">
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card-body pb-0">
                                                                                    <address>
                                                                                        <dl class="mb-0 flexible-grid sm-down-1" style="--width: 5rem">
                                                                                            <dt>{{ translate('name') }}</dt>
                                                                                            <dd class="billing-contact-name">{{$address['contact_person_name']}}</dd>

                                                                                            <dt>{{ translate('phone') }}</dt>
                                                                                            <dd class="billing-contact-phone"><a href="tel:{{$address['phone']}}" class="text-dark">{{$address['phone']}}</a></dd>

                                                                                            <dt>{{ translate('address') }}</dt>
                                                                                            <dd>{{$address['address']}}, {{$address['city']}}, {{$address['zip']}}</dd>
                                                                                            <span class="billing-contact-address d-none">{{ $address['address'] }}</span>
                                                                                            <span class="billing-contact-city d-none">{{ $address['city'] }}</span>
                                                                                            <span class="billing-contact-zip d-none">{{ $address['zip'] }}</span>
                                                                                            <span class="billing-contact-country d-none">{{ $address['country'] }}</span>
                                                                                            <span class="billing-contact-address_type d-none">{{ $address['address_type'] }}</span>
                                                                                        </dl>
                                                                                    </address>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ translate('close') }}</button>
                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ translate('save') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row mb-30">
                                                            <div class="col-sm-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="billing_contact_person_name">{{ translate('Contact_Person_Name')}}</label>
                                                                    <input type="text" name="billing_contact_person_name" id="billing_contact_person_name" class="form-control" placeholder="Ex: Jhon Doe" {{$billing_addresses->count()==0?'required':''}}>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="billing_phone">{{ translate('phone') }}</label>
                                                                    <input type="tel" name="billing_phone" id="billing_phone" class="form-control" placeholder="Ex: +88 01000000000" {{$billing_addresses->count()==0?'required':''}}>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="billing_address_type">{{ translate('address')}} {{translate('Type')}}</label>
                                                                    <select name="billing_address_type" id="billing_address_type" class="form-select">
                                                                        <option value="permanent">{{ translate('Permanent')}}</option>
                                                                        <option value="home">{{ translate('Home')}}</option>
                                                                        <option value="others">{{ translate('Others')}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="billing_country">{{ translate('Country') }}</label>
                                                                    <select name="billing_country" id="billing_country" class="form-control select_picker select2">
                                                                        @forelse($countries as $country)
                                                                            <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                                                        @empty
                                                                            <option value="">{{ translate('No_country_to_deliver') }}</option>
                                                                        @endforelse
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="billing_city">{{ translate('city') }}</label>
                                                                    <input type="text" name="billing_city" id="billing_city" placeholder="Ex: Dhaka" class="form-control"  {{$billing_addresses->count()==0?'required':''}}>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="billing_zip">{{ translate('Zip_Code') }}</label>
                                                                    @if($zip_restrict_status == 1)
                                                                        <select name="billing_zip" id="billing_zip" class="form-control select2 select_picker" data-live-search="true" required>
                                                                            @forelse($zip_codes as $code)
                                                                                <option value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                                            @empty
                                                                                <option value="">{{ translate('No_zip_to_deliver') }}</option>
                                                                            @endforelse
                                                                        </select>
                                                                    @else
                                                                        <input type="text" class="form-control" id="billing_zip" name="billing_zip" placeholder="Ex: 1216" {{$billing_addresses->count()==0?'required':''}}>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group mb-3">
                                                                    <label for="billing_address">{{ translate('address') }}</label>
                                                                    <div class="form-control focus-border rounded d-flex align-items-center">
                                                                        <input type="text" name="billing_address" id="billing_address" class="flex-grow-1 text-dark bg-transparent border-0 focus-input" placeholder="{{ translate('your_address') }}" {{$shipping_addresses->count()==0?'required':''}}>

                                                                        <div class="border-start ps-3 pe-1" data-bs-toggle="modal" data-bs-target="#billingMapModal">
                                                                            <i class="bi bi-compass-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12">
                                                                <label class="custom-checkbox save-billing-address" id="save-billing-address-label">
                                                                    <input type="hidden" name="billing_method_id" id="billing_method_id" value="0">
                                                                    <input type="checkbox" name="save_address_billing" id="save_address_billing">
                                                                    {{ translate('Save_this_Address') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order summery Content -->
                @include('theme-views.partials._order-summery')

            </div>
        </div> --}}
    </main>
    <!-- End Main Content -->
@endsection
@push('script')
    <script src="{{ theme_asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/jquery-1-2.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('.select_picker').select2();

            if($('[name="shipping_method_id"]').prop('checked')){
                let cardBody = $('[name="shipping_method_id"]:checked').parents('.card-header').siblings('.card-body')
                shipping_method_select(cardBody);
            }

            if($('[name="billing_method_id"]').prop('checked')){
                let cardBody = $('[name="billing_method_id"]:checked').parents('.card-header').siblings('.card-body')
                billing_method_select(cardBody);
            }
        });

        /*
        * shipping address saved form to general form start
        */
        $('[name="shipping_method_id"]').on('change', function(){
            let cardBody = $(this).parents('.card-body')

            console.log('cardBody ' ,cardBody)

            shipping_method_select(cardBody);
        })

        function shipping_method_select(cardBody){
            let shipping_method_id = $('[name="shipping_method_id"]:checked').val();
            let shipping_person = cardBody.find('.shipping-contact-person').text();
            let shipping_phone = cardBody.find('.shipping-contact-phone').text();
            let shipping_address = cardBody.find('.shipping-contact-address').text();
            let shipping_city = cardBody.find('.shipping-contact-city').text();
            let shipping_zip = cardBody.find('.shipping-contact-zip').text();
            let shipping_country = cardBody.find('.shipping-contact-country').text();
            let shipping_contact_address_type = cardBody.find('.shipping-contact-address_type').text();
            let update_address = `
                <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="${shipping_method_id}">
                <input type="checkbox" name="update_address" id="update_address">
                {{ translate('Update_this_Address') }}
            `;

            $('#name').val(shipping_person);
            $('#phone').val(shipping_phone);
            $('#address').val(shipping_address);
            $('#city').val(shipping_city);
            $('#zip').val(shipping_zip);
            $('#select2-zip-container').text(shipping_zip);
            $('#country').val(shipping_country);
            $('#select2-country-container').text(shipping_country);
            $('#address_type').val(shipping_contact_address_type);
            $('#save_address_label').html(update_address);


        }
        /*
        * shipping address saved form to general form end
        */

        /*
        * billing address saved form to general form start
        */
        $('[name="billing_method_id"]').on('change', function(){
            let cardBody = $(this).parents('.card-header').siblings('.card-body')
            billing_method_select(cardBody);
        })

        function billing_method_select(cardBody){
            let billing_method_id = $('[name="billing_method_id"]:checked').val();
            let billing_person = cardBody.find('.billing-contact-name').text();
            let billing_phone = cardBody.find('.billing-contact-phone').text();
            let billing_address = cardBody.find('.billing-contact-address').text();
            let billing_city = cardBody.find('.billing-contact-city').text();
            let billing_zip = cardBody.find('.billing-contact-zip').text();
            let billing_country = cardBody.find('.billing-contact-country').text();
            let billing_contact_address_type = cardBody.find('.billing-contact-address_type').text();
            let update_address_billing = `
                <input type="hidden" name="billing_method_id" id="billing_method_id" value="${billing_method_id}">
                <input type="checkbox" name="update_billing_address" id="update_billing_address">
                {{ translate('Update_this_Address') }}
            `;

            $('#billing_contact_person_name').val(billing_person);
            $('#billing_phone').val(billing_phone);
            $('#billing_address').val(billing_address);
            $('#billing_city').val(billing_city);
            $('#billing_zip').val(billing_zip);
            $('#select2-billing_zip-container').text(billing_zip);
            $('#billing_country').val(billing_country);
            $('#select2-billing_country-container').text(billing_country);
            $('#billing_address_type').val(billing_contact_address_type);
            $('#save-billing-address-label').html(update_address_billing);
        }
        /*
        * billing address saved form to general form end
        */

        function hide_billingAddress() {
            let check_same_as_shippping = $('#same_as_shipping_address').is(":checked");
            if (check_same_as_shippping) {
                $('#hide_billing_address').slideUp();
            } else {
                $('#hide_billing_address').slideDown();
            }
        }
   

        function initAutocomplete() {
            let myLatLng = {
                lat: '',// {{$default_location?$default_location['lat']:'-33.8688'}},
                lng: '',// {{$default_location?$default_location['lng']:'151.2195'}}
            };

            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: {
                    lat: '', // {{$default_location?$default_location['lat']:'-33.8688'}},
                    lng: '' // {{$default_location?$default_location['lng']:'151.2195'}}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            let marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap( map );
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinate = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinate);
                var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
                marker.setPosition( latlng );
                map.panTo( latlng );

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').value = results[1].formatted_address;
                            console.log(results[1].formatted_address);
                        }
                    }
                });
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");

            const searchBox = new google.maps.places.SearchBox(input);

            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });

                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };

        $(document).on('ready', function () {
            // initAutocomplete();
        });
        $(document).on("keydown", "input", function(e) {
            if (e.which==13) e.preventDefault();
        });
    
        function billingMap() {
            let myLatLng = {
                lat: '', // {{$default_location?$default_location['lat']:'-33.8688'}},
                lng: '' // {{$default_location?$default_location['lng']:'151.2195'}}
            };
            const map = new google.maps.Map(document.getElementById("billing_location_map_canvas"), {
                center: { 
                    lat: '', // {{$default_location?$default_location['lat']:'-33.8688'}},
                    lng: '' // {{$default_location?$default_location['lng']:'151.2195'}}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            let marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap( map );
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinate = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinate);
                var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
                marker.setPosition( latlng );
                map.panTo( latlng );

                document.getElementById('billing_latitude').value = coordinates['lat'];
                document.getElementById('billing_longitude').value = coordinates['lng'];

                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('billing_address').value = results[1].formatted_address;
                            console.log(results[1].formatted_address);
                        }
                    }
                });
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input-billing");

            const searchBox = new google.maps.places.SearchBox(input);

            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });

                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('billing_latitude').value = this.position.lat();
                        document.getElementById('billing_longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on('ready', function () {
            billingMap();
        });
        $(document).on("keydown", "input", function(e) {
            if (e.which==13) e.preventDefault();
        });

        function mapsShopping(){
            // initAutocomplete();
            // billingMap();
        }

        $(document).on('change','select[name=country]', function() {
            let id = $('select[name=country] option:selected').data('id')
            $.ajax({
                type: "POST",
                url: "{{ route('seller.state.list') }}",
                data: {
                    country_id:id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
                },
                dataType: "json",
                success: function (response) {
                    console.log('response ', response)
                    if(response.status) {
                        let html = "<option value=''>State</option>"
                        $.each(response.data, function(ind,elm) {
                            html += `<option value="${elm.name}" data-id="${elm.id}">${elm.name}</option>`
                        })
                        $('select[name=state]').html(html)
                    }
                }
            });
        })

        $(document).on('change','select[name=state]', function() {
            let id = $('select[name=state] option:selected').data('id')
            $.ajax({
                type: "POST",
                url: "{{ route('seller.city.list') }}",
                data: {
                    state_id:id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
                },
                dataType: "json",
                success: function (response) {
                    console.log('response ', response)
                    if(response.status) {
                        let html = "<option value=''>City</option>"
                        $.each(response.data, function(ind,elm) {
                            html += `<option value="${elm.name}">${elm.name}</option>`
                        })
                        $('select[name=city]').html(html)
                    }
                }
            });
        })

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&callback=mapsShopping&libraries=places&v=3.49" defer></script>

    <script>
        function proceed_to_next() {
            let physical_product = $('#physical_product').val();

            if(physical_product === 'yes') {
                var billing_addresss_same_shipping = $('#same_as_shipping_address').is(":checked");

                let allAreFilled = true;
                document.getElementById("address-form").querySelectorAll("[required]").forEach(function (i) {
                    if (!allAreFilled) return;
                    if (!i.value) allAreFilled = false;
                    if (i.type === "radio") {
                        let radioValueCheck = false;
                        document.getElementById("address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                            if (r.checked) radioValueCheck = true;
                        });
                        allAreFilled = radioValueCheck;
                    }
                });

                //billing address saved
                let allAreFilled_shipping = true;

                if (billing_addresss_same_shipping != true && $('#billing_input_enable').val() == 1) {

                    document.getElementById("billing-address-form").querySelectorAll("[required]").forEach(function (i) {
                        if (!allAreFilled_shipping) return;
                        if (!i.value) allAreFilled_shipping = false;
                        if (i.type === "radio") {
                            let radioValueCheck = false;
                            document.getElementById("billing-address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                                if (r.checked) radioValueCheck = true;
                            });
                            allAreFilled_shipping = radioValueCheck;
                        }
                    });
                }
            }else {
                var billing_addresss_same_shipping = false;
            }

            let selected_address_id = $('input[name=shipping_method_id]:checked').val()

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
                },
            });
            $.post({
                url: "{{route('customer.choose-shipping-address-other')}}?group_id=" + "{{ request('group_id') }}",
                data: {
                    physical_product: physical_product,
                    shipping: physical_product === 'yes' ? $('#address-form').serialize() : "",
                    billing: $('#billing-address-form').serialize(),
                    billing_addresss_same_shipping: true, // billing_addresss_same_shipping,
                    selected_address_id:selected_address_id
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').addClass('d-grid');
                },
                success: function (data) {
                    console.log('here is my data', data)
                    if (data.errors) {
                        swal.fire(`${data.errors}`, '','error')
                        // for (var i = 0; i < data.errors.length; i++) {
                            // toastr.error(data.errors, {
                            //     CloseButton: true,
                            //     ProgressBar: true
                            // });
                        // }
                    } else {
                        location.href = "{{route('checkout-payment')}}?group_id=" + "{{ request('group_id') }}";
                    }
                },
                complete: function () {
                    $('#loading').removeClass('d-grid');
                },
                error: function (data) {
                    let error_msg = data.responseJSON.errors;
                    swal.fire(`${error_msg}`, '','error')
                }
            });


        }
    </script>



<script type="text/javascript">
    // Create an instance of the Stripe object with your publishable API key
    var stripe = Stripe('{{$stripe['published_key']}}');
    var checkoutButton = document.getElementById("checkout-button");
    checkoutButton.addEventListener("click", function () {
        fetch("{{route('pay-stripe')}}", {
            method: "GET",
        }).then(function (response) {
            console.log(response)
            return response.text();
        }).then(function (session) {
            /*console.log(JSON.parse(session).id)*/
            return stripe.redirectToCheckout({sessionId: JSON.parse(session).id});
        }).then(function (result) {
            if (result.error) {
                alert(result.error.message);
            }
        }).catch(function (error) {
            console.error("{{\App\CPU\translate('Error')}}:", error);
        });
    });
</script>

<script>
    setTimeout(function () {
        $('.stripe-button-el').hide();
        $('.razorpay-payment-button').hide();
    }, 10)
</script>

<script type="text/javascript">
    function click_if_alone() {
        let total = $('.checkout_details .click-if-alone').length;
        if (Number.parseInt(total) < 2) {
            $('.click-if-alone').click()
            $('.checkout_details').html('<h1>{{\App\CPU\translate('Redirecting_to_the_payment')}}......</h1>');
        }
    }
    click_if_alone();

    $('#digital_payment_btn').on('click', function (){
        $('.digital_payment').slideToggle('slow');
        // $(this).toggleClass('arrow-up');
    });
</script>

@endpush
