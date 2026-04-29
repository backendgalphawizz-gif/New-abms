@extends('layouts.front-end.auth-cab')
@section('title', \App\CPU\translate('Register'))

@section('content')
@php
    $recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha');
@endphp

<h1 class="auth-h1">{{ \App\CPU\translate('auth_signup_title') }}</h1>
<p class="auth-sub">{{ \App\CPU\translate('auth_signup_subtitle') }}</p>

<form id="form-id" action="{{ url('/signup') }}" method="post" novalidate>
    @csrf

    <div class="auth-field">
        <label class="auth-label" for="reg-name">{{ \App\CPU\translate('name') }}</label>
        <input class="auth-inputline" type="text" id="reg-name" name="f_name" required
               value="{{ old('f_name') }}" placeholder="{{ \App\CPU\translate('auth_signup_placeholder_name') }}">
    </div>

    <div class="auth-field">
        <label class="auth-label" for="reg-phone">{{ \App\CPU\translate('auth_signup_label_mobile_number') }}</label>
        <input class="auth-inputline" type="tel" id="reg-phone" name="phone" inputmode="numeric" autocomplete="tel-national"
               pattern="[0-9]{10}" maxlength="10" minlength="10"
               value="{{ old('phone') }}" placeholder="{{ \App\CPU\translate('auth_signup_placeholder_phone') }}" required
               title="{{ \App\CPU\translate('auth_phone_hint_10_digits') }}">
    </div>

    <div class="auth-field">
        <label class="auth-label" for="reg-email">{{ \App\CPU\translate('auth_signup_label_email') }}</label>
        <input class="auth-inputline" type="email" id="reg-email" name="email" value="{{ old('email') }}"
               placeholder="{{ \App\CPU\translate('auth_signup_placeholder_email') }}">
    </div>

    <label class="auth-check">
        <input type="checkbox" name="agree_terms" id="agree_terms" value="1" required {{ old('agree_terms') ? 'checked' : '' }}>
        <span>
            {{ \App\CPU\translate('auth_signup_agree_prefix') }}
            <a target="_blank" href="{{ route('terms') }}">{{ \App\CPU\translate('Terms & Conditions') }}</a>
            {{ \App\CPU\translate('auth_signup_terms_connector') }}
            <a target="_blank" href="{{ route('privacy-policy') }}">{{ \App\CPU\translate('privacy_policy') }}</a>.
        </span>
    </label>

    @if(isset($recaptcha) && $recaptcha['status'] == 1)
        <div id="recaptcha_element" class="w-100 mb-2" data-type="image"></div>
    @endif

    @if ($errors->any())
        <p class="text-danger small mb-3" role="alert">@foreach($errors->all() as $err){{ $err }} @endforeach</p>
    @endif

    <button class="auth-btn-primary" type="submit" id="sign-up" @if(!old('agree_terms')) disabled @endif>{{ \App\CPU\translate('Sign_Up') }}</button>
</form>

<div class="auth-muted-row auth-muted-row--with-rule">{{ \App\CPU\translate('auth_already_member') }}</div>
<a class="auth-btn-ghost" href="{{ route('customer.login.short') }}">{{ \App\CPU\translate('sign_in') }}</a>
@endsection

@push('script')
<script>
    if ($('#agree_terms').is(':checked')) { $('#sign-up').prop('disabled', false); }
    $('#agree_terms').on('change', function () {
        $('#sign-up').prop('disabled', !this.checked);
    });
</script>
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
        $("#form-id").on('submit', function (e) {
            if (grecaptcha.getResponse().length === 0) {
                e.preventDefault();
                toastr.error("{{\App\CPU\translate('Please check the recaptcha')}}");
            }
        });
    </script>
@endif
@endpush
