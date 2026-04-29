@php
    $dir = Session::get('direction', 'ltr');
    $customer = $customer ?? auth('customer')->user();
    $e_commerce_logo = \App\CPU\Helpers::get_business_settings('company_web_logo') ?? '';
    $custImg = ($customer && $customer->image && $customer->image !== 'def.png')
        ? asset('storage/app/public/profile/'.$customer->image)
        : asset('public/assets/front-end/img/image-place-holder.png');
    $custName = $customer ? trim(($customer->f_name ?? '').' '.($customer->l_name ?? '')) : '';
    $custName = $custName !== '' ? $custName : (optional($customer)->name ?? optional($customer)->email ?? 'User');
@endphp
<style>
    #headerMain .navbar-brand-logo { display: none; }
    #headerMain .navbar-brand:before { content: "CABManagement"; font-size: 22px; font-weight: 800; color: #101a34; }
    #headerMain .navbar { box-shadow: none !important; border-bottom: 1px solid #edf1f7; }
    #headerMain .btn.btn-icon { width: 34px; height: 34px; }
</style>
<div id="headerMain" class="d-none">
    <header id="header" class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container shadow">
        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper">
                <a class="navbar-brand" href="{{ route('portal.home') }}" aria-label="Portal">
                    <img class="navbar-brand-logo"
                         onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'"
                         src="{{ $e_commerce_logo ? asset('storage/app/public/company/'.$e_commerce_logo) : asset('public/assets/front-end/img/image-place-holder.png') }}"
                         alt="Logo">
                    <img class="navbar-brand-logo-mini"
                         onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'"
                         src="{{ $e_commerce_logo ? asset('storage/app/public/company/'.$e_commerce_logo) : asset('public/assets/front-end/img/image-place-holder.png') }}"
                         alt="Logo">
                </a>
            </div>

            <div class="navbar-nav-wrap-content-left">
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3 ">
                    <i class=" navbar-vertical-aside-toggle-short-align" data-toggle="tooltip" data-placement="right" title="Collapse"></i>
                    <i class=" navbar-vertical-aside-toggle-full-align"
                       data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                       data-toggle="tooltip" data-placement="right" title="Expand"></i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21 6H3M15 12H3M17 18H3" stroke="#E1F4F4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="topbar--search-form"></div>
            </div>

            <div class="navbar-nav-wrap-content-right"
                 style="{{ $dir === 'rtl' ? 'margin-left:unset; margin-right: auto' : 'margin-right:unset; margin-left: auto' }}">
                <ul class="navbar-nav align-items-center flex-row">
                    @if(Route::has('mark-all-read'))
                        <li class="nav-item d-none d-md-inline-block">
                            <div class="hs-unfold">
                                <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                   href="{{ route('mark-all-read') }}" title="{{ \App\CPU\translate('notifications') }}">
                                    <i class="tio-notifications"></i>
                                </a>
                            </div>
                        </li>
                    @endif
                    <li class="nav-item d-none d-sm-inline-block">
                        <a class="btn btn-icon btn-ghost-secondary rounded-circle" href="javascript:;">
                            <i class="tio-search"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker media align-items-center gap-3 navbar-dropdown-account-wrapper"
                               href="javascript:;"
                               data-hs-unfold-options='{"target": "#customerAccountNavbarDropdown","type": "css-animation"}'>
                                <div class="avatar border avatar-circle">
                                    <img class="avatar-img" src="{{ $custImg }}" alt="">
                                </div>
                            </a>
                            <div id="customerAccountNavbarDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account">
                                <div class="dropdown-item-text">
                                    <div class="media align-items-center text-break">
                                        <div class="avatar avatar-sm avatar-circle mr-2">
                                            <img class="avatar-img" src="{{ $custImg }}" alt="">
                                        </div>
                                        <div class="media-body">
                                            <span class="card-title h5">{{ $custName }}</span>
                                            <span class="card-text">{{ optional($customer)->email ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                @if(Route::has('user-account'))
                                    <a class="dropdown-item" href="{{ route('user-account') }}">
                                        <span class="text-truncate pr-2">{{ \App\CPU\translate('Account') }}</span>
                                    </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('portal.home') }}">
                                    <span class="text-truncate pr-2">{{ \App\CPU\translate('Home') }}</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:" onclick="Swal.fire({
                                    title: "{{ \App\CPU\translate('Do_you_want_to_logout') }}?",
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#377dff',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: `Yes`,
                                    denyButtonText: `Don't Logout`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{ route('customer.auth.logout') }}';
                                    } else {
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
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>
