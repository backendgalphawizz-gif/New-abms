<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{\App\CPU\translate('seller_login')}}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/style.css">
    <meta name="_token" content="{{ csrf_token() }}">
</head>

<body>
<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main" class="main login-page-seller">
    <div class="position-fixed top-0 right-0 left-0 bg-img-hero __h-32rem"
         style="background-image: url({{asset('public/assets/admin')}}/svg/components/abstract-bg-4.svg);">
        <!-- SVG Bottom Shape -->
        <figure class="position-absolute right-0 bottom-0 left-0">
            <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1921 273">
                <polygon fill="#fff" points="0,273 1921,273 1921,0 "/>
            </svg>
        </figure>
        <!-- End SVG Bottom Shape -->
    </div>

    <!-- Content -->
    <div class="container py-5 py-sm-7">
        @php($e_commerce_logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
        <a class="d-flex justify-content-center mb-5" href="javascript:">
            <!-- <img class="z-index-2" height="40" src="{{asset("storage/app/public/company/".$e_commerce_logo)}}" alt="Logo"
                 onerror="this.src='{{asset('public/assets/back-end/img/400x400/img2.jpg')}}'"> -->
        </a>

        <div class="card card-lg mb-5">
             <!-- Card -->
                    <div class="card-body">

        <div class="row justify-content-center">
            
        <!-- col-5 Start -->
                 <div class="col-md-6 sellerlogindiv">
                    <div class="log-in-bar">
                    <img width="100%" class="dark-support" src="{{asset('public/assets/back-end/img/login-img/login-img.webp')}}" alt="">
                                <div class="caption-login">
                                <img src="{{asset('public/assets/back-end/img/login-img/logo-for-company.png')}}" alt="">
                                <h2>Welcome to the Vendor Panel</h2>
                                <!-- <p> Sign in to access exclusive features and manage your account effortlessly.</p> -->
                            </div>
                        </div> 
                    </div>

         <!-- col-5 end -->


         <div class="col-md-6 d-flex align-items-bash pt-5">
               
                      <div class="col-md-12 mx-auto">
                                    <div class="text-left">
                                        <div class="mb-5">
                                            <img src="{{asset('public/assets/back-end/img/login-img/logo.png')}}" alt="" />
                                            <h1 class="display-4">{{\App\CPU\translate('sign_in')}}</h1>
                                            <h1 class="h4 text-gray-900 mb-4">{{\App\CPU\translate('welcome_back_to_seller_login')}}</h1>
                                        
                                        </div>

                                    </div>

                                    <h3>Login with OTP</h3>
                                    <nav style="border: none; margin-bottom: 0;">
                                        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                            
                                            <button class="nav-link w-50 active" style="display: none;" id="nav-phone-tab" data-bs-toggle="tab" data-bs-target="#nav-phone" type="button" role="tab" aria-controls="nav-phone" aria-selected="true">
                                                Login via Phone
                                            </button>
                                            <button style="display: none;" class="nav-link active w-50" id="nav-email-tab" data-bs-toggle="tab" data-bs-target="#nav-email" type="button" role="tab" aria-controls="nav-email" aria-selected="false">
                                                Login via Email
                                            </button>
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade active show " id="nav-phone" role="tabpanel" aria-labelledby="nav-phone-tab">
                                            <form id="login-with-otp-form"  action="{{route('seller.register.login-with-otp')}}" method="post" >
                                                @csrf

                                                <!-- Form Group -->
                                                <div class="js-form-message form-group">
                                                    <label class="input-label" for="signinSrPhone" tabindex="-1">{{\App\CPU\translate('Phone')}}</label>
                                                    <div class="d-flex">
                                                    <input type="text" class="form-control form-control-lg" name="phone" id="signinSrPhone"
                                                        tabindex="1" placeholder="+91987654321" aria-label="7896543211"
                                                        required data-msg="Please enter a valid phone number."
                                                        onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                                        minlength="10" maxlength="10"
                                                    >
                                                    <button type="button" class="btn btn--primary w-25 btn-sm send-otp form-control pull-right">Send OTP</button>
                                                </div>
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="js-form-message form-group" tabindex="-1">
                                                    <label class="input-label" for="signupSrPassword" tabindex="-1">
                                                        <span class="d-flex justify-content-between align-items-center" tabindex="-1">
                                                          {{\App\CPU\translate('OTP')}}
                                                        </span>
                                                    </label>

                                                    <div class="input-group input-group-merge" tabindex="-1">
                                                        <input type="otp" class="js-toggle-otp form-control form-control-lg"
                                                            name="otp" id="signupSrotp" placeholder="Enter OTP"
                                                            tabindex="1" readonly required
                                                        >
                                                    </div>
                                                </div>
                                                <!-- End Form Group -->

                                                {{-- recaptcha --}}
                                                @php($recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha'))
                                                @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                                    <div id="recaptcha_element" class="w-100" data-type="image"></div>
                                                    <br/>
                                                @else
                                                    {{-- 
                                                        <div class="row py-2 d-none">
                                                            <div class="col-6 pr-0">
                                                                <input type="text" class="form-control __h-40" name="default_recaptcha_id_seller_login" value=""
                                                                    placeholder="{{\App\CPU\translate('Enter captcha value')}}" class="border-0" autocomplete="off">
                                                            </div>
                                                            <div class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                                                <a onclick="javascript:re_captcha();"  class="d-flex align-items-center align-items-center">
                                                                    <img src="{{ URL('/seller/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_seller_login') }}" class="rounded __h-40" id="default_recaptcha_id">
                                                                <i class="tio-refresh position-relative cursor-pointer p-2"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    --}}
                                                @endif

                                                <button type="submit" class="btn btn-lg btn-block btn--primary">{{\App\CPU\translate('sign_in')}}</button>
                                                <div class="text-center my-2">
                                                <p>Don’t Have an account? <a href="{{ route('shop.apply') }}">Sign Up</a></p>
                                            </div>
                                            </form>
                                        </div>
                                        <div style="display: none;" class="tab-pane fade active show" id="nav-email" role="tabpanel" aria-labelledby="nav-email-tab">
                                            <form id="form-id"  action="{{route('seller.auth.login')}}" method="post">
                                                @csrf
                                                <!-- Form Group -->
                                                <div class="js-form-message form-group">
                                                    <label class="input-label" for="signinSrEmail" tabindex="-1">{{\App\CPU\translate('your_email')}}</label>
                                                    <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail"
                                                           tabindex="1" placeholder="email@address.com" aria-label="email@address.com"
                                                           required data-msg="Please enter a valid email address.">
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="js-form-message form-group" tabindex="-1">
                                                    <label class="input-label" for="signupSrPassword" tabindex="-1">
                                                        <span class="d-flex justify-content-between align-items-center" tabindex="-1">
                                                          {{\App\CPU\translate('password')}}
                                                                
                                                        </span>
                                                    </label>

                                                    <div class="input-group input-group-merge" tabindex="-1">
                                                        <input type="password" class="js-toggle-password form-control form-control-lg"
                                                               name="password" id="signupSrPassword" placeholder="8+ characters required"
                                                               aria-label="8+ characters required" tabindex="1" required
                                                               data-msg="Your password is invalid. Please try again."
                                                               data-hs-toggle-password-options='{
                                                                         "target": "#changePassTarget",
                                                                "defaultClass": "tio-hidden-outlined",
                                                                "showClass": "tio-visible-outlined",
                                                                "classChangeTarget": "#changePassIcon"
                                                                }'>
                                                        <div id="changePassTarget" class="input-group-append">
                                                            <a class="input-group-text" href="javascript:">
                                                                <i id="changePassIcon" class="tio-visible-outlined"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Checkbox -->
                                                <div class="form-group d-flex justify-content-between align-items-center">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="termsCheckbox"
                                                               name="remember">
                                                        <label class="custom-control-label text-muted" for="termsCheckbox">
                                                          {{\App\CPU\translate('remember_me')}}
                                                        </label>
                                                    </div>
                                                    <a href="{{route('seller.auth.forgot-password')}}"> {{\App\CPU\translate('Forget Password')}}</a>
                                                </div>
                                                
                                                <!-- End Checkbox -->
                                                {{-- recaptcha --}}
                                                @php($recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha'))
                                                @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                                    <div id="recaptcha_element" class="w-100" data-type="image"></div>
                                                    <br/>
                                                @else
                                                    {{-- 
                                                        <div class="row py-2 d-none">
                                                            <div class="col-6 pr-0">
                                                                <input type="text" class="form-control __h-40" name="default_recaptcha_id_seller_login" value=""
                                                                    placeholder="{{\App\CPU\translate('Enter captcha value')}}" class="border-0" autocomplete="off">
                                                            </div>
                                                            <div class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                                                <a onclick="javascript:re_captcha();"  class="d-flex align-items-center align-items-center">
                                                                    <img src="{{ URL('/seller/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_seller_login') }}" class="rounded __h-40" id="default_recaptcha_id">
                                                                <i class="tio-refresh position-relative cursor-pointer p-2"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    --}}
                                                @endif

                                                <button type="submit" class="btn btn-lg btn-block btn--primary">{{\App\CPU\translate('sign_in')}}</button>
                                                <div class="text-center my-2">
                                                <p>Don’t Have an account? <a href="{{ route('shop.apply') }}">Sign Up</a></p>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- Form -->
                        <!-- End Form -->
                      </div>
               
                    </div>
                    @if(env('APP_MODE')=='demo')
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-10">
                                    <span>{{\App\CPU\translate('Email')}} : test.seller@gmail.com</span><br>
                                    <span>{{\App\CPU\translate('Password')}} : 12345678</span>
                                </div>
                                <div class="col-2">
                                    <button class="btn btn--primary" onclick="copy_cred()"><i class="tio-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- End Card -->
            </div>

         <!-- col-5 end -->
            
        </div>
    </div>
    <!-- End Content -->
</main>
<!-- ========== END MAIN CONTENT ========== -->


<!-- JS Implementing Plugins -->
<script src="{{asset('public/assets/back-end')}}/js/vendor.min.js"></script>
<!-- JS Front -->
<script src="{{ theme_asset('assets/js/bootstrap.bundle.min.js') }}"></script> 

<script src="{{ theme_asset('assets/js/main.js') }}"></script>
<script src="{{ theme_asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{asset('public/assets/back-end')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/back-end')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<!-- JS Plugins Init. -->
<script>
    $(document).on('ready', function () {
        // INITIALIZATION OF SHOW PASSWORD
        // =======================================================
        $('.js-toggle-password').each(function () {
            new HSTogglePassword(this).init()
        });

        // INITIALIZATION OF FORM VALIDATION
        // =======================================================
        $('.js-validate').each(function () {
            $.HSCore.components.HSValidation.init($(this));
        });
    });
</script>

{{-- recaptcha scripts start --}}
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': '{{ \App\CPU\Helpers::get_business_settings('recaptcha')['site_key'] }}'
            });
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script>
        $("#form-id").on('submit',function(e) {
            var response = grecaptcha.getResponse();

            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{\App\CPU\translate('Please check the recaptcha')}}");
            }
        });
    </script>
@else
    <script type="text/javascript">
        function re_captcha() {
            $url = "{{ URL('/seller/auth/code/captcha') }}";
            $url = $url + "/" + Math.random()+'?captcha_session_id=default_recaptcha_id_seller_login';
            document.getElementById('default_recaptcha_id').src = $url;
            console.log('url: '+ $url);
        }
    </script>
@endif
{{-- recaptcha scripts end --}}

@if(env('APP_MODE')=='demo')
    <script>
        function copy_cred() {
            $('#signinSrEmail').val('test.seller@gmail.com');
            $('#signupSrPassword').val('12345678');
            toastr.success('{{\App\CPU\translate("Copied successfully")}}!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endif

<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>

<script>
    let otp = ""
    $(document).on('click', '.send-otp', function () {
        let evt = $(this)
        let mobile = $('input[name=phone]').val()

        if (mobile.length > 9) {
            $.ajax({
                type: "POST",
                url: "{{ route('seller.register.send-seller-otp') }}",
                data: {
                    phone: mobile
                },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
                },
                success: function(response) {
                    if (response.status == true) {
                        $(evt).text('Resend OTP')
                        toastr.success(response.message)

                        // Set OTP value inside the input with id signupSrotp
                        $('#signupSrotp').val(response.otp)
                    } else {
                        toastr.error(response.message)
                    }
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON.message || 'Something went wrong')
                }
            });
        } else {
            toastr.error('Invalid Mobile No')
        }
    });


    $(document).on('click', '.verify-otp', function () {
        let phone = $('input[name=phone]').val()
        let otp = $('input[name=otp]').val()

        $.ajax({
            type: "POST",
            url: "{{ route('seller.register.verify-otp') }}",
            data: {
                phone: phone,
                otp: otp
            },
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
            },
            success: function(response) {
                if (response.status == true) {
                    $(evt).text('Resend OTP')
                    toastr.success(response.message)

                    $('input[name=otp]').val(response.otp)
                } else {
                    toastr.error(response.message)
                }
            },
            error: function (xhr) {
                toastr.error(xhr.responseJSON.message || 'Verification failed')
            }
        });
    });


    $(document).on('submit','#login-with-otp-form', function(e) {
        e.preventDefault()
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
            },
            dataType: "json",
            success: function (response) {
                if(response.status) {
                    toastr.success(`${response.message}`)

                    setTimeout(() => {
                        window.location.reload()
                    }, 2000);

                } else {
                    toastr.error(`${response.message}`)
                }
            }
        });
    })

</script>

</body>
</html>

