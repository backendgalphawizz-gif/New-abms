<!-- Login Modal -->
<div class="modal fade customModal" id="auth-model" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <button type="button" data-bs-dismiss="modal" class="loginclosebtn" aria-label="Close"><img src="{{ theme_asset('assets/images/multiply.png') }}" alt=""></button>

            <section class="login-page">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5 col-md-12 auth-left">
                            <img src="{{ theme_asset('assets/images/loginVector.png') }}" alt="">
                        </div>
                        <div class="col-lg-7 col-md-12">
                            <div class="auth-right">
                                <div class="heading__set__sign__in">
                                    <div class="customModalLogo">
                                        <img src="{{ theme_asset('assets/images/primeLogo.png') }}" alt="">
                                    </div>
                                    <h2 class="auth-heading mt-2">Sign In to your account</h2>
                                </div>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <form action="{{route('customer.auth.login-otp')}}" method="post" autocomplete="off" id="login-with-otp">
                                            {{ csrf_field() }}
                                            <div class="login-form">
                                                <span>
                                                    <div class="form-group">
                                                        <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="10" class="w-100 form-control mobile_code" placeholder="Phone Number" name="phone" />
                                                    </div>
                                                </span>

                                                <span class="d-none otp-field">
                                                    <div class="form-group mt-2">
                                                        <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="4" maxlength="4" class="w-100 form-control mobile_code" readonly placeholder="Enter OTP" name="otp" />
                                                    </div>
                                                    <div class="resend-otp-div">
                                                        <a href="javascript:void(0)" class="resend-otp" onclick="resendLoginOTP()">Resend OTP</a>
                                                    </div>

                                                </span>

                                                <div class="login-button">
                                                    <button submit class="btn-login w-100">Login <i class="fa-solid fa-arrow-right"></i></button>
                                                </div>

                                            </div>
                                        </form>

                                        <form action="{{route('customer.auth.login-otp')}}" method="post" autocomplete="off" id="---submit-with-otp" class="d-none">
                                            {{ csrf_field() }}
                                            <div class="login-form">
                                                <input type="hidden" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="10" class="w-100 form-control mobile_code" placeholder="Phone Number" name="phone" />

                                                <span class="otp-field ">
                                                    <div class="form-group mt-2">
                                                        <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="4" maxlength="4" class="w-100 form-control mobile_code" placeholder="Enter OTP" name="otp" />
                                                    </div>
                                                    <div class="resend-otp-div">
                                                        <a href="javascript:void(0)" class="resend-otp" onclick="resendLoginOTP()">Resend OTP</a>
                                                    </div>
                                                </span>
                                                <div class="login-button">
                                                    <button submit class="btn-login w-100">Login <i class="fa-solid fa-arrow-right"></i></button>
                                                </div>
                                                <div class="forgot-id-pass">
                                                    <a href="{{route('customer.auth.recover-password')}}" class="forGet">Forgot Password?</a>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="text-center">
                                            <p class="havent-account">Don’t Have an account? <button data-bs-toggle="modal" data-bs-target="#auth-model-signup-1" data-bs-dismiss="modal">Sign Up</button></p>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                        <form action="{{route('customer.login.short')}}" method="post" id="customer_login_modal" autocomplete="off">
                                            {{ csrf_field() }}
                                            <div class="login-form">
                                                <span> <input class="balloon form-control" name="user_id" type="email" placeholder="Email..." autocomplete="off" /><label>Email</label> </span>
                                            </div>
                                            <div class="login-form">
                                                <span>
                                                    <input class="balloon form-control" name="password" type="password" placeholder="Password..." autocomplete="off" /><label>Password</label>
                                                    <i class="toggle-password fa fa-fw fa-eye-slash"></i>
                                                </span>
                                                <div class="login-button">
                                                    <button type="submit" class="btn-login w-100">Log in <i class="fa-solid fa-arrow-right"></i></button>
                                                </div>
                                                <div class="forgot-id-pass">
                                                    <a href="{{route('customer.auth.recover-password')}}" class="forGet">Forgot Password?</a>
                                                </div>

                                            </div>
                                        </form>
                                        <div class="text-center">
                                            <p class="havent-account mx-auto">Don’t Have an account? <button data-bs-toggle="modal" data-bs-target="#auth-model-signup-1" data-bs-dismiss="modal">Sign Up</button></p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- Login Modal -->

<!-- Signup Modal -->
<div class="modal fade" id="auth-model-signup-1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content signupcontent">
            <button type="button" data-bs-dismiss="modal" class="loginclosebtn" aria-label="Close"><img src="{{ theme_asset('assets/images/multiply.png') }}" alt=""></button>
            <section class="login-page">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5 col-md-12 auth-left">
                            <img src="{{ theme_asset('assets/images/loginVector.png') }}" alt="">
                        </div>
                        <div class="col-lg-7 col-md-12">

                            <div class="auth-right">
                                <style>
                                    .wizard-container .step {
                                        display: none;
                                    }

                                    .wizard-container .step h2 {
                                        color: #333;
                                    }

                                    .wizard-container button {
                                        background-color: #0a9494;
                                        color: #fff;
                                        border: none;
                                        border-radius: 4px;
                                        cursor: pointer;
                                    }

                                    .wizard-container button:hover {
                                        background-color: #45a049;
                                    }
                                </style>

                                <div class="wizard-container">
                                    <div class="step" id="step1">
                                        <div class="">
                                            <div class="sign__in__pages">
                                                <div class="customModalLogo recoverlogo mb-4">
                                                    <img src="{{ theme_asset('assets/images/primeLogo.png') }}" alt="" class="forgetlogo">
                                                    <h2 class="auth-heading forgethead mt-2">Sign Up to your account</h2>
                                                </div>
                                               
                                                <form action="{{ route('customer.auth.register-otp') }}" method="post" id="register-otp">
                                                    {{ csrf_field() }}
                                                    <div class="login-form">
                                                        <span>
                                                            <div class="form-group">
                                                                <input type="text" class="w-100 form-control mobile_code" placeholder="Phone Number" name="phone" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="10" />
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class="login-button">
                                                        <a href="javascript:void(0)" type="button" class="btn-login" id="loginButton" onclick="nextStep(2)">Send OTP <i class="fa-solid fa-arrow-right"></i></a>
                                                    </div>
                                                </form>
                                                <div class="text-center">
                                                    <p class="havent-account mt-4 mx-auto">Already have an account? <button data-bs-toggle="modal" data-bs-target="#auth-model" data-bs-dismiss="modal">Sign In</button></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="step" id="step2">
                                        <div class="">
                                            <div class="">
                                                <h2 class="auth-heading text-center">Enter verification code</h2>
                                                <p class="text-center">Enter the OTP sent to +91 <span class="register-mobile-response">9556654328</span>.</p>
                                                <p>OTP: <span class="register-otp-response"></span></p>
                                            </div>
                                            <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                                <form action="#" id="otp-verification">
                                                    <div class="login-form">
                                                        <div class="otp-input-fields">
                                                            <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="1" maxlength="1" name="otp1" class="otp__digit otp__field__1" oninput="moveToNextField(this)" />
                                                            <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="1" maxlength="1" name="otp2" class="otp__digit otp__field__2" oninput="moveToNextField(this)" />
                                                            <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="1" maxlength="1" name="otp3" class="otp__digit otp__field__3" oninput="moveToNextField(this)" />
                                                            <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="1" maxlength="1" name="otp4" class="otp__digit otp__field__4" oninput="moveToNextField(this)" />
                                                        </div>
                                                        <div class="login-button">
                                                            <a href="javascript:void(0)" class="btn-login" id="Verify" onclick="nextStep(3)"> Verify </a>
                                                        </div>
                                                        <div class="text-center mt-3">
                                                            <p>Didn’t Received OTP? <a href="javascript:void(0)" class="text-green-light" onclick="nextStep(2)">Resend OTP</a></p>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="step" id="step3">
                                        <div class="">
                                            <div class="pb-3">
                                                <h2 class="auth-heading">Create your account</h2>
                                                <p>Start your journey with Prime Basket: Where every purchase brings you closer to satisfaction and style</p>
                                                <form action="#" id="final-register-step">
                                                    {{ csrf_field() }}
                                                    <div class="login-form">
                                                        <span> <input class="balloon form-control" name="f_name" type="text" placeholder="Full Name " autocomplete="off" /><label>Full Name</label> </span>
                                                    </div>
                                                    <div class="login-form">
                                                        <span> <input class="balloon form-control" name="phone" type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="10" placeholder="Enter Phone" autocomplete="off" readonly /><label>Mobile</label> </span>
                                                    </div>
                                                    <div class="login-form">
                                                        <span> <input class="balloon form-control" name="email" type="email" placeholder="Email..." pattern="/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/" autocomplete="off" onkeypress="return event.which != 32" /><label>Email</label> </span>
                                                    </div>
                                                    <!-- <div class="login-form">
                                                                <span>
                                                                    <input class="balloon form-control" name="password" type="password" placeholder="Create Password..." autocomplete="off" /><label>Create Password</label>
                                                                    <i class="toggle-password fa fa-fw fa-eye-slash"></i>
                                                                </span>
                                                            </div>
                                                            <div class="login-form">
                                                                <span>
                                                                    <input class="balloon form-control" name="confirm_password" type="password" placeholder="Confirm Password..." autocomplete="off" /><label> Confirm Password</label>
                                                                    <i class="toggle-password fa fa-fw fa-eye-slash"></i>
                                                                </span>
                                                            </div>
                                                            <div class="login-form">
                                                                <span> <input class="balloon form-control" name="referral_code" type="text" placeholder="Referral Code...(Optional)" autocomplete="off" /><label>Referral Code</label> </span>
                                                            </div> -->
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="agree_terms" value="1" id="flexCheckDefault" />
                                                        <label class="form-check-label" for="flexCheckDefault"> By Continuing, you agree to our</label> <a href="{{route('terms')}}"> Terms of Service</a> and <a href="{{route('privacy-policy')}}"> Privacy Policy</a>
                                                    </div>
                                                    <div class="login-button">
                                                        <button type="submit" class="btn-login" id="Createaccount">Create Account </button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                        <!-- <button onclick="prevStep(2)">Previous</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- Signup Modal -->

</style>



<div class="div-test-images">
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModalMoney" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bg-transparent">
                <button type="button" data-bs-dismiss="modal" class="loginclosebtn" aria-label="Close"><img src="{{ theme_asset('assets/images/multiply.png') }}" alt=""></button>
                <div class="modal-body">
                    <div class="modal-money">
                        <div class="modal-data1">
                            <div class="money-img">
                                <img src="{{ theme_asset('assets/images/Tick-Square.png') }}" alt="tik" />
                                <h3>Register successfully</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FORGET PASSSWORD MODAL START -->
<div class="modal fade" id="forgetpassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <button type="button" data-bs-dismiss="modal" class="loginclosebtn" aria-label="Close"><img src="{{ theme_asset('assets/images/multiply.png') }}" alt=""></button>
            <section class="login-page">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5 col-md-12 auth-left">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 mx-auto">
                                        <div class="auth-logo">
                                            <!-- <img src="{{ theme_asset('assets/images/brand-logo/auth-logo.png')}}" alt="Logo" /> -->
                                        </div>
                                        <h4 class="ff-primary">
                                            Welcome to<br> Prime Basket
                                        </h4>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-12">
                            <div class="auth-right">
                                <div class="set">
                                    <h2 class="auth-heading">Forget your account</h2>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                    <form>
                                        <div class="form-group">
                                            <input type="text" class="w-100 form-control mobile_code" placeholder="Phone Number" name="name" />
                                        </div>
                                        <div class="login-button">
                                            <a class="btn-login" id="loginButton">forget password <i class="fa-solid fa-arrow-right"></i></a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- FORGET PASS WORD END -->

<!-- Modal -->
<div class="deactivated-modal">
    <div class="modal fade" id="deactivatemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="icon-verify-cancle-order">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                            <circle cx="40" cy="40" r="40" fill="#E3E1EC" />
                            <path
                                d="M51.602 60.3551C51.0247 60.3115 50.4744 60.1038 50.194 59.5182C49.8908 58.8828 49.9054 58.2328 50.4266 57.6929C51.1472 56.9473 51.8512 56.179 52.6361 55.5062C53.2217 55.0036 53.1324 54.7088 52.6029 54.2519C51.8969 53.6455 51.2531 52.9644 50.601 52.2957C49.8472 51.5232 49.7995 50.5659 50.4515 49.9056C51.1327 49.2182 52.0339 49.239 52.8355 49.9949C53.5644 50.6822 54.285 51.382 54.9661 52.1171C55.3523 52.5345 55.6078 52.5968 56.021 52.1358C56.6668 51.4173 57.377 50.7549 58.0748 50.0842C58.9241 49.2701 59.8565 49.2016 60.548 49.8786C61.2582 50.5742 61.1918 51.4879 60.3507 52.3393C59.6468 53.0516 58.9386 53.7618 58.2056 54.445C57.863 54.7648 57.8027 54.9725 58.1931 55.3255C58.9864 56.044 59.7381 56.8103 60.4774 57.5849C61.2042 58.3491 61.2105 59.2337 60.5418 59.8795C59.8835 60.515 58.9636 60.5067 58.2222 59.8131C57.4912 59.1278 56.7644 58.4342 56.0916 57.6929C55.6763 57.236 55.4084 57.1571 54.9599 57.6638C54.3203 58.3864 53.5893 59.0281 52.9102 59.7175C52.5551 60.081 52.1502 60.3115 51.602 60.3551Z"
                                fill="#040D12" />
                            <path
                                d="M55.2884 43.4491C53.7683 42.1949 52.1257 41.1213 50.3294 40.2242C50.4956 40.0476 50.6306 39.894 50.778 39.7507C53.814 36.7915 55.4566 32.5968 54.9374 28.3605C54.4349 24.2716 51.9824 20.6251 48.4293 18.5589C43.5265 15.7077 37.3984 16.0981 32.9316 19.5909C31.7625 20.5047 30.7345 21.6053 29.9205 22.8492C29.4387 23.5863 29.0276 24.3692 28.7015 25.1874C27.0486 29.3365 27.6674 34.0525 30.2403 37.6865C30.2673 37.726 30.2964 37.7654 30.3234 37.8028C30.8342 38.511 31.4427 39.1485 32.0407 39.8608C31.6773 40.0435 31.37 40.1951 31.0647 40.3488C22.8497 44.5186 18.2707 52.6506 19.0951 61.5032C19.1761 62.365 19.5458 62.9007 20.2082 63.3327C20.555 63.3327 20.8997 63.3327 21.2465 63.3327C22.1457 62.853 22.4198 62.1241 22.3367 61.1066C22.1748 59.1047 22.2869 57.1008 22.7998 55.1467C24.5089 48.6448 28.8158 44.5622 34.9168 42.145C35.3446 41.9747 35.6333 42.1492 35.9738 42.2883C39.4085 43.6921 42.8765 43.798 46.3569 42.4835C46.8553 42.2945 47.2394 42.334 47.7129 42.5375C49.7251 43.4055 51.5318 44.5892 53.2429 45.9452C54.2127 46.7115 55.1846 46.6658 55.7972 45.8746C56.3828 45.1167 56.2 44.2009 55.2884 43.4491ZM41.5038 40.2055C35.735 40.2262 31.044 35.6577 31.0315 30.0072C31.019 24.3879 35.7184 19.8318 41.5267 19.8318C47.1668 19.8298 51.7665 24.4648 51.7602 30.1422C51.7561 35.6245 47.1065 40.1868 41.5038 40.2055Z"
                                fill="#040D12" />
                        </svg>
                    </div>
                    <h4 class="text-ceter">Are you sure want to delete account</h4>
                    <p class="text-ceter text-muted">You want to delete the address</p>
                    <div class="d-flex justify-content-around align-items-center">
                        <button type="button" class="btn btn-cancle-order-stop text-bold" data-bs-dismiss="modal" aria-label="Close">CANCEL</button>
                        <a href="order_canceled.php">
                            <button type="button" class="btn btn-primary text-bold px-5">DELETE</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="add-new-address" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>{{translate('Edit_Address')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="card h-100">
                    <div class="card-body p-lg-4">
                        <div class="mt-4">
                            <form action="{{route('address-store')}}" method="post">
                                @csrf
                                <div class="row gy-4">

                                    <!-- <div class="col-12"> -->
                                        <!-- <div class=" d-flex gap-4 align-items-center"> -->
                                            <!-- <h6 class="fw-semibold text-muted ">{{translate('Choose_Label')}}</h6> -->
                                            <!-- <ul class="option-select-btn style--two mb-0"> -->
                                                <!-- <li>
                                                    <label>
                                                        <input type="radio" name="addressAs" value="home" checked>
                                                       <span><i class="bi bi-house"></i></span>
                                                         {{translate('Shipping_address')}}
                                                    </label>

                                                </li> -->


                                                <!-- <li>
                                                    <label>
                                                        <input type="radio" name="addressAs" value="office">
                                                        <span><i class="bi bi-briefcase"></i></span>{{translate('Office')}}
                                                    </label>

                                                </li> -->


                                                <!-- <li>
                                                    <label>
                                                        <input type="radio" name="addressAs" value="other">
                                                        <span><i class="bi bi-paperclip"></i></span>{{translate('other')}}
                                                    </label>

                                                </li> -->

                                            <!-- </ul> -->
                                        <!-- </div> -->
                                    <!-- </div> -->
                                    <div class="col-12 d-none">
                                        <div class="d-flex gap-4 align-items-center">
                                            <h6 class="fw-semibold text-muted ">{{translate('Choose_Address_Type')}}</h6>
                                            <div class="d-flex flex-wrap style--two gap-4 ">
                                                <div>
                                                    <label class="d-flex align-items-baseline gap-2 cursor-pointer">
                                                        <input type="radio" name="is_billing" checked="" value="1">
                                                        {{translate('Billing_Address')}}
                                                    </label>
                                                </div>
                                                <div>
                                                    <label class="d-flex align-items-baseline gap-2 cursor-pointer">
                                                        <input type="radio" name="is_billing" value="0" checked>
                                                        {{translate('Shipping_Address')}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label for="name">{{translate('Contact_Person')}}</label>
                                            <input type="text" id="name" class="form-control" name="name" placeholder="{{translate('Full_Name')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label for="phone">{{translate('Phone')}}</label>
                                            <input type="tel" id="phone" class="form-control " onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="10" name="phone" required placeholder="{{translate('Ex:_01xxxxxxxxx')}}">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="form-group  ">
                                            <label for="country">{{translate('Country')}}</label>
                                            <select name="country" id="country" class="form-control" required>
                                                <option value="" selected>{{translate('Select_Country')}}</option>
                                                @foreach($countries as $d)
                                                <option value="{{ $d['name'] }}" data-id="{{ $d['id'] }}">{{ $d['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>  -->
                                  <input type="hidden" name="hidden_country" id="hidden_country" value="India">
                                 <input type="hidden" name="hidden_country_id" value="101">
                                    <div class="col-md-6">
                                        <div class="form-group  ">
                                            <label for="state">{{translate('State')}}</label>
                                            <select name="state" id="state" class="form-control" required>
                                                <option value="" selected>{{translate('Select_State')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label for="city">{{translate('City')}}</label>
                                            <select name="city" id="city" class="form-control" required>
                                                <option value="" selected>{{translate('Select_City')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label for="area">{{translate('Area')}}</label>
                                            <select name="area" id="area" class="form-control" required>
                                                <option value="" selected>{{translate('Select_Area')}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="zip-code">{{translate('Zip_Code')}}</label>
                                            @if($zip_restrict_status)
                                            <select name="zip" id="" class="form-control select2 select_picker" data-live-search="true" required>
                                                @foreach($zip_codes as $code)
                                                <option value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                @endforeach
                                            </select>
                                            @else
                                            <input class="form-control" type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="6" maxlength="6" id="zip" name="zip" required>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-none">
                                        <div class=" ">
                                            <input id="pac-input" class="controls form-control rounded __inline-46 " title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}" />
                                            <div class="dark-support rounded w-100 __h-14rem" id="location_map_canvas"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="address">{{translate('Address')}}</label>
                                            <textarea name="address" id="address" rows="3" class="form-control" placeholder="{{translate('Ex:_1216_Dhaka')}}" required></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" id="latitude"
                                        name="latitude" class="form-control d-inline"
                                        placeholder="Ex : -94.22213" value="{{$default_location?$default_location['lat']:0}}" required readonly>
                                    <input type="hidden"
                                        name="longitude" class="form-control"
                                        placeholder="Ex : 103.344322" id="longitude" value="{{$default_location?$default_location['lng']:0}}" required>
                                    <div class="col-12">
                                        <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
                                            <label class="custom-checkbox"></label>
                                            <div class="d-flex justify-content-end gap-3">
                                                <button type="reset" class="btn btn-secondary">{{translate('Reset')}}</button>
                                                <button type="submit" class="btn btn-primary">{{translate('Add_Address')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Modal Free Delivery-->
<div class="modal fade" id="exampleModalfree" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Free Delivery</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Alpha Elite Members, enjoy the luxury of Free Delivery on Same-Day, One-Day, and Two-Day orders, making every purchase swift and cost-free."</p>
                <p>"Upgrade to Alpha Lite Membership for Free Delivery on One-Day and Two-Day shipping, ensuring your orders reach you promptly without the additional cost."</p>
                <p>"Non-Alpha members, seize the opportunity! Join Alpha E-commerce for Free Delivery on Standard Delivery, saving ₹40 on each order and making shopping even more affordable."</p>
            </div>

        </div>
    </div>
</div>


<!-- Non- Returnable-->

<div class="modal fade" id="exampleModalReturnable" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Non- Returnable</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Attention Alpha Shoppers! Selected items under our exclusive Alpha Membership are marked as 'Non-Returnable,' ensuring quality assurance and a streamlined shopping experience."</p>

                <p>"For Alpha Members: Some products are tagged as 'Non-Returnable' to guarantee your satisfaction. Please review product details before purchase for a hassle-free shopping journey."</p>
                <p>"Alpha Exclusive Alert: Certain items in your cart are 'Non-Returnable' to maintain product integrity. Check specifications prior to checkout, ensuring you make confident choices in your Alpha E-commerce experience.</p>
            </div>

        </div>
    </div>
</div>



<!-- Policy-->

<div class="modal fade" id="exampleModalPolicy" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warranty Policy</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Explore worry-free shopping with Alpha E-commerce! Our Warranty Policy ensures peace of mind, offering comprehensive coverage for your purchases, so you can shop with confidence."</p>
                <p>"Alpha Membership comes with a robust Warranty Policy – enjoy hassle-free protection for your products. We stand by the quality, ensuring your satisfaction and product longevity."</p>

                <p>"Rest easy with Alpha E-commerce! Our Warranty Policy guarantees your purchases are covered, providing you with reliable protection and support. Shop smart, shop with assurance.</p>
            </div>

        </div>
    </div>
</div>


<!-- Policy-->

<div class="modal fade" id="exampleModalBrand" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Top Brand</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>"Discover luxury and quality – Alpha E-commerce proudly presents our curated selection of Top Brands! Elevate your style and trust in the best names in the industry."</p>
                <p>"Unveiling excellence with our Top Brand collection at Alpha E-commerce! Explore renowned labels for unparalleled craftsmanship and trendsetting designs. Your journey to elevated shopping starts here."</p>
                <p>"Indulge in sophistication with Alpha E-commerce's Top Brands. Elevate your lifestyle as you explore our carefully curated selection, showcasing the epitome of </p>
            </div>

        </div>
    </div>
</div>


<script>
    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        input = $(this).parent().find("input");
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>
<script> 
$(document).on('change','select[name=country]', function() {
            let id = $('select[name=country] option:selected').data('id')
                $('#hidden_country').val(countryName);
                $('#hidden_country_id').val(countryId);
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
                            html += `<option value="${elm.id}">${elm.name}</option>`
                        })
                        $('select[name=city]').html(html)
                    }
                }
            });
        })
        $(document).on('change','select[name=city]', function() {
            let id = $('select[name=city] option:selected').data('id')
            $.ajax({
                type: "POST",
                url: "{{ route('seller.area.list') }}",
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
                        let html = "<option value=''>Area</option>"
                        $.each(response.data, function(ind,elm) {
                            html += `<option value="${elm.id}">${elm.name}</option>`
                        })
                        $('select[name=area]').html(html)
                    }
                }
            });
        })

    </script>