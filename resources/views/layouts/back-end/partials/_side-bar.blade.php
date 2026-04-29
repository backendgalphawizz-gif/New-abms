<div id="sidebarMain" class="d-none">
    <aside style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="bg-white js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset pb-0">
                <div class="navbar-brand-wrapper justify-content-between side-logo">
                    <!-- Logo -->
                    @php($e_commerce_logo = \App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->value('value') ?? '')
                    <a class="navbar-brand" href="{{route('admin.dashboard.index')}}" aria-label="Front">
                        <img onerror="this.src='{{asset('public/assets/back-end/img/900x400/img1.jpg')}}'"
                            class="navbar-brand-logo-mini for-web-logo max-h-30"
                            src="{{asset("storage/app/public/company/$e_commerce_logo")}}" alt="Logo">
                    </a>
                    <!-- Navbar Vertical Toggle -->
                    <button type="button"
                        class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                          <img src="{{asset('public/assets/back-end/img/login-img/semi-logo-for-company.png')}}" alt="" width="100%">
                        
                    </button>
                    <!-- End Navbar Vertical Toggle -->

                     <!-- <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                            data-placement="right" title=""></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                            data-template="<div class=&quot;tooltip d-sm-block&quot; role=&quot;tooltip&quot;><div class=&quot;arrow&quot;></div><div class=&quot;tooltip-inner&quot;></div></div>"
                            data-toggle="tooltip" data-placement="right" title=""></i>
                    </button> -->
                </div>

                <!-- Content -->
                <div class="navbar-vertical-content pt-3">
                    <!-- Search Form -->
                    <div class="sidebar--search-form pb-3 pt-4 d-none">
                        <div class="search--form-group">
                            <button type="button" class="btn"><i class="tio-search"></i></button>
                            <input type="text" class="js-form-search form-control form--control" id="search-bar-input"
                                placeholder="{{\App\CPU\translate('search_menu')}}...">
                        </div>
                    </div>
                    <!-- <div class="input-group">
                        <diV class="card search-card" id="search-card"
                             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                            <div class="card-body search-result-box" id="search-result-box">

                            </div>
                        </diV>
                    </div> -->
                    <!-- End Search Form -->

                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- Dashboards -->
                         
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/dashboard')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                title="{{\App\CPU\translate('Dashboard')}}" href="{{route('admin.dashboard.index')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3 6.5C3 3.87479 3.02811 3 6.5 3C9.97189 3 10 3.87479 10 6.5C10 9.12521 10.0111 10 6.5 10C2.98893 10 3 9.12521 3 6.5Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14 6.5C14 3.87479 14.0281 3 17.5 3C20.9719 3 21 3.87479 21 6.5C21 9.12521 21.0111 10 17.5 10C13.9889 10 14 9.12521 14 6.5Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3 17.5C3 14.8748 3.02811 14 6.5 14C9.97189 14 10 14.8748 10 17.5C10 20.1252 10.0111 21 6.5 21C2.98893 21 3 20.1252 3 17.5Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14 17.5C14 14.8748 14.0281 14 17.5 14C20.9719 14 21 14.8748 21 17.5C21 20.1252 21.0111 21 17.5 21C13.9889 21 14 20.1252 14 17.5Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Dashboard')}}
                                </span>
                            </a>
                        </li>
                        <!-- End Dashboards -->


                        <!-- Order Management -->
                        @if(\App\CPU\Helpers::module_permission_check('order_management'))
                        <li class="d-none nav-item {{ Request::is('admin/report/order') || Request::is('admin/refund-section/refund/details/*') || Request::is('admin/refund-section/refund/list/*') || Request::is('admin/orders*')?'scroll-here':''}}">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('order_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <!-- Order -->
                        <li class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/report/order') || Request::is('admin/refund-section/refund/details/*') || Request::is('admin/refund-section/refund/list/*') || Request::is('admin/orders*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link  its-drop" href="javascript:void(0)"
                                title="{{\App\CPU\translate('Order_Management')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M2.75 3.25L4.83 3.61L5.793 15.083C5.87 16.02 6.653 16.739 7.593 16.736H18.502C19.399 16.738 20.16 16.078 20.287 15.19L21.236 8.632C21.342 7.899 20.833 7.219 20.101 7.113C20.037 7.104 5.164 7.099 5.164 7.099"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M13.375 10.7949C13.375 10.3807 13.7108 10.0449 14.125 10.0449H16.898C17.3122 10.0449 17.648 10.3807 17.648 10.7949C17.648 11.2091 17.3122 11.5449 16.898 11.5449H14.125C13.7108 11.5449 13.375 11.2091 13.375 10.7949Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.15337 19.4521C6.43726 19.4521 5.85938 20.0328 5.85938 20.7461C5.85938 21.4596 6.43637 22.0411 7.15337 22.0411C7.87038 22.0411 8.44737 21.4596 8.44737 20.7461C8.44737 20.0328 7.86949 19.4521 7.15337 19.4521ZM18.4346 19.4521C17.7185 19.4521 17.1406 20.0328 17.1406 20.7461C17.1406 21.4596 17.7176 22.0411 18.4346 22.0411C19.1498 22.0411 19.7296 21.4614 19.7296 20.7461C19.7296 20.031 19.1489 19.4521 18.4346 19.4521Z"
                                        fill="white" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Order_Management')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub ffff"
                                style="display: {{Request::is('admin/report/order') || Request::is('admin/refund-section/refund/details/*') || Request::is('admin/refund-section/refund/list/*') || Request::is('admin/order*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/orders/list/all')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.orders.list',['all'])}}"
                                        title="{{\App\CPU\translate('All')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M11.8743 22.0457C9.45143 22.0457 7.02857 22.0457 4.60571 22.0457C3.05143 22.0457 2 20.9943 2 19.44C2 17.7029 2 15.9657 2 14.2057V12.0343V9.77143C2 8.03429 2 6.29714 2 4.53714C2 3.21143 2.8 2.25143 4.10286 2.02286C4.26286 2 4.44571 2 4.62857 2C7.6 2 10.5714 2 13.5429 2H19.3714C20.6971 2 21.6571 2.73143 21.9314 3.96571C21.9771 4.14857 22 4.37714 22 4.62857V9.54286C22 12.8343 22 16.1029 22 19.3943C22 20.88 20.8343 22.0229 19.3714 22.0229C16.88 22.0457 14.3886 22.0457 11.8743 22.0457ZM4.67429 3.53143C3.89714 3.53143 3.53143 3.89714 3.53143 4.67428C3.53143 9.56571 3.53143 14.48 3.53143 19.3943C3.53143 20.1714 3.89714 20.5143 4.65143 20.5143H19.3714C20.1486 20.5143 20.4914 20.1714 20.4914 19.3943V4.65143C20.4914 3.89714 20.1257 3.53143 19.3714 3.53143H12.0343H4.67429Z"
                                                fill="white" />
                                            <path
                                                d="M10.8017 15.8059C10.756 15.8059 10.6874 15.8059 10.6417 15.7831C10.4131 15.7145 10.2303 15.5317 10.0703 15.3717C9.15599 14.4802 8.21885 13.5888 7.28171 12.7431C6.96171 12.4459 6.61885 12.1259 6.64171 11.7145C6.64171 11.3488 6.98457 11.0059 7.35028 11.0059C7.37314 11.0059 7.39599 11.0059 7.41885 11.0059C7.71599 11.0288 7.94456 11.2345 8.19599 11.4631C8.44742 11.7145 8.72171 11.9431 8.97314 12.1717C9.11028 12.2859 9.24742 12.4002 9.36171 12.5145L9.95599 13.0631C10.0017 13.0859 10.0246 13.1317 10.0703 13.1774C10.2531 13.3602 10.5046 13.6117 10.7789 13.6117C10.8017 13.6117 10.8246 13.6117 10.8474 13.6117C10.9617 13.5888 11.0531 13.5202 11.1217 13.4517C11.3274 13.2688 11.5331 13.0631 11.716 12.8574C11.8074 12.7659 11.876 12.6745 11.9674 12.6059L15.0989 9.40595L15.9674 8.51452C16.1503 8.33166 16.3331 8.24023 16.5388 8.24023C16.7217 8.24023 16.9046 8.30881 17.0417 8.46881C17.3617 8.78881 17.3617 9.22309 17.0417 9.56595C16.4474 10.1831 15.8531 10.7774 15.2589 11.3717L15.2131 11.4174C13.956 12.6974 12.676 14.0231 11.396 15.3717C11.3731 15.3945 11.3503 15.4402 11.3046 15.4631C11.1674 15.6231 11.0303 15.7602 10.8246 15.7831C10.8474 15.8059 10.8246 15.8059 10.8017 15.8059Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('All')}}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Model\Order::count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/pending')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['pending'])}}"
                                        title="{{\App\CPU\translate('pending')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.30521 3.5 3.5 7.30521 3.5 12C3.5 16.6948 7.30521 20.5 12 20.5C16.6948 20.5 20.5 16.6948 20.5 12C20.5 7.30521 16.6948 3.5 12 3.5ZM2 12C2 6.47679 6.47679 2 12 2C17.5232 2 22 6.47679 22 12C22 17.5232 17.5232 22 12 22C6.47679 22 2 17.5232 2 12Z" fill="white"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.6602 7.09766C12.0744 7.09766 12.4102 7.43344 12.4102 7.84766V12.2688L15.8144 14.2996C16.1701 14.5118 16.2865 14.9722 16.0743 15.3279C15.862 15.6836 15.4016 15.8 15.0459 15.5878L11.2759 13.3388C11.0491 13.2034 10.9102 12.9588 10.9102 12.6947V7.84766C10.9102 7.43344 11.2459 7.09766 11.6602 7.09766Z" fill="white"/>
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('pending')}}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'pending'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/confirmed')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['confirmed'])}}"
                                        title="{{\App\CPU\translate('confirmed')}}">
                                        <img src="{{ asset('public/assets/front-end/img/confirmationicon.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('confirmed')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'confirmed'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="d-none nav-item {{Request::is('admin/orders/list/processing')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['processing'])}}"
                                        title="{{\App\CPU\translate('Packaging')}}">
                                        <img src="{{ asset('public/assets/front-end/img/package-icon.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Packaging')}}
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'processing'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/shipped')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['shipped'])}}"
                                        title="{{\App\CPU\translate('Packaging')}}">
                                        <img src="{{ asset('public/assets/front-end/img/package-icon.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Shipped')}}
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'shipped'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/out_for_delivery')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['out_for_delivery'])}}"
                                        title="{{\App\CPU\translate('out_for_delivery')}}">
                                        
                                        <img src="{{ asset('public/assets/front-end/img/out-of-the-box.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('out_for_delivery')}}
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'out_for_delivery'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/delivered')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['delivered'])}}"
                                        title="{{\App\CPU\translate('delivered')}}">
                                        <img src="{{ asset('public/assets/front-end/img/box.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('delivered')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'delivered'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/returned')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['returned'])}}"
                                    title="{{\App\CPU\translate('returned')}}">
                                    <img src="{{ asset('public/assets/front-end/img/product-return.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('returned')}}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{\App\Model\Order::where('order_status','returned')->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/failed')?'active':''}} d-none">
                                    <a class="nav-link " href="{{route('admin.orders.list',['failed'])}}"
                                        title="{{\App\CPU\translate('failed')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Failed_to_Deliver')}}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'failed'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/orders/list/canceled')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['canceled'])}}"
                                        title="{{\App\CPU\translate('canceled')}}">
                                          <img src="{{ asset('public/assets/front-end/img/cancel.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('canceled')}}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'canceled'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/report/order')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.report.order')}}"
                                        title="{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Report')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M7.48549 6.96064C7.66189 6.96064 7.86035 6.96064 8.03675 6.96064C8.38957 6.96064 8.72033 6.96064 9.02904 6.93859C9.42595 6.91654 9.75671 6.58578 9.73466 6.21092C9.71261 5.83605 9.4039 5.52734 8.98494 5.52734C8.91878 5.52734 8.85263 5.52734 8.78648 5.52734C8.67623 5.52734 8.56597 5.52734 8.45572 5.52734C8.38957 5.52734 8.30137 5.52734 8.23521 5.52734H7.9706C7.81625 5.52734 7.63984 5.52734 7.48549 5.52734C7.08857 5.54939 6.75781 5.8581 6.75781 6.23297C6.75781 6.62988 7.08857 6.93859 7.48549 6.96064Z"
                                                fill="white" />
                                            <path
                                                d="M20.8247 7.00551C20.2293 4.02867 17.8038 2.02205 14.7828 2.02205C11.8721 2 8.93936 2 6.44763 2C6.22712 2 6.00662 2.02205 5.80816 2.06615C4.74973 2.30871 4.0882 3.19074 4.06615 4.31533C4.06615 5.52811 4.06615 6.7409 4.0441 7.93164L4.02205 14.2602C4.02205 16.0243 4 17.8104 4 19.5965C4 20.9857 4.97023 21.9559 6.35943 21.9559C10.4168 21.9779 14.4741 21.9779 18.5094 22H18.5755C19.9206 21.9779 20.8688 21.0077 20.8688 19.6406V19.4201V17.7663C20.8909 14.613 20.8909 11.3936 20.8909 8.2183C20.935 7.75524 20.8909 7.35832 20.8247 7.00551ZM5.5215 4.31533C5.5215 3.74201 5.85226 3.41125 6.42558 3.41125H6.64609V3.34509L6.66814 3.41125C9.40243 3.41125 12.1147 3.4333 14.849 3.4333C15.0033 3.4333 15.1577 3.45535 15.3341 3.45535H15.3561C15.4443 3.45535 15.5325 3.4774 15.6207 3.4774H15.6648V7.22602C15.6648 8.19625 16.2823 8.83572 17.2525 8.83572H19.3694C19.3694 8.83572 19.3914 8.83572 19.4135 8.83572L19.4796 8.85777V8.96803C19.4796 9.03418 19.4796 9.07828 19.4796 9.14443C19.4796 12.6284 19.4576 16.1125 19.4355 19.6185C19.4355 20.2139 19.1047 20.5447 18.5094 20.5447L6.35943 20.5006C6.07277 20.5006 5.83021 20.4123 5.67586 20.258C5.5215 20.1036 5.4333 19.8611 5.4333 19.5744L5.5215 4.31533ZM17.0981 7.40243L17.1202 4.02867L17.1863 4.07277C18.4212 4.84454 19.1709 5.92503 19.4576 7.35833V7.40243H17.0981Z"
                                                fill="white" />
                                            <path
                                                d="M9.18352 11.2838C8.6543 11.2838 8.14714 11.2838 7.61792 11.3058C7.35331 11.3058 7.11075 11.4381 6.97845 11.6366C6.84614 11.813 6.82409 12.0335 6.9123 12.254C7.02255 12.5407 7.28716 12.7171 7.66202 12.7171C8.25739 12.7171 8.85276 12.7171 9.44813 12.695H10.1758H11.0137C11.3665 12.695 11.7414 12.695 12.0942 12.673C12.3588 12.673 12.6014 12.5407 12.7337 12.3422C12.866 12.1658 12.888 11.9453 12.7998 11.7248C12.6896 11.4381 12.425 11.2617 12.0281 11.2617C11.5209 11.2617 10.9917 11.2617 10.4625 11.2838H9.18352Z"
                                                fill="white" />
                                            <path
                                                d="M9.07261 8.19588H8.65365C8.32289 8.19588 7.94803 8.19588 7.59522 8.21793C7.44086 8.21793 7.26446 8.26203 7.13215 8.35023C6.86754 8.48254 6.75729 8.8133 6.82344 9.09996C6.91164 9.40867 7.1542 9.60712 7.48496 9.62918H7.55111C8.41109 9.62918 9.38132 9.60713 10.528 9.58507C10.6382 9.58507 10.7485 9.54097 10.8808 9.49687C11.1454 9.36457 11.3218 9.05586 11.2556 8.7692C11.1674 8.39433 10.9028 8.17383 10.5059 8.17383H10.3516C10.0649 8.17383 9.77824 8.17383 9.49158 8.17383L9.07261 8.19588Z"
                                                fill="white" />
                                            <path
                                                d="M12.5547 18.1412C12.5547 18.3176 12.6429 18.494 12.7752 18.6263C12.9075 18.7586 13.0839 18.8468 13.2603 18.8468H13.2824C13.6352 18.8468 13.988 18.494 13.988 18.1412C13.988 17.7884 13.6572 17.4355 13.2824 17.4355C12.9075 17.4576 12.5547 17.7884 12.5547 18.1412Z"
                                                fill="white" />
                                            <path
                                                d="M16.3281 18.8683H16.3502C16.703 18.8683 17.0337 18.5375 17.0558 18.1847C17.0558 18.0083 16.9896 17.8539 16.8573 17.6996C16.725 17.5452 16.5266 17.457 16.3502 17.457C16.1737 17.457 15.9973 17.5452 15.865 17.6775C15.7327 17.8098 15.6445 17.9862 15.6445 18.1627C15.6445 18.5155 15.9973 18.8462 16.3281 18.8683Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Order_Report')}}
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        @endif
                        <!--Order Management Ends-->

                        <!-- User Section -->
                        @if(\App\CPU\Helpers::module_permission_check('user_section'))

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/company') || Request::is('admin/company/show*') || (Request::is('admin/customer/*') || Request::is('admin/customer/list') || Request::is('admin/customer/view*') || Request::is('admin/reviews*') || Request::is('admin/customer/loyalty/report'))?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                    title="{{\App\CPU\translate('Customers')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M5.95006 22.0005C5.76844 21.9324 5.63224 21.7962 5.51873 21.5692C4.83768 20.1844 4.15664 18.8223 3.45289 17.4375L3.40749 17.324C3.08967 16.6883 2.77185 16.0527 2.45403 15.4171L2 14.509V14.2139C2.09081 14.0323 2.24971 13.8961 2.49943 13.7825C2.83995 13.6236 3.18048 13.4647 3.4983 13.2831L3.52099 13.2604C3.81611 13.1015 4.08854 13.0334 4.38365 13.0334C4.61067 13.0334 4.86039 13.0788 5.0874 13.1696C5.1101 13.1696 5.1328 13.1696 5.1555 13.1696C5.22361 13.1696 5.29171 13.1469 5.35982 13.1015C5.54143 12.9653 5.70034 12.8064 5.88195 12.6475C6.19977 12.3751 6.54029 12.0799 6.88082 11.8302C7.51646 11.3989 8.26561 11.1719 9.06016 11.1719C9.96822 11.1719 10.8763 11.467 11.5573 12.0345C12.2611 12.6021 12.2838 12.6021 13.1691 12.6021H14.5766C14.849 12.6021 15.1442 12.6021 15.4166 12.6021C16.3019 12.6021 16.8695 12.9653 17.1873 13.7371C17.1873 13.7598 17.21 13.7598 17.21 13.7825L17.2554 13.8052H17.3235L17.3689 13.7598C17.3916 13.7144 17.4143 13.6917 17.4597 13.6463L17.8456 13.2377C18.2316 12.8291 18.6175 12.4205 19.0034 12.0118C19.3666 11.6259 19.8434 11.4216 20.3201 11.4216C20.6379 11.4216 20.9557 11.5124 21.2281 11.694C21.6595 11.9664 21.9319 12.3751 22.0227 12.8291C22.1135 13.2831 22 13.7371 21.7049 14.1458C20.6833 15.5533 19.7071 16.8472 18.7537 18.0731C17.9818 19.072 16.8922 19.5714 15.5301 19.5714C14.622 19.5714 13.6913 19.5714 12.7832 19.5714H8.69693L8.65153 19.5941C8.62883 19.6849 8.60613 19.753 8.60613 19.8438C8.56073 20.0255 8.53802 20.1844 8.46992 20.3433C8.33371 20.7292 8.03859 21.0243 7.58456 21.2513C7.26674 21.4102 6.97162 21.5691 6.6538 21.7281L6.26787 21.9324L5.95006 22.0005ZM4.38365 14.1685C4.31555 14.1685 4.24745 14.1912 4.15664 14.2139C3.95233 14.3047 3.77071 14.3955 3.5664 14.4863C3.47559 14.5317 3.38479 14.5771 3.29398 14.6225L3.27128 14.6906L6.26787 20.7065L6.33598 20.7292C6.33598 20.7292 6.83541 20.4795 6.99432 20.4114C7.47106 20.1617 7.58456 19.8665 7.33485 19.3671L5.72304 16.1208C5.47332 15.5987 5.22361 15.0992 4.95119 14.5771C4.81498 14.3047 4.61067 14.1685 4.38365 14.1685ZM9.06016 12.3297C8.58343 12.3297 8.1294 12.4659 7.65267 12.7156C7.22134 12.9653 6.83542 13.2831 6.44949 13.6009C6.26788 13.7371 6.10896 13.8961 5.92735 14.0323L5.90465 14.0777L5.95006 14.1912C5.97276 14.2366 5.99546 14.282 5.99546 14.3047L6.49489 15.2808C7.01702 16.3024 7.51646 17.3467 8.03859 18.3682C8.1294 18.5499 8.22021 18.5726 8.37912 18.5726C9.76391 18.5726 11.1714 18.5726 12.5562 18.5726C13.5096 18.5726 14.4631 18.5726 15.3939 18.5726C16.4835 18.5726 17.3689 18.1412 18.0045 17.2559C18.3905 16.7337 18.7991 16.2116 19.185 15.6668L20.0023 14.5544C20.252 14.2366 20.479 13.9188 20.706 13.6009C20.8422 13.3966 20.8876 13.1696 20.7968 12.9653C20.7287 12.761 20.5471 12.6248 20.3201 12.5794C20.2747 12.5794 20.2293 12.5794 20.2066 12.5794C20.025 12.5794 19.8661 12.6702 19.6844 12.8518C19.3212 13.2377 18.958 13.6236 18.5948 14.0096C18.1407 14.4863 17.6867 14.9857 17.2327 15.4852C16.8468 15.9165 16.37 16.1208 15.8025 16.1208H15.8252C15.6663 16.1208 15.5074 16.1208 15.3712 16.1208H13.4188C13.1464 16.1208 12.874 16.1208 12.6016 16.1208C12.3973 16.1208 12.2384 16.03 12.1249 15.8938C12.0341 15.7576 11.9886 15.5987 12.034 15.4171C12.1022 15.1673 12.3065 15.0084 12.6243 15.0084H14.0091C14.5085 15.0084 14.9852 15.0084 15.4847 15.0084C15.7344 15.0084 15.8933 14.9403 16.0295 14.8041C16.2111 14.5998 16.2565 14.3728 16.1657 14.1458C16.0522 13.8734 15.8479 13.7371 15.5528 13.7371C15.3258 13.7371 15.076 13.7371 14.849 13.7371C14.5766 13.7371 14.3042 13.7371 14.0318 13.7371C13.6459 13.7371 13.328 13.7371 13.0329 13.7598C12.9648 13.7598 12.8967 13.7598 12.8286 13.7598C12.1022 13.7598 11.4892 13.5328 10.9898 13.0334C10.9671 13.0107 10.9444 12.988 10.9217 12.988C10.3087 12.5567 9.6731 12.3297 9.06016 12.3297Z"
                                            fill="white" />
                                        <path
                                            d="M12.6708 10.2179C11.6492 10.2179 10.6504 10.2179 9.62882 10.2179C9.3337 10.2179 9.10668 10.0136 9.08397 9.7185C9.06127 9.03746 9.08397 8.1748 9.538 7.38025C9.85582 6.83542 10.2645 6.40409 10.7866 6.08627C10.8093 6.06356 10.832 6.06357 10.8547 6.04087L10.9001 6.01816V5.95006C9.90123 4.83768 10.1736 3.4756 10.9455 2.68104C11.3995 2.22701 11.9898 2 12.6027 2C13.1021 2 13.5789 2.15891 13.9875 2.47673C14.5323 2.90806 14.8502 3.4756 14.8956 4.15664C14.941 4.81498 14.7593 5.40523 14.2826 5.92736V5.99546C14.3734 6.06357 14.4642 6.15437 14.555 6.22248C14.7593 6.38139 14.941 6.5176 15.0999 6.69921C15.7355 7.33485 16.0533 8.1521 16.076 9.10557C16.076 9.28718 16.076 9.49149 16.076 9.6731C16.076 10.0136 15.849 10.2406 15.5085 10.2406C14.5777 10.2179 13.6243 10.2179 12.6708 10.2179ZM12.6027 6.6538C12.126 6.6538 11.6492 6.81272 11.1952 7.15324C10.5596 7.62997 10.2191 8.26561 10.1964 9.08286L10.2418 9.12826H14.9864L15.0318 9.08286C14.9637 8.084 14.5323 7.35755 13.7378 6.92622C13.3519 6.74461 12.9659 6.6538 12.6027 6.6538ZM12.6254 3.08967C12.3076 3.08967 11.9898 3.22588 11.74 3.4756C11.4903 3.72531 11.3541 4.04313 11.3541 4.36095C11.3541 5.01929 11.9217 5.56414 12.6027 5.58684C13.2837 5.58684 13.8286 5.042 13.8286 4.38365C13.8286 4.04313 13.6924 3.72531 13.4654 3.4756C13.2383 3.22588 12.9205 3.08967 12.6254 3.08967Z"
                                            fill="white" />
                                    </svg>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Customers')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/company') || Request::is('admin/company/show*') || (Request::is('admin/customer/*') || Request::is('admin/customer/list') || Request::is('admin/customer/bulk-import') || Request::is('admin/customer/view*') || Request::is('admin/reviews*') || Request::is('admin/customer/loyalty/report'))?'block':'none'}}">
                                    <li
                                        class="nav-item {{Request::is('admin/customer/list') || Request::is('admin/customer/view*')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.customer.list')}}"
                                            title="{{\App\CPU\translate('Customers_List')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M5.87389 21.9984C5.61804 21.9092 5.34404 21.8531 5.11131 21.7227C4.36854 21.3034 4.00045 20.6498 4.00045 19.7964C3.9988 17.8438 4.0021 15.8911 4.00375 13.9384C4.00705 11.106 4.01035 8.2752 4.01365 5.44276C4.01531 4.49696 4.4098 3.77729 5.27802 3.37619C5.55697 3.24745 5.88874 3.21113 6.20071 3.17812C6.50112 3.14511 6.80813 3.17152 7.1234 3.17152C7.12835 3.07083 7.12835 2.98665 7.13661 2.90412C7.19108 2.37428 7.59547 2.00124 8.13357 2.00124C10.4576 1.99959 12.7817 1.99959 15.1057 2.00124C15.6934 2.00124 16.0994 2.40399 16.1159 2.9883C16.1176 3.03947 16.1159 3.09064 16.1159 3.17152C16.2678 3.17152 16.4081 3.16492 16.5484 3.17317C16.9049 3.19298 17.2697 3.17317 17.613 3.25075C18.5539 3.46203 19.2124 4.30549 19.2306 5.27274C19.2405 5.73491 19.2339 6.19708 19.2323 6.65925C19.229 11.0136 19.224 15.3695 19.2191 19.7238C19.2174 20.696 18.7981 21.4107 17.9085 21.825C17.7352 21.9059 17.542 21.9439 17.3572 22C13.5294 21.9984 9.70165 21.9984 5.87389 21.9984ZM7.10194 4.3418C6.83784 4.3418 6.56549 4.3418 6.29479 4.3418C5.57348 4.3418 5.18394 4.72804 5.18394 5.44936C5.17898 10.2213 5.17568 14.9932 5.17238 19.7651C5.17238 20.4237 5.57348 20.8264 6.22712 20.8264C9.81389 20.8264 13.4007 20.8264 16.9874 20.8264C17.0782 20.8264 17.1706 20.8248 17.2581 20.805C17.7632 20.6993 18.0488 20.3098 18.0488 19.7288C18.0537 15.1005 18.057 10.4722 18.0603 5.84385C18.0603 5.65568 18.0653 5.46586 18.0587 5.27769C18.0438 4.86999 17.7731 4.4689 17.3786 4.40122C16.9693 4.33025 16.5451 4.3484 16.1308 4.32694C16.1209 4.39462 16.1159 4.41278 16.1143 4.43093C16.0845 5.1704 15.7247 5.51538 14.9852 5.51538C13.9305 5.51538 12.8758 5.51538 11.821 5.51538C10.5979 5.51538 9.37318 5.51703 8.15008 5.51538C7.69121 5.51538 7.32312 5.26614 7.19273 4.85514C7.14321 4.70163 7.13495 4.53657 7.10194 4.3418ZM8.30854 4.3319C10.5253 4.3319 12.7256 4.3319 14.9291 4.3319C14.9291 3.94235 14.9291 3.56271 14.9291 3.18637C12.7157 3.18637 10.5154 3.18637 8.30854 3.18637C8.30854 3.57097 8.30854 3.93905 8.30854 4.3319Z" fill="white"/>
                                                    <path d="M13.9204 16.4509C14.5905 16.4509 15.259 16.4492 15.9291 16.4509C16.3121 16.4509 16.5729 16.6869 16.5762 17.0286C16.5795 17.3802 16.317 17.6212 15.9242 17.6212C14.579 17.6228 13.2337 17.6228 11.8868 17.6212C11.4956 17.6212 11.2249 17.3769 11.2266 17.0319C11.2282 16.6869 11.4989 16.4492 11.8918 16.4492C12.5685 16.4509 13.2453 16.4509 13.9204 16.4509Z" fill="white"/>
                                                    <path d="M13.8924 9.81214C13.2222 9.81214 12.5521 9.81379 11.8836 9.81214C11.494 9.81214 11.2217 9.5662 11.2266 9.22123C11.2299 8.88285 11.4973 8.64186 11.8753 8.64186C13.2222 8.64021 14.5675 8.64021 15.9144 8.64186C16.2923 8.64186 16.5597 8.8845 16.563 9.22288C16.5663 9.5662 16.2923 9.81214 15.9028 9.81214C15.2327 9.81379 14.5625 9.81214 13.8924 9.81214Z" fill="white"/>
                                                    <path d="M13.8874 13.7179C13.2239 13.7179 12.5603 13.7179 11.8968 13.7179C11.5006 13.7179 11.2316 13.4835 11.2266 13.1402C11.2217 12.7886 11.4957 12.5476 11.9017 12.5476C13.242 12.5476 14.5807 12.546 15.921 12.5476C16.3699 12.5476 16.6571 12.9091 16.5333 13.3069C16.4541 13.5594 16.2313 13.7162 15.9358 13.7179C15.2525 13.7195 14.5691 13.7179 13.8874 13.7179Z" fill="white"/>
                                                    <path d="M7.944 13.2184C8.41113 12.7496 8.83533 12.3188 9.26614 11.8963C9.35197 11.8121 9.45761 11.7345 9.56985 11.6949C9.81249 11.6074 10.0815 11.7131 10.2218 11.9227C10.3704 12.1439 10.3704 12.4327 10.1806 12.6275C9.58141 13.2415 8.97398 13.849 8.35831 14.4481C8.13878 14.6611 7.82186 14.6611 7.59573 14.4547C7.31677 14.2005 7.04938 13.9348 6.79518 13.6558C6.58391 13.4247 6.61527 13.0715 6.8348 12.8619C7.04772 12.6572 7.38775 12.6391 7.61554 12.8404C7.73438 12.9444 7.82681 13.0814 7.944 13.2184Z" fill="white"/>
                                                    <path d="M7.97893 17.142C8.08952 17.0067 8.1704 16.8961 8.26448 16.7987C8.61606 16.4422 8.96764 16.084 9.32747 15.7374C9.56681 15.5063 9.92829 15.5195 10.1528 15.7489C10.3723 15.975 10.3839 16.325 10.1544 16.5594C9.56681 17.1585 8.97424 17.7527 8.37342 18.3387C8.13408 18.5714 7.81056 18.5681 7.56462 18.3404C7.30383 18.0961 7.04963 17.8435 6.80534 17.5811C6.58416 17.3434 6.60232 16.9902 6.82515 16.7706C7.04798 16.5528 7.39626 16.5462 7.63725 16.7657C7.74619 16.8647 7.83862 16.9852 7.97893 17.142Z" fill="white"/>
                                                    <path d="M7.96953 9.0408C8.10323 8.884 8.19401 8.7635 8.298 8.65786C8.63968 8.30959 8.983 7.96296 9.33458 7.62458C9.56732 7.40175 9.92385 7.41 10.1467 7.62788C10.3712 7.84741 10.3926 8.2056 10.1665 8.43668C9.57557 9.04246 8.97805 9.63997 8.37393 10.2309C8.13624 10.4636 7.80942 10.457 7.56513 10.2276C7.30929 9.9866 7.0617 9.73736 6.81906 9.48317C6.59457 9.24878 6.59953 8.89885 6.81741 8.67272C7.04024 8.43998 7.39512 8.43008 7.64106 8.65951C7.74835 8.7602 7.83583 8.88234 7.96953 9.0408Z" fill="white"/>
                                            </svg>
                                            <span class="text-truncate">{{\App\CPU\translate('Customers_List')}}
                                            </span>
                                        </a>
                                    </li>
                                    <li
                                        class="nav-item {{Request::is('admin/company') || Request::is('admin/company/show*')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.company.index')}}"
                                            title="{{\App\CPU\translate('companies')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M5.87389 21.9984C5.61804 21.9092 5.34404 21.8531 5.11131 21.7227C4.36854 21.3034 4.00045 20.6498 4.00045 19.7964C3.9988 17.8438 4.0021 15.8911 4.00375 13.9384C4.00705 11.106 4.01035 8.2752 4.01365 5.44276C4.01531 4.49696 4.4098 3.77729 5.27802 3.37619C5.55697 3.24745 5.88874 3.21113 6.20071 3.17812C6.50112 3.14511 6.80813 3.17152 7.1234 3.17152C7.12835 3.07083 7.12835 2.98665 7.13661 2.90412C7.19108 2.37428 7.59547 2.00124 8.13357 2.00124C10.4576 1.99959 12.7817 1.99959 15.1057 2.00124C15.6934 2.00124 16.0994 2.40399 16.1159 2.9883C16.1176 3.03947 16.1159 3.09064 16.1159 3.17152C16.2678 3.17152 16.4081 3.16492 16.5484 3.17317C16.9049 3.19298 17.2697 3.17317 17.613 3.25075C18.5539 3.46203 19.2124 4.30549 19.2306 5.27274C19.2405 5.73491 19.2339 6.19708 19.2323 6.65925C19.229 11.0136 19.224 15.3695 19.2191 19.7238C19.2174 20.696 18.7981 21.4107 17.9085 21.825C17.7352 21.9059 17.542 21.9439 17.3572 22C13.5294 21.9984 9.70165 21.9984 5.87389 21.9984ZM7.10194 4.3418C6.83784 4.3418 6.56549 4.3418 6.29479 4.3418C5.57348 4.3418 5.18394 4.72804 5.18394 5.44936C5.17898 10.2213 5.17568 14.9932 5.17238 19.7651C5.17238 20.4237 5.57348 20.8264 6.22712 20.8264C9.81389 20.8264 13.4007 20.8264 16.9874 20.8264C17.0782 20.8264 17.1706 20.8248 17.2581 20.805C17.7632 20.6993 18.0488 20.3098 18.0488 19.7288C18.0537 15.1005 18.057 10.4722 18.0603 5.84385C18.0603 5.65568 18.0653 5.46586 18.0587 5.27769C18.0438 4.86999 17.7731 4.4689 17.3786 4.40122C16.9693 4.33025 16.5451 4.3484 16.1308 4.32694C16.1209 4.39462 16.1159 4.41278 16.1143 4.43093C16.0845 5.1704 15.7247 5.51538 14.9852 5.51538C13.9305 5.51538 12.8758 5.51538 11.821 5.51538C10.5979 5.51538 9.37318 5.51703 8.15008 5.51538C7.69121 5.51538 7.32312 5.26614 7.19273 4.85514C7.14321 4.70163 7.13495 4.53657 7.10194 4.3418ZM8.30854 4.3319C10.5253 4.3319 12.7256 4.3319 14.9291 4.3319C14.9291 3.94235 14.9291 3.56271 14.9291 3.18637C12.7157 3.18637 10.5154 3.18637 8.30854 3.18637C8.30854 3.57097 8.30854 3.93905 8.30854 4.3319Z" fill="white"/>
                                                    <path d="M13.9204 16.4509C14.5905 16.4509 15.259 16.4492 15.9291 16.4509C16.3121 16.4509 16.5729 16.6869 16.5762 17.0286C16.5795 17.3802 16.317 17.6212 15.9242 17.6212C14.579 17.6228 13.2337 17.6228 11.8868 17.6212C11.4956 17.6212 11.2249 17.3769 11.2266 17.0319C11.2282 16.6869 11.4989 16.4492 11.8918 16.4492C12.5685 16.4509 13.2453 16.4509 13.9204 16.4509Z" fill="white"/>
                                                    <path d="M13.8924 9.81214C13.2222 9.81214 12.5521 9.81379 11.8836 9.81214C11.494 9.81214 11.2217 9.5662 11.2266 9.22123C11.2299 8.88285 11.4973 8.64186 11.8753 8.64186C13.2222 8.64021 14.5675 8.64021 15.9144 8.64186C16.2923 8.64186 16.5597 8.8845 16.563 9.22288C16.5663 9.5662 16.2923 9.81214 15.9028 9.81214C15.2327 9.81379 14.5625 9.81214 13.8924 9.81214Z" fill="white"/>
                                                    <path d="M13.8874 13.7179C13.2239 13.7179 12.5603 13.7179 11.8968 13.7179C11.5006 13.7179 11.2316 13.4835 11.2266 13.1402C11.2217 12.7886 11.4957 12.5476 11.9017 12.5476C13.242 12.5476 14.5807 12.546 15.921 12.5476C16.3699 12.5476 16.6571 12.9091 16.5333 13.3069C16.4541 13.5594 16.2313 13.7162 15.9358 13.7179C15.2525 13.7195 14.5691 13.7179 13.8874 13.7179Z" fill="white"/>
                                                    <path d="M7.944 13.2184C8.41113 12.7496 8.83533 12.3188 9.26614 11.8963C9.35197 11.8121 9.45761 11.7345 9.56985 11.6949C9.81249 11.6074 10.0815 11.7131 10.2218 11.9227C10.3704 12.1439 10.3704 12.4327 10.1806 12.6275C9.58141 13.2415 8.97398 13.849 8.35831 14.4481C8.13878 14.6611 7.82186 14.6611 7.59573 14.4547C7.31677 14.2005 7.04938 13.9348 6.79518 13.6558C6.58391 13.4247 6.61527 13.0715 6.8348 12.8619C7.04772 12.6572 7.38775 12.6391 7.61554 12.8404C7.73438 12.9444 7.82681 13.0814 7.944 13.2184Z" fill="white"/>
                                                    <path d="M7.97893 17.142C8.08952 17.0067 8.1704 16.8961 8.26448 16.7987C8.61606 16.4422 8.96764 16.084 9.32747 15.7374C9.56681 15.5063 9.92829 15.5195 10.1528 15.7489C10.3723 15.975 10.3839 16.325 10.1544 16.5594C9.56681 17.1585 8.97424 17.7527 8.37342 18.3387C8.13408 18.5714 7.81056 18.5681 7.56462 18.3404C7.30383 18.0961 7.04963 17.8435 6.80534 17.5811C6.58416 17.3434 6.60232 16.9902 6.82515 16.7706C7.04798 16.5528 7.39626 16.5462 7.63725 16.7657C7.74619 16.8647 7.83862 16.9852 7.97893 17.142Z" fill="white"/>
                                                    <path d="M7.96953 9.0408C8.10323 8.884 8.19401 8.7635 8.298 8.65786C8.63968 8.30959 8.983 7.96296 9.33458 7.62458C9.56732 7.40175 9.92385 7.41 10.1467 7.62788C10.3712 7.84741 10.3926 8.2056 10.1665 8.43668C9.57557 9.04246 8.97805 9.63997 8.37393 10.2309C8.13624 10.4636 7.80942 10.457 7.56513 10.2276C7.30929 9.9866 7.0617 9.73736 6.81906 9.48317C6.59457 9.24878 6.59953 8.89885 6.81741 8.67272C7.04024 8.43998 7.39512 8.43008 7.64106 8.65951C7.74835 8.7602 7.83583 8.88234 7.96953 9.0408Z" fill="white"/>
                                            </svg>
                                            <span class="text-truncate">{{\App\CPU\translate('companies')}}
                                            </span>
                                        </a>
                                    </li>

                                    <li class="d-none nav-item {{ Request::is('admin/customer/bulk-import') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('admin.customer.bulk-import') }}"
                                            title="{{ \App\CPU\translate('customer_bulk_import') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M14.4869 3.01175H7.83489C5.75489 3.00378 4.05089 4.66078 4.00089 6.74078V17.4778C3.95589 19.5798 5.62389 21.3198 7.72489 21.3648C7.76189 21.3648 7.79889 21.3658 7.83489 21.3648H15.8229C17.9129 21.2908 19.5649 19.5688 19.553 17.4778V8.28778L14.4869 3.01175Z"
                                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M14.2266 3V5.909C14.2266 7.329 15.3756 8.48 16.7956 8.484H19.5496"
                                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M11.3906 10.1582V16.1992" stroke="white" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M13.7369 12.5152L11.3919 10.1602L9.04688 12.5152" stroke="white"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span class="text-truncate">{{ \App\CPU\translate('Upload_customers') }}</span>
                                        </a>
                                    </li>

                                    
                                    
                                    <li class="nav-item {{Request::is('admin/customer/user-chat')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.user-chat')}}"
                                            title="{{\App\CPU\translate('Customer')}} {{\App\CPU\translate('Reviews')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M3.8757 2C9.29188 2 14.7077 2 20.1238 2C20.1528 2.01174 20.181 2.02896 20.2111 2.03444C20.9598 2.17767 21.4947 2.59797 21.8101 3.2883C21.8947 3.47379 21.9369 3.67847 21.9988 3.87453C21.9988 7.88461 21.9988 11.8943 21.9988 15.9044C21.9111 16.1607 21.8579 16.4358 21.7291 16.6699C21.2967 17.4557 20.6021 17.8063 19.7165 17.8055C18.1675 17.8044 16.619 17.8079 15.07 17.8012C14.9159 17.8004 14.8419 17.8505 14.7754 17.9852C14.2725 19.005 13.7661 20.0233 13.2491 21.0361C13.1443 21.2419 13.0132 21.445 12.8535 21.6102C12.5169 21.9585 12.1045 22.0978 11.627 21.9275C11.2001 21.7753 10.948 21.4415 10.7539 21.0537C10.2421 20.0311 9.72862 19.0093 9.2234 17.9832C9.15648 17.847 9.08017 17.8004 8.92793 17.8012C7.379 17.8075 5.83046 17.8075 4.28153 17.8044C2.91848 17.8012 2.00117 16.8827 2.00117 15.5264C2.00078 11.7652 2.00235 8.00358 2 4.24239C1.99961 3.46519 2.28646 2.82417 2.93531 2.39447C3.21395 2.21054 3.56028 2.12836 3.8757 2ZM11.9976 20.86C12.0966 20.6921 12.1714 20.5809 12.2309 20.4624C12.7964 19.3353 13.3642 18.2098 13.9191 17.0776C14.0721 16.7653 14.2889 16.6276 14.6404 16.6295C16.3325 16.6393 18.0247 16.635 19.7172 16.6335C20.4334 16.6327 20.8271 16.2362 20.8271 15.5209C20.8271 11.7785 20.8271 8.03606 20.8271 4.29366C20.8271 3.55989 20.4412 3.17207 19.7106 3.17207C14.5687 3.17207 9.42689 3.17207 4.28505 3.17207C3.5638 3.17207 3.17285 3.56067 3.17285 4.278C3.17246 6.2175 3.17285 8.15699 3.17285 10.0969C3.17285 11.9256 3.17442 13.7547 3.17168 15.5835C3.17129 15.8934 3.26638 16.1572 3.49375 16.3705C3.7176 16.5802 3.99115 16.6354 4.28661 16.635C5.96586 16.6335 7.64511 16.6409 9.32436 16.6288C9.6977 16.626 9.92781 16.7606 10.0918 17.098C10.6404 18.2258 11.2083 19.3443 11.7706 20.4655C11.8278 20.5798 11.8982 20.6882 11.9976 20.86Z" fill="white"/>
                                                    <path d="M15.1976 12.7415C15.2015 13.8655 14.4274 14.4286 13.507 14.0795C13.0601 13.9101 12.6402 13.6655 12.2191 13.4342C12.0559 13.3446 11.935 13.3466 11.7765 13.4373C11.4384 13.6311 11.0901 13.8076 10.7418 13.9825C10.453 14.1273 10.1469 14.2177 9.81939 14.1735C9.2038 14.0901 8.78898 13.6123 8.8062 12.9423C8.81677 12.5361 8.87273 12.122 8.97565 11.7295C9.07897 11.3347 9.00148 11.0631 8.68097 10.8079C8.39803 10.5825 8.14953 10.3093 7.90924 10.0366C7.66113 9.75521 7.5179 9.41748 7.56291 9.03279C7.62122 8.53461 7.92255 8.20392 8.38159 8.06108C8.76902 7.94055 9.17876 7.85602 9.58262 7.83332C10.0323 7.80828 10.3047 7.64235 10.4514 7.20718C10.552 6.90858 10.7163 6.62799 10.8772 6.35405C11.1069 5.96349 11.4215 5.66724 11.9013 5.62928C12.4731 5.58428 12.8492 5.89853 13.1188 6.35522C13.3 6.66204 13.4663 6.98215 13.5978 7.31284C13.7238 7.62904 13.903 7.79028 14.2607 7.80671C14.6349 7.82354 15.0094 7.89711 15.3768 7.9789C15.9979 8.11666 16.3885 8.51582 16.435 9.05353C16.4749 9.51531 16.2562 9.88396 15.947 10.2013C15.6746 10.4808 15.398 10.7574 15.1064 11.0165C14.9644 11.1429 14.9409 11.2666 14.9741 11.4415C15.0653 11.9201 15.1401 12.4015 15.1976 12.7415ZM9.9497 13.0417C10.1211 12.9673 10.2413 12.9235 10.3536 12.8652C10.7864 12.6414 11.2208 12.4191 11.6473 12.1843C11.8876 12.052 12.1099 12.0528 12.349 12.1831C12.7881 12.4226 13.2327 12.6527 13.6768 12.8832C13.7778 12.9356 13.8866 12.9732 14.0255 13.0319C14.0197 12.9036 14.024 12.8139 14.0099 12.7275C13.9234 12.2015 13.8389 11.6751 13.7402 11.1515C13.6917 10.8948 13.7629 10.6952 13.9469 10.5187C14.3175 10.1626 14.6842 9.80217 15.0497 9.44096C15.1252 9.36621 15.1894 9.27972 15.2849 9.16819C15.1354 9.13062 15.0325 9.09579 14.926 9.08014C14.4439 9.00774 13.9617 8.93417 13.4777 8.87468C13.1806 8.83829 12.9748 8.70915 12.846 8.42855C12.6402 7.97969 12.4136 7.5406 12.1917 7.09956C12.1412 6.99937 12.0743 6.90741 11.9945 6.77866C11.9142 6.91249 11.8516 7.00211 11.8035 7.09916C11.5769 7.55234 11.3503 8.00591 11.1312 8.46299C11.0204 8.69428 10.8478 8.82655 10.5946 8.86177C10.1117 8.92908 9.62919 8.99835 9.14706 9.07114C9.01244 9.09149 8.88016 9.12827 8.70406 9.16702C8.81442 9.29186 8.88212 9.37795 8.95961 9.45426C9.32121 9.81038 9.68437 10.1653 10.0495 10.5175C10.2319 10.6936 10.3062 10.8952 10.2549 11.1515C10.1775 11.5401 10.116 11.9322 10.0518 12.3236C10.0162 12.5443 9.98884 12.7662 9.9497 13.0417Z" fill="white"/>
                                            </svg>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{\App\CPU\translate('Customer')}} {{\App\CPU\translate('Chats')}}
                                            </span>
                                        </a>
                                    </li>

                                    <li class="d-none nav-item {{Request::is('admin/reviews*')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.reviews.list')}}"
                                            title="{{\App\CPU\translate('Customer')}} {{\App\CPU\translate('Reviews')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M3.8757 2C9.29188 2 14.7077 2 20.1238 2C20.1528 2.01174 20.181 2.02896 20.2111 2.03444C20.9598 2.17767 21.4947 2.59797 21.8101 3.2883C21.8947 3.47379 21.9369 3.67847 21.9988 3.87453C21.9988 7.88461 21.9988 11.8943 21.9988 15.9044C21.9111 16.1607 21.8579 16.4358 21.7291 16.6699C21.2967 17.4557 20.6021 17.8063 19.7165 17.8055C18.1675 17.8044 16.619 17.8079 15.07 17.8012C14.9159 17.8004 14.8419 17.8505 14.7754 17.9852C14.2725 19.005 13.7661 20.0233 13.2491 21.0361C13.1443 21.2419 13.0132 21.445 12.8535 21.6102C12.5169 21.9585 12.1045 22.0978 11.627 21.9275C11.2001 21.7753 10.948 21.4415 10.7539 21.0537C10.2421 20.0311 9.72862 19.0093 9.2234 17.9832C9.15648 17.847 9.08017 17.8004 8.92793 17.8012C7.379 17.8075 5.83046 17.8075 4.28153 17.8044C2.91848 17.8012 2.00117 16.8827 2.00117 15.5264C2.00078 11.7652 2.00235 8.00358 2 4.24239C1.99961 3.46519 2.28646 2.82417 2.93531 2.39447C3.21395 2.21054 3.56028 2.12836 3.8757 2ZM11.9976 20.86C12.0966 20.6921 12.1714 20.5809 12.2309 20.4624C12.7964 19.3353 13.3642 18.2098 13.9191 17.0776C14.0721 16.7653 14.2889 16.6276 14.6404 16.6295C16.3325 16.6393 18.0247 16.635 19.7172 16.6335C20.4334 16.6327 20.8271 16.2362 20.8271 15.5209C20.8271 11.7785 20.8271 8.03606 20.8271 4.29366C20.8271 3.55989 20.4412 3.17207 19.7106 3.17207C14.5687 3.17207 9.42689 3.17207 4.28505 3.17207C3.5638 3.17207 3.17285 3.56067 3.17285 4.278C3.17246 6.2175 3.17285 8.15699 3.17285 10.0969C3.17285 11.9256 3.17442 13.7547 3.17168 15.5835C3.17129 15.8934 3.26638 16.1572 3.49375 16.3705C3.7176 16.5802 3.99115 16.6354 4.28661 16.635C5.96586 16.6335 7.64511 16.6409 9.32436 16.6288C9.6977 16.626 9.92781 16.7606 10.0918 17.098C10.6404 18.2258 11.2083 19.3443 11.7706 20.4655C11.8278 20.5798 11.8982 20.6882 11.9976 20.86Z" fill="white"/>
                                                    <path d="M15.1976 12.7415C15.2015 13.8655 14.4274 14.4286 13.507 14.0795C13.0601 13.9101 12.6402 13.6655 12.2191 13.4342C12.0559 13.3446 11.935 13.3466 11.7765 13.4373C11.4384 13.6311 11.0901 13.8076 10.7418 13.9825C10.453 14.1273 10.1469 14.2177 9.81939 14.1735C9.2038 14.0901 8.78898 13.6123 8.8062 12.9423C8.81677 12.5361 8.87273 12.122 8.97565 11.7295C9.07897 11.3347 9.00148 11.0631 8.68097 10.8079C8.39803 10.5825 8.14953 10.3093 7.90924 10.0366C7.66113 9.75521 7.5179 9.41748 7.56291 9.03279C7.62122 8.53461 7.92255 8.20392 8.38159 8.06108C8.76902 7.94055 9.17876 7.85602 9.58262 7.83332C10.0323 7.80828 10.3047 7.64235 10.4514 7.20718C10.552 6.90858 10.7163 6.62799 10.8772 6.35405C11.1069 5.96349 11.4215 5.66724 11.9013 5.62928C12.4731 5.58428 12.8492 5.89853 13.1188 6.35522C13.3 6.66204 13.4663 6.98215 13.5978 7.31284C13.7238 7.62904 13.903 7.79028 14.2607 7.80671C14.6349 7.82354 15.0094 7.89711 15.3768 7.9789C15.9979 8.11666 16.3885 8.51582 16.435 9.05353C16.4749 9.51531 16.2562 9.88396 15.947 10.2013C15.6746 10.4808 15.398 10.7574 15.1064 11.0165C14.9644 11.1429 14.9409 11.2666 14.9741 11.4415C15.0653 11.9201 15.1401 12.4015 15.1976 12.7415ZM9.9497 13.0417C10.1211 12.9673 10.2413 12.9235 10.3536 12.8652C10.7864 12.6414 11.2208 12.4191 11.6473 12.1843C11.8876 12.052 12.1099 12.0528 12.349 12.1831C12.7881 12.4226 13.2327 12.6527 13.6768 12.8832C13.7778 12.9356 13.8866 12.9732 14.0255 13.0319C14.0197 12.9036 14.024 12.8139 14.0099 12.7275C13.9234 12.2015 13.8389 11.6751 13.7402 11.1515C13.6917 10.8948 13.7629 10.6952 13.9469 10.5187C14.3175 10.1626 14.6842 9.80217 15.0497 9.44096C15.1252 9.36621 15.1894 9.27972 15.2849 9.16819C15.1354 9.13062 15.0325 9.09579 14.926 9.08014C14.4439 9.00774 13.9617 8.93417 13.4777 8.87468C13.1806 8.83829 12.9748 8.70915 12.846 8.42855C12.6402 7.97969 12.4136 7.5406 12.1917 7.09956C12.1412 6.99937 12.0743 6.90741 11.9945 6.77866C11.9142 6.91249 11.8516 7.00211 11.8035 7.09916C11.5769 7.55234 11.3503 8.00591 11.1312 8.46299C11.0204 8.69428 10.8478 8.82655 10.5946 8.86177C10.1117 8.92908 9.62919 8.99835 9.14706 9.07114C9.01244 9.09149 8.88016 9.12827 8.70406 9.16702C8.81442 9.29186 8.88212 9.37795 8.95961 9.45426C9.32121 9.81038 9.68437 10.1653 10.0495 10.5175C10.2319 10.6936 10.3062 10.8952 10.2549 11.1515C10.1775 11.5401 10.116 11.9322 10.0518 12.3236C10.0162 12.5443 9.98884 12.7662 9.9497 13.0417Z" fill="white"/>
                                            </svg>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{\App\CPU\translate('Customer')}} {{\App\CPU\translate('Reviews')}}
                                            </span>
                                        </a>
                                    </li>
                                    <!-- <li
                                        class="nav-item {{Request::is('admin/customer/wallet/report')?'active':''}}">
                                        <a class="nav-link" title="{{\App\CPU\translate('wallet')}}"
                                            href="{{route('admin.customer.wallet.report')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M21.6389 14.3943H17.5906C16.1042 14.3934 14.8993 13.1894 14.8984 11.703C14.8984 10.2165 16.1042 9.01263 17.5906 9.01172H21.6389" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M18.05 11.6445H17.7383" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.74766 3H16.3911C19.2892 3 21.6388 5.34951 21.6388 8.24766V15.4247C21.6388 18.3229 19.2892 20.6724 16.3911 20.6724H7.74766C4.84951 20.6724 2.5 18.3229 2.5 15.4247V8.24766C2.5 5.34951 4.84951 3 7.74766 3Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M7.03516 7.53906H12.4341" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            <span class="text-truncate">
                                                {{\App\CPU\translate('wallet')}}
                                            </span>
                                        </a>
                                    </li>
                                    <li
                                        class="d-none nav-item {{Request::is('admin/customer/loyalty/report')?'active':''}}">
                                        <a class="nav-link" title="{{\App\CPU\translate('Loyalty_Points')}}"
                                            href="{{route('admin.customer.loyalty.report')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M19.0984 4.93909C17.2332 3.05394 14.7383 2.00995 12.0742 2H12.0306C6.53074 2 2.03002 6.42605 2.00016 11.8675C1.98523 14.5988 3.01428 17.1509 4.89819 19.0522C6.77089 20.9423 9.29189 21.99 11.9958 22H12.0356C17.5019 22 21.9753 17.5739 22.0076 12.1325C22.0226 9.40496 20.9898 6.85037 19.0984 4.93909ZM11.9821 20.8079H11.956C7.12304 20.7942 3.20218 16.8224 3.21462 11.9533C3.22706 7.12039 7.18525 3.18957 12.0356 3.18957H12.0481C14.3849 3.1933 16.5824 4.1166 18.2348 5.79021C19.8873 7.46507 20.7957 9.68742 20.7907 12.0479C20.782 16.8771 16.83 20.8079 11.9821 20.8079Z" fill="white"/>
                                                <path d="M16.8741 9.51283L16.7534 9.49293C16.166 9.39587 15.5588 9.29508 14.9491 9.24531C14.3941 9.19927 14.1266 9.01386 13.9611 8.55844C13.8155 8.15652 13.6164 7.77701 13.4198 7.39998C13.3277 7.22328 13.2319 7.04036 13.1461 6.85994C12.8947 6.33732 12.5189 6.0673 12.0274 6.05859H12.005C11.4986 6.05859 11.1104 6.33359 10.8515 6.87611C10.7321 7.12622 10.6076 7.37384 10.4832 7.62146C10.3053 7.97734 10.1211 8.34441 9.95064 8.71896C9.81626 9.01635 9.6856 9.17189 9.34715 9.19678C8.94897 9.22664 8.55203 9.29134 8.16753 9.35605C7.88756 9.40209 7.59887 9.45062 7.31268 9.48297C7.08621 9.5091 6.39437 9.5875 6.14302 10.3017C5.87549 11.0583 6.41926 11.5573 6.5972 11.7215C6.78509 11.8945 6.968 12.0799 7.15092 12.264C7.40352 12.5191 7.66358 12.7829 7.94604 13.0206C8.29196 13.3105 8.37284 13.5693 8.25588 14.0061C8.15509 14.3819 8.10034 14.7689 8.04683 15.1434C8.01075 15.3985 7.97342 15.6623 7.9224 15.9136C7.81041 16.4636 7.95475 16.9004 8.35293 17.2115C8.56945 17.3807 8.80711 17.4665 9.05971 17.4665C9.28867 17.4665 9.52509 17.3981 9.78515 17.2563C10.034 17.1206 10.2903 16.9875 10.5479 16.8531C10.9287 16.6552 11.3219 16.4499 11.7039 16.2297C11.8159 16.165 11.9092 16.1351 12 16.1351C12.0921 16.1351 12.1929 16.1675 12.3149 16.2372C12.7877 16.5059 13.2755 16.7598 13.7583 17.0124C13.9362 17.1044 14.1129 17.1978 14.2908 17.2911C14.5148 17.4093 14.7351 17.469 14.9454 17.469C15.1805 17.469 15.402 17.3944 15.6061 17.2488C16.0267 16.9477 16.1947 16.5034 16.0926 15.9659C16.0404 15.6872 15.9943 15.3997 15.9495 15.1173C15.8861 14.7116 15.8201 14.2923 15.728 13.8816C15.6509 13.5357 15.7056 13.3491 15.9421 13.14C16.3178 12.809 16.675 12.4519 17.0196 12.106L17.0545 12.0712C17.1914 11.9343 17.3282 11.7974 17.4664 11.6618C17.816 11.3196 17.9666 11.0533 17.9404 10.8281C17.8882 10.0541 17.5398 9.62358 16.8741 9.51283ZM16.5916 10.8306C16.563 10.8555 16.5356 10.8803 16.5107 10.9052L16.3017 11.1118C15.8525 11.556 15.3871 12.0164 14.9167 12.4656C14.5235 12.8402 14.3867 13.2819 14.4986 13.8157C14.5957 14.2798 14.6728 14.7589 14.7463 15.223C14.7848 15.4644 14.8234 15.7058 14.8657 15.9472C14.8732 15.9895 14.8831 16.0331 14.8943 16.0766C14.903 16.114 14.9279 16.211 14.9155 16.2372C14.9055 16.2446 14.9006 16.2471 14.9006 16.2484C14.8719 16.2471 14.7923 16.1973 14.75 16.1712C14.7064 16.1438 14.6629 16.1177 14.6206 16.0965C13.9549 15.7593 13.3576 15.4532 12.769 15.1173C12.509 14.9692 12.2601 14.897 12.0088 14.897C11.7549 14.897 11.5036 14.9704 11.2385 15.1198C10.8229 15.3562 10.3949 15.5777 9.97926 15.7904L9.93447 15.8128C9.71298 15.9273 9.49149 16.0418 9.27124 16.1588C9.19036 16.2023 9.13313 16.2235 9.09455 16.2347C9.14059 15.9634 9.18165 15.7046 9.22272 15.4532C9.31604 14.8796 9.40314 14.3383 9.5114 13.8107C9.61965 13.2819 9.48029 12.8414 9.08584 12.4644C8.56447 11.9642 8.05928 11.4689 7.58394 10.9898C7.54164 10.9463 7.4956 10.909 7.44831 10.8716C7.30148 10.7522 7.30521 10.741 7.31641 10.7124C7.32761 10.6837 7.34379 10.6676 7.5491 10.6576C7.60634 10.6551 7.66482 10.6514 7.72455 10.6427C8.34547 10.5456 9.01118 10.4461 9.68063 10.3689C10.2742 10.3005 10.6823 10.0093 10.9274 9.47675C11.1464 9.00142 11.3866 8.52235 11.6193 8.05698L11.6317 8.03209C11.7238 7.84918 11.8159 7.66501 11.9067 7.48085C11.9179 7.45721 11.9291 7.43357 11.9403 7.40869C11.9677 7.34896 11.9876 7.30914 12.0013 7.29048C12.0212 7.31785 12.0535 7.38629 12.066 7.41491C12.0772 7.43979 12.0884 7.46468 12.0996 7.48708C12.1954 7.6812 12.2925 7.87406 12.3883 8.06693C12.6197 8.52982 12.8599 9.00764 13.0789 9.48422C13.3215 10.0106 13.7309 10.3005 14.3307 10.3714C14.9752 10.4461 15.6559 10.5406 16.4099 10.6589C16.4497 10.6651 16.492 10.6688 16.5344 10.6725C16.568 10.675 16.6687 10.6837 16.6911 10.7086C16.6949 10.7186 16.6949 10.7223 16.6949 10.7223C16.6911 10.741 16.619 10.8057 16.5916 10.8306Z" fill="white"/>
                                                </svg>
                                            <span class="text-truncate">
                                                {{\App\CPU\translate('Loyalty_Points')}}
                                            </span>
                                        </a>
                                    </li> -->
                                </ul>
                            </li>
                        @endif
                        <!--End User Section -->

                        <!-- Employee section Start -->

                        @if(\App\CPU\Helpers::module_permission_check('employee_management'))
                        <li
                            class="navbar-vertical-aside-has-menu {{(Request::is('admin/employee*') || Request::is('admin/custom-role*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link  its-drop" href="javascript:"
                                title="{{\App\CPU\translate('employees')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M11.9961 13.3906C12.4103 13.3906 12.7461 13.7264 12.7461 14.1406V16.6776C12.7461 17.0918 12.4103 17.4276 11.9961 17.4276C11.5819 17.4276 11.2461 17.0918 11.2461 16.6776V14.1406C11.2461 13.7264 11.5819 13.3906 11.9961 13.3906Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.81 6.08008C4.53101 6.08008 3.5 7.10749 3.5 8.38008V11.3921C5.78555 12.6333 8.74542 13.3901 11.99 13.3901C15.235 13.3901 18.2038 12.6332 20.49 11.392V8.39008C20.49 7.11109 19.4626 6.08008 18.19 6.08008H5.81ZM2 8.38008C2 6.27267 3.70899 4.58008 5.81 4.58008H18.19C20.2974 4.58008 21.99 6.28907 21.99 8.39008V11.8301C21.99 12.0964 21.8487 12.3428 21.6189 12.4773C19.0303 13.9926 15.6465 14.8901 11.99 14.8901C8.33312 14.8901 4.95934 13.9924 2.37112 12.4773C2.14126 12.3428 2 12.0964 2 11.8301V8.38008Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.74609 4.96C7.74609 3.32579 9.07188 2 10.7061 2H13.2861C14.9203 2 16.2461 3.32579 16.2461 4.96V5.326C16.2461 5.74021 15.9103 6.076 15.4961 6.076C15.0819 6.076 14.7461 5.74021 14.7461 5.326V4.96C14.7461 4.15421 14.0919 3.5 13.2861 3.5H10.7061C9.90031 3.5 9.24609 4.15421 9.24609 4.96V5.326C9.24609 5.74021 8.91031 6.076 8.49609 6.076C8.08188 6.076 7.74609 5.74021 7.74609 5.326V4.96Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2.71714 14.7346C3.13018 14.7035 3.49024 15.0131 3.52135 15.4261L3.71033 17.9348C3.80888 19.2349 4.89214 20.2395 6.19447 20.2395H17.7935C19.0958 20.2395 20.179 19.2352 20.2776 17.9351C20.2776 17.9352 20.2776 17.935 20.2776 17.9351L20.4666 15.4261C20.4977 15.0131 20.8578 14.7035 21.2708 14.7346C21.6839 14.7657 21.9935 15.1258 21.9624 15.5388L21.7734 18.0478C21.6158 20.1296 19.881 21.7395 17.7935 21.7395H6.19447C4.1069 21.7395 2.37219 20.1299 2.21461 18.0481L2.02559 15.5388C1.99448 15.1258 2.30409 14.7657 2.71714 14.7346Z"
                                        fill="white" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('employees')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/employee*') || Request::is('admin/custom-role*')?'block':'none'}}">
                                <li class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/custom-role*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.custom-role.create')}}"
                                        title="{{\App\CPU\translate('Employee_Role_Setup')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M19.774 19.6423C19.6927 17.1423 18.6561 15.1707 16.6643 13.748C16.1358 13.3618 15.5667 13.0366 14.9976 12.7114L14.9773 12.6911C14.7944 12.5894 14.6114 12.4878 14.4285 12.3659C14.3878 12.3455 14.3675 12.3252 14.3675 12.3252C14.3675 12.3049 14.3878 12.3049 14.4285 12.2642C15.2822 11.4715 15.77 10.6585 15.9732 9.72358C16.2374 8.54472 16.3187 7.28455 16.2781 5.88211C16.2781 5.61788 16.1968 5.35366 16.1358 5.10975C15.5667 3.26016 13.8797 2 11.9082 2C11.5626 2 11.2374 2.04065 10.8919 2.12195C8.92036 2.5691 7.51792 4.27642 7.4976 6.24796C7.47727 7.28455 7.55858 8.28049 7.72118 9.23577C7.9041 10.3943 8.37158 11.3089 9.10329 12.0203C9.20491 12.122 9.30654 12.2439 9.42849 12.3455L9.48947 12.4065C9.34719 12.4878 9.20491 12.5488 9.06264 12.6301C8.67646 12.8333 8.26996 13.0366 7.88378 13.2602C5.7293 14.4593 4.44881 16.2886 4.08296 18.7073C3.98133 19.3171 4.00166 19.9268 4.00166 20.5366C4.00166 20.8008 4.00166 21.0447 4.00166 21.309C4.00166 21.5325 4.06264 21.6951 4.16426 21.8171C4.28622 21.939 4.44882 22 4.6724 22H14.2862H19.083C19.5098 22 19.7537 21.7561 19.7537 21.3293C19.7537 21.1667 19.7537 21.0244 19.7537 20.8618C19.774 20.4553 19.774 20.0285 19.774 19.6423ZM11.8878 12.3455C11.6846 12.3455 11.461 12.2846 11.2374 12.1626C9.87565 11.4106 9.12361 10.3943 8.90004 9.07317C8.73743 8.15854 8.67646 7.18293 8.69678 6.22764C8.71711 4.78455 9.77402 3.56504 11.2781 3.26016C11.4813 3.21951 11.7049 3.19919 11.9082 3.19919C13.3309 3.19919 14.6114 4.15447 14.9976 5.49593C15.1196 5.88211 15.0992 6.28861 15.0992 6.75609C15.0992 6.83739 15.0992 6.93903 15.0992 7.02033V7.18293C15.0586 8.01626 15.0179 8.95122 14.713 9.82521C14.3269 10.8821 13.5545 11.6951 12.396 12.2439C12.213 12.3049 12.0504 12.3455 11.8878 12.3455ZM12.1521 13.9309C12.1521 13.9309 12.1114 13.9309 12.0911 13.9309C11.9895 13.9309 11.9082 13.9309 11.8472 13.9309C11.6236 13.9309 11.6033 13.8903 11.4204 13.5244C11.4204 13.5041 11.4 13.4837 11.4 13.4634C11.5626 13.5041 11.7252 13.5041 11.9082 13.5041C12.0708 13.5041 12.2334 13.4837 12.396 13.4634C12.2943 13.6463 12.2334 13.7683 12.1521 13.9309ZM11.7659 15.1098C11.7659 15.1098 11.8065 15.1098 11.8472 15.1301H11.8878H11.9082C11.9285 15.1301 11.9691 15.1098 11.9895 15.1098C12.0098 15.1098 12.0504 15.1098 12.0708 15.2317C12.1927 15.9024 12.3147 16.5732 12.4366 17.2236L12.5383 17.7317C12.5586 17.813 12.5383 17.8537 12.4976 17.8943C12.274 18.1992 12.0708 18.4431 11.9082 18.687C11.9082 18.687 11.9082 18.687 11.9082 18.7073C11.9082 18.7073 11.9082 18.687 11.8878 18.687C11.6846 18.4024 11.4813 18.1382 11.2984 17.9146C11.2578 17.8537 11.2374 17.813 11.2578 17.7317C11.4204 16.9187 11.5626 16.0854 11.7049 15.2724C11.7252 15.1911 11.7456 15.1098 11.7659 15.1098ZM10.587 14.7642C10.5057 15.1911 10.4244 15.6179 10.3431 16.0447C10.2415 16.6138 10.1399 17.1829 10.0179 17.7724C9.95695 18.0569 10.0179 18.2805 10.1805 18.5041C10.4448 18.8496 10.709 19.1951 10.9529 19.5407C11.0748 19.7033 11.2171 19.8862 11.3391 20.0488C11.4813 20.252 11.6643 20.3537 11.8675 20.3537C12.0504 20.3537 12.2334 20.252 12.396 20.0488C12.5586 19.8455 12.7009 19.6423 12.8635 19.439C13.1074 19.1138 13.3513 18.7886 13.5952 18.4634C13.7374 18.2805 13.7781 18.0772 13.7374 17.8333L13.5139 16.6748C13.3919 16.065 13.2903 15.4553 13.1683 14.8455C13.148 14.6829 13.148 14.5813 13.209 14.4594C13.2903 14.2968 13.3716 14.1341 13.4529 13.9715C13.4935 13.8699 13.5545 13.7886 13.5952 13.687C13.6358 13.5854 13.6968 13.5041 13.7171 13.3618C14.0017 13.5447 14.3065 13.7276 14.6114 13.8902C15.1195 14.1748 15.587 14.4593 16.0342 14.7845C17.6399 15.9837 18.4935 17.6301 18.5342 19.7033C18.5342 19.8455 18.5342 20.0081 18.5342 20.1504C18.5342 20.3333 18.5342 20.5163 18.5342 20.6992C18.5342 20.7602 18.5342 20.7805 18.5342 20.7805C18.5342 20.7805 18.5139 20.7805 18.4529 20.7805C17.1114 20.7805 15.77 20.7805 14.4082 20.7805H9.02199C7.76183 20.7805 6.52199 20.7805 5.26182 20.7805C5.18052 20.7805 5.1602 20.7602 5.1602 20.7602C5.1602 20.7602 5.13987 20.7398 5.13987 20.6585V20.4146C5.13987 19.6423 5.13987 18.8496 5.36345 18.0772C5.83093 16.4106 6.84719 15.1504 8.35125 14.3171C8.75776 14.0935 9.16427 13.8699 9.55045 13.687C9.69272 13.6057 9.85532 13.5244 9.9976 13.4431L10.1195 13.6667C10.2618 13.9309 10.3838 14.1951 10.5261 14.4594C10.6074 14.5813 10.6074 14.6626 10.587 14.7642Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Employee_Role_Setup')}}</span>
                                    </a>
                                </li>
                                

                                <li
                                    class="nav-item {{(Request::is('admin/employee/list') || Request::is('admin/employee/add-new') || Request::is('admin/employee/update*'))?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.employee.list')}}"
                                        title="{{\App\CPU\translate('Employees')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M16.8098 19.6505C15.5003 19.6505 14.2067 19.6505 12.8972 19.6505C12.1625 19.6505 11.6675 19.1554 11.6675 18.4049C11.6675 18.1653 11.6675 17.9098 11.6675 17.6543V17.3508C11.6675 17.3508 7.70694 17.3508 7.02024 17.3508C5.45519 17.3508 4.2255 17.3508 3.07567 17.3668C2.70836 17.3668 2.42091 17.255 2.22927 17.0314C2.03763 16.8079 1.95778 16.5044 2.02166 16.1531C2.4209 13.534 3.85819 11.7294 6.31756 10.7872L6.3495 10.7712L6.33353 10.7553C5.29549 10.0047 4.76848 8.9826 4.73654 7.73695C4.72057 6.68294 5.10385 5.77266 5.88637 5.02207C6.57308 4.36731 7.48336 4 8.44156 4C9.55945 4 10.6135 4.49507 11.3161 5.35744C12.0028 6.17191 12.2903 7.25786 12.1146 8.31187C11.9549 9.28604 11.412 10.1484 10.5815 10.7553L10.5496 10.7712L10.5815 10.7872C11.2363 11.0268 11.8431 11.3621 12.4181 11.7933C12.4819 11.8412 12.5778 11.8732 12.6576 11.8732C12.977 11.8732 13.2964 11.8732 13.6158 11.8732H14.4941V11.8572C14.4941 11.7933 14.4941 11.7294 14.4941 11.6656C14.5101 10.9629 14.9413 10.5317 15.628 10.5317C16.0273 10.5317 16.4265 10.5317 16.8258 10.5317C17.225 10.5317 17.6402 10.5317 18.0395 10.5317C18.7262 10.5317 19.1574 10.9629 19.1733 11.6656V11.8732H20.3391C20.5627 11.8732 20.7863 11.8732 21.0099 11.8732C21.6007 11.8732 22 12.2564 22 12.8473C22 14.6998 22 16.5523 22 18.4049C22 19.1395 21.489 19.6665 20.7544 19.6665C19.4288 19.6505 18.1193 19.6505 16.8098 19.6505ZM12.6736 16.744C12.6736 17.3189 12.6736 17.8938 12.6736 18.4528C12.6736 18.5166 12.6736 18.6284 12.9131 18.6284C14.2067 18.6284 15.5003 18.6284 16.8098 18.6284C18.1033 18.6284 19.3969 18.6284 20.7064 18.6284C20.8981 18.6284 20.962 18.5645 20.962 18.3729C20.962 18.1493 20.962 17.9417 20.962 17.7181V16.2808H20.946C20.5308 16.3607 20.1475 16.3607 19.8441 16.3607C19.6684 16.3607 19.4927 16.3607 19.333 16.3607H19.3171C19.1574 16.3607 18.9817 16.3607 18.822 16.3607C18.6304 16.3607 18.4707 16.3607 18.3269 16.3767H18.311V16.3926C18.311 16.5044 18.311 17.0953 18.311 17.1911C18.295 17.6543 18.0075 17.9417 17.5604 17.9417C17.3208 17.9417 17.0653 17.9417 16.8258 17.9417C16.5862 17.9417 16.3467 17.9417 16.1071 17.9417C15.644 17.9417 15.3725 17.6543 15.3565 17.1752C15.3565 17.0634 15.3565 16.5204 15.3565 16.3767V16.3607H15.3406C15.1968 16.3447 15.0371 16.3447 14.8455 16.3447C14.6698 16.3447 14.5101 16.3447 14.3344 16.3447H14.3185C14.1588 16.3447 13.9991 16.3447 13.8234 16.3447C13.504 16.3447 13.1207 16.3288 12.7215 16.2649H12.7055L12.6736 16.744ZM16.3626 16.9196H17.2889V16.3767H16.3626V16.9196ZM8.42559 11.41C7.49933 11.41 6.57308 11.6496 5.74265 12.1127C3.95402 13.1188 3.07567 14.9873 3.02776 16.2968V16.3128H11.6675V12.512C10.7732 11.8093 9.62333 11.41 8.42559 11.41ZM12.6736 12.9112V13.3584C12.6736 13.8375 12.6736 14.3006 12.6736 14.7797C12.6736 15.163 12.8492 15.3386 13.2325 15.3386C14.4303 15.3386 15.612 15.3386 16.8098 15.3386C18.0075 15.3386 19.1893 15.3386 20.387 15.3386C20.7863 15.3386 20.962 15.163 20.962 14.7477C20.962 14.5721 20.962 14.4124 20.962 14.2367V14.1409C20.962 13.7416 20.962 13.3424 20.962 12.9431C20.962 12.8952 20.962 12.8793 20.962 12.8633V12.8473H20.946C20.93 12.8473 20.8981 12.8473 20.8502 12.8473C19.1893 12.8473 17.5444 12.8473 15.8995 12.8473H12.6736C12.6736 12.8633 12.6736 12.9112 12.6736 12.9112ZM15.8835 11.5218C15.7079 11.5218 15.5482 11.5218 15.5002 11.5538C15.4843 11.5697 15.4683 11.6176 15.4843 11.8253V11.8412H18.1353V11.7614C18.1353 11.7135 18.1353 11.5857 18.1193 11.5538V11.5378H18.1033C18.0554 11.5218 17.9436 11.5218 17.8957 11.5218H15.8835ZM8.44156 4.99013C6.94039 4.99013 5.74264 6.1719 5.7107 7.6571C5.69473 8.35978 5.96622 9.04649 6.47726 9.55752C6.98829 10.0845 7.64306 10.372 8.34574 10.388H8.39365C9.89482 10.388 11.0926 9.22215 11.1245 7.72098C11.1564 6.26772 9.94273 5.0061 8.48947 4.99013H8.44156Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Employees')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{(Request::is('admin/assessor*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link  its-drop" href="javascript:"
                                title="{{\App\CPU\translate('Assessors/Auditors')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M11.9961 13.3906C12.4103 13.3906 12.7461 13.7264 12.7461 14.1406V16.6776C12.7461 17.0918 12.4103 17.4276 11.9961 17.4276C11.5819 17.4276 11.2461 17.0918 11.2461 16.6776V14.1406C11.2461 13.7264 11.5819 13.3906 11.9961 13.3906Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.81 6.08008C4.53101 6.08008 3.5 7.10749 3.5 8.38008V11.3921C5.78555 12.6333 8.74542 13.3901 11.99 13.3901C15.235 13.3901 18.2038 12.6332 20.49 11.392V8.39008C20.49 7.11109 19.4626 6.08008 18.19 6.08008H5.81ZM2 8.38008C2 6.27267 3.70899 4.58008 5.81 4.58008H18.19C20.2974 4.58008 21.99 6.28907 21.99 8.39008V11.8301C21.99 12.0964 21.8487 12.3428 21.6189 12.4773C19.0303 13.9926 15.6465 14.8901 11.99 14.8901C8.33312 14.8901 4.95934 13.9924 2.37112 12.4773C2.14126 12.3428 2 12.0964 2 11.8301V8.38008Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.74609 4.96C7.74609 3.32579 9.07188 2 10.7061 2H13.2861C14.9203 2 16.2461 3.32579 16.2461 4.96V5.326C16.2461 5.74021 15.9103 6.076 15.4961 6.076C15.0819 6.076 14.7461 5.74021 14.7461 5.326V4.96C14.7461 4.15421 14.0919 3.5 13.2861 3.5H10.7061C9.90031 3.5 9.24609 4.15421 9.24609 4.96V5.326C9.24609 5.74021 8.91031 6.076 8.49609 6.076C8.08188 6.076 7.74609 5.74021 7.74609 5.326V4.96Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2.71714 14.7346C3.13018 14.7035 3.49024 15.0131 3.52135 15.4261L3.71033 17.9348C3.80888 19.2349 4.89214 20.2395 6.19447 20.2395H17.7935C19.0958 20.2395 20.179 19.2352 20.2776 17.9351C20.2776 17.9352 20.2776 17.935 20.2776 17.9351L20.4666 15.4261C20.4977 15.0131 20.8578 14.7035 21.2708 14.7346C21.6839 14.7657 21.9935 15.1258 21.9624 15.5388L21.7734 18.0478C21.6158 20.1296 19.881 21.7395 17.7935 21.7395H6.19447C4.1069 21.7395 2.37219 20.1299 2.21461 18.0481L2.02559 15.5388C1.99448 15.1258 2.30409 14.7657 2.71714 14.7346Z"
                                        fill="white" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Assessors/Auditors')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/assessor*') ?'block':'none'}}">
                                
                                <li class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/assessor/add-assessor')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.assessor.add-assessor')}}"
                                        title="{{\App\CPU\translate('Add_Assessor')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M19.774 19.6423C19.6927 17.1423 18.6561 15.1707 16.6643 13.748C16.1358 13.3618 15.5667 13.0366 14.9976 12.7114L14.9773 12.6911C14.7944 12.5894 14.6114 12.4878 14.4285 12.3659C14.3878 12.3455 14.3675 12.3252 14.3675 12.3252C14.3675 12.3049 14.3878 12.3049 14.4285 12.2642C15.2822 11.4715 15.77 10.6585 15.9732 9.72358C16.2374 8.54472 16.3187 7.28455 16.2781 5.88211C16.2781 5.61788 16.1968 5.35366 16.1358 5.10975C15.5667 3.26016 13.8797 2 11.9082 2C11.5626 2 11.2374 2.04065 10.8919 2.12195C8.92036 2.5691 7.51792 4.27642 7.4976 6.24796C7.47727 7.28455 7.55858 8.28049 7.72118 9.23577C7.9041 10.3943 8.37158 11.3089 9.10329 12.0203C9.20491 12.122 9.30654 12.2439 9.42849 12.3455L9.48947 12.4065C9.34719 12.4878 9.20491 12.5488 9.06264 12.6301C8.67646 12.8333 8.26996 13.0366 7.88378 13.2602C5.7293 14.4593 4.44881 16.2886 4.08296 18.7073C3.98133 19.3171 4.00166 19.9268 4.00166 20.5366C4.00166 20.8008 4.00166 21.0447 4.00166 21.309C4.00166 21.5325 4.06264 21.6951 4.16426 21.8171C4.28622 21.939 4.44882 22 4.6724 22H14.2862H19.083C19.5098 22 19.7537 21.7561 19.7537 21.3293C19.7537 21.1667 19.7537 21.0244 19.7537 20.8618C19.774 20.4553 19.774 20.0285 19.774 19.6423ZM11.8878 12.3455C11.6846 12.3455 11.461 12.2846 11.2374 12.1626C9.87565 11.4106 9.12361 10.3943 8.90004 9.07317C8.73743 8.15854 8.67646 7.18293 8.69678 6.22764C8.71711 4.78455 9.77402 3.56504 11.2781 3.26016C11.4813 3.21951 11.7049 3.19919 11.9082 3.19919C13.3309 3.19919 14.6114 4.15447 14.9976 5.49593C15.1196 5.88211 15.0992 6.28861 15.0992 6.75609C15.0992 6.83739 15.0992 6.93903 15.0992 7.02033V7.18293C15.0586 8.01626 15.0179 8.95122 14.713 9.82521C14.3269 10.8821 13.5545 11.6951 12.396 12.2439C12.213 12.3049 12.0504 12.3455 11.8878 12.3455ZM12.1521 13.9309C12.1521 13.9309 12.1114 13.9309 12.0911 13.9309C11.9895 13.9309 11.9082 13.9309 11.8472 13.9309C11.6236 13.9309 11.6033 13.8903 11.4204 13.5244C11.4204 13.5041 11.4 13.4837 11.4 13.4634C11.5626 13.5041 11.7252 13.5041 11.9082 13.5041C12.0708 13.5041 12.2334 13.4837 12.396 13.4634C12.2943 13.6463 12.2334 13.7683 12.1521 13.9309ZM11.7659 15.1098C11.7659 15.1098 11.8065 15.1098 11.8472 15.1301H11.8878H11.9082C11.9285 15.1301 11.9691 15.1098 11.9895 15.1098C12.0098 15.1098 12.0504 15.1098 12.0708 15.2317C12.1927 15.9024 12.3147 16.5732 12.4366 17.2236L12.5383 17.7317C12.5586 17.813 12.5383 17.8537 12.4976 17.8943C12.274 18.1992 12.0708 18.4431 11.9082 18.687C11.9082 18.687 11.9082 18.687 11.9082 18.7073C11.9082 18.7073 11.9082 18.687 11.8878 18.687C11.6846 18.4024 11.4813 18.1382 11.2984 17.9146C11.2578 17.8537 11.2374 17.813 11.2578 17.7317C11.4204 16.9187 11.5626 16.0854 11.7049 15.2724C11.7252 15.1911 11.7456 15.1098 11.7659 15.1098ZM10.587 14.7642C10.5057 15.1911 10.4244 15.6179 10.3431 16.0447C10.2415 16.6138 10.1399 17.1829 10.0179 17.7724C9.95695 18.0569 10.0179 18.2805 10.1805 18.5041C10.4448 18.8496 10.709 19.1951 10.9529 19.5407C11.0748 19.7033 11.2171 19.8862 11.3391 20.0488C11.4813 20.252 11.6643 20.3537 11.8675 20.3537C12.0504 20.3537 12.2334 20.252 12.396 20.0488C12.5586 19.8455 12.7009 19.6423 12.8635 19.439C13.1074 19.1138 13.3513 18.7886 13.5952 18.4634C13.7374 18.2805 13.7781 18.0772 13.7374 17.8333L13.5139 16.6748C13.3919 16.065 13.2903 15.4553 13.1683 14.8455C13.148 14.6829 13.148 14.5813 13.209 14.4594C13.2903 14.2968 13.3716 14.1341 13.4529 13.9715C13.4935 13.8699 13.5545 13.7886 13.5952 13.687C13.6358 13.5854 13.6968 13.5041 13.7171 13.3618C14.0017 13.5447 14.3065 13.7276 14.6114 13.8902C15.1195 14.1748 15.587 14.4593 16.0342 14.7845C17.6399 15.9837 18.4935 17.6301 18.5342 19.7033C18.5342 19.8455 18.5342 20.0081 18.5342 20.1504C18.5342 20.3333 18.5342 20.5163 18.5342 20.6992C18.5342 20.7602 18.5342 20.7805 18.5342 20.7805C18.5342 20.7805 18.5139 20.7805 18.4529 20.7805C17.1114 20.7805 15.77 20.7805 14.4082 20.7805H9.02199C7.76183 20.7805 6.52199 20.7805 5.26182 20.7805C5.18052 20.7805 5.1602 20.7602 5.1602 20.7602C5.1602 20.7602 5.13987 20.7398 5.13987 20.6585V20.4146C5.13987 19.6423 5.13987 18.8496 5.36345 18.0772C5.83093 16.4106 6.84719 15.1504 8.35125 14.3171C8.75776 14.0935 9.16427 13.8699 9.55045 13.687C9.69272 13.6057 9.85532 13.5244 9.9976 13.4431L10.1195 13.6667C10.2618 13.9309 10.3838 14.1951 10.5261 14.4594C10.6074 14.5813 10.6074 14.6626 10.587 14.7642Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Add_Assessor')}}</span>
                                    </a>
                                </li>

                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/assessor/assessor-list')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.assessor.assessor-list')}}"
                                        title="{{\App\CPU\translate('Add_Assessor')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M19.774 19.6423C19.6927 17.1423 18.6561 15.1707 16.6643 13.748C16.1358 13.3618 15.5667 13.0366 14.9976 12.7114L14.9773 12.6911C14.7944 12.5894 14.6114 12.4878 14.4285 12.3659C14.3878 12.3455 14.3675 12.3252 14.3675 12.3252C14.3675 12.3049 14.3878 12.3049 14.4285 12.2642C15.2822 11.4715 15.77 10.6585 15.9732 9.72358C16.2374 8.54472 16.3187 7.28455 16.2781 5.88211C16.2781 5.61788 16.1968 5.35366 16.1358 5.10975C15.5667 3.26016 13.8797 2 11.9082 2C11.5626 2 11.2374 2.04065 10.8919 2.12195C8.92036 2.5691 7.51792 4.27642 7.4976 6.24796C7.47727 7.28455 7.55858 8.28049 7.72118 9.23577C7.9041 10.3943 8.37158 11.3089 9.10329 12.0203C9.20491 12.122 9.30654 12.2439 9.42849 12.3455L9.48947 12.4065C9.34719 12.4878 9.20491 12.5488 9.06264 12.6301C8.67646 12.8333 8.26996 13.0366 7.88378 13.2602C5.7293 14.4593 4.44881 16.2886 4.08296 18.7073C3.98133 19.3171 4.00166 19.9268 4.00166 20.5366C4.00166 20.8008 4.00166 21.0447 4.00166 21.309C4.00166 21.5325 4.06264 21.6951 4.16426 21.8171C4.28622 21.939 4.44882 22 4.6724 22H14.2862H19.083C19.5098 22 19.7537 21.7561 19.7537 21.3293C19.7537 21.1667 19.7537 21.0244 19.7537 20.8618C19.774 20.4553 19.774 20.0285 19.774 19.6423ZM11.8878 12.3455C11.6846 12.3455 11.461 12.2846 11.2374 12.1626C9.87565 11.4106 9.12361 10.3943 8.90004 9.07317C8.73743 8.15854 8.67646 7.18293 8.69678 6.22764C8.71711 4.78455 9.77402 3.56504 11.2781 3.26016C11.4813 3.21951 11.7049 3.19919 11.9082 3.19919C13.3309 3.19919 14.6114 4.15447 14.9976 5.49593C15.1196 5.88211 15.0992 6.28861 15.0992 6.75609C15.0992 6.83739 15.0992 6.93903 15.0992 7.02033V7.18293C15.0586 8.01626 15.0179 8.95122 14.713 9.82521C14.3269 10.8821 13.5545 11.6951 12.396 12.2439C12.213 12.3049 12.0504 12.3455 11.8878 12.3455ZM12.1521 13.9309C12.1521 13.9309 12.1114 13.9309 12.0911 13.9309C11.9895 13.9309 11.9082 13.9309 11.8472 13.9309C11.6236 13.9309 11.6033 13.8903 11.4204 13.5244C11.4204 13.5041 11.4 13.4837 11.4 13.4634C11.5626 13.5041 11.7252 13.5041 11.9082 13.5041C12.0708 13.5041 12.2334 13.4837 12.396 13.4634C12.2943 13.6463 12.2334 13.7683 12.1521 13.9309ZM11.7659 15.1098C11.7659 15.1098 11.8065 15.1098 11.8472 15.1301H11.8878H11.9082C11.9285 15.1301 11.9691 15.1098 11.9895 15.1098C12.0098 15.1098 12.0504 15.1098 12.0708 15.2317C12.1927 15.9024 12.3147 16.5732 12.4366 17.2236L12.5383 17.7317C12.5586 17.813 12.5383 17.8537 12.4976 17.8943C12.274 18.1992 12.0708 18.4431 11.9082 18.687C11.9082 18.687 11.9082 18.687 11.9082 18.7073C11.9082 18.7073 11.9082 18.687 11.8878 18.687C11.6846 18.4024 11.4813 18.1382 11.2984 17.9146C11.2578 17.8537 11.2374 17.813 11.2578 17.7317C11.4204 16.9187 11.5626 16.0854 11.7049 15.2724C11.7252 15.1911 11.7456 15.1098 11.7659 15.1098ZM10.587 14.7642C10.5057 15.1911 10.4244 15.6179 10.3431 16.0447C10.2415 16.6138 10.1399 17.1829 10.0179 17.7724C9.95695 18.0569 10.0179 18.2805 10.1805 18.5041C10.4448 18.8496 10.709 19.1951 10.9529 19.5407C11.0748 19.7033 11.2171 19.8862 11.3391 20.0488C11.4813 20.252 11.6643 20.3537 11.8675 20.3537C12.0504 20.3537 12.2334 20.252 12.396 20.0488C12.5586 19.8455 12.7009 19.6423 12.8635 19.439C13.1074 19.1138 13.3513 18.7886 13.5952 18.4634C13.7374 18.2805 13.7781 18.0772 13.7374 17.8333L13.5139 16.6748C13.3919 16.065 13.2903 15.4553 13.1683 14.8455C13.148 14.6829 13.148 14.5813 13.209 14.4594C13.2903 14.2968 13.3716 14.1341 13.4529 13.9715C13.4935 13.8699 13.5545 13.7886 13.5952 13.687C13.6358 13.5854 13.6968 13.5041 13.7171 13.3618C14.0017 13.5447 14.3065 13.7276 14.6114 13.8902C15.1195 14.1748 15.587 14.4593 16.0342 14.7845C17.6399 15.9837 18.4935 17.6301 18.5342 19.7033C18.5342 19.8455 18.5342 20.0081 18.5342 20.1504C18.5342 20.3333 18.5342 20.5163 18.5342 20.6992C18.5342 20.7602 18.5342 20.7805 18.5342 20.7805C18.5342 20.7805 18.5139 20.7805 18.4529 20.7805C17.1114 20.7805 15.77 20.7805 14.4082 20.7805H9.02199C7.76183 20.7805 6.52199 20.7805 5.26182 20.7805C5.18052 20.7805 5.1602 20.7602 5.1602 20.7602C5.1602 20.7602 5.13987 20.7398 5.13987 20.6585V20.4146C5.13987 19.6423 5.13987 18.8496 5.36345 18.0772C5.83093 16.4106 6.84719 15.1504 8.35125 14.3171C8.75776 14.0935 9.16427 13.8699 9.55045 13.687C9.69272 13.6057 9.85532 13.5244 9.9976 13.4431L10.1195 13.6667C10.2618 13.9309 10.3838 14.1951 10.5261 14.4594C10.6074 14.5813 10.6074 14.6626 10.587 14.7642Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Assessor_List')}}</span>
                                    </a>
                                </li>

                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/assessor/witness-list')||Request::is('admin/assessor/view-witness/*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('admin.assessor.witness-list')}}"  {{-- change route name if needed --}}
                                    title="{{\App\CPU\translate('Witness_List')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none">
                                            <path d="M12 2C9.2 2 7 4.2 7 7C7 9.8 9.2 12 12 12C14.8 12 17 9.8 17 7C17 4.2 14.8 2 12 2ZM12 10C10.3 10 9 8.7 9 7C9 5.3 10.3 4 12 4C13.7 4 15 5.3 15 7C15 8.7 13.7 10 12 10ZM12 13C8.1 13 2 15 2 19V21H22V19C22 15 15.9 13 12 13ZM4 19C4 17.3 8.7 15 12 15C15.3 15 20 17.3 20 19V19H4V19Z"
                                                fill="white"/>
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Witness_List')}}
                                        </span>
                                    </a>
                                </li>


                            </ul>
                        </li>
                        @endif

                        <!-- employee section end -->
                        @if(\App\CPU\Helpers::module_permission_check('promotion_management'))
                        
                        <!-- Banner -->
                        <li class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/banner*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('admin.banner.list')}}"
                                title="{{\App\CPU\translate('banners')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path d="M15.7161 16.2236H8.49609" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M15.7161 12.0371H8.49609" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M11.2511 7.86035H8.49609" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M15.908 2.75C15.908 2.75 8.231 2.754 8.219 2.754C5.459 2.771 3.75 4.587 3.75 7.357V16.553C3.75 19.337 5.472 21.16 8.256 21.16C8.256 21.16 15.932 21.157 15.945 21.157C18.705 21.14 20.415 19.323 20.415 16.553V7.357C20.415 4.573 18.692 2.75 15.908 2.75Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('banners')}}</span>
                            </a>
                        </li>
                        <!-- Banner End -->
                        @endif

                        <li
                            class="nav-item{{(Request::is('admin/scehme*')) ? 'scroll-here':''}}">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('scheme_and_Area')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/scehme*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('scheme_and_Area')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.79476 7.05589C4.79476 5.80689 5.80676 4.79489 7.05576 4.79389H8.08476C8.68176 4.79389 9.25376 4.55689 9.67776 4.13689L10.3968 3.41689C11.2778 2.53089 12.7098 2.52689 13.5958 3.40789L13.5968 3.40889L13.6058 3.41689L14.3258 4.13689C14.7498 4.55789 15.3218 4.79389 15.9188 4.79389H16.9468C18.1958 4.79389 19.2088 5.80589 19.2088 7.05589V8.08289C19.2088 8.67989 19.4448 9.25289 19.8658 9.67689L20.5858 10.3969C21.4718 11.2779 21.4768 12.7099 20.5958 13.5959L20.5948 13.5969L20.5858 13.6059L19.8658 14.3259C19.4448 14.7489 19.2088 15.3209 19.2088 15.9179V16.9469C19.2088 18.1959 18.1968 19.2079 16.9478 19.2079H15.9168C15.3198 19.2079 14.7468 19.4449 14.3238 19.8659L13.6038 20.5849C12.7238 21.4709 11.2928 21.4759 10.4068 20.5969C10.4058 20.5959 10.4048 20.5949 10.4038 20.5939L10.3948 20.5849L9.67576 19.8659C9.25276 19.4449 8.67976 19.2089 8.08276 19.2079H7.05576C5.80676 19.2079 4.79476 18.1959 4.79476 16.9469V15.9159C4.79476 15.3189 4.55776 14.7469 4.13676 14.3239L3.41776 13.6039C2.53176 12.7239 2.52676 11.2929 3.40676 10.4069C3.40676 10.4059 3.40776 10.4049 3.40876 10.4039L3.41776 10.3949L4.13676 9.67489C4.55776 9.25089 4.79476 8.67889 4.79476 8.08089V7.05589Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9.42969 14.5714L14.5697 9.4314" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.4961 14.4998H14.5051" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.49609 9.49976H9.50509" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('scheme_and_Area')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('admin/scheme*'))?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/scheme/view')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.scheme.view') }}"
                                        title="{{\App\CPU\translate('Scheme')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.326 14.3625C20.306 14.3261 19.5046 13.5064 19.5046 12.5046C19.5046 11.4845 20.306 10.6648 21.326 10.6284C21.7632 10.6102 22 10.3734 22 9.93625C22 8.93443 22 7.93261 22 6.93079C22 6.76686 21.9818 6.58471 21.9454 6.42077C21.7268 5.56467 20.9982 5 20.0874 5C14.6958 5 9.3224 5 3.93078 5C3.89435 5 3.85792 5 3.83971 5C3.03825 5.01821 2.32787 5.58288 2.09107 6.34791C2.07286 6.40255 2.09108 6.47542 2.01822 6.51185C2.01822 7.71403 2.01822 8.93443 2.01822 10.1366C2.10929 10.4827 2.30965 10.6466 2.67395 10.6466C2.69217 10.6466 2.7286 10.6466 2.74681 10.6466C3.40255 10.7013 3.91257 11.0109 4.22222 11.5756C4.89617 12.796 4.05829 14.2168 2.65574 14.2896C2.29144 14.3078 2.09107 14.4718 2 14.7996C2 16.0018 2 17.1858 2 18.388C2.05464 18.4062 2.03643 18.4426 2.03643 18.4791C2.27322 19.3898 2.98361 19.9362 3.93078 19.9362C9.30419 19.9362 14.6776 19.9362 20.051 19.9362C21.1621 19.9362 21.9818 19.1166 21.9818 18.0055C21.9818 17.0036 21.9818 16.02 21.9818 15.0182C22.0182 14.6175 21.7814 14.3807 21.326 14.3625ZM20.7614 15.8197C20.7432 16.5665 20.7614 17.2951 20.7614 18.0419C20.7614 18.4791 20.5064 18.7341 20.0692 18.7341C17.3916 18.7341 14.6958 18.7341 12.0182 18.7341C9.35883 18.7341 6.68124 18.7341 4.02186 18.7341C3.53005 18.7341 3.29326 18.4973 3.29326 17.9873C3.29326 17.2404 3.29326 16.4754 3.29326 15.7286C3.29326 15.6011 3.31148 15.5464 3.4572 15.51C4.85975 15.1275 5.78871 13.9071 5.78871 12.4863C5.78871 11.0656 4.84153 9.84517 3.4572 9.46266C3.32969 9.42623 3.29326 9.37159 3.29326 9.24409C3.29326 8.47906 3.29326 7.69582 3.29326 6.93079C3.29326 6.49363 3.54827 6.23862 3.98543 6.23862C9.35883 6.23862 14.714 6.23862 20.0874 6.23862C20.5428 6.23862 20.7796 6.49363 20.7796 6.949C20.7796 7.71403 20.7796 8.47906 20.7796 9.24409C20.7796 9.37159 20.7614 9.42623 20.6157 9.46266C19.3042 9.80874 18.3752 10.9381 18.2842 12.2678C18.1931 13.5975 18.9581 14.8361 20.2149 15.3643C20.3242 15.4007 20.4153 15.4554 20.5246 15.4736C20.725 15.51 20.7796 15.6193 20.7614 15.8197Z"
                                                fill="white" />
                                            <path
                                                d="M14.4599 7.7499C14.4417 7.78633 14.4235 7.84098 14.4052 7.87741C13.2031 11.1015 12.0009 14.3073 10.7987 17.5313C10.744 17.6588 10.7076 17.7135 10.5619 17.6406C10.2887 17.5131 9.99722 17.422 9.70578 17.3127C9.57828 17.2763 9.57828 17.2217 9.61471 17.1124C10.1612 15.6552 10.7076 14.198 11.2541 12.7226C11.9098 10.9739 12.5655 9.22531 13.2213 7.45846C13.2759 7.31274 13.3306 7.2581 13.4945 7.33096C13.7677 7.45847 14.0592 7.54954 14.3506 7.65883C14.387 7.67704 14.4599 7.67704 14.4599 7.7499Z"
                                                fill="white" />
                                            <path
                                                d="M15.5145 12.832C14.4762 12.832 13.6383 13.6699 13.6565 14.7082C13.6565 15.7464 14.4944 16.5843 15.5327 16.5843C16.5527 16.5843 17.4088 15.7282 17.4088 14.7082C17.3906 13.6699 16.5527 12.832 15.5145 12.832ZM15.5145 15.3275C15.1684 15.3275 14.8952 15.036 14.8952 14.69C14.8952 14.3439 15.1684 14.0707 15.5145 14.0889C15.8605 14.0889 16.1338 14.3803 16.1338 14.7082C16.1338 15.036 15.8605 15.3275 15.5145 15.3275Z"
                                                fill="white" />
                                            <path
                                                d="M9.06754 8.95312C8.04751 8.95312 7.19141 9.80922 7.19141 10.8293C7.19141 11.8675 8.02929 12.6872 9.06754 12.6872C10.1058 12.6872 10.9255 11.8493 10.9255 10.8111C10.9255 9.79101 10.0876 8.95312 9.06754 8.95312ZM9.06754 11.4486C8.72146 11.4486 8.43002 11.1571 8.43002 10.8293C8.43002 10.4832 8.72146 10.21 9.04933 10.21C9.39541 10.21 9.66864 10.4832 9.66864 10.8293C9.68685 11.1753 9.41363 11.4486 9.06754 11.4486Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Scheme')}}</span>
                                    </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{(Request::is('admin/scheme/area/view*'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.scheme.area.view') }}"
                                        title="{{\App\CPU\translate('Flash_Deals')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.85366 21.9321C6.06181 21.9321 5.24733 21.5701 4.70434 20.9593C4.31973 20.5294 3.88986 19.7602 4.02561 18.6063C4.09348 18.0181 4.16136 17.4072 4.2066 16.819C4.22923 16.5475 4.25185 16.2534 4.27448 15.9819L4.3876 14.6923C4.45547 14.0815 4.50072 13.4932 4.5686 12.8824C4.61385 12.4977 4.63647 12.1358 4.68172 11.7511C4.72697 11.276 4.77221 10.8009 4.81746 10.3258C4.84009 10.0543 4.86271 9.76019 4.88534 9.48869C4.95321 8.76471 5.02109 7.99548 5.13421 7.2715C5.26995 6.29865 6.15231 5.57467 7.17041 5.57467C7.17041 5.57467 7.66814 5.57467 7.89439 5.57467C8.14326 5.57467 8.3695 5.57467 8.61837 5.57467C8.77674 5.57467 8.82199 5.50679 8.82199 5.37105C8.93511 3.44797 10.4509 2 12.374 2H12.3966C14.2292 2.02262 15.7903 3.51585 15.8808 5.34843C15.8808 5.52942 15.9487 5.57467 16.1297 5.57467H16.1749C16.3333 5.57467 16.4917 5.57467 16.65 5.57467C16.8084 5.57467 16.9668 5.57467 17.1252 5.57467C17.3966 5.57467 17.6229 5.59728 17.8265 5.61991C18.822 5.77828 19.5233 6.54752 19.6138 7.56562C19.727 8.76471 19.8401 9.96381 19.9532 11.1855C20.0211 11.819 20.0663 12.4751 20.1342 13.1086C20.1795 13.6968 20.2473 14.2851 20.2926 14.8733C20.4057 16.095 20.5188 17.3167 20.6319 18.5385C20.6998 19.1041 20.6998 19.7149 20.4283 20.2806C19.908 21.3665 19.0482 21.9321 17.8718 21.9547C17.1252 21.9547 16.3785 21.9774 15.6093 21.9774C15.0663 21.9774 14.5007 21.9774 13.9577 21.9774C13.4147 21.9774 12.8491 21.9774 12.3062 21.9774C11.7405 21.9774 11.1976 21.9774 10.6772 21.9774C10.1342 21.9774 9.59122 21.9774 9.04823 21.9774C8.23375 21.9774 7.55502 21.9774 6.92154 22L6.85366 21.9321ZM7.23828 6.88688C6.80842 6.88688 6.5143 7.15838 6.46905 7.56562C6.40117 8.26697 6.3333 8.99095 6.26543 9.69231L6.10706 11.2986C6.06181 11.7059 6.03919 12.1131 5.99394 12.5204C5.94869 13.0633 5.88081 13.6063 5.83556 14.1493L5.67719 15.7783C5.63194 16.2986 5.56407 16.819 5.54145 17.3394C5.51882 17.543 5.4962 17.7466 5.47357 17.9729C5.42832 18.3575 5.38307 18.7647 5.38307 19.1493C5.38307 19.8959 6.01656 20.552 6.74054 20.5747C6.94416 20.5747 7.1704 20.5747 7.37402 20.5747C7.60027 20.5747 7.82651 20.5747 8.05276 20.5747C8.25638 20.5747 8.43737 20.5747 8.64099 20.5747H8.70887C8.75412 20.5747 8.82199 20.5747 8.86724 20.5747H17.736C18.2338 20.5747 18.6636 20.4163 18.9351 20.0995C19.2066 19.8054 19.3423 19.3756 19.2971 18.8778C19.2518 18.2896 19.184 17.7014 19.1387 17.1131C19.1161 16.8643 19.0935 16.6154 19.0709 16.3665L18.641 11.8643L18.5052 10.3937C18.4147 9.44345 18.3243 8.51584 18.2338 7.56562C18.1885 7.13575 17.8944 6.86425 17.4419 6.86425C17.3062 6.86425 17.1704 6.86425 17.0347 6.86425C16.8537 6.86425 16.6953 6.86425 16.5143 6.86425C16.4464 6.86425 16.4012 6.86425 16.3333 6.86425C16.2654 6.86425 16.2202 6.86425 16.1523 6.86425C16.0844 6.86425 15.9713 6.86426 15.9034 6.93214C15.8129 7.02263 15.8129 7.18099 15.8356 7.31674C15.8356 7.38461 15.8582 7.45249 15.8582 7.52037C15.8582 7.54299 15.8582 7.56561 15.8582 7.58824V7.63349C15.8582 8.04073 15.5867 8.31222 15.2021 8.31222C14.8175 8.31222 14.546 8.04073 14.5233 7.63349C14.5233 7.42987 14.5233 7.24888 14.5233 7.04526C14.5233 7.00001 14.5233 6.95475 14.4781 6.9095C14.4328 6.86425 14.3876 6.86425 14.3423 6.86425C13.6636 6.86425 12.9623 6.86425 12.2835 6.86425C11.6274 6.86425 10.2926 6.86425 10.2926 6.86425C10.2473 6.86425 10.1795 6.86425 10.1568 6.9095C10.1116 6.95475 10.1116 7.00001 10.1116 7.04526C10.1116 7.20363 10.1116 7.362 10.1116 7.52037V7.58824C10.1116 8.0181 9.86271 8.26697 9.45547 8.26697C9.41022 8.26697 9.36497 8.26697 9.31973 8.26697C8.95774 8.1991 8.77674 7.97285 8.77674 7.58824V7.4525C8.77674 7.31675 8.77674 7.20362 8.77674 7.06788C8.77674 7 8.77674 6.93213 8.73149 6.9095C8.68624 6.86425 8.61837 6.86425 8.55049 6.86425C8.3695 6.86425 8.21113 6.86425 8.05276 6.86425H7.53239C7.46452 6.88688 7.3514 6.88688 7.23828 6.88688ZM12.3514 3.31223C11.2428 3.31223 10.27 4.19458 10.1568 5.30317C10.1568 5.39367 10.1568 5.46154 10.2021 5.50679C10.2473 5.55204 10.3152 5.55204 10.3831 5.55204C10.6998 5.55204 10.9939 5.55204 11.2881 5.55204H12.3288H13.3695C13.6636 5.55204 13.9804 5.55204 14.2745 5.55204C14.365 5.55204 14.4328 5.55204 14.4781 5.50679C14.5233 5.46154 14.5233 5.4163 14.5233 5.30317C14.4328 4.17195 13.4826 3.31223 12.3514 3.31223Z"
                                                fill="white" />
                                            <path
                                                d="M9.04773 19.0597C8.84411 19.0597 8.64049 18.9013 8.52737 18.6525C8.41425 18.4036 8.43687 18.1774 8.66311 17.9285C9.68121 16.6841 10.6993 15.4398 11.7174 14.1955L13.2332 12.3402C13.8441 11.6163 14.4323 10.8697 15.0432 10.1457C15.2016 9.96468 15.3826 9.85156 15.5862 9.85156C15.7898 9.85156 15.9934 9.96469 16.1292 10.1683C16.3102 10.4398 16.2875 10.7113 16.0613 11.0054C15.4504 11.7746 14.817 12.5212 14.2061 13.2905L12.5093 15.3493C11.5817 16.4805 10.6541 17.6117 9.72646 18.743C9.56809 18.924 9.43234 19.0823 9.07035 19.0823L9.04773 19.0597Z"
                                                fill="white" />
                                            <path
                                                d="M15.2017 19.0597C14.0479 19.0597 13.0977 18.1095 13.0977 16.9556C13.0977 15.8018 14.0479 14.8516 15.2017 14.8516C16.3556 14.8516 17.3058 15.8018 17.3058 16.9556C17.3058 18.1095 16.3782 19.0597 15.2017 19.0597ZM15.2017 16.1864C14.7945 16.1864 14.4551 16.5484 14.4551 16.9556C14.4551 17.3629 14.8171 17.7022 15.2244 17.7022C15.428 17.7022 15.609 17.6117 15.7673 17.476C15.9031 17.3176 15.9936 17.1366 15.9936 16.933C15.971 16.5258 15.6316 16.1864 15.2017 16.1864Z"
                                                fill="white" />
                                            <path
                                                d="M9.52503 14.0359C9.27616 14.0359 9.0273 13.9907 8.7558 13.9228C8.07707 13.6287 7.64721 13.1536 7.46621 12.4975C7.30784 11.9319 7.39834 11.2984 7.7377 10.8006C8.07707 10.3029 8.59743 9.96354 9.18567 9.89567C9.29879 9.87304 9.41191 9.87305 9.50241 9.87305C10.4979 9.87305 11.3802 10.6196 11.5386 11.6151C11.7422 12.7463 10.9956 13.8323 9.88702 14.0359C9.7739 14.0359 9.66078 14.0359 9.52503 14.0359ZM9.52503 11.1853C9.32141 11.1853 9.14042 11.2758 8.98205 11.4341C8.71055 11.7056 8.68793 12.0224 8.89155 12.3617C9.04992 12.588 9.29879 12.7011 9.52503 12.7011C9.6834 12.7011 9.84177 12.6332 9.97752 12.5427C10.1359 12.407 10.249 12.226 10.2716 12.0224C10.2943 11.8187 10.2264 11.6151 10.0906 11.4568C9.90965 11.2758 9.72865 11.1853 9.52503 11.1853Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Scheme Area')}}</span>
                                    </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{(Request::is('admin/scheme/scope/view*'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.scheme.scope.view') }}"
                                        title="{{\App\CPU\translate('Flash_Deals')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.85366 21.9321C6.06181 21.9321 5.24733 21.5701 4.70434 20.9593C4.31973 20.5294 3.88986 19.7602 4.02561 18.6063C4.09348 18.0181 4.16136 17.4072 4.2066 16.819C4.22923 16.5475 4.25185 16.2534 4.27448 15.9819L4.3876 14.6923C4.45547 14.0815 4.50072 13.4932 4.5686 12.8824C4.61385 12.4977 4.63647 12.1358 4.68172 11.7511C4.72697 11.276 4.77221 10.8009 4.81746 10.3258C4.84009 10.0543 4.86271 9.76019 4.88534 9.48869C4.95321 8.76471 5.02109 7.99548 5.13421 7.2715C5.26995 6.29865 6.15231 5.57467 7.17041 5.57467C7.17041 5.57467 7.66814 5.57467 7.89439 5.57467C8.14326 5.57467 8.3695 5.57467 8.61837 5.57467C8.77674 5.57467 8.82199 5.50679 8.82199 5.37105C8.93511 3.44797 10.4509 2 12.374 2H12.3966C14.2292 2.02262 15.7903 3.51585 15.8808 5.34843C15.8808 5.52942 15.9487 5.57467 16.1297 5.57467H16.1749C16.3333 5.57467 16.4917 5.57467 16.65 5.57467C16.8084 5.57467 16.9668 5.57467 17.1252 5.57467C17.3966 5.57467 17.6229 5.59728 17.8265 5.61991C18.822 5.77828 19.5233 6.54752 19.6138 7.56562C19.727 8.76471 19.8401 9.96381 19.9532 11.1855C20.0211 11.819 20.0663 12.4751 20.1342 13.1086C20.1795 13.6968 20.2473 14.2851 20.2926 14.8733C20.4057 16.095 20.5188 17.3167 20.6319 18.5385C20.6998 19.1041 20.6998 19.7149 20.4283 20.2806C19.908 21.3665 19.0482 21.9321 17.8718 21.9547C17.1252 21.9547 16.3785 21.9774 15.6093 21.9774C15.0663 21.9774 14.5007 21.9774 13.9577 21.9774C13.4147 21.9774 12.8491 21.9774 12.3062 21.9774C11.7405 21.9774 11.1976 21.9774 10.6772 21.9774C10.1342 21.9774 9.59122 21.9774 9.04823 21.9774C8.23375 21.9774 7.55502 21.9774 6.92154 22L6.85366 21.9321ZM7.23828 6.88688C6.80842 6.88688 6.5143 7.15838 6.46905 7.56562C6.40117 8.26697 6.3333 8.99095 6.26543 9.69231L6.10706 11.2986C6.06181 11.7059 6.03919 12.1131 5.99394 12.5204C5.94869 13.0633 5.88081 13.6063 5.83556 14.1493L5.67719 15.7783C5.63194 16.2986 5.56407 16.819 5.54145 17.3394C5.51882 17.543 5.4962 17.7466 5.47357 17.9729C5.42832 18.3575 5.38307 18.7647 5.38307 19.1493C5.38307 19.8959 6.01656 20.552 6.74054 20.5747C6.94416 20.5747 7.1704 20.5747 7.37402 20.5747C7.60027 20.5747 7.82651 20.5747 8.05276 20.5747C8.25638 20.5747 8.43737 20.5747 8.64099 20.5747H8.70887C8.75412 20.5747 8.82199 20.5747 8.86724 20.5747H17.736C18.2338 20.5747 18.6636 20.4163 18.9351 20.0995C19.2066 19.8054 19.3423 19.3756 19.2971 18.8778C19.2518 18.2896 19.184 17.7014 19.1387 17.1131C19.1161 16.8643 19.0935 16.6154 19.0709 16.3665L18.641 11.8643L18.5052 10.3937C18.4147 9.44345 18.3243 8.51584 18.2338 7.56562C18.1885 7.13575 17.8944 6.86425 17.4419 6.86425C17.3062 6.86425 17.1704 6.86425 17.0347 6.86425C16.8537 6.86425 16.6953 6.86425 16.5143 6.86425C16.4464 6.86425 16.4012 6.86425 16.3333 6.86425C16.2654 6.86425 16.2202 6.86425 16.1523 6.86425C16.0844 6.86425 15.9713 6.86426 15.9034 6.93214C15.8129 7.02263 15.8129 7.18099 15.8356 7.31674C15.8356 7.38461 15.8582 7.45249 15.8582 7.52037C15.8582 7.54299 15.8582 7.56561 15.8582 7.58824V7.63349C15.8582 8.04073 15.5867 8.31222 15.2021 8.31222C14.8175 8.31222 14.546 8.04073 14.5233 7.63349C14.5233 7.42987 14.5233 7.24888 14.5233 7.04526C14.5233 7.00001 14.5233 6.95475 14.4781 6.9095C14.4328 6.86425 14.3876 6.86425 14.3423 6.86425C13.6636 6.86425 12.9623 6.86425 12.2835 6.86425C11.6274 6.86425 10.2926 6.86425 10.2926 6.86425C10.2473 6.86425 10.1795 6.86425 10.1568 6.9095C10.1116 6.95475 10.1116 7.00001 10.1116 7.04526C10.1116 7.20363 10.1116 7.362 10.1116 7.52037V7.58824C10.1116 8.0181 9.86271 8.26697 9.45547 8.26697C9.41022 8.26697 9.36497 8.26697 9.31973 8.26697C8.95774 8.1991 8.77674 7.97285 8.77674 7.58824V7.4525C8.77674 7.31675 8.77674 7.20362 8.77674 7.06788C8.77674 7 8.77674 6.93213 8.73149 6.9095C8.68624 6.86425 8.61837 6.86425 8.55049 6.86425C8.3695 6.86425 8.21113 6.86425 8.05276 6.86425H7.53239C7.46452 6.88688 7.3514 6.88688 7.23828 6.88688ZM12.3514 3.31223C11.2428 3.31223 10.27 4.19458 10.1568 5.30317C10.1568 5.39367 10.1568 5.46154 10.2021 5.50679C10.2473 5.55204 10.3152 5.55204 10.3831 5.55204C10.6998 5.55204 10.9939 5.55204 11.2881 5.55204H12.3288H13.3695C13.6636 5.55204 13.9804 5.55204 14.2745 5.55204C14.365 5.55204 14.4328 5.55204 14.4781 5.50679C14.5233 5.46154 14.5233 5.4163 14.5233 5.30317C14.4328 4.17195 13.4826 3.31223 12.3514 3.31223Z"
                                                fill="white" />
                                            <path
                                                d="M9.04773 19.0597C8.84411 19.0597 8.64049 18.9013 8.52737 18.6525C8.41425 18.4036 8.43687 18.1774 8.66311 17.9285C9.68121 16.6841 10.6993 15.4398 11.7174 14.1955L13.2332 12.3402C13.8441 11.6163 14.4323 10.8697 15.0432 10.1457C15.2016 9.96468 15.3826 9.85156 15.5862 9.85156C15.7898 9.85156 15.9934 9.96469 16.1292 10.1683C16.3102 10.4398 16.2875 10.7113 16.0613 11.0054C15.4504 11.7746 14.817 12.5212 14.2061 13.2905L12.5093 15.3493C11.5817 16.4805 10.6541 17.6117 9.72646 18.743C9.56809 18.924 9.43234 19.0823 9.07035 19.0823L9.04773 19.0597Z"
                                                fill="white" />
                                            <path
                                                d="M15.2017 19.0597C14.0479 19.0597 13.0977 18.1095 13.0977 16.9556C13.0977 15.8018 14.0479 14.8516 15.2017 14.8516C16.3556 14.8516 17.3058 15.8018 17.3058 16.9556C17.3058 18.1095 16.3782 19.0597 15.2017 19.0597ZM15.2017 16.1864C14.7945 16.1864 14.4551 16.5484 14.4551 16.9556C14.4551 17.3629 14.8171 17.7022 15.2244 17.7022C15.428 17.7022 15.609 17.6117 15.7673 17.476C15.9031 17.3176 15.9936 17.1366 15.9936 16.933C15.971 16.5258 15.6316 16.1864 15.2017 16.1864Z"
                                                fill="white" />
                                            <path
                                                d="M9.52503 14.0359C9.27616 14.0359 9.0273 13.9907 8.7558 13.9228C8.07707 13.6287 7.64721 13.1536 7.46621 12.4975C7.30784 11.9319 7.39834 11.2984 7.7377 10.8006C8.07707 10.3029 8.59743 9.96354 9.18567 9.89567C9.29879 9.87304 9.41191 9.87305 9.50241 9.87305C10.4979 9.87305 11.3802 10.6196 11.5386 11.6151C11.7422 12.7463 10.9956 13.8323 9.88702 14.0359C9.7739 14.0359 9.66078 14.0359 9.52503 14.0359ZM9.52503 11.1853C9.32141 11.1853 9.14042 11.2758 8.98205 11.4341C8.71055 11.7056 8.68793 12.0224 8.89155 12.3617C9.04992 12.588 9.29879 12.7011 9.52503 12.7011C9.6834 12.7011 9.84177 12.6332 9.97752 12.5427C10.1359 12.407 10.249 12.226 10.2716 12.0224C10.2943 11.8187 10.2264 11.6151 10.0906 11.4568C9.90965 11.2758 9.72865 11.1853 9.52503 11.1853Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Area_scope')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                       
                        @if(\App\CPU\Helpers::module_permission_check('training_management'))
                       <li class="nav-item 
                            {{ (
                                Request::is('admin/training') ||
                                Request::is('admin/training/add') ||
                                Request::is('admin/training/list') ||
                                Request::is('admin/training/edit/*') ||
                                Request::is('admin/training/view/*')
                            ) ? 'scroll-here' : '' }}">

                            <small class="nav-subtitle" title="">{{ \App\CPU\translate('Training_Courses') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu 
                            {{ (
                                Request::is('admin/training') ||
                                Request::is('admin/training/add') ||
                                Request::is('admin/training/list') ||
                                Request::is('admin/training/edit/*') ||
                                Request::is('admin/training/view/*')
                            ) ? 'active' : '' }}">

                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{ \App\CPU\translate('Training_Courses') }}">

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 4H20V6H4V4Z" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M4 10H20V12H4V10Z" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M4 16H20V18H4V16Z" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>

                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ \App\CPU\translate('Training_Courses') }}
                                </span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display:
                                {{ (
                                    Request::is('admin/training') ||
                                    Request::is('admin/training/add') ||
                                    Request::is('admin/training/list') ||
                                    Request::is('admin/training/edit/*') ||
                                    Request::is('admin/training/view/*')
                                ) ? 'block' : 'none' }}">

                                {{-- ADD TRAINING --}}
                                <li class="navbar-vertical-aside-has-menu 
                                    {{ Request::is('admin/training/add') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{ route('admin.training.add-training') }}"
                                    title="{{ \App\CPU\translate('Add') }}">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 5V19" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M5 12H19" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>

                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ \App\CPU\translate('Add') }}
                                        </span>
                                    </a>
                                </li>

                                {{-- LIST TRAINING --}}
                                <li class="navbar-vertical-aside-has-menu 
                                    {{ Request::is('admin/training/list') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{ route('admin.training.list-training') }}"
                                    title="{{ \App\CPU\translate('List') }}">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M4 7H20" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M4 12H20" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M4 17H20" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>

                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ \App\CPU\translate('List') }}
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        @endif
                       
                        
                        <li
                            class="d-none nav-item{{(Request::is('admin/area*')) ? 'scroll-here':''}}">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('city_area_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="d-none navbar-vertical-aside-has-menu {{(Request::is('admin/area*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('City_Area_Management')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.79476 7.05589C4.79476 5.80689 5.80676 4.79489 7.05576 4.79389H8.08476C8.68176 4.79389 9.25376 4.55689 9.67776 4.13689L10.3968 3.41689C11.2778 2.53089 12.7098 2.52689 13.5958 3.40789L13.5968 3.40889L13.6058 3.41689L14.3258 4.13689C14.7498 4.55789 15.3218 4.79389 15.9188 4.79389H16.9468C18.1958 4.79389 19.2088 5.80589 19.2088 7.05589V8.08289C19.2088 8.67989 19.4448 9.25289 19.8658 9.67689L20.5858 10.3969C21.4718 11.2779 21.4768 12.7099 20.5958 13.5959L20.5948 13.5969L20.5858 13.6059L19.8658 14.3259C19.4448 14.7489 19.2088 15.3209 19.2088 15.9179V16.9469C19.2088 18.1959 18.1968 19.2079 16.9478 19.2079H15.9168C15.3198 19.2079 14.7468 19.4449 14.3238 19.8659L13.6038 20.5849C12.7238 21.4709 11.2928 21.4759 10.4068 20.5969C10.4058 20.5959 10.4048 20.5949 10.4038 20.5939L10.3948 20.5849L9.67576 19.8659C9.25276 19.4449 8.67976 19.2089 8.08276 19.2079H7.05576C5.80676 19.2079 4.79476 18.1959 4.79476 16.9469V15.9159C4.79476 15.3189 4.55776 14.7469 4.13676 14.3239L3.41776 13.6039C2.53176 12.7239 2.52676 11.2929 3.40676 10.4069C3.40676 10.4059 3.40776 10.4049 3.40876 10.4039L3.41776 10.3949L4.13676 9.67489C4.55776 9.25089 4.79476 8.67889 4.79476 8.08089V7.05589Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9.42969 14.5714L14.5697 9.4314" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.4961 14.4998H14.5051" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.49609 9.49976H9.50509" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('City_Area_Management')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('admin/area*'))?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/area/city*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.area.city') }}"
                                        title="{{\App\CPU\translate('coupon')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.326 14.3625C20.306 14.3261 19.5046 13.5064 19.5046 12.5046C19.5046 11.4845 20.306 10.6648 21.326 10.6284C21.7632 10.6102 22 10.3734 22 9.93625C22 8.93443 22 7.93261 22 6.93079C22 6.76686 21.9818 6.58471 21.9454 6.42077C21.7268 5.56467 20.9982 5 20.0874 5C14.6958 5 9.3224 5 3.93078 5C3.89435 5 3.85792 5 3.83971 5C3.03825 5.01821 2.32787 5.58288 2.09107 6.34791C2.07286 6.40255 2.09108 6.47542 2.01822 6.51185C2.01822 7.71403 2.01822 8.93443 2.01822 10.1366C2.10929 10.4827 2.30965 10.6466 2.67395 10.6466C2.69217 10.6466 2.7286 10.6466 2.74681 10.6466C3.40255 10.7013 3.91257 11.0109 4.22222 11.5756C4.89617 12.796 4.05829 14.2168 2.65574 14.2896C2.29144 14.3078 2.09107 14.4718 2 14.7996C2 16.0018 2 17.1858 2 18.388C2.05464 18.4062 2.03643 18.4426 2.03643 18.4791C2.27322 19.3898 2.98361 19.9362 3.93078 19.9362C9.30419 19.9362 14.6776 19.9362 20.051 19.9362C21.1621 19.9362 21.9818 19.1166 21.9818 18.0055C21.9818 17.0036 21.9818 16.02 21.9818 15.0182C22.0182 14.6175 21.7814 14.3807 21.326 14.3625ZM20.7614 15.8197C20.7432 16.5665 20.7614 17.2951 20.7614 18.0419C20.7614 18.4791 20.5064 18.7341 20.0692 18.7341C17.3916 18.7341 14.6958 18.7341 12.0182 18.7341C9.35883 18.7341 6.68124 18.7341 4.02186 18.7341C3.53005 18.7341 3.29326 18.4973 3.29326 17.9873C3.29326 17.2404 3.29326 16.4754 3.29326 15.7286C3.29326 15.6011 3.31148 15.5464 3.4572 15.51C4.85975 15.1275 5.78871 13.9071 5.78871 12.4863C5.78871 11.0656 4.84153 9.84517 3.4572 9.46266C3.32969 9.42623 3.29326 9.37159 3.29326 9.24409C3.29326 8.47906 3.29326 7.69582 3.29326 6.93079C3.29326 6.49363 3.54827 6.23862 3.98543 6.23862C9.35883 6.23862 14.714 6.23862 20.0874 6.23862C20.5428 6.23862 20.7796 6.49363 20.7796 6.949C20.7796 7.71403 20.7796 8.47906 20.7796 9.24409C20.7796 9.37159 20.7614 9.42623 20.6157 9.46266C19.3042 9.80874 18.3752 10.9381 18.2842 12.2678C18.1931 13.5975 18.9581 14.8361 20.2149 15.3643C20.3242 15.4007 20.4153 15.4554 20.5246 15.4736C20.725 15.51 20.7796 15.6193 20.7614 15.8197Z"
                                                fill="white" />
                                            <path
                                                d="M14.4599 7.7499C14.4417 7.78633 14.4235 7.84098 14.4052 7.87741C13.2031 11.1015 12.0009 14.3073 10.7987 17.5313C10.744 17.6588 10.7076 17.7135 10.5619 17.6406C10.2887 17.5131 9.99722 17.422 9.70578 17.3127C9.57828 17.2763 9.57828 17.2217 9.61471 17.1124C10.1612 15.6552 10.7076 14.198 11.2541 12.7226C11.9098 10.9739 12.5655 9.22531 13.2213 7.45846C13.2759 7.31274 13.3306 7.2581 13.4945 7.33096C13.7677 7.45847 14.0592 7.54954 14.3506 7.65883C14.387 7.67704 14.4599 7.67704 14.4599 7.7499Z"
                                                fill="white" />
                                            <path
                                                d="M15.5145 12.832C14.4762 12.832 13.6383 13.6699 13.6565 14.7082C13.6565 15.7464 14.4944 16.5843 15.5327 16.5843C16.5527 16.5843 17.4088 15.7282 17.4088 14.7082C17.3906 13.6699 16.5527 12.832 15.5145 12.832ZM15.5145 15.3275C15.1684 15.3275 14.8952 15.036 14.8952 14.69C14.8952 14.3439 15.1684 14.0707 15.5145 14.0889C15.8605 14.0889 16.1338 14.3803 16.1338 14.7082C16.1338 15.036 15.8605 15.3275 15.5145 15.3275Z"
                                                fill="white" />
                                            <path
                                                d="M9.06754 8.95312C8.04751 8.95312 7.19141 9.80922 7.19141 10.8293C7.19141 11.8675 8.02929 12.6872 9.06754 12.6872C10.1058 12.6872 10.9255 11.8493 10.9255 10.8111C10.9255 9.79101 10.0876 8.95312 9.06754 8.95312ZM9.06754 11.4486C8.72146 11.4486 8.43002 11.1571 8.43002 10.8293C8.43002 10.4832 8.72146 10.21 9.04933 10.21C9.39541 10.21 9.66864 10.4832 9.66864 10.8293C9.68685 11.1753 9.41363 11.4486 9.06754 11.4486Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('City')}}</span>
                                    </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{(Request::is('admin/area/view*'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.area.view') }}"
                                        title="{{\App\CPU\translate('Flash_Deals')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.85366 21.9321C6.06181 21.9321 5.24733 21.5701 4.70434 20.9593C4.31973 20.5294 3.88986 19.7602 4.02561 18.6063C4.09348 18.0181 4.16136 17.4072 4.2066 16.819C4.22923 16.5475 4.25185 16.2534 4.27448 15.9819L4.3876 14.6923C4.45547 14.0815 4.50072 13.4932 4.5686 12.8824C4.61385 12.4977 4.63647 12.1358 4.68172 11.7511C4.72697 11.276 4.77221 10.8009 4.81746 10.3258C4.84009 10.0543 4.86271 9.76019 4.88534 9.48869C4.95321 8.76471 5.02109 7.99548 5.13421 7.2715C5.26995 6.29865 6.15231 5.57467 7.17041 5.57467C7.17041 5.57467 7.66814 5.57467 7.89439 5.57467C8.14326 5.57467 8.3695 5.57467 8.61837 5.57467C8.77674 5.57467 8.82199 5.50679 8.82199 5.37105C8.93511 3.44797 10.4509 2 12.374 2H12.3966C14.2292 2.02262 15.7903 3.51585 15.8808 5.34843C15.8808 5.52942 15.9487 5.57467 16.1297 5.57467H16.1749C16.3333 5.57467 16.4917 5.57467 16.65 5.57467C16.8084 5.57467 16.9668 5.57467 17.1252 5.57467C17.3966 5.57467 17.6229 5.59728 17.8265 5.61991C18.822 5.77828 19.5233 6.54752 19.6138 7.56562C19.727 8.76471 19.8401 9.96381 19.9532 11.1855C20.0211 11.819 20.0663 12.4751 20.1342 13.1086C20.1795 13.6968 20.2473 14.2851 20.2926 14.8733C20.4057 16.095 20.5188 17.3167 20.6319 18.5385C20.6998 19.1041 20.6998 19.7149 20.4283 20.2806C19.908 21.3665 19.0482 21.9321 17.8718 21.9547C17.1252 21.9547 16.3785 21.9774 15.6093 21.9774C15.0663 21.9774 14.5007 21.9774 13.9577 21.9774C13.4147 21.9774 12.8491 21.9774 12.3062 21.9774C11.7405 21.9774 11.1976 21.9774 10.6772 21.9774C10.1342 21.9774 9.59122 21.9774 9.04823 21.9774C8.23375 21.9774 7.55502 21.9774 6.92154 22L6.85366 21.9321ZM7.23828 6.88688C6.80842 6.88688 6.5143 7.15838 6.46905 7.56562C6.40117 8.26697 6.3333 8.99095 6.26543 9.69231L6.10706 11.2986C6.06181 11.7059 6.03919 12.1131 5.99394 12.5204C5.94869 13.0633 5.88081 13.6063 5.83556 14.1493L5.67719 15.7783C5.63194 16.2986 5.56407 16.819 5.54145 17.3394C5.51882 17.543 5.4962 17.7466 5.47357 17.9729C5.42832 18.3575 5.38307 18.7647 5.38307 19.1493C5.38307 19.8959 6.01656 20.552 6.74054 20.5747C6.94416 20.5747 7.1704 20.5747 7.37402 20.5747C7.60027 20.5747 7.82651 20.5747 8.05276 20.5747C8.25638 20.5747 8.43737 20.5747 8.64099 20.5747H8.70887C8.75412 20.5747 8.82199 20.5747 8.86724 20.5747H17.736C18.2338 20.5747 18.6636 20.4163 18.9351 20.0995C19.2066 19.8054 19.3423 19.3756 19.2971 18.8778C19.2518 18.2896 19.184 17.7014 19.1387 17.1131C19.1161 16.8643 19.0935 16.6154 19.0709 16.3665L18.641 11.8643L18.5052 10.3937C18.4147 9.44345 18.3243 8.51584 18.2338 7.56562C18.1885 7.13575 17.8944 6.86425 17.4419 6.86425C17.3062 6.86425 17.1704 6.86425 17.0347 6.86425C16.8537 6.86425 16.6953 6.86425 16.5143 6.86425C16.4464 6.86425 16.4012 6.86425 16.3333 6.86425C16.2654 6.86425 16.2202 6.86425 16.1523 6.86425C16.0844 6.86425 15.9713 6.86426 15.9034 6.93214C15.8129 7.02263 15.8129 7.18099 15.8356 7.31674C15.8356 7.38461 15.8582 7.45249 15.8582 7.52037C15.8582 7.54299 15.8582 7.56561 15.8582 7.58824V7.63349C15.8582 8.04073 15.5867 8.31222 15.2021 8.31222C14.8175 8.31222 14.546 8.04073 14.5233 7.63349C14.5233 7.42987 14.5233 7.24888 14.5233 7.04526C14.5233 7.00001 14.5233 6.95475 14.4781 6.9095C14.4328 6.86425 14.3876 6.86425 14.3423 6.86425C13.6636 6.86425 12.9623 6.86425 12.2835 6.86425C11.6274 6.86425 10.2926 6.86425 10.2926 6.86425C10.2473 6.86425 10.1795 6.86425 10.1568 6.9095C10.1116 6.95475 10.1116 7.00001 10.1116 7.04526C10.1116 7.20363 10.1116 7.362 10.1116 7.52037V7.58824C10.1116 8.0181 9.86271 8.26697 9.45547 8.26697C9.41022 8.26697 9.36497 8.26697 9.31973 8.26697C8.95774 8.1991 8.77674 7.97285 8.77674 7.58824V7.4525C8.77674 7.31675 8.77674 7.20362 8.77674 7.06788C8.77674 7 8.77674 6.93213 8.73149 6.9095C8.68624 6.86425 8.61837 6.86425 8.55049 6.86425C8.3695 6.86425 8.21113 6.86425 8.05276 6.86425H7.53239C7.46452 6.88688 7.3514 6.88688 7.23828 6.88688ZM12.3514 3.31223C11.2428 3.31223 10.27 4.19458 10.1568 5.30317C10.1568 5.39367 10.1568 5.46154 10.2021 5.50679C10.2473 5.55204 10.3152 5.55204 10.3831 5.55204C10.6998 5.55204 10.9939 5.55204 11.2881 5.55204H12.3288H13.3695C13.6636 5.55204 13.9804 5.55204 14.2745 5.55204C14.365 5.55204 14.4328 5.55204 14.4781 5.50679C14.5233 5.46154 14.5233 5.4163 14.5233 5.30317C14.4328 4.17195 13.4826 3.31223 12.3514 3.31223Z"
                                                fill="white" />
                                            <path
                                                d="M9.04773 19.0597C8.84411 19.0597 8.64049 18.9013 8.52737 18.6525C8.41425 18.4036 8.43687 18.1774 8.66311 17.9285C9.68121 16.6841 10.6993 15.4398 11.7174 14.1955L13.2332 12.3402C13.8441 11.6163 14.4323 10.8697 15.0432 10.1457C15.2016 9.96468 15.3826 9.85156 15.5862 9.85156C15.7898 9.85156 15.9934 9.96469 16.1292 10.1683C16.3102 10.4398 16.2875 10.7113 16.0613 11.0054C15.4504 11.7746 14.817 12.5212 14.2061 13.2905L12.5093 15.3493C11.5817 16.4805 10.6541 17.6117 9.72646 18.743C9.56809 18.924 9.43234 19.0823 9.07035 19.0823L9.04773 19.0597Z"
                                                fill="white" />
                                            <path
                                                d="M15.2017 19.0597C14.0479 19.0597 13.0977 18.1095 13.0977 16.9556C13.0977 15.8018 14.0479 14.8516 15.2017 14.8516C16.3556 14.8516 17.3058 15.8018 17.3058 16.9556C17.3058 18.1095 16.3782 19.0597 15.2017 19.0597ZM15.2017 16.1864C14.7945 16.1864 14.4551 16.5484 14.4551 16.9556C14.4551 17.3629 14.8171 17.7022 15.2244 17.7022C15.428 17.7022 15.609 17.6117 15.7673 17.476C15.9031 17.3176 15.9936 17.1366 15.9936 16.933C15.971 16.5258 15.6316 16.1864 15.2017 16.1864Z"
                                                fill="white" />
                                            <path
                                                d="M9.52503 14.0359C9.27616 14.0359 9.0273 13.9907 8.7558 13.9228C8.07707 13.6287 7.64721 13.1536 7.46621 12.4975C7.30784 11.9319 7.39834 11.2984 7.7377 10.8006C8.07707 10.3029 8.59743 9.96354 9.18567 9.89567C9.29879 9.87304 9.41191 9.87305 9.50241 9.87305C10.4979 9.87305 11.3802 10.6196 11.5386 11.6151C11.7422 12.7463 10.9956 13.8323 9.88702 14.0359C9.7739 14.0359 9.66078 14.0359 9.52503 14.0359ZM9.52503 11.1853C9.32141 11.1853 9.14042 11.2758 8.98205 11.4341C8.71055 11.7056 8.68793 12.0224 8.89155 12.3617C9.04992 12.588 9.29879 12.7011 9.52503 12.7011C9.6834 12.7011 9.84177 12.6332 9.97752 12.5427C10.1359 12.407 10.249 12.226 10.2716 12.0224C10.2943 11.8187 10.2264 11.6151 10.0906 11.4568C9.90965 11.2758 9.72865 11.1853 9.52503 11.1853Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Area')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>        
                      

                        <!-- <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/area*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('City_Area_Management')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.79476 7.05589C4.79476 5.80689 5.80676 4.79489 7.05576 4.79389H8.08476C8.68176 4.79389 9.25376 4.55689 9.67776 4.13689L10.3968 3.41689C11.2778 2.53089 12.7098 2.52689 13.5958 3.40789L13.5968 3.40889L13.6058 3.41689L14.3258 4.13689C14.7498 4.55789 15.3218 4.79389 15.9188 4.79389H16.9468C18.1958 4.79389 19.2088 5.80589 19.2088 7.05589V8.08289C19.2088 8.67989 19.4448 9.25289 19.8658 9.67689L20.5858 10.3969C21.4718 11.2779 21.4768 12.7099 20.5958 13.5959L20.5948 13.5969L20.5858 13.6059L19.8658 14.3259C19.4448 14.7489 19.2088 15.3209 19.2088 15.9179V16.9469C19.2088 18.1959 18.1968 19.2079 16.9478 19.2079H15.9168C15.3198 19.2079 14.7468 19.4449 14.3238 19.8659L13.6038 20.5849C12.7238 21.4709 11.2928 21.4759 10.4068 20.5969C10.4058 20.5959 10.4048 20.5949 10.4038 20.5939L10.3948 20.5849L9.67576 19.8659C9.25276 19.4449 8.67976 19.2089 8.08276 19.2079H7.05576C5.80676 19.2079 4.79476 18.1959 4.79476 16.9469V15.9159C4.79476 15.3189 4.55776 14.7469 4.13676 14.3239L3.41776 13.6039C2.53176 12.7239 2.52676 11.2929 3.40676 10.4069C3.40676 10.4059 3.40776 10.4049 3.40876 10.4039L3.41776 10.3949L4.13676 9.67489C4.55776 9.25089 4.79476 8.67889 4.79476 8.08089V7.05589Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9.42969 14.5714L14.5697 9.4314" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.4961 14.4998H14.5051" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.49609 9.49976H9.50509" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Applicants')}}</span>
                            </a>
                            <ul style=" display:none;" class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('admin/area*'))?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/area/city*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.area.city') }}"
                                        title="{{\App\CPU\translate('coupon')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.326 14.3625C20.306 14.3261 19.5046 13.5064 19.5046 12.5046C19.5046 11.4845 20.306 10.6648 21.326 10.6284C21.7632 10.6102 22 10.3734 22 9.93625C22 8.93443 22 7.93261 22 6.93079C22 6.76686 21.9818 6.58471 21.9454 6.42077C21.7268 5.56467 20.9982 5 20.0874 5C14.6958 5 9.3224 5 3.93078 5C3.89435 5 3.85792 5 3.83971 5C3.03825 5.01821 2.32787 5.58288 2.09107 6.34791C2.07286 6.40255 2.09108 6.47542 2.01822 6.51185C2.01822 7.71403 2.01822 8.93443 2.01822 10.1366C2.10929 10.4827 2.30965 10.6466 2.67395 10.6466C2.69217 10.6466 2.7286 10.6466 2.74681 10.6466C3.40255 10.7013 3.91257 11.0109 4.22222 11.5756C4.89617 12.796 4.05829 14.2168 2.65574 14.2896C2.29144 14.3078 2.09107 14.4718 2 14.7996C2 16.0018 2 17.1858 2 18.388C2.05464 18.4062 2.03643 18.4426 2.03643 18.4791C2.27322 19.3898 2.98361 19.9362 3.93078 19.9362C9.30419 19.9362 14.6776 19.9362 20.051 19.9362C21.1621 19.9362 21.9818 19.1166 21.9818 18.0055C21.9818 17.0036 21.9818 16.02 21.9818 15.0182C22.0182 14.6175 21.7814 14.3807 21.326 14.3625ZM20.7614 15.8197C20.7432 16.5665 20.7614 17.2951 20.7614 18.0419C20.7614 18.4791 20.5064 18.7341 20.0692 18.7341C17.3916 18.7341 14.6958 18.7341 12.0182 18.7341C9.35883 18.7341 6.68124 18.7341 4.02186 18.7341C3.53005 18.7341 3.29326 18.4973 3.29326 17.9873C3.29326 17.2404 3.29326 16.4754 3.29326 15.7286C3.29326 15.6011 3.31148 15.5464 3.4572 15.51C4.85975 15.1275 5.78871 13.9071 5.78871 12.4863C5.78871 11.0656 4.84153 9.84517 3.4572 9.46266C3.32969 9.42623 3.29326 9.37159 3.29326 9.24409C3.29326 8.47906 3.29326 7.69582 3.29326 6.93079C3.29326 6.49363 3.54827 6.23862 3.98543 6.23862C9.35883 6.23862 14.714 6.23862 20.0874 6.23862C20.5428 6.23862 20.7796 6.49363 20.7796 6.949C20.7796 7.71403 20.7796 8.47906 20.7796 9.24409C20.7796 9.37159 20.7614 9.42623 20.6157 9.46266C19.3042 9.80874 18.3752 10.9381 18.2842 12.2678C18.1931 13.5975 18.9581 14.8361 20.2149 15.3643C20.3242 15.4007 20.4153 15.4554 20.5246 15.4736C20.725 15.51 20.7796 15.6193 20.7614 15.8197Z"
                                                fill="white" />
                                            <path
                                                d="M14.4599 7.7499C14.4417 7.78633 14.4235 7.84098 14.4052 7.87741C13.2031 11.1015 12.0009 14.3073 10.7987 17.5313C10.744 17.6588 10.7076 17.7135 10.5619 17.6406C10.2887 17.5131 9.99722 17.422 9.70578 17.3127C9.57828 17.2763 9.57828 17.2217 9.61471 17.1124C10.1612 15.6552 10.7076 14.198 11.2541 12.7226C11.9098 10.9739 12.5655 9.22531 13.2213 7.45846C13.2759 7.31274 13.3306 7.2581 13.4945 7.33096C13.7677 7.45847 14.0592 7.54954 14.3506 7.65883C14.387 7.67704 14.4599 7.67704 14.4599 7.7499Z"
                                                fill="white" />
                                            <path
                                                d="M15.5145 12.832C14.4762 12.832 13.6383 13.6699 13.6565 14.7082C13.6565 15.7464 14.4944 16.5843 15.5327 16.5843C16.5527 16.5843 17.4088 15.7282 17.4088 14.7082C17.3906 13.6699 16.5527 12.832 15.5145 12.832ZM15.5145 15.3275C15.1684 15.3275 14.8952 15.036 14.8952 14.69C14.8952 14.3439 15.1684 14.0707 15.5145 14.0889C15.8605 14.0889 16.1338 14.3803 16.1338 14.7082C16.1338 15.036 15.8605 15.3275 15.5145 15.3275Z"
                                                fill="white" />
                                            <path
                                                d="M9.06754 8.95312C8.04751 8.95312 7.19141 9.80922 7.19141 10.8293C7.19141 11.8675 8.02929 12.6872 9.06754 12.6872C10.1058 12.6872 10.9255 11.8493 10.9255 10.8111C10.9255 9.79101 10.0876 8.95312 9.06754 8.95312ZM9.06754 11.4486C8.72146 11.4486 8.43002 11.1571 8.43002 10.8293C8.43002 10.4832 8.72146 10.21 9.04933 10.21C9.39541 10.21 9.66864 10.4832 9.66864 10.8293C9.68685 11.1753 9.41363 11.4486 9.06754 11.4486Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('All')}}</span>
                                    </a>
                                </li>
                              
                            </ul>
                        </li>         -->

                        @if(\App\CPU\Helpers::module_permission_check('application_management'))
                            <li class=" navbar-vertical-aside-has-menu {{ (Request::is('admin/applicants/approved'))?'active':''}}"
                                title="{{\App\CPU\translate('Settings')}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{ route('admin.application.approved-list') }}"
                                    title="{{\App\CPU\translate('Applicants')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M15.7161 16.2236H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M15.7161 12.0371H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M11.2511 7.86035H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.908 2.75C15.908 2.75 8.231 2.754 8.219 2.754C5.459 2.771 3.75 4.587 3.75 7.357V16.553C3.75 19.337 5.472 21.16 8.256 21.16C8.256 21.16 15.932 21.157 15.945 21.157C18.705 21.14 20.415 19.323 20.415 16.553V7.357C20.415 4.573 18.692 2.75 15.908 2.75Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('Applicants')}}
                                    </span>
                                </a>
                            </li>
                            @if (auth('admin')->user()->admin_role_id == 1)
                                <li class=" navbar-vertical-aside-has-menu {{(Request::is('admin/applicants/assessment'))?'active':''}}"
                                    title="{{\App\CPU\translate('Settings')}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.application.assessment') }}"
                                        title="{{\App\CPU\translate('Request')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M15.7161 16.2236H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.7161 12.0371H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M11.2511 7.86035H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.908 2.75C15.908 2.75 8.231 2.754 8.219 2.754C5.459 2.771 3.75 4.587 3.75 7.357V16.553C3.75 19.337 5.472 21.16 8.256 21.16C8.256 21.16 15.932 21.157 15.945 21.157C18.705 21.14 20.415 19.323 20.415 16.553V7.357C20.415 4.573 18.692 2.75 15.908 2.75Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Request Application')}}
                                        </span>
                                    </a>
                                </li>

                                <li class=" navbar-vertical-aside-has-menu {{ (Request::is('admin/applicants/witness-list'))?'active':''}}"
                                    title="{{\App\CPU\translate('Witness')}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.application.witness-list') }}"
                                        title="{{\App\CPU\translate('Client Witness')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M15.7161 16.2236H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.7161 12.0371H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M11.2511 7.86035H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.908 2.75C15.908 2.75 8.231 2.754 8.219 2.754C5.459 2.771 3.75 4.587 3.75 7.357V16.553C3.75 19.337 5.472 21.16 8.256 21.16C8.256 21.16 15.932 21.157 15.945 21.157C18.705 21.14 20.415 19.323 20.415 16.553V7.357C20.415 4.573 18.692 2.75 15.908 2.75Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Client Witness')}}
                                        </span>
                                    </a>
                                </li>
                            
                            @endif
                        @endif
                        
                        @if(\App\CPU\Helpers::module_permission_check('fee_management'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/training/fee*') ? 'active' : '' }}"
                            title="{{ \App\CPU\translate('Fee Structures') }}">

                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('admin.training.fee-list') }}"
                            title="{{ \App\CPU\translate('Fee Structures') }}">

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M15.7161 16.2236H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15.7161 12.0371H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M11.2511 7.86035H8.49609" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M15.908 2.75C15.908 2.75 8.231 2.754 8.219 2.754C5.459 2.771 3.75 4.587 3.75 7.357V16.553C3.75 19.337 5.472 21.16 8.256 21.16C8.256 21.16 15.932 21.157 15.945 21.157C18.705 21.14 20.415 19.323 20.415 16.553V7.357C20.415 4.573 18.692 2.75 15.908 2.75Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ \App\CPU\translate('Fee Structures') }}
                                </span>
                            </a>
                        </li>
                        @endif


                        <!-- Plan -->
                        <li class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/plan*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('Membership')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M5.76828 19.0051C5.33127 19.0051 5.10236 18.8386 4.9775 18.4016L4.20754 15.842C3.5 13.5321 2.77165 11.1181 2.04331 8.76659C1.96007 8.51687 2.00169 8.24635 2.12655 8.05906C2.2306 7.89258 2.37626 7.80934 2.56355 7.78853C2.58436 7.78853 2.62598 7.78853 2.6676 7.78853C2.81327 7.78853 2.97975 7.83015 3.12542 7.89258C4.1243 8.37121 5.12317 8.84983 6.10124 9.32846L7.12092 9.8279C7.16254 9.84871 7.41226 9.97357 7.41226 9.97357L7.47469 9.86952C7.4955 9.84871 7.51631 9.80709 7.51631 9.78628C8.66085 8.47525 11.4078 5.29134 11.4078 5.29134C11.5742 5.10405 11.7823 5 11.9904 5C12.1985 5 12.4066 5.10405 12.5731 5.31215L16.4438 9.78628C16.4646 9.80709 16.4854 9.84871 16.527 9.86952L16.6102 9.95275L16.9016 9.80708L18.0461 9.24522C18.9826 8.78741 19.919 8.32958 20.8555 7.87176C20.9803 7.80933 21.126 7.74691 21.2925 7.74691C21.3549 7.74691 21.3965 7.7469 21.4589 7.76771C21.7087 7.83014 21.8751 7.99663 22 8.30878V8.60011L21.7711 9.32846C21.6046 9.86951 21.4381 10.4106 21.2717 10.9724C20.793 12.554 20.3144 14.1355 19.8358 15.7171L19.0242 18.36C18.8993 18.797 18.6704 18.9634 18.2126 18.9634H12.0112L5.76828 19.0051ZM11.0956 17.6108C13.2182 17.6108 15.3408 17.6108 17.4634 17.6108C17.6507 17.6108 17.7548 17.5484 17.8172 17.3611C18.3583 15.5714 18.8993 13.7818 19.4404 11.9921L19.9814 10.2233C20.0023 10.1817 20.0023 10.14 20.0231 10.0984L20.0855 9.8487L19.6693 10.0568L16.8391 11.4303C16.6519 11.5135 16.5062 11.5551 16.3813 11.5551C16.1732 11.5551 15.9859 11.4511 15.7986 11.2222L12.3442 7.22666C12.2818 7.16423 12.2193 7.08099 12.1569 7.01856L11.9904 6.83127L11.8864 6.93532C11.8656 6.95613 11.8448 6.97694 11.824 7.01856L8.16142 11.243C7.97413 11.4719 7.78684 11.5759 7.57874 11.5759C7.45388 11.5759 7.30821 11.5343 7.14173 11.4511L6.20529 10.9932L3.85377 9.8487L3.9162 10.0984C3.93701 10.14 3.93701 10.1609 3.93701 10.2025L4.37402 11.6384C4.95669 13.5737 5.53937 15.4882 6.12205 17.4235C6.18448 17.6524 6.33015 17.6524 6.45501 17.6524C7.99494 17.6108 9.55568 17.6108 11.0956 17.6108Z"
                                        fill="white" />
                                </svg>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Membership')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/plan*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/plan/add-new')?'active':''}}"
                                    title="{{\App\CPU\translate('add_new')}}">
                                    <a class="nav-link " href="{{route('admin.plan.add-new')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M11.9985 7.88672C12.3838 7.88672 12.6961 8.19908 12.6961 8.58439V15.3996C12.6961 15.7849 12.3838 16.0973 11.9985 16.0973C11.6131 16.0973 11.3008 15.7849 11.3008 15.3996V8.58439C11.3008 8.19908 11.6131 7.88672 11.9985 7.88672Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.89062 11.9906C7.89062 11.6053 8.20298 11.293 8.5883 11.293H15.41C15.7953 11.293 16.1077 11.6053 16.1077 11.9906C16.1077 12.376 15.7953 12.6883 15.41 12.6883H8.5883C8.20298 12.6883 7.89062 12.376 7.89062 11.9906Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.51958 3.67792C4.5211 2.60357 5.94906 2 7.6412 2H16.3588C18.0549 2 19.4833 2.60329 20.4841 3.67825C21.4796 4.74758 22 6.22683 22 7.89317V16.1068C22 17.7732 21.4796 19.2524 20.4841 20.3217C19.4833 21.3967 18.0549 22 16.3588 22H7.6412C5.94508 22 4.51675 21.3967 3.51595 20.3217C2.5204 19.2524 2 17.7732 2 16.1068V7.89317C2 6.22597 2.52312 4.74684 3.51958 3.67792ZM4.54022 4.62938C3.82461 5.39703 3.39535 6.51565 3.39535 7.89317V16.1068C3.39535 17.4852 3.8229 18.6037 4.53721 19.3709C5.24627 20.1326 6.2897 20.6047 7.6412 20.6047H16.3588C17.7103 20.6047 18.7537 20.1326 19.4628 19.3709C20.1771 18.6037 20.6047 17.4852 20.6047 16.1068V7.89317C20.6047 6.51479 20.1771 5.39629 19.4628 4.62905C18.7537 3.86745 17.7103 3.39535 16.3588 3.39535H7.6412C6.29457 3.39535 5.25077 3.86717 4.54022 4.62938Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('add_new')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/plan/list')?'active':''}}"
                                    title="{{\App\CPU\translate('List')}}">
                                    <a class="nav-link " href="{{route('admin.plan.list')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path d="M10.332 16.5938H4.03125" stroke="white" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M13.1406 6.90039H19.4413" stroke="white" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.72629 6.84625C8.72629 5.5506 7.66813 4.5 6.36314 4.5C5.05816 4.5 4 5.5506 4 6.84625C4 8.14191 5.05816 9.19251 6.36314 9.19251C7.66813 9.19251 8.72629 8.14191 8.72629 6.84625Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M19.9997 16.5533C19.9997 15.2576 18.9424 14.207 17.6374 14.207C16.3316 14.207 15.2734 15.2576 15.2734 16.5533C15.2734 17.8489 16.3316 18.8995 17.6374 18.8995C18.9424 18.8995 19.9997 17.8489 19.9997 16.5533Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('List')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Plan End  -->

                        <!--Reports & Analytics section-->
                        @if(\App\CPU\Helpers::module_permission_check('report'))
                            <li
                                class=" nav-item {{(Request::is('admin/report/earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/earning') || Request::is('admin/transaction/list') || Request::is('admin/refund-section/refund-list') || Request::is('admin/stock/product-in-wishlist') || Request::is('admin/reviews*') || Request::is('admin/stock/product-stock')) ? 'scroll-here':''}}">
                                <small class="nav-subtitle" title="">
                                    {{\App\CPU\translate('Reports')}} & {{\App\CPU\translate('Analysis')}}
                                </small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li
                                class=" navbar-vertical-aside-has-menu {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/earning') || Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list')) ?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                    title=" {{\App\CPU\translate('Revenue_&_Account_Management')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none">
                                        <path
                                            d="M19.2817 7.61779C19.1884 7.33791 18.9785 7.15134 18.6986 7.05805C18.582 7.03472 18.4654 6.98807 18.3254 6.96475H18.3021C18.1622 6.9181 18.0456 6.89478 17.9056 6.84813L17.8823 6.82482C17.789 6.68488 17.6957 6.54494 17.6024 6.40501L17.4625 6.17178C17.2992 5.91522 17.0194 5.75195 16.7395 5.75195C16.4596 5.75195 16.1797 5.91522 16.0165 6.17178L15.8532 6.40501C15.7599 6.54494 15.6666 6.68488 15.5733 6.82482L15.55 6.84813C15.3867 6.89478 15.2002 6.94143 15.0602 6.98808L14.8037 7.05805C14.5005 7.15134 14.2672 7.33791 14.174 7.61779C14.0807 7.89766 14.1506 8.17754 14.3372 8.43409L14.3839 8.50407C14.5238 8.66733 14.6637 8.8539 14.8037 9.01716C14.827 9.04048 14.827 9.06381 14.827 9.06381C14.827 9.1571 14.8037 9.27371 14.8037 9.36701V9.60023C14.8037 9.69353 14.8037 9.78682 14.7804 9.88011C14.7804 10.1367 14.8503 10.3466 15.0136 10.5098C15.1768 10.6731 15.3634 10.7431 15.5967 10.7431C15.7133 10.7431 15.8066 10.7197 15.9232 10.6731C16.0398 10.6265 16.1331 10.6031 16.2497 10.5565C16.3896 10.5098 16.5296 10.4399 16.6928 10.3932C16.8328 10.4399 16.9727 10.5098 17.136 10.5565C17.2293 10.6031 17.3226 10.6265 17.4392 10.6731C17.5558 10.7197 17.6724 10.7431 17.789 10.7431C18.0222 10.7431 18.2088 10.6498 18.3721 10.5098C18.5353 10.3466 18.6286 10.1133 18.6053 9.8568C18.6053 9.71686 18.6053 9.57692 18.582 9.43699C18.582 9.29705 18.5587 9.18043 18.582 9.04049C18.6753 8.92388 18.7686 8.83059 18.8619 8.69065C18.9318 8.59736 19.0251 8.50406 19.0951 8.41077C19.305 8.17754 19.375 7.89766 19.2817 7.61779ZM17.5791 9.06381C17.5791 9.25039 17.5791 9.4603 17.5791 9.64688C17.3925 9.57692 17.2293 9.50695 17.0427 9.43699C16.9261 9.39034 16.8328 9.36701 16.7162 9.36701C16.5995 9.36701 16.5062 9.39034 16.3896 9.43699C16.2264 9.50695 16.0398 9.5536 15.8532 9.62357L15.8066 9.64688C15.8066 9.55359 15.8066 9.4603 15.8299 9.36701C15.8765 8.76061 15.8532 8.71397 15.48 8.24751L15.2701 7.96763C15.4101 7.92098 15.55 7.89766 15.6899 7.85102C16.0165 7.78105 16.2497 7.6178 16.413 7.33792C16.5062 7.19799 16.5762 7.05805 16.6695 6.94143C16.7628 7.08137 16.8561 7.2213 16.9494 7.36124C17.0893 7.61779 17.2992 7.75773 17.5791 7.8277C17.7424 7.87434 17.9056 7.92098 18.0922 7.96763C17.9756 8.10757 17.859 8.24752 17.7424 8.38745C17.6491 8.59736 17.5791 8.83058 17.5791 9.06381Z"
                                            fill="white" />
                                        <path
                                            d="M2.02332 13.9619C2.18658 13.6354 2.41981 13.5188 2.79298 13.5188C3.37605 13.5421 3.98245 13.5188 4.56552 13.5188C4.65881 13.5188 4.75211 13.4721 4.82207 13.4255C5.80164 12.6325 6.94446 12.306 8.2039 12.3293C8.90359 12.3293 9.55663 12.5392 10.2097 12.7491C11.6324 13.2389 13.0551 13.7287 14.5011 14.1485C15.504 14.4517 16.1803 15.2913 15.8771 16.5041C16.1337 16.3642 16.3902 16.2709 16.5768 16.1309C17.4165 15.5245 18.2561 14.9181 19.0724 14.2884C19.6321 13.8686 20.2385 13.7287 20.8916 14.0085C22.081 14.475 22.4076 16.1309 21.4047 16.9239C20.1919 17.9035 18.9791 18.9063 17.5564 19.6294C15.2707 20.8188 12.8452 21.1454 10.303 20.6089C8.74033 20.2824 7.17769 19.9092 5.63838 19.5594C5.61505 19.5594 5.56841 19.5361 5.52176 19.5361C5.52176 19.606 5.52176 19.676 5.52176 19.746C5.56841 20.2824 5.28853 20.5856 4.70546 20.5623C4.07574 20.539 3.4227 20.539 2.79298 20.5623C2.41981 20.5623 2.16326 20.469 2 20.1191C2.02332 18.0667 2.02332 16.0143 2.02332 13.9619ZM5.52176 18.3233C5.59173 18.3466 5.63838 18.3699 5.70835 18.3699C7.17769 18.6964 8.67035 19.023 10.1397 19.3728C11.4924 19.6993 12.8452 19.7926 14.2212 19.5594C15.8072 19.2795 17.2299 18.6498 18.4893 17.6936C19.1657 17.1805 19.8654 16.6907 20.5417 16.1776C20.775 15.991 20.8682 15.7578 20.7983 15.5245C20.6817 15.1047 20.2152 14.9648 19.842 15.2447C19.0491 15.8277 18.2328 16.4341 17.4398 17.0172C16.4602 17.7402 15.364 18.0201 14.1746 17.7635C13.5448 17.6236 12.9151 17.3904 12.2854 17.1805C11.5391 16.9472 10.7927 16.6907 10.0464 16.4341C9.76653 16.3408 9.6266 16.1076 9.6266 15.8277C9.64992 15.5712 9.81318 15.3613 10.0697 15.3146C10.1863 15.2913 10.3496 15.3146 10.4662 15.3613C11.609 15.7344 12.7285 16.1076 13.8714 16.4808C13.988 16.5274 14.1279 16.5507 14.2445 16.5274C14.5244 16.5041 14.711 16.2709 14.7343 15.991C14.7576 15.6878 14.5944 15.4779 14.2679 15.3613C12.7519 14.8482 11.2126 14.3584 9.69656 13.8453C9.18346 13.682 8.64704 13.5188 8.11061 13.5188C7.20101 13.4954 6.36139 13.7053 5.61505 14.2651C5.52176 14.3351 5.47512 14.405 5.47512 14.545C5.47512 15.7578 5.47512 16.9706 5.47512 18.1833C5.52176 18.2067 5.52176 18.2766 5.52176 18.3233ZM4.35562 19.3728C4.35562 17.8102 4.35562 16.2709 4.35562 14.7082C3.95913 14.7082 3.58596 14.7082 3.21279 14.7082C3.21279 16.2709 3.21279 17.8102 3.21279 19.3728C3.58596 19.3728 3.95913 19.3728 4.35562 19.3728Z"
                                            fill="white" />
                                        <path
                                            d="M11.4688 8.27099C11.4688 5.35562 13.8477 3 16.7397 3C19.6318 3 21.9874 5.35562 21.9874 8.27099C21.9874 11.1864 19.6084 13.542 16.7164 13.542C13.8244 13.5186 11.4688 11.163 11.4688 8.27099ZM12.6349 8.24766C12.6349 10.51 14.4541 12.3525 16.7164 12.3525C18.9787 12.3525 20.8212 10.5333 20.8212 8.27099C20.8212 6.00867 19.0021 4.16615 16.7397 4.16615C14.4774 4.16615 12.6349 6.00866 12.6349 8.24766Z"
                                            fill="white" />
                                    </svg>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('Revenue_&_Account_Management')}}
                                    </span>
                                </a>
                                <ul
                                    class="js-navbar-vertical-aside-submenu nav nav-sub {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/seller-report') || Request::is('admin/report/earning') || Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list')) ?'block':'none'}}">
                                    <li
                                        class="d-none navbar-vertical-aside-has-menu {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning'))?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                            href="{{route('admin.report.admin-earning')}}"
                                            title="{{\App\CPU\translate('Earning')}} {{\App\CPU\translate('reports')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M21.9113 11.812C21.6854 11.0663 21.03 10.5918 20.2617 10.5918C19.7872 10.5918 19.3353 10.7726 18.9737 11.1341C18.8155 11.2923 18.6574 11.4505 18.4766 11.6087C18.3184 11.7668 18.1602 11.925 18.0021 12.0832L16.9852 13.1001C16.8496 13.2356 16.6914 13.3938 16.5559 13.5294L16.5107 13.5746C16.4655 13.6198 16.4203 13.665 16.3751 13.7328L16.1943 13.9136C16.1039 14.0039 16.0135 14.0943 15.9231 14.2073C15.8327 14.2977 15.7424 14.4107 15.652 14.5011C15.6068 14.3881 15.539 14.2977 15.4486 14.2073L15.3582 14.0943C14.9967 13.7102 14.5221 13.5294 13.8216 13.5294C13.7764 13.5294 13.7312 13.5294 13.686 13.5294C13.4149 13.5294 13.1663 13.5294 12.8951 13.5294C12.6918 13.5294 12.4884 13.5294 12.285 13.5294C12.1043 13.5294 11.9461 13.5294 11.7653 13.5294H11.6523C11.6071 13.5294 11.5619 13.5294 11.5167 13.5294H11.4263C10.7484 13.5068 10.5225 13.3486 10.4999 13.326L10.4095 13.2808C10.3417 13.2356 10.2513 13.213 10.1835 13.1678C9.55079 12.8063 8.89548 12.6029 8.21758 12.5803C8.17238 12.5803 8.12719 12.5803 8.08199 12.5803C6.88435 12.5803 5.84489 13.0097 4.98621 13.8684C4.35349 14.5011 3.72078 15.1564 3.08806 15.7891L2.25197 16.6478C2.0712 16.8286 1.98082 17.0093 2.00341 17.1675C2.00341 17.3257 2.11639 17.4839 2.29717 17.6646L3.22365 18.4555C3.72078 18.8849 4.19532 19.2916 4.69245 19.721C5.0766 20.0599 5.48334 20.3989 5.86749 20.7152L6.43241 21.1898H6.77136L6.92955 21.0542C7.01993 20.9864 7.08773 20.9186 7.17811 20.8508C7.3137 20.7152 7.47187 20.5571 7.60745 20.4215C7.85602 20.1729 8.08199 19.9469 8.33056 19.6984C8.37575 19.6532 8.48874 19.608 8.55653 19.608C9.57339 19.608 10.5903 19.608 11.6071 19.608C12.2624 19.608 12.9177 19.608 13.5956 19.608H13.6182C14.7707 19.608 15.765 19.1786 16.601 18.3651C17.2338 17.7324 17.8891 17.0771 18.5218 16.4444L20.1488 14.8174C20.5555 14.4107 20.9849 14.0039 21.3916 13.5746C21.9791 13.1001 22.1147 12.4899 21.9113 11.812ZM13.7764 18.5233H11.0422C10.1835 18.5233 9.32483 18.5233 8.44354 18.5233C8.17238 18.5233 7.969 18.6137 7.78823 18.7945C7.51706 19.0882 7.22331 19.3594 6.95214 19.6306L6.68098 19.9017L3.44962 17.1223C3.54 17.0545 3.6078 17.0093 3.67559 16.9415C4.42129 16.1958 5.14438 15.4501 5.86749 14.7044C6.4776 14.0943 7.2911 13.7328 8.14978 13.7328C8.69211 13.7328 9.21184 13.8684 9.66378 14.1621C10.0705 14.4107 10.5225 14.5689 10.997 14.6366C11.2456 14.6592 11.4941 14.6818 11.7201 14.6818C11.9235 14.6818 12.1494 14.6818 12.3528 14.6818H13.2793C13.5279 14.6818 13.7538 14.6818 14.0024 14.6818C14.2736 14.6818 14.4995 14.7948 14.6351 14.9756C14.7481 15.1564 14.7933 15.3598 14.7255 15.5631C14.6351 15.8343 14.4091 16.0151 14.1606 16.0377C14.025 16.0377 13.912 16.0603 13.7764 16.0603H10.4321C10.3417 16.0603 10.2513 16.0603 10.1609 16.0829C9.91234 16.128 9.70897 16.354 9.70897 16.6252C9.70897 16.8963 9.91234 17.1223 10.1609 17.1675C10.2513 17.1901 10.3417 17.1901 10.4095 17.1901H12.511H13.912C14.8385 17.1901 15.4034 16.806 15.7424 15.9473C15.765 15.9021 15.7876 15.8569 15.8327 15.8343C17.1208 14.5463 18.4088 13.2582 19.6968 11.9702C19.855 11.8346 20.0132 11.7442 20.194 11.7442C20.2843 11.7442 20.3973 11.7668 20.4877 11.812C20.7589 11.9476 20.8719 12.1962 20.8267 12.4673C20.8041 12.6029 20.7137 12.7611 20.6233 12.8515C18.8381 14.6367 17.2338 16.241 15.7198 17.755C15.2452 18.2522 14.5673 18.5233 13.7764 18.5233Z"
                                                    fill="white" />
                                                <path
                                                    d="M13.3258 4.58203C12.196 4.58203 11.2695 5.53111 11.2695 6.66095C11.2695 7.7908 12.196 8.71728 13.3258 8.71728C13.8682 8.71728 14.3879 8.49131 14.7947 8.10716C15.1788 7.72302 15.4048 7.20329 15.4048 6.63836C15.4048 5.50851 14.4783 4.58203 13.3258 4.58203ZM12.196 6.63836C12.196 6.02824 12.7157 5.50851 13.3258 5.50851C13.6196 5.50851 13.9134 5.6215 14.1167 5.84747C14.3427 6.07344 14.4557 6.3446 14.4557 6.66095C14.4557 7.29367 13.936 7.7908 13.3033 7.7908C12.6931 7.7682 12.196 7.24848 12.196 6.63836Z"
                                                    fill="white" />
                                                <path
                                                    d="M7.51805 10.2536H10.6364H10.772C12.6928 10.2536 14.6361 10.2536 16.5568 10.2536H19.1329C19.5848 10.2536 19.743 10.0954 19.743 9.64351V3.58753C19.743 3.54234 19.743 3.51974 19.743 3.47454C19.7204 3.18078 19.5396 3 19.2459 3C18.5454 3 17.8223 3 17.1218 3H7.51805C7.1113 3 6.95312 3.15818 6.95312 3.56492C6.95312 4.40101 6.95312 5.23711 6.95312 6.07319V7.70018V9.64351C6.95312 10.0954 7.1113 10.2536 7.51805 10.2536ZM18.7939 8.71704V9.32715H18.1612C18.2742 9.07858 18.5228 8.80743 18.7939 8.71704ZM18.1838 3.94907H18.7939V4.5592C18.5228 4.46881 18.2742 4.22024 18.1838 3.94907ZM18.7939 5.55347L18.8165 5.57606C18.8165 5.9828 18.8165 6.36695 18.8165 6.77369C18.8165 7.09005 18.8165 7.40641 18.8165 7.72277V7.74536H18.7939C18.4098 7.83575 18.0934 8.01653 17.7997 8.28769C17.5059 8.55886 17.3025 8.92041 17.2121 9.34975H11.7437H10.659H9.50658C9.371 8.80743 9.09984 8.37808 8.6705 8.10692C8.51232 7.99394 8.35413 7.92614 8.17336 7.85835C8.08297 7.81315 8.01518 7.79055 7.92479 7.74536L7.90219 7.72277C7.90219 7.54199 7.90219 7.36121 7.90219 7.18044V6.27656C7.90219 6.02799 7.90219 5.77943 7.90219 5.57606C7.90219 5.57606 7.92479 5.55347 7.94739 5.55347C8.01518 5.50827 8.10557 5.48568 8.19596 5.44048C8.35414 5.37269 8.53491 5.30489 8.69309 5.19191C9.12243 4.89815 9.39359 4.4914 9.52918 3.94907H17.2347C17.4155 4.78516 17.9578 5.3275 18.7939 5.55347ZM7.8796 4.5592V3.94907H8.48972C8.42192 4.19764 8.17336 4.44621 7.8796 4.5592ZM8.51231 9.32715H7.90219V8.71704C8.17336 8.83003 8.42193 9.07858 8.51231 9.32715Z"
                                                    fill="white" />
                                            </svg>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{\App\CPU\translate('Earning')}} {{\App\CPU\translate('reports')}}
                                            </span>
                                        </a>
                                    </li>
                                    <li
                                        class="d-none nav-item {{Request::is('admin/report/inhoue-product-sale')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.report.inhoue-product-sale')}}"
                                            title="{{\App\CPU\translate('inhouse')}} {{\App\CPU\translate('sales')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M18.3198 13.1111C17.8897 12.4421 17.4595 11.7491 17.0294 11.08C16.8383 10.7694 16.6471 10.4827 16.4559 10.172L15.4285 8.54718C15.8586 8.23655 16.0975 7.78256 16.0736 7.25687C16.0497 6.75508 15.763 6.30107 15.2851 6.03822C15.2851 6.01433 15.309 5.96655 15.309 5.94265L15.5002 5.2497C15.7152 4.53285 15.9064 3.816 16.1214 3.09916C16.2409 2.69295 16.0258 2.50179 15.9064 2.4301C15.7152 2.33453 15.5241 2.23894 15.3568 2.14336C15.2851 2.09557 15.1895 2.07168 15.1178 2.02389L15.0939 2H14.6399H14.616C14.2576 2.07168 13.947 2.26284 13.6125 2.54958C13.5169 2.64516 13.3735 2.69294 13.254 2.69294C13.1107 2.69294 12.9912 2.64516 12.8717 2.54958C12.585 2.28674 12.2505 2.11947 11.892 2.02389H11.8681H11.3663H11.3425C10.984 2.11947 10.6734 2.28674 10.3628 2.54958C10.2672 2.64516 10.1238 2.69294 10.0043 2.69294C9.86098 2.69294 9.7415 2.64516 9.64592 2.54958C9.33529 2.26284 9.00076 2.09558 8.64234 2H8.61844H8.14054L8.11665 2.02389C8.04497 2.07168 7.94939 2.09557 7.8777 2.14336C7.68655 2.23894 7.49538 2.31063 7.32812 2.4301C7.08917 2.57347 7.01749 2.78852 7.11307 3.09916C7.18475 3.33811 7.25643 3.57706 7.32812 3.83991L7.94939 6.03822C7.47149 6.30107 7.18475 6.75508 7.16086 7.25687C7.13696 7.75866 7.37591 8.23655 7.80602 8.54718C7.78212 8.57108 7.78212 8.59498 7.75823 8.61888L7.18475 9.52688C6.44401 10.6738 5.70327 11.8447 4.96253 12.9916C4.1501 14.282 3.83947 15.6679 4.07842 17.1254C4.50852 19.8734 6.92191 21.9761 9.66982 22C10.2911 22 10.9123 22 11.5336 22C12.2027 22 12.8478 22 13.5169 21.9761C14.5205 21.9522 15.4763 21.6894 16.3365 21.1637C17.6985 20.3513 18.6543 19.037 19.0366 17.5078C19.4189 16.0502 19.1561 14.4731 18.3198 13.1111ZM8.66623 6.94624H9.83708H11.6053C11.6053 6.94624 14.1382 6.94624 14.5683 6.94624C14.7116 6.94624 14.855 6.99402 14.9267 7.0896C14.9984 7.16129 15.0462 7.28076 15.0223 7.40023C14.9984 7.61529 14.8311 7.78256 14.5922 7.78256C14.5205 7.78256 14.3771 7.78256 14.3771 7.78256H9.47866C9.47866 7.78256 8.8096 7.78256 8.64234 7.78256C8.47507 7.78256 8.3556 7.73476 8.26002 7.63918C8.18833 7.5675 8.14054 7.47191 8.16444 7.35244C8.18833 7.11349 8.37949 6.94624 8.66623 6.94624ZM14.2576 5.94265H8.97686L8.18833 3.14695C8.26002 3.12306 8.3317 3.09916 8.40339 3.09916C8.54676 3.09916 8.71402 3.17085 8.88128 3.31422C9.21581 3.62485 9.57424 3.76821 9.98045 3.76821C10.1716 3.76821 10.3628 3.72042 10.5778 3.64874C10.8168 3.57705 11.0079 3.40979 11.1513 3.29031C11.3186 3.14694 11.4858 3.07527 11.6292 3.07527C11.7726 3.07527 11.9398 3.14695 12.0832 3.26642C12.4177 3.57706 12.8239 3.74432 13.2302 3.74432C13.6364 3.74432 14.0426 3.57706 14.3771 3.26642C14.5205 3.14695 14.6638 3.07527 14.8072 3.07527C14.8789 3.07527 14.9506 3.09917 15.0223 3.12306L14.2576 5.94265ZM6.99359 11.773C7.61486 10.8172 8.21223 9.83751 8.8335 8.88172C8.88129 8.81003 8.90518 8.81004 8.95297 8.81004C9.09634 8.81004 9.21581 8.81004 9.35918 8.81004H9.38308H14.2815C14.3293 8.81004 14.3771 8.83393 14.401 8.85783C15.4763 10.5544 16.456 12.0836 17.4356 13.6129C18.1764 14.7837 18.3914 16.1219 18.033 17.4361C17.6746 18.7503 16.7905 19.8495 15.6196 20.4707C14.9506 20.8291 14.2576 20.9964 13.5647 21.0203C12.8478 21.0203 12.131 21.0442 11.4141 21.0442C10.8407 21.0442 10.2672 21.0442 9.69371 21.0203C7.37591 20.9725 5.34485 19.1087 5.082 16.7909C4.96253 15.644 5.20148 14.5687 5.84664 13.589L6.99359 11.773Z"
                                                    fill="white" />
                                                <path
                                                    d="M9.93223 17.8908C10.219 18.0342 10.5296 18.1537 10.8402 18.2731C10.9358 18.297 11.0075 18.3209 11.1031 18.3209C11.1031 18.4165 11.1031 18.4882 11.1031 18.5838C11.1031 18.8705 11.342 19.0856 11.6049 19.0856C11.8916 19.0856 12.1067 18.8705 12.1067 18.5599C12.1067 18.4643 12.1067 18.3687 12.1067 18.2731V18.2492C12.943 17.9386 13.4687 17.3173 13.5404 16.5527C13.612 15.573 13.158 14.9279 12.1306 14.5933V13.1118C12.2261 13.1596 12.2978 13.2074 12.3934 13.2552C12.489 13.303 12.5846 13.3269 12.6801 13.3269C12.7996 13.3269 12.9191 13.2791 13.0386 13.1835C13.158 13.0641 13.2058 12.9207 13.1819 12.7534C13.158 12.6101 13.0625 12.4667 12.943 12.395C12.7518 12.2755 12.5607 12.2038 12.3456 12.1083C12.2739 12.0844 12.1306 12.0127 12.1306 12.0127C12.1306 11.941 12.1306 11.8454 12.1306 11.7498C12.1067 11.4631 11.9155 11.248 11.6049 11.248C11.342 11.248 11.127 11.4631 11.1031 11.7498C11.1031 11.8454 11.1031 11.9171 11.1031 12.0127V12.0366C11.0792 12.0366 11.0553 12.0605 11.0314 12.0605C10.9119 12.1083 10.7925 12.1322 10.6969 12.2038C10.1473 12.5145 9.86055 12.9924 9.83666 13.6375C9.81276 14.2588 10.0756 14.7128 10.6252 15.0234C10.6969 15.0473 10.7447 15.0951 10.8163 15.119C10.888 15.1668 10.9836 15.2146 11.0553 15.2624C11.0792 15.2624 11.0792 15.2863 11.1031 15.2863C11.1031 15.8597 11.1031 16.4332 11.1031 17.0067V17.2934C10.8641 17.2695 10.673 17.174 10.4579 17.0306C10.3623 16.9828 10.2668 16.935 10.1473 16.935C9.98003 16.935 9.81276 17.0306 9.71718 17.174C9.6455 17.2934 9.62161 17.4368 9.6694 17.5802C9.71719 17.7236 9.81276 17.8191 9.93223 17.8908ZM11.1031 13.1596V14.1154C10.888 13.996 10.8402 13.8526 10.8402 13.6614C10.8641 13.4464 10.9597 13.2791 11.1031 13.1596ZM12.1306 17.1262V15.6925C12.3934 15.812 12.5368 16.027 12.5368 16.3137C12.5607 16.6483 12.3934 16.9589 12.1306 17.1262Z"
                                                    fill="white" />
                                            </svg>
                                            <span class="text-truncate">
                                                {{\App\CPU\translate('inhouse')}} {{\App\CPU\translate('sales')}}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="d-none nav-item {{ Request::is('admin/currency/view') ?'active':'' }}">
                                        <a class="nav-link" href="{{route('admin.currency.view')}}"
                                            title="{{\App\CPU\translate('Currency_Management')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M11.8619 22C11.3838 22 10.8791 21.8141 10.4011 21.4157L8.91368 20.2204C8.64807 20.008 8.46215 19.9283 8.27623 19.9283C8.0903 19.9283 7.90438 20.0345 7.63878 20.247C7.18725 20.6188 6.78884 20.8048 6.31075 20.8579C6.25763 20.8579 6.17795 20.8579 6.12483 20.8579C4.92961 20.8579 4.02656 19.9283 4 18.7596C4 17.0332 4 15.3068 4 13.5803V4.25763C4 2.87649 4.87649 2 6.25763 2H10.5073C11.676 2 12.8181 2 13.9867 2C14.6773 2 15.2351 2.23905 15.7131 2.71713C16.8818 3.91235 17.9708 4.97477 19.0066 5.98406C19.5113 6.46215 19.7238 7.04649 19.7238 7.73706C19.7238 10.0744 19.7238 12.4382 19.7238 14.7756C19.7238 16.077 19.7238 17.3785 19.7238 18.6799C19.7238 19.8486 18.9801 20.7251 17.8114 20.8579C17.7317 20.8579 17.6521 20.8845 17.5724 20.8845C17.1474 20.8845 16.749 20.7517 16.3772 20.4861C16.2709 20.4329 16.1912 20.3533 16.1115 20.2736L16.0053 20.1939C15.7928 20.0345 15.6335 19.9548 15.4475 19.9548C15.2616 19.9548 15.0757 20.0345 14.8898 20.1939C14.3586 20.6188 13.8539 21.0173 13.3227 21.4422C12.8446 21.7875 12.3665 22 11.8619 22ZM8.24967 18.5737C8.72775 18.5737 9.20584 18.7596 9.60425 19.0784C9.97609 19.3705 10.3214 19.6361 10.6667 19.9283L11.2244 20.3798C11.4635 20.5657 11.6494 20.6454 11.8353 20.6454C12.0212 20.6454 12.2072 20.5657 12.4462 20.3798L13.1899 19.7689C13.4821 19.5299 13.7742 19.2908 14.0664 19.0784C14.4648 18.7596 14.9429 18.5737 15.421 18.5737C15.8991 18.5737 16.3506 18.7331 16.7756 19.0518C16.8021 19.0783 16.8552 19.1049 16.8818 19.158C16.9615 19.2377 17.0412 19.3174 17.1474 19.344C17.3333 19.4236 17.5192 19.4768 17.652 19.4768C17.6786 19.4768 17.7052 19.4768 17.7317 19.4768C18.1301 19.4236 18.3692 19.1315 18.3692 18.6268C18.3692 15.2802 18.3692 11.9336 18.3692 8.56042C18.3692 8.53386 18.3692 8.48074 18.3692 8.42762V8.29482H16.51C16.1647 8.29482 15.7928 8.29482 15.4475 8.29482C14.8101 8.29482 14.2789 8.05578 13.8539 7.55113C13.5086 7.15273 13.3493 6.67464 13.3493 6.14343V3.24834H6.25763C5.62018 3.24834 5.32802 3.51395 5.32802 4.17796V18.5471C5.32802 18.6003 5.32802 18.6534 5.32802 18.7065C5.35458 19.1315 5.69987 19.4236 6.09827 19.4236C6.25764 19.4236 6.39044 19.3705 6.52324 19.2908C6.60292 19.2377 6.70916 19.158 6.78884 19.0784L6.89508 18.9987C7.32005 18.7596 7.79814 18.5737 8.24967 18.5737ZM14.7038 4.47013C14.7038 5.08102 14.7038 5.71847 14.7038 6.32936C14.7038 6.70121 15.0226 6.99337 15.3944 6.99337C15.7663 6.99337 16.1381 6.99337 16.51 6.99337H18.077L14.7038 3.56707V4.47013Z"
                                                    fill="white" />
                                                <path
                                                    d="M15.3946 12.6775C14.6244 12.6775 13.8276 12.6775 13.0573 12.6775H9.17948C8.91388 12.6775 8.62171 12.6775 8.35611 12.6775C8.01082 12.6775 7.79834 12.5447 7.66554 12.2791C7.53274 12.0135 7.61242 11.7479 7.85146 11.5088C8.17018 11.1901 8.86076 10.4995 8.86076 10.4995C9.20604 10.1542 9.55133 9.80894 9.89661 9.46366C10.056 9.3043 10.2419 9.22461 10.4278 9.22461C10.5872 9.22461 10.7465 9.30429 10.8793 9.41054C11.0121 9.54334 11.0653 9.7027 11.0653 9.86207C11.0653 10.048 10.9856 10.2339 10.8262 10.3933C10.6403 10.5792 10.4544 10.7651 10.2419 10.951L9.94973 11.2432L10.0029 11.3494H13.7213C14.2791 11.3494 14.8368 11.3494 15.3681 11.3494C15.6337 11.3494 15.8461 11.4822 15.9789 11.6682C16.0852 11.8541 16.1117 12.0666 16.0321 12.2791C15.9524 12.5447 15.7133 12.6775 15.3946 12.6775Z"
                                                    fill="white" />
                                                <path
                                                    d="M13.3238 17.6708C13.2175 17.6708 13.1113 17.6443 13.0316 17.5911C12.766 17.4583 12.6332 17.1662 12.6863 16.874C12.7129 16.7412 12.7926 16.6084 12.8988 16.4756C13.0847 16.2631 13.2972 16.0772 13.4831 15.8913L13.855 15.5194H8.38353C7.90545 15.5194 7.61328 15.2538 7.61328 14.8554C7.61328 14.6695 7.6664 14.5101 7.79921 14.4039C7.93201 14.2711 8.14449 14.1914 8.38353 14.1914H11.8895C13.0582 14.1914 14.2268 14.1914 15.3955 14.1914C15.7673 14.1914 15.9798 14.3242 16.1126 14.5898C16.2454 14.882 16.1657 15.121 15.9001 15.3866C15.5548 15.7319 15.183 16.1038 14.8377 16.449L14.7315 16.5553C14.4393 16.8475 14.1471 17.1396 13.855 17.4318C13.6691 17.6177 13.5097 17.6708 13.3238 17.6708Z"
                                                    fill="white" />
                                            </svg>
                                            <span class="text-truncate">
                                                {{\App\CPU\translate('Currency_Management')}}
                                            </span>
                                        </a>
                                    </li>

                                    <li
                                        class="navbar-vertical-aside-has-menu {{(Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list'))?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                            href="{{route('admin.transaction.order-transaction-list')}}"
                                            title="{{\App\CPU\translate('Transaction_Report')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M5.86446 22C5.60987 21.9434 5.35527 21.8868 5.10068 21.802C3.60138 21.2362 2.75273 19.7369 3.0639 18.1527C3.43165 16.2857 3.74282 14.4187 4.13886 12.5516C4.61977 10.2885 5.97762 8.73267 8.09926 7.82743C8.15583 7.79914 8.21241 7.77085 8.26899 7.77085C8.26899 7.77085 8.26899 7.77086 8.26899 7.74257C7.84466 6.58274 7.98611 5.96039 8.91963 5.1966C8.89134 5.14002 8.86305 5.08345 8.83476 5.02688C8.46701 4.37624 8.12755 3.7256 7.7598 3.04668C7.44862 2.48091 7.73151 2 8.38214 2C9.7117 2 11.0413 2 12.3708 2C13.0215 2 13.3043 2.48091 12.9932 3.04668C12.5971 3.75389 12.2011 4.4611 11.8333 5.1966C12.7103 5.81895 12.9366 6.63932 12.5406 7.65771C12.5688 7.686 12.5971 7.686 12.6537 7.71429C12.9366 7.88402 13.0498 8.22347 12.9366 8.53465C12.8234 8.84582 12.484 8.95897 12.1728 8.90239C11.6636 8.81753 11.1544 8.67609 10.6452 8.67609C8.15583 8.6478 5.94933 10.4017 5.46843 12.8628C5.10068 14.7298 4.73292 16.5969 4.39346 18.4639C4.16715 19.5955 5.07238 20.6987 6.2605 20.727C7.10916 20.727 7.95781 20.727 8.80647 20.727C9.11764 20.727 9.37225 20.925 9.42882 21.1796C9.51369 21.4625 9.40053 21.7454 9.17422 21.8868C9.11765 21.9151 9.03278 21.9717 8.9762 22C7.87295 22 6.88285 22 5.86446 22ZM10.3623 7.34652C10.5321 7.34652 10.7018 7.34652 10.8715 7.34652C11.1544 7.34652 11.3807 7.12022 11.409 6.83733C11.4373 6.55445 11.2393 6.27157 10.9564 6.24328C10.5604 6.21499 10.1643 6.21499 9.76828 6.24328C9.4854 6.27157 9.28738 6.55445 9.31567 6.83733C9.34395 7.12022 9.57026 7.31823 9.85315 7.34652C10.0229 7.34652 10.1926 7.34652 10.3623 7.34652ZM9.34396 3.30127C9.62684 3.83875 9.90973 4.34794 10.1926 4.85714C10.2775 4.99858 10.4189 4.97029 10.5038 4.85714C10.6735 4.57425 10.8432 4.26308 10.9847 3.9802C11.0978 3.75389 11.211 3.55587 11.3524 3.32956C10.6735 3.30128 10.0512 3.30127 9.34396 3.30127Z"
                                                    fill="white" />
                                                <path
                                                    d="M13.6998 22.0002C13.3038 21.9154 12.8794 21.8871 12.5117 21.7456C10.1071 20.9818 8.57957 19.3694 8.12695 16.88C7.53289 13.7117 9.45651 10.7414 12.5683 9.86444C16.0195 8.87434 19.697 11.2223 20.2627 14.7866C20.8002 18.0964 18.7352 21.1233 15.4537 21.8588C15.1708 21.9154 14.9162 21.9436 14.6333 22.0002C14.3221 22.0002 14.011 22.0002 13.6998 22.0002ZM19.0746 15.8333C19.0746 13.1459 16.8964 10.9677 14.209 10.9394C11.5216 10.9394 9.31507 13.1176 9.31507 15.8333C9.31507 18.5207 11.4933 20.6989 14.1807 20.6989C16.8681 20.6989 19.0463 18.5207 19.0746 15.8333Z"
                                                    fill="white" />
                                                <path
                                                    d="M12.6268 18.0971C12.3439 18.0405 12.1459 17.9274 12.0327 17.7011C11.9196 17.4465 11.9479 17.2202 12.1176 17.0221C12.1459 16.9939 12.1742 16.9656 12.2025 16.909C13.2209 15.8906 14.2675 14.8722 15.2859 13.8255C15.4839 13.6275 15.682 13.5427 15.9648 13.5992C16.4175 13.7124 16.5872 14.2499 16.3043 14.6176C16.3043 14.6459 16.276 14.6459 16.276 14.6742C15.201 15.7492 14.1261 16.8241 13.0511 17.8708C12.938 17.984 12.7682 18.0122 12.6268 18.0971Z"
                                                    fill="white" />
                                                <path
                                                    d="M16.3614 17.417C16.3614 17.8979 15.9936 18.2657 15.5127 18.2657C15.0601 18.2657 14.6641 17.8696 14.6641 17.417C14.6641 16.9361 15.0601 16.5684 15.5127 16.5684C15.9936 16.5684 16.3614 16.9361 16.3614 17.417Z"
                                                    fill="white" />
                                                <path
                                                    d="M12.8526 15.0977C12.3717 15.0977 12.0039 14.73 12.0039 14.249C12.0039 13.7681 12.3999 13.4004 12.8526 13.4004C13.3335 13.4004 13.7012 13.7964 13.7012 14.249C13.7295 14.7017 13.3335 15.0977 12.8526 15.0977Z"
                                                    fill="white" />
                                            </svg>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{\App\CPU\translate('Transaction_Report')}}
                                            </span>
                                        </a>
                                    </li>
                                    <li
                                        class="d-none navbar-vertical-aside-has-menu {{(Request::is('admin/taxes/add-new'))?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                            title="{{\App\CPU\translate('tax_setup')}}"
                                            href="{{route('admin.taxes.add-new')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M18.3198 13.1111C17.8897 12.4421 17.4595 11.7491 17.0294 11.08C16.8383 10.7694 16.6471 10.4827 16.4559 10.172L15.4285 8.54718C15.8586 8.23655 16.0975 7.78256 16.0736 7.25687C16.0497 6.75508 15.763 6.30107 15.2851 6.03822C15.2851 6.01433 15.309 5.96655 15.309 5.94265L15.5002 5.2497C15.7152 4.53285 15.9064 3.816 16.1214 3.09916C16.2409 2.69295 16.0258 2.50179 15.9064 2.4301C15.7152 2.33453 15.5241 2.23894 15.3568 2.14336C15.2851 2.09557 15.1895 2.07168 15.1178 2.02389L15.0939 2H14.6399H14.616C14.2576 2.07168 13.947 2.26284 13.6125 2.54958C13.5169 2.64516 13.3735 2.69294 13.254 2.69294C13.1107 2.69294 12.9912 2.64516 12.8717 2.54958C12.585 2.28674 12.2505 2.11947 11.892 2.02389H11.8681H11.3663H11.3425C10.984 2.11947 10.6734 2.28674 10.3628 2.54958C10.2672 2.64516 10.1238 2.69294 10.0043 2.69294C9.86098 2.69294 9.7415 2.64516 9.64592 2.54958C9.33529 2.26284 9.00076 2.09558 8.64234 2H8.61844H8.14054L8.11665 2.02389C8.04497 2.07168 7.94939 2.09557 7.8777 2.14336C7.68655 2.23894 7.49538 2.31063 7.32812 2.4301C7.08917 2.57347 7.01749 2.78852 7.11307 3.09916C7.18475 3.33811 7.25643 3.57706 7.32812 3.83991L7.94939 6.03822C7.47149 6.30107 7.18475 6.75508 7.16086 7.25687C7.13696 7.75866 7.37591 8.23655 7.80602 8.54718C7.78212 8.57108 7.78212 8.59498 7.75823 8.61888L7.18475 9.52688C6.44401 10.6738 5.70327 11.8447 4.96253 12.9916C4.1501 14.282 3.83947 15.6679 4.07842 17.1254C4.50852 19.8734 6.92191 21.9761 9.66982 22C10.2911 22 10.9123 22 11.5336 22C12.2027 22 12.8478 22 13.5169 21.9761C14.5205 21.9522 15.4763 21.6894 16.3365 21.1637C17.6985 20.3513 18.6543 19.037 19.0366 17.5078C19.4189 16.0502 19.1561 14.4731 18.3198 13.1111ZM8.66623 6.94624H9.83708H11.6053C11.6053 6.94624 14.1382 6.94624 14.5683 6.94624C14.7116 6.94624 14.855 6.99402 14.9267 7.0896C14.9984 7.16129 15.0462 7.28076 15.0223 7.40023C14.9984 7.61529 14.8311 7.78256 14.5922 7.78256C14.5205 7.78256 14.3771 7.78256 14.3771 7.78256H9.47866C9.47866 7.78256 8.8096 7.78256 8.64234 7.78256C8.47507 7.78256 8.3556 7.73476 8.26002 7.63918C8.18833 7.5675 8.14054 7.47191 8.16444 7.35244C8.18833 7.11349 8.37949 6.94624 8.66623 6.94624ZM14.2576 5.94265H8.97686L8.18833 3.14695C8.26002 3.12306 8.3317 3.09916 8.40339 3.09916C8.54676 3.09916 8.71402 3.17085 8.88128 3.31422C9.21581 3.62485 9.57424 3.76821 9.98045 3.76821C10.1716 3.76821 10.3628 3.72042 10.5778 3.64874C10.8168 3.57705 11.0079 3.40979 11.1513 3.29031C11.3186 3.14694 11.4858 3.07527 11.6292 3.07527C11.7726 3.07527 11.9398 3.14695 12.0832 3.26642C12.4177 3.57706 12.8239 3.74432 13.2302 3.74432C13.6364 3.74432 14.0426 3.57706 14.3771 3.26642C14.5205 3.14695 14.6638 3.07527 14.8072 3.07527C14.8789 3.07527 14.9506 3.09917 15.0223 3.12306L14.2576 5.94265ZM6.99359 11.773C7.61486 10.8172 8.21223 9.83751 8.8335 8.88172C8.88129 8.81003 8.90518 8.81004 8.95297 8.81004C9.09634 8.81004 9.21581 8.81004 9.35918 8.81004H9.38308H14.2815C14.3293 8.81004 14.3771 8.83393 14.401 8.85783C15.4763 10.5544 16.456 12.0836 17.4356 13.6129C18.1764 14.7837 18.3914 16.1219 18.033 17.4361C17.6746 18.7503 16.7905 19.8495 15.6196 20.4707C14.9506 20.8291 14.2576 20.9964 13.5647 21.0203C12.8478 21.0203 12.131 21.0442 11.4141 21.0442C10.8407 21.0442 10.2672 21.0442 9.69371 21.0203C7.37591 20.9725 5.34485 19.1087 5.082 16.7909C4.96253 15.644 5.20148 14.5687 5.84664 13.589L6.99359 11.773Z"
                                                    fill="white" />
                                                <path
                                                    d="M9.93223 17.8908C10.219 18.0342 10.5296 18.1537 10.8402 18.2731C10.9358 18.297 11.0075 18.3209 11.1031 18.3209C11.1031 18.4165 11.1031 18.4882 11.1031 18.5838C11.1031 18.8705 11.342 19.0856 11.6049 19.0856C11.8916 19.0856 12.1067 18.8705 12.1067 18.5599C12.1067 18.4643 12.1067 18.3687 12.1067 18.2731V18.2492C12.943 17.9386 13.4687 17.3173 13.5404 16.5527C13.612 15.573 13.158 14.9279 12.1306 14.5933V13.1118C12.2261 13.1596 12.2978 13.2074 12.3934 13.2552C12.489 13.303 12.5846 13.3269 12.6801 13.3269C12.7996 13.3269 12.9191 13.2791 13.0386 13.1835C13.158 13.0641 13.2058 12.9207 13.1819 12.7534C13.158 12.6101 13.0625 12.4667 12.943 12.395C12.7518 12.2755 12.5607 12.2038 12.3456 12.1083C12.2739 12.0844 12.1306 12.0127 12.1306 12.0127C12.1306 11.941 12.1306 11.8454 12.1306 11.7498C12.1067 11.4631 11.9155 11.248 11.6049 11.248C11.342 11.248 11.127 11.4631 11.1031 11.7498C11.1031 11.8454 11.1031 11.9171 11.1031 12.0127V12.0366C11.0792 12.0366 11.0553 12.0605 11.0314 12.0605C10.9119 12.1083 10.7925 12.1322 10.6969 12.2038C10.1473 12.5145 9.86055 12.9924 9.83666 13.6375C9.81276 14.2588 10.0756 14.7128 10.6252 15.0234C10.6969 15.0473 10.7447 15.0951 10.8163 15.119C10.888 15.1668 10.9836 15.2146 11.0553 15.2624C11.0792 15.2624 11.0792 15.2863 11.1031 15.2863C11.1031 15.8597 11.1031 16.4332 11.1031 17.0067V17.2934C10.8641 17.2695 10.673 17.174 10.4579 17.0306C10.3623 16.9828 10.2668 16.935 10.1473 16.935C9.98003 16.935 9.81276 17.0306 9.71718 17.174C9.6455 17.2934 9.62161 17.4368 9.6694 17.5802C9.71719 17.7236 9.81276 17.8191 9.93223 17.8908ZM11.1031 13.1596V14.1154C10.888 13.996 10.8402 13.8526 10.8402 13.6614C10.8641 13.4464 10.9597 13.2791 11.1031 13.1596ZM12.1306 17.1262V15.6925C12.3934 15.812 12.5368 16.027 12.5368 16.3137C12.5607 16.6483 12.3934 16.9589 12.1306 17.1262Z"
                                                    fill="white" />
                                            </svg>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{\App\CPU\translate('tax_setup')}}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        @endif
                        <!--Reports & Analytics section End-->


                        @if(auth('admin')->user()->admin_role_id==1)

                        <li class=" navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/web-config') || Request::is('admin/business-settings/web-config/app-settings') || Request::is('admin/product-settings/inhouse-shop') || Request::is('admin/business-settings/seller-settings') || Request::is('admin/customer/customer-settings') || Request::is('admin/refund-section/refund-index') || Request::is('admin/business-settings/shipping-method/setting') || Request::is('admin/business-settings/order-settings/index') || Request::is('admin/product-settings') || Request::is('admin/business-settings/web-config/delivery-restriction') || Request::is('admin/business-settings/cookie-settings') || Request::is('admin/business-settings/otp-setup') || Request::is('admin/business-settings/all-pages-banner*') || Request::is('admin/business-settings/delivery-restriction'))?'active':''}}"
                            title="{{\App\CPU\translate('Settings')}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.business-settings.web-config.index')}}"
                                title="{{\App\CPU\translate('Business_Setup')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M20.8054 7.62288L20.183 6.54279C19.6564 5.62887 18.4895 5.31359 17.5743 5.83798C17.1387 6.09461 16.6189 6.16742 16.1295 6.04035C15.6401 5.91329 15.2214 5.59678 14.9656 5.16064C14.8011 4.88342 14.7127 4.56766 14.7093 4.24531C14.7242 3.72849 14.5292 3.22767 14.1688 2.85694C13.8084 2.4862 13.3133 2.27713 12.7963 2.27734H11.5423C11.0357 2.27734 10.5501 2.47918 10.1928 2.83821C9.83547 3.19724 9.63595 3.68386 9.63839 4.19039C9.62338 5.23619 8.77126 6.07608 7.72535 6.07597C7.40299 6.07262 7.08724 5.98421 6.81001 5.81968C5.89484 5.29528 4.72789 5.61056 4.20132 6.52448L3.53313 7.62288C3.00719 8.53566 3.31818 9.70187 4.22878 10.2316C4.82068 10.5733 5.18531 11.2049 5.18531 11.8883C5.18531 12.5718 4.82068 13.2033 4.22878 13.5451C3.31934 14.0712 3.00801 15.2346 3.53313 16.1446L4.1647 17.2339C4.41143 17.6791 4.82538 18.0076 5.31497 18.1467C5.80456 18.2859 6.32942 18.2242 6.7734 17.9753C7.20986 17.7206 7.72997 17.6508 8.21812 17.7815C8.70627 17.9121 9.12201 18.2323 9.37294 18.6709C9.53748 18.9482 9.62589 19.2639 9.62924 19.5863C9.62924 20.6428 10.4857 21.4993 11.5423 21.4993H12.7963C13.8493 21.4993 14.7043 20.6484 14.7093 19.5954C14.7069 19.0873 14.9076 18.5993 15.2669 18.24C15.6262 17.8807 16.1143 17.6799 16.6224 17.6824C16.944 17.691 17.2584 17.779 17.5377 17.9387C18.4505 18.4646 19.6167 18.1536 20.1464 17.243L20.8054 16.1446C21.0605 15.7068 21.1305 15.1853 21 14.6956C20.8694 14.206 20.549 13.7886 20.1098 13.5359C19.6705 13.2832 19.3502 12.8658 19.2196 12.3762C19.089 11.8866 19.159 11.3651 19.4141 10.9272C19.58 10.6376 19.8202 10.3975 20.1098 10.2316C21.0149 9.70216 21.3252 8.54276 20.8054 7.63204V7.62288Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12.1752 14.5243C13.6311 14.5243 14.8114 13.344 14.8114 11.8881C14.8114 10.4322 13.6311 9.25195 12.1752 9.25195C10.7193 9.25195 9.53906 10.4322 9.53906 11.8881C9.53906 13.344 10.7193 14.5243 12.1752 14.5243Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Settings')}}
                                </span>
                            </a>
                        </li>
                        <li
                            class="d-none navbar-vertical-aside-has-menu {{(Request::is('admin/login-report/report/*') || Request::is('admin/user-active/report/*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('User Report')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M16.5918 12.2754C16.1983 12.2754 15.9453 12.5565 15.9453 12.9781C15.9453 13.6807 15.9453 14.4115 15.9453 15.1141C15.9453 15.8449 15.9453 16.5757 15.9453 17.2783C15.9453 17.3627 15.9453 17.4751 15.9734 17.5594C16.114 17.8686 16.395 18.0372 16.7323 17.981C17.0415 17.9248 17.2663 17.6437 17.2663 17.2783C17.2663 15.8449 17.2663 14.4115 17.2663 12.9781C17.2663 12.5565 16.9853 12.2754 16.5918 12.2754Z"
                                        fill="white" />
                                    <path
                                        d="M11.3078 14.411C11.1953 14.158 10.8862 13.9613 10.6332 14.0175C10.2959 14.0737 10.0711 14.3266 10.043 14.692C10.043 14.8607 10.043 15.0293 10.043 15.1979C10.043 15.9006 10.043 16.6033 10.043 17.2778C10.043 17.6994 10.324 18.0086 10.7456 17.9805C11.1391 17.9805 11.364 17.6994 11.364 17.2497C11.364 16.8281 11.364 16.4065 11.364 15.9849C11.364 15.5633 11.364 15.1136 11.364 14.692C11.364 14.6077 11.3359 14.5234 11.3078 14.411Z"
                                        fill="white" />
                                    <path
                                        d="M14.289 15.4226C14.2047 15.0854 13.9236 14.8886 13.5582 14.9167C13.2491 14.9448 13.0242 15.2259 12.9961 15.5632C12.9961 15.8442 12.9961 16.1534 12.9961 16.4345C12.9961 16.7436 12.9961 17.0809 12.9961 17.3901C12.9961 17.5868 13.1085 17.7555 13.2491 17.8679C13.4739 18.0084 13.6988 18.0365 13.9236 17.9241C14.1766 17.7836 14.289 17.5587 14.289 17.2777C14.289 16.7155 14.289 16.1815 14.289 15.6194C14.3171 15.5632 14.3171 15.4788 14.289 15.4226Z"
                                        fill="white" />
                                    <path
                                        d="M7.79251 14.918C7.39902 14.918 7.11795 15.1709 7.08984 15.5644C7.08984 16.1547 7.08984 16.7449 7.08984 17.3351C7.08984 17.7286 7.39902 17.9816 7.79251 17.9816C8.15789 17.9816 8.41085 17.7005 8.41085 17.307C8.41085 17.026 8.41085 16.7449 8.41085 16.4638C8.41085 16.1828 8.41085 15.8736 8.41085 15.5925C8.41085 15.199 8.15789 14.918 7.79251 14.918Z"
                                        fill="white" />
                                    <path
                                        d="M10.6611 12.3312C10.9141 12.2188 11.1109 11.9096 11.0546 11.6566C10.9984 11.3194 10.7455 11.0945 10.3801 11.0664C10.2114 11.0664 10.0428 11.0664 9.87416 11.0664C9.1715 11.0664 8.46884 11.0664 7.79428 11.0664C7.37268 11.0664 7.06351 11.3475 7.09162 11.7691C7.09162 12.1626 7.37268 12.3874 7.82239 12.3874C8.24399 12.3874 8.66558 12.3874 9.08718 12.3874C9.50878 12.3874 9.95849 12.3874 10.3801 12.3874C10.4644 12.3593 10.5487 12.3593 10.6611 12.3312Z"
                                        fill="white" />
                                    <path
                                        d="M12.9641 9.46438C12.9641 9.07089 12.7111 8.78983 12.3177 8.76172C11.7274 8.76172 8.32653 8.76172 7.73629 8.76172C7.3428 8.76172 7.08984 9.07089 7.08984 9.46438C7.08984 9.82977 7.37091 10.0827 7.7644 10.0827C8.04546 10.0827 8.32653 10.0827 8.60759 10.0827C8.88866 10.0827 12.0085 10.0827 12.2895 10.0827C12.683 10.0827 12.9641 9.82977 12.9641 9.46438Z"
                                        fill="white" />
                                    <path
                                        d="M20.3299 5.52959C20.3299 5.47337 20.3299 5.41717 20.3299 5.36095C20.3018 4.6864 19.8521 4.09615 19.2056 3.92751C18.9527 3.8713 18.7278 3.8713 18.4749 3.8713H16.6479C16.395 3.36538 15.8891 3 15.2707 3C13.1908 3 11.1391 3 9.05917 3C8.49704 3 8.04734 3.28107 7.76627 3.75888C7.71006 3.87131 7.62574 3.89941 7.51331 3.89941C7.06361 3.89941 6.64201 3.89941 6.19231 3.89941C5.91124 3.89941 5.63017 3.8713 5.34911 3.92751C4.53402 4.03994 4 4.68639 4 5.52959C4 8.00296 4 10.4763 4 12.9497C4 15.395 4 17.8402 4 20.2855C4 20.4541 4 20.5947 4.02811 20.7633C4.19675 21.5222 4.81509 22 5.60208 22C7.429 22 9.28402 22 11.1109 22C13.6405 22 16.142 22 18.6716 22C19.037 22 19.3743 21.9438 19.6834 21.7189C20.1612 21.3817 20.3299 20.9039 20.3299 20.3417C20.3299 15.3669 20.3299 10.4482 20.3299 5.52959ZM8.86242 4.63017C8.86242 4.37722 8.91864 4.32101 9.1716 4.32101C9.87426 4.32101 10.605 4.32101 11.3077 4.32101C12.5725 4.32101 13.8654 4.32101 15.1302 4.32101C15.4394 4.32101 15.4956 4.37722 15.4956 4.68639C15.4956 4.91124 15.4956 5.10799 15.4956 5.33284C15.4956 5.5858 15.4394 5.67012 15.1583 5.67012C14.3151 5.67012 13.4719 5.67012 12.6287 5.67012C12.4882 5.67012 12.3195 5.67012 12.179 5.67012C11.1953 5.67012 10.2115 5.67012 9.25592 5.67012C8.91864 5.67012 8.89053 5.6139 8.89053 5.27662C8.86243 5.05177 8.86242 4.82692 8.86242 4.63017ZM18.6997 20.6509C14.3432 20.6509 9.98669 20.6509 5.63017 20.6509C5.37722 20.6509 5.32101 20.5947 5.32101 20.3417C5.32101 15.395 5.32101 10.4482 5.32101 5.50149C5.32101 5.24853 5.37722 5.19231 5.63017 5.19231C6.22041 5.19231 6.78255 5.19231 7.37278 5.19231C7.51331 5.19231 7.54142 5.24853 7.54142 5.36095C7.54142 6.28847 8.18787 6.96301 9.08728 6.96301C11.1391 6.99112 13.1908 6.96301 15.2426 6.96301C16.0577 6.96301 16.676 6.40089 16.7885 5.5858C16.8166 5.44527 16.8166 5.33284 16.8166 5.19231H18.3343C18.4468 5.19231 18.5592 5.19231 18.6997 5.19231C18.9527 5.19231 19.0089 5.24853 19.0089 5.50149C19.0089 10.4482 19.0089 15.395 19.0089 20.3136C19.0089 20.5947 18.9808 20.6509 18.6997 20.6509Z"
                                        fill="white" />
                                </svg>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('User Report')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/login-report*')|| Request::is('admin/user-active/report/*')?'block':'none'}}">

                                <li class="nav-item {{Request::is('admin/login-report/report/1')?'active':''}}"
                                    title="{{\App\CPU\translate('User Login Report')}}">
                                    <a class="nav-link " href="{{route('admin.login-report.user.report', 1)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.29592 21.949C4.94388 21.949 4 21.0051 4 19.6786C4 15.3163 4 10.9541 4 6.59184C4 5.23979 4.94388 4.32144 6.27041 4.32144H7.11225V3.88776C7.11225 3.70919 7.11225 3.53062 7.11225 3.37756C7.11225 2.58674 7.72449 2 8.51531 2C9.53571 2 10.5816 2 11.602 2C12.648 2 13.6939 2 14.7653 2C15.5561 2 16.1684 2.61224 16.1684 3.40306C16.1684 3.55612 16.1684 3.73469 16.1684 3.91326V4.34694H17.0102C18.3878 4.34694 19.3061 5.26531 19.3061 6.64286C19.3061 11.0051 19.3061 15.3418 19.3061 19.7041C19.3061 21.0561 18.3622 22 17.0357 22H11.6531L6.29592 21.949ZM6.32143 5.4949C5.58163 5.4949 5.22449 5.87756 5.22449 6.61735V19.6275C5.22449 20.3673 5.60714 20.75 6.34694 20.75H16.9592C17.75 20.75 18.1071 20.3929 18.1071 19.602V6.59184C18.1071 6.5153 18.1071 6.46429 18.1071 6.38776C18.0561 5.92857 17.699 5.57144 17.2653 5.52042C17.1378 5.52042 17.0357 5.52042 16.9082 5.52042C16.8316 5.52042 16.7551 5.52042 16.7041 5.52042H16.6531C16.551 5.52042 16.4745 5.52042 16.398 5.52042H16.2194L16.1939 5.67347C16.0663 6.64286 15.5561 7.07654 14.6122 7.07654H14.2551C14.1786 7.07654 14.102 7.07654 14.0255 7.07654H13.9745C13.3112 7.07654 12.8265 6.77041 12.5459 6.13265C12.4694 5.92857 12.2398 5.7245 12.0357 5.59695C11.9082 5.54593 11.7806 5.4949 11.6531 5.4949C11.2704 5.4949 10.9388 5.75 10.7602 6.13265C10.5306 6.66837 10.199 6.97449 9.71428 7.02551C9.45918 7.05102 9.22959 7.07654 8.97449 7.07654C8.71939 7.07654 8.4898 7.05102 8.2602 7.02551C7.62245 6.94898 7.2398 6.4643 7.11225 5.64797L7.08673 5.4949H6.32143ZM11.6786 4.29592C12.5204 4.29592 13.2857 4.80613 13.6429 5.59695C13.6939 5.69899 13.8214 5.87755 13.9745 5.87755H14.051C14.2296 5.87755 14.3827 5.87756 14.5612 5.85205C14.6378 5.85205 14.8673 5.82653 14.8673 5.82653H14.9439V3.14796H8.33673V5.82653C8.33673 5.82653 8.87245 5.82653 8.92347 5.82653C9 5.82653 9.10204 5.82653 9.17857 5.82653C9.20408 5.82653 9.20408 5.82653 9.22959 5.82653C9.48469 5.82653 9.58673 5.67348 9.66327 5.52042C10.0459 4.7296 10.6837 4.29593 11.551 4.24491C11.602 4.29593 11.6531 4.29592 11.6786 4.29592Z"
                                                fill="white" />
                                            <path
                                                d="M7.82549 14.1169C7.62141 14.1169 7.41733 14.0404 7.28978 13.9129C7.18774 13.8108 7.13672 13.6577 7.13672 13.5047C7.13672 13.1475 7.41733 12.918 7.851 12.918H15.4786C15.9122 12.918 16.1673 13.1475 16.1928 13.5047C16.1928 13.6577 16.1418 13.8108 16.0398 13.9129C15.9122 14.0404 15.7337 14.1169 15.5041 14.1169H11.6775H7.82549Z"
                                                fill="white" />
                                            <path
                                                d="M7.77447 17.2302C7.41733 17.2302 7.13672 16.9751 7.13672 16.6435C7.13672 16.4904 7.18774 16.3374 7.31529 16.2098C7.44284 16.0823 7.5959 16.0312 7.79998 16.0312C8.3357 16.0312 8.89692 16.0312 9.43264 16.0312H13.8204C14.4071 16.0312 14.9939 16.0312 15.5806 16.0312C15.8612 16.0312 16.0908 16.2098 16.1673 16.4649C16.2439 16.72 16.1418 16.9751 15.9377 17.1027C15.8102 17.1792 15.6826 17.2047 15.5806 17.2047C14.05 17.2047 12.5194 17.2047 11.0143 17.2047C9.89182 17.2302 8.8459 17.2302 7.77447 17.2302Z"
                                                fill="white" />
                                            <path
                                                d="M7.79998 10.9801C7.5959 10.9801 7.41733 10.9036 7.28978 10.8015C7.18774 10.6995 7.13672 10.5464 7.13672 10.3934C7.13672 10.0618 7.41733 9.80664 7.79998 9.80664C8.05509 9.80664 9.12651 9.80664 9.12651 9.80664H11.5755C11.9837 9.80664 12.2388 10.0617 12.2643 10.4189C12.2643 10.572 12.2132 10.725 12.1112 10.827C11.9837 10.9546 11.8051 11.0056 11.601 11.0056C10.9632 11.0056 10.3255 11.0056 9.71325 11.0056C9.101 11.0056 8.43774 10.9801 7.79998 10.9801Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('User Login Report')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/login-report/report/2')?'active':''}}"
                                    title="{{\App\CPU\translate('Vendor Login Report')}}">
                                    <a class="nav-link " href="{{route('admin.login-report.user.report', 2)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M13.3758 21.9491C12.3057 21.9491 11.2102 21.9491 10.1401 21.9491C7.18471 21.9491 4.71338 19.9108 4.12739 17.0318C4.05096 16.6497 4 16.2166 4 15.7834C4 12.4459 4 9.08281 4 5.74523V4.31847C4 2.94267 4.94267 2 6.31847 2H18.5223C19.9236 2 20.8408 2.94268 20.8408 4.34395C20.8408 6.15287 20.8408 19.6051 20.8408 19.6051C20.8408 21.0573 19.8981 22 18.4713 22L13.3758 21.9491ZM7.64331 15.1975C8.53503 15.1975 9.1465 15.8089 9.1465 16.7006C9.1465 17.465 9.1465 18.2293 9.1465 18.9936V20.5733H9.27388C9.37579 20.5733 9.4777 20.5732 9.55414 20.5987C9.75796 20.6242 9.93631 20.6242 10.1146 20.6242C11.6688 20.6242 13.1975 20.6242 14.7516 20.6242H18.4713C19.1592 20.6242 19.5159 20.2675 19.5159 19.5796V4.29299C19.5159 3.6051 19.1592 3.24841 18.4713 3.24841H6.29299C5.6051 3.24841 5.24841 3.6051 5.24841 4.29299V15.1975H6.90446H7.64331ZM5.35032 16.6497C5.63057 18.1274 6.3949 19.1974 7.66879 19.9873L7.87261 20.1146V16.4968H5.32484L5.35032 16.6497Z"
                                                fill="white" />
                                            <path
                                                d="M16.2787 8.82889C16.2787 9.59322 16.2787 10.332 16.2787 11.0964C16.2787 11.4785 16.0748 11.7843 15.7436 11.8862C15.2341 12.0391 14.75 11.6824 14.75 11.0964C14.75 10.1537 14.75 9.21103 14.75 8.26836C14.75 7.68237 14.75 7.09639 14.75 6.5104C14.75 6.10276 15.0048 5.82251 15.3869 5.74607C15.6927 5.66964 16.0494 5.84799 16.1767 6.1792C16.2277 6.30659 16.2532 6.48493 16.2532 6.63779C16.3041 7.37665 16.2787 8.11551 16.2787 8.82889Z"
                                                fill="white" />
                                            <path
                                                d="M11.6445 9.5919C11.6445 9.08234 11.6445 8.59826 11.6445 8.08871C11.6445 7.68106 11.8738 7.40081 12.2815 7.2989C12.5872 7.22247 12.9439 7.40081 13.0713 7.70654C13.1222 7.83393 13.1477 7.96132 13.1477 8.08871C13.1477 9.08234 13.1477 10.1014 13.1477 11.0951C13.1477 11.4517 12.9948 11.7065 12.6636 11.8339C12.3834 11.9613 12.0267 11.8849 11.8483 11.6556C11.721 11.4772 11.6445 11.2734 11.6445 11.0696C11.6445 10.5855 11.6445 10.1015 11.6445 9.5919Z"
                                                fill="white" />
                                            <path
                                                d="M8.53516 10.4076C8.53516 10.1529 8.53516 9.92357 8.53516 9.66879C8.53516 9.21019 8.86637 8.85352 9.29949 8.85352C9.70713 8.85352 10.0638 9.21019 10.0638 9.66879C10.0638 10.1529 10.0638 10.637 10.0638 11.121C10.0638 11.5796 9.70713 11.9363 9.29949 11.9363C8.89184 11.9363 8.56064 11.5796 8.56064 11.1465C8.53516 10.8917 8.53516 10.6369 8.53516 10.4076Z"
                                                fill="white" />
                                            <path
                                                d="M13.9596 18.1776C13.9596 18.5853 13.6029 18.942 13.1952 18.942C12.8131 18.942 12.4564 18.5853 12.4309 18.2031C12.4054 17.821 12.7876 17.4388 13.1698 17.4388C13.5774 17.4133 13.9341 17.77 13.9596 18.1776Z"
                                                fill="white" />
                                            <path
                                                d="M17.0677 18.1776C17.0677 18.5853 16.6856 18.942 16.3034 18.942C15.9212 18.942 15.5645 18.5853 15.5391 18.2031C15.5391 17.7955 15.8958 17.4388 16.3034 17.4388C16.6856 17.4133 17.0677 17.77 17.0677 18.1776Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Vendor Login Report')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/login-report/report/3')?'active':''}}"
                                    title="{{\App\CPU\translate('Driver Login Report')}}">
                                    <a class="nav-link " href="{{route('admin.login-report.user.report', 3)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.61874 19.3556C6.33632 19.3556 6.07959 19.3556 5.82285 19.3556C4.79589 19.3299 4 18.5597 4 17.5327C4 15.8896 4 14.2465 4 12.6033C4 9.70218 4 6.80103 4 3.92554C4 2.97561 4.56482 2.25674 5.46341 2.05135C5.61745 2.02568 5.79718 2 5.95122 2C8.9294 2 11.9076 2 14.9114 2C16.0924 2 16.8883 2.7959 16.8883 3.9769C16.8883 4.18229 16.8883 4.38767 16.8883 4.64441C17.1194 4.64441 17.3504 4.64441 17.5558 4.64441C18.7625 4.64441 19.5327 5.41463 19.5327 6.62131C19.5327 11.0886 19.5327 15.5558 19.5327 20.0231C19.5327 21.2041 18.7625 22 17.5815 22C14.6033 22 11.6252 22 8.62131 22C7.44031 22 6.64441 21.2041 6.64441 20.0231C6.61874 19.792 6.61874 19.5867 6.61874 19.3556ZM5.0783 10.6778C5.0783 12.9114 5.0783 15.1451 5.0783 17.3787C5.0783 17.9949 5.36071 18.2773 5.97689 18.2773C8.92939 18.2773 11.9076 18.2773 14.8601 18.2773C15.4762 18.2773 15.7587 17.9949 15.7587 17.3787C15.7587 12.9371 15.7587 8.49551 15.7587 4.02825C15.7587 3.3864 15.4762 3.10398 14.8344 3.10398C11.8819 3.10398 8.92939 3.10398 5.97689 3.10398C5.36071 3.10398 5.0783 3.38639 5.0783 4.00256C5.0783 6.21052 5.0783 8.44415 5.0783 10.6778ZM7.72272 19.3556C7.72272 19.5867 7.72272 19.7664 7.72272 19.9461C7.72272 20.6136 8.00513 20.896 8.67265 20.896C11.5995 20.896 14.552 20.896 17.4788 20.896C18.0693 20.896 18.4031 20.5623 18.4031 19.9461C18.3774 15.5045 18.4031 11.0886 18.4031 6.64699C18.4031 6.56997 18.4031 6.49294 18.4031 6.41592C18.3774 6.08216 18.1463 5.77408 17.8126 5.7484C17.5045 5.72273 17.1964 5.7484 16.8626 5.7484C16.8626 5.8511 16.8626 5.92811 16.8626 6.03081C16.8626 9.80488 16.8626 13.5789 16.8626 17.353C16.8626 17.5584 16.837 17.7895 16.7856 17.9692C16.5545 18.8164 15.8357 19.3299 14.9114 19.3556C12.6008 19.3556 10.2901 19.3556 8.00514 19.3556C7.90244 19.3556 7.82542 19.3556 7.72272 19.3556Z"
                                                fill="white" />
                                            <path
                                                d="M10.4206 13.0391C11.3962 13.0391 12.3975 13.0391 13.3731 13.0391C13.8096 13.0391 14.092 13.4242 13.9123 13.7836C13.8096 14.0147 13.6042 14.1174 13.3474 14.1174C12.7569 14.1174 12.1408 14.1174 11.5503 14.1174C10.1895 14.1174 8.82883 14.1174 7.49378 14.1174C7.083 14.1174 6.82626 13.8093 6.8776 13.4755C6.92895 13.1931 7.16002 13.0391 7.46811 13.0391C8.46939 13.0391 9.445 13.0391 10.4206 13.0391Z"
                                                fill="white" />
                                            <path
                                                d="M10.4199 10.8577C11.3955 10.8577 12.3968 10.8577 13.3724 10.8577C13.8089 10.8577 14.0913 11.2685 13.9116 11.6279C13.8089 11.859 13.6035 11.936 13.3467 11.936C12.6792 11.936 12.0374 11.936 11.3698 11.936C10.0605 11.936 8.77678 11.936 7.46741 11.936C7.05662 11.936 6.79988 11.6279 6.8769 11.2685C6.92825 10.9861 7.15932 10.832 7.46741 10.832C8.46869 10.8577 9.4443 10.8577 10.4199 10.8577Z"
                                                fill="white" />
                                            <path
                                                d="M9.51942 16.3263C8.8519 16.3263 8.18437 16.3263 7.51685 16.3263C7.13174 16.3263 6.875 16.121 6.875 15.7872C6.875 15.4534 7.13175 15.248 7.49118 15.248C8.8519 15.248 10.1869 15.248 11.5477 15.248C11.9328 15.248 12.1638 15.4534 12.1895 15.7872C12.1895 16.121 11.9584 16.3263 11.5733 16.3263C10.8801 16.3263 10.2126 16.3263 9.51942 16.3263Z"
                                                fill="white" />
                                            <path
                                                d="M9.56915 8.41888H7.4639C6.92474 8.41888 6.48828 7.98243 6.48828 7.44327V5.59475C6.48828 5.05559 6.92474 4.61914 7.4639 4.61914H9.56915C10.1083 4.61914 10.5448 5.05559 10.5448 5.59475V7.44327C10.5448 7.98243 10.1083 8.41888 9.56915 8.41888ZM7.4639 5.44071C7.38687 5.44071 7.30985 5.51773 7.30985 5.59475V7.44327C7.30985 7.52029 7.38687 7.59731 7.4639 7.59731H9.56915C9.64617 7.59731 9.7232 7.52029 9.7232 7.44327V5.59475C9.7232 5.51773 9.64617 5.44071 9.56915 5.44071H7.4639Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Driver Login Report')}}</span>
                                    </a>
                                </li>


                                <li class="nav-item {{Request::is('admin/user-active/report/1')?'active':''}}"
                                    title="{{\App\CPU\translate('User Active Report')}}">
                                    <a class="nav-link " href="{{route('admin.user-active.user.report', 1)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M8.52857 17.6358C8.19528 17.6358 7.94531 17.3858 7.94531 17.0248C7.94531 16.5248 7.94531 16.0527 7.94531 15.5805V14.9695V14.8306C7.94531 14.7195 7.94531 14.6084 7.94531 14.4973C7.97309 14.1918 8.1675 13.9696 8.47302 13.9141C8.50079 13.9141 8.52857 13.9141 8.52857 13.9141C8.72298 13.9141 8.94518 14.0529 9.05627 14.2751C9.08405 14.3585 9.08405 14.4418 9.08405 14.5251C9.08405 14.5251 9.08405 16.7748 9.08405 17.0525C9.08405 17.4136 8.86185 17.6358 8.52857 17.6358Z"
                                                fill="white" />
                                            <path
                                                d="M11.4149 17.6654C11.3038 17.6654 11.1927 17.6376 11.0816 17.5543C10.9149 17.4432 10.8594 17.3044 10.8594 17.1377V15.9434C10.8594 15.749 10.8594 15.5546 10.8594 15.3602C10.8594 15.0547 11.0538 14.8325 11.3315 14.8047C11.3593 14.8047 11.3871 14.8047 11.4149 14.8047C11.6926 14.8047 11.9148 14.9713 11.9703 15.2491C11.9981 15.3046 11.9981 15.3601 11.9981 15.4435V15.6379C11.9981 16.1101 11.9981 16.5822 11.9981 17.0821C11.9981 17.3599 11.887 17.5543 11.6648 17.6376C11.5815 17.6376 11.4982 17.6654 11.4149 17.6654Z"
                                                fill="white" />
                                            <path
                                                d="M5.6106 17.6361C5.27731 17.6361 5.02734 17.3861 5.02734 17.0806C5.02734 16.4974 5.02734 15.9141 5.02734 15.3309C5.02734 14.9976 5.27731 14.7754 5.6106 14.7754C5.94389 14.7754 6.16608 15.0254 6.16608 15.3586C6.16608 15.5531 6.16608 15.7475 6.16608 15.9419V16.4696C6.16608 16.664 6.16608 16.8584 6.16608 17.0528C6.16608 17.3861 5.94389 17.6361 5.6106 17.6361Z"
                                                fill="white" />
                                            <path
                                                d="M5.6388 12.109C5.24997 12.109 5.02777 11.9146 5 11.5813C5 11.4147 5.05555 11.2758 5.16665 11.1647C5.27774 11.0536 5.44439 10.998 5.6388 10.998C5.94432 10.998 6.22206 10.998 6.52757 10.998H7.72186H8.02737C8.08292 10.998 8.16624 10.998 8.22179 10.998C8.5273 11.0258 8.7495 11.2202 8.80504 11.5258C8.83282 11.748 8.69395 11.9979 8.47175 12.109C8.38843 12.1368 8.33289 12.1368 8.22179 12.1368H6.11096L5.6388 12.109Z"
                                                fill="white" />
                                            <path
                                                d="M5.6106 9.85901C5.27731 9.85901 5.02734 9.63681 5.02734 9.33129C5.02734 9.16465 5.08289 9.02578 5.19399 8.91468C5.30508 8.80359 5.44395 8.74805 5.58282 8.74805C5.86056 8.74805 6.74933 8.74805 7.86029 8.74805C8.97126 8.74805 9.86002 8.74805 10.11 8.74805C10.4433 8.74805 10.6655 8.99801 10.6655 9.33129C10.6655 9.66458 10.4155 9.85901 10.0822 9.85901C9.97112 9.85901 9.38787 9.85901 8.72129 9.85901H5.91611C5.80502 9.85901 5.72169 9.85901 5.6106 9.85901Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M17.9978 13.1655V8.97142V5.41633V5.24969C17.9701 4.63866 17.5535 4.08317 16.9424 3.91652C16.7758 3.86099 16.5815 3.86099 16.4426 3.86099H16.4425H16.2481H14.3872L14.3594 3.80545C14.1372 3.30552 13.6373 3 13.0818 3H10.3044H6.94378C6.41607 3 5.99946 3.22219 5.72172 3.69435C5.66617 3.80545 5.55507 3.86099 5.4162 3.86099H4.74963H4.08305H3.86086H3.63867H3.63855C3.47197 3.86099 3.3609 3.86099 3.24983 3.88876C2.49993 3.99985 2 4.58311 2 5.38856V10.5545V20.0533C2 20.1921 2 20.3032 2.02777 20.4421C2.19442 21.1364 2.7499 21.5808 3.4998 21.5808H5.83281H12.6652H15.6358C15.1572 21.2804 14.7385 20.8935 14.4015 20.4421H3.4998C3.19428 20.4421 3.11096 20.3588 3.11096 20.0533V5.38856C3.11096 5.11082 3.22206 4.99972 3.4998 4.99972L3.63867 5.02748H4.44411H5.22179C5.44398 5.02748 5.47175 5.13859 5.47175 5.27746C5.47175 6.16623 6.08278 6.77726 6.916 6.77726H9.88782H13.0263C13.804 6.77726 14.3872 6.24956 14.4705 5.49966C14.4983 5.36081 14.4983 5.22194 14.4983 5.11085V5.11082V5.02748H16.0814H16.1925H16.4147C16.7202 5.02748 16.8036 5.11081 16.8036 5.41633V13.339C17.1837 13.2307 17.5841 13.1705 17.9978 13.1655ZM13.3318 5.19415C13.3318 5.49967 13.2207 5.61077 12.9152 5.61077L9.94337 5.58296H7.05487C6.69381 5.58296 6.61049 5.49966 6.61049 5.11082V4.4998C6.61049 4.19429 6.69381 4.11096 6.99932 4.11096H12.8874C13.2485 4.11096 13.3318 4.19428 13.3318 4.55534V5.19415Z"
                                                fill="white" />
                                            <path
                                                d="M12.832 20.2754C12.8876 20.3587 12.8598 20.442 12.832 20.5531C12.832 20.442 12.832 20.3587 12.832 20.2754Z"
                                                fill="white" />
                                            <path
                                                d="M21.9972 17.3031C21.9694 16.8588 21.8306 16.4144 21.6084 15.97C21.3584 15.47 21.0251 15.0257 20.5807 14.6646C20.053 14.2202 19.4142 13.9147 18.7199 13.8036C18.6365 13.7758 18.5532 13.7758 18.4699 13.7758C18.4143 13.7758 18.3588 13.7758 18.3033 13.748H18.2477C18.2199 13.748 18.2199 13.748 18.1922 13.748C18.1644 13.748 18.1644 13.748 18.1088 13.748H17.97C17.9422 13.748 17.9422 13.748 17.9144 13.748C17.8866 13.748 17.8866 13.748 17.8311 13.748H17.7478C17.72 13.748 17.6922 13.748 17.6367 13.748C17.6089 13.748 17.5534 13.748 17.5256 13.748C17.0812 13.8036 16.6646 13.9425 16.248 14.1647C15.7758 14.4146 15.3592 14.7201 15.0259 15.1367C14.5815 15.6645 14.276 16.3033 14.1649 16.9976C14.1649 17.0532 14.1372 17.1365 14.1372 17.2198C14.1372 17.2753 14.1371 17.3309 14.1094 17.3864V17.4698C14.1094 17.4975 14.1094 17.5531 14.1094 17.6086V17.7475C14.1094 17.7753 14.1094 17.8308 14.1094 17.8864V17.9697C14.1094 17.9975 14.1094 18.0252 14.1094 18.0808C14.1094 18.1086 14.1094 18.1641 14.1094 18.1919C14.1649 18.6085 14.276 19.0251 14.4704 19.3862C14.7204 19.8861 15.0537 20.3305 15.4981 20.6916C15.9702 21.0804 16.5257 21.3581 17.1367 21.497C17.4145 21.5526 17.6922 21.5803 17.9977 21.5803C18.1366 21.5803 18.3033 21.5803 18.4421 21.5525C18.8865 21.5248 19.3309 21.3859 19.7753 21.1637C20.2752 20.9138 20.7196 20.5805 21.0807 20.1361C21.4695 19.6639 21.7472 19.1084 21.8861 18.4974C22.025 18.1641 21.9972 17.6642 21.9972 17.3031ZM18.0255 14.6924C18.831 14.6924 19.5809 14.9979 20.1641 15.5811C20.7474 16.1644 21.0529 16.9143 21.0529 17.7197C21.0529 19.3862 19.692 20.7471 18.0255 20.7471C17.2201 20.7471 16.4702 20.4416 15.8869 19.8583C15.3037 19.2751 14.9981 18.5252 14.9981 17.7197C14.9981 16.0533 16.3591 14.6924 18.0255 14.6924Z"
                                                fill="white" />
                                            <path
                                                d="M16.914 17.4419C16.8306 17.3586 16.6918 17.303 16.5807 17.303C16.4696 17.303 16.3585 17.3586 16.2474 17.4419C16.053 17.6363 16.053 17.9141 16.2474 18.1085C16.4973 18.3585 16.7473 18.6084 16.9973 18.8584L17.1917 19.0528C17.275 19.1361 17.3861 19.1917 17.525 19.1917C17.6916 19.1917 17.8027 19.1083 17.8583 19.025L19.7747 17.1086C19.8025 17.0808 19.8302 17.0531 19.858 17.0253C19.9413 16.9142 19.9691 16.7476 19.9413 16.6087C19.8858 16.4698 19.7747 16.3587 19.6358 16.3032C19.5803 16.3032 19.5525 16.2754 19.4969 16.2754C19.3581 16.2754 19.247 16.331 19.1359 16.4421L18.4693 17.1086C18.1638 17.4141 17.8305 17.7474 17.4972 18.0529C17.3028 17.8585 17.1084 17.6641 16.914 17.4419Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('User Active Report')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/user-active/report/2')?'active':''}}"
                                    title="{{\App\CPU\translate('Vendor Active Report')}}">
                                    <a class="nav-link " href="{{route('admin.user-active.user.report', 2)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.78573 14.0983C6.57981 14.0983 6.42537 14.0468 6.29667 13.9181C6.19371 13.8151 6.16797 13.6865 6.16797 13.5578C6.16797 13.2489 6.42537 13.043 6.81147 13.043H14.5077C14.8938 13.043 15.1255 13.2489 15.1512 13.5835C15.1512 13.7379 15.0998 13.8409 15.0225 13.9439C14.9196 14.0468 14.7394 14.124 14.5335 14.124H10.6725L6.78573 14.0983Z"
                                                fill="white" />
                                            <path
                                                d="M6.75999 10.9323C6.57981 10.9323 6.42537 10.8808 6.32241 10.7521C6.21945 10.6492 6.16797 10.5205 6.16797 10.3918C6.16797 10.0829 6.42537 9.87695 6.75999 9.87695C7.01739 9.87695 8.09847 9.87695 8.09847 9.87695H10.5695C10.9299 9.87695 11.1615 10.0829 11.1873 10.4175C11.1873 10.5719 11.1358 10.7006 11.0328 10.7779C10.9299 10.8808 10.7754 10.958 10.5695 10.958C9.92602 10.958 9.28251 10.958 8.66475 10.958C8.04699 10.958 7.40349 10.9323 6.75999 10.9323Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3 19.7864C3 21.0734 3.92664 22 5.23939 22H10.6448H15.7892C15.1911 21.7686 14.659 21.4048 14.2286 20.9447H5.29087C4.49293 20.9447 4.08109 20.5071 4.08109 19.7349V6.60747C4.08109 5.80953 4.49292 5.39767 5.26512 5.39767H6.19177L6.21751 5.62934C6.32047 6.40154 6.68083 6.86486 7.29859 6.94208C7.50451 6.96782 7.73617 6.99358 7.99357 6.99358C8.20387 6.99358 8.41417 6.97235 8.64372 6.94918C8.66704 6.94682 8.69056 6.94445 8.71429 6.94208C9.17761 6.8906 9.51223 6.60747 9.71815 6.09267C9.89833 5.65509 10.2587 5.39767 10.6705 5.39767C10.825 5.39767 10.9794 5.42342 11.1081 5.50064C11.3398 5.6036 11.5457 5.83525 11.6487 6.06691C11.9318 6.68467 12.3694 6.99358 13.0129 6.99358H13.0644H13.296H13.6564C14.583 6.99358 15.0463 6.58172 15.175 5.65507L15.2008 5.42341H15.4324H15.6898H15.7413H15.9472C16.0116 5.42341 16.0695 5.42985 16.1274 5.43629C16.1853 5.44273 16.2433 5.44917 16.3076 5.44917C16.7967 5.47491 17.1828 5.88675 17.2342 6.37581V6.58171V13.8411C17.2602 13.8406 17.2862 13.8404 17.3122 13.8404C17.6486 13.8404 17.9759 13.8797 18.2896 13.9541V6.58171C18.2896 5.24323 17.3887 4.34234 16.0502 4.34234H15.1236V3.82754V3.33847C15.1236 2.56627 14.5315 2 13.7851 2H10.5933H7.47877C6.73231 2.02574 6.14029 2.59201 6.14029 3.33847V3.85327V4.36807H5.21365C3.92664 4.36807 3 5.26897 3 6.58171V19.7864ZM12.5753 5.62934C12.2407 4.8314 11.4942 4.34234 10.6705 4.34234H10.6705C10.6448 4.34234 10.5933 4.34234 10.5676 4.36807C9.71815 4.41955 9.10039 4.8314 8.71429 5.60361C8.63707 5.75805 8.50837 5.93821 8.19949 5.93821H8.14801H7.89061H7.22137V3.08107H14.0425V5.91247C14.0425 5.91247 13.6564 5.93821 13.5792 5.93821C13.5049 5.93821 13.4247 5.94416 13.3414 5.95035C13.2515 5.95703 13.1579 5.96397 13.0644 5.96397H12.9871C12.7812 5.96397 12.6268 5.75804 12.5753 5.62934Z"
                                                fill="white" />
                                            <path
                                                d="M20.9664 17.6756C20.9407 17.2638 20.812 16.8519 20.6061 16.4401C20.3744 15.9767 20.0655 15.5649 19.6537 15.2303C19.1646 14.8185 18.5726 14.5353 17.9291 14.4323C17.8519 14.4066 17.7747 14.4066 17.6974 14.4066C17.646 14.4066 17.5945 14.4066 17.543 14.3809H17.4915C17.4658 14.3809 17.4658 14.3809 17.44 14.3809C17.4143 14.3809 17.4143 14.3809 17.3628 14.3809H17.2341C17.2084 14.3809 17.2084 14.3809 17.1826 14.3809C17.1569 14.3809 17.1569 14.3809 17.1054 14.3809H17.0282C17.0025 14.3809 16.9767 14.3809 16.9252 14.3809C16.8995 14.3809 16.848 14.3809 16.8223 14.3809C16.4104 14.4323 16.0243 14.561 15.6382 14.767C15.2007 14.9986 14.8146 15.2818 14.5057 15.6679C14.0938 16.1569 13.8107 16.7489 13.7077 17.3924C13.7077 17.4439 13.682 17.5211 13.682 17.5984C13.682 17.6498 13.682 17.7013 13.6562 17.7528V17.83C13.6562 17.8558 13.6562 17.9073 13.6562 17.9587V18.0874C13.6562 18.1132 13.6562 18.1647 13.6562 18.2161V18.2933C13.6562 18.3191 13.6562 18.3448 13.6562 18.3963C13.6562 18.422 13.6562 18.4735 13.6562 18.4993C13.7077 18.8854 13.8107 19.2715 13.9909 19.6061C14.2225 20.0694 14.5314 20.4813 14.9433 20.8159C15.3808 21.1762 15.8956 21.4336 16.4619 21.5623C16.7193 21.6138 16.9767 21.6395 17.2599 21.6395C17.3886 21.6395 17.543 21.6395 17.6717 21.6138C18.0835 21.5881 18.4954 21.4594 18.9072 21.2534C19.3705 21.0218 19.7824 20.7129 20.117 20.3011C20.4774 19.8635 20.7348 19.3487 20.8635 18.7824C20.9922 18.4478 20.9922 18.0102 20.9664 17.6756ZM17.3113 15.256C18.0578 15.256 18.7528 15.5392 19.2933 16.0797C19.8339 16.6202 20.117 17.3152 20.117 18.0617C20.117 19.6061 18.8557 20.8673 17.3113 20.8673C16.5649 20.8673 15.8699 20.5842 15.3294 20.0437C14.7888 19.5031 14.5057 18.8082 14.5057 18.0617C14.5057 16.5173 15.7669 15.256 17.3113 15.256Z"
                                                fill="white" />
                                            <path
                                                d="M16.2568 17.8038C16.1796 17.7265 16.0509 17.6751 15.9479 17.6751C15.845 17.6751 15.742 17.7265 15.639 17.8038C15.4589 17.9839 15.4589 18.2413 15.639 18.4215C15.8707 18.6532 16.1024 18.8848 16.334 19.1165L16.5142 19.2967C16.5914 19.3739 16.6944 19.4254 16.8231 19.4254C16.9775 19.4254 17.0805 19.3481 17.132 19.2709L18.908 17.4949C18.9338 17.4691 18.9595 17.4434 18.9852 17.4177C19.0625 17.3147 19.0882 17.1603 19.0625 17.0316C19.011 16.9029 18.908 16.7999 18.7793 16.7484C18.7278 16.7484 18.7021 16.7227 18.6506 16.7227C18.5219 16.7227 18.419 16.7741 18.316 16.8771L17.6982 17.4949C17.4151 17.778 17.1062 18.0869 16.7973 18.37C16.6429 18.1898 16.437 17.9839 16.2568 17.8038Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M13.3789 16.207C13.2166 16.5565 13.1289 16.9315 13.0872 17.2624H9.92441C8.86933 17.2366 7.78852 17.2366 6.7077 17.2366H6.70691C6.39802 17.2366 6.14062 17.005 6.14062 16.7218C6.14062 16.5674 6.1921 16.4387 6.29506 16.3615C6.39802 16.2585 6.55246 16.207 6.70691 16.207H8.35427H12.7816H13.3789Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="text-truncate">{{\App\CPU\translate('Vendor Active Report')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/user-active/report/3')?'active':''}}"
                                    title="{{\App\CPU\translate('Driver Active Report')}}">
                                    <a class="nav-link " href="{{route('admin.user-active.user.report', 3)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5.00122 11.6293C5.00746 11.2423 5.34748 10.9639 5.76033 10.9705C6.43121 10.9813 7.1021 10.9922 7.79878 11.0034L8.62447 11.0167L9.16633 11.0254C9.57918 11.0321 9.99204 11.0388 10.4049 11.0454C10.7919 11.0516 11.0208 11.2618 11.092 11.6501C11.0912 11.7017 11.0907 11.7275 11.0899 11.7791C11.0858 12.0372 10.9272 12.2669 10.6933 12.3664C10.5635 12.4159 10.434 12.4396 10.2792 12.4371C9.73734 12.4284 9.16967 12.4192 8.6278 12.4105L7.31185 12.3893C6.79579 12.3809 6.27973 12.3726 5.73787 12.3639C5.37662 12.3581 5.12149 12.1733 5.02327 11.862C4.99872 11.7842 4.99997 11.7067 5.00122 11.6293Z"
                                                fill="white" />
                                            <path
                                                d="M4.9943 8.83244C4.99722 8.65182 5.07752 8.47245 5.2082 8.37132C5.36468 8.2706 5.59818 8.19693 5.77921 8.17404C6.14087 8.15406 6.50211 8.15989 6.83755 8.1653C6.99237 8.1678 7.12137 8.16988 7.27619 8.17237L7.74066 8.17986C8.1019 8.18569 8.43734 8.1911 8.79858 8.19692C9.18563 8.20316 9.41454 8.41332 9.51193 8.77623C9.51109 8.82784 9.53647 8.85406 9.53564 8.90566C9.53148 9.16369 9.37292 9.39343 9.13903 9.4929C9.03498 9.54284 8.90554 9.56656 8.80233 9.5649C7.82181 9.54909 6.7897 9.53245 5.75758 9.51581C5.42214 9.5104 5.19239 9.35183 5.06795 9.06592C5.01718 9.01348 4.99306 8.90985 4.9943 8.83244Z"
                                                fill="white" />
                                            <path
                                                d="M4.99797 5.684C5.00421 5.29695 5.34424 5.01852 5.7575 4.99938C5.91232 5.00187 6.09292 5.00479 6.24774 5.00728L6.47997 5.01103L6.7122 5.01477C6.89283 5.01768 7.04764 5.02018 7.22827 5.02309C7.66692 5.03016 7.97157 5.3448 7.96533 5.73184C7.95909 6.11889 7.61865 6.42311 7.2058 6.41646C6.68974 6.40814 6.19949 6.40023 5.73504 6.39274C5.32219 6.38609 4.99173 6.07105 4.99797 5.684Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.09032 21.9484H11.3677L16.529 22C16.6655 22 16.7974 21.991 16.9242 21.9734C16.271 21.7612 15.6875 21.3943 15.2168 20.9161H12.7871H8.09032H8.09014C7.88377 20.9161 7.70317 20.9161 7.49678 20.8903C7.41938 20.8645 7.31618 20.8645 7.21298 20.8645H7.21291L6.98065 20.8387V19.1097V16.7871C6.98065 15.9613 6.4129 15.3935 5.5871 15.3935H4.83871H3.03226V4.21935C3.03226 3.44515 3.44516 3.03226 4.21936 3.03226H16.5548C17.329 3.03226 17.7419 3.47096 17.7419 4.21935V13.7435C17.9032 13.7248 18.0671 13.7153 18.2333 13.7153C18.4255 13.7153 18.6146 13.7281 18.8 13.7529V4.24515C18.8 2.90321 17.9226 2 16.5806 2H4.21936C2.90323 2 2 2.90322 2 4.21935V5.6645V15.8323C2 16.271 2.05161 16.6838 2.12903 17.0709C2.72258 19.9355 5.1742 21.9484 8.09032 21.9484ZM5.50968 20.2451C4.19355 19.4193 3.41936 18.2839 3.10968 16.7613L3.03226 16.4516H5.89678V20.4774L5.50968 20.2451Z"
                                                fill="white" />
                                            <path
                                                d="M21.8954 17.561C21.8696 17.1481 21.7406 16.7352 21.5341 16.3223C21.3019 15.8578 20.9922 15.4449 20.5793 15.1094C20.089 14.6965 19.4954 14.4126 18.8503 14.3094C18.7729 14.2836 18.6954 14.2836 18.618 14.2836C18.5664 14.2836 18.5148 14.2836 18.4632 14.2578H18.4116C18.3858 14.2578 18.3858 14.2578 18.36 14.2578C18.3341 14.2578 18.3341 14.2578 18.2825 14.2578H18.1535C18.1277 14.2578 18.1277 14.2578 18.1019 14.2578C18.0761 14.2578 18.0761 14.2578 18.0245 14.2578H17.9471C17.9212 14.2578 17.8954 14.2578 17.8438 14.2578C17.818 14.2578 17.7664 14.2578 17.7406 14.2578C17.3277 14.3094 16.9406 14.4385 16.5535 14.6449C16.1148 14.8772 15.7277 15.161 15.418 15.5481C15.0051 16.0385 14.7212 16.632 14.618 17.2772C14.618 17.3288 14.5922 17.4062 14.5922 17.4836C14.5922 17.5352 14.5922 17.5868 14.5664 17.6385V17.7159C14.5664 17.7417 14.5664 17.7933 14.5664 17.8449V17.9739C14.5664 17.9997 14.5664 18.0513 14.5664 18.1029V18.1804C14.5664 18.2062 14.5664 18.232 14.5664 18.2836C14.5664 18.3094 14.5664 18.361 14.5664 18.3868C14.618 18.7739 14.7212 19.161 14.9019 19.4965C15.1341 19.961 15.4438 20.3739 15.8567 20.7094C16.2954 21.0707 16.8116 21.3288 17.3793 21.4578C17.6374 21.5094 17.8954 21.5352 18.1793 21.5352C18.3083 21.5352 18.4632 21.5352 18.5922 21.5094C19.0051 21.4836 19.418 21.3546 19.8309 21.1481C20.2954 20.9159 20.7083 20.6062 21.0438 20.1933C21.4051 19.7546 21.6632 19.2385 21.7922 18.6707C21.9212 18.3352 21.9212 17.8965 21.8954 17.561ZM18.2309 15.1352C18.9793 15.1352 19.6761 15.4191 20.218 15.961C20.76 16.503 21.0438 17.1997 21.0438 17.9481C21.0438 19.4965 19.7793 20.761 18.2309 20.761C17.4825 20.761 16.7858 20.4772 16.2438 19.9352C15.7019 19.3933 15.418 18.6965 15.418 17.9481C15.418 16.3997 16.6567 15.1352 18.2309 15.1352Z"
                                                fill="white" />
                                            <path
                                                d="M17.1728 17.6913C17.0954 17.6139 16.9664 17.5622 16.8631 17.5622C16.7599 17.5622 16.6567 17.6139 16.5535 17.6913C16.3728 17.8719 16.3728 18.13 16.5535 18.3106C16.7857 18.5429 17.018 18.7751 17.2502 19.0074L17.4309 19.1881C17.5083 19.2655 17.6115 19.3171 17.7406 19.3171C17.8954 19.3171 17.9986 19.2397 18.0502 19.1623L19.8309 17.3816C19.8567 17.3558 19.8825 17.33 19.9083 17.3042C19.9857 17.201 20.0115 17.0461 19.9857 16.9171C19.9341 16.7881 19.8309 16.6848 19.7018 16.6332C19.6502 16.6332 19.6244 16.6074 19.5728 16.6074C19.4438 16.6074 19.3406 16.659 19.2373 16.7623L18.618 17.3816C18.3341 17.6655 18.0244 17.9752 17.7147 18.259C17.5341 18.0784 17.3535 17.8977 17.1728 17.6913Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="text-truncate">{{\App\CPU\translate('Driver Active Report')}}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        @endif
                        <!-- Plan End  -->

                        <!--promotion management end-->
                        

                        <!--support section -->
                        @if(\App\CPU\Helpers::module_permission_check('support_section'))

                        <li class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/support-ticke')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:void(0)"
                                title="{{\App\CPU\translate('help_&_support_section')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M12.5165 21.9981C12.1532 21.9233 11.6892 21.7977 11.2837 21.4948C10.3903 20.8266 10.0298 19.6849 10.3855 18.6534C10.7382 17.6305 11.7103 16.9432 12.8051 16.9432C12.8271 16.9432 12.8492 16.9432 12.8712 16.9442C13.9458 16.971 14.9093 17.7216 15.216 18.7694L15.2438 18.8662C15.3023 18.8739 15.3665 18.8777 15.4327 18.8777C16.2475 18.8777 17.5426 18.1971 18.0775 17.4944H17.9951C17.9376 17.4944 17.881 17.4944 17.8245 17.4935C17.4055 17.4867 17.1927 17.2701 17.1927 16.8502V16.4514C17.1918 14.6655 17.1918 12.8192 17.1956 10.9441C17.1956 10.8089 17.2234 10.6622 17.2733 10.5299C17.3605 10.2979 17.533 10.1762 17.7737 10.1762C17.7832 10.1762 17.7928 10.1762 17.8024 10.1772C17.8599 10.1791 17.9165 10.18 17.9731 10.18C17.9903 10.18 18.1025 10.1791 18.1677 10.1791C18.229 8.83123 18.1725 7.4067 17.3614 6.11638C16.1574 4.19911 14.4664 3.22801 12.3353 3.22801C12.1963 3.22801 12.0554 3.23185 11.9154 3.24047C9.08267 3.40248 6.84522 5.48943 6.47423 8.31453C6.41671 8.75071 6.41192 9.18593 6.40712 9.64703V9.66812C6.40521 9.83972 6.40329 10.0084 6.3985 10.1791H6.74744C7.11076 10.181 7.32933 10.3727 7.37918 10.7332C7.39068 10.8194 7.39068 10.9038 7.39068 10.9853V11.0198C7.39164 12.9562 7.39164 14.8927 7.39068 16.8291C7.39068 17.2902 7.18554 17.4963 6.72635 17.4963C6.5653 17.4963 6.40425 17.4973 6.2432 17.4992H6.1828C6.02846 17.5002 5.88467 17.5011 5.74183 17.5011C5.42356 17.5011 5.16377 17.4963 4.92603 17.4858C3.8792 17.4398 3.04136 16.5991 3.01739 15.5704C2.99343 14.5274 2.99438 13.4212 3.01931 12.1884C3.03944 11.2173 3.85428 10.1772 5.03628 10.1772C5.06504 10.1772 5.09475 10.1781 5.12447 10.1791C5.14364 10.18 5.16282 10.18 5.18391 10.18H5.20787C5.20787 10.1302 5.20787 10.0832 5.20787 10.0362C5.20883 9.89148 5.20883 9.74769 5.20787 9.60293C5.20595 8.99995 5.20308 8.37684 5.33345 7.76331C5.85974 5.27757 7.27757 3.52998 9.54953 2.56751C10.4391 2.19077 11.3709 2 12.319 2C15.3895 2 18.0948 3.99013 19.0496 6.95135C19.2835 7.678 19.3938 8.4612 19.3851 9.34506C19.3832 9.54925 19.3832 9.75344 19.3842 9.96242V10.1743C19.7657 10.2011 20.0878 10.3257 20.3677 10.5549C20.965 11.0428 21.2928 11.6822 21.3177 12.4041C21.357 13.5822 21.3455 14.7355 21.3283 15.7094C21.3129 16.5943 20.7588 17.2375 19.8827 17.388C19.6487 17.4283 19.505 17.527 19.3688 17.7417C18.6086 18.9362 17.5187 19.6859 16.1296 19.9687C15.9158 20.0118 15.7059 20.0338 15.4835 20.0568C15.402 20.0655 15.3272 20.0731 15.2515 20.0818C15.0837 20.8305 14.638 21.3817 13.9276 21.721C13.7436 21.8092 13.5451 21.8648 13.3524 21.9185L13.34 21.9224C13.247 21.9482 13.1693 21.9703 13.0936 21.9942L13.0696 22H12.5165V21.9981ZM12.7773 18.1357C12.4197 18.1367 12.0822 18.2795 11.8292 18.5374C11.5838 18.7876 11.4505 19.1126 11.4553 19.4529C11.4658 20.1776 12.0813 20.7902 12.7993 20.7902H12.8214C13.5336 20.7777 14.1098 20.2006 14.1059 19.5037C14.1021 18.7234 13.5327 18.1357 12.7811 18.1357H12.7773ZM5.15706 11.3831C4.6001 11.3831 4.22144 11.7493 4.21664 12.2948C4.20802 13.2477 4.20706 14.2638 4.21568 15.4937C4.21856 15.9098 4.556 16.2635 4.96821 16.2817C5.1168 16.2885 5.27593 16.2913 5.4715 16.2913C5.60379 16.2913 5.73608 16.2904 5.87029 16.2885H5.89905C5.99108 16.2875 6.08119 16.2865 6.17034 16.2865V11.3841H5.63063L5.15706 11.3831ZM18.4131 16.2817C18.4888 16.2827 18.5636 16.2837 18.6393 16.2846H18.6527C18.7889 16.2865 18.9068 16.2885 19.0237 16.2885C19.1934 16.2885 19.3324 16.2846 19.4599 16.276C19.8798 16.2472 20.1223 16.0258 20.1233 15.6682C20.1281 14.6588 20.1319 13.5794 20.1204 12.5277C20.1166 12.1625 19.9833 11.8433 19.735 11.6036C19.6171 11.4905 19.4628 11.408 19.3401 11.3956C19.2193 11.3831 19.0851 11.3764 18.9183 11.3764C18.8234 11.3764 18.7285 11.3783 18.6316 11.3802C18.5482 11.3822 18.4792 11.3831 18.4131 11.3841V16.2817Z"
                                        fill="#fff" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('help_&_support_section')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/support-ticke')?'block':'none'}}">
                                <li
                                    class="nav-item {{(Request::is('admin/support-ticket*') || Request::is('admin/contact*'))?'scroll-here':''}}">
                                    <small class="nav-subtitle"
                                        title="">{{\App\CPU\translate('help_&_support_section')}}</small>
                                    <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{Request::is('admin/contact*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.contact.list')}}"
                                        title="{{\App\CPU\translate('messages')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.6407 10.1437C21.3669 8.86624 20.3176 8.0222 19.0174 8.0222C17.3521 8.0222 15.6869 8.0222 14.0216 8.0222C13.862 8.0222 13.7935 7.99941 13.7935 7.81692C13.7935 6.67635 13.7935 5.51294 13.7935 4.37237C13.7935 2.98087 12.8126 2 11.4211 2C9.04874 2 6.67635 2 4.32677 2C3.00371 2 2 3.00369 2 4.32676C2 5.90075 2 7.49756 2 9.07155C2 9.25405 2.02281 9.43651 2.06844 9.61901C2.31936 10.6683 3.1862 11.3755 4.25834 11.3983C4.3952 11.3983 4.44083 11.4439 4.41802 11.5808C4.41802 11.9458 4.41802 12.3336 4.41802 12.6986C4.41802 12.8126 4.41802 12.9267 4.46364 13.0179C4.69175 13.8163 5.64984 14.0672 6.24293 13.497C6.74479 12.9951 7.22383 12.4933 7.72568 11.9914C7.74849 12.0142 7.7713 12.0142 7.7713 12.037C7.7713 12.1055 7.7713 12.1739 7.7713 12.2423C7.7713 13.6338 7.7713 15.0025 7.7713 16.394C7.7713 16.6678 7.79411 16.9187 7.86255 17.1696C8.18191 18.333 9.20842 19.1086 10.4402 19.1086C11.6493 19.1086 12.8583 19.1086 14.0445 19.1086C14.1813 19.1086 14.2726 19.1542 14.3638 19.2455C15.1622 20.0439 15.9378 20.8195 16.7362 21.6179C17.1012 21.9828 17.5574 22.0969 18.0593 21.9144C18.5155 21.7319 18.7892 21.3213 18.7892 20.7738C18.7892 20.272 18.7892 19.7929 18.7892 19.2911C18.7892 19.1314 18.8349 19.1086 18.9717 19.1086C20.5457 19.0858 21.6863 17.9452 21.6863 16.3712C21.6863 14.5235 21.6863 12.6529 21.6863 10.8052C21.6863 10.5543 21.6863 10.349 21.6407 10.1437ZM7.79411 10.3262C7.7713 10.5315 7.70287 10.6912 7.54319 10.8508C6.90447 11.4667 6.28856 12.1055 5.64983 12.7214C5.5814 12.7898 5.53578 12.8811 5.42172 12.8354C5.33047 12.7898 5.35329 12.6986 5.35329 12.6073C5.35329 12.0827 5.35329 11.558 5.35329 11.0105C5.35329 10.5999 5.17079 10.4402 4.783 10.4402C4.53207 10.4402 4.30396 10.463 4.05303 10.4174C3.41431 10.3034 2.93527 9.7787 2.93527 9.11717C2.93527 7.49755 2.93527 5.87793 2.93527 4.25831C2.93527 3.52835 3.52837 2.95807 4.25834 2.95807C5.46734 2.95807 6.69916 2.95807 7.90817 2.95807C9.07155 2.95807 10.2349 2.95807 11.3983 2.95807C12.3108 2.95807 12.8583 3.50555 12.8583 4.41801C12.8583 5.55858 12.8583 6.69915 12.8583 7.86253C12.8583 8.04503 12.7898 8.06784 12.6301 8.06784C11.8089 8.06784 10.9877 8.04503 10.1665 8.09065C8.9575 8.11346 7.93098 9.11718 7.79411 10.3262ZM20.751 16.3712C20.751 17.3977 20.0211 18.1277 18.9945 18.1505C18.7892 18.1505 18.5839 18.1505 18.3786 18.1505C18.0365 18.1505 17.854 18.333 17.854 18.6752C17.854 19.3595 17.854 20.0211 17.854 20.7054C17.854 20.7966 17.854 20.9107 17.7627 20.9791C17.6259 21.0932 17.489 21.0247 17.3749 20.9107C16.7362 20.272 16.0747 19.6104 15.436 18.9717C15.2307 18.7892 15.0482 18.5839 14.8429 18.3786C14.6832 18.1961 14.5007 18.1277 14.2726 18.1277C12.9951 18.1277 11.7405 18.1277 10.4631 18.1277C9.59622 18.1277 8.93469 17.6259 8.72938 16.8275C8.68376 16.6678 8.68376 16.5081 8.68376 16.3484C8.68376 14.4778 8.68376 12.6073 8.68376 10.7368C8.68376 9.68745 9.41373 8.9803 10.4402 8.9803C11.8546 8.9803 13.2689 8.9803 14.706 8.9803C16.1203 8.9803 17.5346 8.9803 18.9717 8.9803C19.8158 8.9803 20.4773 9.48215 20.6826 10.2577C20.7282 10.4174 20.7282 10.5771 20.7282 10.714C20.751 12.6073 20.751 14.4779 20.751 16.3712Z"
                                                fill="white" />
                                            <path
                                                d="M14.043 13.5418C14.043 13.154 14.3623 12.8574 14.7501 12.8574C15.1379 12.8574 15.4345 13.154 15.4345 13.5418C15.4345 13.9296 15.1379 14.2489 14.7501 14.2489C14.3395 14.2489 14.043 13.9296 14.043 13.5418Z"
                                                fill="white" />
                                            <path
                                                d="M11.125 13.5652C11.125 13.1774 11.4216 12.8809 11.8322 12.8809C12.22 12.8809 12.5393 13.2002 12.5165 13.588C12.5165 13.953 12.1971 14.2724 11.8322 14.2724C11.4444 14.2495 11.125 13.953 11.125 13.5652Z"
                                                fill="white" />
                                            <path
                                                d="M18.3329 13.5646C18.3329 13.9524 18.0135 14.2489 17.6258 14.2489C17.238 14.2489 16.9414 13.9296 16.9414 13.5418C16.9414 13.1768 17.2608 12.8574 17.6486 12.8574C18.0364 12.8574 18.3329 13.1768 18.3329 13.5646Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            <span class="position-relative">
                                                {{\App\CPU\translate('messages')}}
                                                @php($message=\App\Model\Contact::where('seen',0)->count())
                                                @if($message!=0)
                                                <span
                                                    class="btn-status btn-xs-status btn-status-danger position-absolute top-0 menu-status"></span>
                                                @endif
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{Request::is('admin/support-ticket*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.support-ticket.view')}}"
                                        title="{{\App\CPU\translate('Support_Ticket')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5.54738 22C4.71562 22 4.1178 21.4801 4.01383 20.6484C3.96184 20.3365 4.06581 20.0246 4.16978 19.7127C5.1315 16.7495 7.00295 14.7481 9.81014 13.7864C9.83613 13.7864 9.86212 13.7604 9.88811 13.7604L10.0181 13.7084L9.75815 13.5265C9.62819 13.4225 9.49823 13.3445 9.39426 13.2405C9.16033 13.0586 8.97838 12.8767 8.82243 12.6687C8.74445 12.5647 8.66647 12.5128 8.53651 12.5128C8.27658 12.5128 8.01666 12.5128 7.75674 12.5128H7.02895C5.88528 12.5128 5.10551 11.733 5.10551 10.5893C5.10551 10.1215 5.10551 8.25 5.10551 8.25C5.10551 7.83412 5.20948 7.47022 5.44341 7.13231C5.52139 7.02834 5.57337 6.89841 5.59936 6.76845C5.62536 6.69047 5.65135 6.63847 5.67734 6.56049C6.5091 4.68904 7.93868 3.18149 9.88811 2.4797C12.1235 1.64794 14.6707 1.90785 16.6721 3.18148C18.0237 4.03923 19.0115 5.39082 19.5833 6.87239C19.6093 6.97636 19.6613 7.08035 19.7392 7.18432C19.9472 7.47023 20.0252 7.80814 20.0252 8.19803V8.63989C20.0252 9.2897 20.0252 9.9395 20.0252 10.6153C20.0252 11.707 19.2454 12.4868 18.1537 12.4868C17.9198 12.4868 17.6858 12.4868 17.4779 12.4868C17.192 12.4868 16.9061 12.4868 16.6202 12.4868C16.4902 12.4868 16.3862 12.5388 16.3082 12.6427C15.9963 13.0066 15.6324 13.3445 15.1906 13.6304L15.1126 13.6824L15.2166 13.7084C15.7884 13.9163 16.2303 14.0723 16.6202 14.2802C18.9335 15.5019 20.441 17.4773 21.0908 20.1285C21.1948 20.5704 21.1168 21.0123 20.8569 21.3502C20.571 21.7141 20.1291 21.922 19.6093 21.922C18.1537 21.922 16.6981 21.922 15.2166 21.922H8.53651L5.54738 22ZM12.6433 14.4622C12.1754 14.4622 11.6816 14.5142 11.1617 14.5922C8.14662 15.112 5.75532 17.3733 5.07951 20.3884C5.05352 20.4924 5.05352 20.6224 5.10551 20.7264C5.20948 20.9083 5.39143 20.9343 5.57337 20.9343C6.97696 20.9343 8.35456 20.9343 9.75815 20.9343H12.5913H18.8295C19.0634 20.9343 19.3234 20.9343 19.5573 20.9343C19.7133 20.9343 19.8432 20.8823 19.9212 20.7783C19.9992 20.7004 20.0252 20.5704 19.9732 20.4405C19.5053 18.465 18.4916 16.9315 16.984 15.8918C15.7104 14.9301 14.2548 14.4622 12.6433 14.4622ZM12.5913 5.62477C12.4094 5.62477 12.2274 5.65075 12.0195 5.67674C10.9798 5.8327 10.0181 6.40455 9.39426 7.28829C8.77044 8.14604 8.53651 9.23773 8.77044 10.2774C9.13433 12.0709 10.7459 13.3965 12.6173 13.3965C13.2151 13.3965 13.813 13.2665 14.3588 12.9806C14.8007 12.7727 15.1646 12.4608 15.5025 12.0709L15.5545 12.0189L15.5025 11.9929C15.4505 11.9669 15.3985 11.9409 15.3465 11.9409H15.2685H14.4368C14.2548 11.9409 14.0729 11.9409 13.9169 11.9409C13.605 11.9409 13.4231 11.811 13.3451 11.551C13.2931 11.3691 13.3191 11.2131 13.4231 11.0832C13.5271 10.9532 13.709 10.8752 13.9429 10.8752C14.2808 10.8752 14.5927 10.8752 14.9306 10.8752C15.2945 10.8752 15.6584 10.8752 16.0223 10.8752C16.1783 10.8752 16.2563 10.8233 16.3082 10.6673C16.4122 10.3034 16.4902 9.9395 16.4902 9.60159C16.5162 8.5619 16.0743 7.54822 15.3205 6.76845C14.5668 6.01467 13.605 5.62477 12.5913 5.62477ZM7.47082 11.4731C7.57479 11.4731 7.65277 11.4731 7.75674 11.4731H7.80872C7.8607 11.4731 7.93868 11.473 7.96468 11.4211C7.99067 11.3691 7.99067 11.2911 7.93868 11.2131C7.62677 10.4074 7.5488 9.52364 7.70475 8.6139C7.75674 8.30199 7.83471 7.99008 7.99067 7.60019L8.01666 7.54822L7.96468 7.5222C7.93868 7.49621 7.91269 7.49621 7.8867 7.49621H6.95097C6.4831 7.49621 6.1712 7.78214 6.1712 8.25C6.1712 9.05577 6.1712 9.86154 6.1712 10.6933C6.1712 11.1612 6.4831 11.4471 6.95097 11.4731H7.08093H7.47082ZM17.14 7.5742C17.6339 8.84783 17.6339 10.0955 17.14 11.3951L17.114 11.4731H17.8418C17.9718 11.4731 18.1277 11.4731 18.2577 11.4731C18.6736 11.4471 18.9595 11.1611 18.9595 10.7713C18.9595 9.96549 18.9595 9.10776 18.9595 8.22402C18.9595 7.86012 18.6995 7.54819 18.3616 7.5222C18.2317 7.5222 18.0757 7.49621 17.8938 7.49621C17.7898 7.49621 17.6599 7.49621 17.5299 7.49621C17.3999 7.49621 17.296 7.49621 17.166 7.49621H17.088L17.14 7.5742ZM7.8607 6.43053C8.09464 6.43053 8.30258 6.43053 8.53651 6.43053C8.66647 6.43053 8.71845 6.37855 8.79643 6.30057C9.73216 5.1829 10.9538 4.61107 12.4094 4.55909C12.4614 4.55909 12.5393 4.55909 12.5913 4.55909C14.1249 4.55909 15.3985 5.1569 16.3862 6.32656C16.4382 6.37854 16.4902 6.43053 16.5942 6.43053C16.8541 6.43053 17.088 6.43053 17.3479 6.43053H18.2057L18.1537 6.35254C17.088 4.35113 14.9566 3.10349 12.6173 3.10349C12.4354 3.10349 12.2794 3.10351 12.0975 3.12951C9.6022 3.31145 7.78273 4.89697 7.08093 6.35254L7.05494 6.43053H7.8607Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            <span class="position-relative">
                                                {{\App\CPU\translate('Support_Ticket')}}
                                                @if(Schema::hasTable('support_tickets') && \App\Model\SupportTicket::where('status','open')->count()>0)
                                                <span
                                                    class="btn-status btn-xs-status btn-status-danger position-absolute top-0 menu-status"></span>
                                                @endif
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        <!--support section ends here-->

                        <!--System Settings-->
                        @if(\App\CPU\Helpers::module_permission_check('system_settings'))
                        <li
                            class="nav-item {{(Request::is('admin/business-settings/social-media') || Request::is('admin/business-settings/web-config/app-settings') || Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/fcm-index') || Request::is('admin/business-settings/mail')|| Request::is('admin/business-settings/web-config/db-index')||Request::is('admin/business-settings/web-config/environment-setup') || Request::is('admin/business-settings/web-config') || Request::is('admin/business-settings/cookie-settings')  || Request::is('admin/business-settings/all-pages-banner*') || Request::is('admin/business-settings/otp-setup') || Request::is('admin/system-settings/software-update') || Request::is('admin/business-settings/web-config/theme/setup') || Request::is('admin/business-settings/delivery-restriction')) ? 'scroll-here' : '' }}">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('System_Settings')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>



                        <li
                            class="d-none navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/mail') || Request::is('admin/business-settings/sms-module') || Request::is('admin/business-settings/captcha') || Request::is('admin/social-login/view') || Request::is('admin/social-media-chat/view') || Request::is('admin/business-settings/map-api') || Request::is('admin/business-settings/payment-method') || Request::is('admin/business-settings/fcm-index'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.business-settings.sms-module')}}"
                                title="{{\App\CPU\translate('3rd_party')}}">
                                <!-- ================================================== -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M20.9385 6.09782C20.624 5.9329 20.3078 5.84958 20 5.84958C19.5801 5.84958 19.1754 6.0043 18.7963 6.30694C18.1468 6.82891 17.9189 7.51921 18.1196 8.35742C18.1553 8.50364 18.1604 8.67197 17.9376 8.80798C17.2099 9.25174 16.4567 9.72101 15.7018 10.2022C15.6491 10.2362 15.576 10.2753 15.4927 10.2753C15.3754 10.2753 15.2972 10.2056 15.2343 10.1359C14.4131 9.23304 13.3232 8.73658 12.1637 8.73658C11.3748 8.73658 10.5876 8.97121 9.88535 9.41667C9.82585 9.45407 9.75444 9.49318 9.66773 9.49318C9.55041 9.49318 9.4671 9.42176 9.41439 9.36906C8.78361 8.72807 8.13242 8.07348 7.42343 7.37129C7.28061 7.23017 7.26021 7.09246 7.35542 6.91053C7.62916 6.38346 7.72097 5.85129 7.63596 5.28171C7.44043 3.98103 6.28937 3 4.95809 3C4.90369 3 4.84928 3.0017 4.79317 3.0051C3.43299 3.08671 2.30234 4.25818 2.27513 5.61496C2.25983 6.36646 2.53187 7.06525 3.03853 7.58382C3.5367 8.09219 4.23039 8.38463 4.93939 8.38463C5.37125 8.38463 5.7929 8.28092 6.19416 8.07689C6.24347 8.05138 6.30977 8.02418 6.38458 8.02418C6.5019 8.02418 6.58351 8.09218 6.63452 8.14489C7.34011 8.85899 7.993 9.51188 8.62889 10.1393C8.77681 10.2855 8.79381 10.43 8.6833 10.6102C7.77878 12.0741 7.75837 13.5737 8.62209 15.0648C8.7598 15.3029 8.64929 15.4321 8.52177 15.529C8.09502 15.8537 7.63935 16.2006 7.19899 16.5542C7.14459 16.5984 7.05788 16.6579 6.95076 16.6579C6.83855 16.6579 6.75354 16.5967 6.68553 16.5372C6.15336 16.0782 5.53617 15.8452 4.84928 15.8452C4.76597 15.8452 4.68266 15.8486 4.59765 15.8554C3.04874 15.9762 1.93339 17.2717 2.00309 18.8699C2.066 20.3032 3.35818 21.5138 4.81697 21.5138C4.87308 21.5087 4.92749 21.5036 4.9836 21.5002C5.09921 21.4917 5.20803 21.4832 5.31174 21.4628C6.12785 21.2996 6.80624 20.8507 7.2211 20.1995C7.63595 19.5483 7.75667 18.7458 7.56114 17.9348C7.52034 17.7648 7.56454 17.6424 7.70396 17.537C8.38235 17.0269 8.89923 16.6358 9.37869 16.2669C9.4484 16.2142 9.51641 16.187 9.58612 16.187C9.67283 16.187 9.74084 16.2261 9.79014 16.2601C11.1333 17.1816 13.0087 17.1629 14.3672 16.2839C15.5454 15.5222 16.2612 14.1875 16.2289 12.7848C16.2204 12.387 16.1524 11.9925 16.0368 11.6117C15.9722 11.4008 16.0215 11.2648 16.2034 11.1509C17.0144 10.6459 17.8135 10.1393 18.582 9.6462C18.6874 9.57819 18.7793 9.54758 18.8762 9.54758C18.9561 9.54758 19.0377 9.56969 19.1295 9.61559C19.41 9.75671 19.7093 9.82982 20.0187 9.82982C21.0797 9.82982 21.9485 9.01711 21.9961 7.97997C22.042 7.11796 21.6832 6.48547 20.9385 6.09782ZM4.9734 7.29648H4.94959C4.07227 7.28288 3.36328 6.56028 3.37008 5.68637C3.37688 4.79204 4.08757 4.09155 4.98699 4.09155H5.0074C5.87961 4.10175 6.58351 4.82775 6.57501 5.71017C6.56821 6.58409 5.84901 7.29648 4.9734 7.29648ZM4.96659 20.2063H4.94789C4.07398 20.1961 3.36668 19.4752 3.36838 18.5962C3.37178 17.7155 4.09098 16.998 4.9734 16.998H4.9836C5.41205 17.0014 5.81331 17.1714 6.11425 17.4792C6.41689 17.7869 6.58011 18.1916 6.57501 18.62C6.56651 19.4939 5.84391 20.2063 4.96659 20.2063ZM12.1569 15.9047C10.4804 15.9047 9.11345 14.5446 9.10835 12.8732C9.10665 12.0622 9.4212 11.3005 9.99417 10.7241C10.5688 10.1478 11.3357 9.82812 12.1501 9.82812H12.1535C13.8214 9.82812 15.1799 11.1883 15.1816 12.8579C15.1833 14.5327 13.8299 15.8996 12.1569 15.9047ZM20.0306 8.73488C19.7943 8.73488 19.5716 8.64306 19.4032 8.47474C19.2349 8.30641 19.1414 8.08368 19.1414 7.84905C19.1414 7.36619 19.5325 6.97004 20.0136 6.96324H20.0255H20.0272C20.5152 6.96324 20.913 7.36109 20.9147 7.84905C20.9164 8.32172 20.5169 8.72807 20.0306 8.73488Z" fill="white"/>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('3rd_party')}}
                                </span>
                            </a>
                        </li>

                        <li
                            class=" navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/driver-page/*') || Request::is('admin/business-settings/vendor-page/*') || Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/social-media') || Request::is('admin/file-manager*') || Request::is('admin/business-settings/features-section') ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('Pages_&_Media')}}">

                                <!-- ==================================================== -->

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M17.4266 15.2512C17.3932 15.2121 17.3624 15.1698 17.3264 15.1338C16.4941 14.2995 15.6619 13.4656 14.8286 12.6323C14.5056 12.3094 14.1852 12.3104 13.8617 12.6334C13.2862 13.2084 12.708 13.7813 12.1393 14.3631C12.0281 14.4764 11.9665 14.469 11.8648 14.359C11.6707 14.1487 11.4656 13.9478 11.2585 13.75C11.0158 13.5183 10.6809 13.4896 10.4487 13.7145C9.93052 14.2165 9.43012 14.7378 8.92294 15.2512C8.24513 15.9254 7.56367 16.5964 6.8916 17.2763C6.71733 17.4527 6.67976 17.6775 6.78516 17.9097C6.89265 18.1466 7.0831 18.2619 7.34295 18.2625C11.2308 18.2625 15.1187 18.2625 19.0065 18.2625C19.2664 18.2625 19.4563 18.1472 19.5649 17.9108C19.6718 17.6781 19.6306 17.4521 19.4584 17.2773C18.7858 16.5964 18.1044 15.9259 17.4266 15.2512ZM8.89998 17.0321C8.91876 16.9705 8.96625 16.9449 9.00121 16.91C9.52874 16.3788 10.0657 15.856 10.5687 15.3008C10.6099 15.2554 10.6522 15.2084 10.7049 15.1766C10.7988 15.1197 10.8927 15.1432 10.9725 15.2063C11.1379 15.3368 11.3007 15.4599 11.4562 15.6055C11.6044 15.7438 11.7902 15.8737 11.9968 15.8946C12.2514 15.9207 12.4022 15.7469 12.5786 15.5919C13.151 15.0884 13.6749 14.5395 14.2228 14.0109C14.3496 13.8893 14.4038 13.9452 14.5171 14.0584C14.756 14.2974 14.994 14.5364 15.233 14.7753C15.4725 15.0154 15.7167 15.2502 15.9525 15.4933C16.1993 15.748 16.4461 16.0026 16.6929 16.2572C16.8166 16.3845 16.9397 16.5119 17.0634 16.6392C17.1709 16.7498 17.3619 16.8881 17.4224 17.0258C17.2283 17.0618 16.9987 17.031 16.7999 17.031C16.5875 17.031 16.3746 17.031 16.1623 17.031C15.7375 17.031 15.3128 17.031 14.8875 17.031C14.0386 17.031 13.1891 17.031 12.3401 17.031C12.0526 17.0321 8.89998 17.0321 8.89998 17.0321Z" fill="white"/>
                                <path d="M10.8248 12.394C11.7875 12.3955 12.5817 11.6092 12.5864 10.6501C12.5906 9.67595 11.8027 8.87657 10.8358 8.875C9.87571 8.87344 9.07841 9.66343 9.07424 10.6199C9.07006 11.5941 9.85797 12.3924 10.8248 12.394Z" fill="white"/>
                                <path d="M21.943 7.96733C21.8548 7.52276 21.6498 7.13507 21.2292 6.83452C20.8191 6.54127 20.351 6.50057 19.872 6.52979C19.6878 6.54075 19.6404 6.47814 19.6497 6.30229C19.6717 5.87756 19.6555 5.45595 19.4175 5.07869C19.0873 4.4849 18.5681 4.18643 17.9038 4.18278C15.7707 4.1713 13.6382 4.17756 11.5051 4.18069C11.3726 4.18069 11.3079 4.14521 11.2447 4.01685C10.9384 3.39435 10.4276 3.02075 9.73623 3.01292C7.72734 2.99153 5.7174 3.00197 3.70798 3.00718C2.96234 3.00875 2.27097 3.56341 2.07739 4.28766C1.94537 4.7818 2.02521 5.2848 2.01216 5.78363C2.01216 7.19195 2.01216 8.60026 2.01268 10.0081C2.01268 11.1033 2.01268 12.1985 2.01268 13.2943C2.0106 13.7164 2.00642 14.138 2.00694 14.5602C2.00799 15.573 2.0106 16.5852 2.01268 17.598C2.0106 18.0212 2.00381 18.4449 2.00799 18.8681C2.01738 19.825 2.7938 20.6067 3.75233 20.6072C9.25306 20.6103 14.7543 20.6124 20.255 20.6041C20.9558 20.603 21.4755 20.255 21.8068 19.6314C21.9602 19.3429 22.0025 19.011 21.9999 18.6886C21.9915 17.5839 21.9942 16.4783 21.9942 15.3721C21.9942 14.8226 21.9942 14.2732 21.9942 13.7237C21.9942 12.0509 21.991 10.378 21.9988 8.70514C21.9988 8.68844 21.9988 8.67123 21.9988 8.65453C22.0015 8.41398 21.9863 8.18387 21.943 7.96733ZM4.68321 7.15281C4.23186 7.73357 4.3237 8.50164 4.32943 9.20241C4.34248 10.8471 4.36909 12.4923 4.38161 14.137C4.39257 15.5401 4.38579 16.9432 4.37483 18.3463C4.37431 18.4365 4.37222 18.5294 4.36492 18.6207V18.8242C4.36492 19.1509 4.10037 19.4154 3.77373 19.4154C3.44709 19.4154 3.18254 19.1509 3.18254 18.8242V18.4908C3.18254 18.4705 3.18358 18.4501 3.18567 18.4303C3.17367 17.693 3.19454 16.9505 3.20132 16.2121C3.21124 15.1352 3.23941 14.0587 3.24046 12.9817C3.24098 12.5507 3.2415 12.1192 3.24098 11.6882C3.24046 11.3136 3.23941 10.9395 3.23785 10.5648C3.23159 8.64253 3.24098 6.71921 3.24098 4.79641C3.25507 4.45776 3.45857 4.25844 3.79773 4.2407C3.87548 4.23652 3.95375 4.23861 4.03149 4.23861C5.59165 4.23861 7.15128 4.23861 8.71144 4.23861C9.00416 4.23861 9.29637 4.23444 9.58909 4.24018C10.3259 4.25479 10.0295 5.09435 10.5972 5.34742C11.0339 5.54152 11.7337 5.41421 12.2012 5.41421C12.8101 5.41473 13.419 5.41525 14.0285 5.41577C15.2297 5.41629 16.4308 5.41577 17.6315 5.40847C17.9028 5.4069 18.1772 5.46534 18.3171 5.71945C18.4183 5.90312 18.5514 6.47866 18.2148 6.46353C18.1371 6.45988 18.0588 6.46353 17.9805 6.46353C14.0509 6.46353 10.1213 6.46248 6.19171 6.46405C5.61356 6.46509 5.04377 6.68946 4.68321 7.15281ZM20.7878 11.8197C20.7909 13.5166 20.7831 15.214 20.7653 16.9108C20.7601 17.4018 20.7596 17.8923 20.7559 18.3828C20.7549 18.5273 20.7575 18.6828 20.7392 18.8263C20.7038 19.105 20.5911 19.3403 20.2263 19.3685C20.1418 19.3747 20.0573 19.3721 19.9727 19.3721C18.172 19.3721 16.3713 19.3721 14.5706 19.3721C11.802 19.3721 9.0339 19.3721 6.26528 19.3721C6.26267 19.3721 6.26006 19.3732 6.25745 19.3732H5.65896C5.58173 19.3732 5.51912 19.3105 5.51912 19.2333V18.8352C5.51912 18.8347 5.51912 18.8347 5.51912 18.8341C5.51807 17.8088 5.51599 16.7835 5.51442 15.7582C5.51234 14.5205 5.50294 13.2499 5.50346 11.9997C5.50346 11.3564 5.50712 10.713 5.51651 10.0696C5.52538 9.468 5.47007 8.80533 5.59947 8.21779C5.66626 7.91359 5.8969 7.76905 6.19953 7.76174C6.26476 7.76018 6.32946 7.76122 6.39468 7.76122C10.9165 7.76122 15.4384 7.76122 19.9602 7.76122C20.0317 7.76122 20.1032 7.75966 20.1747 7.76279C20.95 7.79201 20.7711 8.74115 20.7752 9.27389C20.782 10.1228 20.7862 10.9713 20.7878 11.8197Z" fill="white"/>
                                </svg>


                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Pages_&_Media')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/business-settings/vendor-page/*') || Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/social-media') || Request::is('admin/file-manager*') || Request::is('admin/business-settings/features-section')?'block':'none'}}">
                                <li
                                    class="nav-item {{(Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list')|| Request::is('admin/business-settings/features-section'))?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.business-settings.terms-condition')}}"
                                        title="{{\App\CPU\translate('pages')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.74609 16.2227C7.74609 15.8084 8.08188 15.4727 8.49609 15.4727H15.7161C16.1303 15.4727 16.4661 15.8084 16.4661 16.2227C16.4661 16.6369 16.1303 16.9727 15.7161 16.9727H8.49609C8.08188 16.9727 7.74609 16.6369 7.74609 16.2227Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.74609 12.0371C7.74609 11.6229 8.08188 11.2871 8.49609 11.2871H15.7161C16.1303 11.2871 16.4661 11.6229 16.4661 12.0371C16.4661 12.4513 16.1303 12.7871 15.7161 12.7871H8.49609C8.08188 12.7871 7.74609 12.4513 7.74609 12.0371Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.74609 7.85938C7.74609 7.44516 8.08188 7.10938 8.49609 7.10938H11.2511C11.6653 7.10938 12.0011 7.44516 12.0011 7.85938C12.0011 8.27359 11.6653 8.60938 11.2511 8.60938H8.49609C8.08188 8.60938 7.74609 8.27359 7.74609 7.85938Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M15.908 2C17.4684 2 18.8017 2.51495 19.7441 3.48761C20.6837 4.45737 21.165 5.80983 21.165 7.357V16.553C21.165 18.0929 20.6872 19.4393 19.7554 20.4075C18.8207 21.3785 17.498 21.8974 15.9496 21.907L15.945 21.907L8.25664 21.91C6.6962 21.91 5.36245 21.395 4.42028 20.4223C3.48095 19.4525 3 18.1001 3 16.553V7.357C3 5.81725 3.47746 4.47105 4.40912 3.50304C5.34357 2.53213 6.66607 2.01355 8.21438 2.00401L8.219 2.00399L15.908 2ZM15.9082 3.5C15.9081 3.5 15.9082 3.5 15.9082 3.5L8.22362 3.50399C8.22294 3.50399 8.22226 3.50399 8.22159 3.504C7.01091 3.51189 6.09959 3.90972 5.48988 4.54321C4.87704 5.17995 4.5 6.12675 4.5 7.357V16.553C4.5 17.7899 4.88005 18.741 5.49772 19.3787C6.11251 20.0134 7.03234 20.41 8.25579 20.41C8.25573 20.41 8.25586 20.41 8.25579 20.41L15.9404 20.407C15.941 20.407 15.9417 20.407 15.9423 20.407C17.1529 20.3991 18.0646 20.0011 18.6746 19.3673C19.2878 18.7302 19.665 17.7831 19.665 16.553V7.357C19.665 6.12017 19.2848 5.16913 18.6669 4.53139C18.0518 3.8966 17.1316 3.50007 15.9082 3.5Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('pages')}}
                                        </span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/business-settings/vendor-page/*') ?'active':''}}">
                                    <a class="nav-link"
                                        href="{{route('admin.business-settings.vendor.page',['vendor-privacy-policy'])}}"
                                        title="{{\App\CPU\translate('assessor_policy_pages')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M14.7366 2.76175H8.08455C6.00455 2.75279 4.29955 4.41079 4.25055 6.49079V17.3398C4.21555 19.3898 5.84855 21.0808 7.89955 21.1168C7.96055 21.1168 8.02255 21.1168 8.08455 21.1148H16.0726C18.1416 21.0938 19.8056 19.4088 19.8026 17.3398V8.03979L14.7366 2.76175Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M14.4727 2.75V5.659C14.4727 7.079 15.6217 8.23 17.0417 8.234H19.7957"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12.9 17H8" stroke="white" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M16 13H8" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('assessor_policy_pages')}}
                                        </span>
                                    </a>
                                </li>

                                <li
                                    class=" d-none nav-item {{ Request::is('admin/business-settings/driver-page/*') ?'active':''}}">
                                    <a class="nav-link"
                                        href="{{route('admin.business-settings.driver.page',['driver-privacy-policy'])}}"
                                        title="{{\App\CPU\translate('Driver_policy_pages')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M7.29592 21.949C5.94388 21.949 5 21.0051 5 19.6786C5 15.3163 5 10.9541 5 6.59184C5 5.23979 5.94388 4.32142 7.27041 4.32142H8.11224V3.88776C8.11224 3.70919 8.11224 3.53062 8.11224 3.37756C8.11224 2.58674 8.72449 2 9.5153 2C10.5357 2 11.5816 2 12.602 2C13.648 2 14.6939 2 15.7653 2C16.5561 2 17.1684 2.61224 17.1684 3.40306C17.1684 3.55612 17.1684 3.73469 17.1684 3.91326V4.34693H18.0102C19.3878 4.34693 20.3061 5.26529 20.3061 6.64284C20.3061 11.0051 20.3061 15.3418 20.3061 19.7041C20.3061 21.0561 19.3622 22 18.0357 22H12.6531L7.29592 21.949ZM7.32143 5.52042C6.58163 5.52042 6.22449 5.90305 6.22449 6.64284V19.653C6.22449 20.3928 6.60714 20.7755 7.34694 20.7755H17.9592C18.75 20.7755 19.1071 20.4184 19.1071 19.6275V6.61734C19.1071 6.54081 19.1071 6.48981 19.1071 6.41328C19.0561 5.95409 18.699 5.59694 18.2653 5.54592C18.1378 5.54592 18.0357 5.54592 17.9082 5.54592C17.8316 5.54592 17.7551 5.54592 17.7041 5.54592H17.6531C17.551 5.54592 17.4745 5.54592 17.398 5.54592H17.2449L17.2194 5.69898C17.0918 6.66837 16.5816 7.10204 15.6378 7.10204H15.2806C15.2041 7.10204 15.1275 7.10204 15.051 7.10204H15C14.3367 7.10204 13.852 6.79593 13.5714 6.15817C13.4949 5.95409 13.2653 5.74999 13.0612 5.62244C12.9337 5.57141 12.8061 5.52042 12.6786 5.52042C12.2959 5.52042 11.9643 5.77552 11.7857 6.15817C11.5561 6.69389 11.2245 7.00001 10.7398 7.05103C10.4847 7.07654 10.2551 7.10204 10 7.10204C9.7449 7.10204 9.51531 7.07654 9.28571 7.05103C8.64796 6.9745 8.26531 6.4898 8.13776 5.67347L8.11224 5.52042H7.32143ZM12.7041 4.29592C13.5459 4.29592 14.3112 4.80612 14.6684 5.59693C14.7194 5.69897 14.8469 5.87754 15 5.87754H15.0765C15.2551 5.87754 15.4082 5.87754 15.5867 5.85203C15.6633 5.85203 15.8929 5.82653 15.8929 5.82653H15.9694V3.14796H9.36225V5.82653C9.36225 5.82653 9.89796 5.82653 9.94898 5.82653C10.0255 5.82653 10.1275 5.82653 10.2041 5.82653C10.2296 5.82653 10.2296 5.82653 10.2551 5.82653C10.5102 5.82653 10.6122 5.67348 10.6888 5.52042C11.0714 4.7296 11.7092 4.29593 12.5765 4.24491C12.6275 4.29593 12.6531 4.29592 12.7041 4.29592Z"
                                                fill="white" />
                                            <path
                                                d="M8.85284 14.1169C8.64876 14.1169 8.44468 14.0404 8.31713 13.9128C8.21508 13.8108 8.16406 13.6577 8.16406 13.5047C8.16406 13.1475 8.44468 12.918 8.87835 12.918H16.5059C16.9396 12.918 17.1947 13.1475 17.2202 13.5047C17.2202 13.6577 17.1692 13.8108 17.0671 13.9128C16.9396 14.0404 16.761 14.1169 16.5314 14.1169H12.7049H8.85284Z"
                                                fill="white" />
                                            <path
                                                d="M8.77447 17.2556C8.41733 17.2556 8.13672 17.0005 8.13672 16.6689C8.13672 16.5158 8.18774 16.3628 8.31529 16.2352C8.44284 16.1077 8.5959 16.0566 8.79998 16.0566C9.3357 16.0566 9.89692 16.0566 10.4326 16.0566H14.8204C15.4071 16.0566 15.9939 16.0566 16.5806 16.0566C16.8612 16.0566 17.0908 16.2352 17.1673 16.4903C17.2439 16.7454 17.1418 17.0005 16.9377 17.1281C16.8102 17.2046 16.6826 17.2301 16.5806 17.2301C15.05 17.2301 13.5194 17.2301 12.0143 17.2301C10.9173 17.2556 9.8459 17.2556 8.77447 17.2556Z"
                                                fill="white" />
                                            <path
                                                d="M8.82733 10.9801C8.62325 10.9801 8.44468 10.9036 8.31713 10.8015C8.21508 10.6995 8.16406 10.5464 8.16406 10.3934C8.16406 10.0618 8.44468 9.80664 8.82733 9.80664C9.08243 9.80664 10.1539 9.80664 10.1539 9.80664H12.6028C13.011 9.80664 13.2661 10.0617 13.2916 10.4189C13.2916 10.572 13.2406 10.725 13.1386 10.827C13.011 10.9546 12.8324 11.0056 12.6283 11.0056C11.9906 11.0056 11.3528 11.0056 10.7406 11.0056C10.1283 11.0056 9.46508 10.9801 8.82733 10.9801Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Driver_policy_pages')}}
                                        </span>
                                    </a>
                                </li>

                                <!-- <li
                                    class="navbar-vertical-aside-has-menu d-none{{Request::is('admin/business-settings/social-media')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.business-settings.social-media')}}"
                                        title="{{\App\CPU\translate('Social_Media_Links')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.558 7.55251V6.08841C21.558 5.31493 21.3646 4.62432 21.0055 4.01659C20.2044 2.69062 18.9889 2 17.442 2C15.8121 2 14.2099 2 12.5801 2H6.03315C5.95028 2 5.8674 2 5.78452 2C5.06629 2.05525 4.37569 2.27625 3.79558 2.66299C3.79558 2.66299 3.76795 2.66298 3.76795 2.69061C3.74033 2.71823 3.71271 2.71825 3.68508 2.74588C3.62984 2.7735 3.6022 2.82876 3.57458 2.85638C3.21546 3.16025 2.58011 3.74033 2.35911 4.34807C2.11049 4.90055 2 5.48067 2 6.11603V9.37571C2 10.7293 2 12.0553 2 13.4088C2 13.6298 2 13.8508 2.02763 14.0718C2.221 15.8674 3.68508 17.3867 5.45304 17.6077C5.83978 17.663 6.22651 17.663 6.61325 17.663H6.86188C7 17.663 7.13812 17.663 7.27624 17.663C7.27624 17.9669 7.27624 18.6022 7.27624 18.6022C7.27624 19.3205 7.27624 20.0387 7.27624 20.7569C7.27624 21.3923 7.52486 21.8066 8.07735 22H8.10497H8.57458L8.65746 21.9448C8.71271 21.8895 8.76795 21.8619 8.85082 21.8066C8.98894 21.6961 9.15469 21.5856 9.29281 21.4475C10.5083 20.1768 11.6961 18.9061 12.8011 17.7182C12.8564 17.663 12.884 17.6354 12.9668 17.6354C13.7679 17.6354 14.5691 17.6354 15.3702 17.6354H17.5801C17.7182 17.6354 17.8563 17.6354 17.9945 17.6077C20.1215 17.3591 21.6133 15.674 21.6133 13.547C21.558 11.558 21.558 9.51384 21.558 7.55251ZM20.453 13.5194C20.453 13.7403 20.4254 13.9337 20.3978 14.0995C20.1492 15.5359 18.9337 16.5304 17.4696 16.558C16.8066 16.558 16.1436 16.558 15.453 16.558C14.5691 16.558 13.6575 16.558 12.7735 16.558C12.4696 16.558 12.1934 16.6685 11.9724 16.9171C10.7569 18.2155 9.5138 19.5138 8.27071 20.8398C8.27071 19.6796 8.27071 18.547 8.27071 17.3868C8.27071 16.8343 7.99447 16.558 7.44198 16.558H6.66851C6.36464 16.558 6.06077 16.558 5.78452 16.558C5.39778 16.558 5.0663 16.4751 4.7348 16.3094C3.60221 15.7569 3.0221 14.8177 2.99448 13.547C2.99448 11.9448 2.99448 10.2873 2.99448 8.71272C2.99448 7.88399 2.99448 7.05525 2.99448 6.25415C2.99448 5.75691 3.07735 5.31494 3.24309 4.9282C3.24309 4.90058 3.27072 4.87294 3.27072 4.84532C3.29834 4.79007 3.32596 4.73483 3.35359 4.67958C3.57458 4.32046 3.98895 3.76798 4.70718 3.40887C5.09392 3.24312 5.53591 3.16024 6.00552 3.16024C7.74585 3.16024 11.9724 3.16024 11.9724 3.16024C13.768 3.16024 15.5635 3.16024 17.3591 3.16024C17.5801 3.16024 17.8011 3.18785 17.9945 3.21548C19.4033 3.46409 20.3978 4.7072 20.3978 6.19891C20.453 8.43648 20.453 10.8674 20.453 13.5194Z"
                                                fill="white" />
                                            <path
                                                d="M14.0428 5.86719C13.3246 5.86719 12.6616 6.14345 12.1367 6.61306C11.9986 6.75119 11.8881 6.86168 11.75 7.02743L11.8605 7.11028L11.75 7.05505L11.7224 7.02743C11.5842 6.86168 11.4461 6.69592 11.2804 6.55779C10.7831 6.11581 10.1201 5.86719 9.42954 5.86719C8.84943 5.86719 8.26932 6.06056 7.79971 6.39206C7.1091 6.88929 6.69473 7.63516 6.61186 8.43626C6.55661 9.26499 6.86048 10.0937 7.49584 10.7291C8.35219 11.5854 9.20854 12.4418 10.0649 13.2981L11.0594 14.2926C11.2804 14.5136 11.5014 14.6241 11.75 14.6241C11.9986 14.6241 12.2196 14.5136 12.4406 14.2926C12.7997 13.9335 13.1588 13.5744 13.5179 13.2153L14.319 12.4142C14.3467 12.3865 14.3743 12.3865 14.3743 12.3589L15.7003 11.033C15.8384 10.8948 15.9765 10.7567 16.1146 10.591C17.1643 9.45837 17.1091 7.74565 16.0317 6.6683C15.4793 6.14344 14.7887 5.86719 14.0428 5.86719ZM15.3135 9.81748C15.2306 9.90035 15.1478 9.98322 15.0649 10.0937L14.8439 10.3147C14.8163 10.3424 14.8163 10.3423 14.7887 10.37C14.6782 10.4805 14.5677 10.5909 14.4572 10.7014L13.0759 12.0827C12.634 12.5246 12.2196 12.939 11.7776 13.381L11.75 13.4086L10.7003 12.3589C9.89915 11.5578 9.09804 10.7291 8.26931 9.92798C7.82733 9.48599 7.63396 8.93348 7.77208 8.35337C7.88258 7.74564 8.26932 7.30366 8.84943 7.08266C9.07042 6.99979 9.29142 6.97216 9.48479 6.97216C9.87153 6.97216 10.2583 7.11031 10.6174 7.41418C10.7279 7.52467 10.8384 7.60754 10.9489 7.71804C11.0041 7.77328 11.0594 7.82853 11.1146 7.88377C11.3356 8.07715 11.5566 8.18764 11.7776 8.18764C11.9986 8.18764 12.2196 8.07715 12.4406 7.88377L12.634 7.69042C12.7445 7.57992 12.8549 7.4694 12.9654 7.35891C13.2693 7.08266 13.6837 6.91692 14.098 6.91692C14.3467 6.91692 14.5953 6.97217 14.8163 7.08266C15.3135 7.30366 15.645 7.74565 15.7555 8.29814C15.8107 8.87825 15.6726 9.43074 15.3135 9.81748Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Social_Media_Links')}}
                                        </span>
                                    </a>
                                </li> -->

                                <!-- <li
                                    class="navbar-vertical-aside-has-menu d-none {{Request::is('admin/file-manager*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.file-manager.index')}}"
                                        title="{{\App\CPU\translate('gallery')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M16.553 3H7.901C4.889 3 3 5.134 3 8.154V16.3C3 19.32 4.881 21.454 7.901 21.454H16.548C19.573 21.454 21.453 19.32 21.453 16.3V8.154C21.457 5.134 19.576 3 16.553 3Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M10.9537 9.03545C10.9537 10.0555 10.1277 10.8815 9.10772 10.8815C8.08872 10.8815 7.26172 10.0555 7.26172 9.03545C7.26172 8.01545 8.08872 7.18945 9.10772 7.18945C10.1267 7.19045 10.9527 8.01645 10.9537 9.03545Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M21.457 15.201C20.534 14.251 18.759 12.332 16.829 12.332C14.898 12.332 13.785 16.565 11.928 16.565C10.071 16.565 8.384 14.651 6.896 15.878C5.408 17.104 4 19.611 4 19.611"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('gallery')}}
                                        </span>
                                    </a>
                                </li> -->
                            </ul>
                        </li>

                        <li
                            class="d-none navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/web-config/environment-setup') || Request::is('admin/business-settings/web-config/mysitemap') || Request::is('admin/business-settings/analytics-index') || Request::is('admin/currency/view') || Request::is('admin/business-settings/web-config/db-index') || Request::is('admin/business-settings/language*') || Request::is('admin/business-settings/web-config/theme/setup')  || Request::is('admin/system-settings/software-update'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                title="{{\App\CPU\translate('System_Setup')}}"
                                href="{{route('admin.business-settings.web-config.environment-setup')}}">


                                <!-- ================================================== -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M21.9946 5.28623C21.9946 4.97924 21.9565 4.67762 21.8137 4.39703C21.3591 3.50636 20.6337 3.04049 19.6345 3.00187C19.5372 2.99796 19.4394 3.00139 19.3421 3.00139C14.375 3.00139 9.40742 3.00187 4.4403 3.00041C4.05069 3.00041 3.67184 3.05614 3.32427 3.23359C2.56461 3.62221 2.07333 4.2098 2 5.09118C2 8.42069 2 11.7502 2 15.0802C2.04497 15.3075 2.04497 15.5431 2.15399 15.7587C2.61301 16.666 3.34676 17.1587 4.36306 17.1656C6.49539 17.1802 8.6282 17.17 10.7605 17.17C10.8255 17.17 10.8906 17.1724 10.9556 17.1705C11.0837 17.1661 11.1472 17.2184 11.1467 17.3533C11.1453 17.7106 11.1477 18.0685 11.1457 18.4258C11.1453 18.5446 11.0944 18.6077 10.9707 18.6072C10.9057 18.6067 10.8407 18.6086 10.7757 18.6086C9.65086 18.6091 8.52604 18.6072 7.40121 18.6101C7.13626 18.6106 7.09373 18.6541 7.09031 18.9156C7.08591 19.2275 7.08884 19.5399 7.08933 19.8518C7.08982 20.279 7.10155 20.2912 7.52391 20.2912C10.5014 20.2917 13.4795 20.2912 16.457 20.2912C16.4893 20.2912 16.522 20.2912 16.5543 20.2907C16.8427 20.2854 16.8838 20.2472 16.8867 19.9647C16.8901 19.6724 16.8877 19.3795 16.8877 19.0867C16.8877 18.6081 16.8872 18.6081 16.4155 18.6081C15.3038 18.6081 14.1917 18.6091 13.0801 18.6072C12.8528 18.6067 12.8332 18.5871 12.8308 18.3637C12.8278 18.0386 12.8337 17.7136 12.8283 17.3885C12.8259 17.2321 12.8841 17.1573 13.0449 17.169C13.1094 17.1739 13.1749 17.1695 13.2399 17.1695C15.3723 17.1695 17.5051 17.171 19.6374 17.169C20.9265 17.1675 22.0054 16.1019 22 14.8817C21.9868 11.6837 21.9966 8.48472 21.9946 5.28623ZM19.4981 15.5485C14.4923 15.5485 9.48661 15.5485 4.48087 15.5485C3.86004 15.5485 3.56038 15.2498 3.55989 14.6314C3.55989 11.6021 3.55989 8.57223 3.55989 5.54287C3.55989 4.92253 3.85858 4.62287 4.47794 4.62287C6.98081 4.62287 9.48368 4.62287 11.9865 4.62287C14.4894 4.62287 16.9923 4.62287 19.4952 4.62287C20.116 4.62287 20.4156 4.92156 20.4161 5.53994C20.4161 8.56929 20.4161 11.5991 20.4161 14.6285C20.4161 15.2488 20.1175 15.5485 19.4981 15.5485Z" fill="white"/>
                                </svg>


                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('System_Setup')}}
                                </span>
                            </a>
                        </li>
                        
                        
                        
                        @endif
                        <li class="navbar-vertical-aside-has-menu">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" href="javascript:" onclick="Swal.fire({
                                    title: '{{\App\CPU\translate('Do_you_want_to_logout')}}?',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#377dff',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: `Yes`,
                                    denyButtonText: `Don't Logout`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{route('admin.auth.logout')}}';
                                    } else{
                                    Swal.fire('Canceled', '', 'info')
                                    }
                                    })">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M15.0174 7.38948V6.45648C15.0174 4.42148 13.3674 2.77148 11.3324 2.77148H6.45744C4.42344 2.77148 2.77344 4.42148 2.77344 6.45648V17.5865C2.77344 19.6215 4.42344 21.2715 6.45744 21.2715H11.3424C13.3714 21.2715 15.0174 19.6265 15.0174 17.5975V16.6545"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M21.8105 12.0215H9.76953" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M18.8828 9.10645L21.8108 12.0214L18.8828 14.9374" stroke="#fff"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate"
                                    title="Sign out">{{ \App\CPU\translate('sign_out')}}</span>
                            </a>
                        </li>
                        <!--System Settings end-->

                        <li class="nav-item pt-5">
                        </li>
                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>

@push('script_2')
<script>
$(window).on('load', function() {
    if ($(".navbar-vertical-content li.active").length) {
        $('.navbar-vertical-content').animate({
            scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
        }, 10);
    }
});

//Sidebar Menu Search
var $rows = $('.navbar-vertical-content .navbar-nav > li');
$('#search-bar-input').keyup(function() {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
});
</script>
@endpush