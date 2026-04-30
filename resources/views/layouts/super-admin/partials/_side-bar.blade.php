@php
    $dir = Session::get('direction', 'ltr');
    $subdomainSidebarEntities = $subdomainSidebarEntities ?? collect();
    $inspectEntity = request()->route('entity');
    $inspectEntityId = $inspectEntity instanceof \App\Models\Entity ? $inspectEntity->id : null;
    $subdomainsNavOpen = request()->routeIs('super-admin.subdomains.*');
@endphp
<div id="sidebarMain" class="d-none">
    <aside style="text-align: {{ $dir === 'rtl' ? 'right' : 'left' }};"
           class="bg-white js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset pb-0">
                <div class="navbar-brand-wrapper justify-content-between side-logo">
                    <a class="navbar-brand" href="{{ route('super-admin.dashboard') }}" aria-label="">
                        <img class="navbar-brand-logo-mini for-web-logo max-h-30"
                             src="{{ asset('assets/back-end/img/login-img/login-abms.png') }}"
                             alt="Logo" style="max-height: 36px; object-fit: contain;">
                    </a>
                    <button type="button"
                            class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <img src="{{ asset('assets/back-end/img/login-img/semi-logo-for-company.png') }}" alt=""
                             width="100%">
                    </button>
                </div>
                <div class="navbar-vertical-content pt-3">
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <li class="navbar-vertical-aside-has-menu {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               title="{{ \App\CPU\translate('Dashboard') }}"
                               href="{{ route('super-admin.dashboard') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M3 6.5C3 3.87479 3.02811 3 6.5 3C9.97189 3 10 3.87479 10 6.5C10 9.12521 10.0111 10 6.5 10C2.98893 10 3 9.12521 3 6.5Z"
                                          stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M14 6.5C14 3.87479 14.0281 3 17.5 3C20.9719 3 21 3.87479 21 6.5C21 9.12521 21.0111 10 17.5 10C13.9889 10 14 9.12521 14 6.5Z"
                                          stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M3 17.5C3 14.8748 3.02811 14 6.5 14C9.97189 14 10 14.8748 10 17.5C10 20.1252 10.0111 21 6.5 21C2.98893 21 3 20.1252 3 17.5Z"
                                          stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M14 17.5C14 14.8748 14.0281 14 17.5 14C20.9719 14 21 14.8748 21 17.5C21 20.1252 21.0111 21 17.5 21C13.9889 21 14 20.1252 14 17.5Z"
                                          stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ \App\CPU\translate('Dashboard') }}
                                </span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{ request()->routeIs('super-admin.entities.*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               title="Entities"
                               href="{{ route('super-admin.entities.index') }}">
                                <i class="tio-shop nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Entities</span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{ request()->routeIs('super-admin.iso-standards.*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               title="ISO Standards"
                               href="{{ route('super-admin.iso-standards.index') }}">
                                <i class="tio-checkmark-square nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">ISO Standards</span>
                            </a>
                        </li>
                        <li class="{{ $subdomainsNavOpen ? 'active' : '' }}">
                            <div class="nav-link"
                                 title="{{ \App\CPU\translate('Subdomain management') }}">
                                <i class="tio-globe nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Subdomain management') }}</span>
                            </div>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: block">
                                @forelse($subdomainSidebarEntities as $ent)
                                    @php
                                        $domainLabel = optional($ent->domains->first())->domain ?? $ent->name;
                                        $isThisTenant = $inspectEntityId === $ent->id;
                                    @endphp
                                    <li class="{{ $isThisTenant ? 'active' : '' }}">
                                        <a class="d-block px-3 py-1 text-white"
                                           style="font-size: 12px; text-decoration: none;"
                                           href="{{ url('/super-admin/subdomains/' . $ent->id . '/dashboard') }}"
                                           title="{{ $domainLabel }}">
                                            <i class="tio-label nav-icon"></i> {{ $domainLabel }}
                                        </a>
                                        @if($isThisTenant)
                                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: block">
                                                <li class="nav-item {{ request()->routeIs('super-admin.subdomains.dashboard') ? 'active' : '' }}">
                                                    <a class="nav-link" href="{{ route('super-admin.subdomains.dashboard', $ent) }}">{{ \App\CPU\translate('Dashboard') }}</a>
                                                </li>
                                                <li class="nav-item {{ request()->routeIs('super-admin.subdomains.applications') ? 'active' : '' }}">
                                                    <a class="nav-link" href="{{ route('super-admin.subdomains.applications', $ent) }}">{{ \App\CPU\translate('Applications') }}</a>
                                                </li>
                                                <li class="nav-item {{ request()->routeIs('super-admin.subdomains.orders') ? 'active' : '' }}">
                                                    <a class="nav-link" href="{{ route('super-admin.subdomains.orders', $ent) }}">{{ \App\CPU\translate('Orders') }}</a>
                                                </li>
                                                <li class="nav-item {{ request()->routeIs('super-admin.subdomains.products') ? 'active' : '' }}">
                                                    <a class="nav-link" href="{{ route('super-admin.subdomains.products', $ent) }}">{{ \App\CPU\translate('Products') }}</a>
                                                </li>
                                                <li class="nav-item {{ request()->routeIs('super-admin.subdomains.customers') ? 'active' : '' }}">
                                                    <a class="nav-link" href="{{ route('super-admin.subdomains.customers', $ent) }}">{{ \App\CPU\translate('Customers') }}</a>
                                                </li>
                                                <li class="nav-item {{ request()->routeIs('super-admin.subdomains.sellers') ? 'active' : '' }}">
                                                    <a class="nav-link" href="{{ route('super-admin.subdomains.sellers', $ent) }}">{{ \App\CPU\translate('Sellers') }}</a>
                                                </li>
                                                <li class="nav-item {{ request()->routeIs('super-admin.subdomains.employees') ? 'active' : '' }}">
                                                    <a class="nav-link" href="{{ route('super-admin.subdomains.employees', $ent) }}">{{ \App\CPU\translate('Employees') }}</a>
                                                </li>
                                            </ul>
                                        @endif
                                    </li>
                                @empty
                                    <li class="nav-item px-3 py-2">
                                        <span class="text-muted small">{{ \App\CPU\translate('No data found') }}</span>
                                    </li>
                                @endforelse
                            </ul>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{ request()->routeIs('super-admin.auditors.*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               title="Assessors/Auditors"
                               href="{{ route('super-admin.auditors.index') }}">
                                <i class="tio-user nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Assessors/Auditors</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </aside>
</div>
