@extends('theme-views.layouts.app')

@section('title', translate('Personal_Details').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))
@section('content')
<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 py-3">
    <div class="container">
        <div class="row g-3">

        <div class="col-md-12">
        <div class="bread__crum">
                    <nav aria-label="breadcrumb">
                                <ol class="breadcrumb fs-12 mb-0">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}">{{ translate('home') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{translate('Account')}}</li>
                                </ol>
                        </nav>
              </div>
        </div>

            <!-- Sidebar-->
            @include('theme-views.partials._profile-aside')

            <div class="col-lg-9 card py-3 profilecard">
            <nav class="d-flex justify-content-between align-items-center">
                    <div class="subscribe-tittle">
                        <h4>Subscription Plan</h4>
                    </div>
                    <div class="nav nav-tabs border-0 justify-content-end mb-3" id="nav-tab" role="tablist">
                        <button class="nav-link navbar__tabsubscribtion active" id="nav-home-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-home123" type="button" role="tab" aria-controls="nav-home123"
                            aria-selected="true">Monthly</button>
                        <button class="nav-link navbar__tabsubscribtion" id="nav-profile-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-profile123" type="button" role="tab" aria-controls="nav-profile123"
                            aria-selected="false">Yearly</button>
                    </div>
                </nav>
                <div class="tab-content " id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home123" role="tabpanel"
                        aria-labelledby="nav-home-tab">
                        <!-- first Tab In TABLIST -->

                        <div class="">
                            <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
                                @foreach($monthly_plans as $m_key => $m_plan)
                                    <div class="col-lg-4">
                                        <div class="card mb-4 rounded-3 shadow-sm subscription-plan-card position-relative">
                                            <div class="card-header py-3 light-green-bg">
                                                <h4 class="my-0 fw-normal">{{$m_plan->title}}</h4>
                                                <h3 class="card-title pricing-card-title">
                                                    @if($m_plan->price > 0)
                                                        {{ App\CPU\Helpers::set_symbol(App\CPU\BackEndHelper::usd_to_currency($m_plan->price)) }}<small class="text-muted fw-light">/ Month</small>
                                                    @else
                                                        <small class="text-muted fw-light">Free Plan</small>
                                                    @endif
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled mt-3 mb-4">
                                                    <li>
                                                          <div class="icon__svg">
                                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 16 16" fill="none">
                                                            <path
                                                                d="M14.2507 8.75005C13.7507 11.25 11.8657 13.604 9.22071 14.13C7.9307 14.3869 6.59252 14.2303 5.39672 13.6824C4.20091 13.1346 3.20843 12.2234 2.56061 11.0786C1.91278 9.93389 1.64263 8.61393 1.78862 7.30672C1.93461 5.99951 2.4893 4.77167 3.37371 3.79805C5.18771 1.80005 8.25071 1.25005 10.7507 2.25005"
                                                                stroke="#0A9494" stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path d="M5.75 7.75L8.25 10.25L14.25 3.75" stroke="#0A9494"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                          </div>
                                                        <p>{{ $m_plan->description }}</p>
                                                    </li>
                                                </ul>
                                                @php($isSubscribed = \App\Model\SellerSubscription::where(['type' => 1, 'seller_id' => auth('customer')->id(), 'plan_id' => $m_plan->id])->where('expiry_date' ,'>=', date('Y-m-d'))->first() ? true : false)
                                                <button type="button" class="btn btn-lg btn-primary position-absolute  btn__start__subscribtion {{ !$isSubscribed ? 'purchase-plan' : '' }}" data-id="{{ $m_plan->id }}" data-amount="{{ App\CPU\BackEndHelper::usd_to_currency($m_plan->price) }}">{{ !$isSubscribed ? 'Get started' : 'Purchased'}}</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>


                        </div>



                    </div>
                    <div class="tab-pane fade" id="nav-profile123" role="tabpanel" aria-labelledby="nav-profile-tab">

                        <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
                            @foreach($yearly_plans as $m_key => $m_plan)
                                <div class="col-lg-4 col-md-6">
                                    <div class="card mb-4 rounded-3 shadow-sm subscription-plan-card position-relative">
                                        <div class="card-header py-3 light-green-bg">
                                            <h4 class="my-0 fw-normal">{{$m_plan->title}}</h4>
                                            <h3 class="card-title pricing-card-title">{{ App\CPU\Helpers::set_symbol(App\CPU\BackEndHelper::usd_to_currency($m_plan->price)) }}<small
                                                    class="text-muted fw-light">/ Year</small></h3>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled mt-3 mb-4">
                                                <li>
                                                        <div class="icon__svg">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 16 16" fill="none">
                                                        <path
                                                                d="M14.2507 8.75005C13.7507 11.25 11.8657 13.604 9.22071 14.13C7.9307 14.3869 6.59252 14.2303 5.39672 13.6824C4.20091 13.1346 3.20843 12.2234 2.56061 11.0786C1.91278 9.93389 1.64263 8.61393 1.78862 7.30672C1.93461 5.99951 2.4893 4.77167 3.37371 3.79805C5.18771 1.80005 8.25071 1.25005 10.7507 2.25005"
                                                                stroke="#0A9494" stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                                <path d="M5.75 7.75L8.25 10.25L14.25 3.75" stroke="#0A9494"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                 stroke-linejoin="round" />
                                                        </svg>
                                                        </div>
                                                    <p>{{ $m_plan->description }}</p>
                                                </li>
                                            </ul>
                                            @php($isSubscribed = \App\Model\SellerSubscription::where(['type' => 1, 'seller_id' => auth('customer')->id(), 'plan_id' => $m_plan->id])->where('expiry_date' ,'>=', date('Y-m-d'))->first() ? true : false)
                                            <button type="button" class=" btn btn-lg btn-primary position-absolute  btn__start__subscribtion {{  !$isSubscribed ? 'purchase-plan' : '' }}" data-id="{{ $m_plan->id }}" data-amount="{{ App\CPU\BackEndHelper::usd_to_currency($m_plan->price) }}">{{ !$isSubscribed ? 'Get started' : 'Purchased'}}</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>




                <div class="pricing d-none">
            <div class="plan">
                <h2>Dev</h2>
                <div class="price">FREE</div>
                <ul class="features">
                    <li><i class="fas fa-check-circle"></i> Unlimited Websites</li>
                    <li><i class="fas fa-check-circle"></i> 1 User</li>
                    <li><i class="fas fa-check-circle"></i> 100MB Space/website</li>
                    <li><i class="fas fa-check-circle"></i> Continuous deployment</li>
                    <li><i class="fas fa-times-circle"></i> No priority support</li>
                </ul>
                <button>Signup</button>
            </div>
            <div class="plan popular">
                <span>Most Popular</span>
                <h2>Pro</h2>
                <div class="price">$10/month</div>
                <ul class="features">
                    <li><i class="fas fa-check-circle"></i> Unlimited Websites</li>
                    <li><i class="fas fa-check-circle"></i> 5 Users</li>
                    <li><i class="fas fa-check-circle"></i> 512MB Space/website</li>
                    <li><i class="fas fa-check-circle"></i> Continuous deployment</li>
                    <li><i class="fas fa-check-circle"></i> Email Support</li>
                </ul>
                <button>Buy Now</button>
            </div>
            <div class="plan">
                <h2>Enterprise</h2>
                <div class="price">Custom Pricing</div>
                <ul class="features">
                    <li><i class="fas fa-check-circle"></i> Unlimited Websites</li>
                    <li><i class="fas fa-check-circle"></i> Unlimited Users</li>
                    <li><i class="fas fa-check-circle"></i> Unlimited Space/website</li>
                    <li><i class="fas fa-check-circle"></i> Continuous deployment</li>
                    <li><i class="fas fa-check-circle"></i> 24/7 Email support</li>
                </ul>
                <button>Contact Us</button>
            </div>
        </div>

            </div>

        </div>
    </div>
</main>

<style>
        @import url("https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap");

        .pricing {
            display: flex;
            /* flex-wrap: wrap; */
            justify-content: center;
        }

        .pricing .plan {
            background-color: #fff;
            padding: 1rem;
            margin: 12px;
            border-radius: 5px;
            text-align: center;
            transition: 0.3s;
            cursor: pointer;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        }

        .pricing .plan h2 {
            font-size: 22px;
            margin-bottom: 12px;
        }

        .pricing .plan .price {
            margin-bottom: 1rem;
            font-size: 30px;
        }

        .pricing .plan ul.features {
            list-style-type: none;
            text-align: left;
        }

        .pricing .plan ul.features li {
            margin: 8px;
        }

        .pricing .plan ul.features li .fas {
            margin-right: 4px;
        }

        .pricing .plan ul.features li .fa-check-circle {
            color: #0A9494;
        }

        .pricing .plan ul.features li .fa-times-circle {
            color: #eb4d4b;
        }

        .pricing .plan button {
            border: none;
            width: 100%;
            padding: 12px 35px;
            margin-top: 1rem;
            background-color: #0A9494;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .pricing .plan.popular {
            border: 2px solid #0A9494;
            position: relative;
            transform: scale(1.08);
        }

        .pricing .plan.popular span {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #0A9494;
            color: #fff;
            padding: 4px 20px;
            font-size: 18px;
            border-radius: 5px;
        }

        .pricing .plan:hover {
            box-shadow: 5px 7px 67px -28px rgba(0, 0, 0, 0.37);
        }
    </style>



<style>
    .image-container {
        position: relative;
    }

    #image-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .btn__height{
        height: 48px;
        width: 138px;
    }

    .btn__profile{
        display: flex;
        gap:13px;
        

    }

    .edit__icon{
        width: 28px;
        height: 28px;
        flex-shrink: 0;
        border-radius: 20px;
        background: var(--green, #0A9494);
    }

    .edit__icon {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
        border-radius: 20px;
        background: var(--green, #0A9494);
        display: flex;
        justify-content: center;
        align-items: center;
    }

</style>
<!-- End Main Content -->
@endsection

@push('script')


<script>
    function updateImage() {
        const input = document.getElementById('image-input');
        const image = document.getElementById('selected-image');

        const file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                image.src = e.target.result;
            };

            reader.readAsDataURL(file);
        }
    }

    function checkPasswordMatch() {
        var password = $("#password").val();
        var confirmPassword = $("#confirm_password").val();
        $("#message").removeAttr("style");
        $("#message").html("");
        if (confirmPassword == "") {
            $("#message").attr("style", "color:black");
            $("#message").html("{{translate('Please ReType Password')}}");

        } else if (password == "") {
            $("#message").removeAttr("style");
            $("#message").html("");

        } else if (password != confirmPassword) {
            $("#message").html("{{translate('Passwords do not match')}}!");
            $("#message").attr("style", "color:red");
        } else if (confirmPassword.length <= 6) {
            $("#message").html("{{translate('password Must Be 6 Character')}}");
            $("#message").attr("style", "color:red");
        } else {

            $("#message").html("{{translate('Passwords match')}}.");
            $("#message").attr("style", "color:green");
        }
    }
    $(document).ready(function() {
        $("#confirm_password").keyup(checkPasswordMatch);
    });

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
                        html += `<option value="${elm.id}">${elm.name}</option>`
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
                        html += `<option value="${elm.id}">${elm.name}</option>`
                    })
                    $('select[name=city]').html(html)
                }
            }
        });
    })

    $(document).on('click','.purchase-plan', function() {
        let amount = $(this).data('amount')
        let id = $(this).data('id')

        if(amount > 0) {
            var rzp1 = openRazorPay(amount, new Date().getTime(), id)
            rzp1.open();
            rzp1.on('payment.failed', function(response) {
                location.href = base_url + 'payment/cancel';
            });
        } else {
            var order_id = new Date().getTime();
            var randomIds = `Free Plan ${new Date().getTime()}`;
            purchase_plan(order_id, id, randomIds)
        }

    })

    function purchase_plan(order_id, plan_id, randomIds) {
        $.ajax({
            type: "POST",
            url: "{{ route('user-purchased') }}",
            data: {
                order_id: order_id,
                plan_id: plan_id,
                transaction_id: randomIds,
                _token: $('meta[name=_token]').attr('content')
            },
            headers:{
                'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    swal.fire('Subscription purchased success').then(function() {
                        window.location.href = "{{ route('user-subscription') }}"
                    })
                }
            }
        });
    }

    function openRazorPay(amount, order_id, plan_id) {

        var options = {
            "key": 'rzp_test_1spO8LVd5ENWfO', // Enter the Key ID generated from the Dashboard
            "amount": (amount * 100), // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            "currency": "INR",
            "name": 'Alpha Ecommerce',
            "description": "Order Place",
            "image": 'https://alpha-ecom.developmentalphawizz.com/resources/themes/alpha_ecom/public/assets/images/logo.svg',
            // "order_id": order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            "handler": function(response) {
                $('#razorpay_payment_id').val(response.razorpay_payment_id);
                $('#razorpay_signature').val(response.razorpay_signature);
                purchase_plan(order_id, plan_id, response.razorpay_payment_id)
            },
            "prefill": {
                "name": "Cartuko.com",
                "email": "admin@gmail.com",
                "contact": ""
            },
            "notes": {
                "address": "Cartuko Purchase"
            },
            "theme": {
                "color": "#3399cc"
            },
            "escape": false,
            "modal": {
                "ondismiss": function() {
                    $('#place_order_btn').attr('disabled', false).html('Place Order');
                }
            }
        };
        var rzp = new Razorpay(options);
        return rzp;
    }

</script>
@endpush