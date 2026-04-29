{{-- CAB customer auth: split-screen (brand + wave + form), matches CAB web/mobile spec. --}}
@php
    $cabBlue = $web_config['primary_color'] ?? '#1E70E6';
    $authBgFile = public_path('assets/front-end/img/auth-cab-split-bg.png');
    $authSplitBgUrl = '';
    $authSplitBgCss = 'none';
    if (is_file($authBgFile)) {
        $rel = storefront_public_uri('public/assets/front-end/img/auth-cab-split-bg.png');
        $authSplitBgUrl = rtrim(request()->getSchemeAndHttpHost(), '/') . rtrim(request()->getBasePath(), '/') . $rel;
        $authSplitBgUrl .= '?v=' . (int) @filemtime($authBgFile);
        $authSplitBgCss = 'url(' . json_encode($authSplitBgUrl) . ')';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title')@if(filled(optional($web_config['name'] ?? null)->value)) — {{ optional($web_config['name'])->value }}@endif</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Libre+Baskerville:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32"
          href="{{ asset('storage/app/public/company') }}/{{ optional($web_config['fav_icon'] ?? null)->value }}">
    <link rel="stylesheet" href="{{ storefront_public_uri('public/assets/back-end') }}/css/toastr.css">
    @if($authSplitBgUrl !== '')
        <link rel="preload" as="image" href="{{ $authSplitBgUrl }}">
    @endif
    <style>
        :root {
            --auth-blue: {{ $cabBlue }};
            --auth-accent: #1e73be;
            --auth-btn-primary-bg: #9ac1f0;
            --auth-muted: #666666;
            --auth-line: #e5e7eb;
            --auth-split-bg-image: {!! $authSplitBgCss !!};
        }
        * { box-sizing: border-box; }
        body.auth-cab-page {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', system-ui, sans-serif;
            color: #111827;
            background-color: #f8fafc;
            background-image: var(--auth-split-bg-image);
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
        }
        .auth-shell {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: transparent;
            background-image: var(--auth-split-bg-image);
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
        }
        @media (min-width: 900px) {
            .auth-shell { flex-direction: row; align-items: stretch; }
        }
        .auth-brand {
            position: relative;
            background: transparent;
            color: #fff;
            padding: 2rem 1.75rem 2.5rem;
            overflow: hidden;
            flex: 0 0 auto;
            text-shadow: 0 1px 2px rgba(0, 25, 70, 0.28);
        }
        @media (min-width: 900px) {
            .auth-brand {
                flex: 0 0 40%;
                min-width: 0;
                padding: 3rem 2.25rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }
        .auth-brand__inner { position: relative; z-index: 1; max-width: 24rem; margin: 0 auto; text-align: center; }
        @media (min-width: 900px) { .auth-brand__inner { margin: 0 auto; } }

        .auth-logo-mark { display: flex; flex-direction: column; align-items: center; margin-bottom: 1.25rem; }
        .auth-brand__rule--above-bars {
            width: 3.4rem;
            margin: 0 auto 0.45rem;
            height: 2px;
            background: rgba(255,255,255,0.92);
        }
        .auth-brand__rule--above-logo {
            width: 4rem;
            margin: 0 auto 0.55rem;
            height: 2px;
            background: rgba(255,255,255,0.92);
        }
        .auth-logo-img-wrap { margin-bottom: 0.65rem; }
        .auth-logo-img-wrap img {
            display: block;
            max-height: 48px;
            max-width: 160px;
            object-fit: contain;
        }
        .auth-bars {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 5px;
            height: 2rem;
            margin: 0;
        }
        .auth-bars span {
            width: 6px;
            border-radius: 2px;
            background: #fff;
            opacity: 0.95;
        }
        .auth-bars .b1 { height: 42%; }
        .auth-bars .b2 { height: 100%; }
        .auth-bars .b3 { height: 68%; }
        .auth-bars .b4 { height: 100%; }
        .auth-bars .b5 { height: 48%; }
        .auth-brand__system {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            text-align: center;
            margin: 0 0 0.25rem;
            line-height: 1.4;
            opacity: 0.95;
            max-width: 16rem;
        }
        .auth-brand__title {
            font-size: clamp(1.65rem, 4vw, 2.15rem);
            font-weight: 700;
            line-height: 1.15;
            margin: 1rem 0 0.65rem;
            text-align: center;
        }
        .auth-brand__lead {
            font-size: 1rem;
            font-weight: 500;
            opacity: 0.95;
            line-height: 1.45;
            margin: 0 0 1rem;
            text-align: center;
        }
        .auth-brand__desc {
            font-size: 0.875rem;
            line-height: 1.55;
            opacity: 0.88;
            margin: 0;
            text-align: center;
            font-weight: 400;
        }

        .auth-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem 1.5rem 2.5rem;
            background: transparent;
            position: relative;
            min-height: 100vh;
        }
        @media (min-width: 900px) {
            .auth-panel {
                min-height: 0;
                margin-left: 0;
                padding: 2.25rem 3rem 2.5rem 3rem;
                min-width: 0;
            }
        }
        .auth-panel__home {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 0.875rem;
            color: var(--auth-muted);
            text-decoration: none;
        }
        .auth-panel__home:hover { color: var(--auth-accent); }
        .auth-panel__inner { width: 100%; max-width: 26rem; margin: 0 auto; }
        @media (min-width: 900px) {
            .auth-panel__inner {
                margin: 0 auto;
                max-width: 22.5rem;
                padding-left: 0;
            }
        }

        .auth-h1 { font-size: 1.85rem; font-weight: 700; margin: 0 0 0.5rem; color: #000; letter-spacing: -0.02em; }
        .auth-sub { font-size: 0.95rem; color: #666; margin: 0 0 2rem; line-height: 1.45; }

        .auth-field { margin-bottom: 1.35rem; }
        .auth-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #666;
            margin-bottom: 0.35rem;
        }
        .auth-inputline {
            width: 100%;
            border: none;
            border-bottom: 1.5px solid var(--auth-line);
            padding: 0.55rem 0;
            font-size: 1rem;
            background: transparent;
            border-radius: 0;
        }
        .auth-inputline:focus {
            outline: none;
            border-bottom-color: var(--auth-accent);
        }
        .auth-inputline::placeholder { color: #999; }
        .auth-row-dial input::placeholder { color: #999; }

        .auth-row-dial {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1.5px solid var(--auth-line);
            padding-bottom: 0.35rem;
        }
        .auth-row-dial:focus-within { border-bottom-color: var(--auth-accent); }
        .auth-row-dial .prefix { font-size: 0.95rem; color: #666; white-space: nowrap; }
        .auth-row-dial input {
            flex: 1;
            border: none;
            padding: 0.35rem 0;
            font-size: 1rem;
            min-width: 0;
            background: transparent;
        }
        .auth-row-dial input:focus { outline: none; }

        .auth-check {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            font-size: 0.875rem;
            color: #333;
            line-height: 1.45;
            margin: 0.25rem 0 1.5rem;
        }
        .auth-check input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            margin-top: 0.18rem;
            flex-shrink: 0;
            border-radius: 2px;
            accent-color: var(--auth-accent);
        }
        .auth-check a { color: var(--auth-accent); font-weight: 600; text-decoration: none; }
        .auth-check a:hover { text-decoration: underline; }

        .auth-btn-primary {
            display: block;
            width: 100%;
            border: none;
            border-radius: 999px;
            padding: 0.9rem 1.25rem;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            background: var(--auth-btn-primary-bg);
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(30, 115, 190, 0.2);
            transition: filter 0.2s, transform 0.15s, background 0.2s;
        }
        .auth-btn-primary:hover { filter: brightness(0.96); color: #fff; }
        .auth-btn-primary:active { transform: scale(0.99); }
        .auth-btn-primary:disabled { opacity: 0.45; cursor: not-allowed; }

        .auth-muted-row {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--auth-muted);
        }
        .auth-muted-row--with-rule {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-top: 1.65rem;
            white-space: nowrap;
        }
        .auth-muted-row--with-rule::before,
        .auth-muted-row--with-rule::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--auth-line);
            min-width: 0;
        }
        @media (max-width: 899px) {
            .auth-muted-row--with-rule { justify-content: center; white-space: normal; }
            .auth-muted-row--with-rule::before,
            .auth-muted-row--with-rule::after { display: none; }
        }
        .auth-btn-ghost {
            display: block;
            width: 100%;
            margin-top: 0.65rem;
            border-radius: 999px;
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #000;
            background: #fff;
            border: 1.5px solid #d1d5db;
            text-align: center;
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s;
        }
        .auth-btn-ghost:hover {
            border-color: var(--auth-accent);
            color: var(--auth-accent);
        }

        .auth-otp-row {
            display: flex;
            gap: 0.65rem;
            justify-content: flex-start;
            margin-bottom: 1rem;
        }
        .auth-otp-box {
            width: 3.25rem;
            height: 3.25rem;
            text-align: center;
            font-size: 1.35rem;
            font-weight: 600;
            border: 2px solid var(--auth-line);
            border-radius: 0.5rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .auth-otp-box:focus {
            outline: none;
            border-color: var(--auth-accent);
            box-shadow: 0 0 0 3px rgba(30, 112, 230, 0.22);
        }
        .auth-resend {
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .auth-resend a {
            color: var(--auth-accent);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-resend a[disabled] { pointer-events: none; opacity: 0.45; }
        .auth-resend .timer { color: var(--auth-muted); margin-left: 0.35rem; }

        .auth-details { margin-top: 1.75rem; border-top: 1px solid var(--auth-line); padding-top: 1.25rem; }
        .auth-details summary {
            cursor: pointer;
            list-style: none;
            text-align: center;
            color: var(--auth-accent);
            font-size: 0.9rem;
            font-weight: 500;
        }
        .auth-details summary::-webkit-details-marker { display: none; }
        .auth-captcha-row { margin-top: 1rem; }
    </style>
    @stack('css_or_js')
</head>
<body class="auth-cab-page">
<div class="auth-shell">
    <aside class="auth-brand" aria-label="CAB">
        <div class="auth-brand__inner">
            @php($hasLogo = !empty(optional($web_config['web_logo'] ?? null)->value))
            @if($hasLogo)
                <div class="auth-logo-mark">
                    <div class="auth-brand__rule auth-brand__rule--above-logo" aria-hidden="true"></div>
                    <div class="auth-logo-img-wrap">
                        <img src="{{ asset('storage/app/public/company') }}/{{ $web_config['web_logo']->value }}"
                             alt="{{ optional($web_config['name'] ?? null)->value }}">
                    </div>
                    <p class="auth-brand__system">{{ \App\CPU\translate('CAB_MANAGEMENT_SYSTEM') }}</p>
                </div>
            @else
                <div class="auth-logo-mark">
                    <div class="auth-brand__rule auth-brand__rule--above-bars" aria-hidden="true"></div>
                    <div class="auth-bars" aria-hidden="true">
                        <span class="b1"></span><span class="b2"></span><span class="b3"></span><span class="b4"></span><span class="b5"></span>
                    </div>
                    <p class="auth-brand__system">{{ \App\CPU\translate('CAB_MANAGEMENT_SYSTEM') }}</p>
                </div>
            @endif
            <h2 class="auth-brand__title">{{ \App\CPU\translate('auth_brand_title') }}</h2>
            <p class="auth-brand__lead">{{ \App\CPU\translate('auth_brand_tagline') }}</p>
            <p class="auth-brand__desc">{{ \App\CPU\translate('auth_brand_desc') }}</p>
        </div>
    </aside>
    <main class="auth-panel">
        <a class="auth-panel__home" href="{{ route('home') }}">{{ \App\CPU\translate('Home') }}</a>
        <div class="auth-panel__inner">
            @yield('content')
        </div>
    </main>
</div>
<script src="{{ storefront_public_uri('public/assets/front-end') }}/vendor/jquery/dist/jquery-2.2.4.min.js"></script>
<script src="{{ storefront_public_uri('public/assets/back-end/js/toastr.js') }}"></script>
{!! Toastr::message() !!}
@stack('script')
</body>
</html>
