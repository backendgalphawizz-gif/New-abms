<style>
    html,body{
        display: flex;
        justify-content: center;
        align-items: center;
        /* height: 100vh; */
    }
    body{
        width: 100%;
    }
    .customLogo{
        width: 150px;
    }
    .cert-login-panel{
        margin-top: 1rem;
        border: 1px solid #d9e4f0;
        border-radius: 10px;
        background: #f8fbff;
        padding: .85rem .95rem;
    }
    .cert-login-panel h6{
        margin: 0 0 .45rem 0;
        font-weight: 700;
        color: #0b7edb;
    }
    .cert-login-list{
        margin: 0;
        padding-left: 1rem;
        max-height: 180px;
        overflow: auto;
    }
    .cert-login-list li{
        font-size: .84rem;
        color: #4f5b67;
        margin-bottom: .2rem;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{\App\CPU\translate('Admin | Login')}}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
</head>

<body>

<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main" class="main login-page ">
    <div class="position-fixed top-0 right-0 left-0 bg-img-hero __inline-1"
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
    <div class="container  py-sm-5">
        <label class="badge badge-soft-success float-right __inline-2 d-none">{{\App\CPU\translate('Software version')}} : {{ env('SOFTWARE_VERSION') }}</label>
        @php($e_commerce_logo = optional(\App\Model\BusinessSetting::where(['type' => 'company_web_logo'])->first())->value)
        <a class="d-flex justify-content-center d-none" href="javascript:">
            <!-- <img class="z-index-2" height="40" src="{{asset("storage/app/public/company/".$e_commerce_logo)}}" alt="Logo"
                 onerror="this.src='{{asset('public/assets/back-end/img/400x400/img2.jpg')}}'"> -->
        </a>
    <div class="card login-card">
        <div class="card-body">
            <!-- STEP WIZARD -->

            <div class="col-md-12">
                <div id="step1" class="step active">
                    <!-- Include form fields for Step 1 -->

                    <div class="row">
                        <div class="col-md-6 auth-left d-flex align-items-center justify-content-end">
                            <div class="log-in-bar">
                                <img src="{{asset('public/assets/back-end/img/login-img/login-abms.png')}}" alt="" style="height: 500px;object-fit: contain;"/>
                                <div class="caption-login">
                                    <!-- <img src="{{asset('public/assets/back-end/img/login-img/logo-for-company.png')}}" alt="" /> -->
                                    <!-- <h2>Welcome to Prime Basket</h2> -->
                                    <!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 auth-right">
                            <!-- Card -->
                            <div class="mb-5 col-md-12 mx-auto">
                                <div class="card-body">
                                    <!-- Form -->
                                    <form id="form-id" action="{{route('admin.auth.login')}}" method="post">
                                        @csrf

                                        <div class="text-left">
                                            <div class="mb-5">
                                                <!-- SEMI LOGO -->
                                                <img class="customLogo" src="{{asset('public/assets/back-end/img/login-img/login-abms.png')}}" alt="" />
                                                <h2 class="mt-4">{{\App\CPU\translate('Sign in to your account')}}</h2>
                                                <!-- <span>({{\App\CPU\translate('Lorem Ipsum is simply dummy text of the printing and typesetting industry.')}})</span> -->
                                            </div>
                                        </div>

                                        <!-- Form Group -->
                                        <div class="js-form-message form-group login-form">
                                        
                                            <span>
                                            <input
                                                type="email"
                                                class="balloon form-control form-control-lg"
                                                name="email"
                                                id="signinSrEmail"
                                                tabindex="1"
                                                placeholder="email@address.com"
                                                aria-label="email@address.com"
                                                required
                                                data-msg="Please enter a valid email address."
                                                autocomplete="off"
                                            />
                                            <label class="" for="signinSrEmail">{{\App\CPU\translate('your_email')}}</label>
                                            </span>
                                        </div>
                                        <!-- End Form Group -->

                                        <!-- Form Group -->
                                        <div class="js-form-message form-group login-form">
                                        

                                            <div class="input-group  input-group-merge">
                                                <input
                                                    type="password"
                                                    class="js-toggle-password form-control form-control-lg balloon"
                                                    name="password"
                                                    id="signupSrPassword"
                                                    placeholder="8+ characters required"
                                                    aria-label="8+ characters required"
                                                    tabindex="1"
                                                    required
                                                    data-msg="Your password is invalid. Please try again."
                                                    data-hs-toggle-password-options='{
                                                                                       "target": "#changePassTarget",
                                                                               "defaultClass": "tio-hidden-outlined",
                                                                               "showClass": "tio-visible-outlined",
                                                                               "classChangeTarget": "#changePassIcon"
                                                                               }'
                                                />
                                                <label class="input-label" for="signupSrPassword" tabindex="0">
                                                <span class="d-flex justify-content-between align-items-center">
                                                    {{\App\CPU\translate('password')}}
                                                </span>
                                            </label>
                                                <div id="changePassTarget" class="input-group-append">
                                                    <a class="input-group-text" href="javascript:">
                                                        <i id="changePassIcon" class="tio-visible-outlined"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Form Group -->

                                        <!-- Checkbox -->
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="termsCheckbox" name="remember" />
                                                <label class="custom-control-label text-muted" for="termsCheckbox">
                                                    {{\App\CPU\translate('remember_me')}}
                                                </label>
                                            </div>
                                        </div>
                                        <!-- End Checkbox -->
                                        {{-- recaptcha --}} @php($recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha')) @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                        <div id="recaptcha_element" class="w-100;" data-type="image"></div>
                                        <br />
                                        @else
                                        <div class="row p-2 d-none">
                                            <div class="col-6 pr-0">
                                                <input type="text" class="form-control form-control-lg border-0" name="default_captcha_value" value="" placeholder="{{\App\CPU\translate('Enter captcha value')}}" autocomplete="off" />
                                            </div>
                                            <div class="col-6 input-icons" class="bg-white rounded">
                                                <a onclick="javascript:re_captcha();">
                                                    <img src="{{ URL('/admin/auth/code/captcha/1') }}" class="input-field w-90 h-75" id="default_recaptcha_id" />
                                                    <i class="tio-refresh icon"></i>
                                                </a>
                                            </div>
                                        </div>
                                        @endif

                                        <button type="submit" class="btn btn-lg btn-block btn--primary" tabindex="1">{{\App\CPU\translate('sign_in')}}</button>
                                         <p class="forgot-text d-none">Forgot Password? </p>
                                    </form>
                                    @if(!empty($isoCertifications))
                                        <div class="cert-login-panel">
                                            <h6>Allotted Certifications</h6>
                                            <ul class="cert-login-list">
                                                @foreach($isoCertifications as $certification)
                                                    <li>{{ $certification }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <!-- End Form -->
                                </div>
                                @if(env('APP_MODE')=='demo')
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-10">
                                            <span>{{\App\CPU\translate('Email')}} : {{\App\CPU\translate('admin@admin.com')}}</span><br />
                                            <span>{{\App\CPU\translate('Password')}} : {{\App\CPU\translate('12345678')}}</span>
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn--primary" onclick="copy_cred()"><i class="tio-copy"></i></button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <button onclick="nextStep()" class="btn btn--primary ml-auto d-bloc k  d-none">Next</button>
                            </div>

                            <!-- End Card -->
                        </div>
                    </div>
                </div>

                <div id="step2" class="step">
                   
                    <!-- Include form fields for Step 2 -->

                     <div class="row">
                         <div class="col-md-5">
                            <div class="log-in-bar">
                                <img src="{{asset('public/assets/back-end/img/login-img/login-bg-set.png')}}" alt="" />
                                <div class="caption-login">
                                    <img src="{{asset('public/assets/back-end/img/login-img/logo-for-company.png')}}" alt="" />
                                    <h2>Welcome to Alpha e-commerce</h2>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div> 
                             
                         </div>
                         <div class="col-md-7 d-flex align-items-center">
                                  <div class="col-md-8 mx-auto">
                                    <div class="log-in-number">

                                        <form>
                                        <div class="text-left">
                                            <div class="mb-5">
                                                <!-- SEMI LOGO -->
                                                <img src="{{asset('public/assets/back-end/img/login-img/semi-logo-for-company.svg')}}" alt="" />
                                                <h2 class="mt-4">{{\App\CPU\translate('Enter your mobile number')}}</h2>
                                                <span>({{\App\CPU\translate('We have sent the 4 digit verification code')}})</span>
                                            </div>
                                        </div>
                                            <div class="login-form">
                                                <span>
                                                    <div class="form-group">
                                                        <input type="text" id="" class="w-100 form-control mobile_code" placeholder="Phone Number"
                                                            name="name">
                                                    </div>
                                                </span>
                                                <div class="login-button">
                                                    <button class="btn-login">
                                                        Login <i class="fa-solid fa-arrow-right"></i>
                                                    </button>
                                                </div>
                                                <div class="text-right">
                                                    <a >
                                                      <p class="forgot-text ">  Forgot Password?</p>
                                                    </a>
                                                </div>


                                                </div>
                                             </form>
                                        </div>  
                                        <div class="d-flex justify-content-end gap-5 mt-5">
                                            <button onclick="prevStep()" class="btn btn--primary d-block">Previous</button>
                                            <button onclick="nextStep()" class="btn btn--primary  d-block">Next</button>
                                        </div>
                                    </div>  
                                
                                </div> 
                     </div>
         
                </div>

                <div id="step3" class="step">
          
                    <!-- Include form fields for Step 3 -->
                    <div class="row">
                         <div class="col-md-5">
                         <div class="log-in-bar">
                                <img src="{{asset('public/assets/back-end/img/login-img/login-bg-set.png')}}" alt="" />
                                <div class="caption-login">
                                    <img src="{{asset('public/assets/back-end/img/login-img/logo-for-company.png')}}" alt="" />
                                    <h2>Welcome to Alpha e-commerce</h2>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div> 
                         </div>

                          <div class="col-md-7 d-flex align-items-center">
                                      <div class="col-md-8 mx-auto">
                                      <div class="otp__section">
                                                <div class="w-75 mx-auto">
                                                    <h2 class="auth-heading">Enter verification code</h2>
                                                    <p class="">Enter the OTP sent to +91 9556654328.</p>
                                                </div>
                                          
                                                    <form>
                                                        <div class="login-form">
                                                            <div class="otp-input-fields">
                                                                <input type="number" class="otp__digit otp__field__1">
                                                                <input type="number" class="otp__digit otp__field__2">
                                                                <input type="number" class="otp__digit otp__field__3">
                                                                <input type="number" class="otp__digit otp__field__4">
                                                          
                                                            </div>
                                                            <div class="login-button">
                                                                <a class="btn-login w-75 mx-auto" id="Verify"> Verify </a>
                                                            </div>
                                                            <div class="text-center mt-3">
                                                                <p> Didn’t Received OTP? <a href="" class="text-green-light">Resend OTP</a></p>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="text-center">
                                                        <p class="havent-account mx-auto">Don’t Have an account? <a class="text-green-light" > Sign In</a class=""></p>
                                                    </div>
                                        
                                            </div>
                                            <div class="d-flex justify-content-end gap-5 mt-5">
                                                <button onclick="prevStep()" class="btn btn--primary d-block">Previous</button>
                                             <button onclick="nextStep()" class="btn btn--primary  d-block">Next</button>
                                             </div>
                                     </div>
                                      </div>
                              </div>

                                 

                </div>

                <div id="step4" class="step">
                    <!-- Include form fields for Step 4 -->
                      <div class="row">
                          <div class="col-md-5">
                          <div class="log-in-bar">
                                <img src="{{asset('public/assets/back-end/img/login-img/login-bg-set.png')}}" alt="" />
                                <div class="caption-login">
                                    <img src="{{asset('public/assets/back-end/img/login-img/logo-for-company.png')}}" alt="" />
                                    <h2>Welcome to Alpha e-commerce</h2>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-7 d-flex align-items-center">
                                 <div class="col-md-8 mx-auto">
                                        <form action="">

                                        <div class="">
                                        <img src="{{asset('public/assets/back-end/img/login-img/semi-logo-for-company.svg')}}" alt="" />
                                            <h2>Enter new password</h2>
                                            <p>Set your password your new password so you can access alpha e-commerce</p>
                                        </div>
                                                       <div class="login-form mb-4">
                                                            <span>
                                                            <input type="password" name="password" class="toggleInput form-control balloon" data-toggle="password" placeholder="Create New password">
                                                            <label for="">Create New password</label>
                                                                <i class="bi bi-eye-slash togglePassword"></i>
                                                               
                                                            </span>
                                                       </div>

                                                       <div class="login-form ">
                                                            <span>
                                                            <input type="password" name="password" class="toggleInput form-control balloon" data-toggle="password" placeholder="Confirm password">
                                                            <label for="">Confirm password</label>
                                                            <i class="bi bi-eye-slash togglePassword"></i>
                                                          
                                                            </span>
                                                       </div>
                                        </form>     <div class="login-button">
                                                    <button class="btn-login">
                                                        Send 
                                                    </button>
                                                </div>    
                                 </div>
                          </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- End Content -->
</main>
<!-- ========== END MAIN CONTENT ========== -->


<!-- JS Implementing Plugins -->
<script src="{{asset('public/assets/back-end')}}/js/vendor.min.js"></script>

<!-- JS Front -->
<script src="{{asset('public/assets/back-end')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/back-end')}}/js/toastr.js"></script>
<script src="{{asset('public/assets/back-end')}}/js/jsdeliver-npm.js"></script>
<script src="{{asset('public/assets/back-end')}}/js/tell-input.js"></script>
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
            $url = "{{ URL('/admin/auth/code/captcha') }}";
            $url = $url + "/" + Math.random();
            document.getElementById('default_recaptcha_id').src = $url;
            console.log('url: '+ $url);
        }
    </script>
@endif
{{-- recaptcha scripts end --}}

@if(env('APP_MODE')=='demo')
    <script>
        function copy_cred() {
            $('#signinSrEmail').val('admin@admin.com');
            $('#signupSrPassword').val('12345678');
            toastr.success('Copied successfully!', 'Success!', {
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
     let currentStep = 1;

function showStep(stepNumber) {
  document.querySelectorAll('.step').forEach(step => step.classList.remove('active'));
  document.getElementById(`step${stepNumber}`).classList.add('active');
}

function nextStep() {
  if (currentStep < 4) {
    currentStep++;
    showStep(currentStep);
  }
}

function prevStep() {
  if (currentStep > 1) {
    currentStep--;
    showStep(currentStep);
  }
}

function submitForm() {
  // Implement form submission logic
}

</script>

<script>
// -----Country Code Selection
$(".mobile_code").intlTelInput({
    initialCountry: "in",
    separateDialCode: true,
    // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
});
</script>

<script>
    const togglePasswordIcons = document.querySelectorAll('.togglePassword ');
    const toggleInputs = document.querySelectorAll('.toggleInput.form-control.balloon');

    togglePasswordIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const inputField = icon.previousElementSibling; // Assuming the icon is placed after the input field
            const dataType = inputField.getAttribute('data-toggle');

            if (dataType === 'password') {
                const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
                inputField.setAttribute('type', type);
                icon.classList.toggle('bi-eye');
            }
        });
    });
</script>
</body>
</html>

