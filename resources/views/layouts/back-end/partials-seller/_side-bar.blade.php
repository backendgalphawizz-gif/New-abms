<div id="sidebarMain" class="d-none">
    <aside style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset pb-0">
                <div class="navbar-brand-wrapper justify-content-between side-logo">
                    <!-- Logo -->
                    @php($shop=\App\Model\Shop::where(['seller_id'=>auth('seller')->id()])->first())
                    <a class="navbar-brand" href="{{route('seller.dashboard.index')}}" aria-label="Front">
                        @if (isset($shop))
                            <img onerror="this.src='{{asset('public/assets/back-end/img/900x400/img1.jpg')}}'"
                                class="navbar-brand-logo-mini for-seller-logo"
                                src="{{asset("storage/app/public/shop/$shop->image")}}" alt="Logo">
                        @else
                            <img class="navbar-brand-logo-mini for-seller-logo"
                                src="{{asset('public/assets/back-end/img/login-img/semi-logo-for-company.png')}}" alt="Logo">
                        @endif
                    </a>
                    <!-- End Logo -->

                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="d-none js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <!-- <i class="tio-clear tio-lg"></i> -->
                        <img class="navbar-brand-logo-mini for-seller-logo"
                                src="{{asset('public/assets/back-end/img/login-img/semi-logo-for-company.png')}}" alt="Logo">
                    </button>
                    <!-- End Navbar Vertical Toggle -->

                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3">
                        <!-- <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip" data-placement="right" title="" data-original-title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align" data-template="<div class=&quot;tooltip d-none d-sm-block&quot; role=&quot;tooltip&quot;><div class=&quot;arrow&quot;></div><div class=&quot;tooltip-inner&quot;></div></div>" data-toggle="tooltip" data-placement="right" title="" data-original-title="Expand"></i> -->
                    </button>
                </div>

                <!-- Content -->
                <div class="navbar-vertical-content">
                    <!-- Search Form -->
                    <!-- <div class="sidebar--search-form pb-3 pt-4">
                        <div class="search--form-group">
                            <button type="button" class="btn"><i class="tio-search"></i></button>
                            <input type="text" class="js-form-search form-control form--control" id="search-bar-input"
                                   placeholder="{{\App\CPU\translate('search_menu')}}...">
                        </div>
                    </div> -->
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- Dashboards -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/dashboard')?'show':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.dashboard.index')}}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M3 6.5C3 3.87479 3.02811 3 6.5 3C9.97189 3 10 3.87479 10 6.5C10 9.12521 10.0111 10 6.5 10C2.98893 10 3 9.12521 3 6.5Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M14 6.5C14 3.87479 14.0281 3 17.5 3C20.9719 3 21 3.87479 21 6.5C21 9.12521 21.0111 10 17.5 10C13.9889 10 14 9.12521 14 6.5Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M3 17.5C3 14.8748 3.02811 14 6.5 14C9.97189 14 10 14.8748 10 17.5C10 20.1252 10.0111 21 6.5 21C2.98893 21 3 20.1252 3 17.5Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M14 17.5C14 14.8748 14.0281 14 17.5 14C20.9719 14 21 14.8748 21 17.5C21 20.1252 21.0111 21 17.5 21C13.9889 21 14 20.1252 14 17.5Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Dashboard')}}
                                </span>
                            </a>
                        </li>
                        <!-- End Dashboards -->
                        @php($seller = auth('seller')->user())
                        <!-- POS -->
                        @php($sellerId = $seller->id)
                        @php($seller_pos = \App\Model\BusinessSetting::where('type','seller_pos')->value('value') ?? '0')
                        @if ($seller_pos==1)
                            @if ($seller->pos_status == 1)
                                <li class="nav-item">
                                    <small
                                        class="nav-subtitle">{{\App\CPU\translate('pos')}} {{\App\CPU\translate('system')}} </small>
                                    <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                                </li>
                                <li class="navbar-vertical-aside-has-menu {{Request::is('seller/pos')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('seller.pos.index')}}">
                                        <i class="tio-shopping"></i>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('create order')}}</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                        <!-- End POS -->

                        <li class="nav-item">
                            <small class="nav-subtitle">{{\App\CPU\translate('order_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <!-- Pages -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/orders*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:" title="  {{\App\CPU\translate('Order_Management')}}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M2.75 3.25L4.83 3.61L5.793 15.083C5.87 16.02 6.653 16.739 7.593 16.736H18.502C19.399 16.738 20.16 16.078 20.287 15.19L21.236 8.632C21.342 7.899 20.833 7.219 20.101 7.113C20.037 7.104 5.164 7.099 5.164 7.099" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.375 10.7949C13.375 10.3807 13.7108 10.0449 14.125 10.0449H16.898C17.3122 10.0449 17.648 10.3807 17.648 10.7949C17.648 11.2091 17.3122 11.5449 16.898 11.5449H14.125C13.7108 11.5449 13.375 11.2091 13.375 10.7949Z" fill="white"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.15337 19.4521C6.43726 19.4521 5.85938 20.0328 5.85938 20.7461C5.85938 21.4596 6.43637 22.0411 7.15337 22.0411C7.87038 22.0411 8.44737 21.4596 8.44737 20.7461C8.44737 20.0328 7.86949 19.4521 7.15337 19.4521ZM18.4346 19.4521C17.7185 19.4521 17.1406 20.0328 17.1406 20.7461C17.1406 21.4596 17.7176 22.0411 18.4346 22.0411C19.1498 22.0411 19.7296 21.4614 19.7296 20.7461C19.7296 20.031 19.1489 19.4521 18.4346 19.4521Z" fill="white"></path>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Order_Management')}}
                                </span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('seller/order*')?'block':'none'}}">

                                <li class="nav-item {{Request::is('seller/orders/list/all')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['all'])}}" title="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                          <path d="M11.8743 22.0457C9.45143 22.0457 7.02857 22.0457 4.60571 22.0457C3.05143 22.0457 2 20.9943 2 19.44C2 17.7029 2 15.9657 2 14.2057V12.0343V9.77143C2 8.03429 2 6.29714 2 4.53714C2 3.21143 2.8 2.25143 4.10286 2.02286C4.26286 2 4.44571 2 4.62857 2C7.6 2 10.5714 2 13.5429 2H19.3714C20.6971 2 21.6571 2.73143 21.9314 3.96571C21.9771 4.14857 22 4.37714 22 4.62857V9.54286C22 12.8343 22 16.1029 22 19.3943C22 20.88 20.8343 22.0229 19.3714 22.0229C16.88 22.0457 14.3886 22.0457 11.8743 22.0457ZM4.67429 3.53143C3.89714 3.53143 3.53143 3.89714 3.53143 4.67428C3.53143 9.56571 3.53143 14.48 3.53143 19.3943C3.53143 20.1714 3.89714 20.5143 4.65143 20.5143H19.3714C20.1486 20.5143 20.4914 20.1714 20.4914 19.3943V4.65143C20.4914 3.89714 20.1257 3.53143 19.3714 3.53143H12.0343H4.67429Z" fill="white"/>
                                          <path d="M10.8017 15.8059C10.756 15.8059 10.6874 15.8059 10.6417 15.7831C10.4131 15.7145 10.2303 15.5317 10.0703 15.3717C9.15599 14.4802 8.21885 13.5888 7.28171 12.7431C6.96171 12.4459 6.61885 12.1259 6.64171 11.7145C6.64171 11.3488 6.98457 11.0059 7.35028 11.0059C7.37314 11.0059 7.39599 11.0059 7.41885 11.0059C7.71599 11.0288 7.94456 11.2345 8.19599 11.4631C8.44742 11.7145 8.72171 11.9431 8.97314 12.1717C9.11028 12.2859 9.24742 12.4002 9.36171 12.5145L9.95599 13.0631C10.0017 13.0859 10.0246 13.1317 10.0703 13.1774C10.2531 13.3602 10.5046 13.6117 10.7789 13.6117C10.8017 13.6117 10.8246 13.6117 10.8474 13.6117C10.9617 13.5888 11.0531 13.5202 11.1217 13.4517C11.3274 13.2688 11.5331 13.0631 11.716 12.8574C11.8074 12.7659 11.876 12.6745 11.9674 12.6059L15.0989 9.40595L15.9674 8.51452C16.1503 8.33166 16.3331 8.24023 16.5388 8.24023C16.7217 8.24023 16.9046 8.30881 17.0417 8.46881C17.3617 8.78881 17.3617 9.22309 17.0417 9.56595C16.4474 10.1831 15.8531 10.7774 15.2589 11.3717L15.2131 11.4174C13.956 12.6974 12.676 14.0231 11.396 15.3717C11.3731 15.3945 11.3503 15.4402 11.3046 15.4631C11.1674 15.6231 11.0303 15.7602 10.8246 15.7831C10.8474 15.8059 10.8246 15.8059 10.8017 15.8059Z" fill="white"/>
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('All')}}
                                            <span class="badge badge-soft-info badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/pending')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['pending'])}}" title="">
                                         <img src="{{ asset('public/assets/front-end/img/confirmationicon.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Pending')}}
                                            <span class="badge badge-soft-info badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'pending'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/confirmed')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['confirmed'])}}" title="">
                                         <img src="{{ asset('public/assets/front-end/img/confirmationicon.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('confirmed')}}
                                            <span
                                                class="badge badge-soft-info badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'confirmed'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="d-none nav-item {{Request::is('seller/orders/list/processing')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['processing'])}}" title="">
                                         <img src="{{ asset('public/assets/front-end/img/package-icon.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Packaging')}}
                                            <span class="badge badge-soft-warning badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'processing'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/orders/list/shipped')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['shipped'])}}" title="">
                                         <img src="{{ asset('public/assets/front-end/img/package-icon.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Shipped')}}
                                            <span class="badge badge-soft-warning badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'shipped'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/orders/list/out_for_delivery')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['out_for_delivery'])}}"
                                       title="">
                                        <img src="{{ asset('public/assets/front-end/img/out-of-the-box.png') }}">

                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Out_For_Delivery')}}
                                            <span class="badge badge-soft-warning badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'out_for_delivery'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/orders/list/delivered')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['delivered'])}}" title="">
                                            <img src="{{ asset('public/assets/front-end/img/box.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Delivered')}}
                                            <span class="badge badge-soft-success badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'delivered'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/returned')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['returned'])}}" title="">
                                         <img src="{{ asset('public/assets/front-end/img/product-return.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Returned')}}
                                            <span class="badge badge-soft-danger badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'returned'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/failed')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['failed'])}}" title="">
                                        <img src="{{ asset('public/assets/front-end/img/cancel.png') }}">

                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Failed To Deliver')}}
                                            <span class="badge badge-soft-danger badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'failed'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/canceled')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['canceled'])}}" title="">
                                          <img src="{{ asset('public/assets/back-end/img/cancel-2.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('canceled')}}
                                            <span class="badge badge-soft-danger badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'canceled'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/delivery-man/assign-bulk-order')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.delivery-man.assign-bulk-order')}}" title="">
                               <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                          <path d="M12.1992 8.32715V15.6535" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                          <path d="M15.8646 11.9907H8.53125" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M16.8849 2H7.5135C4.24684 2 2.19922 4.31208 2.19922 7.58516V16.4148C2.19922 19.6879 4.23731 22 7.5135 22H16.8849C20.1611 22 22.1992 19.6879 22.1992 16.4148V7.58516C22.1992 4.31208 20.1611 2 16.8849 2Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Assign_bulk_order')}}

                                        </span>
                                    </a>
                                </li>
                                <li class=" d-none navbar-vertical-aside-has-menu {{Request::is('seller/refund*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link its-drop"
                                    href="javascript:">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M21.8594 21.1341C21.6472 20.8335 21.3289 20.8335 21.2228 20.8335H21.2051C20.9399 20.8335 20.6746 20.8335 20.4271 20.8335H19.4898V15.44H20.4094C20.6746 15.44 20.9399 15.44 21.2051 15.44H21.2228C21.3289 15.44 21.6472 15.44 21.8594 15.1394L21.9125 15.0686V14.6796L21.8594 14.6089C21.6649 14.3259 21.3996 14.3082 21.2051 14.3082C20.8515 14.3082 20.5155 14.3082 20.1618 14.3082C19.7728 14.3082 19.3661 14.3082 18.977 14.3082C18.924 14.3082 18.8532 14.2906 18.8179 14.2729C18.0044 13.7777 17.1202 13.5302 16.2361 13.5302C16.1477 13.5302 16.0592 13.5302 15.9708 13.5302C15.3696 13.5655 14.7683 13.6893 14.0256 13.9369L13.7958 14.0253C13.2653 14.2022 12.7701 14.379 12.2396 14.4144C11.4262 14.4497 10.6127 14.4674 9.85234 14.4851C9.41026 14.4851 8.89743 14.5558 8.47303 14.9272L7.51812 14.3436C6.72237 13.8662 5.87356 13.3533 5.02475 12.8582C4.70645 12.6814 4.40583 12.5399 4.1229 12.4692C3.96375 12.4161 3.78691 12.3984 3.62776 12.3984C2.88505 12.3984 2.26613 12.8936 2.05393 13.6716C1.8771 14.3436 2.14235 15.0333 2.74359 15.4754C2.95579 15.6345 3.16799 15.7937 3.39788 15.9528C4.21132 16.5541 4.95402 17.1022 5.71441 17.6151C6.95225 18.4639 8.03094 19.1535 9.02122 19.7548C10.1706 20.4444 11.4615 20.7981 12.8585 20.7981C12.9116 20.7981 12.9469 20.7981 13 20.7981C13.5659 20.7804 14.2202 20.7804 15.0867 20.7804C15.6525 20.7804 16.2007 20.7804 16.7666 20.7804H16.8373C17.3501 20.7804 17.863 20.7804 18.3581 20.7804V21.3286C18.3581 21.7707 18.5703 22.0006 19.0124 22.0006H20.2325C20.5685 22.0006 20.8868 22.0006 21.2228 22.0006H21.2405C21.3466 22.0006 21.6649 22.0006 21.8771 21.6999L21.9301 21.6292V21.2402L21.8594 21.1341ZM3.09725 14.1314C3.07957 13.9899 3.13262 13.8308 3.25641 13.6716C3.34482 13.5479 3.45093 13.4948 3.55703 13.4948C3.59239 13.4948 3.62776 13.4948 3.68081 13.5125C3.94606 13.5832 4.17595 13.6716 4.35278 13.7777C5.53757 14.4674 6.70468 15.1747 7.97789 15.9528C7.94253 16.5894 8.24315 17.0846 8.86207 17.4205C9.49867 17.7565 10.1883 17.8273 10.7188 17.8626C11.4615 17.898 12.2042 17.9157 12.9293 17.951L14.6269 18.0218C14.6622 18.0218 14.6976 18.0218 14.7507 18.0218C15.0866 18.0218 15.3342 17.7919 15.3519 17.4736C15.3696 17.1376 15.122 16.89 14.7683 16.8724C14.2555 16.837 13.7427 16.8193 13.2299 16.8016C12.9293 16.7839 12.6286 16.7839 12.328 16.7663H12.2927C11.6561 16.7486 11.0018 16.7309 10.3652 16.6778C10.0292 16.6602 9.69319 16.5187 9.3572 16.3949C9.18037 16.3242 9.10964 16.2004 9.12732 16.0059C9.16269 15.6876 9.30416 15.6522 9.42794 15.6345C9.62246 15.6168 9.83466 15.5991 10.0999 15.5991C10.2237 15.5991 10.3652 15.5991 10.4889 15.5991C10.6304 15.5991 10.7719 15.5991 10.931 15.5991C11.6914 15.5991 12.3103 15.5461 12.8939 15.4046C13.2652 15.3162 13.6189 15.2101 13.9726 15.0863L14.008 15.0686C14.1671 15.0156 14.3263 14.9625 14.5031 14.9095C14.9982 14.768 15.5818 14.6089 16.2007 14.6089C16.3599 14.6089 16.5013 14.6266 16.6428 14.6442C17.244 14.715 17.8099 14.9449 18.3404 15.2808C18.3404 16.3949 18.3404 17.509 18.3404 18.623V19.4365V19.5779C17.8276 19.5779 17.3324 19.5779 16.8196 19.5779C16.2891 19.5779 15.7586 19.5779 15.2281 19.5779C14.397 19.5779 13.725 19.5779 13.1415 19.5956C13.0884 19.5956 13.0177 19.5956 12.9646 19.5956C11.6737 19.5956 10.4536 19.2419 9.21574 18.5169C7.80106 17.6858 6.45712 16.7132 5.14854 15.7583L5.06012 15.6876C4.47657 15.2632 3.87533 14.8211 3.43324 14.4851C3.22104 14.4144 3.13262 14.2729 3.09725 14.1314Z" fill="white"/>
                                        <path d="M9.83409 10.3996L11.3018 12.1149C11.4256 12.2564 11.5848 12.4156 11.8146 12.4156C12.0445 12.4156 12.2214 12.2564 12.3275 12.1326L17.491 6.08488C17.5971 5.9611 17.7916 5.73121 17.6502 5.39523C17.491 5.04156 17.155 5.04156 17.0136 5.04156C17.0136 5.04156 16.3947 5.04156 16.094 5.04156H15.0507V4.22812C15.0507 3.7153 15.0507 3.20248 15.0507 2.70734C15.0507 2.60124 15.0507 2.26525 14.7501 2.05305L14.6794 2H8.9676L8.89686 2.05305C8.57856 2.26525 8.57856 2.60124 8.59624 2.70734C8.59624 3.29089 8.59624 3.87445 8.59624 4.458V5.04156H7.53524C7.23462 5.04156 6.91632 5.04156 6.59801 5.04156C6.45655 5.04156 6.12056 5.04156 5.96141 5.39523C5.81994 5.71353 6.03214 5.9611 6.12056 6.08488C7.37608 7.51724 8.61393 8.9496 9.83409 10.3996ZM9.72798 5.43059V3.13174H13.9013V5.48364C13.9013 5.9611 14.1135 6.1733 14.5909 6.1733H15.8995L11.797 10.9655L7.69439 6.1733H8.94991C9.55115 6.1733 9.72798 5.99646 9.72798 5.43059Z" fill="white"/>
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Refund_Request_List')}}
                                        </span>
                                    </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('seller/refund*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('seller/refund/list/pending')?'active':''}} d-none">
                                        <a class="nav-link"
                                        href="{{route('seller.refund.list',['pending'])}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M21.25 12.0005C21.25 17.1095 17.109 21.2505 12 21.2505C6.891 21.2505 2.75 17.1095 2.75 12.0005C2.75 6.89149 6.891 2.75049 12 2.75049C17.109 2.75049 21.25 6.89149 21.25 12.0005Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M15.4302 14.9427L11.6602 12.6937V7.84668" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('pending')}}
                                                <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::whereHas('order', function ($query) {
                                                        $query->where('seller_is', 'seller')->where('seller_id',auth('seller')->id());
                                                            })->where('status','pending')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('seller/refund/list/approved')?'active':''}}">
                                        <a class="nav-link"
                                        href="{{route('seller.refund.list',['approved'])}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M22.0015 11.8471C21.9478 11.6907 21.927 11.5343 22.0015 11.3779C22.0015 11.5343 22.0015 11.6907 22.0015 11.8471Z" fill="#383838"/>
                                                <path d="M22.0015 12.6288C21.927 12.4724 21.9478 12.316 22.0015 12.1597C22.0015 12.316 22.0015 12.4724 22.0015 12.6288Z" fill="#383838"/>
                                                <path d="M11.3906 22.007C11.547 21.9325 11.7022 21.9544 11.8585 22.007C11.7034 22.007 11.547 22.007 11.3906 22.007Z" fill="#3C3C3C"/>
                                                <path d="M22 11.2607C21.9585 11.031 21.978 10.7977 21.9462 10.5667C21.7764 9.32792 21.3696 8.16972 20.7673 7.07628C20.3152 6.2565 19.7459 5.52102 19.0874 4.86373C18.4655 4.24309 17.7704 3.7092 17.0007 3.27426C15.9842 2.70005 14.9079 2.30299 13.7582 2.09896C13.4003 2.03543 13.0386 2.04276 12.6794 2C12.6538 2 12.6269 2 12.6013 2C12.4583 2.03176 12.3154 2.03176 12.1724 2C12.0686 2 11.9647 2 11.8609 2C11.7179 2.03176 11.575 2.03176 11.4321 2C11.3795 2 11.3282 2 11.2757 2C11.0582 2.03543 10.8371 2.01955 10.6208 2.04765C9.5616 2.18937 8.55123 2.49969 7.59584 2.97372C6.4975 3.51861 5.52868 4.24065 4.68935 5.13984C3.95387 5.92785 3.37477 6.81238 2.90807 7.78243C2.10051 9.46353 1.89526 11.2448 2.04553 13.075C2.14816 14.3333 2.53178 15.5184 3.11577 16.6424C3.73152 17.825 4.54763 18.8403 5.56289 19.6979C6.93977 20.861 8.51947 21.5941 10.2934 21.9105C10.6062 21.9667 10.9238 21.9594 11.2366 22.007C11.2891 22.007 11.3404 22.007 11.393 22.007C11.5493 21.974 11.7045 21.974 11.8609 22.007C11.9647 22.007 12.0686 22.007 12.1724 22.007C12.3154 21.9557 12.4583 21.93 12.6013 22.007C12.6403 22.007 12.6794 22.007 12.7185 22.007C12.9482 21.9704 13.1816 21.9899 13.4113 21.9594C14.4681 21.8164 15.4784 21.5085 16.4338 21.0357C17.7007 20.4078 18.793 19.5538 19.7007 18.4652C20.8552 17.0822 21.5895 15.505 21.9035 13.7298C21.9585 13.4158 21.9524 13.0982 22 12.7854C22 12.7329 22 12.6816 22 12.629C21.967 12.4726 21.967 12.3163 22 12.1599C22 12.056 22 11.951 22 11.8471C21.967 11.6907 21.967 11.5344 22 11.378C22 11.3389 22 11.2998 22 11.2607ZM12.0209 20.4432C7.37348 20.4469 3.59713 16.6668 3.59346 12.0072C3.5898 7.35116 7.36249 3.56625 12.0124 3.56259C16.6598 3.55892 20.4362 7.33895 20.4399 11.9986C20.4435 16.6558 16.6708 20.4395 12.0209 20.4432Z" fill="white"/>
                                                <path d="M10.6729 15.4034C10.4115 15.4059 10.2209 15.3057 10.0584 15.1432C9.24961 14.3332 8.4396 13.5257 7.63448 12.712C7.29362 12.3675 7.28507 11.902 7.60028 11.5807C7.91182 11.263 8.38585 11.2655 8.73037 11.6051C9.32291 12.1891 9.91056 12.7792 10.4921 13.3742C10.6069 13.4914 10.6631 13.5061 10.789 13.379C12.2722 11.8824 13.7627 10.3944 15.2495 8.90264C15.4926 8.65951 15.7651 8.5349 16.1096 8.64241C16.6117 8.79879 16.8145 9.39744 16.5103 9.82871C16.4578 9.90201 16.3943 9.9692 16.3295 10.0327C14.6496 11.7175 12.9673 13.401 11.2874 15.087C11.1091 15.2654 10.9148 15.4059 10.6729 15.4034Z" fill="white"/>
                                                </svg>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('approved')}}
                                                <span class="badge badge-soft-info badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::whereHas('order', function ($query) {
                                                        $query->where('seller_is', 'seller')->where('seller_id',auth('seller')->id());
                                                            })->where('status','approved')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('seller/refund/list/refunded')?'active':''}}">
                                        <a class="nav-link"
                                        href="{{route('seller.refund.list',['refunded'])}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M11.8911 22C11.736 21.8759 11.5699 21.7628 11.4278 21.6247C9.78639 20.0254 8.14903 18.423 6.51066 16.8207C6.46862 16.7796 6.42559 16.7396 6.38656 16.6956C6.22142 16.5074 6.17338 16.2922 6.27546 16.062C6.37655 15.8329 6.5617 15.7108 6.81391 15.7098C7.46446 15.7067 8.116 15.7087 8.76654 15.7087C8.8366 15.7087 8.90666 15.7087 8.98873 15.7087C8.99273 15.6497 8.99873 15.6057 8.99873 15.5616C8.99974 14.852 8.99673 14.1424 9.00174 13.4329C9.00274 13.3228 8.96771 13.2617 8.87363 13.1997C6.94302 11.9326 5.9602 10.1461 6.00123 7.83318C6.04927 5.07187 8.19707 2.61082 10.9013 2.1054C14.167 1.49489 17.2696 3.59965 17.9702 6.7863C18.5047 9.21633 17.4188 11.8215 15.271 13.1676C15.1399 13.2497 15.0888 13.3348 15.0908 13.4909C15.0998 14.2195 15.0948 14.9481 15.0948 15.7087C15.1709 15.7087 15.2399 15.7087 15.308 15.7087C15.9455 15.7087 16.5841 15.7077 17.2216 15.7087C17.5248 15.7098 17.734 15.8359 17.8261 16.0791C17.9262 16.3413 17.8481 16.5625 17.654 16.7526C15.9365 18.432 14.2191 20.1114 12.4987 21.7888C12.4126 21.8719 12.3015 21.9299 12.2024 22C12.0993 22 11.9952 22 11.8911 22ZM12.0533 3.17029C9.36504 3.16728 7.16921 5.35711 7.16621 8.04535C7.1632 10.7336 9.35503 12.9304 12.0423 12.9334C14.7305 12.9364 16.9273 10.7436 16.9294 8.05737C16.9314 5.36812 14.7425 3.17429 12.0533 3.17029ZM12.0483 20.5948C13.3123 19.3588 14.5614 18.1378 15.8454 16.8817C15.3921 16.8817 14.9917 16.8827 14.5904 16.8817C14.16 16.8807 13.9238 16.6475 13.9228 16.2212C13.9218 15.4926 13.9228 14.763 13.9228 14.0344C13.9228 13.9673 13.9228 13.8992 13.9228 13.8112C12.6598 14.2045 11.4238 14.2025 10.1717 13.8112C10.1717 13.9052 10.1717 13.9763 10.1717 14.0464C10.1717 14.769 10.1727 15.4916 10.1717 16.2142C10.1707 16.6445 9.93752 16.8807 9.51116 16.8827C9.17288 16.8837 8.8346 16.8827 8.49531 16.8827C8.42826 16.8827 8.3612 16.8827 8.25011 16.8827C9.53618 18.1388 10.7822 19.3568 12.0483 20.5948Z" fill="white"/>
                                                </svg>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('refunded')}}
                                                <span class="badge badge-soft-success badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::whereHas('order', function ($query) {
                                                        $query->where('seller_is', 'seller')->where('seller_id',auth('seller')->id());
                                                            })->where('status','refunded')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('seller/refund/list/rejected')?'active':''}}">
                                        <a class="nav-link"
                                        href="{{route('seller.refund.list',['rejected'])}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M12.0132 2C6.51569 1.99498 2.00881 6.46791 2.00001 11.939C1.99121 17.4931 6.44906 21.9899 11.9755 22C17.4919 22.0088 21.9937 17.5321 21.9987 12.0295C22.005 6.49683 17.5333 2.00629 12.0132 2ZM11.9755 20.6661C7.19832 20.6485 3.32379 16.7577 3.33385 11.9855C3.34391 7.19957 7.24358 3.3175 12.0245 3.33384C16.8029 3.35018 20.6775 7.24232 20.6662 12.0145C20.6548 16.8017 16.7552 20.6837 11.9755 20.6661Z" fill="white"/>
                                                <path d="M7.3719 15.9733C7.36813 15.747 7.46744 15.5886 7.61075 15.4466C8.71202 14.3478 9.80825 13.2453 10.9133 12.1516C11.039 12.0271 11.0327 11.9681 10.9108 11.8486C9.80574 10.7537 8.70824 9.65365 7.6095 8.55238C7.32412 8.26701 7.28893 7.89489 7.51395 7.61706C7.7679 7.30278 8.21796 7.28392 8.52219 7.58312C9.0263 8.0797 9.52539 8.58255 10.0257 9.0829C10.6392 9.69639 11.2565 10.3074 11.8637 10.9259C11.9731 11.0378 12.0271 11.0365 12.1365 10.9259C13.2415 9.81205 14.3516 8.70324 15.4617 7.59569C15.7345 7.32289 16.1091 7.29272 16.3819 7.51398C16.6962 7.76918 16.7163 8.21672 16.4158 8.52221C15.9783 8.9685 15.5333 9.40724 15.092 9.8485C14.4195 10.5211 13.7507 11.1962 13.0718 11.8625C12.9587 11.9731 12.9637 12.0271 13.0731 12.1353C14.1819 13.2353 15.2831 14.3428 16.3894 15.4453C16.5943 15.6502 16.6811 15.8841 16.5969 16.1619C16.4561 16.627 15.8853 16.7817 15.5195 16.4573C15.3799 16.3329 15.253 16.1946 15.1197 16.0613C14.1228 15.0644 13.1246 14.07 12.134 13.0693C12.0208 12.9549 11.968 12.965 11.8612 13.0731C10.7612 14.1819 9.65488 15.2844 8.54984 16.3882C8.22424 16.7125 7.77293 16.7062 7.5127 16.3781C7.4159 16.2574 7.36184 16.1166 7.3719 15.9733Z" fill="white"/>
                                            </svg>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('rejected')}}
                                                <span class="badge badge-danger badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::whereHas('order', function ($query) {
                                                        $query->where('seller_is', 'seller')->where('seller_id',auth('seller')->id());
                                                            })->where('status','rejected')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="d-none navbar-vertical-aside-has-menu {{Request::is('seller/report/order-report')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('seller.report.order-report')}}"
                                   title="{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Report')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                          <path d="M7.48549 6.96064C7.66189 6.96064 7.86035 6.96064 8.03675 6.96064C8.38957 6.96064 8.72033 6.96064 9.02904 6.93859C9.42595 6.91654 9.75671 6.58578 9.73466 6.21092C9.71261 5.83605 9.4039 5.52734 8.98494 5.52734C8.91878 5.52734 8.85263 5.52734 8.78648 5.52734C8.67623 5.52734 8.56597 5.52734 8.45572 5.52734C8.38957 5.52734 8.30137 5.52734 8.23521 5.52734H7.9706C7.81625 5.52734 7.63984 5.52734 7.48549 5.52734C7.08857 5.54939 6.75781 5.8581 6.75781 6.23297C6.75781 6.62988 7.08857 6.93859 7.48549 6.96064Z" fill="white"/>
                                          <path d="M20.8247 7.00551C20.2293 4.02867 17.8038 2.02205 14.7828 2.02205C11.8721 2 8.93936 2 6.44763 2C6.22712 2 6.00662 2.02205 5.80816 2.06615C4.74973 2.30871 4.0882 3.19074 4.06615 4.31533C4.06615 5.52811 4.06615 6.7409 4.0441 7.93164L4.02205 14.2602C4.02205 16.0243 4 17.8104 4 19.5965C4 20.9857 4.97023 21.9559 6.35943 21.9559C10.4168 21.9779 14.4741 21.9779 18.5094 22H18.5755C19.9206 21.9779 20.8688 21.0077 20.8688 19.6406V19.4201V17.7663C20.8909 14.613 20.8909 11.3936 20.8909 8.2183C20.935 7.75524 20.8909 7.35832 20.8247 7.00551ZM5.5215 4.31533C5.5215 3.74201 5.85226 3.41125 6.42558 3.41125H6.64609V3.34509L6.66814 3.41125C9.40243 3.41125 12.1147 3.4333 14.849 3.4333C15.0033 3.4333 15.1577 3.45535 15.3341 3.45535H15.3561C15.4443 3.45535 15.5325 3.4774 15.6207 3.4774H15.6648V7.22602C15.6648 8.19625 16.2823 8.83572 17.2525 8.83572H19.3694C19.3694 8.83572 19.3914 8.83572 19.4135 8.83572L19.4796 8.85777V8.96803C19.4796 9.03418 19.4796 9.07828 19.4796 9.14443C19.4796 12.6284 19.4576 16.1125 19.4355 19.6185C19.4355 20.2139 19.1047 20.5447 18.5094 20.5447L6.35943 20.5006C6.07277 20.5006 5.83021 20.4123 5.67586 20.258C5.5215 20.1036 5.4333 19.8611 5.4333 19.5744L5.5215 4.31533ZM17.0981 7.40243L17.1202 4.02867L17.1863 4.07277C18.4212 4.84454 19.1709 5.92503 19.4576 7.35833V7.40243H17.0981Z" fill="white"/>
                                          <path d="M9.18352 11.2838C8.6543 11.2838 8.14714 11.2838 7.61792 11.3058C7.35331 11.3058 7.11075 11.4381 6.97845 11.6366C6.84614 11.813 6.82409 12.0335 6.9123 12.254C7.02255 12.5407 7.28716 12.7171 7.66202 12.7171C8.25739 12.7171 8.85276 12.7171 9.44813 12.695H10.1758H11.0137C11.3665 12.695 11.7414 12.695 12.0942 12.673C12.3588 12.673 12.6014 12.5407 12.7337 12.3422C12.866 12.1658 12.888 11.9453 12.7998 11.7248C12.6896 11.4381 12.425 11.2617 12.0281 11.2617C11.5209 11.2617 10.9917 11.2617 10.4625 11.2838H9.18352Z" fill="white"/>
                                          <path d="M9.07261 8.19588H8.65365C8.32289 8.19588 7.94803 8.19588 7.59522 8.21793C7.44086 8.21793 7.26446 8.26203 7.13215 8.35023C6.86754 8.48254 6.75729 8.8133 6.82344 9.09996C6.91164 9.40867 7.1542 9.60712 7.48496 9.62918H7.55111C8.41109 9.62918 9.38132 9.60713 10.528 9.58507C10.6382 9.58507 10.7485 9.54097 10.8808 9.49687C11.1454 9.36457 11.3218 9.05586 11.2556 8.7692C11.1674 8.39433 10.9028 8.17383 10.5059 8.17383H10.3516C10.0649 8.17383 9.77824 8.17383 9.49158 8.17383L9.07261 8.19588Z" fill="white"/>
                                          <path d="M12.5547 18.1412C12.5547 18.3176 12.6429 18.494 12.7752 18.6263C12.9075 18.7586 13.0839 18.8468 13.2603 18.8468H13.2824C13.6352 18.8468 13.988 18.494 13.988 18.1412C13.988 17.7884 13.6572 17.4355 13.2824 17.4355C12.9075 17.4576 12.5547 17.7884 12.5547 18.1412Z" fill="white"/>
                                          <path d="M16.3281 18.8683H16.3502C16.703 18.8683 17.0337 18.5375 17.0558 18.1847C17.0558 18.0083 16.9896 17.8539 16.8573 17.6996C16.725 17.5452 16.5266 17.457 16.3502 17.457C16.1737 17.457 15.9973 17.5452 15.865 17.6775C15.7327 17.8098 15.6445 17.9862 15.6445 18.1627C15.6445 18.5155 15.9973 18.8462 16.3281 18.8683Z" fill="white"/>
                                        </svg>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                 {{\App\CPU\translate('Order_Report')}}
                                </span>
                                </a>
                            </li>
                            </ul>
                        </li>


                        <!-- End Pages -->

                        <li class="nav-item">
                            <small class="nav-subtitle">{{\App\CPU\translate('product_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('seller/product*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:" title=" {{\App\CPU\translate('Product_Management')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M15.7729 9.30507V6.27307C15.7729 4.18907 14.0839 2.50004 12.0009 2.50004C9.91694 2.49107 8.21994 4.17207 8.21094 6.25607V6.27307V9.30507" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.7422 21.0004H7.25778C4.90569 21.0004 3 19.0954 3 16.7454V11.2294C3 8.87936 4.90569 6.97437 7.25778 6.97437H16.7422C19.0943 6.97437 21 8.87936 21 11.2294V16.7454C21 19.0954 19.0943 21.0004 16.7422 21.0004Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Product_Management')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('seller/product*'))?'block':''}}">
                                <li class="nav-item {{Request::is('seller/product/list') || Request::is('seller/product/stock-limit-list/in_house')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.product.list')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M15.0424 9.48466C14.2164 9.48466 13.3653 9.48466 12.5393 9.48466C12.0637 9.48466 11.7383 9.18428 11.7383 8.75875C11.7383 8.33322 12.0637 8.00781 12.5393 8.00781C14.2164 8.00781 15.8935 8.00781 17.5455 8.00781C17.9711 8.00781 18.2965 8.25813 18.3465 8.65863C18.3966 8.98403 18.1713 9.33447 17.8459 9.4346C17.7458 9.45963 17.6457 9.45963 17.5205 9.45963C16.6945 9.48466 15.8684 9.48466 15.0424 9.48466Z" fill="white"/>
                                            <path d="M15.0446 14.89C15.8957 14.89 16.7217 14.89 17.5728 14.89C18.1235 14.89 18.4739 15.3656 18.3237 15.8412C18.2236 16.1416 17.9733 16.3418 17.6479 16.3418C17.4476 16.3418 17.2474 16.3418 17.0471 16.3418C15.5703 16.3418 14.0935 16.3418 12.5916 16.3418C12.4414 16.3418 12.2912 16.3418 12.166 16.2918C11.8406 16.1666 11.6654 15.8162 11.7155 15.4908C11.7655 15.1654 12.0409 14.9151 12.3913 14.89C12.7418 14.865 13.0922 14.89 13.4176 14.89C13.9683 14.89 14.519 14.89 15.0446 14.89Z" fill="white"/>
                                            <path d="M7.28575 15.3661C7.8865 14.7653 8.41216 14.2397 8.96285 13.689C9.18813 13.4637 9.41341 13.3886 9.71379 13.4637C10.2645 13.6139 10.4397 14.2647 10.0392 14.7153C9.66372 15.1158 9.23819 15.5163 8.86272 15.9168C8.53732 16.2422 8.23694 16.5676 7.91153 16.868C7.53606 17.2184 7.13556 17.2184 6.78512 16.868C6.55984 16.6427 6.33456 16.4174 6.10928 16.1921C5.80891 15.8667 5.78387 15.4161 6.08425 15.1158C6.38463 14.8404 6.83519 14.8404 7.13557 15.1408C7.1606 15.1909 7.21065 15.266 7.28575 15.3661Z" fill="white"/>
                                            <path d="M7.30847 8.43254C7.38356 8.35745 7.43363 8.30739 7.50873 8.25733C8.00935 7.78173 8.48494 7.28111 8.98557 6.78048C9.31098 6.45508 9.76154 6.45507 10.0619 6.75545C10.3623 7.05582 10.3623 7.50639 10.0369 7.8318C9.31097 8.5577 8.6101 9.25858 7.88419 9.98448C7.53376 10.3349 7.13326 10.3349 6.75779 9.95946C6.53251 9.73417 6.30722 9.50889 6.08194 9.28361C5.78156 8.9582 5.75653 8.53267 6.05691 8.23229C6.33225 7.93192 6.80785 7.93192 7.13326 8.25733C7.15829 8.30739 7.23338 8.35745 7.30847 8.43254Z" fill="white"/>
                                            <path d="M17.0188 21.3742H6.98123C4.22778 21.3742 2 19.1464 2 16.393V6.98123C2 4.22778 4.22778 2 6.98123 2H17.0188C19.7722 2 22 4.22778 22 6.98123V16.393C22 19.1464 19.7472 21.3742 17.0188 21.3742ZM6.98123 3.47684C5.05382 3.47684 3.50188 5.02879 3.50188 6.9562V16.393C3.50188 18.3204 5.05382 19.8723 6.98123 19.8723H17.0188C18.9462 19.8723 20.4981 18.3204 20.4981 16.393V6.98123C20.4981 5.05382 18.9462 3.50188 17.0188 3.50188H6.98123V3.47684Z" fill="white"/>
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Products')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/product/bulk-import')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.product.bulk-import')}}">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.4869 3.01175H7.83489C5.75489 3.00378 4.05089 4.66078 4.00089 6.74078V17.4778C3.95589 19.5798 5.62389 21.3198 7.72489 21.3648C7.76189 21.3648 7.79889 21.3658 7.83489 21.3648H15.8229C17.9129 21.2908 19.5649 19.5688 19.553 17.4778V8.28778L14.4869 3.01175Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M14.2266 3V5.909C14.2266 7.329 15.3756 8.48 16.7956 8.484H19.5496" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11.3906 10.1582V16.1992" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M13.7369 12.5152L11.3919 10.1602L9.04688 12.5152" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('bulk_import')}}</span>
                                    </a>
                                </li>

                                <li class="d-none nav-item {{ Request::is('seller/product/list') || Request::is('seller/product/stock-limit-list/in_house') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('seller.supplier') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                          <path d="M21.0925 14.6502C20.9105 13.9904 20.3416 13.5581 19.6135 13.5353H19.5453C19.022 13.5353 18.4759 13.6946 17.8843 14.0359C17.1562 14.4455 16.4281 14.8778 15.7 15.3101C15.7 15.3101 14.4486 16.0382 14.4258 16.0609C14.4713 15.7196 14.4031 15.4011 14.2438 15.1281C14.0163 14.6957 13.5612 14.4 13.0834 14.3317C13.0379 14.3317 13.0151 14.3089 12.9696 14.3089H12.9241C11.923 14.0814 10.9218 13.7174 10.1027 13.3988C9.78419 13.2851 9.4429 13.2168 9.12435 13.2168C8.69204 13.2168 8.25974 13.3306 7.85018 13.5353C7.28135 13.8084 6.73527 14.1497 6.1892 14.4682L6.12094 14.5137C5.82515 14.6957 5.52935 14.8778 5.21081 15.037C5.1653 15.0598 5.1198 15.0826 5.0743 15.1281C5.0743 15.1281 5.0743 15.1281 5.05154 15.1281L5.00603 15.0598C4.98328 15.037 4.98329 14.9915 4.96053 14.9688C4.733 14.6047 4.41446 14.4 4.02765 14.4C3.84563 14.4 3.64085 14.4455 3.45882 14.5592C3.16303 14.7185 2.86724 14.9005 2.57145 15.0598C2.00263 15.3783 1.84335 15.9927 2.16189 16.5388C3.07202 18.177 4.0049 19.8152 4.93778 21.4534C5.14256 21.7947 5.48385 21.9995 5.8479 21.9995C6.02993 21.9995 6.21195 21.954 6.37122 21.863C6.73527 21.681 7.05382 21.4989 7.34961 21.2942C7.73641 21.0666 7.91844 20.7026 7.87293 20.2475C7.85018 20.0427 7.75916 19.8835 7.66815 19.7242L7.6454 19.6559C7.62265 19.6332 7.59989 19.5877 7.59989 19.5649C7.62264 19.5422 7.6454 19.5194 7.69091 19.5194C7.78192 19.4511 7.85018 19.4512 7.87293 19.4512C7.89568 19.4512 7.91844 19.4512 7.96395 19.4739C9.26088 19.838 10.5123 20.1793 12.0367 20.5661C12.3098 20.6343 12.5828 20.6798 12.8559 20.6798C13.3564 20.6798 13.8342 20.5433 14.2666 20.2703C16.3143 19.0188 18.3849 17.7447 20.4327 16.4705C21.0242 16.1292 21.2973 15.3556 21.0925 14.6502ZM5.8934 20.8163C5.25632 19.7014 4.61923 18.5865 4.0049 17.4716L3.18578 16.0154C3.45882 15.8562 3.73186 15.6969 4.0049 15.5604L5.27907 17.8129C5.75689 18.6548 6.2347 19.4967 6.71252 20.3385C6.71252 20.3613 6.73527 20.3613 6.73527 20.384L6.68976 20.4068C6.59875 20.4523 6.50774 20.4978 6.41673 20.5661L6.39398 20.5888C6.21195 20.6343 6.05268 20.7253 5.8934 20.8163ZM7.03106 18.632C7.00831 18.6093 6.98556 18.541 6.9628 18.4955C6.94005 18.4273 6.9173 18.3818 6.87179 18.3135C6.73527 18.086 6.62151 17.8584 6.48499 17.6309L6.32572 17.3351L5.93891 16.607C5.8479 16.4477 5.73414 16.2657 5.64313 16.0837C5.8024 15.9927 6.50774 15.5831 6.50774 15.5831C7.09932 15.2418 7.69091 14.9005 8.30524 14.5592C8.57828 14.4 8.85131 14.3317 9.1471 14.3317C9.35188 14.3317 9.55667 14.3772 9.7842 14.4682C9.98898 14.5592 10.2165 14.6275 10.4213 14.7185L10.535 14.764C10.6261 14.8095 10.7398 14.8323 10.8536 14.8778C11.0356 14.9233 11.1949 14.9915 11.3769 15.037C11.7865 15.1736 12.1733 15.2873 12.5828 15.3783L12.9924 15.4694C13.2427 15.5376 13.3792 15.7196 13.3337 15.9472C13.2882 16.1292 13.1517 16.243 12.9469 16.243C12.9014 16.243 12.8786 16.243 12.8331 16.2202C12.378 16.1292 11.9457 16.0154 11.4907 15.8789L10.9219 15.7196C10.8308 15.6969 10.7626 15.6741 10.6716 15.6514C10.6261 15.6514 10.5805 15.6286 10.5123 15.6286C10.2392 15.6286 10.0345 15.7879 9.96622 16.0382C9.92071 16.1747 9.94347 16.334 10.0117 16.4705C10.08 16.607 10.2165 16.698 10.3758 16.7435C10.5578 16.789 10.7171 16.8345 10.8991 16.9028L11.0129 16.9256C11.6955 17.1303 12.4918 17.3579 13.2882 17.4489C13.3792 17.4489 13.4929 17.4716 13.584 17.4716C14.0618 17.4716 14.5396 17.3351 14.9719 17.0848L16.2916 16.3112C17.0424 15.8789 17.7705 15.4466 18.5214 15.0143C18.7944 14.855 19.1585 14.673 19.568 14.673H19.5908C19.8638 14.673 19.9776 14.764 20.0459 14.9688C20.0914 15.1963 20.0231 15.4011 19.8411 15.5149L18.1118 16.5843C16.7011 17.4489 15.3132 18.3135 13.9025 19.1781C13.5612 19.3829 13.2199 19.4967 12.8559 19.4967C12.6511 19.4967 12.4463 19.4739 12.2415 19.4056C11.3769 19.1554 10.535 18.9506 9.76144 18.723L9.67043 18.7003C9.12436 18.5638 8.60103 18.4045 8.05495 18.268C7.98669 18.2452 7.91844 18.2452 7.85018 18.2452C7.6454 18.2452 7.48612 18.3135 7.37236 18.4045C7.32685 18.4273 7.2586 18.4728 7.21309 18.5183C7.12208 18.5638 7.07657 18.6093 7.03106 18.632Z" fill="white"/>
                                          <path d="M9.42127 11.3515C10.8775 12.1706 12.4019 13.058 13.9719 13.9681C14.1084 14.0364 14.2449 14.0819 14.3587 14.0819C14.4952 14.0819 14.609 14.0364 14.7455 13.9681C16.3837 13.0125 17.8627 12.1479 19.2961 11.3515C19.5692 11.1923 19.6829 10.9647 19.6829 10.6689C19.6829 10.0319 19.6829 9.39477 19.6829 8.75768V7.1422C19.6829 6.57338 19.6829 5.9818 19.6829 5.41297C19.6829 5.11718 19.5692 4.9124 19.3189 4.77588C17.7944 3.88851 16.2472 3.00114 14.7227 2.11377C14.5862 2.04551 14.4725 2 14.3587 2C14.2449 2 14.1084 2.04551 13.9947 2.11377C12.0151 3.27418 10.6499 4.04778 9.42127 4.75313C9.14823 4.9124 9.01172 5.13993 9.01172 5.45847C9.01172 7.14221 9.01172 8.84869 9.01172 10.6917C9.03447 10.9647 9.17099 11.1923 9.42127 11.3515ZM13.7899 11.8749V12.5347L11.9469 11.4653C11.3553 11.124 10.7637 10.7827 10.1721 10.4414C10.1721 9.281 10.1721 8.09784 10.1721 6.93743V6.25483L13.7899 8.34812C13.7899 9.53128 13.7899 10.6917 13.7899 11.8749ZM18.568 10.4642C17.5896 11.033 16.5885 11.6018 15.6101 12.1706L14.9503 12.5575V11.306C14.9503 10.3276 14.9503 9.37201 14.9503 8.41638C14.9503 8.39363 14.9503 8.39363 14.9503 8.39363C14.9503 8.39363 14.9503 8.39363 14.973 8.37088C15.2006 8.25711 15.4281 8.12059 15.6101 8.00682L16.5885 7.438L18.022 6.61889C18.204 6.52787 18.386 6.41411 18.568 6.32309C18.568 6.27759 18.568 10.4414 18.568 10.4642ZM11.3553 4.93515L13.8126 3.52446C13.9946 3.41069 14.1767 3.31968 14.3587 3.20592C14.9275 3.52446 17.6807 5.11718 17.9765 5.2992L14.9275 7.07395C14.7455 7.18771 14.5635 7.27872 14.3815 7.39249C14.1994 7.27872 13.4941 6.89192 10.7637 5.2992L10.8775 5.23094C11.0367 5.11718 11.196 5.02616 11.3553 4.93515Z" fill="white"/>
                                        </svg>
                                        <span class="text-truncate">{{ \App\CPU\translate('Suppliers') }}</span>
                                    </a>
                                </li>
                                <li class="d-none navbar-vertical-aside-has-menu {{ (Request::is('seller/report/all-product') ||Request::is('seller/report/stock-product-report')) ?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('seller.report.all-product')}}" title="{{\App\CPU\translate('Product_Report')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M6.29592 21.949C4.94388 21.949 4 21.0051 4 19.6786C4 15.3163 4 10.9541 4 6.59184C4 5.23979 4.94388 4.32144 6.27041 4.32144H7.11225V3.88776C7.11225 3.70919 7.11225 3.53062 7.11225 3.37756C7.11225 2.58674 7.72449 2 8.51531 2C9.53571 2 10.5816 2 11.602 2C12.648 2 13.6939 2 14.7653 2C15.5561 2 16.1684 2.61224 16.1684 3.40306C16.1684 3.55612 16.1684 3.73469 16.1684 3.91326V4.34694H17.0102C18.3878 4.34694 19.3061 5.26531 19.3061 6.64286C19.3061 11.0051 19.3061 15.3418 19.3061 19.7041C19.3061 21.0561 18.3622 22 17.0357 22H11.6531L6.29592 21.949ZM6.32143 5.4949C5.58163 5.4949 5.22449 5.87756 5.22449 6.61735V19.6275C5.22449 20.3673 5.60714 20.75 6.34694 20.75H16.9592C17.75 20.75 18.1071 20.3929 18.1071 19.602V6.59184C18.1071 6.5153 18.1071 6.46429 18.1071 6.38776C18.0561 5.92857 17.699 5.57144 17.2653 5.52042C17.1378 5.52042 17.0357 5.52042 16.9082 5.52042C16.8316 5.52042 16.7551 5.52042 16.7041 5.52042H16.6531C16.551 5.52042 16.4745 5.52042 16.398 5.52042H16.2194L16.1939 5.67347C16.0663 6.64286 15.5561 7.07654 14.6122 7.07654H14.2551C14.1786 7.07654 14.102 7.07654 14.0255 7.07654H13.9745C13.3112 7.07654 12.8265 6.77041 12.5459 6.13265C12.4694 5.92857 12.2398 5.7245 12.0357 5.59695C11.9082 5.54593 11.7806 5.4949 11.6531 5.4949C11.2704 5.4949 10.9388 5.75 10.7602 6.13265C10.5306 6.66837 10.199 6.97449 9.71428 7.02551C9.45918 7.05102 9.22959 7.07654 8.97449 7.07654C8.71939 7.07654 8.4898 7.05102 8.2602 7.02551C7.62245 6.94898 7.2398 6.4643 7.11225 5.64797L7.08673 5.4949H6.32143ZM11.6786 4.29592C12.5204 4.29592 13.2857 4.80613 13.6429 5.59695C13.6939 5.69899 13.8214 5.87755 13.9745 5.87755H14.051C14.2296 5.87755 14.3827 5.87756 14.5612 5.85205C14.6378 5.85205 14.8673 5.82653 14.8673 5.82653H14.9439V3.14796H8.33673V5.82653C8.33673 5.82653 8.87245 5.82653 8.92347 5.82653C9 5.82653 9.10204 5.82653 9.17857 5.82653C9.20408 5.82653 9.20408 5.82653 9.22959 5.82653C9.48469 5.82653 9.58673 5.67348 9.66327 5.52042C10.0459 4.7296 10.6837 4.29593 11.551 4.24491C11.602 4.29593 11.6531 4.29592 11.6786 4.29592Z" fill="white"/>
                                            <path d="M7.82549 14.1169C7.62141 14.1169 7.41733 14.0404 7.28978 13.9129C7.18774 13.8108 7.13672 13.6577 7.13672 13.5047C7.13672 13.1475 7.41733 12.918 7.851 12.918H15.4786C15.9122 12.918 16.1673 13.1475 16.1928 13.5047C16.1928 13.6577 16.1418 13.8108 16.0398 13.9129C15.9122 14.0404 15.7337 14.1169 15.5041 14.1169H11.6775H7.82549Z" fill="white"/>
                                            <path d="M7.77447 17.2302C7.41733 17.2302 7.13672 16.9751 7.13672 16.6435C7.13672 16.4904 7.18774 16.3374 7.31529 16.2098C7.44284 16.0823 7.5959 16.0312 7.79998 16.0312C8.3357 16.0312 8.89692 16.0312 9.43264 16.0312H13.8204C14.4071 16.0312 14.9939 16.0312 15.5806 16.0312C15.8612 16.0312 16.0908 16.2098 16.1673 16.4649C16.2439 16.72 16.1418 16.9751 15.9377 17.1027C15.8102 17.1792 15.6826 17.2047 15.5806 17.2047C14.05 17.2047 12.5194 17.2047 11.0143 17.2047C9.89182 17.2302 8.8459 17.2302 7.77447 17.2302Z" fill="white"/>
                                            <path d="M7.79998 10.9801C7.5959 10.9801 7.41733 10.9036 7.28978 10.8015C7.18774 10.6995 7.13672 10.5464 7.13672 10.3934C7.13672 10.0618 7.41733 9.80664 7.79998 9.80664C8.05509 9.80664 9.12651 9.80664 9.12651 9.80664H11.5755C11.9837 9.80664 12.2388 10.0617 12.2643 10.4189C12.2643 10.572 12.2132 10.725 12.1112 10.827C11.9837 10.9546 11.8051 11.0056 11.601 11.0056C10.9632 11.0056 10.3255 11.0056 9.71325 11.0056C9.101 11.0056 8.43774 10.9801 7.79998 10.9801Z" fill="white"/>
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            <span class="position-relative">
                                                {{\App\CPU\translate('Product_Report')}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('seller/reviews/list*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('seller.reviews.list')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                      <path fill-rule="evenodd" clip-rule="evenodd" d="M13.3035 3.67701L15.1309 7.32776C15.31 7.68616 15.6557 7.93467 16.0566 7.99218L20.1446 8.58062C21.1546 8.72644 21.5565 9.95055 20.8255 10.6519L17.8694 13.4924C17.5789 13.7718 17.4466 14.1733 17.5154 14.5676L18.213 18.5778C18.3848 19.5698 17.329 20.3267 16.4262 19.8574L12.7724 17.9627C12.4142 17.7768 11.9852 17.7768 11.626 17.9627L7.97222 19.8574C7.06945 20.3267 6.01361 19.5698 6.18646 18.5778L6.88306 14.5676C6.95179 14.1733 6.81955 13.7718 6.52904 13.4924L3.5729 10.6519C2.84193 9.95055 3.24386 8.72644 4.25388 8.58062L8.34187 7.99218C8.74276 7.93467 9.0895 7.68616 9.26859 7.32776L11.095 3.67701C11.5469 2.77433 12.8516 2.77433 13.3035 3.67701Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('Product')}} {{\App\CPU\translate('Reviews')}}
                                    </span>
                                </a>
                            </li>

                            </ul>
                        </li>


                        <li class="d-none navbar-vertical-aside-has-menu  || * ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop"
                               href="javascript:" title=" {{\App\CPU\translate('Customer')}}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                      <path d="M21.6389 14.3962H17.5906C16.1042 14.3953 14.8993 13.1914 14.8984 11.7049C14.8984 10.2185 16.1042 9.01458 17.5906 9.01367H21.6389" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                      <path d="M18.05 11.6426H17.7383" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                      <path fill-rule="evenodd" clip-rule="evenodd" d="M7.74766 3H16.3911C19.2892 3 21.6388 5.34951 21.6388 8.24766V15.4247C21.6388 18.3229 19.2892 20.6724 16.3911 20.6724H7.74766C4.84951 20.6724 2.5 18.3229 2.5 15.4247V8.24766C2.5 5.34951 4.84951 3 7.74766 3Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                      <path d="M7.03516 7.53809H12.4341" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{\App\CPU\translate('Customer')}}
                            </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('seller/customer/list') || Request::is('seller/transaction/order-list') || Request::is('seller/transaction/expense-list')) ?'block':'none'}}">


                                    <li class="navbar-vertical-aside-has-menu {{ Request::is('seller/business-settings/withdraw*')?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('seller.customer.list')}}">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5.87389 21.9984C5.61804 21.9092 5.34404 21.8531 5.11131 21.7227C4.36854 21.3034 4.00045 20.6498 4.00045 19.7964C3.9988 17.8438 4.0021 15.8911 4.00375 13.9384C4.00705 11.106 4.01035 8.2752 4.01365 5.44276C4.01531 4.49696 4.4098 3.77729 5.27802 3.37619C5.55697 3.24745 5.88874 3.21113 6.20071 3.17812C6.50112 3.14511 6.80813 3.17152 7.1234 3.17152C7.12835 3.07083 7.12835 2.98665 7.13661 2.90412C7.19108 2.37428 7.59547 2.00124 8.13357 2.00124C10.4576 1.99959 12.7817 1.99959 15.1057 2.00124C15.6934 2.00124 16.0994 2.40399 16.1159 2.9883C16.1176 3.03947 16.1159 3.09064 16.1159 3.17152C16.2678 3.17152 16.4081 3.16492 16.5484 3.17317C16.9049 3.19298 17.2697 3.17317 17.613 3.25075C18.5539 3.46203 19.2124 4.30549 19.2306 5.27274C19.2405 5.73491 19.2339 6.19708 19.2323 6.65925C19.229 11.0136 19.224 15.3695 19.2191 19.7238C19.2174 20.696 18.7981 21.4107 17.9085 21.825C17.7352 21.9059 17.542 21.9439 17.3572 22C13.5294 21.9984 9.70165 21.9984 5.87389 21.9984ZM7.10194 4.3418C6.83784 4.3418 6.56549 4.3418 6.29479 4.3418C5.57348 4.3418 5.18394 4.72804 5.18394 5.44936C5.17898 10.2213 5.17568 14.9932 5.17238 19.7651C5.17238 20.4237 5.57348 20.8264 6.22712 20.8264C9.81389 20.8264 13.4007 20.8264 16.9874 20.8264C17.0782 20.8264 17.1706 20.8248 17.2581 20.805C17.7632 20.6993 18.0488 20.3098 18.0488 19.7288C18.0537 15.1005 18.057 10.4722 18.0603 5.84385C18.0603 5.65568 18.0653 5.46586 18.0587 5.27769C18.0438 4.86999 17.7731 4.4689 17.3786 4.40122C16.9693 4.33025 16.5451 4.3484 16.1308 4.32694C16.1209 4.39462 16.1159 4.41278 16.1143 4.43093C16.0845 5.1704 15.7247 5.51538 14.9852 5.51538C13.9305 5.51538 12.8758 5.51538 11.821 5.51538C10.5979 5.51538 9.37318 5.51703 8.15008 5.51538C7.69121 5.51538 7.32312 5.26614 7.19273 4.85514C7.14321 4.70163 7.13495 4.53657 7.10194 4.3418ZM8.30854 4.3319C10.5253 4.3319 12.7256 4.3319 14.9291 4.3319C14.9291 3.94235 14.9291 3.56271 14.9291 3.18637C12.7157 3.18637 10.5154 3.18637 8.30854 3.18637C8.30854 3.57097 8.30854 3.93905 8.30854 4.3319Z" fill="white"/>
                                                <path d="M13.9204 16.4509C14.5905 16.4509 15.259 16.4492 15.9291 16.4509C16.3121 16.4509 16.5729 16.6869 16.5762 17.0286C16.5795 17.3802 16.317 17.6212 15.9242 17.6212C14.579 17.6228 13.2337 17.6228 11.8868 17.6212C11.4956 17.6212 11.2249 17.3769 11.2266 17.0319C11.2282 16.6869 11.4989 16.4492 11.8918 16.4492C12.5685 16.4509 13.2453 16.4509 13.9204 16.4509Z" fill="white"/>
                                                <path d="M13.8924 9.81214C13.2222 9.81214 12.5521 9.81379 11.8836 9.81214C11.494 9.81214 11.2217 9.5662 11.2266 9.22123C11.2299 8.88285 11.4973 8.64186 11.8753 8.64186C13.2222 8.64021 14.5675 8.64021 15.9144 8.64186C16.2923 8.64186 16.5597 8.8845 16.563 9.22288C16.5663 9.5662 16.2923 9.81214 15.9028 9.81214C15.2327 9.81379 14.5625 9.81214 13.8924 9.81214Z" fill="white"/>
                                                <path d="M13.8874 13.7179C13.2239 13.7179 12.5603 13.7179 11.8968 13.7179C11.5006 13.7179 11.2316 13.4835 11.2266 13.1402C11.2217 12.7886 11.4957 12.5476 11.9017 12.5476C13.242 12.5476 14.5807 12.546 15.921 12.5476C16.3699 12.5476 16.6571 12.9091 16.5333 13.3069C16.4541 13.5594 16.2313 13.7162 15.9358 13.7179C15.2525 13.7195 14.5691 13.7179 13.8874 13.7179Z" fill="white"/>
                                                <path d="M7.944 13.2184C8.41113 12.7496 8.83533 12.3188 9.26614 11.8963C9.35197 11.8121 9.45761 11.7345 9.56985 11.6949C9.81249 11.6074 10.0815 11.7131 10.2218 11.9227C10.3704 12.1439 10.3704 12.4327 10.1806 12.6275C9.58141 13.2415 8.97398 13.849 8.35831 14.4481C8.13878 14.6611 7.82186 14.6611 7.59573 14.4547C7.31677 14.2005 7.04938 13.9348 6.79518 13.6558C6.58391 13.4247 6.61527 13.0715 6.8348 12.8619C7.04772 12.6572 7.38775 12.6391 7.61554 12.8404C7.73438 12.9444 7.82681 13.0814 7.944 13.2184Z" fill="white"/>
                                                <path d="M7.97893 17.142C8.08952 17.0067 8.1704 16.8961 8.26448 16.7987C8.61606 16.4422 8.96764 16.084 9.32747 15.7374C9.56681 15.5063 9.92829 15.5195 10.1528 15.7489C10.3723 15.975 10.3839 16.325 10.1544 16.5594C9.56681 17.1585 8.97424 17.7527 8.37342 18.3387C8.13408 18.5714 7.81056 18.5681 7.56462 18.3404C7.30383 18.0961 7.04963 17.8435 6.80534 17.5811C6.58416 17.3434 6.60232 16.9902 6.82515 16.7706C7.04798 16.5528 7.39626 16.5462 7.63725 16.7657C7.74619 16.8647 7.83862 16.9852 7.97893 17.142Z" fill="white"/>
                                                <path d="M7.96953 9.0408C8.10323 8.884 8.19401 8.7635 8.298 8.65786C8.63968 8.30959 8.983 7.96296 9.33458 7.62458C9.56732 7.40175 9.92385 7.41 10.1467 7.62788C10.3712 7.84741 10.3926 8.2056 10.1665 8.43668C9.57557 9.04246 8.97805 9.63997 8.37393 10.2309C8.13624 10.4636 7.80942 10.457 7.56513 10.2276C7.30929 9.9866 7.0617 9.73736 6.81906 9.48317C6.59457 9.24878 6.59953 8.89885 6.81741 8.67272C7.04024 8.43998 7.39512 8.43008 7.64106 8.65951C7.74835 8.7602 7.83583 8.88234 7.96953 9.0408Z" fill="white"/>
                                             </svg>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate text-capitalize">
                                                    {{\App\CPU\translate('List')}}
                                                </span>
                                        </a>
                                    </li>
                                    <!--
                                        <li class="navbar-vertical-aside-has-menu {{ Request::is('seller/business-settings/withdraw*')?'active':''}}">
                                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                            href="{{route('seller.business-settings.withdraw.list')}}">
                                                <i class="tio-wallet-outlined nav-icon"></i>
                                                <span
                                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate text-capitalize">
                                                        {{\App\CPU\translate('withdraws')}}
                                                    </span>
                                            </a>
                                        </li>
                                    <li class="navbar-vertical-aside-has-menu {{ Request::is('seller/profile/view') || Request::is('seller/profile/bank-edit/*') ? 'active' : '' }}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('seller.profile.view')}}">
                                            <i class="tio-shop nav-icon"></i>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{\App\CPU\translate('My_Bank_Info')}}
                                            </span>
                                        </a>
                                    </li>
                                    -->

                            </ul>
                        </li>


                        <li class="nav-item d-none">
                            <small class="nav-subtitle">{{\App\CPU\translate('promotion_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>


                        <li class="navbar-vertical-aside-has-menu d-none {{Request::is('seller/coupon*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop"
                               href="javascript:" title="{{\App\CPU\translate('Offers_&_Deals')}}">
                                <i class="tio-users-switch"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Offers_&_Deals')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('seller/coupon*')?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{Request::is('seller/coupon*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('seller.coupon.add-new')}}"
                                       title="{{\App\CPU\translate('coupon')}}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                         <img src="{{ asset('public/assets/back-end/img/discount.png') }}" alt="">
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('coupon')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <small class="nav-subtitle">{{\App\CPU\translate('Help_&_Support_Section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>


                        <li class="d-none navbar-vertical-aside-has-menu {{Request::is('seller/messages*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop"
                               href="javascript:">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                  <path d="M21.6407 10.1437C21.3669 8.86624 20.3176 8.0222 19.0174 8.0222C17.3521 8.0222 15.6869 8.0222 14.0216 8.0222C13.862 8.0222 13.7935 7.99941 13.7935 7.81692C13.7935 6.67635 13.7935 5.51294 13.7935 4.37237C13.7935 2.98087 12.8126 2 11.4211 2C9.04874 2 6.67635 2 4.32677 2C3.00371 2 2 3.00369 2 4.32676C2 5.90075 2 7.49756 2 9.07155C2 9.25405 2.02281 9.43651 2.06844 9.61901C2.31936 10.6683 3.1862 11.3755 4.25834 11.3983C4.3952 11.3983 4.44083 11.4439 4.41802 11.5808C4.41802 11.9458 4.41802 12.3336 4.41802 12.6986C4.41802 12.8126 4.41802 12.9267 4.46364 13.0179C4.69175 13.8163 5.64984 14.0672 6.24293 13.497C6.74479 12.9951 7.22383 12.4933 7.72568 11.9914C7.74849 12.0142 7.7713 12.0142 7.7713 12.037C7.7713 12.1055 7.7713 12.1739 7.7713 12.2423C7.7713 13.6338 7.7713 15.0025 7.7713 16.394C7.7713 16.6678 7.79411 16.9187 7.86255 17.1696C8.18191 18.333 9.20842 19.1086 10.4402 19.1086C11.6493 19.1086 12.8583 19.1086 14.0445 19.1086C14.1813 19.1086 14.2726 19.1542 14.3638 19.2455C15.1622 20.0439 15.9378 20.8195 16.7362 21.6179C17.1012 21.9828 17.5574 22.0969 18.0593 21.9144C18.5155 21.7319 18.7892 21.3213 18.7892 20.7738C18.7892 20.272 18.7892 19.7929 18.7892 19.2911C18.7892 19.1314 18.8349 19.1086 18.9717 19.1086C20.5457 19.0858 21.6863 17.9452 21.6863 16.3712C21.6863 14.5235 21.6863 12.6529 21.6863 10.8052C21.6863 10.5543 21.6863 10.349 21.6407 10.1437ZM7.79411 10.3262C7.7713 10.5315 7.70287 10.6912 7.54319 10.8508C6.90447 11.4667 6.28856 12.1055 5.64983 12.7214C5.5814 12.7898 5.53578 12.8811 5.42172 12.8354C5.33047 12.7898 5.35329 12.6986 5.35329 12.6073C5.35329 12.0827 5.35329 11.558 5.35329 11.0105C5.35329 10.5999 5.17079 10.4402 4.783 10.4402C4.53207 10.4402 4.30396 10.463 4.05303 10.4174C3.41431 10.3034 2.93527 9.7787 2.93527 9.11717C2.93527 7.49755 2.93527 5.87793 2.93527 4.25831C2.93527 3.52835 3.52837 2.95807 4.25834 2.95807C5.46734 2.95807 6.69916 2.95807 7.90817 2.95807C9.07155 2.95807 10.2349 2.95807 11.3983 2.95807C12.3108 2.95807 12.8583 3.50555 12.8583 4.41801C12.8583 5.55858 12.8583 6.69915 12.8583 7.86253C12.8583 8.04503 12.7898 8.06784 12.6301 8.06784C11.8089 8.06784 10.9877 8.04503 10.1665 8.09065C8.9575 8.11346 7.93098 9.11718 7.79411 10.3262ZM20.751 16.3712C20.751 17.3977 20.0211 18.1277 18.9945 18.1505C18.7892 18.1505 18.5839 18.1505 18.3786 18.1505C18.0365 18.1505 17.854 18.333 17.854 18.6752C17.854 19.3595 17.854 20.0211 17.854 20.7054C17.854 20.7966 17.854 20.9107 17.7627 20.9791C17.6259 21.0932 17.489 21.0247 17.3749 20.9107C16.7362 20.272 16.0747 19.6104 15.436 18.9717C15.2307 18.7892 15.0482 18.5839 14.8429 18.3786C14.6832 18.1961 14.5007 18.1277 14.2726 18.1277C12.9951 18.1277 11.7405 18.1277 10.4631 18.1277C9.59622 18.1277 8.93469 17.6259 8.72938 16.8275C8.68376 16.6678 8.68376 16.5081 8.68376 16.3484C8.68376 14.4778 8.68376 12.6073 8.68376 10.7368C8.68376 9.68745 9.41373 8.9803 10.4402 8.9803C11.8546 8.9803 13.2689 8.9803 14.706 8.9803C16.1203 8.9803 17.5346 8.9803 18.9717 8.9803C19.8158 8.9803 20.4773 9.48215 20.6826 10.2577C20.7282 10.4174 20.7282 10.5771 20.7282 10.714C20.751 12.6073 20.751 14.4779 20.751 16.3712Z" fill="white"/>
                                  <path d="M14.043 13.5418C14.043 13.154 14.3623 12.8574 14.7501 12.8574C15.1379 12.8574 15.4345 13.154 15.4345 13.5418C15.4345 13.9296 15.1379 14.2489 14.7501 14.2489C14.3395 14.2489 14.043 13.9296 14.043 13.5418Z" fill="white"/>
                                  <path d="M11.125 13.5652C11.125 13.1774 11.4216 12.8809 11.8322 12.8809C12.22 12.8809 12.5393 13.2002 12.5165 13.588C12.5165 13.953 12.1971 14.2724 11.8322 14.2724C11.4444 14.2495 11.125 13.953 11.125 13.5652Z" fill="white"/>
                                  <path d="M18.3329 13.5646C18.3329 13.9524 18.0135 14.2489 17.6258 14.2489C17.238 14.2489 16.9414 13.9296 16.9414 13.5418C16.9414 13.1768 17.2608 12.8574 17.6486 12.8574C18.0364 12.8574 18.3329 13.1768 18.3329 13.5646Z" fill="white"/>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('messages')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('seller/messages*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('seller/messages/chat/customer')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.messages.chat', ['type' => 'customer'])}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M5.87389 21.9984C5.61804 21.9092 5.34404 21.8531 5.11131 21.7227C4.36854 21.3034 4.00045 20.6498 4.00045 19.7964C3.9988 17.8438 4.0021 15.8911 4.00375 13.9384C4.00705 11.106 4.01035 8.2752 4.01365 5.44276C4.01531 4.49696 4.4098 3.77729 5.27802 3.37619C5.55697 3.24745 5.88874 3.21113 6.20071 3.17812C6.50112 3.14511 6.80813 3.17152 7.1234 3.17152C7.12835 3.07083 7.12835 2.98665 7.13661 2.90412C7.19108 2.37428 7.59547 2.00124 8.13357 2.00124C10.4576 1.99959 12.7817 1.99959 15.1057 2.00124C15.6934 2.00124 16.0994 2.40399 16.1159 2.9883C16.1176 3.03947 16.1159 3.09064 16.1159 3.17152C16.2678 3.17152 16.4081 3.16492 16.5484 3.17317C16.9049 3.19298 17.2697 3.17317 17.613 3.25075C18.5539 3.46203 19.2124 4.30549 19.2306 5.27274C19.2405 5.73491 19.2339 6.19708 19.2323 6.65925C19.229 11.0136 19.224 15.3695 19.2191 19.7238C19.2174 20.696 18.7981 21.4107 17.9085 21.825C17.7352 21.9059 17.542 21.9439 17.3572 22C13.5294 21.9984 9.70165 21.9984 5.87389 21.9984ZM7.10194 4.3418C6.83784 4.3418 6.56549 4.3418 6.29479 4.3418C5.57348 4.3418 5.18394 4.72804 5.18394 5.44936C5.17898 10.2213 5.17568 14.9932 5.17238 19.7651C5.17238 20.4237 5.57348 20.8264 6.22712 20.8264C9.81389 20.8264 13.4007 20.8264 16.9874 20.8264C17.0782 20.8264 17.1706 20.8248 17.2581 20.805C17.7632 20.6993 18.0488 20.3098 18.0488 19.7288C18.0537 15.1005 18.057 10.4722 18.0603 5.84385C18.0603 5.65568 18.0653 5.46586 18.0587 5.27769C18.0438 4.86999 17.7731 4.4689 17.3786 4.40122C16.9693 4.33025 16.5451 4.3484 16.1308 4.32694C16.1209 4.39462 16.1159 4.41278 16.1143 4.43093C16.0845 5.1704 15.7247 5.51538 14.9852 5.51538C13.9305 5.51538 12.8758 5.51538 11.821 5.51538C10.5979 5.51538 9.37318 5.51703 8.15008 5.51538C7.69121 5.51538 7.32312 5.26614 7.19273 4.85514C7.14321 4.70163 7.13495 4.53657 7.10194 4.3418ZM8.30854 4.3319C10.5253 4.3319 12.7256 4.3319 14.9291 4.3319C14.9291 3.94235 14.9291 3.56271 14.9291 3.18637C12.7157 3.18637 10.5154 3.18637 8.30854 3.18637C8.30854 3.57097 8.30854 3.93905 8.30854 4.3319Z" fill="white"/>
                                                    <path d="M13.9204 16.4509C14.5905 16.4509 15.259 16.4492 15.9291 16.4509C16.3121 16.4509 16.5729 16.6869 16.5762 17.0286C16.5795 17.3802 16.317 17.6212 15.9242 17.6212C14.579 17.6228 13.2337 17.6228 11.8868 17.6212C11.4956 17.6212 11.2249 17.3769 11.2266 17.0319C11.2282 16.6869 11.4989 16.4492 11.8918 16.4492C12.5685 16.4509 13.2453 16.4509 13.9204 16.4509Z" fill="white"/>
                                                    <path d="M13.8924 9.81214C13.2222 9.81214 12.5521 9.81379 11.8836 9.81214C11.494 9.81214 11.2217 9.5662 11.2266 9.22123C11.2299 8.88285 11.4973 8.64186 11.8753 8.64186C13.2222 8.64021 14.5675 8.64021 15.9144 8.64186C16.2923 8.64186 16.5597 8.8845 16.563 9.22288C16.5663 9.5662 16.2923 9.81214 15.9028 9.81214C15.2327 9.81379 14.5625 9.81214 13.8924 9.81214Z" fill="white"/>
                                                    <path d="M13.8874 13.7179C13.2239 13.7179 12.5603 13.7179 11.8968 13.7179C11.5006 13.7179 11.2316 13.4835 11.2266 13.1402C11.2217 12.7886 11.4957 12.5476 11.9017 12.5476C13.242 12.5476 14.5807 12.546 15.921 12.5476C16.3699 12.5476 16.6571 12.9091 16.5333 13.3069C16.4541 13.5594 16.2313 13.7162 15.9358 13.7179C15.2525 13.7195 14.5691 13.7179 13.8874 13.7179Z" fill="white"/>
                                                    <path d="M7.944 13.2184C8.41113 12.7496 8.83533 12.3188 9.26614 11.8963C9.35197 11.8121 9.45761 11.7345 9.56985 11.6949C9.81249 11.6074 10.0815 11.7131 10.2218 11.9227C10.3704 12.1439 10.3704 12.4327 10.1806 12.6275C9.58141 13.2415 8.97398 13.849 8.35831 14.4481C8.13878 14.6611 7.82186 14.6611 7.59573 14.4547C7.31677 14.2005 7.04938 13.9348 6.79518 13.6558C6.58391 13.4247 6.61527 13.0715 6.8348 12.8619C7.04772 12.6572 7.38775 12.6391 7.61554 12.8404C7.73438 12.9444 7.82681 13.0814 7.944 13.2184Z" fill="white"/>
                                                    <path d="M7.97893 17.142C8.08952 17.0067 8.1704 16.8961 8.26448 16.7987C8.61606 16.4422 8.96764 16.084 9.32747 15.7374C9.56681 15.5063 9.92829 15.5195 10.1528 15.7489C10.3723 15.975 10.3839 16.325 10.1544 16.5594C9.56681 17.1585 8.97424 17.7527 8.37342 18.3387C8.13408 18.5714 7.81056 18.5681 7.56462 18.3404C7.30383 18.0961 7.04963 17.8435 6.80534 17.5811C6.58416 17.3434 6.60232 16.9902 6.82515 16.7706C7.04798 16.5528 7.39626 16.5462 7.63725 16.7657C7.74619 16.8647 7.83862 16.9852 7.97893 17.142Z" fill="white"/>
                                                    <path d="M7.96953 9.0408C8.10323 8.884 8.19401 8.7635 8.298 8.65786C8.63968 8.30959 8.983 7.96296 9.33458 7.62458C9.56732 7.40175 9.92385 7.41 10.1467 7.62788C10.3712 7.84741 10.3926 8.2056 10.1665 8.43668C9.57557 9.04246 8.97805 9.63997 8.37393 10.2309C8.13624 10.4636 7.80942 10.457 7.56513 10.2276C7.30929 9.9866 7.0617 9.73736 6.81906 9.48317C6.59457 9.24878 6.59953 8.89885 6.81741 8.67272C7.04024 8.43998 7.39512 8.43008 7.64106 8.65951C7.74835 8.7602 7.83583 8.88234 7.96953 9.0408Z" fill="white"/>
                                                    </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Customer')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/messages/chat/delivery-man')?'active':''}}">
                                    <a class="nav-link" href="{{route('seller.messages.chat', ['type' => 'delivery-man'])}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
  <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0412 15.207C15.7302 15.207 18.8832 15.766 18.8832 17.999C18.8832 20.232 15.7512 20.807 12.0412 20.807C8.35122 20.807 5.19922 20.253 5.19922 18.019C5.19922 15.785 8.33022 15.207 12.0412 15.207Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
  <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0422 12.02C9.62025 12.02 7.65625 10.057 7.65625 7.635C7.65625 5.213 9.62025 3.25 12.0422 3.25C14.4632 3.25 16.4273 5.213 16.4273 7.635C16.4363 10.048 14.4862 12.011 12.0732 12.02H12.0422Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Delivery-Man')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item {{(Request::is('seller/transaction/order-list')) ? 'scroll-here':''}}">
                            <small class="nav-subtitle" title="">
                                {{\App\CPU\translate('Reports')}} & {{\App\CPU\translate('Analysis')}}
                            </small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('seller/transaction/order-list') || Request::is('seller/transaction/expense-list') || Request::is('seller/report/all-product') ||Request::is('seller/report/stock-product-report') || Request::is('seller/report/order-report')) ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop"
                               href="javascript:" title=" {{\App\CPU\translate('Analytics_and_Reports')}}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                  <path d="M7.37109 10.2021V17.0623" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M12.0391 6.91895V17.0617" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M16.6289 13.8271V17.0622" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M16.6857 2H7.31429C4.04762 2 2 4.31208 2 7.58516V16.4148C2 19.6879 4.0381 22 7.31429 22H16.6857C19.9619 22 22 19.6879 22 16.4148V7.58516C22 4.31208 19.9619 2 16.6857 2Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{\App\CPU\translate('Analytics_and_Reports')}}
                            </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('seller/transaction/order-list') || Request::is('seller/transaction/expense-list') || Request::is('seller/report/all-product') ||Request::is('seller/report/stock-product-report') || Request::is('seller/report/order-report')) ?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{(Request::is('seller/transaction/order-list') || Request::is('seller/transaction/expense-list') || Request::is('seller/transaction/order-history-log*'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('seller.transaction.order-list')}}"
                                       title="{{\App\CPU\translate('Transaction_Report')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                          <path d="M11.8743 22.0457C9.45143 22.0457 7.02857 22.0457 4.60571 22.0457C3.05143 22.0457 2 20.9943 2 19.44C2 17.7029 2 15.9657 2 14.2057V12.0343V9.77143C2 8.03429 2 6.29714 2 4.53714C2 3.21143 2.8 2.25143 4.10286 2.02286C4.26286 2 4.44571 2 4.62857 2C7.6 2 10.5714 2 13.5429 2H19.3714C20.6971 2 21.6571 2.73143 21.9314 3.96571C21.9771 4.14857 22 4.37714 22 4.62857V9.54286C22 12.8343 22 16.1029 22 19.3943C22 20.88 20.8343 22.0229 19.3714 22.0229C16.88 22.0457 14.3886 22.0457 11.8743 22.0457ZM4.67429 3.53143C3.89714 3.53143 3.53143 3.89714 3.53143 4.67428C3.53143 9.56571 3.53143 14.48 3.53143 19.3943C3.53143 20.1714 3.89714 20.5143 4.65143 20.5143H19.3714C20.1486 20.5143 20.4914 20.1714 20.4914 19.3943V4.65143C20.4914 3.89714 20.1257 3.53143 19.3714 3.53143H12.0343H4.67429Z" fill="white"/>
                                          <path d="M10.8017 15.8059C10.756 15.8059 10.6874 15.8059 10.6417 15.7831C10.4131 15.7145 10.2303 15.5317 10.0703 15.3717C9.15599 14.4802 8.21885 13.5888 7.28171 12.7431C6.96171 12.4459 6.61885 12.1259 6.64171 11.7145C6.64171 11.3488 6.98457 11.0059 7.35028 11.0059C7.37314 11.0059 7.39599 11.0059 7.41885 11.0059C7.71599 11.0288 7.94456 11.2345 8.19599 11.4631C8.44742 11.7145 8.72171 11.9431 8.97314 12.1717C9.11028 12.2859 9.24742 12.4002 9.36171 12.5145L9.95599 13.0631C10.0017 13.0859 10.0246 13.1317 10.0703 13.1774C10.2531 13.3602 10.5046 13.6117 10.7789 13.6117C10.8017 13.6117 10.8246 13.6117 10.8474 13.6117C10.9617 13.5888 11.0531 13.5202 11.1217 13.4517C11.3274 13.2688 11.5331 13.0631 11.716 12.8574C11.8074 12.7659 11.876 12.6745 11.9674 12.6059L15.0989 9.40595L15.9674 8.51452C16.1503 8.33166 16.3331 8.24023 16.5388 8.24023C16.7217 8.24023 16.9046 8.30881 17.0417 8.46881C17.3617 8.78881 17.3617 9.22309 17.0417 9.56595C16.4474 10.1831 15.8531 10.7774 15.2589 11.3717L15.2131 11.4174C13.956 12.6974 12.676 14.0231 11.396 15.3717C11.3731 15.3945 11.3503 15.4402 11.3046 15.4631C11.1674 15.6231 11.0303 15.7602 10.8246 15.7831C10.8474 15.8059 10.8246 15.8059 10.8017 15.8059Z" fill="white"/>
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate"> {{ \App\CPU\translate('Transaction_Report') }}
                                    </span>
                                    </a>
                                </li>

                                <li class="navbar-vertical-aside-has-menu {{Request::is('seller/report/order-report')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('seller.report.order-report')}}"
                                    title="{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Report')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M7.48549 6.96064C7.66189 6.96064 7.86035 6.96064 8.03675 6.96064C8.38957 6.96064 8.72033 6.96064 9.02904 6.93859C9.42595 6.91654 9.75671 6.58578 9.73466 6.21092C9.71261 5.83605 9.4039 5.52734 8.98494 5.52734C8.91878 5.52734 8.85263 5.52734 8.78648 5.52734C8.67623 5.52734 8.56597 5.52734 8.45572 5.52734C8.38957 5.52734 8.30137 5.52734 8.23521 5.52734H7.9706C7.81625 5.52734 7.63984 5.52734 7.48549 5.52734C7.08857 5.54939 6.75781 5.8581 6.75781 6.23297C6.75781 6.62988 7.08857 6.93859 7.48549 6.96064Z" fill="white"/>
                                            <path d="M20.8247 7.00551C20.2293 4.02867 17.8038 2.02205 14.7828 2.02205C11.8721 2 8.93936 2 6.44763 2C6.22712 2 6.00662 2.02205 5.80816 2.06615C4.74973 2.30871 4.0882 3.19074 4.06615 4.31533C4.06615 5.52811 4.06615 6.7409 4.0441 7.93164L4.02205 14.2602C4.02205 16.0243 4 17.8104 4 19.5965C4 20.9857 4.97023 21.9559 6.35943 21.9559C10.4168 21.9779 14.4741 21.9779 18.5094 22H18.5755C19.9206 21.9779 20.8688 21.0077 20.8688 19.6406V19.4201V17.7663C20.8909 14.613 20.8909 11.3936 20.8909 8.2183C20.935 7.75524 20.8909 7.35832 20.8247 7.00551ZM5.5215 4.31533C5.5215 3.74201 5.85226 3.41125 6.42558 3.41125H6.64609V3.34509L6.66814 3.41125C9.40243 3.41125 12.1147 3.4333 14.849 3.4333C15.0033 3.4333 15.1577 3.45535 15.3341 3.45535H15.3561C15.4443 3.45535 15.5325 3.4774 15.6207 3.4774H15.6648V7.22602C15.6648 8.19625 16.2823 8.83572 17.2525 8.83572H19.3694C19.3694 8.83572 19.3914 8.83572 19.4135 8.83572L19.4796 8.85777V8.96803C19.4796 9.03418 19.4796 9.07828 19.4796 9.14443C19.4796 12.6284 19.4576 16.1125 19.4355 19.6185C19.4355 20.2139 19.1047 20.5447 18.5094 20.5447L6.35943 20.5006C6.07277 20.5006 5.83021 20.4123 5.67586 20.258C5.5215 20.1036 5.4333 19.8611 5.4333 19.5744L5.5215 4.31533ZM17.0981 7.40243L17.1202 4.02867L17.1863 4.07277C18.4212 4.84454 19.1709 5.92503 19.4576 7.35833V7.40243H17.0981Z" fill="white"/>
                                            <path d="M9.18352 11.2838C8.6543 11.2838 8.14714 11.2838 7.61792 11.3058C7.35331 11.3058 7.11075 11.4381 6.97845 11.6366C6.84614 11.813 6.82409 12.0335 6.9123 12.254C7.02255 12.5407 7.28716 12.7171 7.66202 12.7171C8.25739 12.7171 8.85276 12.7171 9.44813 12.695H10.1758H11.0137C11.3665 12.695 11.7414 12.695 12.0942 12.673C12.3588 12.673 12.6014 12.5407 12.7337 12.3422C12.866 12.1658 12.888 11.9453 12.7998 11.7248C12.6896 11.4381 12.425 11.2617 12.0281 11.2617C11.5209 11.2617 10.9917 11.2617 10.4625 11.2838H9.18352Z" fill="white"/>
                                            <path d="M9.07261 8.19588H8.65365C8.32289 8.19588 7.94803 8.19588 7.59522 8.21793C7.44086 8.21793 7.26446 8.26203 7.13215 8.35023C6.86754 8.48254 6.75729 8.8133 6.82344 9.09996C6.91164 9.40867 7.1542 9.60712 7.48496 9.62918H7.55111C8.41109 9.62918 9.38132 9.60713 10.528 9.58507C10.6382 9.58507 10.7485 9.54097 10.8808 9.49687C11.1454 9.36457 11.3218 9.05586 11.2556 8.7692C11.1674 8.39433 10.9028 8.17383 10.5059 8.17383H10.3516C10.0649 8.17383 9.77824 8.17383 9.49158 8.17383L9.07261 8.19588Z" fill="white"/>
                                            <path d="M12.5547 18.1412C12.5547 18.3176 12.6429 18.494 12.7752 18.6263C12.9075 18.7586 13.0839 18.8468 13.2603 18.8468H13.2824C13.6352 18.8468 13.988 18.494 13.988 18.1412C13.988 17.7884 13.6572 17.4355 13.2824 17.4355C12.9075 17.4576 12.5547 17.7884 12.5547 18.1412Z" fill="white"/>
                                            <path d="M16.3281 18.8683H16.3502C16.703 18.8683 17.0337 18.5375 17.0558 18.1847C17.0558 18.0083 16.9896 17.8539 16.8573 17.6996C16.725 17.5452 16.5266 17.457 16.3502 17.457C16.1737 17.457 15.9973 17.5452 15.865 17.6775C15.7327 17.8098 15.6445 17.9862 15.6445 18.1627C15.6445 18.5155 15.9973 18.8462 16.3281 18.8683Z" fill="white"/>
                                            </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Order_Report')}}
                                    </span>
                                    </a>
                                </li>
                                <li class="navbar-vertical-aside-has-menu {{ (Request::is('seller/report/all-product') ||Request::is('seller/report/stock-product-report')) ?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('seller.report.all-product')}}" title="{{\App\CPU\translate('Product_Report')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M6.29592 21.949C4.94388 21.949 4 21.0051 4 19.6786C4 15.3163 4 10.9541 4 6.59184C4 5.23979 4.94388 4.32144 6.27041 4.32144H7.11225V3.88776C7.11225 3.70919 7.11225 3.53062 7.11225 3.37756C7.11225 2.58674 7.72449 2 8.51531 2C9.53571 2 10.5816 2 11.602 2C12.648 2 13.6939 2 14.7653 2C15.5561 2 16.1684 2.61224 16.1684 3.40306C16.1684 3.55612 16.1684 3.73469 16.1684 3.91326V4.34694H17.0102C18.3878 4.34694 19.3061 5.26531 19.3061 6.64286C19.3061 11.0051 19.3061 15.3418 19.3061 19.7041C19.3061 21.0561 18.3622 22 17.0357 22H11.6531L6.29592 21.949ZM6.32143 5.4949C5.58163 5.4949 5.22449 5.87756 5.22449 6.61735V19.6275C5.22449 20.3673 5.60714 20.75 6.34694 20.75H16.9592C17.75 20.75 18.1071 20.3929 18.1071 19.602V6.59184C18.1071 6.5153 18.1071 6.46429 18.1071 6.38776C18.0561 5.92857 17.699 5.57144 17.2653 5.52042C17.1378 5.52042 17.0357 5.52042 16.9082 5.52042C16.8316 5.52042 16.7551 5.52042 16.7041 5.52042H16.6531C16.551 5.52042 16.4745 5.52042 16.398 5.52042H16.2194L16.1939 5.67347C16.0663 6.64286 15.5561 7.07654 14.6122 7.07654H14.2551C14.1786 7.07654 14.102 7.07654 14.0255 7.07654H13.9745C13.3112 7.07654 12.8265 6.77041 12.5459 6.13265C12.4694 5.92857 12.2398 5.7245 12.0357 5.59695C11.9082 5.54593 11.7806 5.4949 11.6531 5.4949C11.2704 5.4949 10.9388 5.75 10.7602 6.13265C10.5306 6.66837 10.199 6.97449 9.71428 7.02551C9.45918 7.05102 9.22959 7.07654 8.97449 7.07654C8.71939 7.07654 8.4898 7.05102 8.2602 7.02551C7.62245 6.94898 7.2398 6.4643 7.11225 5.64797L7.08673 5.4949H6.32143ZM11.6786 4.29592C12.5204 4.29592 13.2857 4.80613 13.6429 5.59695C13.6939 5.69899 13.8214 5.87755 13.9745 5.87755H14.051C14.2296 5.87755 14.3827 5.87756 14.5612 5.85205C14.6378 5.85205 14.8673 5.82653 14.8673 5.82653H14.9439V3.14796H8.33673V5.82653C8.33673 5.82653 8.87245 5.82653 8.92347 5.82653C9 5.82653 9.10204 5.82653 9.17857 5.82653C9.20408 5.82653 9.20408 5.82653 9.22959 5.82653C9.48469 5.82653 9.58673 5.67348 9.66327 5.52042C10.0459 4.7296 10.6837 4.29593 11.551 4.24491C11.602 4.29593 11.6531 4.29592 11.6786 4.29592Z" fill="white"/>
                                            <path d="M7.82549 14.1169C7.62141 14.1169 7.41733 14.0404 7.28978 13.9129C7.18774 13.8108 7.13672 13.6577 7.13672 13.5047C7.13672 13.1475 7.41733 12.918 7.851 12.918H15.4786C15.9122 12.918 16.1673 13.1475 16.1928 13.5047C16.1928 13.6577 16.1418 13.8108 16.0398 13.9129C15.9122 14.0404 15.7337 14.1169 15.5041 14.1169H11.6775H7.82549Z" fill="white"/>
                                            <path d="M7.77447 17.2302C7.41733 17.2302 7.13672 16.9751 7.13672 16.6435C7.13672 16.4904 7.18774 16.3374 7.31529 16.2098C7.44284 16.0823 7.5959 16.0312 7.79998 16.0312C8.3357 16.0312 8.89692 16.0312 9.43264 16.0312H13.8204C14.4071 16.0312 14.9939 16.0312 15.5806 16.0312C15.8612 16.0312 16.0908 16.2098 16.1673 16.4649C16.2439 16.72 16.1418 16.9751 15.9377 17.1027C15.8102 17.1792 15.6826 17.2047 15.5806 17.2047C14.05 17.2047 12.5194 17.2047 11.0143 17.2047C9.89182 17.2302 8.8459 17.2302 7.77447 17.2302Z" fill="white"/>
                                            <path d="M7.79998 10.9801C7.5959 10.9801 7.41733 10.9036 7.28978 10.8015C7.18774 10.6995 7.13672 10.5464 7.13672 10.3934C7.13672 10.0618 7.41733 9.80664 7.79998 9.80664C8.05509 9.80664 9.12651 9.80664 9.12651 9.80664H11.5755C11.9837 9.80664 12.2388 10.0617 12.2643 10.4189C12.2643 10.572 12.2132 10.725 12.1112 10.827C11.9837 10.9546 11.8051 11.0056 11.601 11.0056C10.9632 11.0056 10.3255 11.0056 9.71325 11.0056C9.101 11.0056 8.43774 10.9801 7.79998 10.9801Z" fill="white"/>
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            <span class="position-relative">
                                                {{\App\CPU\translate('Product_Report')}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <!-- here -->
                            </ul>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{(Request::is('seller/profile/view') || Request::is('seller/profile/bank-edit/*') || Request::is('seller/business-settings/withdraw*')) ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop"
                               href="javascript:" title=" {{\App\CPU\translate('Wallet')}}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                      <path d="M21.6389 14.3962H17.5906C16.1042 14.3953 14.8993 13.1914 14.8984 11.7049C14.8984 10.2185 16.1042 9.01458 17.5906 9.01367H21.6389" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                      <path d="M18.05 11.6426H17.7383" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                      <path fill-rule="evenodd" clip-rule="evenodd" d="M7.74766 3H16.3911C19.2892 3 21.6388 5.34951 21.6388 8.24766V15.4247C21.6388 18.3229 19.2892 20.6724 16.3911 20.6724H7.74766C4.84951 20.6724 2.5 18.3229 2.5 15.4247V8.24766C2.5 5.34951 4.84951 3 7.74766 3Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                      <path d="M7.03516 7.53809H12.4341" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{\App\CPU\translate('Wallet')}}
                            </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('seller/profile/view') || Request::is('seller/profile/bank-edit/*') || Request::is('seller/business-settings/withdraw*')) ?'block':'none'}}">


                                    <li class="navbar-vertical-aside-has-menu {{ Request::is('seller/wallet') ? 'active' : '' }}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('seller.wallet.view') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path d="M21 7H3C1.9 7 1 7.9 1 9V18C1 19.1 1.9 20 3 20H21C22.1 20 23 19.1 23 18V9C23 7.9 22.1 7 21 7ZM3 9H21V11H3V9ZM21 18H3V13H21V18ZM16 15C16.55 15 17 15.45 17 16C17 16.55 16.55 17 16 17C15.45 17 15 16.55 15 16C15 15.45 15.45 15 16 15Z" fill="white"/>
                                            </svg>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate text-capitalize">
                                                {{\App\CPU\translate('My Wallet')}}
                                            </span>
                                        </a>
                                    </li> 
                                    <li class="navbar-vertical-aside-has-menu {{ Request::is('seller/business-settings/withdraw*')?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('seller.business-settings.withdraw.list')}}">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M12.0052 5.80078C14.2867 5.80078 16.5682 5.80078 18.8497 5.80078C19.4081 5.80078 19.6784 6.07105 19.6784 6.62805C19.6784 10.8865 19.6784 15.145 19.6784 19.4034C19.6784 20.4612 18.991 21.1499 17.9333 21.1499C13.9808 21.1512 10.0283 21.1512 6.07574 21.1499C5.01936 21.1499 4.33203 20.4598 4.33203 19.402C4.33203 15.1436 4.33203 10.8851 4.33203 6.62668C4.33203 6.06968 4.6023 5.80078 5.16204 5.80078C7.44218 5.80078 9.72369 5.80078 12.0052 5.80078ZM5.73276 7.20563C5.73002 7.26737 5.72728 7.30304 5.72728 7.34008C5.72728 11.3653 5.72728 15.3919 5.72728 19.4171C5.72728 19.6956 5.7945 19.7546 6.10455 19.7546C10.0351 19.7546 13.967 19.7546 17.8976 19.7546C18.2351 19.7546 18.2845 19.7039 18.2845 19.3609C18.2845 15.3864 18.2845 11.4106 18.2845 7.43611C18.2845 7.35929 18.2845 7.28246 18.2845 7.20563C14.085 7.20563 9.91713 7.20563 5.73276 7.20563Z" fill="white"/>
                                                     <path d="M12.0112 3.0097C14.7797 3.0097 17.5469 3.00832 20.3154 3.01107C21.1454 3.01107 21.7985 3.52829 21.9658 4.31577C21.9946 4.44885 21.9988 4.59015 21.9988 4.72735C22.0001 6.83462 22.0015 8.9419 21.996 11.0492C21.996 11.196 21.9645 11.3551 21.9014 11.4868C21.7806 11.7392 21.4912 11.8778 21.2332 11.8421C20.941 11.8024 20.6996 11.5952 20.6365 11.3126C20.6118 11.2001 20.6076 11.0821 20.6063 10.9669C20.6049 8.91034 20.6049 6.85383 20.6049 4.79731C20.6049 4.44885 20.561 4.40494 20.2208 4.40494C14.7427 4.40494 9.26458 4.40494 3.78649 4.40494C3.44625 4.40494 3.40509 4.44747 3.40372 4.80006C3.40372 6.87852 3.40372 8.95561 3.40372 11.0341C3.40372 11.4827 3.19656 11.7626 2.82065 11.8339C2.43377 11.908 2.05237 11.6226 2.01533 11.2289C2.0071 11.1493 2.00985 11.0697 2.00985 10.9888C2.00985 8.92543 2.03317 6.86069 2.00024 4.79869C1.98378 3.70801 2.80556 2.99049 3.79609 3.0001C6.53308 3.02753 9.27144 3.0097 12.0112 3.0097Z" fill="white"/>
                                                    <path d="M11.3042 15.0347C11.3042 13.4954 11.3042 12.0055 11.3042 10.5156C11.3042 10.414 11.3014 10.3125 11.3056 10.211C11.3207 9.81861 11.6252 9.51816 12.0039 9.51954C12.3811 9.52091 12.6926 9.8241 12.6967 10.2165C12.7036 10.9655 12.6994 11.7132 12.6994 12.4623C12.6994 13.3047 12.6994 14.147 12.6994 15.0251C12.7694 14.9647 12.8188 14.9277 12.8613 14.8851C13.3141 14.4338 13.7654 13.9797 14.2182 13.5283C14.5515 13.1963 14.9645 13.1702 15.2622 13.4597C15.5613 13.7519 15.5366 14.1827 15.1991 14.5216C14.5419 15.1801 13.8834 15.8372 13.2263 16.4944C13.1645 16.5561 13.1028 16.6192 13.0328 16.67C12.8394 16.8058 12.6706 16.9512 12.5307 17.157C12.2481 17.5714 11.6444 17.4959 11.3879 17.0541C11.3303 16.954 11.2342 16.8744 11.1464 16.7948C11.049 16.7084 10.9324 16.6426 10.8405 16.5506C10.1435 15.8592 9.4521 15.165 8.7579 14.4708C8.55486 14.2678 8.48763 14.0236 8.56858 13.7505C8.6454 13.4926 8.82787 13.3335 9.08991 13.2759C9.35469 13.2182 9.57282 13.3102 9.76078 13.4981C10.2217 13.9618 10.6855 14.4228 11.1492 14.8851C11.1862 14.9263 11.2287 14.9633 11.3042 15.0347Z" fill="white"/>
                                            </svg>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate text-capitalize">

                                                    {{\App\CPU\translate('withdraws')}}
                                                </span>
                                        </a>
                                    </li>

                                    <li class="navbar-vertical-aside-has-menu {{ Request::is('seller/profile/view') || Request::is('seller/profile/bank-edit/*') ? 'active' : '' }}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('seller.profile.view')}}">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                              <path d="M22.0828 21.1543C22.0544 20.7736 21.7428 20.4666 21.3564 20.4485C21.1557 20.4388 20.9538 20.4343 20.7532 20.4478C20.59 20.4588 20.5351 20.4182 20.5358 20.2414C20.5416 17.4422 20.5409 14.6431 20.5377 11.8439C20.5377 11.7168 20.5558 11.6536 20.7009 11.6104C21.497 11.3704 21.9886 10.7053 21.9963 9.86663C22.0034 9.06217 22.0054 8.2577 21.9957 7.45324C21.9867 6.69007 21.6318 6.11849 20.9396 5.80819C18.1972 4.57924 15.4471 3.36707 12.6989 2.15102C12.2247 1.94136 11.7461 1.95426 11.2738 2.16328C8.54111 3.37416 5.80517 4.57989 3.07761 5.80303C2.37701 6.1172 2.01704 6.69394 2.00671 7.46614C1.99639 8.27061 1.99962 9.07507 2.00542 9.87953C2.01188 10.7182 2.55377 11.4143 3.36533 11.6246C3.50339 11.6601 3.55306 11.7097 3.55306 11.8613C3.54726 14.6528 3.5479 17.4435 3.55242 20.235C3.55242 20.3988 3.51823 20.4582 3.34404 20.4511C3.08793 20.4408 2.82666 20.4137 2.57442 20.4711C2.21767 20.5524 1.97059 20.9239 2.00736 21.2917C2.04671 21.6852 2.3725 21.98 2.79892 21.989C3.30534 22 3.8124 21.9994 4.31882 21.9994C6.89284 22 9.46621 22 12.0402 22C12.0402 21.9974 12.0402 21.9942 12.0402 21.9916C12.2009 21.9916 12.3621 21.9916 12.5228 21.9916C15.3942 21.9916 18.2656 21.9916 21.137 21.9903C21.2486 21.9903 21.3628 21.9865 21.4719 21.9639C21.8557 21.8819 22.1112 21.5387 22.0828 21.1543ZM7.20765 20.4472C6.5806 20.4401 5.95355 20.4369 5.32714 20.4485C5.14134 20.4517 5.08715 20.4066 5.08844 20.2137C5.09747 18.8312 5.0936 17.4493 5.0936 16.0669C5.0936 14.6766 5.09618 13.2864 5.09038 11.8962C5.08973 11.7355 5.11941 11.6755 5.29682 11.6788C5.93935 11.6904 6.58253 11.6878 7.22572 11.6794C7.3728 11.6775 7.41474 11.7142 7.41474 11.8652C7.41022 14.6618 7.40958 17.4584 7.41538 20.255C7.41474 20.4253 7.3541 20.4485 7.20765 20.4472ZM15.1362 20.2562C15.1362 20.3969 15.1103 20.4511 14.9542 20.4485C14.3117 20.4388 13.6685 20.4388 13.0253 20.4485C12.9827 20.4491 12.9486 20.4466 12.9208 20.4401H11.1584C11.128 20.4472 11.0906 20.4504 11.0435 20.4491C10.4087 20.4349 9.77393 20.4388 9.13914 20.4478C8.98947 20.4498 8.95141 20.4091 8.95205 20.2608C8.95657 17.4642 8.95721 14.6676 8.95141 11.871C8.95076 11.6988 9.01592 11.6794 9.15978 11.6807C9.47782 11.6839 9.79587 11.6859 10.1139 11.6865H10.1475C10.4532 11.6865 10.7584 11.6846 11.0642 11.6794C11.1022 11.6788 11.1332 11.6813 11.1596 11.6865H12.9363C12.9653 11.6801 13.0008 11.6775 13.0447 11.6788C13.244 11.6833 13.4434 11.6852 13.6427 11.6865H13.7104C14.1233 11.6884 14.5362 11.6852 14.9491 11.6801C15.0974 11.6781 15.1374 11.7168 15.1374 11.8665C15.1323 14.6624 15.1323 17.459 15.1362 20.2562ZM18.7611 20.4485C18.1347 20.4369 17.5076 20.4401 16.8805 20.4472C16.7341 20.4485 16.6735 20.4253 16.6735 20.2556C16.6793 17.459 16.6786 14.6624 16.6741 11.8658C16.6741 11.7149 16.716 11.6781 16.8631 11.6801C17.5057 11.6884 18.1489 11.6904 18.792 11.6794C18.9688 11.6762 18.9991 11.7362 18.9985 11.8968C18.9927 13.2954 18.9952 14.6934 18.9952 16.092C18.9952 17.4661 18.9907 18.8402 19.0004 20.215C19.0011 20.4072 18.9475 20.4524 18.7611 20.4485ZM20.1274 10.1382C17.4334 10.1382 14.7394 10.1382 12.0454 10.1382C9.62491 10.1382 7.20378 10.1382 4.7833 10.1382C4.47752 10.1382 4.17238 10.1356 3.86659 10.1389C3.64402 10.1414 3.54597 10.0363 3.54726 9.81502C3.55048 9.06733 3.55629 8.31899 3.54403 7.5713C3.54016 7.34035 3.63177 7.22487 3.83433 7.1352C6.45158 5.97979 9.06688 4.82052 11.6783 3.65285C11.9106 3.54899 12.0996 3.54383 12.3351 3.64898C14.9542 4.81987 17.5766 5.98302 20.2023 7.13907C20.45 7.24809 20.5539 7.3855 20.5455 7.66419C20.5255 8.34737 20.539 9.0312 20.539 9.71502C20.5397 10.0847 20.4868 10.1382 20.1274 10.1382Z" fill="white"/>
                                            </svg>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{\App\CPU\translate('My_Bank_Info')}}
                                            </span>
                                        </a>
                                    </li>

                            </ul>
                        </li>






                        <!-- End Pages -->
                        <li class="nav-item {{ ( Request::is('seller/business-settings*')) ? 'scroll-here' : '' }}">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('business_section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        @php($shippingMethod = \App\CPU\Helpers::get_business_settings('shipping_method'))
                        @if($shippingMethod=='sellerwise_shipping')
                            <li class="d-none navbar-vertical-aside-has-menu {{Request::is('seller/business-settings/shipping-method*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('seller.business-settings.shipping-method.add')}}">
                                    <i class="tio-settings"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate text-capitalize">
                                        {{\App\CPU\translate('shipping_method')}}
                                    </span>
                                </a>
                            </li>
                        @endif

                            <li class="d-none navbar-vertical-aside-has-menu  || * ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop"
                               href="javascript:" title=" {{\App\CPU\translate('Payment')}}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                      <path d="M21.6389 14.3962H17.5906C16.1042 14.3953 14.8993 13.1914 14.8984 11.7049C14.8984 10.2185 16.1042 9.01458 17.5906 9.01367H21.6389" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                      <path d="M18.05 11.6426H17.7383" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                      <path fill-rule="evenodd" clip-rule="evenodd" d="M7.74766 3H16.3911C19.2892 3 21.6388 5.34951 21.6388 8.24766V15.4247C21.6388 18.3229 19.2892 20.6724 16.3911 20.6724H7.74766C4.84951 20.6724 2.5 18.3229 2.5 15.4247V8.24766C2.5 5.34951 4.84951 3 7.74766 3Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                      <path d="M7.03516 7.53809H12.4341" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{\App\CPU\translate('Payment')}}
                            </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('seller/transaction/order-list') || Request::is('seller/transaction/expense-list')) ?'block':'none'}}">


                                    <li class="navbar-vertical-aside-has-menu {{ Request::is('seller/business-settings/withdraw*')?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('seller.business-settings.withdraw.list')}}">
                                            <i class="tio-wallet-outlined nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate text-capitalize">
                                                    {{\App\CPU\translate('withdraws')}}
                                                </span>
                                        </a>
                                    </li>

                                    <li class="navbar-vertical-aside-has-menu {{ Request::is('seller/profile/view') || Request::is('seller/profile/bank-edit/*') ? 'active' : '' }}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('seller.profile.view')}}">
                                            <i class="tio-shop nav-icon"></i>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{\App\CPU\translate('My_Bank_Info')}}
                                            </span>
                                        </a>
                                    </li>

                            </ul>
                        </li>



                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/profile/update/*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.profile.update',auth('seller')->user()->id)}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                      <path d="M21.9826 8.31182C21.9818 8.08382 21.9527 7.85997 21.8989 7.63861C21.6054 6.44638 21.2862 5.26079 20.9935 4.06857C20.9205 3.77176 20.8509 3.47494 20.7688 3.18062C20.6071 2.59694 20.0807 1.99835 19.2358 2C14.4006 2.00581 9.56539 2.00249 4.73017 2.00415C4.53617 2.00415 4.34382 2.02156 4.15976 2.10613C3.62915 2.34988 3.32322 2.76442 3.18476 3.32239C2.93852 4.31895 2.68897 5.31385 2.43776 6.30958C2.27443 6.95627 2.06633 7.59301 2 8.26125C2 8.33918 2 8.41712 2 8.49505C2 8.57298 2 8.65092 2 8.72885C2 8.87145 2 9.01488 2 9.15749C2.0398 9.21635 2.01907 9.28434 2.02487 9.34735C2.08705 9.98326 2.39464 10.5197 2.72545 11.0412C2.86142 11.2551 2.92111 11.4648 2.92028 11.7152C2.91531 14.5722 2.9236 17.4293 2.91282 20.2871C2.91033 21.0175 3.40696 21.9154 4.30402 21.9859C4.34299 21.9859 4.38196 21.9859 4.42093 21.9859C9.46093 21.9859 14.5009 21.9859 19.5401 21.9859C19.5658 21.9859 19.5923 21.9859 19.618 21.9859C19.8212 21.9519 20.0293 21.9494 20.2158 21.8383C20.8094 21.4851 21.0781 20.9504 21.0797 20.2797C21.0855 18.415 21.0822 13.2333 21.0822 11.3687C21.0822 11.3512 21.0814 11.333 21.0805 11.3156C21.1394 11.2484 21.1966 11.1788 21.2513 11.1067C21.6709 10.5528 21.9445 9.93683 22 9.23542C22 9.01406 22 8.79352 22 8.57215C21.9627 8.48759 21.9925 8.39805 21.9826 8.31182ZM15.9369 3.38375C16.984 3.39038 18.032 3.38872 19.0791 3.3854C19.2342 3.38457 19.3444 3.42188 19.3859 3.58687C19.7805 5.16379 20.1976 6.73573 20.564 8.31928C20.8625 9.61017 19.8991 10.9632 18.5809 11.1788C17.2386 11.3977 15.9518 10.4475 15.7868 9.11355C15.7686 8.96597 15.7587 8.81591 15.7587 8.6675C15.7562 7.84919 15.757 7.03089 15.757 6.21258C15.757 5.32961 15.7611 4.44663 15.7528 3.56366C15.752 3.41276 15.7943 3.38292 15.9369 3.38375ZM9.87547 3.38375C11.3206 3.38872 12.7657 3.38872 14.2108 3.38375C14.3525 3.38292 14.3956 3.41193 14.394 3.562C14.3857 4.45824 14.3898 5.35531 14.3898 6.25155C14.389 6.25155 14.3882 6.25155 14.3873 6.25155C14.3873 7.13535 14.3998 8.01915 14.384 8.90213C14.365 9.94678 13.5856 10.906 12.5915 11.144C11.3305 11.4458 10.0827 10.6722 9.77515 9.39958C9.72126 9.17821 9.6939 8.95353 9.6939 8.72554C9.69556 7.00353 9.69722 5.28235 9.69058 3.56034C9.68976 3.40696 9.73618 3.38292 9.87547 3.38375ZM3.40115 9.06297C3.33897 8.52821 3.48821 8.03408 3.6134 7.53248C3.9326 6.25072 4.25843 4.96978 4.58094 3.68885C4.65058 3.41359 4.68292 3.38706 4.96481 3.38706C6.01277 3.38706 7.0599 3.39038 8.10703 3.38375C8.25129 3.38292 8.29109 3.41608 8.28943 3.56366C8.28197 4.45326 8.28529 5.34287 8.28529 6.23248C8.28529 7.05742 8.28695 7.88153 8.28446 8.70647C8.28031 10.0297 7.36749 11.0478 6.05505 11.1979C4.79484 11.343 3.5479 10.329 3.40115 9.06297ZM10.3306 18.2666C10.3306 17.552 10.334 16.8381 10.3282 16.1235C10.3265 15.9809 10.3489 15.922 10.5147 15.9236C11.5038 15.9328 12.4929 15.9319 13.482 15.9245C13.6412 15.9228 13.6735 15.9717 13.6727 16.1201C13.6669 17.5619 13.6677 19.0037 13.6718 20.4455C13.6718 20.5756 13.6486 20.6229 13.5044 20.6221C12.502 20.6154 11.5005 20.6154 10.4981 20.6221C10.3597 20.6229 10.3265 20.5839 10.3273 20.4488C10.334 19.7217 10.3306 18.9937 10.3306 18.2666ZM19.5617 20.0832C19.5617 20.5383 19.5376 20.5624 19.0941 20.5624C17.8894 20.5624 16.6847 20.5624 15.4809 20.5624C15.4162 20.5624 15.3507 20.5632 15.2861 20.5591C15.2007 20.5533 15.1567 20.5101 15.1509 20.4247C15.1468 20.3534 15.1468 20.2821 15.1468 20.21C15.1468 18.6182 15.1468 17.0263 15.1468 15.4337C15.1468 15.277 15.1393 15.1219 15.0854 14.9727C14.9768 14.6692 14.7289 14.4819 14.4064 14.481C12.8046 14.4777 11.2028 14.4802 9.60104 14.4794C9.30009 14.4794 9.09862 14.6294 8.95767 14.879C8.86813 15.0374 8.8557 15.2115 8.85653 15.3889C8.85736 17.0006 8.85736 18.6115 8.85736 20.2233C8.85736 20.5624 8.85736 20.5624 8.50914 20.5624C7.71488 20.5624 6.92061 20.5624 6.12635 20.5624C5.67036 20.5624 5.21519 20.5649 4.75919 20.5615C4.50964 20.5599 4.45243 20.4994 4.44248 20.249C4.44082 20.21 4.44165 20.171 4.44165 20.1321C4.44165 17.6498 4.44165 15.1675 4.44165 12.6852C4.44165 12.6272 4.43917 12.5683 4.44663 12.5103C4.45575 12.4456 4.49306 12.4216 4.55607 12.4332C4.6141 12.444 4.66799 12.4672 4.72437 12.4838C5.69772 12.7723 6.64287 12.6968 7.55569 12.2574C8.05978 12.0145 8.50168 11.6854 8.84658 11.2393C8.92949 11.1324 8.99084 11.1108 9.08038 11.236C9.19396 11.3952 9.34403 11.5229 9.48414 11.6588C10.5263 12.672 12.2864 12.9472 13.5939 12.3088C14.1096 12.0576 14.5714 11.7417 14.9312 11.2882C15.0664 11.1183 15.0962 11.1232 15.2396 11.2949C15.3234 11.3952 15.408 11.493 15.5025 11.5826C16.6466 12.6595 18.2235 12.9108 19.5608 12.3901C19.5625 14.6867 19.5617 18.5444 19.5617 20.0832Z" fill="#fff"/>
                                    </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Store_Setting')}}
                                </span>
                            </a>
                        </li>

                        @php( $shipping_method = \App\CPU\Helpers::get_business_settings('shipping_method'))
                        {{-- @if($shipping_method=='sellerwise_shipping') --}}
                            <li class="nav-item {{Request::is('seller/delivery-man*')?'scroll-here':''}}">
                                <small class="nav-subtitle">{{\App\CPU\translate('delivery_man_management')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('seller/delivery-man*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                      <path d="M19.7608 20.7743C19.7414 20.7354 19.7219 20.716 19.7219 20.6965C19.469 20.0934 19.1772 19.5876 18.8075 19.1595C18.0488 18.2646 17.0954 17.7393 15.967 17.6031C15.753 17.5837 15.539 17.5642 15.325 17.5642C15.2083 17.5642 15.0721 17.5642 14.9554 17.5448C14.8581 17.5448 14.7803 17.5058 14.7803 17.3891C14.7414 17.0973 14.683 16.8054 14.6441 16.5914C14.6246 16.5136 14.6441 16.4747 14.7219 16.4358C15.3445 16.0856 15.8892 15.5992 16.3172 14.9572L16.5702 14.5486C16.9203 13.9261 17.1344 13.2841 17.2511 12.6226C17.2705 12.5448 17.29 12.4669 17.3678 12.4086C17.7569 12.0389 18.0293 11.6304 18.2044 11.1634C18.4573 10.4241 18.4379 9.68483 18.1266 8.88717L18.1071 8.84825C18.0877 8.78989 18.0682 8.73152 18.0682 8.67315C18.146 7.95331 18.146 7.33074 18.0488 6.72762C17.8542 5.36576 17.2511 4.27626 16.2589 3.45914C15.1305 2.52529 13.7297 2.03891 12.0954 2C12.0371 2 11.9982 2 11.9398 2C10.8892 2 9.81918 2.27238 8.78805 2.81712C7.21217 3.6537 6.29778 4.9572 6.0254 6.74709C5.94758 7.29183 5.92813 7.87549 5.98649 8.49806C6.00595 8.67316 5.98649 8.8288 5.92813 8.98444C5.79194 9.31518 5.73358 9.66538 5.71412 10.0545C5.67521 10.9689 6.00595 11.7665 6.70634 12.428C6.7647 12.4864 6.80362 12.5448 6.82307 12.6226C6.9398 13.323 7.17327 13.9844 7.50401 14.5486L7.73747 14.9183C8.16548 15.5603 8.71023 16.0662 9.35225 16.4358C9.43008 16.4747 9.44953 16.5136 9.43007 16.6109C9.37171 16.8833 9.31334 17.1751 9.27443 17.4669C9.25498 17.5447 9.23552 17.5837 9.13825 17.5837C9.06042 17.5837 9.00206 17.5837 8.92424 17.5837C8.80751 17.5837 8.67132 17.5837 8.55459 17.5837C7.504 17.6031 6.58961 17.9533 5.83085 18.5953C5.22774 19.1012 4.76081 19.7627 4.39116 20.6187C4.17716 21.144 4.13825 21.572 4.29389 21.8054C4.39116 21.9416 4.52735 22 4.74136 22C5.03319 22 6.60906 22 8.71023 22H9.07988C9.27443 22 9.48844 22 9.66354 22H9.70245H11.5896H12.2122H19.5274C19.7219 22 19.8581 21.9416 19.9165 21.8249C20.0332 21.6109 19.9748 21.2412 19.7608 20.7743ZM11.6869 3.18677C11.7063 3.18677 11.7258 3.18677 11.7647 3.16732H11.8036H12.1927C12.9904 3.24514 13.613 3.38133 14.1577 3.59533C15.1305 3.98444 15.8309 4.50973 16.2783 5.24903C16.648 5.83269 16.8425 6.53308 16.9009 7.36966L16.5507 7.19456L16.2005 7.03892C15.5001 6.74709 14.7219 6.53308 13.827 6.39689C13.2044 6.31907 12.6013 6.26071 11.9982 6.26071C11.0449 6.26071 10.111 6.37744 9.19661 6.59145C8.69077 6.70818 8.24331 6.86382 7.8542 7.03892C7.71801 7.07783 7.58182 7.15565 7.42618 7.23347L7.17327 7.36966C7.17327 7.13619 7.21218 6.92219 7.25109 6.68872C7.52346 5.21012 8.4184 4.17899 9.91646 3.59533C10.4612 3.38133 11.0643 3.24514 11.6869 3.18677ZM8.74914 7.95332C9.74136 7.62258 10.7919 7.44747 11.9787 7.44747C12.2705 7.44747 12.5624 7.44748 12.8542 7.46693C13.8464 7.5253 14.7219 7.71985 15.539 8.03113C15.9476 8.18677 16.2783 8.36187 16.5896 8.57587C16.5118 8.6537 16.434 8.71207 16.3561 8.78989C16.2005 8.94553 16.0254 9.10118 15.8309 9.21791C15.6363 9.33464 15.4028 9.393 15.1694 9.393C15.0721 9.393 14.9554 9.37354 14.8386 9.35408C13.827 9.14008 12.8931 9.02335 12.0371 9.02335C11.9787 9.02335 11.9398 9.02335 11.8814 9.02335C11.0449 9.04281 10.1694 9.14008 9.23552 9.33463C9.11879 9.35409 9.00206 9.37355 8.90478 9.37355C8.55459 9.37355 8.2433 9.23736 7.97093 8.98444C7.93202 8.94553 7.89311 8.90661 7.83474 8.8677C7.75692 8.78988 7.65965 8.69261 7.56237 8.61479C7.52346 8.57588 7.50401 8.55643 7.50401 8.53697C7.50401 8.51752 7.54291 8.4786 7.58183 8.45915C7.93202 8.28405 8.32112 8.0895 8.74914 7.95332ZM6.9398 9.72374C6.99817 9.74319 7.03708 9.76265 7.05653 9.80156L7.07599 9.82101C7.62074 10.3463 8.20439 10.5992 8.88533 10.5992C9.09933 10.5992 9.3328 10.5798 9.56626 10.5214C10.3834 10.3463 11.2005 10.249 12.0176 10.249C12.8153 10.249 13.6324 10.3463 14.4495 10.5214C14.683 10.5798 14.9165 10.5992 15.1499 10.5992C15.7336 10.5992 16.2589 10.4047 16.7452 10.035C16.8231 9.97665 16.9009 9.89884 16.9787 9.82101C17.0176 9.7821 17.0565 9.7432 17.0954 9.70429C17.1927 10.1518 17.1733 10.5409 16.9787 10.9494C16.8425 11.2412 16.6869 11.4553 16.4923 11.5914C16.2005 11.8054 16.1032 12.0973 16.0449 12.4475C15.8892 13.4202 15.5001 14.2374 14.8775 14.8599C14.2939 15.4436 13.5351 15.7938 12.6207 15.8716C12.3678 15.8911 12.1538 15.9105 11.9398 15.9105C11.434 15.9105 10.9865 15.8521 10.5585 15.716C9.56626 15.4047 8.82696 14.7237 8.34058 13.7121C8.12657 13.2451 7.99039 12.7393 7.93202 12.2724C7.91256 12.0389 7.81529 11.8444 7.62073 11.6887C7.01762 11.144 6.78416 10.5019 6.9398 9.72374ZM11.9593 17.0778C12.2122 17.0778 12.4651 17.0584 12.7375 17.0389C12.9709 17.0195 13.1849 16.9805 13.3795 16.9222C13.3989 16.9222 13.4184 16.9222 13.4184 16.9222C13.4573 16.9222 13.4768 16.9222 13.4962 17.0195C13.5157 17.1167 13.5351 17.214 13.5351 17.2918C13.574 17.5253 13.5935 17.7393 13.6713 17.9533C13.6908 18.0117 13.6713 18.0506 13.613 18.0895C13.5935 18.109 13.574 18.1284 13.574 18.1284L13.5546 18.1479V18.1673L13.3017 18.3813L13.2239 18.4397L13.0098 18.5759H12.9904H12.9515L12.932 18.5953L12.6986 18.6926C12.5235 18.7704 12.3484 18.8093 12.0954 18.8093C11.9787 18.8093 11.8814 18.8093 11.7647 18.7899H11.6091C11.5312 18.7899 11.4534 18.7704 11.4145 18.7315L11.1227 18.6148L11.0449 18.5953L10.9865 18.5564C10.9281 18.5175 10.8892 18.4981 10.8114 18.4592L10.7336 18.4202L10.5585 18.2646C10.3639 18.1284 10.325 17.9728 10.4028 17.7393C10.4612 17.5642 10.4807 17.3697 10.5001 17.1946C10.5001 17.1362 10.5196 17.0973 10.5196 17.0389C10.539 16.9611 10.5585 16.9611 10.5974 16.9611C10.6168 16.9611 10.6363 16.9611 10.6558 16.9611C11.0838 17.0389 11.4923 17.0778 11.9593 17.0778ZM12.0176 19.9572C12.1149 19.9572 12.2316 19.9572 12.3289 19.9377C13.1849 19.8794 13.9048 19.5097 14.5079 18.8677C14.6052 18.7704 14.683 18.7315 14.8192 18.7315H15.0137C15.4223 18.7315 15.8698 18.7315 16.2783 18.8483C17.076 19.0623 17.718 19.5486 18.2044 20.3074C18.2433 20.3852 18.3017 20.463 18.3406 20.5409C18.36 20.5798 18.3989 20.6381 18.4184 20.677C18.4379 20.6965 18.4573 20.7549 18.4379 20.7743C18.4379 20.7743 18.4184 20.7938 18.3795 20.7938C18.3795 20.7938 18.36 20.7938 18.3406 20.7938C18.3017 20.7938 18.2822 20.7938 18.2433 20.7938H18.0877H5.55848C5.77249 20.3463 6.04486 19.9767 6.35614 19.6459C6.9398 19.0623 7.6791 18.751 8.53513 18.7315C8.67132 18.7315 8.80751 18.7315 8.96315 18.7315C9.07988 18.7315 9.21607 18.7315 9.3328 18.7315C9.39116 18.7315 9.43008 18.751 9.44953 18.8093C10.1694 19.5681 11.0254 19.9572 12.0176 19.9572Z" fill="white"/>
                                    </svg>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Delivery-Man') }}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: {{ Request::is('seller/delivery-man*') ? 'block' : 'none' }}">
                                    <li class="nav-item {{ Request::is('seller/delivery-man/add') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{route('seller.delivery-man.add')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                          <path d="M12.1992 8.32715V15.6535" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                          <path d="M15.8646 11.9907H8.53125" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M16.8849 2H7.5135C4.24684 2 2.19922 4.31208 2.19922 7.58516V16.4148C2.19922 19.6879 4.23731 22 7.5135 22H16.8849C20.1611 22 22.1992 19.6879 22.1992 16.4148V7.58516C22.1992 4.31208 20.1611 2 16.8849 2Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                                <span class="text-truncate">{{\App\CPU\translate('Add_New')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ Request::is('seller/delivery-man/list') || Request::is('seller/delivery-man/earning-statement*') || Request::is('seller/delivery-man/earning-active-log*') || Request::is('seller/delivery-man/order-wise-earning*') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{route('seller.delivery-man.list')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M5.87389 21.9984C5.61804 21.9092 5.34404 21.8531 5.11131 21.7227C4.36854 21.3034 4.00045 20.6498 4.00045 19.7964C3.9988 17.8438 4.0021 15.8911 4.00375 13.9384C4.00705 11.106 4.01035 8.2752 4.01365 5.44276C4.01531 4.49696 4.4098 3.77729 5.27802 3.37619C5.55697 3.24745 5.88874 3.21113 6.20071 3.17812C6.50112 3.14511 6.80813 3.17152 7.1234 3.17152C7.12835 3.07083 7.12835 2.98665 7.13661 2.90412C7.19108 2.37428 7.59547 2.00124 8.13357 2.00124C10.4576 1.99959 12.7817 1.99959 15.1057 2.00124C15.6934 2.00124 16.0994 2.40399 16.1159 2.9883C16.1176 3.03947 16.1159 3.09064 16.1159 3.17152C16.2678 3.17152 16.4081 3.16492 16.5484 3.17317C16.9049 3.19298 17.2697 3.17317 17.613 3.25075C18.5539 3.46203 19.2124 4.30549 19.2306 5.27274C19.2405 5.73491 19.2339 6.19708 19.2323 6.65925C19.229 11.0136 19.224 15.3695 19.2191 19.7238C19.2174 20.696 18.7981 21.4107 17.9085 21.825C17.7352 21.9059 17.542 21.9439 17.3572 22C13.5294 21.9984 9.70165 21.9984 5.87389 21.9984ZM7.10194 4.3418C6.83784 4.3418 6.56549 4.3418 6.29479 4.3418C5.57348 4.3418 5.18394 4.72804 5.18394 5.44936C5.17898 10.2213 5.17568 14.9932 5.17238 19.7651C5.17238 20.4237 5.57348 20.8264 6.22712 20.8264C9.81389 20.8264 13.4007 20.8264 16.9874 20.8264C17.0782 20.8264 17.1706 20.8248 17.2581 20.805C17.7632 20.6993 18.0488 20.3098 18.0488 19.7288C18.0537 15.1005 18.057 10.4722 18.0603 5.84385C18.0603 5.65568 18.0653 5.46586 18.0587 5.27769C18.0438 4.86999 17.7731 4.4689 17.3786 4.40122C16.9693 4.33025 16.5451 4.3484 16.1308 4.32694C16.1209 4.39462 16.1159 4.41278 16.1143 4.43093C16.0845 5.1704 15.7247 5.51538 14.9852 5.51538C13.9305 5.51538 12.8758 5.51538 11.821 5.51538C10.5979 5.51538 9.37318 5.51703 8.15008 5.51538C7.69121 5.51538 7.32312 5.26614 7.19273 4.85514C7.14321 4.70163 7.13495 4.53657 7.10194 4.3418ZM8.30854 4.3319C10.5253 4.3319 12.7256 4.3319 14.9291 4.3319C14.9291 3.94235 14.9291 3.56271 14.9291 3.18637C12.7157 3.18637 10.5154 3.18637 8.30854 3.18637C8.30854 3.57097 8.30854 3.93905 8.30854 4.3319Z" fill="white"/>
                                            <path d="M13.9204 16.4509C14.5905 16.4509 15.259 16.4492 15.9291 16.4509C16.3121 16.4509 16.5729 16.6869 16.5762 17.0286C16.5795 17.3802 16.317 17.6212 15.9242 17.6212C14.579 17.6228 13.2337 17.6228 11.8868 17.6212C11.4956 17.6212 11.2249 17.3769 11.2266 17.0319C11.2282 16.6869 11.4989 16.4492 11.8918 16.4492C12.5685 16.4509 13.2453 16.4509 13.9204 16.4509Z" fill="white"/>
                                            <path d="M13.8924 9.81214C13.2222 9.81214 12.5521 9.81379 11.8836 9.81214C11.494 9.81214 11.2217 9.5662 11.2266 9.22123C11.2299 8.88285 11.4973 8.64186 11.8753 8.64186C13.2222 8.64021 14.5675 8.64021 15.9144 8.64186C16.2923 8.64186 16.5597 8.8845 16.563 9.22288C16.5663 9.5662 16.2923 9.81214 15.9028 9.81214C15.2327 9.81379 14.5625 9.81214 13.8924 9.81214Z" fill="white"/>
                                            <path d="M13.8874 13.7179C13.2239 13.7179 12.5603 13.7179 11.8968 13.7179C11.5006 13.7179 11.2316 13.4835 11.2266 13.1402C11.2217 12.7886 11.4957 12.5476 11.9017 12.5476C13.242 12.5476 14.5807 12.546 15.921 12.5476C16.3699 12.5476 16.6571 12.9091 16.5333 13.3069C16.4541 13.5594 16.2313 13.7162 15.9358 13.7179C15.2525 13.7195 14.5691 13.7179 13.8874 13.7179Z" fill="white"/>
                                            <path d="M7.944 13.2184C8.41113 12.7496 8.83533 12.3188 9.26614 11.8963C9.35197 11.8121 9.45761 11.7345 9.56985 11.6949C9.81249 11.6074 10.0815 11.7131 10.2218 11.9227C10.3704 12.1439 10.3704 12.4327 10.1806 12.6275C9.58141 13.2415 8.97398 13.849 8.35831 14.4481C8.13878 14.6611 7.82186 14.6611 7.59573 14.4547C7.31677 14.2005 7.04938 13.9348 6.79518 13.6558C6.58391 13.4247 6.61527 13.0715 6.8348 12.8619C7.04772 12.6572 7.38775 12.6391 7.61554 12.8404C7.73438 12.9444 7.82681 13.0814 7.944 13.2184Z" fill="white"/>
                                            <path d="M7.97893 17.142C8.08952 17.0067 8.1704 16.8961 8.26448 16.7987C8.61606 16.4422 8.96764 16.084 9.32747 15.7374C9.56681 15.5063 9.92829 15.5195 10.1528 15.7489C10.3723 15.975 10.3839 16.325 10.1544 16.5594C9.56681 17.1585 8.97424 17.7527 8.37342 18.3387C8.13408 18.5714 7.81056 18.5681 7.56462 18.3404C7.30383 18.0961 7.04963 17.8435 6.80534 17.5811C6.58416 17.3434 6.60232 16.9902 6.82515 16.7706C7.04798 16.5528 7.39626 16.5462 7.63725 16.7657C7.74619 16.8647 7.83862 16.9852 7.97893 17.142Z" fill="white"/>
                                            <path d="M7.96953 9.0408C8.10323 8.884 8.19401 8.7635 8.298 8.65786C8.63968 8.30959 8.983 7.96296 9.33458 7.62458C9.56732 7.40175 9.92385 7.41 10.1467 7.62788C10.3712 7.84741 10.3926 8.2056 10.1665 8.43668C9.57557 9.04246 8.97805 9.63997 8.37393 10.2309C8.13624 10.4636 7.80942 10.457 7.56513 10.2276C7.30929 9.9866 7.0617 9.73736 6.81906 9.48317C6.59457 9.24878 6.59953 8.89885 6.81741 8.67272C7.04024 8.43998 7.39512 8.43008 7.64106 8.65951C7.74835 8.7602 7.83583 8.88234 7.96953 9.0408Z" fill="white"/>
</svg>
                                            <span class="text-truncate">{{\App\CPU\translate('List')}}</span>
                                        </a>
                                    </li>
                                    <li class="d-none nav-item {{Request::is('seller/delivery-man/withdraw-list') || Request::is('seller/delivery-man/withdraw-view*')?'active':''}}">
                                        <a class="nav-link " href="{{route('seller.delivery-man.withdraw-list')}}"
                                           title="{{\App\CPU\translate('withdraws')}}">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M8.37526 21.1404C6.82271 21.1404 5.73793 20.0521 5.73793 18.4937V11.7476C5.73793 11.7476 5.58396 11.7112 5.5299 11.6982C5.41825 11.6724 5.31012 11.6465 5.202 11.6171C3.19227 11.0636 1.87948 9.17022 2.00876 7.01123C2.12394 5.08025 3.74818 3.342 5.70502 3.05406C5.92832 3.02115 6.15985 3.0047 6.39608 3.0047L7.30105 3.00352C9.23909 3.00235 11.1783 3 13.1163 3C14.6125 3 16.1074 3.00117 17.6036 3.00352C19.8542 3.00705 21.777 4.74294 21.9803 6.95364C22.1907 9.24191 20.6969 11.2399 18.4263 11.7029L18.2558 11.7382V13.809C18.2558 15.3722 18.2558 16.9353 18.2558 18.4996C18.2558 20.0533 17.1687 21.1393 15.6115 21.1393H11.9928L8.37526 21.1404ZM7.02486 18.5066C7.02486 19.3505 7.5314 19.8535 8.37995 19.8535H15.6162C16.4635 19.8535 16.9701 19.3493 16.9701 18.5055V8.0443H7.02486V18.5066ZM11.7636 4.28458C9.77149 4.28458 7.99917 4.2881 6.3432 4.29633C5.13383 4.30221 4.19713 4.88044 3.63417 5.96875C3.08179 7.03708 3.15348 8.14302 3.8422 9.16434C4.26412 9.78959 4.87645 10.208 5.66388 10.4066L5.73675 10.4254V7.54598C5.73675 6.96892 5.96476 6.74444 6.54887 6.74444H17.4508C18.029 6.74444 18.2547 6.97127 18.2547 7.55538V10.4195C18.2547 10.4195 18.3839 10.389 18.4074 10.3843C18.4545 10.3737 18.4921 10.3655 18.5285 10.3537C19.9788 9.90947 20.9061 8.45565 20.684 6.97245C20.4536 5.42343 19.1808 4.2975 17.66 4.29398C15.6973 4.28928 13.7298 4.28458 11.7636 4.28458Z" fill="white"/>
                                                    <path d="M11.9947 17.0122C11.5799 17.0122 11.1862 16.843 10.8559 16.5221C10.4775 16.1554 10.1014 15.7735 9.73821 15.4056L9.70882 15.3762C9.56779 15.2329 9.4867 15.0589 9.48082 14.8861C9.47494 14.7157 9.54311 14.5512 9.67239 14.4243C9.7958 14.3032 9.94623 14.2386 10.1096 14.2386C10.2859 14.2386 10.4634 14.3173 10.6091 14.4595C10.7478 14.5959 10.8841 14.7404 11.0169 14.8803C11.0769 14.9437 11.138 15.0084 11.1979 15.0718L11.2296 15.1047L11.353 15.0331V14.3784C11.353 13.343 11.353 12.3076 11.3542 11.271V11.2369C11.3542 11.157 11.3542 11.0747 11.3695 11.0007C11.4341 10.701 11.6892 10.5 12.003 10.5C12.0253 10.5 12.0476 10.5012 12.0711 10.5035C12.3932 10.5329 12.6364 10.8103 12.6388 11.1487C12.6423 11.9162 12.6411 12.6837 12.6411 13.4511V15.2211L12.9561 14.8967C13.0983 14.7498 13.2276 14.6158 13.3581 14.4854C13.5179 14.3267 13.7071 14.2386 13.8905 14.2386C14.0515 14.2386 14.2007 14.3032 14.3241 14.4266C14.5945 14.6981 14.571 15.0871 14.2654 15.3962C13.8916 15.7735 13.525 16.1437 13.1489 16.5116C12.8174 16.8348 12.4084 17.0122 11.9947 17.0122Z" fill="white"/>
                                                    </svg>
                                            <span class="text-truncate">{{\App\CPU\translate('withdraws')}}</span>
                                        </a>
                                    </li>

                                    <li class="d-none nav-item {{Request::is('seller/delivery-man/emergency-contact/') ? 'active' : ''}}">
                                        <a class="nav-link " href="{{route('seller.delivery-man.emergency-contact.index')}}"
                                           title="{{\App\CPU\translate('withdraws')}}">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M14.3516 2.5C18.0526 2.911 20.9766 5.831 21.3916 9.532" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M14.3516 6.04297C16.1226 6.38697 17.5066 7.77197 17.8516 9.54297" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0315 12.4724C15.0205 16.4604 15.9254 11.8467 18.4653 14.3848C20.9138 16.8328 22.3222 17.3232 19.2188 20.4247C18.8302 20.737 16.3613 24.4943 7.68447 15.8197C-0.993406 7.144 2.76157 4.67244 3.07394 4.28395C6.18377 1.17385 6.66682 2.58938 9.11539 5.03733C11.6541 7.5765 7.04253 8.48441 11.0315 12.4724Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                            <span class="text-truncate">{{\App\CPU\translate('Emergency_Contact')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
 <li
                            class=" navbar-vertical-aside-has-menu {{ Request::is('seller/business-settings/driver-page/*') || Request::is('seller/business-settings/vendor-page/*') || Request::is('seller/business-settings/terms-condition') || Request::is('seller/business-settings/page*') || Request::is('seller/business-settings/privacy-policy') || Request::is('seller/business-settings/about-us') || Request::is('seller/helpTopic/list') || Request::is('seller/business-settings/social-media') || Request::is('seller/file-manager*') || Request::is('seller/business-settings/features-section') ?'active':''}}">
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
                                style="display: {{ Request::is('seller/business-settings/vendor-page/*') || Request::is('seller/business-settings/terms-condition') || Request::is('seller/business-settings/page*') || Request::is('seller/business-settings/privacy-policy') || Request::is('seller/business-settings/about-us') || Request::is('seller/helpTopic/list') || Request::is('seller/business-settings/social-media') || Request::is('seller/file-manager*') || Request::is('seller/business-settings/features-section')?'block':'none'}}">
                               
 <li
                                    class="nav-item {{ Request::is('seller/business-settings/vendor-page/*') ?'active':''}}">
                                    <a class="nav-link"
                                        href="{{route('seller.business-settings.vendor.page',['vendor-privacy-policy'])}}"
                                        title="{{\App\CPU\translate('Vendor_policy_pages')}}">
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
                                            {{\App\CPU\translate('Seller_policy_pages')}}
                                        </span>
                                    </a>
                                </li>
 </ul>
                        </li>

                            <li class="nav-item {{Request::is('seller/orders/list/canceled')?'active':''}} ">
                                    <a class="nav-link js-navbar-vertical-aside-menu-link nav-link" href="javascript:" onclick="Swal.fire({
                                    title: '{{\App\CPU\translate('Do you want to logout')}}?',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#377dff',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: `Yes`,
                                    denyButtonText: `Don't Logout`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{route('seller.auth.logout')}}';
                                    } else{
                                    Swal.fire('Canceled', '', 'info')
                                    }
                                    })" title="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                                                <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                                                </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('LogOut')}}

                                        </span>
                                    </a>
                                </li>
                        {{-- @endif --}}
                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>

@push('script_2')
    <script>
        $(window).on('load' , function() {
            if($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });
        //Sidebar Menu Search
        var $rows = $('.navbar-vertical-content li');
        $('#search-bar-input').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>
@endpush
