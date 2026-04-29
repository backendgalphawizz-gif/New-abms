@php($dir = Session::get('direction', 'ltr'))
<div id="headerMain" class="d-none">
    <header id="header" class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container shadow">
        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper">
                <a class="navbar-brand" href="{{ route('super-admin.dashboard') }}" aria-label="">
                    <img class="navbar-brand-logo"
                         src="{{ asset('assets/back-end/img/login-img/login-abms.png') }}"
                         alt="Logo" style="max-height: 40px; object-fit: contain;">
                    <img class="navbar-brand-logo-mini"
                         src="{{ asset('assets/back-end/img/login-img/login-abms.png') }}"
                         alt="Logo" style="max-height: 32px; object-fit: contain;">
                </a>
            </div>

            <div class="navbar-nav-wrap-content-left">
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3 ">
                    <i class=" navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                       data-placement="right" title="Collapse"></i>
                    <i class=" navbar-vertical-aside-toggle-full-align"
                       data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                       data-toggle="tooltip" data-placement="right" title="Expand"></i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21 6H3M15 12H3M17 18H3" stroke="#E1F4F4" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="topbar--search-form"></div>
            </div>

            <div class="navbar-nav-wrap-content-right"
                 style="{{ $dir === 'rtl' ? 'margin-left:unset; margin-right: auto' : 'margin-right:unset; margin-left: auto' }}">
                <ul class="navbar-nav align-items-center flex-row">
                    <li class="nav-item view-web-site-info d-none">
                        <div class="hs-unfold">
                            <a onclick="openInfoWeb()" href="javascript:"
                               class="bg-white js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle">
                                <i class="tio-info"></i>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker media align-items-center gap-3 navbar-dropdown-account-wrapper "
                               href="javascript:;"
                               data-hs-unfold-options='{"target": "#accountNavbarDropdown","type": "css-animation"}'>
                                <div class="avatar border avatar-circle">
                                    <img class="avatar-img"
                                         src="{{ asset('assets/back-end/img/400x400/img2.jpg') }}"
                                         alt="">
                                </div>
                                <div class="d-none d-md-block media-body">
                                    <h5 class="profile-name mb-0" title="{{ auth('super_admin')->user()->name }}">{{ auth('super_admin')->user()->name }}</h5>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M15.8346 7.08325L10.0013 12.9166L4.16797 7.08325" stroke="#E3E1EC" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                            <div id="accountNavbarDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account">
                                <div class="dropdown-item-text">
                                    <div class="media align-items-center text-break">
                                        <div class="media-body">
                                            <span class="card-title h5">{{ auth('super_admin')->user()->name }}</span>
                                            <span class="card-text">{{ auth('super_admin')->user()->email }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:" onclick="Swal.fire({
                                    title: '{{ \App\CPU\translate('Do_you_want_to_logout') }}?',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#377dff',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: `Yes`,
                                    denyButtonText: `Don't Logout`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{ route('super-admin.auth.logout') }}';
                                    } else{
                                    Swal.fire('Canceled', '', 'info')
                                    }
                                    })">
                                    <span class="text-truncate pr-2">{{ \App\CPU\translate('sign_out') }}</span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div id="website_info" style="display: none;" class="bg-secondary w-100 d-none"></div>
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>
