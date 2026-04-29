@php
    if (! session()->has('local')) {
        session()->put('local', 'en');
        \Illuminate\Support\Facades\Session::put('direction', 'ltr');
    }
@endphp
@php($dir = Session::get('direction', 'ltr'))
<style>
    .cke_notification_warning {
        display: none;
    }
</style>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $dir }}"
      style="text-align: {{ $dir === 'rtl' ? 'right' : 'left' }};">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/back-end/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/back-end/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/back-end/vendor/icon-set/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/back-end/css/theme.minc619.css') }}?v=1.0">
    <link rel="stylesheet" href="{{ asset('assets/back-end/css/style.css') }}">
    @if($dir === 'rtl')
        <link rel="stylesheet" href="{{ asset('assets/back-end/css/menurtl.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">
    @stack('css_or_js')
    <script src="{{ asset('assets/back-end/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/back-end/css/toastr.css') }}">
</head>

<body class="footer-offset">
@include('layouts.back-end.partials._front-settings')
<span class="d-none" id="placeholderImg" data-img="{{ asset('assets/back-end/img/400x400/img3.png') }}"></span>

<div class="row">
    <div class="col-12 position-fixed z-9999 mt-10rem">
        <div id="loading" class="d--none">
            <center>
                <img width="200" src="{{ asset('assets/front-end/img/loader.gif') }}" alt=""
                     onerror="this.style.display='none'">
            </center>
        </div>
    </div>
</div>

@include('layouts.super-admin.partials._header')
@include('layouts.super-admin.partials._side-bar')

<main id="content" role="main" class="main pointer-event">
    @yield('content')
    @include('layouts.super-admin.partials._footer')
</main>

<script src="{{ asset('assets/back-end/js/jquery.js') }}"></script>
<script src="{{ asset('assets/back-end/js/custom.js') }}"></script>
<script src="{{ asset('assets/back-end/js/theme.min.js') }}"></script>
<script src="{{ asset('assets/back-end/js/sweet_alert.js') }}"></script>
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
    function openInfoWeb() {
    }

    $(document).on('ready', function () {
        "use strict";
        $('.js-navbar-vertical-aside-toggle-invoker').click(function () {
            $('.js-navbar-vertical-aside-toggle-invoker i').tooltip('hide');
        });
        var sidebar = $('.js-navbar-vertical-aside').hsSideNav();
        $('.js-nav-tooltip-link').tooltip({boundary: 'window'});
        $(".js-nav-tooltip-link").on("show.bs.tooltip", function (e) {
            if (!$("body").hasClass("navbar-vertical-aside-mini-mode")) {
                return false;
            }
        });
        $('.js-hs-unfold-invoker').each(function () {
            new HSUnfold($(this)).init();
        });
    });
</script>

<script src="{{ asset('assets/back-end/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/lightbox.min.js') }}"></script>
@stack('script')
@stack('script_2')
</body>
</html>
