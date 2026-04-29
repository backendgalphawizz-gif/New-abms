@extends('layouts.front-end.auth-cab')
@section('title', \App\CPU\translate('Login'))

@section('content')
@php
    $recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha');
    $otpStep = session()->has('web_customer_login_otp') && session()->has('web_customer_login_phone');
    $pendingPhone = session('web_customer_login_phone', old('phone'));
@endphp

@if($otpStep)
    <h1 class="auth-h1">{{ \App\CPU\translate('auth_login_title') }}</h1>
    <p class="auth-sub">{{ \App\CPU\translate('auth_login_subtitle') }}</p>

    <form method="post" action="{{ route('customer.auth.login-verify-otp') }}" id="cab-login-otp-form">
        @csrf
        <input type="hidden" name="phone" value="{{ $pendingPhone }}">
        <input type="hidden" name="otp" id="cab-otp-combined" value="">

        <div class="auth-field">
            <label class="auth-label" for="cab-otp-0">
                {{ \App\CPU\translate('OTP') }}
                @if(session()->has('web_customer_login_otp'))
                    <span style="margin-left:.5rem;color:var(--auth-accent);font-weight:600;">
                        {{ session('web_customer_login_otp') }}
                    </span>
                @endif
            </label>
            <div class="auth-otp-row" role="group" aria-label="OTP digits">
                @for($i = 0; $i < 4; $i++)
                    <input type="text" inputmode="numeric" maxlength="1" pattern="[0-9]*"
                           class="auth-otp-box" id="cab-otp-{{ $i }}" autocomplete="one-time-code" aria-label="Digit {{ $i + 1 }}">
                @endfor
            </div>
        </div>

        <div class="auth-resend">
            <a href="#" id="cab-resend-otp">{{ \App\CPU\translate('Resend_OTP') }}</a>
            <span class="timer" id="cab-otp-timer">00:30</span>
        </div>

        @if ($errors->any())
            <p class="text-danger small mb-3" role="alert">@foreach($errors->all() as $err){{ $err }} @endforeach</p>
        @endif

        <button class="auth-btn-primary" type="submit">{{ \App\CPU\translate('sign_in') }}</button>
    </form>

    <form method="post" action="{{ route('customer.auth.login-request-otp') }}" id="cab-resend-form" class="d-none">
        @csrf
        <input type="hidden" name="phone" value="{{ $pendingPhone }}">
    </form>

    <div class="auth-muted-row" style="margin-top:1rem;">
        <a href="{{ route('customer.login.short', ['cancel_otp' => 1]) }}" class="auth-btn-ghost" style="margin-top:0;display:inline-block;width:auto;padding-left:1.5rem;padding-right:1.5rem;">{{ \App\CPU\translate('Change') }} {{ \App\CPU\translate('phone') }}</a>
    </div>
@else
    <h1 class="auth-h1">{{ \App\CPU\translate('auth_login_title') }}</h1>
    <p class="auth-sub">{{ \App\CPU\translate('auth_login_subtitle') }}</p>

    <form method="post" action="{{ route('customer.auth.login-request-otp') }}" novalidate>
        @csrf
        <div class="auth-field">
            <label class="auth-label" for="login-phone">{{ \App\CPU\translate('phone') }}</label>
            <input class="auth-inputline" type="tel" id="login-phone" name="phone" inputmode="numeric" autocomplete="tel-national"
                   pattern="[0-9]{10}" maxlength="10" minlength="10" placeholder="{{ \App\CPU\translate('auth_signup_placeholder_phone') }}"
                   value="{{ old('phone') }}" required
                   title="{{ \App\CPU\translate('auth_phone_hint_10_digits') }}">
        </div>
        @if ($errors->any())
            <p class="text-danger small mb-3" role="alert">@foreach($errors->all() as $err){{ $err }} @endforeach</p>
        @endif
        <button class="auth-btn-primary" type="submit">{{ \App\CPU\translate('Send OTP') }}</button>
    </form>

    <div class="auth-muted-row auth-muted-row--with-rule">{{ \App\CPU\translate('auth_new_member') }}</div>
    <a class="auth-btn-ghost" href="{{ route('customer.signup') }}">{{ \App\CPU\translate('Sign_Up') }}</a>

    <details class="auth-details">
        <summary>{{ \App\CPU\translate('sign_in') }} — {{ \App\CPU\translate('email_address') }} &amp; {{ \App\CPU\translate('password') }}</summary>
        <div style="margin-top:1rem;">
            <form class="needs-validation" autocomplete="off" action="{{ route('customer.login.short') }}" method="post" id="form-id-pwd">
                @csrf
                <div class="auth-field">
                    <label class="auth-label" for="si-email">{{\App\CPU\translate('email_address')}} / {{\App\CPU\translate('phone')}}</label>
                    <input class="auth-inputline" type="text" name="user_id" id="si-email" value="{{ old('user_id') }}"
                           placeholder="{{\App\CPU\translate('Enter_email_address_or_phone_number')}}" required>
                </div>
                <div class="auth-field">
                    <label class="auth-label" for="si-password">{{\App\CPU\translate('password')}}</label>
                    <input class="auth-inputline" type="password" name="password" id="si-password" required>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center small mb-2" style="color:var(--auth-muted);">
                    <label class="m-0"><input type="checkbox" class="mr-1" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}> {{\App\CPU\translate('remember_me')}}</label>
                    <a href="{{ route('customer.auth.recover-password') }}" style="color:var(--auth-blue);">{{\App\CPU\translate('forgot_password')}}</a>
                </div>
                @if(isset($recaptcha) && $recaptcha['status'] == 1)
                    <div id="recaptcha_element" class="w-100" data-type="image"></div>
                @else
                    <div class="row auth-captcha-row">
                        <div class="col-6 pr-1">
                            <input type="text" class="form-control form-control-sm" name="default_recaptcha_id_customer_login" value=""
                                   placeholder="{{\App\CPU\translate('Enter captcha value')}}" autocomplete="off">
                        </div>
                        <div class="col-6 pl-1">
                            <a onclick="re_captcha_pwd();" class="d-flex align-items-center">
                                <img src="{{ route('customer.auth.default-captcha', ['tmp' => 1]) }}?captcha_session_id=default_recaptcha_id_customer_login"
                                     class="rounded __h-40" id="customer_login_recaptcha_id" alt="captcha" width="100" height="40">
                            </a>
                        </div>
                    </div>
                @endif
                <button class="auth-btn-primary" style="margin-top:1rem;" type="submit">{{\App\CPU\translate('sign_in')}}</button>
            </form>
        </div>
    </details>
@endif
@endsection

@push('script')
@if($otpStep)
<script>
(function () {
    var boxes = document.querySelectorAll('.auth-otp-box');
    var combined = document.getElementById('cab-otp-combined');
    var form = document.getElementById('cab-login-otp-form');
    var resendA = document.getElementById('cab-resend-otp');
    var resendForm = document.getElementById('cab-resend-form');
    var timerEl = document.getElementById('cab-otp-timer');
    var seconds = 30;

    function syncOtp() {
        var v = '';
        boxes.forEach(function (b) { v += (b.value || '').replace(/\D/g, '').slice(0, 1); });
        combined.value = v;
    }
    boxes.forEach(function (box, i) {
        box.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 1);
            syncOtp();
            if (this.value && i < boxes.length - 1) boxes[i + 1].focus();
        });
        box.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && i > 0) boxes[i - 1].focus();
        });
    });
    if (boxes[0]) boxes[0].focus();

    function tick() {
        var m = Math.floor(seconds / 60);
        var s = seconds % 60;
        timerEl.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        if (seconds <= 0) {
            resendA.style.pointerEvents = 'auto';
            resendA.style.opacity = '1';
            return;
        }
        seconds -= 1;
        setTimeout(tick, 1000);
    }
    resendA.style.pointerEvents = 'none';
    resendA.style.opacity = '0.45';
    tick();

    resendA.addEventListener('click', function (e) {
        e.preventDefault();
        if (seconds > 0) return;
        resendForm.submit();
    });

    form.addEventListener('submit', function () {
        syncOtp();
    });
})();
</script>
@else
    @if(isset($recaptcha) && $recaptcha['status'] == 1)
        <script>
            var onloadCallback = function () {
                grecaptcha.render('recaptcha_element', {
                    'sitekey': '{{ \App\CPU\Helpers::get_business_settings('recaptcha')['site_key'] }}'
                });
            };
        </script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
        <script>
            $("#form-id-pwd").on('submit', function (e) {
                if (grecaptcha.getResponse().length === 0) {
                    e.preventDefault();
                    toastr.error("{{\App\CPU\translate('Please check the recaptcha')}}");
                }
            });
        </script>
    @else
        <script>
            function re_captcha_pwd() {
                var u = "{{ url('/customer/auth/code/captcha') }}/" + Math.random() + "?captcha_session_id=default_recaptcha_id_customer_login";
                var el = document.getElementById("customer_login_recaptcha_id");
                if (el) { el.src = u; }
            }
        </script>
    @endif
@endif
@endpush
