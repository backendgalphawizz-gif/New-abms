@extends('theme-views.layouts.app')

@section('title', $web_config['name']->value.' '.translate('Forgot Password').' | '.$web_config['name']->value.''.translate(' Ecommerce'))

@section('content')
<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 py-3">
    <div class="container">
           <div class="col-md-8 mx-auto mt-md-5 mt-4">
           <div class="card shadow border-0 recovercard">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-lg-5  mb-lg-0">
                            <!-- <h2 class="text-center mb-5">{{ translate('Forget Password') }}</h2> -->
                            <div class="log-ing">
                                <!-- <img width="100%" class="dark-support" src="{{ theme_asset('assets/img/login-img/login-bg-set.png') }}" alt=""> -->
                                <div class="caption-login forget-login">
                                <img src="{{ theme_asset('assets/images/forget-password1.jpeg') }}" alt="">
                                <!-- <h2>Welcome to Prime Basket</h2> -->
                                <!-- <p> Sign in to access exclusive features and manage your account effortlessly.</p> -->
                            </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                              <div class="col-md-10 mx-auto">
                              <div class="mb-3 recoverlogo">
                                <img class="dark-support forgetlogo" src="{{ theme_asset('assets/images/primeLogo.png') }}" alt="">
                            </div>
                            <h3 class="mb-2 forgethead" style="width: 100%;">
                                @if($verification_by=='email')
                                    {{ translate('Enter your Email') }}
                                @else($verification_by=='phone')
                                    {{ translate('Enter your Mobile No.') }}
                                @endif
                            </h3>

                            <p class="forgetpara">Enter The Email address or mobile number associated with your Account.</p>
                        <!-- <p class="text-muted"> We have sent the 4 digit verification code</p> -->

                            <form action="{{route('customer.auth.forgot-password')}}" class="forget-password-form" method="post">
                                @csrf
                                @if($verification_by=='email')
                                <div class="form-group position-relative">
                                  <span>
                                      
                                    <input class="form-control balloon forminputdiv" type="email" name="identity" id="recover-email" autocomplete="off" placeholder="Please enter your email" required>
                                    <label for="recover-email">{{translate('email')}}</label>
                                    <div class="invalid-feedback">{{translate('Please provide valid email address')}}.</div>
                                  </span>
                                </div>
                                @else
                                    <div class="form-group position-relative">
                                        <input class="form-control balloon forminputdiv" type="text" name="identity" id="recover-email" autocomplete="off" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="10" placeholder="Please enter your Mobile Number" required>
                                        <label for="recover-email">{{translate('phone')}}</label>
                                        <div class="invalid-feedback">{{translate('Please provide valid phone number')}}.</div>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-center gap-3 mt-4">
                                    <!-- <button class="btn btn-outline-primary" onclick="location.href='{{ route('home') }}'" type="button">{{ translate('Back_Again') }}</button> -->
                                    <button class="btn btn-primary px-sm-5 d-block w-100 forgetbtn" type="submit">{{ translate('Send') }}</button>
                                </div>
                            </form>       
                              </div>
                        </div>
                    </div>
                </div>
            </div>
           </div>
    </div>
</main>
<!-- End Main Content -->
@endsection
