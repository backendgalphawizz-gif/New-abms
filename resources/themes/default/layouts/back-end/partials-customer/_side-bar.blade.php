@php($dir = Session::get('direction', 'ltr'))
<style>
    .cab-side .navbar-brand-wrapper { min-height: 70px; border-bottom: 1px solid #edf1f7; padding: 0 .9rem; }
    .cab-side .navbar-brand {
        font-size: 18px;
        font-weight: 800;
        color: #0f1a34;
        text-decoration: none;
        white-space: nowrap;
        letter-spacing: -.01em;
        line-height: 1.1;
    }
    .cab-side .navbar-vertical-content { padding: 1rem 1rem 0; }
    .cab-side .nav-label { font-size: .72rem; color: rgba(255,255,255,.72); text-transform: uppercase; letter-spacing: .09em; font-weight: 700; margin: .2rem 0 .6rem .35rem; }
    .cab-side .nav-link { border-radius: 999px; color: #fff; font-weight: 500; min-height: 42px; padding: .62rem .9rem; }
    .cab-side .nav-link .nav-icon { font-size: 1.1rem; margin-right: .55rem; }
    .cab-side .active > .nav-link { background: rgba(255,255,255,.18); color: #fff; }
    .cab-help {
        margin: 1.2rem 1rem;
        background: #eef4ff;
        border: 1px solid #dbe7ff;
        border-radius: 18px;
        padding: 1rem;
    }
    .cab-help h5 { margin: 0; font-size: 1.05rem; font-weight: 700; color: #1a2742; }
    .cab-help p { margin: .4rem 0 .8rem; color: #6e7f96; font-size: .82rem; }
    .cab-help .btn { width: 100%; border-radius: 999px; font-weight: 600; }
</style>
<div id="sidebarMain" class="d-none">
    <aside style="text-align: {{ $dir === 'rtl' ? 'right' : 'left' }};"
           class="cab-side bg-white js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset pb-0">
                <div class="navbar-brand-wrapper justify-content-between side-logo">
                    <a class="navbar-brand" href="{{ route('portal.home') }}" aria-label="Portal">
                        CABManagement
                    </a>
                    <button type="button"
                            class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <img src="{{ asset('public/assets/back-end/img/login-img/semi-logo-for-company.png') }}" alt="" width="100%">
                    </button>
                </div>
                <div class="navbar-vertical-content pt-3">
                    <div class="nav-label">Navigation</div>
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <li class="navbar-vertical-aside-has-menu {{ request()->routeIs('portal.home') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" title="Dashboard"
                               href="{{ route('portal.home') }}">
                                <i class="tio-dashboard-vs nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Dashboard</span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{ request()->routeIs('portal.applications') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" title="My Applications"
                               href="{{ route('portal.applications', ['tab' => 'certifications']) }}">
                                <i class="tio-library nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">My Applications</span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{ request()->routeIs('account-payment') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" title="Payment History" href="{{ route('account-payment') }}">
                                <i class="tio-receipt nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Payment History</span>
                            </a>
                        </li>
                        @if(Route::has('user-account'))
                            <li class="navbar-vertical-aside-has-menu {{ request()->routeIs('user-account') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link" title="Profile"
                                   href="{{ route('user-account') }}">
                                    <i class="tio-user-big nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Profile</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="cab-help">
                    <h5>Need help?</h5>
                    <p>Our orbital support team is available 24/7.</p>
                    <a href="{{ route('contacts') }}" class="btn btn-outline-primary btn-sm">Contact Support</a>
                </div>
            </div>
        </div>
    </aside>
</div>
