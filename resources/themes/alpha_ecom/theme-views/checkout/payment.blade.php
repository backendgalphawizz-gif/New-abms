@extends('theme-views.layouts.app')

@section('title', translate('Payment_Details').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))
@push('css_or_js')
    <style>
        .stripe-button-el {
            display: none !important;
        }

        .razorpay-payment-button {
            display: none !important;
        }
      ul.option-select-btn li {
    width: 23% !important;
}





    </style>

    {{--stripe--}}
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
    {{--stripe--}}
@endpush

@section('content')
    <!-- Main Content -->

    <main class="main-content d-flex flex-column gap-3 py-3 mb-5 payment-page">
        <div class="container">
            <!-- <h4 class="text-center mb-3">{{ translate('Payment_Details') }}</h4> -->

            <div class="row">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-body  px-sm-4">
                            <div class="d-flex justify-content-center mb-30 d-none">
                                <ul class="cart-step-list">
                                    <li class="done"><span><i class="bi bi-check2"></i></span> {{ translate('cart') }}</li>
                                    <li class="done"><span><i class="bi bi-check2"></i></span> {{ translate('Shipping_Details') }}</li>
                                    <li class="current"><span><i class="bi bi-check2"></i></span> {{ translate('payment') }}</li>
                                </ul>
                            </div>

                            <h5 class="mb-4"><b>{{ translate('Payment_Information') }}</b></h5>

                            <div class="mb-30">
                                <ul class="option-select-btn flex-wrap gap-3">
                                    @if(!$cod_not_show && $cash_on_delivery['status'])
                                    <li>
                                        <form action="{{route('checkout-complete')}}" method="get">
                                            <label>
                                                <input type="hidden" name="payment_method" value="cash_on_delivery">
                                                <input type="hidden" name="group_id" value="{{request('group_id')}}">
                                                <button type="submit" class="payment-method text-center border-0 align-iems-center gap-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6 8.5C5.46957 8.5 4.96086 8.71071 4.58579 9.08579C4.21071 9.46086 4 9.96957 4 10.5V25.5C4 26.0304 4.21071 26.5391 4.58579 26.9142C4.96086 27.2893 5.46957 27.5 6 27.5H30C30.5304 27.5 31.0391 27.2893 31.4142 26.9142C31.7893 26.5391 32 26.0304 32 25.5V10.5C32 9.96957 31.7893 9.46086 31.4142 9.08579C31.0391 8.71071 30.5304 8.5 30 8.5H6ZM3.17157 7.67157C3.92172 6.92143 4.93913 6.5 6 6.5H30C31.0609 6.5 32.0783 6.92143 32.8284 7.67157C33.5786 8.42172 34 9.43913 34 10.5V25.5C34 26.5609 33.5786 27.5783 32.8284 28.3284C32.0783 29.0786 31.0609 29.5 30 29.5H6C4.93913 29.5 3.92172 29.0786 3.17157 28.3284C2.42143 27.5783 2 26.5609 2 25.5V10.5C2 9.43913 2.42143 8.42172 3.17157 7.67157Z" fill="#0a9494"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M18 14.5C16.067 14.5 14.5 16.067 14.5 18C14.5 19.933 16.067 21.5 18 21.5C19.933 21.5 21.5 19.933 21.5 18C21.5 16.067 19.933 14.5 18 14.5ZM12.5 18C12.5 14.9624 14.9624 12.5 18 12.5C21.0376 12.5 23.5 14.9624 23.5 18C23.5 21.0376 21.0376 23.5 18 23.5C14.9624 23.5 12.5 21.0376 12.5 18Z" fill="#0a9494"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9 6.5C9.55229 6.5 10 6.94772 10 7.5C10 9.35652 9.2625 11.137 7.94975 12.4497C6.63699 13.7625 4.85652 14.5 3 14.5C2.44772 14.5 2 14.0523 2 13.5C2 12.9477 2.44772 12.5 3 12.5C4.32608 12.5 5.59785 11.9732 6.53554 11.0355C7.47322 10.0979 8 8.82608 8 7.5C8 6.94772 8.44772 6.5 9 6.5ZM33 23.5C31.6739 23.5 30.4022 24.0268 29.4645 24.9645C28.5268 25.9022 28 27.1739 28 28.5C28 29.0523 27.5523 29.5 27 29.5C26.4477 29.5 26 29.0523 26 28.5C26 26.6435 26.7375 24.863 28.0503 23.5503C29.363 22.2375 31.1435 21.5 33 21.5C33.5523 21.5 34 21.9477 34 22.5C34 23.0523 33.5523 23.5 33 23.5Z" fill="#0a9494"/>
                                                    </svg>
                                                    <p class=""><b>{{ translate('Cash_on_Delivery') }}</b></p>
                                                </button>
                                            </label>
                                        </form>
                                    </li>
                                    @endif

                                    <!--Digital payment start-->
                                    @if ($digital_payment['status']==1)
                                            <!-- <li>
                                                <label id="digital_payment_btn">
                                                    <input type="hidden" >
                                                    <span class="payment-method text-center  align-items-center gap-3">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                                                        <path d="M30.1212 7.20977C30.1132 4.90941 28.2423 3.01804 25.9616 3.00822C22.3021 2.99304 18.6417 3.00286 14.9822 3.00643C14.2776 3.00732 13.6106 3.1886 13.0078 3.54937C11.6085 4.38611 10.8574 5.62024 10.8476 7.26335C10.8351 9.41637 10.8396 11.5703 10.8503 13.7242C10.8512 13.9715 10.7967 14.0582 10.5395 14.0394C10.2065 14.0162 9.87069 14.0269 9.53581 14.0367C8.42581 14.068 7.52567 14.928 7.51585 16.0371C7.49442 18.4133 7.49531 20.7905 7.51496 23.1667C7.52389 24.2767 8.41867 25.1421 9.5251 25.1778C9.81532 25.1867 10.1055 25.1805 10.3958 25.1814C10.8449 25.1814 10.8449 25.1814 10.8449 25.6145C10.8449 26.6521 10.8414 27.6898 10.8458 28.7274C10.8566 31.1251 12.714 32.9924 15.1063 32.9969C18.6881 33.0031 22.27 32.9978 25.8518 32.9995C26.1768 32.9995 26.4974 32.9674 26.8135 32.8933C28.7772 32.4325 30.1203 30.7554 30.1248 28.7346C30.131 25.1528 30.1266 21.571 30.1266 17.9891C30.1239 14.3957 30.1337 10.8032 30.1212 7.20977ZM9.57868 17.6052C9.59028 17.2381 9.59832 16.8684 9.57689 16.5023C9.56171 16.246 9.63047 16.1665 9.90015 16.1692C11.4263 16.1835 12.9533 16.1764 14.4803 16.1764C15.9958 16.1764 17.5112 16.1871 19.0266 16.1674C19.3365 16.1639 19.399 16.2585 19.3802 16.538C19.3561 16.8925 19.3668 17.2506 19.3775 17.6069C19.3829 17.7873 19.3391 17.8588 19.1391 17.8588C16.0297 17.8525 12.9212 17.8525 9.81175 17.8588C9.60904 17.8597 9.57332 17.7829 9.57868 17.6052ZM9.86979 23.1087C9.69744 23.1087 9.64208 23.0641 9.64386 22.8855C9.65279 22.0041 9.65637 21.1227 9.64208 20.2413C9.63851 20.018 9.72155 19.9921 9.91087 19.993C11.4397 19.9993 12.9676 19.9966 14.4964 19.9966C16.0252 19.9966 17.554 20.002 19.082 19.9912C19.3115 19.9895 19.3829 20.0404 19.3784 20.2806C19.3624 21.1396 19.3677 21.9987 19.3758 22.8578C19.3775 23.0399 19.3382 23.1051 19.1409 23.1051C16.0511 23.1007 12.9605 23.1025 9.86979 23.1087ZM27.9825 28.548C27.9825 30.0009 27.1279 30.8555 25.6776 30.8555C22.1967 30.8555 18.7158 30.8564 15.2349 30.8555C13.8499 30.8555 12.9881 29.991 12.9864 28.5997C12.9846 27.5514 12.9926 26.5021 12.981 25.4537C12.9783 25.2314 13.0417 25.176 13.2596 25.1769C15.201 25.1849 17.1424 25.1858 19.0828 25.1769C19.3177 25.176 19.3972 25.2412 19.3793 25.4751C19.3624 25.6966 19.3641 25.9225 19.39 26.1431C19.4561 26.7173 19.9258 27.1227 20.4831 27.1058C21.0403 27.0888 21.5091 26.653 21.51 26.0779C21.5189 21.7603 21.5189 17.4426 21.5091 13.125C21.5082 12.5642 21.0492 12.1373 20.5045 12.1105C19.9419 12.0838 19.4677 12.4749 19.3954 13.05C19.365 13.292 19.3704 13.5402 19.3793 13.7849C19.3856 13.9644 19.3427 14.0394 19.1409 14.0385C17.1665 14.0314 15.1912 14.0314 13.2167 14.0385C13.0123 14.0394 12.9837 13.9626 12.9837 13.7858C12.989 11.5881 12.9792 9.39047 12.9917 7.19281C12.9989 6.03548 13.9267 5.15052 15.1108 5.14873C17.8103 5.14427 20.5108 5.14695 23.2103 5.14695C24.047 5.14695 24.8838 5.14605 25.7205 5.14695C27.1064 5.14784 27.9825 6.01583 27.9825 7.39551C27.9843 10.9318 27.9834 14.469 27.9834 18.0052C27.9825 21.5192 27.9834 25.0331 27.9825 28.548Z" fill="#0a9494"/>
                                                    </svg>
                                                    <p class=""><b>{{ translate('Digital_Payment') }}</b></p>
                                                    </span>
                                                </label>
                                            </li> -->

                                        @if($wallet_status==1)
                                        <li>
                                            <label class="digital_payment d--none">
                                                <button class="payment-method text-center align-iems-center border-0 gap-3" type="submit" data-bs-toggle="modal" data-bs-target="#wallet_submit_button">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                                                        <path d="M5.8125 33.0009C5.86955 32.8985 5.96725 32.9391 6.04931 32.9391C13.4108 32.9383 20.7723 32.9391 28.1345 32.9407C28.2166 32.9407 28.3135 32.9 28.3713 33.0009C20.8512 33.0009 13.3318 33.0009 5.8125 33.0009Z" fill="#222222"/>
                                                        <path d="M31.6331 17.3978C31.3947 17.3134 31.3197 17.186 31.322 16.9484C31.3306 16.0793 31.3275 15.2102 31.3243 14.3411C31.3236 14.0488 31.3447 13.755 31.3032 13.4635C31.0891 11.9574 30.0817 10.8906 28.5811 10.5795C28.2231 10.5053 28.2208 10.5053 28.22 10.1419C28.2184 9.62446 28.2012 9.10629 28.2231 8.58968C28.2747 7.38922 27.3275 6.2755 25.9433 6.30598C24.2552 6.34349 22.5647 6.30989 20.8757 6.32005C20.5936 6.32161 20.4537 6.21923 20.3654 5.95506C20.2052 5.47362 20.0254 4.99844 19.8558 4.52012C19.5502 3.66041 19.018 3.08128 18.0582 3C17.8628 3 17.6674 3 17.4721 3C16.9039 3.06409 16.3873 3.30246 15.8589 3.49472C13.6299 4.30363 11.4009 5.11411 9.17429 5.92927C8.3771 6.22079 7.57132 6.49121 6.78429 6.80852C5.644 7.26807 5.14302 8.41227 5.56037 9.56897C5.64322 9.79875 5.70809 10.0348 5.80969 10.2567C5.90582 10.467 5.83157 10.5483 5.61821 10.5983C4.16608 10.9406 3.25869 11.8152 3 13.3126C3 18.9766 3 24.6413 3 30.3052C3.06878 30.6553 3.12974 31.0047 3.31575 31.322C3.86674 32.2653 4.64517 32.871 5.7542 33C5.77374 33 5.79328 33 5.81282 33C5.91364 32.9429 6.02306 32.9711 6.12857 32.9711C13.4377 32.9695 20.7468 32.9695 28.0559 32.9711C28.1606 32.9711 28.2708 32.9429 28.3716 33C28.3912 33 28.4107 33 28.4302 33C28.6725 32.932 28.9289 32.9562 29.1704 32.8648C30.4607 32.3748 31.3189 31.1493 31.3228 29.7659C31.3259 28.6819 31.3243 27.5979 31.3228 26.5147C31.3228 26.3404 31.3337 26.2013 31.5455 26.1301C32.3349 25.8667 32.8585 25.3533 33 24.5037C33 22.6874 33 20.871 33 19.0539C32.9007 18.2083 32.4115 17.6745 31.6331 17.3978ZM25.9902 8.14967C26.2599 8.14967 26.3826 8.26143 26.3802 8.53419C26.374 9.1485 26.3701 9.76358 26.3834 10.3779C26.3873 10.5709 26.3318 10.6186 26.1419 10.6178C24.796 10.6092 23.4502 10.6131 22.1043 10.6131C20.778 10.6131 19.4517 10.61 18.1247 10.617C17.9433 10.6178 17.873 10.585 17.8777 10.3834C17.8918 9.76905 17.8871 9.15475 17.8808 8.53966C17.8777 8.27003 17.9934 8.14967 18.2661 8.15045C20.8406 8.15279 23.4158 8.15279 25.9902 8.14967ZM7.55412 8.46776C10.8906 7.25166 14.2278 6.03712 17.5651 4.82337C17.8753 4.71082 17.9855 4.76084 18.0989 5.07112C18.256 5.49941 18.4052 5.93005 18.5694 6.39273C18.1567 6.38804 17.7964 6.38804 17.451 6.52169C16.6655 6.82493 16.1372 7.57054 16.127 8.41852C16.1192 9.06252 16.1184 9.70731 16.1293 10.3521C16.1325 10.5436 16.102 10.6178 15.8832 10.6178C13.2954 10.6092 10.7077 10.6108 8.12075 10.6163C7.94646 10.617 7.8644 10.5662 7.80813 10.4005C7.64869 9.93083 7.46893 9.46815 7.30168 9.00078C7.1907 8.69363 7.24228 8.58109 7.55412 8.46776ZM29.4924 26.4748C29.4721 27.5385 29.4853 28.603 29.4838 29.6674C29.4822 30.6217 28.8656 31.2376 27.9066 31.2376C20.7202 31.2384 13.5346 31.2384 6.34818 31.2376C5.37046 31.2376 4.75772 30.6264 4.75772 29.6518C4.75694 24.4185 4.75694 19.1844 4.75772 13.9512C4.75772 12.9703 5.35952 12.3716 6.34818 12.3708C9.94099 12.3693 13.5346 12.3701 17.1274 12.3701C20.6913 12.3701 24.2552 12.3888 27.8183 12.3583C28.8695 12.3497 29.5299 13.0985 29.4932 14.0238C29.4517 15.0477 29.4775 16.0739 29.4893 17.0993C29.4916 17.304 29.44 17.358 29.2337 17.3556C28.1989 17.3447 27.1633 17.34 26.1293 17.3556C23.8855 17.3892 21.9746 19.2532 21.8714 21.4924C21.7651 23.8136 23.4291 25.8331 25.6909 26.0895C26.8374 26.2192 27.9926 26.1254 29.1438 26.1317C29.4189 26.1333 29.4978 26.1864 29.4924 26.4748ZM31.2415 23.9863C31.2407 24.2857 31.1415 24.381 30.8335 24.3818C29.3118 24.3834 27.7894 24.3919 26.2677 24.3794C24.7882 24.3669 23.6237 23.1923 23.626 21.7394C23.6276 20.2857 24.7898 19.1282 26.2755 19.111C27.007 19.1024 27.7386 19.1094 28.4709 19.1094C29.261 19.1094 30.0512 19.1079 30.8413 19.1102C31.1532 19.111 31.2415 19.1985 31.2415 19.5088C31.2431 21.0008 31.2431 22.4936 31.2415 23.9863Z" fill="#0a9494"/>
                                                        <path d="M26.2764 20.8692C26.7555 20.8763 27.1447 21.2741 27.14 21.7524C27.1353 22.2252 26.7321 22.6254 26.2608 22.6246C25.7778 22.6246 25.3706 22.2057 25.3831 21.7211C25.3948 21.2428 25.7942 20.8622 26.2764 20.8692Z" fill="#0a9494"/>
                                                    </svg>
                                                    <p class=""><b>{{ translate('wallet') }}</b></p>
                                                </button>
                                            </label>
                                        </li>
                                        @endif

                                        @if(isset($offline_payment) && $offline_payment['status'])
                                        <li>
                                            <form action="{{route('offline-payment-checkout-complete')}}" method="get" class="digital_payment d--none">
                                                <label>
                                                    <input type="hidden" name="weight" >
                                                    <span class="payment-method align-iems-center gap-3" data-bs-toggle="modal" data-bs-target="#offline_payment_submit_button">
                                                        <img  src="{{ theme_asset('assets/img/payment/pay-offline.png') }}" class="dark-support" alt="">
                                                      
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
                                                    <button class="payment-method border-0 align-iems-center gap-3">
                                                        <img  src="{{ theme_asset('assets/img/payment/sslcomz.png') }}" class="dark-support" alt="">
                                                         
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
                                                        <img  src="{{ theme_asset('assets/img/payment/paypal.png') }}" class="dark-support" alt="">
                                                    </button>
                                                </label>
                                            </form>
                                        </li>
                                        @endif

                                        @if($stripe['status'])
                                        <li>
                                            <label class="digital_payment d--none">
                                                <button class="payment-method border-0 d-flex align-iems-center gap-3" type="button" id="checkout-button">
                                                    <img  src="{{ theme_asset('assets/img/payment/stripe.png') }}" class="dark-support" alt="">
                                                </button>
                                            </label>
                                        </li>
                                        @endif

                                        @if(isset($inr) && isset($usd) && $razor_pay['status'])
                                            <li>
                                                <form action="{!! route('payment-razor') !!}?group_id={{request('group_id')}}" method="post" id="payment-form" class="digital_payment d--none">
                                                    <input type="hidden" name="group_id" value="{{request('group_id')}}">
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
                                                        <button class="payment-method border-0 align-iems-center gap-3" type="button" onclick="$('.razorpay-payment-button').click()">
                                                            <img width="100" src="{{ theme_asset('assets/img/payment/razor.png') }}" class="dark-support" alt="">
                                                             <p class=""><b>{{ translate('razorpay') }}</b></p>
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
                                                <form action="{{route('checkout-complete-wallet')}}?group_id={{request('group_id')}}" method="get" class="needs-validation">
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

                <!-- Order summery Content -->
                @include('theme-views.partials._order-summery')

            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection

@push('script')

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
