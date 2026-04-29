@php
    /* Central app has no business_settings; translate() otherwise hits Helpers::default_lang() → DB. */
    if (! session()->has('local')) {
        session()->put('local', 'en');
        \Illuminate\Support\Facades\Session::put('direction', 'ltr');
    }
@endphp
<style>
    html, body {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    body {
        width: 100%;
    }
    .customLogo {
        width: 150px;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ \App\CPU\translate('Super Admin | Login') }}</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/back-end/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/back-end/vendor/icon-set/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/back-end/css/theme.minc619.css') }}?v=1.0">
    <link rel="stylesheet" href="{{ asset('assets/back-end/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/back-end/css/toastr.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"/>
</head>
<body>

<main id="content" role="main" class="main login-page">
    <div class="position-fixed top-0 right-0 left-0 bg-img-hero __inline-1"
         style="background-image: url({{ asset('assets/back-end/svg/components/abstract-bg-4.svg') }});">
        <figure class="position-absolute right-0 bottom-0 left-0">
            <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1921 273">
                <polygon fill="#fff" points="0,273 1921,273 1921,0 "/>
            </svg>
        </figure>
    </div>

    <div class="container py-sm-5">
        <label class="badge badge-soft-success float-right __inline-2 d-none">{{ \App\CPU\translate('Software version') }}
            : {{ env('SOFTWARE_VERSION') }}</label>

        <div class="card login-card">
            <div class="card-body">
                <div class="col-md-12">
                    <div id="step1" class="step active">
                        <div class="row">
                            <div class="col-md-6 auth-left d-flex align-items-center justify-content-end">
                                <div class="log-in-bar">
                                    <img src="{{ asset('assets/back-end/img/login-img/login-abms.png') }}" alt=""
                                         style="height: 500px;object-fit: contain;"/>
                                </div>
                            </div>
                            <div class="col-md-6 auth-right">
                                <div class="mb-5 col-md-12 mx-auto">
                                    <div class="card-body">
                                        <form id="form-id" action="{{ route('super-admin.auth.submit') }}" method="post">
                                            @csrf
                                            <div class="text-left">
                                                <div class="mb-5">
                                                    <img class="customLogo"
                                                         src="{{ asset('assets/back-end/img/login-img/login-abms.png') }}"
                                                         alt=""/>
                                                    <h2 class="mt-4">{{ \App\CPU\translate('Sign in to your account') }}</h2>
                                                    <p class="text-muted small mb-0">{{ \App\CPU\translate('Platform super admin') }}</p>
                                                </div>
                                            </div>

                                            <div class="js-form-message form-group login-form">
                                                <span>
                                                    <input type="email"
                                                           class="balloon form-control form-control-lg"
                                                           name="email"
                                                           id="signinSrEmail"
                                                           tabindex="1"
                                                           placeholder="email@address.com"
                                                           value="{{ old('email') }}"
                                                           required
                                                           autocomplete="username"/>
                                                    <label for="signinSrEmail">{{ \App\CPU\translate('your_email') }}</label>
                                                </span>
                                            </div>

                                            <div class="js-form-message form-group login-form">
                                                <div class="input-group input-group-merge">
                                                    <input type="password"
                                                           class="js-toggle-password form-control form-control-lg balloon"
                                                           name="password"
                                                           id="signupSrPassword"
                                                           placeholder="8+ characters required"
                                                           tabindex="1"
                                                           required
                                                           autocomplete="current-password"
                                                           data-hs-toggle-password-options='{"target": "#changePassTarget","defaultClass": "tio-hidden-outlined","showClass": "tio-visible-outlined","classChangeTarget": "#changePassIcon"}'/>
                                                    <label class="input-label" for="signupSrPassword" tabindex="0">
                                                        <span class="d-flex justify-content-between align-items-center">
                                                            {{ \App\CPU\translate('password') }}
                                                        </span>
                                                    </label>
                                                    <div id="changePassTarget" class="input-group-append">
                                                        <a class="input-group-text" href="javascript:">
                                                            <i id="changePassIcon" class="tio-visible-outlined"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="termsCheckbox"
                                                           name="remember" value="1"/>
                                                    <label class="custom-control-label text-muted" for="termsCheckbox">
                                                        {{ \App\CPU\translate('remember_me') }}
                                                    </label>
                                                </div>
                                            </div>

                                            @php
                                                try {
                                                    $recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha');
                                                } catch (\Throwable $e) {
                                                    $recaptcha = ['status' => 0];
                                                }
                                            @endphp
                                            @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                                <div id="recaptcha_element" class="w-100" data-type="image"></div>
                                                <br/>
                                            @endif

                                            <button type="submit" class="btn btn-lg btn-block btn--primary" tabindex="1">
                                                {{ \App\CPU\translate('sign_in') }}
                                            </button>
                                        </form>
                                    </div>
                                    @if(env('APP_MODE') == 'demo')
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-10">
                                                    <span>{{ \App\CPU\translate('Use credentials from your seeder') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="{{ asset('assets/back-end/js/jquery.js') }}"></script>
<script src="{{ asset('assets/back-end/js/theme.min.js') }}"></script>
<script src="{{ asset('assets/back-end/js/toastr.js') }}"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{ $error }}', 'Error', {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<script>
    $(document).on('ready', function () {
        $('.js-toggle-password').each(function () {
            new HSTogglePassword(this).init()
        });
    });
</script>

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
        $("#form-id").on('submit', function (e) {
            var response = grecaptcha.getResponse();
            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{ \App\CPU\translate('Please check the recaptcha') }}");
            }
        });
    </script>
@endif
</body>
</html>
