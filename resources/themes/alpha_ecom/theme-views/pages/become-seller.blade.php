@extends('theme-views.layouts.app')

@section('title', translate('Become_Seller').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')

<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 pb-3">
    <div class="page-title overlay py-5 __opacity-half" data-bg-img="{{theme_asset('assets/img/media/page-title-bg.png')}}">
        <div class="container">
            <h1 class="absolute-white text-center">{{translate('Become Seller')}}</h1>
        </div>
    </div>
    <!-- <div class="container">
        <div class="card my-4">
            <div class="card-body p-lg-4 text-dark page-paragraph">
              
            </div>
        </div>
    </div> -->

    <section class="login-page logindiv">
    <div class="container">
        <div class="row gutter-y-30 align-items-center">
            <div class="col-xl-6 loginimgsection">
                <div class="login-page__left wow fadeInLeft animated" data-wow-duration="1500ms" data-wow-delay="500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 500ms; animation-name: fadeInLeft;">
                    <div class="login-page__thumb real-image">
                        <img src="{{ theme_asset('assets/images/loginVector.png')}}" alt="login image" class="loginimg">
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="login-page__right wow fadeInRight animated" data-wow-duration="1500ms" data-wow-delay="500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 500ms; animation-name: fadeInRight;">
                    <div class="login-page__login-box loginpagediv">
                        <div class="login-page__content">
                            <div class="login-page__logo">
                                <a href="index.html">
                                    <img src="{{ theme_asset('assets/images/primeLogo.png')}}" width="220" alt="logo" class="loginpagelogo">
                                </a>
                            </div>
                            <h2 class="login-page__title">Nice to see you again</h2>

                            <!-- OTP FORM -->
                            <form id="otpForm" method="post">
                                <div class="login-page__group">
                                    <!-- Mobile Input -->
                                    <div class="login-page__input-box">
                                        <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                                        <input type="text" id="mobile" name="mobile" maxlength="10" minlength="10" class="form-control" placeholder="+91XXXXXXXXXX" required="">
                                    </div>

                                    <!-- OTP Input (Initially Hidden) -->
                                    <div class="login-page__input-box" id="otpBox" style="display:none;">
                                        <label for="otp" class="form-label">Enter OTP <span class="text-danger">*</span></label>
                                        <!-- <input type="number" id="otp" name="otp" maxlength="4" minlength="4" pattern="\d{4}" class="form-control" placeholder="Enter OTP"> -->
                                        <input type="text" id="otp" name="otp" maxlength="4" class="form-control" placeholder="Enter OTP" inputmode="numeric" pattern="\d{4}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);">

                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="text-center"> <button type="submit" id="sendOtpBtn" class="btn btn-outline-light btn-size mt-4 sendotpdiv w-100" style="border: 1px solid grey;color: grey;">Send OTP</button></div>
                                    <!-- <div class="text-center"> <button type="button" id="verifyOtpBtn" class="btn btn-success btn-size mt-4" style="display:none;">Verify OTP</button></div> -->
                                    <!-- <div class="text-center">
                                        <button type="button" class="btn btn-outline-secondary btn-size mt-2" style="display:none;">Resend OTP</button>
                                    </div> -->
                                </div>
                            </form>

                            <p class="login-page__form__text">Don't have an account yet?<a data-bs-toggle="modal" data-bs-target="#exampleModalsignup">Register</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade signupModal" id="exampleModalsignup" tabindex="-1" aria-labelledby="exampleModalLabelsingup" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content registermodal">
                        <div class="modal-header">
                            <img src="{{ theme_asset('assets/images/primeLogo.png')}}" alt="logo" class="registerimg">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="login-wrapper">
                            <div class="modalImg">
                                <img src="assets/web/images/modalImg.jpg" alt="">
                            </div>
                            <div class="loginbox">
                                <div class="login-auth">
                                    <div class="login-auth-wrap">
                                        <h1>Register Your Account</h1>

                                        <form action="#" method="post" enctype="multipart/form-data" id="sign-up-form" autocomplete="off">

                                            <div class="input-block">
                                                <label class="form-label">Mobile <span class="text-danger">*</span></label>
                                                <div class="signupPnputFeald">
                                                    <input type="text" name="mobile" maxlength="10" minlength="10" class="form-control" placeholder="+91XXXXXXXXXX" inputmode="numeric" pattern="\d{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);" id="mobileregister" required="">
                                                    <button type="button" class="btn btn-primary">Get OTP</button>
                                                </div>
                                                <span id="mobile-error" class="text-danger"></span>
                                            </div>

                                            <div class="input-block" id="otp-section" style="display: none;">
                                                <label class="form-label">Enter OTP <span id="otpDisplay" class="text-danger">*</span></label>
                                                <div class="signupPnputFeald">
                                                    <input type="text" name="otp" class="form-control" placeholder="Enter OTP" id="register_otp" maxlength="6" required="">
                                                    <button type="button" class="btn btn-primary mt-2" >Verify OTP</button>
                                                </div>
                                                <span id="otp-error" class="text-danger"></span>
                                            </div>

                                            <div class="input-block verifyDiv d-none">
                                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                                <input type="text" name="username" minlength="3" maxlength="50" class="form-control" placeholder="Your Name" onkeypress="return (event.charCode == 32 || (event.charCode &gt;= 65 &amp;&amp; event.charCode &lt;= 90) || (event.charCode &gt;= 97 &amp;&amp; event.charCode &lt;= 122))" required="">
                                            </div>
                                            <div class="input-block verifyDiv d-none">
                                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" placeholder="Enter your Email" required="">
</div>

                                            <div class="input-block verifyDiv d-none">
                                                <label class="form-label">Referral Code (Optional)</label>
                                                <input type="text" name="referral_code" class="form-control" placeholder="Enter referral code (Optional)">
                                            </div>

                                            <button type="submit" id="signup-button1" class="btn btn-primary w-100 btn-size mt-1 d-none">Sign Up</button>
                                        
<!-- 
                                            <div class="dont-have mt-4">Already have an Account? <a href="https://pristin.pristineandaman.com/login">Log In</a></div> -->

                                            <!-- <div class="bottomLabel">
                                                <span>Think Andaman! Think Pristine Andaman!!</span>
                                                <li>Request to carefully Read the conditions and policies.<br> <a href="#">Terms and conditions</a> and <a href="https://pristin.pristineandaman.com/privacy-policy">Privacy &amp; policy.</a>
                                                </li>
                                            </div> -->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
<!-- End Main Content -->

@endsection
