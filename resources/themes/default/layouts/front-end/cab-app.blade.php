{{-- Customer CAB portal: responsive (bottom nav mobile, top links on md+). --}}
@php
    $cabBlue = $web_config['primary_color'] ?? '#003594';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Portal') — {{ optional($web_config['name'] ?? null)->value ?? config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ storefront_public_uri('public/assets/back-end') }}/css/toastr.css">
    <style>
        :root { --cab-primary: {{ $cabBlue }}; --cab-primary-dark: #002a70; }
        body.cab-app { font-family: 'Inter', sans-serif; background: #f4f6f8; min-height: 100vh; padding-bottom: 72px; }
        @media (min-width: 768px) { body.cab-app { padding-bottom: 24px; } }
        .cab-header { background: var(--cab-primary); color: #fff; padding: 0.75rem 1rem; }
        .cab-header-in { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .cab-hello { display: flex; align-items: center; gap: 0.65rem; }
        .cab-avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; background: rgba(255,255,255,.2); }
        .cab-hello h1 { font-size: 0.85rem; font-weight: 400; margin: 0; opacity: 0.95; }
        .cab-hello p { font-size: 1rem; font-weight: 700; margin: 0; }
        .cab-icon-btn { color: #fff; opacity: 0.95; font-size: 1.25rem; padding: 0.25rem; }
        .cab-nav-desk { display: none; max-width: 1200px; margin: 0 auto; background: #fff; border-bottom: 1px solid #e2e5ea; padding: 0 0.5rem; }
        @media (min-width: 768px) { .cab-nav-desk { display: flex; } }
        .cab-nav-desk a { display: inline-block; padding: 0.9rem 1.1rem; color: #6c757d; text-decoration: none; font-weight: 500; border-bottom: 3px solid transparent; }
        .cab-nav-desk a:hover { color: var(--cab-primary); }
        .cab-nav-desk a.active { color: var(--cab-primary); border-bottom-color: var(--cab-primary); }
        .cab-main { max-width: 1200px; margin: 0 auto; padding: 1rem; }
        .cab-bottom { position: fixed; left: 0; right: 0; bottom: 0; background: #fff; border-top: 1px solid #e2e5ea; display: flex; justify-content: space-around; align-items: center; padding: 0.4rem 0.25rem calc(0.4rem + env(safe-area-inset-bottom)); z-index: 100; }
        @media (min-width: 768px) { .cab-bottom { display: none; } }
        .cab-bottom a { flex: 1; text-align: center; text-decoration: none; color: #6c757d; font-size: 0.68rem; padding: 0.2rem; }
        .cab-bottom a span { display: block; font-size: 1.2rem; margin-bottom: 0.1rem; }
        .cab-bottom a.active { color: var(--cab-primary); font-weight: 600; }
    </style>
    @stack('css_or_js')
</head>
<body class="cab-app">
<header class="cab-header">
    <div class="cab-header-in">
        <a href="{{ route('portal.home') }}" class="cab-hello text-white text-decoration-none" style="flex:1;">
            @php
                $av = $customer->image && $customer->image !== 'def.png'
                    ? asset('storage/app/public/profile/'.$customer->image)
                    : storefront_public_uri('public/assets/front-end/img/image-place-holder.png');
                $displayName = trim(($customer->f_name ?? '').' '.($customer->l_name ?? '')) ?: ($customer->name ?? $customer->email ?? 'User');
            @endphp
            <img src="{{ $av }}" class="cab-avatar" width="44" height="44" alt="">
            <div>
                <h1>Hello!</h1>
                <p>{{ $displayName }}</p>
            </div>
        </a>
        <div class="d-flex align-items-center" style="gap:0.5rem;">
            <a href="{{ route('mark-all-read') }}" class="cab-icon-btn" title="{{ \App\CPU\translate('notifications') }}" aria-label="Notifications">&#128276;</a>
            <a href="{{ route('contacts') }}" class="cab-icon-btn" title="{{ \App\CPU\translate('contact_us') }}" aria-label="Support">&#128222;</a>
        </div>
    </div>
</header>
<nav class="cab-nav-desk" aria-label="Main">
    <a href="{{ route('portal.home') }}" class="{{ request()->routeIs('portal.home') ? 'active' : '' }}">{{ \App\CPU\translate('Home') }}</a>
    <a href="{{ route('portal.applications', ['tab' => 'new']) }}" class="{{ request()->routeIs('portal.applications') ? 'active' : '' }}">{{ \App\CPU\translate('Applications') }}</a>
    <a href="{{ route('home') }}">Shop</a>
    <a href="{{ route('user-account') }}">{{ \App\CPU\translate('Account') }}</a>
</nav>

<main class="cab-main">
    @yield('content')
</main>

<nav class="cab-bottom" aria-label="Main mobile">
    <a href="{{ route('portal.home') }}" class="{{ request()->routeIs('portal.home') ? 'active' : '' }}"><span>&#127968;</span>{{ \App\CPU\translate('Home') }}</a>
    <a href="{{ route('portal.applications', ['tab' => 'new']) }}" class="{{ request()->routeIs('portal.applications') ? 'active' : '' }}"><span>&#128196;</span>{{ \App\CPU\translate('Applications') }}</a>
    <a href="{{ route('home') }}"><span>&#128722;</span>Shop</a>
    <a href="{{ route('user-account') }}"><span>&#128100;</span>{{ \App\CPU\translate('Account') }}</a>
</nav>

<script src="{{ storefront_public_uri('public/assets/front-end') }}/vendor/jquery/dist/jquery-2.2.4.min.js"></script>
<script src="{{ storefront_public_uri('public/assets/back-end/js/toastr.js') }}"></script>
{!! Toastr::message() !!}
@stack('script')
</body>
</html>
