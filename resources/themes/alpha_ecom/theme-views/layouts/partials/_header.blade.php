<style type="text/css">
    /* ============ desktop view ============ */
    @media all and (min-width: 992px) {

        .dropdown-menu li {
            position: relative;
        }

        .dropdown-menu .submenu {
            display: none;
            position: absolute;
            left: 100%;
            top: -7px;
        }

        .dropdown-menu .submenu-left {
            right: 100%;
            left: auto;
        }

        .dropdown-menu>li:hover {
            background-color: #f1f1f1
        }

        .dropdown-menu>li:hover>.submenu {
            display: block;
        }
    }

    /* ============ desktop view .end// ============ */

    /* ============ small devices ============ */
    @media (max-width: 991px) {

        .dropdown-menu .dropdown-menu {
            margin-left: 0.7rem;
            margin-right: 0.7rem;
            margin-bottom: .5rem;
        }

    }

    /* ============ small devices .end// ============ */
</style>


<!-- Top Offer Bar -->
@php
$customer_info = \App\CPU\customer_info();
@endphp

@if (isset($web_config['announcement']) && $web_config['announcement']['status']==1)
<div class="offer-bar py-3 d-none" data-bg-img="{{theme_asset('assets/img/media/top-offer-bg.png')}}">
    <div class="d-flex gap-2 align-items-center">
        <div class="offer-bar-close">
            <i class="bi bi-x-lg"></i>
        </div>
        <div class="top-offer-text flex-grow-1 d-flex justify-content-center fw-semibold">
            {{ $web_config['announcement']['announcement'] }}
        </div>
    </div>
</div>
@endif
@php($categories = \App\Model\Category::with('childes.childes')->where(['position'=> 0])->priority()->take(11)->get())
@php($brands = \App\Model\Brand::active()->take(15)->get())
<!-- Header -->

{{-- Unusable Code --}}
{{--
<header class="header d-none">
    <div class="header-top py-2">
        <div class="container">
            <div class="d-flex align-items-center flex-wrap justify-content-between gap-2">
                <a href="tel:+{{ $web_config['phone']->value }}" class="d-flex gap-2 align-items-center">
<i class="bi bi-telephone text-primary"></i>
{{ $web_config['phone']->value }}
</a>
<ul class="nav justify-content-center justify-content-sm-end align-items-center gap-4">
    <li>
        <div class="language-dropdown">
            @if($web_config['currency_model']=='multi_currency')
            <button type="button"
                class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0"
                data-bs-toggle="dropdown" aria-expanded="false">
                {{session('currency_code')}} {{session('currency_symbol')}}
            </button>
            <ul class="dropdown-menu" style="--bs-dropdown-min-width: 10rem">
                @foreach ($web_config['currencies'] as $key => $currency)
                <li onclick="currency_change('{{$currency['code']}}')"><a href="javascript:">{{ $currency->name }}</a></li>
                @endforeach
                <span id="currency-route" data-currency-route="{{route('currency.change')}}"></span>
            </ul>
            @endif
        </div>
    </li>
    <li>
        <div class="language-dropdown">
            <button type="button"
                class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0"
                data-bs-toggle="dropdown" aria-expanded="false">
                @php( $local = \App\CPU\Helpers::default_lang())
                @foreach(json_decode($language['value'],true) as $data)
                @if($data['code']==$local)
                <img width="20" src="{{theme_asset('assets/img/flags')}}/{{ $data['code'].'.png' }}"
                    class="dark-support" alt="Eng" />
                {{ ucwords($data['name']) }}
                @endif
                @endforeach
            </button>
            <ul class="dropdown-menu" style="--bs-dropdown-min-width: 10rem">
                @foreach(json_decode($language['value'],true) as $key =>$data)
                @if($data['status']==1)
                <li>
                    <a class="d-flex gap-2 align-items-center" href="{{route('lang',[$data['code']])}}">
                        <img width="20" src="{{theme_asset('assets/img/flags')}}/{{ $data['code'].'.png' }}"
                            loading="lazy" class="dark-support" alt="{{$data['name']}}" />
                        {{ ucwords($data['name']) }}
                    </a>
                </li>
                @endif
                @endforeach
            </ul>
        </div>
    </li>
    @if($web_config['seller_registration'])
    <li class="d-none d-xl-block">
        <a href="{{route('shop.apply')}}" class="d-flex">
            <div class="fz-16">{{ translate('Become_a')}} {{ translate('Seller')}}</div>
        </a>
    </li>
    @endif
</ul>
</div>
</div>
</div>
<div class="header-middle border-bottom py-2 d-none d-xl-block">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <a class="logo" href="{{route('home')}}">
                <img src="{{asset("storage/app/public/company")."/".$web_config['web_logo']->value}}"
                    class="dark-support svg h-45"
                    onerror="this.src='{{theme_asset('assets/img/image-place-holder-2:1.png')}}'" alt="Logo" />
            </a>
            <div class="search-box position-relative">
                <form action="{{route('products')}}" type="submit">
                    <div class="d-flex">
                        <div class="select-wrap focus-border border border-end-logical-0 d-flex align-items-center">
                            <div class="border-end">
                                <div class="dropdown search_dropdown">
                                    <button type="button"
                                        class="border-0 px-3 bg-transparent dropdown-toggle text-dark py-0"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ translate('All_Categories') }}
                                    </button>
                                    <input type="hidden" name="search_category_value" id="search_category_value"
                                        value="all">
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="d-flex" data-value="all" href="javascript:">
                                                {{ translate('All_Categories') }}
                                            </a>
                                        </li>
                                        @if($categories)
                                        @foreach($categories as $category)
                                        <li>
                                            <a class="d-flex" data-value="{{ $category->id }}" href="javascript:">
                                                {{ $category['name'] }}
                                            </a>
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <input type="search" class="form-control border-0 focus-input search-bar-input" name="name"
                                onkeyup="global_search(event)"
                                placeholder="{{ translate('Search_for_items_or_store') }}..." />
                        </div>
                        <input name="data_from" value="search" hidden>
                        <input name="page" value="1" hidden>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <div
                    class="card search-card __inline-13 position-absolute z-99 w-100 bg-white top-100 start-0 search-result-box">
                </div>
            </div>
            <div class="offer-btn">
                @if($web_config['header_banner'])
                <a href="{{ $web_config['header_banner']['url'] }}">
                    <img width="180"
                        src="{{asset('storage/app/public/banner')}}/{{$web_config['header_banner']['photo']}}"
                        onerror="this.src='{{theme_asset('assets/img/header-banner-placeholder.png')}}'" loading="lazy"
                        class="dark-support" alt="" />
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="header-main love-sticky py-2 py-lg-3 py-xl-0 shadow-sm">
    <div class="container">
        <!-- Aside -->
        <aside class="aside d-flex flex-column d-xl-none">
            <div class="aside-close p-3 pb-2">
                <i class="bi bi-x-lg"></i>
            </div>
            <!-- Aside Body -->
            <div>
                <div class="aside-body" data-trigger="scrollbar">
                    <!-- Search -->
                    <form action="{{route('products')}}" class="mb-3">
                        <div class="search-bar">
                            <input type="search" name="name" class="form-control search-bar-input-mobile"
                                autocomplete="off" placeholder="{{ translate('Search_for_items') }}...">
                            <input name="data_from" value="search" hidden="">
                            <input name="page" value="1" hidden="">
                            <button type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <div
                            class="card search-card __inline-13 position-absolute z-99 w-100 bg-white start-0 search-result-box d--none">
                        </div>
                    </form>

                    <!-- Nav -->
                    <ul class="main-nav nav">
                        <li>
                            <a href="{{route('categories')}}#">{{ translate('categories') }}</a>
                            <!-- Sub Menu -->
                            <ul class="sub_menu">
                                @foreach($categories as $key=>$category)
                                <li>
                                    <a href="javascript:" onclick="location.href='{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}'">
                                        {{ ucwords($category['name']) }}
                                    </a>
                                    @if ($category->childes->count() > 0)
                                    <ul class="sub_menu">
                                        @foreach($category['childes'] as $subCategory)
                                        <li>
                                            <a href="javascript:"
                                                onclick="location.href='{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}'">
                                                {{ ucwords($subCategory['name']) }}
                                            </a>
                                            @if($subCategory->childes->count()>0)
                                            <ul class="sub_menu">
                                                @foreach($subCategory['childes'] as $subSubCategory)
                                                <li>
                                                    <a
                                                        href="{{route('products',['id'=> $subSubCategory['id'],'data_from'=>'category','page'=>1])}}">
                                                        {{ ucwords($subSubCategory['name']) }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        <li>
                            <a href="{{route('home')}}">{{ translate('Home') }}</a>
                        </li>
                        @if($web_config['featured_deals']->count()>0 || $web_config['flash_deals'])
                        <li>
                            <a href="javascript:">{{ translate('Offers')}}</a>
                            <ul class="sub_menu">
                                @if($web_config['featured_deals']->count()>0)
                                <li><a
                                        href="{{route('products',['data_from'=>'featured_deal'])}}">{{ translate('Featured_Deal') }}</a>
                                </li>
                                @endif

                                @if($web_config['flash_deals'])
                                <li><a
                                        href="{{route('flash-deals',[$web_config['flash_deals']?$web_config['flash_deals']['id']:0])}}">{{ translate('Flash_Deal') }}</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if($web_config['business_mode'] == 'multi')
                        <li>
                            <a href="javascript:">{{ translate('stores') }}</a>
                            <!-- Sub Menu -->
                            <ul class="sub_menu">
                                @foreach($web_config['shops'] as $shop)
                                <li>
                                    <a
                                        href="{{route('shopView',['id'=>$shop['id']])}}">{{Str::limit($shop->name, 14)}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif

                        @if($web_config['brand_setting'])
                        <li>
                            <a href="javascript:">{{ translate('brands') }}</a>
                            <!-- Sub Menu -->
                            <ul class="sub_menu">
                                @foreach($brands as $brand)
                                <li>
                                    <a
                                        href="{{ route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1]) }}">{{ $brand->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif

                        <li>
                            <a class="d-flex gap-2 align-items-center"
                                href="{{route('products',['data_from'=>'discounted','page'=>1])}}">
                                {{ translate('Discounted_Products')}}
                                <i class="bi bi-patch-check-fill text-warning"></i>
                            </a>
                        </li>

                        @if($web_config['seller_registration'])
                        <li class="d-xl-none">
                            <a href="{{route('shop.apply')}}" class="d-flex">
                                <div class="fz-16">{{ translate('Become_a')}} {{ translate('Seller')}}</div>
                            </a>
                        </li>
                        @endif
                    </ul>
                    <!-- End Nav -->
                </div>

                <div class="d-flex align-items-center gap-2 justify-content-between p-4">
                    <span class="text-dark">{{ translate('theme_mode') }}</span>
                    <div class="theme-bar p-1">
                        <button class="light_button active">
                            <img src="{{theme_asset('assets/img/svg/light.svg')}}" alt="" class="svg">
                        </button>
                        <button class="dark_button">
                            <img src="{{theme_asset('assets/img/svg/dark.svg')}}" alt="" class="svg">
                        </button>
                    </div>
                </div>
            </div>

            @if(auth('customer')->check())
            <div class="d-flex justify-content-center mb-5 pb-5 mt-auto px-4">
                <a href="{{route('customer.auth.logout')}}" class="btn btn-primary w-100">{{ translate('logout') }}</a>
            </div>
            @else
            <div class="d-flex justify-content-center mb-5 pb-5 mt-auto px-4">
                <a href="" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn btn-primary w-100">
                    {{ translate('login') }} / {{ translate('register') }}
                </a>
            </div>
            @endif
        </aside>

        <div class="d-flex justify-content-between gap-3 align-items-center position-relative">
            <div class="d-flex align-items-center gap-3">
                <!-- Header Category Dropdown -->
                <div class="dropdown d-none d-xl-block">
                    <button
                        class="btn btn-primary rounded-0 text-uppercase fw-bold fs-14 dropdown-toggle select-category-button"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-list fs-4"></i>
                        {{ translate('Select_Category')}}
                    </button>
                    <ul class="dropdown-menu dropdown--menu">
                        @foreach($categories as $key=>$category)
                        @if($key<8)
                            <li class="{{ $category->childes->count() > 0 ? 'menu-item-has-children':'' }}">
                            <a href="javascript:"
                                onclick="location.href='{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}'">
                                {{$category['name']}}
                            </a>
                            @if ($category->childes->count() > 0)
                            <ul class="sub-menu">
                                @foreach($category['childes'] as $subCategory)
                                <li class="{{ $subCategory->childes->count()>0 ? 'menu-item-has-children':'' }}">
                                    <a href="javascript:"
                                        onclick="location.href='{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}'">
                                        {{$subCategory['name']}}
                                    </a>
                                    @if($subCategory->childes->count()>0)
                                    <ul class="sub-menu">
                                        @foreach($subCategory['childes'] as $subSubCategory)
                                        <li>
                                            <a
                                                href="{{route('products',['id'=> $subSubCategory['id'],'data_from'=>'category','page'=>1])}}">
                                                {{$subSubCategory['name']}}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            </li>
                            @endif
                            @endforeach
                            <li>
                                <a href="{{route('products',['data_from'=>'latest'])}}" class="btn-link text-primary">
                                    {{ translate('view_all') }}
                                </a>
                            </li>
                    </ul>
                </div>
                <!-- End Header Category Dropdown -->

                <!-- Main Nav -->
                <div class="nav-wrapper">
                    <div class="d-xl-none">
                        <a class="logo" href="{{route('home')}}">
                            <img width="123"
                                src="{{asset("storage/app/public/company")."/".$web_config['web_logo']->value}}"
                                onerror="this.src='{{theme_asset('assets/img/image-place-holder-2:1.png')}}'"
                                class="dark-support" alt="Logo" />
                        </a>
                    </div>
                    <ul class="nav main-menu align-items-center d-none d-xl-flex flex-nowrap">
                        <li class="{{request()->is('/')?'active':''}}">
                            <a href="{{route('home')}}">{{ translate('Home')}}</a>
                        </li>
                        @if($web_config['featured_deals']->count()>0 || $web_config['flash_deals'])
                        <li>
                            <a class="cursor-pointer">{{ translate('Offers')}}</a>
                            <ul class="sub-menu">
                                @if($web_config['featured_deals']->count()>0)
                                <li>
                                    <a
                                        href="{{route('products',['data_from'=>'featured_deal'])}}">{{ translate('Featured_Deal') }}</a>
                                </li>
                                @endif

                                @if($web_config['flash_deals'])
                                <li>
                                    <a
                                        href="{{route('flash-deals',[$web_config['flash_deals']?$web_config['flash_deals']['id']:0])}}">{{ translate('Flash_Deal') }}</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if($web_config['business_mode'] == 'multi')
                        <li>
                            <a class="cursor-pointer">{{ translate('stores') }}</a>
                            <div class="sub-menu megamenu p-3" style="--bs-dropdown-min-width: max-content">
                                <div class="d-flex gap-5">
                                    <div class="column-2 row-gap-3">
                                        @foreach($web_config['shops'] as $shop)
                                        <a href="{{route('shopView',['id'=>$shop['id']])}}"
                                            class="media gap-3 align-items-center border-bottom">
                                            <div class="avatar rounded" style="--size: 2.5rem">
                                                <img onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'"
                                                    src="{{asset("storage/app/public/shop")}}/{{ $shop->image }}"
                                                    loading="lazy" class="img-fit rounded dark-support" alt="" />
                                            </div>
                                            <div class="media-body text-truncate" style="--width: 7rem"
                                                title="Morning Mart">
                                                {{Str::limit($shop->name, 14)}}
                                            </div>
                                        </a>
                                        @endforeach
                                        <div class="d-flex">
                                            <a href="{{route('sellers')}}"
                                                class="fw-bold text-primary d-flex justify-content-center">
                                                {{ translate('view_all') }}...
                                            </a>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#">
                                            <img width="277" src="{{theme_asset('assets/img/media/super-market.png')}}"
                                                class="dark-support" alt="" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif

                        @if($web_config['brand_setting'])
                        <li>
                            <a class="cursor-pointer">{{ translate('brands') }}</a>
                            <div class="sub-menu megamenu p-3" style="--bs-dropdown-min-width: max-content">
                                <div class="d-flex gap-4">
                                    <div class="column-2">
                                        @foreach($brands as $brand)
                                        <a href="{{ route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1]) }}"
                                            class="media gap-3 align-items-center border-bottom">
                                            <div class="avatar rounded-circle" style="--size: 1.25rem">
                                                <img onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'"
                                                    src="{{asset("storage/app/public/brand")}}/{{ $brand->image }}"
                                                    loading="lazy" class="img-fit rounded-circle dark-support" alt="" />
                                            </div>
                                            <div class="media-body text-truncate" style="--width: 7rem" title="Bata">
                                                {{ $brand->name }}
                                            </div>
                                        </a>
                                        @endforeach
                                        <div class="d-flex">
                                            <a href="{{route('brands')}}"
                                                class="fw-bold text-primary d-flex justify-content-center">{{ translate('view_all') }}...
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif
                        @if ($web_config['discount_product']>0)
                        <li class="">
                            <a class="d-flex gap-2 align-items-center discount-product-menu {{request()->is('/')?'active':''}}"
                                href="{{route('products',['data_from'=>'discounted','page'=>1])}}">
                                {{ translate('discounted_products') }}
                                <i class="bi bi-patch-check-fill text-warning"></i></a>
                        </li>
                        @endif
                    </ul>
                </div>
                <!-- End Main Nav -->
            </div>
            <ul class="list-unstyled list-separator mb-0 pe-2">
                @if(auth('customer')->check())
                <li class="login-register d-flex align-items-center gap-4">
                    <div class="profile-dropdown">
                        <button type="button"
                            class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0 user"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="avatar overflow-hidden header-avatar rounded-circle" style="--size: 1.5rem">
                                <img loading="lazy"
                                    src="{{asset('storage/app/public/profile/'.auth('customer')->user()->image)}}"
                                    onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'"
                                    class="img-fit" alt="" />
                            </span>
                        </button>
                        <ul class="dropdown-menu" style="--bs-dropdown-min-width: 10rem">
                            <li><a href="{{route('account-oder')}}">{{ translate('my_order') }}</a></li>
                            <li><a href="{{route('user-profile')}}">{{ translate('my_profile') }}</a></li>
                            <li><a href="{{route('customer.auth.logout')}}">{{ translate('logout') }}</a></li>
                        </ul>
                    </div>
                    <div class="menu-btn d-xl-none">
                        <i class="bi bi-list fs-30"></i>
                    </div>
                </li>
                @else
                <li class="login-register d-flex gap-4">
                    <button class="media gap-2 align-items-center text-uppercase fs-12 bg-transparent border-0 p-0"
                        data-bs-toggle="modal" data-bs-target="#loginModal">
                        <span class="avatar header-avatar rounded-circle d-xl-none" style="--size: 1.5rem">
                            <img loading="lazy" src="{{theme_asset('assets/img/image-place-holder.png')}}"
                                class="img-fit rounded-circle" alt="" />
                        </span>
                        <span class="media-body d-none d-xl-block hover-primary">{{ translate('login') }} /
                            {{ translate('register') }}</span>
                    </button>
                    <div class="menu-btn d-xl-none">
                        <i class="bi bi-list fs-30"></i>
                    </div>
                </li>
                @endif
                <li class="d-none d-xl-block">
                    @if(auth('customer')->check())
                    <a href="{{ route('compare-list') }}" class="position-relative">
                        <i class="bi bi-repeat fs-18"></i>
                        <span
                            class="count compare_list_count_status">{{session()->has('compare_list')?count(session('compare_list')):0}}</span>
                    </a>
                    @else
                    <a href="javascript:" class="position-relative" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="bi bi-repeat fs-18"></i>
                    </a>
                    @endif
                </li>
                <li class="d-none d-xl-block">
                    @if(auth('customer')->check())
                    <a href="{{ route('wishlists') }}" class="position-relative">
                        <i class="bi bi-heart fs-18"></i>
                        <span
                            class="count wishlist_count_status">{{session()->has('wish_list')?count(session('wish_list')):0}}</span>
                    </a>
                    @else
                    <a href="javascript:" class="position-relative" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="bi bi-heart fs-18"></i>
                    </a>
                    @endif

                </li>
                <li class="d-none d-xl-block" id="cart_items">
                    @include('theme-views.layouts.partials._cart')
                </li>
            </ul>
        </div>
    </div>
</div>
</header>
--}}

{{-- Unusable Code --}}

<div id="rotation-notification" class="rotation-notification">
    <div class="orientation-disable">
        <div id="notice">
            <svg viewbox="0 0 512 512.001" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="m187.886719 7.566406h-170.5625c-5.300781 0-9.601563 4.296875-9.601563 9.601563v349.027343c0 5.304688 4.296875 9.601563 9.601563 9.601563h170.5625c5.300781 0 9.601562-4.296875 9.601562-9.601563v-349.027343c0-5.304688-4.300781-9.601563-9.601562-9.601563zm0 0"
                    fill="#8ac2ff"></path>
                <path
                    d="m136.492188 324.402344v170.5625c0 5.300781 4.296874 9.601562 9.601562 9.601562h349.027344c5.300781 0 9.601562-4.300781 9.601562-9.601562v-170.5625c0-5.300782-4.296875-9.601563-9.601562-9.601563h-349.027344c-5.304688 0-9.601562 4.300781-9.601562 9.601563zm0 0"
                    fill="#8ac2ff"></path>
                <g fill="#54a0ff">
                    <path
                        d="m37.726562 366.195312v-349.027343c0-5.304688 4.296876-9.601563 9.597657-9.601563h-30c-5.304688 0-9.601563 4.296875-9.601563 9.601563v349.027343c0 5.304688 4.296875 9.601563 9.601563 9.601563h30c-5.300781 0-9.597657-4.296875-9.597657-9.601563zm0 0">
                    </path>
                    <path
                        d="m166.492188 494.964844v-170.5625c0-5.300782 4.296874-9.601563 9.601562-9.601563h-30.003906c-5.300782 0-9.597656 4.296875-9.597656 9.601563v170.5625c0 5.300781 4.296874 9.601562 9.597656 9.601562h30.003906c-5.300781 0-9.601562-4.300781-9.601562-9.601562zm0 0">
                    </path>
                    <path
                        d="m146.09375 314.800781c-5.304688 0-9.601562 4.300781-9.601562 9.601563v51.394531h51.394531c5.300781 0 9.601562-4.296875 9.601562-9.601563v-51.394531zm0 0">
                    </path>
                </g>
                <path
                    d="m166.492188 324.402344c0-5.300782 4.296874-9.601563 9.601562-9.601563h-30.003906c-5.300782 0-9.597656 4.296875-9.597656 9.601563v51.394531h30zm0 0"
                    fill="#338def"></path>
                <path
                    d="m463.453125 245.71875h-52.003906c-2.519531 0-3.777344 3.042969-2 4.824219l26.003906 26c1.101563 1.105469 2.890625 1.105469 3.996094 0l26-26c1.78125-1.78125.519531-4.824219-1.996094-4.824219zm0 0"
                    fill="#e4eaf8"></path>
                <path
                    d="m261.347656 46.351562-26 26c-1.105468 1.105469-1.105468 2.894532 0 4l26 26c1.78125 1.78125 4.824219.519532 4.824219-2v-52c0-2.519531-3.042969-3.777343-4.824219-2zm0 0"
                    fill="#e4eaf8"></path>
                <path
                    d="m431.683594 250.542969c-1.777344-1.78125-.519532-4.824219 2-4.824219h-22.234375c-2.519531 0-3.777344 3.042969-2 4.824219l26.003906 26c1.101563 1.105469 2.890625 1.105469 3.996094 0l9.117187-9.117188zm0 0"
                    fill="#c7d2e5"></path>
                <path
                    d="m257.582031 76.351562c-1.105469-1.105468-1.105469-2.894531 0-4l8.589844-8.589843v-15.410157c0-2.519531-3.046875-3.78125-4.824219-2l-26 26c-1.105468 1.105469-1.105468 2.894532 0 4l26 26c1.78125 1.78125 4.824219.519532 4.824219-2v-15.410156zm0 0"
                    fill="#c7d2e5"></path>
                <path
                    d="m404.410156 255.78125 26.003906 26c4.027344 4.027344 10.574219 4.027344 14.601563 0l26.003906-26c6.488281-6.492188 1.886719-17.628906-7.304687-17.628906h-18.640625c-3.5-92.816406-78.332031-167.679688-171.136719-171.222656v-18.644532c0-9.175781-11.128906-13.800781-17.628906-7.304687l-26 26.003906c-4.027344 4.023437-4.027344 10.578125 0 14.605469l26 25.996094c6.484375 6.488281 17.628906 1.910156 17.628906-7.300782v-18.34375c84.53125 3.527344 152.644531 71.667969 156.125 156.210938h-18.347656c-9.191406 0-13.792969 11.140625-7.304688 17.628906zm-145.476562-166.78125-14.714844-14.714844 14.714844-14.714844zm193.496094 164.152344-14.714844 14.71875-14.714844-14.71875zm0 0">
                </path>
                <path
                    d="m494.898438 307.234375h-290.132813v-98.117187c0-4.144532-3.359375-7.5-7.5-7.5-4.144531 0-7.5 3.355468-7.5 7.5v98.117187h-43.894531c-9.429688 0-17.101563 7.671875-17.101563 17.101563v43.894531h-111.667969c-1.15625 0-2.101562-.941407-2.101562-2.097657v-349.03125c0-1.160156.945312-2.101562 2.101562-2.101562h170.5625c1.15625 0 2.101563.941406 2.101563 2.101562v157.015626c0 4.140624 3.355469 7.5 7.5 7.5s7.5-3.359376 7.5-7.5v-157.015626c0-9.429687-7.671875-17.101562-17.101563-17.101562h-170.5625c-9.429687 0-17.101562 7.671875-17.101562 17.101562v349.03125c0 9.429688 7.671875 17.101563 17.101562 17.101563h111.667969v111.664063c0 9.429687 7.671875 17.101562 17.101563 17.101562h155.195312c4.140625 0 7.5-3.355469 7.5-7.5s-3.359375-7.5-7.5-7.5h-155.195312c-1.160156 0-2.101563-.941406-2.101563-2.101562v-111.667969h43.894531c9.429688 0 17.101563-7.667969 17.101563-17.101563v-43.894531h290.132813c1.15625 0 2.101562.941406 2.101562 2.101563v170.5625c0 1.15625-.941406 2.101562-2.101562 2.101562h-158.832032c-4.144531 0-7.5 3.355469-7.5 7.5s3.355469 7.5 7.5 7.5h158.832032c9.429687 0 17.101562-7.671875 17.101562-17.101562v-170.5625c0-9.429688-7.671875-17.101563-17.101562-17.101563zm-305.132813 58.894531c0 1.160156-.945313 2.101563-2.101563 2.101563h-43.894531v-43.894531c0-1.15625.941407-2.101563 2.101563-2.101563h43.894531zm0 0">
                </path>
            </svg>
            <h4>Please rotate your device.</h4>
            <p>We don't Support landscape mode yet. Please go back to portait mode for the best exprience</p>
        </div>
    </div>
</div>
<?php 
 $app_marque_text = \App\Model\BusinessSetting::where('type', 'app_marque_text')->first()->value;?>
<div class="page-header">
    <div class="marquee">
         <span>{{ $app_marque_text ?? '' }}</span>
    </div>
    <header class="p-2 header-top-bg-dark ">
        <div class="container-fluid px-md-5 mb-3 d-none">
            <div class="d-flex justify-content-between gap-3">
                <div class=""><a href="" class=""><i class="fa-solid fa-phone"></i> 9087654321</a>
                </div>
                <div class=""><a href="" class=""><i class="fa-solid fa-location-dot"></i> Vijay Nagar
                        Indore
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid px-md-5 top-header-phone">
            <div
                class="d-flex align-items-center justify-content-lg-between justify-content-md-between justify-content-between justify-content-lg-start">
                <a href="{{route('home')}}" class="d-flex align-items-center mb-2 mb-lg-0 text-decoration-none">
                    <img src="{{ theme_asset('assets/images/primeLogo.png')}}" alt="logo" class="navbar__brand">
                </a>
                <div class="col-md-5 ">
                    <div class="navMenu">
                        <a href="{{route('home')}}">Home</a>
                        <a href="{{route('about-us')}}">About</a>
                        <a href="{{route('contacts')}}">Contact</a>
                    </div>
                </div>

                <ul class="header-right-options col-md-6">
                    <!-- <div class="locationInput">
                        <img src="{{ theme_asset('assets/images/location-pin.png')}}" alt="">
                        <input type="text" placeholder="Current location">
                    </div> -->

                    <div class="locationInput">
                        <img src="{{ theme_asset('assets/images/location-pin.png')}}" alt="">
                        <input 
                            type="text" 
                            class="form-control location-input" 
                            placeholder="Current location" 
                            readonly
                        >
                    </div>

                    <!-- Hidden fields (keep them once, outside) -->
                    <input type="hidden" id="lat" name="latitude">
                    <input type="hidden" id="lng" name="longitude">
                    <input type="hidden" id="pincode" name="pincode">
                    <input type="hidden" id="area" name="area">
                    <input type="hidden" id="city" name="city">
                    <input type="hidden" id="state" name="state">

                    <div class="col-md-4 col-lg-4 col-xl-4 col-12 position-relative">
                        <form action="{{route('products')}}" type="submit">
                            <img class="searchImgClas" src="{{ theme_asset('assets/images/search.png')}}" alt="">
                            <input name="data_from" value="search" hidden>
                            <input type="search"
                                class="form-control w-100 border-0 focus-input search-bar-input custom-control-search-topheader"
                                placeholder="Search Products" name="name" aria-label="Search"
                                onkeyup="global_search(event)">

                            {{-- <input type="search" class="form-control border-0 focus-input search-bar-input custom-control-search-topheader px-md-5 px-3 py-md-" name="name"
                            onkeyup="global_search()"
                            placeholder="{{ translate('Search_for_items_or_store') }}..." /> --}}

                        </form>
                        <div class="card search-card __inline-13 position-absolute z-99 w-100 bg-white top-100 start-0 search-result-box">
                        </div>
                    </div>
                    <li data-bs-toggle="collapse" class="profileBtnToggle" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <span class="my__set">
                            @if(auth('customer')->check())

                            <!-- <i class="fa-solid fa-angle-down"></i> -->
                            <img src="{{ theme_asset('assets/images/user-profile1.png')}}" alt="">
                            @else
                            <button data-bs-toggle="modal" data-bs-target="#auth-model" class="loginBtn">{{translate('Login')}} </button>
                            @endif
                        </span>
                    </li>


                    <li class="nav-item dropdown cart-dropdown">
                        @if(auth('customer')->check())
                        <a href="{{route('shop-cart')}}" class="nav-link position-relative">
                            @php($cart=\App\CPU\CartManager::get_cart())
                            <img src="{{ theme_asset('assets/images/bag.png')}}" alt="">
                            <span class="countBadge">{{ $cart->count() }}</span>
                        </a>
                        <!-- Dropdown -->
                        <ul class="dropdown-menu cart-menu">
                            @foreach($cart as $item)
                            <li class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img src="{{asset('storage/app/public/product/thumbnail')}}/{{$item['thumbnail']}}" width="40" class="me-2">
                                    <div class="cartname">
                                        <span>{{ $item['name'] }}</span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                            <a href="{{route('shop-cart')}}" class="viewCartBtn">View Cart</a>
                        </ul>
                        @else
                        <a href="javascript:void(0)" class="nav-link position-relative" data-bs-toggle="modal" data-bs-target="#auth-model">
                            @php($cart=\App\CPU\CartManager::get_cart())
                            <img src="{{ theme_asset('assets/images/bag.png')}}" alt="">
                            <span class="countBadge">{{ $cart->count() }}</span>
                        </a>
                        <!-- Dropdown -->
                        <ul class="dropdown-menu cart-menu">
                            <li class="dropdown-item text-center">Login to view cart</li>
                        </ul>
                        @endif
                    </li>

                    <li>
                        <ul class="notification-drop br-12">
                            @if(auth('customer')->check())
                            @php($noti=\App\CPU\Helpers::get_notification(auth('customer')->user()->id))
                            @endif
                            <li class="item">
                                <span class="nav-link position-relative"
                                    onclick="toggleNavs('mySidenav', '25%')">
                                    <img src="{{ theme_asset('assets/images/notification.png')}}" alt="">
                                    <span
                                        class="countBadge">
                                        {{ $noti['notification_count'] ?? 0 }}
                                        <span class="visually-hidden">{{ translate('unread messages') }}</span>
                                    </span>
                                </span>
                                <ul class="text-left" id="mySidenav">
                                    <li class="notification-header">
                                        <h4 class="notification-title">{{ translate('Notification') }}</h4>
                                        <button type="button" onclick="toggleNavs('mySidenav', '0')"
                                            class="close-btns"><i class="fa-solid fa-x"></i></button>
                                    </li>
                                    @if(isset($noti['notification_count']) && $noti['notification_count'] > 0)
                                    <li class="">
                                        <a href="javascript:void(0)" class="all-notification mark-as-read">Mark as read</a>
                                    </li>
                                    @endif
                                    <!-- Modal body -->
                                    @if(isset($noti['notifications']) && !empty($noti['notifications']))
                                    @foreach($noti['notifications'] as $notification)
                                    @php($orderKey=explode('-', $notification['type_id']))
                                    @php($redirectUrl=$notification['type']=='order'?route('account-order-details', ['id' => $orderKey[0]??'']):'javascript:void(0)')

                                    <li class="modal-notification {{ $notification['is_read'] == '0' ? 'unread' : '' }}">
                                        <div class="model-img">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                viewBox="0 0 48 48" fill="none">
                                                <rect width="48" height="48" rx="8" fill="#D9D9D9"
                                                    fill-opacity="0.11" />
                                                <path
                                                    d="M22.1285 35.2356C21.9036 35.1594 21.6665 35.1064 21.4555 35.0023C20.6514 34.6026 20.2312 33.6925 20.4265 32.8131C20.4423 32.7434 20.4571 32.6737 20.4776 32.5788C20.1401 32.5788 19.8148 32.5788 19.4904 32.5788C19.2366 32.5788 18.9819 32.5854 18.729 32.5751C18.0523 32.5491 17.5252 32.1484 17.316 31.5117C17.1069 30.873 17.2956 30.2437 17.8338 29.8235C18.3934 29.3866 18.9568 28.9534 19.5247 28.5277C19.6447 28.4375 19.6828 28.3576 19.6465 28.206C18.6937 24.2051 17.7455 20.2041 16.802 16.2012C16.762 16.033 16.696 15.9874 16.5277 15.9902C15.9328 16.0014 15.3379 15.9958 14.7438 15.9939C14.3134 15.9921 14.0085 15.7206 14.0002 15.3386C13.9918 14.9509 14.3032 14.6609 14.7438 14.659C15.5563 14.6553 16.3688 14.6553 17.1812 14.659C17.6098 14.6609 17.831 14.8384 17.9407 15.2596C18.1201 15.9521 18.3014 16.6447 18.4715 17.3391C18.5087 17.4897 18.5756 17.5454 18.7299 17.565C19.1901 17.6217 19.6484 17.696 20.1057 17.7713C20.4878 17.8345 20.7323 18.1506 20.6886 18.5066C20.644 18.8673 20.3344 19.1192 19.9486 19.083C19.5973 19.0495 19.2477 18.99 18.8601 18.937C19.5638 21.9731 20.261 24.9813 20.9638 28.0155C21.0456 28.0155 21.1227 28.0155 21.1999 28.0155C24.8272 28.0155 28.4535 28.0155 32.0808 28.0155C32.4154 28.0155 32.4461 27.9866 32.4526 27.6641C32.4945 25.5437 32.5344 23.4233 32.5809 21.3029C32.5856 21.0956 32.5465 20.9719 32.316 20.9124C31.9841 20.826 31.8047 20.495 31.8679 20.1687C31.933 19.8304 32.2249 19.6101 32.5781 19.6333C33.3032 19.6816 33.9167 20.3658 33.9167 21.1495C33.9167 21.7862 33.8926 22.423 33.8805 23.0598C33.8498 24.6447 33.8238 26.2297 33.7885 27.8147C33.7689 28.7062 33.1015 29.3373 32.1784 29.3392C30.0459 29.3429 27.9134 29.3401 25.7809 29.3401C24.142 29.3401 22.5022 29.3373 20.8634 29.3457C20.7444 29.3466 20.6068 29.3941 20.512 29.4647C19.9189 29.9063 19.336 30.3599 18.7504 30.8108C18.6491 30.8888 18.4948 30.9697 18.5849 31.1147C18.6333 31.1919 18.7876 31.2374 18.8945 31.2374C23.667 31.243 28.4405 31.2421 33.213 31.243C33.6109 31.243 33.8684 31.402 33.966 31.706C34.1073 32.1447 33.7931 32.5649 33.3013 32.5761C32.83 32.5863 32.3587 32.5788 31.8865 32.5798C31.8437 32.5798 31.801 32.5863 31.8047 32.5854C31.8047 32.9181 31.8437 33.2444 31.7973 33.5568C31.669 34.4129 31.0471 35.0283 30.1928 35.1984C30.1584 35.205 30.1259 35.2235 30.0933 35.2356C29.9046 35.2356 29.7159 35.2356 29.5272 35.2356C29.1507 35.1315 28.7826 35.0069 28.4879 34.7346C27.8502 34.1461 27.6829 33.4276 27.906 32.5937C26.7049 32.5937 25.5095 32.5937 24.3038 32.5937C24.3233 32.6802 24.3391 32.7499 24.354 32.8196C24.565 33.7855 24.0351 34.7587 23.1055 35.1018C22.9577 35.1566 22.8025 35.191 22.65 35.2356C22.4762 35.2356 22.3024 35.2356 22.1285 35.2356ZM22.3823 32.577C22.0151 32.5798 21.7121 32.8763 21.7093 33.2333C21.7065 33.5977 22.0198 33.8998 22.3972 33.897C22.7653 33.8942 23.0674 33.5986 23.0702 33.2407C23.0721 32.8754 22.7597 32.5733 22.3823 32.577ZM29.8237 33.897C30.1965 33.8961 30.4912 33.6088 30.494 33.2435C30.4968 32.8679 30.1984 32.5751 29.8154 32.577C29.4463 32.5788 29.1433 32.8717 29.1386 33.2305C29.1349 33.594 29.4463 33.8979 29.8237 33.897Z"
                                                    fill="#040D12" />
                                                <path
                                                    d="M26.1967 22.6408C23.5352 22.639 21.3572 20.4758 21.3525 17.8274C21.3479 15.1715 23.5324 12.9972 26.2032 13C28.8767 13.0028 31.0538 15.1762 31.0464 17.8339C31.0389 20.4832 28.86 22.6427 26.1967 22.6408ZM26.2134 14.3349C24.2854 14.3265 22.7051 15.8715 22.6921 17.78C22.6791 19.7247 24.2278 21.2938 26.1753 21.3069C28.1154 21.3199 29.6938 19.7749 29.7059 17.8516C29.718 15.9078 28.1637 14.3433 26.2134 14.3349Z"
                                                    fill="#040D12" />
                                                <path
                                                    d="M25.5558 18.0834C25.7352 17.8938 25.9091 17.7116 26.082 17.5285C26.504 17.0813 26.9242 16.6314 27.349 16.1871C27.6418 15.8803 28.0555 15.8561 28.3325 16.122C28.6095 16.3869 28.6002 16.7959 28.3074 17.1074C27.5684 17.8929 26.8284 18.6774 26.0866 19.4602C25.7436 19.8227 25.2649 19.805 24.9386 19.4211C24.6244 19.0511 24.312 18.6812 23.9997 18.3102C23.7087 17.9635 23.7199 17.5638 24.0248 17.3091C24.3232 17.0599 24.735 17.1194 25.0194 17.4541C25.1961 17.6577 25.368 17.8622 25.5558 18.0834Z"
                                                    fill="#040D12" />
                                            </svg>
                                        </div>
                                        <div class="model-p">
                                            <h5><a href="{{$redirectUrl}}">{{$notification['title']}}</a></h5>
                                            <p><a href="{{$redirectUrl}}">{{$notification['message']}}</a></p>
                                        </div>
                                        <div class="model-d">{{date('d M Y', strtotime($notification['created_at']))}}</div>
                                    </li>
                                    @endforeach
                                    @else
                                    <li class="modal-notification">
                                        <div class="col-lg-12">
                                            <div class="empty-content">
                                                <div>
                                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                        viewBox="0 0 212 180" style="enable-background:new 0 0 212 180;" xml:space="preserve">
                                                        <style type="text/css">
                                                            .st0 {
                                                                fill: #E3E1EC;
                                                            }

                                                            .st1 {
                                                                fill: #0A9494;
                                                            }
                                                        </style>
                                                        <g>
                                                            <path class="st0" d="M184.52,35.28H124.3c-1.24,0-2.26,1.01-2.26,2.26c0,1.24,1.01,2.26,2.26,2.26h60.22
                                                                    c1.25,0,2.26-1.01,2.26-2.26C186.78,36.29,185.77,35.28,184.52,35.28z" />
                                                            <path class="st0" d="M114.25,35.28h-15.2c-1.24,0-2.26,1.01-2.26,2.26c0,1.24,1.01,2.26,2.26,2.26h15.2c1.24,0,2.26-1.01,2.26-2.26
                                                                    C116.51,36.29,115.5,35.28,114.25,35.28z" />
                                                            <path class="st0" d="M186.92,175.19H126.7c-1.24,0-2.26,1.01-2.26,2.26c0,1.24,1.01,2.26,2.26,2.26h60.22
                                                                    c1.24,0,2.26-1.01,2.26-2.26C189.17,176.2,188.16,175.19,186.92,175.19z" />
                                                            <path class="st0" d="M116.65,175.19h-15.2c-1.24,0-2.26,1.01-2.26,2.26c0,1.24,1.01,2.26,2.26,2.26h15.2
                                                                    c1.24,0,2.26-1.01,2.26-2.26C118.91,176.2,117.89,175.19,116.65,175.19z" />
                                                            <path class="st0" d="M2.15,161.51h42.86c0.89,0,1.61-0.72,1.61-1.61c0-0.89-0.72-1.61-1.61-1.61H2.15c-0.89,0-1.61,0.72-1.61,1.61
                                                                    C0.54,160.8,1.26,161.51,2.15,161.51z" />
                                                            <path class="st0" d="M52.17,161.51h10.82c0.89,0,1.61-0.72,1.61-1.61c0-0.89-0.72-1.61-1.61-1.61H52.17
                                                                    c-0.89,0-1.61,0.72-1.61,1.61C50.56,160.8,51.28,161.51,52.17,161.51z" />
                                                            <path class="st0" d="M148.57,169.69h42.86c0.89,0,1.61-0.72,1.61-1.61c0-0.89-0.72-1.61-1.61-1.61h-42.86
                                                                    c-0.89,0-1.61,0.72-1.61,1.61C146.96,168.97,147.68,169.69,148.57,169.69z" />
                                                            <path class="st0" d="M198.59,169.69h10.82c0.89,0,1.61-0.72,1.61-1.61c0-0.89-0.72-1.61-1.61-1.61h-10.82
                                                                    c-0.89,0-1.61,0.72-1.61,1.61C196.98,168.97,197.69,169.69,198.59,169.69z" />
                                                            <path class="st0" d="M102.89,0H72.78c-0.62,0-1.13,0.51-1.13,1.13c0,0.62,0.51,1.13,1.13,1.13h30.11c0.62,0,1.13-0.51,1.13-1.13
                                                                    C104.02,0.51,103.52,0,102.89,0z" />
                                                            <path class="st0" d="M67.76,0h-7.6c-0.62,0-1.13,0.51-1.13,1.13c0,0.62,0.51,1.13,1.13,1.13h7.6c0.62,0,1.13-0.51,1.13-1.13
                                                                    C68.89,0.51,68.38,0,67.76,0z" />
                                                            <path class="st0" d="M82.38,165.35H52.27c-0.62,0-1.13,0.51-1.13,1.13c0,0.62,0.51,1.13,1.13,1.13h30.11
                                                                    c0.62,0,1.13-0.51,1.13-1.13C83.51,165.86,83,165.35,82.38,165.35z" />
                                                            <path class="st0" d="M47.25,165.35h-7.6c-0.62,0-1.13,0.51-1.13,1.13c0,0.62,0.51,1.13,1.13,1.13h7.6c0.62,0,1.13-0.51,1.13-1.13
                                                                    C48.38,165.86,47.87,165.35,47.25,165.35z" />
                                                            <path class="st1" d="M105.5,118.83c-13.01,0-24.76-0.91-25.72-11.95c0-0.06-0.01-0.12-0.01-0.17c0-4.4,1.4-6.72,2.63-8.78
                                                                    c1.26-2.1,2.45-4.07,2.45-8.74c0-8.98,8.3-18.6,20.65-18.6c12.35,0,20.65,9.62,20.65,18.6c0,4.67,1.19,6.65,2.45,8.74
                                                                    c1.23,2.05,2.63,4.38,2.63,8.78c0,0.06,0,0.12-0.01,0.17C130.26,117.91,118.51,118.83,105.5,118.83z M83.77,106.61
                                                                    c0.51,5.45,4.06,8.21,21.73,8.21c17.57,0,21.23-2.89,21.73-8.21c-0.02-3.23-0.92-4.73-2.06-6.63c-1.41-2.36-3.02-5.02-3.02-10.8
                                                                    c0-6.9-6.84-14.6-16.65-14.6c-9.81,0-16.65,7.69-16.65,14.6c0,5.78-1.6,8.45-3.02,10.8C84.69,101.88,83.79,103.38,83.77,106.61z" />
                                                            <path class="st1" d="M105.36,130.42C105.36,130.42,105.36,130.42,105.36,130.42c-3.11,0-6.05-1.37-8.28-3.85
                                                                    c-0.74-0.82-0.67-2.09,0.15-2.82c0.82-0.74,2.09-0.67,2.82,0.15c1.46,1.63,3.35,2.53,5.3,2.53c0,0,0,0,0,0
                                                                    c1.96,0,3.85-0.9,5.32-2.53c0.74-0.82,2-0.89,2.82-0.15c0.82,0.74,0.89,2,0.15,2.82C111.42,129.05,108.47,130.42,105.36,130.42z" />
                                                        </g>
                                                    </svg>
                                                    <h3>{{translate('No_Notification')}}</h3>
                                                    <p>{{translate('We_notify_you_went_there_is_something_for_you')}}</p>

                                                </div>
                                            </div>
                                        </div>

                                    </li>
                                    @endif


                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown d-none">
                        <a href="#" class="nav-link dropdown-toggle toggle-bgn"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ theme_asset('assets/images/cuntry-logo.png')}}" alt="" class="me-2 wp-24">
                            EN
                            <i class="fa-solid fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" title="English" href="#">EN</a></li>
                            <li><a class="dropdown-item" title="Spanish" href="#">ES</a></li>
                            <li><a class="dropdown-item" title="French" href="#">FR</a></li>
                            <li><a class="dropdown-item" title="German" href="#">DE</a></li>
                            <li><a class="dropdown-item" title="Chinese" href="#">ZH</a></li>
                            <li><a class="dropdown-item" title="Hindi" href="#">HI</a></li>
                            <li><a class="dropdown-item" title="Russian" href="#">RU</a></li>
                            <li><a class="dropdown-item" title="Arabic" href="#">AR</a></li>
                            <li><a class="dropdown-item" title="Portuguese" href="#">PT</a></li>
                            <li><a class="dropdown-item" title="Japanese" href="#">JA</a></li>
                        </ul>
                    </li>
                    <li class="dropdown" style="display: none;">
                        @php($currency_model = \App\CPU\Helpers::get_business_settings('currency_model'))
                        @if($currency_model=='multi_currency')
                        <a href="#" class="nav-link dropdown-toggle toggle-bgn"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{session('currency_code')}} {{session('currency_symbol')}}
                            <i class="fa-solid fa-angle-down"></i>
                        </a>

                        <div class="topbar-text dropdown disable-autohide {{Session::get('direction') === "rtl" ? 'mr-4' : 'mr-4'}}">
                            <ul class="dropdown-menu end-0">
                                @foreach (\App\Model\Currency::where('status', 1)->get() as $key => $currency)
                                <li class="dropdown-item cursor-pointer"
                                    onclick="currency_change('{{$currency['code']}}')">
                                    {{ $currency->name }}
                                </li>
                                @endforeach
                                <span id="currency-route" data-currency-route="{{route('currency.change')}}"></span>
                            </ul>
                        </div>
                        @endif

                    </li>
                </ul>
            </div>
        </div>
    </header>

    <header class="header-for-mobile">
        <a href="{{ route('home') }}" class="d-flex align-items-center mb-2 mb-lg-0  text-decoration-none">
            <img src="{{ theme_asset('assets/images/primeLogo.png') }}" alt="" class="navbar__brand">
        </a>
        <div class="search-for-mobile">
            <div class="search-toggle">
                <button class="search-icon icon-search">
                    <img src="{{ theme_asset('assets/images/Search.svg') }}" alt="">

                </button>
                <button class="search-icon icon-close"><i class="fa fa-fw  fa-close"></i></button>
            </div>
            <div class="search-container">
                <form action="{{route('products')}}" class="mb-3 W-100">
                    <div class="search-bar d-flex align-items-center  W-100">
                        <input type="search" name="name" class="form-control search-bar-input-mobile"
                            autocomplete="off" placeholder="{{ translate('Search_for_items') }}...">
                        <input name="data_from" value="search" hidden="">
                        <input name="page" value="1" hidden="">
                        <button type="submit" class="btn">
                            <i class="fa fa fa-search"></i>
                        </button>
                    </div>
                    <div
                        class="card search-card __inline-13 position-absolute z-99 w-100 bg-white start-0 search-result-box d--none">
                    </div>
                </form>
            </div>

            <ul class="notification-drop br-12 d-none">
                <li class="item">
                    <span class="nav-link position-relative"
                        onclick="toggleNavs('Notification', '100%')">
                        <img src="{{ theme_asset('assets/images/Notification.svg') }}" alt="" class="wp-24 me-md-2">
                        <span
                            class="position-absolute toppx-10 custom-left-40 translate-middle badge rounded-pill bg-danger">
                            {{ $noti['notification_count'] ?? 0 }}
                            <span class="visually-hidden"> {{ translate('unread_messages') }} </span>
                        </span>
                    </span>
                    <ul class="text-left" id="Notification">
                        <li class="notification-header  noti">
                            <h4 class="notification-title">Notification</h4>
                            <button type="button" onclick="toggleNavs('Notification', '0')" class="close-btns"><i
                                    class="fa-solid fa-x"></i></button>
                        </li>
                        @if(isset($noti['notification_count']) && $noti['notification_count'] > 0)
                        <li class="">
                            <a href="javascript:void(0)" class="all-notification mark-as-read">Mark as read</a>
                        </li>
                        @endif
                        @if(isset($noti['notifications']) && !empty($noti['notifications']))
                        @foreach($noti['notifications'] as $notification)
                        @php($orderKey=explode('-', $notification['type_id']))
                        @php($redirectUrl=$notification['type']=='order'?route('account-order-details', ['id' => $orderKey[0]??'']):'javascript:void(0)')

                        <li class="modal-notification {{ $notification['is_read'] == '0' ? 'unread' : '' }}">
                            <div class="model-img">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                    viewBox="0 0 48 48" fill="none">
                                    <rect width="48" height="48" rx="8" fill="#D9D9D9"
                                        fill-opacity="0.11" />
                                    <path
                                        d="M22.1285 35.2356C21.9036 35.1594 21.6665 35.1064 21.4555 35.0023C20.6514 34.6026 20.2312 33.6925 20.4265 32.8131C20.4423 32.7434 20.4571 32.6737 20.4776 32.5788C20.1401 32.5788 19.8148 32.5788 19.4904 32.5788C19.2366 32.5788 18.9819 32.5854 18.729 32.5751C18.0523 32.5491 17.5252 32.1484 17.316 31.5117C17.1069 30.873 17.2956 30.2437 17.8338 29.8235C18.3934 29.3866 18.9568 28.9534 19.5247 28.5277C19.6447 28.4375 19.6828 28.3576 19.6465 28.206C18.6937 24.2051 17.7455 20.2041 16.802 16.2012C16.762 16.033 16.696 15.9874 16.5277 15.9902C15.9328 16.0014 15.3379 15.9958 14.7438 15.9939C14.3134 15.9921 14.0085 15.7206 14.0002 15.3386C13.9918 14.9509 14.3032 14.6609 14.7438 14.659C15.5563 14.6553 16.3688 14.6553 17.1812 14.659C17.6098 14.6609 17.831 14.8384 17.9407 15.2596C18.1201 15.9521 18.3014 16.6447 18.4715 17.3391C18.5087 17.4897 18.5756 17.5454 18.7299 17.565C19.1901 17.6217 19.6484 17.696 20.1057 17.7713C20.4878 17.8345 20.7323 18.1506 20.6886 18.5066C20.644 18.8673 20.3344 19.1192 19.9486 19.083C19.5973 19.0495 19.2477 18.99 18.8601 18.937C19.5638 21.9731 20.261 24.9813 20.9638 28.0155C21.0456 28.0155 21.1227 28.0155 21.1999 28.0155C24.8272 28.0155 28.4535 28.0155 32.0808 28.0155C32.4154 28.0155 32.4461 27.9866 32.4526 27.6641C32.4945 25.5437 32.5344 23.4233 32.5809 21.3029C32.5856 21.0956 32.5465 20.9719 32.316 20.9124C31.9841 20.826 31.8047 20.495 31.8679 20.1687C31.933 19.8304 32.2249 19.6101 32.5781 19.6333C33.3032 19.6816 33.9167 20.3658 33.9167 21.1495C33.9167 21.7862 33.8926 22.423 33.8805 23.0598C33.8498 24.6447 33.8238 26.2297 33.7885 27.8147C33.7689 28.7062 33.1015 29.3373 32.1784 29.3392C30.0459 29.3429 27.9134 29.3401 25.7809 29.3401C24.142 29.3401 22.5022 29.3373 20.8634 29.3457C20.7444 29.3466 20.6068 29.3941 20.512 29.4647C19.9189 29.9063 19.336 30.3599 18.7504 30.8108C18.6491 30.8888 18.4948 30.9697 18.5849 31.1147C18.6333 31.1919 18.7876 31.2374 18.8945 31.2374C23.667 31.243 28.4405 31.2421 33.213 31.243C33.6109 31.243 33.8684 31.402 33.966 31.706C34.1073 32.1447 33.7931 32.5649 33.3013 32.5761C32.83 32.5863 32.3587 32.5788 31.8865 32.5798C31.8437 32.5798 31.801 32.5863 31.8047 32.5854C31.8047 32.9181 31.8437 33.2444 31.7973 33.5568C31.669 34.4129 31.0471 35.0283 30.1928 35.1984C30.1584 35.205 30.1259 35.2235 30.0933 35.2356C29.9046 35.2356 29.7159 35.2356 29.5272 35.2356C29.1507 35.1315 28.7826 35.0069 28.4879 34.7346C27.8502 34.1461 27.6829 33.4276 27.906 32.5937C26.7049 32.5937 25.5095 32.5937 24.3038 32.5937C24.3233 32.6802 24.3391 32.7499 24.354 32.8196C24.565 33.7855 24.0351 34.7587 23.1055 35.1018C22.9577 35.1566 22.8025 35.191 22.65 35.2356C22.4762 35.2356 22.3024 35.2356 22.1285 35.2356ZM22.3823 32.577C22.0151 32.5798 21.7121 32.8763 21.7093 33.2333C21.7065 33.5977 22.0198 33.8998 22.3972 33.897C22.7653 33.8942 23.0674 33.5986 23.0702 33.2407C23.0721 32.8754 22.7597 32.5733 22.3823 32.577ZM29.8237 33.897C30.1965 33.8961 30.4912 33.6088 30.494 33.2435C30.4968 32.8679 30.1984 32.5751 29.8154 32.577C29.4463 32.5788 29.1433 32.8717 29.1386 33.2305C29.1349 33.594 29.4463 33.8979 29.8237 33.897Z"
                                        fill="#040D12" />
                                    <path
                                        d="M26.1967 22.6408C23.5352 22.639 21.3572 20.4758 21.3525 17.8274C21.3479 15.1715 23.5324 12.9972 26.2032 13C28.8767 13.0028 31.0538 15.1762 31.0464 17.8339C31.0389 20.4832 28.86 22.6427 26.1967 22.6408ZM26.2134 14.3349C24.2854 14.3265 22.7051 15.8715 22.6921 17.78C22.6791 19.7247 24.2278 21.2938 26.1753 21.3069C28.1154 21.3199 29.6938 19.7749 29.7059 17.8516C29.718 15.9078 28.1637 14.3433 26.2134 14.3349Z"
                                        fill="#040D12" />
                                    <path
                                        d="M25.5558 18.0834C25.7352 17.8938 25.9091 17.7116 26.082 17.5285C26.504 17.0813 26.9242 16.6314 27.349 16.1871C27.6418 15.8803 28.0555 15.8561 28.3325 16.122C28.6095 16.3869 28.6002 16.7959 28.3074 17.1074C27.5684 17.8929 26.8284 18.6774 26.0866 19.4602C25.7436 19.8227 25.2649 19.805 24.9386 19.4211C24.6244 19.0511 24.312 18.6812 23.9997 18.3102C23.7087 17.9635 23.7199 17.5638 24.0248 17.3091C24.3232 17.0599 24.735 17.1194 25.0194 17.4541C25.1961 17.6577 25.368 17.8622 25.5558 18.0834Z"
                                        fill="#040D12" />
                                </svg>
                            </div>
                            <div class="model-p">
                                <h5><a href="{{$redirectUrl}}">{{$notification['title']}}</a></h5>
                                <p><a href="{{$redirectUrl}}">{{$notification['message']}}</a></p>
                            </div>
                            <div class="model-d">{{date('d M Y', strtotime($notification['created_at']))}}</div>
                        </li>
                        @endforeach
                        @else
                        <li class="modal-notification">
                            <div class="col-lg-12">
                                <div class="empty-content">
                                    <div>
                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 212 180" style="enable-background:new 0 0 212 180;" xml:space="preserve">
                                            <style type="text/css">
                                                .st0 {
                                                    fill: #E3E1EC;
                                                }

                                                .st1 {
                                                    fill: #0A9494;
                                                }
                                            </style>
                                            <g>
                                                <path class="st0" d="M184.52,35.28H124.3c-1.24,0-2.26,1.01-2.26,2.26c0,1.24,1.01,2.26,2.26,2.26h60.22
                                                        c1.25,0,2.26-1.01,2.26-2.26C186.78,36.29,185.77,35.28,184.52,35.28z" />
                                                <path class="st0" d="M114.25,35.28h-15.2c-1.24,0-2.26,1.01-2.26,2.26c0,1.24,1.01,2.26,2.26,2.26h15.2c1.24,0,2.26-1.01,2.26-2.26
                                                        C116.51,36.29,115.5,35.28,114.25,35.28z" />
                                                <path class="st0" d="M186.92,175.19H126.7c-1.24,0-2.26,1.01-2.26,2.26c0,1.24,1.01,2.26,2.26,2.26h60.22
                                                        c1.24,0,2.26-1.01,2.26-2.26C189.17,176.2,188.16,175.19,186.92,175.19z" />
                                                <path class="st0" d="M116.65,175.19h-15.2c-1.24,0-2.26,1.01-2.26,2.26c0,1.24,1.01,2.26,2.26,2.26h15.2
                                                        c1.24,0,2.26-1.01,2.26-2.26C118.91,176.2,117.89,175.19,116.65,175.19z" />
                                                <path class="st0" d="M2.15,161.51h42.86c0.89,0,1.61-0.72,1.61-1.61c0-0.89-0.72-1.61-1.61-1.61H2.15c-0.89,0-1.61,0.72-1.61,1.61
                                                        C0.54,160.8,1.26,161.51,2.15,161.51z" />
                                                <path class="st0" d="M52.17,161.51h10.82c0.89,0,1.61-0.72,1.61-1.61c0-0.89-0.72-1.61-1.61-1.61H52.17
                                                        c-0.89,0-1.61,0.72-1.61,1.61C50.56,160.8,51.28,161.51,52.17,161.51z" />
                                                <path class="st0" d="M148.57,169.69h42.86c0.89,0,1.61-0.72,1.61-1.61c0-0.89-0.72-1.61-1.61-1.61h-42.86
                                                        c-0.89,0-1.61,0.72-1.61,1.61C146.96,168.97,147.68,169.69,148.57,169.69z" />
                                                <path class="st0" d="M198.59,169.69h10.82c0.89,0,1.61-0.72,1.61-1.61c0-0.89-0.72-1.61-1.61-1.61h-10.82
                                                        c-0.89,0-1.61,0.72-1.61,1.61C196.98,168.97,197.69,169.69,198.59,169.69z" />
                                                <path class="st0" d="M102.89,0H72.78c-0.62,0-1.13,0.51-1.13,1.13c0,0.62,0.51,1.13,1.13,1.13h30.11c0.62,0,1.13-0.51,1.13-1.13
                                                        C104.02,0.51,103.52,0,102.89,0z" />
                                                <path class="st0" d="M67.76,0h-7.6c-0.62,0-1.13,0.51-1.13,1.13c0,0.62,0.51,1.13,1.13,1.13h7.6c0.62,0,1.13-0.51,1.13-1.13
                                                        C68.89,0.51,68.38,0,67.76,0z" />
                                                <path class="st0" d="M82.38,165.35H52.27c-0.62,0-1.13,0.51-1.13,1.13c0,0.62,0.51,1.13,1.13,1.13h30.11
                                                        c0.62,0,1.13-0.51,1.13-1.13C83.51,165.86,83,165.35,82.38,165.35z" />
                                                <path class="st0" d="M47.25,165.35h-7.6c-0.62,0-1.13,0.51-1.13,1.13c0,0.62,0.51,1.13,1.13,1.13h7.6c0.62,0,1.13-0.51,1.13-1.13
                                                        C48.38,165.86,47.87,165.35,47.25,165.35z" />
                                                <path class="st1" d="M105.5,118.83c-13.01,0-24.76-0.91-25.72-11.95c0-0.06-0.01-0.12-0.01-0.17c0-4.4,1.4-6.72,2.63-8.78
                                                        c1.26-2.1,2.45-4.07,2.45-8.74c0-8.98,8.3-18.6,20.65-18.6c12.35,0,20.65,9.62,20.65,18.6c0,4.67,1.19,6.65,2.45,8.74
                                                        c1.23,2.05,2.63,4.38,2.63,8.78c0,0.06,0,0.12-0.01,0.17C130.26,117.91,118.51,118.83,105.5,118.83z M83.77,106.61
                                                        c0.51,5.45,4.06,8.21,21.73,8.21c17.57,0,21.23-2.89,21.73-8.21c-0.02-3.23-0.92-4.73-2.06-6.63c-1.41-2.36-3.02-5.02-3.02-10.8
                                                        c0-6.9-6.84-14.6-16.65-14.6c-9.81,0-16.65,7.69-16.65,14.6c0,5.78-1.6,8.45-3.02,10.8C84.69,101.88,83.79,103.38,83.77,106.61z" />
                                                <path class="st1" d="M105.36,130.42C105.36,130.42,105.36,130.42,105.36,130.42c-3.11,0-6.05-1.37-8.28-3.85
                                                        c-0.74-0.82-0.67-2.09,0.15-2.82c0.82-0.74,2.09-0.67,2.82,0.15c1.46,1.63,3.35,2.53,5.3,2.53c0,0,0,0,0,0
                                                        c1.96,0,3.85-0.9,5.32-2.53c0.74-0.82,2-0.89,2.82-0.15c0.82,0.74,0.89,2,0.15,2.82C111.42,129.05,108.47,130.42,105.36,130.42z" />
                                            </g>
                                        </svg>
                                        <h3>{{translate('No_Notification')}}</h3>
                                        <p>{{translate('We_notify_you_went_there_is_something_for_you')}}</p>

                                    </div>
                                </div>
                            </div>

                        </li>
                        @endif


                    </ul>
                </li>
            </ul>
            <ul class=" mb-0 ps-0 d-none">
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle toggle-bgn" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(auth('customer')->check())
                        <i class="fa-regular fa-user"></i> <span class="">{{ translate('Profile') }}</span> <i class="fa-solid fa-angle-down"></i>
                        @else
                        <i class="fa-regular fa-user"></i> <span class="hide-on-phone">{{ translate('SignIn') }}</span> <i class="fa-solid fa-angle-down"></i>
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        @if(auth('customer')->check())
                        <li>
                            <a class="dropdown-item" href="{{route('user-profile')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.8664 14.2957C4.68236 14.4885 4.58325 14.7139 4.58325 15.0157C4.58325 15.316 4.68235 15.5393 4.86653 15.7302C5.06562 15.9366 5.38709 16.1266 5.85417 16.2828C6.79467 16.5973 8.1354 16.714 9.65992 16.714C11.1917 16.714 12.5317 16.593 13.4694 16.275C13.9349 16.1171 14.2552 15.9255 14.4536 15.7179C14.6376 15.5254 14.7366 15.3004 14.7366 14.999C14.7366 14.699 14.6375 14.4756 14.453 14.2842C14.2537 14.0775 13.932 13.8871 13.4649 13.7305C12.5244 13.4151 11.1837 13.2974 9.65992 13.2974C8.12793 13.2974 6.78797 13.419 5.85054 13.7377C5.38511 13.8959 5.06486 14.0877 4.8664 14.2957ZM5.44825 12.5542C6.58853 12.1666 8.09941 12.0474 9.65992 12.0474C11.2104 12.0474 12.7205 12.1625 13.8622 12.5453C14.4363 12.7377 14.9635 13.0127 15.353 13.4167C15.7572 13.836 15.9866 14.3686 15.9866 14.999C15.9866 15.6281 15.7594 16.1608 15.3573 16.5815C14.9698 16.9871 14.4443 17.2642 13.8709 17.4587C12.7306 17.8455 11.2198 17.964 9.65992 17.964C8.10944 17.964 6.59933 17.85 5.45775 17.4682C4.88379 17.2763 4.35641 17.0018 3.9669 16.598C3.56248 16.1788 3.33325 15.6462 3.33325 15.0157C3.33325 14.3866 3.56029 13.8537 3.96211 13.4327C4.34951 13.0267 4.87483 12.7491 5.44825 12.5542Z"
                                        fill="#040D12" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M9.65988 3.33301C7.98654 3.33301 6.62988 4.6892 6.62988 6.36217C6.62988 8.03514 7.98654 9.39134 9.65988 9.39134H9.6844C11.3499 9.38458 12.6953 8.02953 12.6891 6.3645L12.6891 6.36217C12.6891 4.68912 11.3323 3.33301 9.65988 3.33301ZM5.37988 6.36217C5.37988 3.99848 7.29656 2.08301 9.65988 2.08301C12.022 2.08301 13.9384 3.99788 13.9391 6.36091C13.9473 8.71672 12.0434 10.6326 9.68805 10.6413L9.68572 10.6413H9.65988C7.29656 10.6413 5.37988 8.72587 5.37988 6.36217Z"
                                        fill="#040D12" />
                                </svg>
                                Account
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{route('account-oder')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M10.0022 1.45853C12.0827 1.45926 13.7692 3.14644 13.7692 5.22772V7.75439C13.7692 8.09957 13.4894 8.37939 13.1442 8.37939C12.799 8.37939 12.5192 8.09957 12.5192 7.75439V5.22772C12.5192 3.83612 11.3914 2.70853 10.0009 2.70853H9.99817C8.60679 2.70254 7.47422 3.82438 7.46753 5.21498V7.75439C7.46753 8.09957 7.18771 8.37939 6.84253 8.37939C6.49735 8.37939 6.21753 8.09957 6.21753 7.75439V5.21086C6.22652 3.12916 7.9213 1.4503 10.0022 1.45853Z"
                                        fill="#040D12" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.04815 6.43701C4.43292 6.43701 3.125 7.74502 3.125 9.35784V13.9545C3.125 15.5673 4.43292 16.8753 6.04815 16.8753H13.9518C15.5671 16.8753 16.875 15.5673 16.875 13.9545V9.35784C16.875 7.74502 15.5671 6.43701 13.9518 6.43701H6.04815ZM1.875 9.35784C1.875 7.054 3.74323 5.18701 6.04815 5.18701H13.9518C16.2568 5.18701 18.125 7.054 18.125 9.35784V13.9545C18.125 16.2584 16.2568 18.1253 13.9518 18.1253H6.04815C3.74323 18.1253 1.875 16.2584 1.875 13.9545V9.35784Z"
                                        fill="#040D12" />
                                </svg>
                                Orders
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{route('wishlists')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2.39314 9.66527C1.49897 6.8736 2.54397 3.68277 5.47481 2.7386C7.01647 2.2411 8.71814 2.53443 9.99981 3.4986C11.2123 2.5611 12.9765 2.24443 14.5165 2.7386C17.4473 3.68277 18.499 6.8736 17.6056 9.66527C16.214 14.0903 9.99981 17.4986 9.99981 17.4986C9.99981 17.4986 3.83147 14.1419 2.39314 9.66527Z"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Wishlist
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path
                                        d="M18.3189 6.92636C18.3182 6.73636 18.294 6.54981 18.2491 6.36534C18.0045 5.37182 17.7385 4.38383 17.4947 3.39031C17.4339 3.14297 17.3758 2.89562 17.3074 2.65035C17.1727 2.16396 16.734 1.66512 16.0299 1.66651C12.0006 1.67134 7.97124 1.66858 3.94189 1.66996C3.78022 1.66996 3.61993 1.68447 3.46655 1.75494C3.02437 1.95807 2.76943 2.30352 2.65405 2.7685C2.44885 3.59896 2.24089 4.42805 2.03155 5.25782C1.89544 5.79673 1.72202 6.32734 1.66675 6.88421C1.66675 6.94916 1.66675 7.0141 1.66675 7.07905C1.66675 7.14399 1.66675 7.20894 1.66675 7.27388C1.66675 7.39272 1.66675 7.51224 1.66675 7.63108C1.69991 7.68013 1.68264 7.73679 1.68748 7.78929C1.73929 8.31922 1.99562 8.76623 2.27129 9.20081C2.3846 9.37906 2.43434 9.55386 2.43365 9.76251C2.42951 12.1434 2.43641 14.5242 2.42743 16.9058C2.42536 17.5145 2.83921 18.2627 3.58677 18.3214C3.61924 18.3214 3.65171 18.3214 3.68419 18.3214C7.88419 18.3214 12.0842 18.3214 16.2835 18.3214C16.3049 18.3214 16.327 18.3214 16.3484 18.3214C16.5177 18.2931 16.6911 18.291 16.8466 18.1984C17.3413 17.9041 17.5651 17.4585 17.5665 16.8996C17.5713 15.3457 17.5686 11.0276 17.5686 9.47372C17.5686 9.45921 17.5679 9.44401 17.5672 9.4295C17.6163 9.37354 17.6639 9.3155 17.7095 9.25539C18.0591 8.79387 18.2871 8.28053 18.3334 7.69602C18.3334 7.51155 18.3334 7.32777 18.3334 7.1433C18.3023 7.07283 18.3272 6.99821 18.3189 6.92636ZM13.2808 2.81963C14.1534 2.82515 15.0267 2.82377 15.8994 2.82101C16.0286 2.82032 16.1205 2.85141 16.155 2.9889C16.4839 4.30299 16.8314 5.61295 17.1368 6.93257C17.3855 8.00831 16.5827 9.13586 15.4841 9.3155C14.3656 9.4979 13.2933 8.70612 13.1558 7.59446C13.1406 7.47148 13.1323 7.34643 13.1323 7.22275C13.1302 6.54083 13.1309 5.85891 13.1309 5.17699C13.1309 4.44118 13.1344 3.70536 13.1275 2.96955C13.1268 2.84381 13.162 2.81893 13.2808 2.81963ZM8.22964 2.81963C9.43389 2.82377 10.6381 2.82377 11.8424 2.81963C11.9605 2.81893 11.9964 2.84312 11.9951 2.96817C11.9882 3.71504 11.9916 4.46259 11.9916 5.20946C11.9909 5.20946 11.9902 5.20946 11.9895 5.20946C11.9895 5.94596 11.9999 6.68247 11.9868 7.41828C11.9709 8.28882 11.3214 9.08819 10.493 9.28648C9.44218 9.53797 8.40237 8.89336 8.14604 7.83282C8.10113 7.64835 8.07833 7.46112 8.07833 7.27112C8.07971 5.83611 8.0811 4.40179 8.07557 2.96679C8.07488 2.83897 8.11357 2.81893 8.22964 2.81963ZM2.83437 7.55231C2.78256 7.10668 2.90692 6.6949 3.01125 6.27691C3.27724 5.20877 3.54877 4.14132 3.81753 3.07388C3.87557 2.8445 3.90251 2.82239 4.13742 2.82239C5.01072 2.82239 5.88333 2.82515 6.75594 2.81963C6.87616 2.81893 6.90932 2.84657 6.90794 2.96955C6.90172 3.71089 6.90449 4.45223 6.90449 5.19357C6.90449 5.88102 6.90587 6.56778 6.9038 7.25523C6.90034 8.35791 6.13966 9.20634 5.04596 9.33139C3.99578 9.4523 2.95666 8.60732 2.83437 7.55231ZM8.60895 15.222C8.60895 14.6265 8.61171 14.0316 8.60687 13.4361C8.60549 13.3172 8.62415 13.2682 8.76233 13.2695C9.58658 13.2771 10.4108 13.2765 11.2351 13.2702C11.3677 13.2689 11.3947 13.3096 11.394 13.4333C11.3891 14.6348 11.3898 15.8362 11.3933 17.0377C11.3933 17.1462 11.3739 17.1856 11.2537 17.1849C10.4184 17.1794 9.58381 17.1794 8.74851 17.1849C8.63313 17.1856 8.60549 17.1531 8.60618 17.0405C8.61171 16.4346 8.60895 15.828 8.60895 15.222ZM16.3015 16.7358C16.3015 17.1151 16.2814 17.1351 15.9118 17.1351C14.9079 17.1351 13.904 17.1351 12.9008 17.1351C12.8469 17.1351 12.7924 17.1358 12.7385 17.1324C12.6673 17.1275 12.6307 17.0916 12.6259 17.0205C12.6224 16.961 12.6224 16.9016 12.6224 16.8415C12.6224 15.515 12.6224 14.1884 12.6224 12.8612C12.6224 12.7306 12.6162 12.6014 12.5713 12.4771C12.4808 12.2242 12.2742 12.0681 12.0054 12.0674C10.6706 12.0646 9.33578 12.0667 8.00095 12.066C7.75015 12.066 7.58226 12.191 7.46481 12.399C7.39019 12.531 7.37983 12.6761 7.38052 12.8239C7.38121 14.167 7.38121 15.5095 7.38121 16.8526C7.38121 17.1351 7.38121 17.1351 7.09103 17.1351C6.42915 17.1351 5.76726 17.1351 5.10537 17.1351C4.72538 17.1351 4.34607 17.1372 3.96607 17.1345C3.75811 17.1331 3.71044 17.0826 3.70215 16.874C3.70077 16.8415 3.70146 16.809 3.70146 16.7766C3.70146 14.708 3.70146 12.6394 3.70146 10.5709C3.70146 10.5225 3.69939 10.4735 3.7056 10.4251C3.7132 10.3712 3.74429 10.3512 3.7968 10.3608C3.84517 10.3698 3.89008 10.3892 3.93706 10.403C4.74818 10.6434 5.53581 10.5805 6.29649 10.2144C6.71656 10.0119 7.08481 9.73764 7.37223 9.36594C7.44132 9.27681 7.49245 9.25885 7.56706 9.36317C7.66172 9.49583 7.78677 9.60223 7.90353 9.71553C8.772 10.5598 10.2388 10.7892 11.3283 10.2572C11.7581 10.0479 12.1429 9.78462 12.4428 9.4067C12.5554 9.26506 12.5803 9.26921 12.6998 9.41223C12.7696 9.49583 12.84 9.57735 12.9188 9.65197C13.8722 10.5495 15.1863 10.7588 16.3008 10.3249C16.3022 12.2387 16.3015 15.4535 16.3015 16.7358Z"
                                        fill="#040D12" />
                                </svg>
                                Vendors
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{route('customer.auth.logout')}}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.02344 6.45648C2.02344 4.0074 4.00909 2.02148 6.45744 2.02148H11.3324C13.7817 2.02148 15.7674 4.00727 15.7674 6.45648V7.38948C15.7674 7.8037 15.4317 8.13948 15.0174 8.13948C14.6032 8.13948 14.2674 7.8037 14.2674 7.38948V6.45648C14.2674 4.8357 12.9532 3.52148 11.3324 3.52148H6.45744C4.83778 3.52148 3.52344 4.83557 3.52344 6.45648V17.5865C3.52344 19.2074 4.83778 20.5215 6.45744 20.5215H11.3424C12.9575 20.5215 14.2674 19.212 14.2674 17.5975V16.6545C14.2674 16.2403 14.6032 15.9045 15.0174 15.9045C15.4317 15.9045 15.7674 16.2403 15.7674 16.6545V17.5975C15.7674 20.041 13.7854 22.0215 11.3424 22.0215H6.45744C4.00909 22.0215 2.02344 20.0356 2.02344 17.5865V6.45648Z" fill="#040D12" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.01953 12.0215C9.01953 11.6073 9.35532 11.2715 9.76953 11.2715H21.8105C22.2247 11.2715 22.5605 11.6073 22.5605 12.0215C22.5605 12.4357 22.2247 12.7715 21.8105 12.7715H9.76953C9.35532 12.7715 9.01953 12.4357 9.01953 12.0215Z" fill="#040D12" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M18.3513 8.5773C18.6435 8.28375 19.1184 8.2827 19.412 8.57494L22.34 11.4899C22.4813 11.6307 22.5608 11.8219 22.5608 12.0214C22.5608 12.2208 22.4814 12.4121 22.3401 12.5529L19.4121 15.4689C19.1186 15.7612 18.6437 15.7602 18.3514 15.4667C18.0591 15.1732 18.0601 14.6983 18.3536 14.406L20.7479 12.0215L18.3537 9.63795C18.0601 9.34571 18.0591 8.87084 18.3513 8.5773Z" fill="#040D12" />
                                </svg>
                                Logout
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" id="deactivated" href="javascript:" onclick="route_alert('{{ route('account-delete',[$customer_info['id']]) }}','{{\App\CPU\translate('want_to_delete_this_account?')}}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M16.9742 20.7237C16.7268 20.7051 16.491 20.6161 16.3708 20.3651C16.2409 20.0927 16.2471 19.8142 16.4705 19.5828C16.7793 19.2633 17.081 18.934 17.4174 18.6456C17.6684 18.4303 17.6301 18.3039 17.4032 18.1081C17.1006 17.8482 16.8247 17.5563 16.5453 17.2697C16.2222 16.9387 16.2017 16.5284 16.4812 16.2454C16.7731 15.9508 17.1593 15.9597 17.5029 16.2836C17.8153 16.5782 18.1241 16.8782 18.416 17.1932C18.5815 17.3721 18.691 17.3988 18.8681 17.2012C19.1449 16.8933 19.4493 16.6094 19.7483 16.3219C20.1123 15.973 20.5119 15.9437 20.8082 16.2338C21.1126 16.532 21.0841 16.9235 20.7237 17.2884C20.422 17.5937 20.1185 17.8981 19.8044 18.1909C19.6575 18.3279 19.6317 18.4169 19.799 18.5682C20.139 18.8761 20.4612 19.2046 20.778 19.5365C21.0895 19.864 21.0921 20.2432 20.8056 20.5199C20.5235 20.7923 20.1292 20.7887 19.8115 20.4915C19.4982 20.1978 19.1867 19.9005 18.8984 19.5828C18.7204 19.387 18.6056 19.3532 18.4133 19.5703C18.1392 19.88 17.8259 20.155 17.5349 20.4505C17.3827 20.6063 17.2092 20.7051 16.9742 20.7237Z"
                                        fill="#767680" />
                                    <path
                                        d="M18.5522 13.4785C17.9007 12.9409 17.1967 12.4808 16.4269 12.0963C16.4981 12.0207 16.556 11.9548 16.6191 11.8934C17.9203 10.6252 18.6243 8.82746 18.4018 7.01191C18.1864 5.25955 17.1353 3.69675 15.6126 2.81122C13.5113 1.58929 10.885 1.7566 8.97069 3.25354C8.46963 3.64513 8.02909 4.11682 7.68022 4.64992C7.47374 4.96586 7.29753 5.30138 7.1578 5.65203C6.44938 7.4302 6.71459 9.45134 7.81728 11.0088C7.82885 11.0257 7.8413 11.0426 7.85287 11.0586C8.07181 11.3621 8.33257 11.6353 8.58888 11.9406C8.43314 12.0189 8.30142 12.0839 8.1706 12.1497C4.64985 13.9368 2.68745 17.422 3.04077 21.2159C3.07548 21.5853 3.2339 21.8149 3.5178 22C3.66643 22 3.81416 22 3.96279 22C4.34815 21.7944 4.46563 21.482 4.43003 21.0459C4.36061 20.188 4.40867 19.3292 4.62849 18.4917C5.36094 15.7052 7.20675 13.9555 9.8215 12.9196C10.0048 12.8466 10.1285 12.9214 10.2745 12.981C11.7465 13.5826 13.2328 13.628 14.7244 13.0646C14.938 12.9837 15.1026 13.0006 15.3055 13.0878C16.1679 13.4598 16.9422 13.9671 17.6755 14.5482C18.0912 14.8766 18.5077 14.8571 18.7702 14.518C19.0212 14.1931 18.9429 13.8006 18.5522 13.4785ZM12.6445 12.0883C10.1722 12.0972 8.1617 10.1393 8.15636 7.71766C8.15102 5.30939 10.165 3.35678 12.6543 3.35678C15.0715 3.35589 17.0428 5.34232 17.0401 7.77551C17.0383 10.125 15.0457 12.0803 12.6445 12.0883Z"
                                        fill="#767680" />
                                </svg>
                                {{ translate('Delete_My_Account') }}
                            </a>
                        </li>
                        @else
                        <li><a class="dropdown-item proceed-to-buy" href="#" data-bs-toggle="modal" data-bs-target="#auth-model">{{ translate('login') }} / {{ translate('register') }}</a></li>
                        @endif

                    </ul>
                </li>
            </ul>
        </div>
    </header>




    <nav class="navbar navbar-expand-lg bg-green-dark top-nav-category">
        <!-- <img class="categoryBgImg" src="{{ theme_asset('assets/images/categorybg2.png') }}" alt=""> -->
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarToggleExternalContent">



                <div class="navCategory">


                    <?php

                        use App\Model\Category;

                        $errorImage = asset('public/assets/front-end/img/image-place-holder.png');

                        $categories = Category::where('parent_id', 0)->take(6)->get();
                    ?>


                    @foreach($categories as $m_category)

                    <?php
                        $image = $m_category['icon'] !== "def.png"
                            ? asset('storage/app/public/category/' . $m_category['icon'])
                            : $errorImage;
                    ?>

                    <a href="{{ route('products',['id'=> $m_category['id'],'data_from'=>'category','page'=>1]) }}">
                        <div class="sliderCard">
                            <img src="{{ $image }}"
                                alt="{{ $m_category['name'] }}"
                                onerror="this.onerror=null;this.src='{{ $errorImage }}';">
                            <span>{{ ucwords($m_category['name']) }}</span>
                        </div>
                    </a>

                    @endforeach

                    <li class="nav-item categoryTopCard">
                        <a class="nav-link cat-all categoryCover" href="{{ route('products',['page'=>1]) }}">
                            <img src="{{ theme_asset('assets/images/next1.png') }}" alt="">

                        </a>
                    </li>



                </div>
            </div>
        </div>
    </nav>
    <div class="collapse customDropdown" id="collapseExample">
        @if(auth('customer')->check())
        <li>
            <a class="dropdown-item" href="{{route('user-account')}}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.83978 16.655C5.61893 16.8864 5.5 17.1569 5.5 17.519C5.5 17.8794 5.61892 18.1473 5.83994 18.3764C6.07884 18.6241 6.4646 18.8521 7.0251 19.0395C8.1537 19.4169 9.76258 19.557 11.592 19.557C13.4301 19.557 15.0382 19.4118 16.1633 19.0301C16.722 18.8407 17.1063 18.6108 17.3444 18.3617C17.5652 18.1307 17.684 17.8607 17.684 17.499C17.684 17.139 17.5651 16.8709 17.3437 16.6413C17.1046 16.3932 16.7185 16.1647 16.158 15.9768C15.0293 15.5984 13.4205 15.457 11.592 15.457C9.75361 15.457 8.14567 15.603 7.02075 15.9854C6.46223 16.1752 6.07793 16.4054 5.83978 16.655ZM6.538 14.5652C7.90633 14.1001 9.71939 13.957 11.592 13.957C13.4525 13.957 15.2647 14.0952 16.6348 14.5546C17.3236 14.7855 17.9564 15.1155 18.4237 15.6002C18.9088 16.1034 19.184 16.7425 19.184 17.499C19.184 18.2539 18.9113 18.8931 18.4289 19.398C17.9638 19.8847 17.3332 20.2173 16.6452 20.4507C15.2768 20.9148 13.4639 21.057 11.592 21.057C9.73142 21.057 7.9193 20.9202 6.5494 20.4621C5.86065 20.2317 5.22779 19.9024 4.76037 19.4178C4.27508 18.9148 4 18.2757 4 17.519C4 16.7641 4.27245 16.1247 4.75462 15.6194C5.21951 15.1323 5.8499 14.7991 6.538 14.5652Z" fill="" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.593 3.5C9.58502 3.5 7.95703 5.12744 7.95703 7.135C7.95703 9.14256 9.58502 10.77 11.593 10.77H11.6225C13.621 10.7619 15.2355 9.13582 15.2281 7.13779L15.2281 7.135C15.2281 5.12733 13.5999 3.5 11.593 3.5ZM6.45703 7.135C6.45703 4.29856 8.75704 2 11.593 2C14.4276 2 16.7272 4.29784 16.7281 7.13348C16.7379 9.96046 14.4533 12.2595 11.6268 12.27L11.624 12.27H11.593C8.75704 12.27 6.45703 9.97144 6.45703 7.135Z" fill="" />
                </svg>

                {{translate('My_Account')}}
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{route('account-oder')}}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0026 1.75004C14.4992 1.75091 16.5229 3.77554 16.5229 6.27307V9.30507C16.5229 9.71929 16.1872 10.0551 15.7729 10.0551C15.3587 10.0551 15.0229 9.71929 15.0229 9.30507V6.27307C15.0229 4.60315 13.6696 3.25004 12.0009 3.25004H11.9977C10.3281 3.24285 8.96897 4.58907 8.96094 6.25778V9.30507C8.96094 9.71929 8.62515 10.0551 8.21094 10.0551C7.79672 10.0551 7.46094 9.71929 7.46094 9.30507V6.25283C7.47173 3.75479 9.50546 1.74016 12.0026 1.75004Z" fill="" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.25778 7.72461C5.31951 7.72461 3.75 9.29422 3.75 11.2296V16.7456C3.75 18.681 5.31951 20.2506 7.25778 20.2506H16.7422C18.6805 20.2506 20.25 18.681 20.25 16.7456V11.2296C20.25 9.29422 18.6805 7.72461 16.7422 7.72461H7.25778ZM2.25 11.2296C2.25 8.465 4.49188 6.22461 7.25778 6.22461H16.7422C19.5081 6.22461 21.75 8.465 21.75 11.2296V16.7456C21.75 19.5102 19.5081 21.7506 16.7422 21.7506H7.25778C4.49188 21.7506 2.25 19.5102 2.25 16.7456V11.2296Z" fill="" />
                </svg>

                {{translate('Orders')}}
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{route('wishlists')}}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.8972 8.06468C21.113 3.54013 15.78 1.47666 12.1394 4.26991C12.0323 4.35199 11.9697 4.35365 11.8615 4.27047C10.7685 3.42867 9.523 3.02329 8.10779 3C7.75454 3.01054 7.36303 3.04325 6.97374 3.11257C4.201 3.60723 2.04991 6.1282 2.00111 8.94531C1.97006 10.737 2.59059 12.2654 3.85774 13.5325C6.38592 16.0596 8.91355 18.5878 11.4417 21.1154C11.8216 21.4953 12.176 21.4969 12.5525 21.121C13.5501 20.1239 14.5478 19.1262 15.5448 18.1292C17.0898 16.5842 18.6447 15.0487 20.177 13.4915C21.6648 11.9787 22.2582 10.1503 21.8972 8.06468ZM20.6605 9.14272C20.6456 10.4825 20.1664 11.6304 19.2176 12.5793C16.8513 14.9461 14.484 17.3112 12.1205 19.6808C12.0235 19.7778 11.9758 19.7751 11.8809 19.6802C9.49749 17.2918 7.0974 14.9194 4.73004 12.5149C3.4019 11.1663 2.99541 9.52425 3.54941 7.72086C4.10395 5.91637 5.37276 4.79673 7.22384 4.42574C8.80652 4.1091 10.2201 4.53942 11.4179 5.63077C11.8532 6.02728 12.1443 6.02339 12.5797 5.63355C13.9888 4.37029 15.6208 3.98876 17.4081 4.59432C19.1705 5.19102 20.2247 6.46759 20.5907 8.29815C20.6245 8.46729 20.6428 8.63975 20.6583 8.81111C20.6688 8.92091 20.6605 9.03181 20.6605 9.14272Z" fill="" />
                </svg>
                {{translate('Wishlist')}}
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('sellers') }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.9826 8.31182C21.9818 8.08382 21.9527 7.85997 21.8989 7.63861C21.6054 6.44638 21.2862 5.26079 20.9935 4.06857C20.9205 3.77176 20.8509 3.47494 20.7688 3.18062C20.6071 2.59694 20.0807 1.99835 19.2358 2C14.4006 2.00581 9.56539 2.00249 4.73017 2.00415C4.53617 2.00415 4.34382 2.02156 4.15976 2.10613C3.62915 2.34988 3.32322 2.76442 3.18476 3.32239C2.93852 4.31895 2.68897 5.31385 2.43776 6.30958C2.27443 6.95627 2.06633 7.59301 2 8.26125C2 8.33918 2 8.41712 2 8.49505C2 8.57298 2 8.65092 2 8.72885C2 8.87145 2 9.01488 2 9.15749C2.0398 9.21635 2.01907 9.28434 2.02487 9.34735C2.08705 9.98326 2.39464 10.5197 2.72545 11.0412C2.86142 11.2551 2.92111 11.4648 2.92028 11.7152C2.91531 14.5722 2.9236 17.4293 2.91282 20.2871C2.91033 21.0175 3.40696 21.9154 4.30402 21.9859C4.34299 21.9859 4.38196 21.9859 4.42093 21.9859C9.46093 21.9859 14.5009 21.9859 19.5401 21.9859C19.5658 21.9859 19.5923 21.9859 19.618 21.9859C19.8212 21.9519 20.0293 21.9494 20.2158 21.8383C20.8094 21.4851 21.0781 20.9504 21.0797 20.2797C21.0855 18.415 21.0822 13.2333 21.0822 11.3687C21.0822 11.3512 21.0814 11.333 21.0805 11.3156C21.1394 11.2484 21.1966 11.1788 21.2513 11.1067C21.6709 10.5528 21.9445 9.93683 22 9.23542C22 9.01406 22 8.79352 22 8.57215C21.9627 8.48759 21.9925 8.39805 21.9826 8.31182ZM15.9369 3.38375C16.984 3.39038 18.032 3.38872 19.0791 3.3854C19.2342 3.38457 19.3444 3.42188 19.3859 3.58687C19.7805 5.16379 20.1976 6.73573 20.564 8.31928C20.8625 9.61017 19.8991 10.9632 18.5809 11.1788C17.2386 11.3977 15.9518 10.4475 15.7868 9.11355C15.7686 8.96597 15.7587 8.81591 15.7587 8.6675C15.7562 7.84919 15.757 7.03089 15.757 6.21258C15.757 5.32961 15.7611 4.44663 15.7528 3.56366C15.752 3.41276 15.7943 3.38292 15.9369 3.38375ZM9.87547 3.38375C11.3206 3.38872 12.7657 3.38872 14.2108 3.38375C14.3525 3.38292 14.3956 3.41193 14.394 3.562C14.3857 4.45824 14.3898 5.35531 14.3898 6.25155C14.389 6.25155 14.3882 6.25155 14.3873 6.25155C14.3873 7.13535 14.3998 8.01915 14.384 8.90213C14.365 9.94678 13.5856 10.906 12.5916 11.144C11.3305 11.4458 10.0827 10.6722 9.77515 9.39958C9.72126 9.17821 9.6939 8.95353 9.6939 8.72554C9.69556 7.00353 9.69722 5.28235 9.69058 3.56034C9.68976 3.40696 9.73618 3.38292 9.87547 3.38375ZM3.40115 9.06297C3.33897 8.52821 3.48821 8.03408 3.6134 7.53248C3.9326 6.25072 4.25843 4.96978 4.58094 3.68885C4.65058 3.41359 4.68292 3.38706 4.96481 3.38706C6.01277 3.38706 7.0599 3.39038 8.10703 3.38375C8.25129 3.38292 8.29109 3.41608 8.28943 3.56366C8.28197 4.45326 8.28529 5.34287 8.28529 6.23248C8.28529 7.05742 8.28695 7.88153 8.28446 8.70647C8.28031 10.0297 7.36749 11.0478 6.05505 11.1979C4.79484 11.343 3.5479 10.329 3.40115 9.06297ZM10.3306 18.2666C10.3306 17.552 10.334 16.8381 10.3282 16.1235C10.3265 15.9809 10.3489 15.922 10.5147 15.9236C11.5038 15.9328 12.4929 15.9319 13.482 15.9245C13.6412 15.9228 13.6735 15.9717 13.6727 16.1201C13.6669 17.5619 13.6677 19.0037 13.6718 20.4455C13.6718 20.5756 13.6486 20.6229 13.5044 20.6221C12.502 20.6154 11.5005 20.6154 10.4981 20.6221C10.3597 20.6229 10.3265 20.5839 10.3273 20.4488C10.334 19.7217 10.3306 18.9937 10.3306 18.2666ZM19.5617 20.0832C19.5617 20.5383 19.5376 20.5624 19.0941 20.5624C17.8894 20.5624 16.6847 20.5624 15.4809 20.5624C15.4162 20.5624 15.3507 20.5632 15.2861 20.5591C15.2007 20.5533 15.1567 20.5101 15.1509 20.4247C15.1468 20.3534 15.1468 20.2821 15.1468 20.21C15.1468 18.6182 15.1468 17.0263 15.1468 15.4337C15.1468 15.277 15.1393 15.1219 15.0854 14.9727C14.9768 14.6692 14.7289 14.4819 14.4064 14.481C12.8046 14.4777 11.2028 14.4802 9.60104 14.4794C9.30009 14.4794 9.09862 14.6294 8.95767 14.879C8.86813 15.0374 8.8557 15.2115 8.85653 15.3889C8.85736 17.0006 8.85736 18.6115 8.85736 20.2233C8.85736 20.5624 8.85736 20.5624 8.50914 20.5624C7.71488 20.5624 6.92061 20.5624 6.12635 20.5624C5.67036 20.5624 5.21519 20.5649 4.75919 20.5615C4.50964 20.5599 4.45243 20.4994 4.44248 20.249C4.44082 20.21 4.44165 20.171 4.44165 20.1321C4.44165 17.6498 4.44165 15.1675 4.44165 12.6852C4.44165 12.6272 4.43917 12.5683 4.44663 12.5103C4.45575 12.4456 4.49306 12.4216 4.55607 12.4332C4.6141 12.444 4.66799 12.4672 4.72437 12.4838C5.69772 12.7723 6.64287 12.6968 7.55569 12.2574C8.05978 12.0145 8.50168 11.6854 8.84658 11.2393C8.92949 11.1324 8.99084 11.1108 9.08038 11.236C9.19396 11.3952 9.34403 11.5229 9.48414 11.6588C10.5263 12.672 12.2864 12.9472 13.5939 12.3088C14.1096 12.0576 14.5714 11.7417 14.9312 11.2882C15.0664 11.1183 15.0962 11.1232 15.2396 11.2949C15.3234 11.3952 15.408 11.493 15.5025 11.5826C16.6466 12.6595 18.2235 12.9108 19.5608 12.3901C19.5625 14.6867 19.5617 18.5444 19.5617 20.0832Z" fill="" />
                </svg>
                {{translate('Vendors')}}
            </a>
        </li>
        <li>
            <a class="dropdown-item text-danger" href="{{route('customer.auth.logout')}}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.02344 6.45648C2.02344 4.0074 4.00909 2.02148 6.45744 2.02148H11.3324C13.7817 2.02148 15.7674 4.00727 15.7674 6.45648V7.38948C15.7674 7.8037 15.4317 8.13948 15.0174 8.13948C14.6032 8.13948 14.2674 7.8037 14.2674 7.38948V6.45648C14.2674 4.8357 12.9532 3.52148 11.3324 3.52148H6.45744C4.83778 3.52148 3.52344 4.83557 3.52344 6.45648V17.5865C3.52344 19.2074 4.83778 20.5215 6.45744 20.5215H11.3424C12.9575 20.5215 14.2674 19.212 14.2674 17.5975V16.6545C14.2674 16.2403 14.6032 15.9045 15.0174 15.9045C15.4317 15.9045 15.7674 16.2403 15.7674 16.6545V17.5975C15.7674 20.041 13.7854 22.0215 11.3424 22.0215H6.45744C4.00909 22.0215 2.02344 20.0356 2.02344 17.5865V6.45648Z" fill="" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.01953 12.0215C9.01953 11.6073 9.35532 11.2715 9.76953 11.2715H21.8105C22.2247 11.2715 22.5605 11.6073 22.5605 12.0215C22.5605 12.4357 22.2247 12.7715 21.8105 12.7715H9.76953C9.35532 12.7715 9.01953 12.4357 9.01953 12.0215Z" fill="" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M18.3513 8.5773C18.6435 8.28375 19.1184 8.2827 19.412 8.57494L22.34 11.4899C22.4813 11.6307 22.5608 11.8219 22.5608 12.0214C22.5608 12.2208 22.4814 12.4121 22.3401 12.5529L19.4121 15.4689C19.1186 15.7612 18.6437 15.7602 18.3514 15.4667C18.0591 15.1732 18.0601 14.6983 18.3536 14.406L20.7479 12.0215L18.3537 9.63795C18.0601 9.34571 18.0591 8.87084 18.3513 8.5773Z" fill="" />
                </svg>
                {{translate('Logout')}}
            </a>
        </li>
        <li>
            <a class="dropdown-item" id="deactivated" href="javascript:" onclick="route_alert('{{ route('account-delete',[$customer_info['id']]) }}','{{\App\CPU\translate('want_to_delete_this_account?')}}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="">
                    <path
                        d="M16.9742 20.7237C16.7268 20.7051 16.491 20.6161 16.3708 20.3651C16.2409 20.0927 16.2471 19.8142 16.4705 19.5828C16.7793 19.2633 17.081 18.934 17.4174 18.6456C17.6684 18.4303 17.6301 18.3039 17.4032 18.1081C17.1006 17.8482 16.8247 17.5563 16.5453 17.2697C16.2222 16.9387 16.2017 16.5284 16.4812 16.2454C16.7731 15.9508 17.1593 15.9597 17.5029 16.2836C17.8153 16.5782 18.1241 16.8782 18.416 17.1932C18.5815 17.3721 18.691 17.3988 18.8681 17.2012C19.1449 16.8933 19.4493 16.6094 19.7483 16.3219C20.1123 15.973 20.5119 15.9437 20.8082 16.2338C21.1126 16.532 21.0841 16.9235 20.7237 17.2884C20.422 17.5937 20.1185 17.8981 19.8044 18.1909C19.6575 18.3279 19.6317 18.4169 19.799 18.5682C20.139 18.8761 20.4612 19.2046 20.778 19.5365C21.0895 19.864 21.0921 20.2432 20.8056 20.5199C20.5235 20.7923 20.1292 20.7887 19.8115 20.4915C19.4982 20.1978 19.1867 19.9005 18.8984 19.5828C18.7204 19.387 18.6056 19.3532 18.4133 19.5703C18.1392 19.88 17.8259 20.155 17.5349 20.4505C17.3827 20.6063 17.2092 20.7051 16.9742 20.7237Z"
                        fill="" />
                    <path
                        d="M18.5522 13.4785C17.9007 12.9409 17.1967 12.4808 16.4269 12.0963C16.4981 12.0207 16.556 11.9548 16.6191 11.8934C17.9203 10.6252 18.6243 8.82746 18.4018 7.01191C18.1864 5.25955 17.1353 3.69675 15.6126 2.81122C13.5113 1.58929 10.885 1.7566 8.97069 3.25354C8.46963 3.64513 8.02909 4.11682 7.68022 4.64992C7.47374 4.96586 7.29753 5.30138 7.1578 5.65203C6.44938 7.4302 6.71459 9.45134 7.81728 11.0088C7.82885 11.0257 7.8413 11.0426 7.85287 11.0586C8.07181 11.3621 8.33257 11.6353 8.58888 11.9406C8.43314 12.0189 8.30142 12.0839 8.1706 12.1497C4.64985 13.9368 2.68745 17.422 3.04077 21.2159C3.07548 21.5853 3.2339 21.8149 3.5178 22C3.66643 22 3.81416 22 3.96279 22C4.34815 21.7944 4.46563 21.482 4.43003 21.0459C4.36061 20.188 4.40867 19.3292 4.62849 18.4917C5.36094 15.7052 7.20675 13.9555 9.8215 12.9196C10.0048 12.8466 10.1285 12.9214 10.2745 12.981C11.7465 13.5826 13.2328 13.628 14.7244 13.0646C14.938 12.9837 15.1026 13.0006 15.3055 13.0878C16.1679 13.4598 16.9422 13.9671 17.6755 14.5482C18.0912 14.8766 18.5077 14.8571 18.7702 14.518C19.0212 14.1931 18.9429 13.8006 18.5522 13.4785ZM12.6445 12.0883C10.1722 12.0972 8.1617 10.1393 8.15636 7.71766C8.15102 5.30939 10.165 3.35678 12.6543 3.35678C15.0715 3.35589 17.0428 5.34232 17.0401 7.77551C17.0383 10.125 15.0457 12.0803 12.6445 12.0883Z"
                        fill="" />
                </svg>
                {{ translate('Delete_My_Account') }}
            </a>
        </li>
        @else
        <li><a class="dropdown-item proceed-to-buy" href="#" data-bs-toggle="modal" data-bs-target="#auth-model">{{ translate('login') }} / {{ translate('register') }}</a></li>
        @endif
    </div>


    <div id="allCategory" class="sidenav d-done">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <ul class="list-group br-12 w-100">

        </ul>
    </div>



    <style>
        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 11;
            top: 0;
            left: 0;
            border-bottom: 1px solid #c3c5dd;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            background: #FFF;

        }

        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: #f1f1f1;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }
    </style>


</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const duration = 10000; //ms
        const directionAnimation = 'left'; //left or right  

        const marquee = document.querySelector('.marquee');
        const span = marquee.querySelector('span');

        const marqueeWidth = marquee.offsetWidth;
        const spanWidth = span.offsetWidth;

        let keyframes = [];
        if ('left' == directionAnimation) {
            keyframes = [{
                    transform: `translateX(${marqueeWidth}px)`
                },
                {
                    transform: `translateX(${-spanWidth}px)`
                }
            ];
        } else if ('right' == directionAnimation) {
            keyframes = [{
                    transform: `translateX(${-spanWidth}px)`
                },
                {
                    transform: `translateX(${marqueeWidth}px)`
                }
            ];
        }

        let options = {
            duration: duration, // Durata dell'animazione in millisecondi
            iterations: Infinity,
            easing: "linear"
        };

        const marqueeAnimation = span.animate(keyframes, options);

        marquee.addEventListener('mouseenter', () => {
            marqueeAnimation.pause();
        });

        marquee.addEventListener('mouseleave', () => {
            marqueeAnimation.play();
        });
    });
</script>

<script src="https://maps.google.com/maps/api/js?libraries=places&key=AIzaSyAsMSsS1jOb_HJvHbTXubVhROavNlW7baE"></script>
<script>
        document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.location-input');

        // Save to Laravel session
        function saveToSession(data, reload = false) {
            fetch('{{ route('update_city_session') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="_token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                console.log('Location saved to session:', result);
                if (reload) location.reload();
            })
            .catch(error => {
                console.error('Error saving location to session:', error);
            });
        }

        // Fetch address details from lat/lng
        function fetchAddressDetails(lat, lng, input) {
            const geocoder = new google.maps.Geocoder();
            const latLng = new google.maps.LatLng(lat, lng);

            geocoder.geocode({ location: latLng }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK && results[0]) {
                    const place = results[0];
                    const address = place.formatted_address;
                    let city = '', pincode = '', area = '', state = '';

                    place.address_components.forEach(function (component) {
                        const types = component.types;
                        if (types.includes("locality")) city = component.long_name;
                        if (types.includes("postal_code")) pincode = component.long_name;
                        if (types.includes("sublocality_level_1")) area = component.long_name;
                        if (types.includes("administrative_area_level_1")) state = component.long_name;
                    });

                    // Fill hidden fields
                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                    document.getElementById('pincode').value = pincode;
                    document.getElementById('area').value = area;
                    document.getElementById('city').value = city;
                    document.getElementById('state').value = state;

                    // Fill input field
                    input.value = address;

                    // Save to localStorage + session
                    const locationData = { address, lat, lng, pincode, area, city, state };
                    localStorage.setItem('userLocation', JSON.stringify(locationData));
                    saveToSession(locationData, false);
                } else {
                    console.error('Failed to retrieve address:', status);
                }
            });
        }

        // Attach autocomplete to all inputs
        inputs.forEach(input => {
            const autocomplete = new google.maps.places.Autocomplete(input);

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                const place = autocomplete.getPlace();
                if (place.geometry) {
                    const latitude = place.geometry.location.lat();
                    const longitude = place.geometry.location.lng();
                    const address = place.formatted_address;

                    let city = '', pincode = '', area = '', state = '';
                    place.address_components.forEach(component => {
                        const types = component.types;
                        if (types.includes("locality")) city = component.long_name;
                        if (types.includes("postal_code")) pincode = component.long_name;
                        if (types.includes("sublocality_level_1")) area = component.long_name;
                        if (types.includes("administrative_area_level_1")) state = component.long_name;
                    });

                    // Fill hidden fields
                    document.getElementById('lat').value = latitude;
                    document.getElementById('lng').value = longitude;
                    document.getElementById('pincode').value = pincode;
                    document.getElementById('area').value = area;
                    document.getElementById('city').value = city;
                    document.getElementById('state').value = state;

                    // Save to localStorage + session
                    const locationData = { address, lat: latitude, lng: longitude, pincode, area, city, state };
                    localStorage.setItem('userLocation', JSON.stringify(locationData));
                    saveToSession(locationData, true);
                }
            });

            // Allow manual typing
            input.addEventListener("focus", function () {
                this.removeAttribute("readonly");
            });
        });

        // Custom Modal Logic
        const modal = document.getElementById("customLocationModal");
        const allowAlwaysBtn = document.getElementById("customAllowAlways");
        const allowOnceBtn = document.getElementById("customAllowOnce");
        const denyBtn = document.getElementById("customDeny");

        const storedLocation = localStorage.getItem('userLocation');
        if (!storedLocation) {
            modal.style.display = "block"; // Show modal only if no saved location
        }

        function requestGeolocation(saveAlways) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    fetchAddressDetails(lat, lng, inputs[0]); // fill first input by default
                    if (!saveAlways) {
                        // Clear location on tab close if "Allow this time"
                        window.addEventListener("beforeunload", () => {
                            localStorage.removeItem("userLocation");
                        });
                    }
                }, function (error) {
                    console.error("Error fetching location:", error.message);
                    alert("We need your location for better results.");
                });
            } else {
                alert("Geolocation not supported.");
            }
        }

        allowAlwaysBtn.addEventListener("click", () => {
            modal.style.display = "none";
            requestGeolocation(true);
        });

        allowOnceBtn.addEventListener("click", () => {
            modal.style.display = "none";
            requestGeolocation(false);
        });

        denyBtn.addEventListener("click", () => {
            modal.style.display = "none";
            alert("You can still search manually.");
        });

        document.querySelector(".custom-modal-close").addEventListener("click", () => {
            modal.style.display = "none";
        });
    });

</script>

<script>
    fetch("{{ route('check-city-session') }}")
    .then(response => response.json())
    .then(data => {
        if (data.hasLocation) {
            // console.log("✅ Session has location:", data);
            document.querySelector('.location-input').value = data.address;
        } else {
            console.log("❌ No location stored in session yet");
        }
    })
    .catch(err => console.error("Error checking session:", err));

</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const input = document.querySelector(".location-input");

    function saveToSession(data) {
        return fetch("{{ route('update_city_session') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="_token"]').getAttribute("content")
            },
            body: JSON.stringify(data)
        }).then(r => r.json());
    }

    function fetchAddressDetails(lat, lng) {
        const geocoder = new google.maps.Geocoder();
        const latLng = new google.maps.LatLng(lat, lng);

        geocoder.geocode({ location: latLng }, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK && results[0]) {
                const place = results[0];
                const address = place.formatted_address;
                let city = "", pincode = "", area = "", state = "";

                place.address_components.forEach(component => {
                    const types = component.types;
                    if (types.includes("locality")) city = component.long_name;
                    if (types.includes("postal_code")) pincode = component.long_name;
                    if (types.includes("sublocality_level_1")) area = component.long_name;
                    if (types.includes("administrative_area_level_1")) state = component.long_name;
                });

                // Fill input
                input.value = address;

                // Save to session
                saveToSession({
                    address,
                    city,
                    lat,
                    lng,
                    pincode,
                    area,
                    state
                });
            }
        });
    }

    // 1. Check if session already has location
    fetch("{{ route('check-city-session') }}")
        .then(response => response.json())
        .then(data => {
            if (data.hasLocation) {
                // console.log("Using session location:", data);
                input.value = data.address;
            } else {
                console.log("No session location, fetching current position...");
                // 2. Ask for location
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (pos) {
                        fetchAddressDetails(pos.coords.latitude, pos.coords.longitude);
                    }, function (err) {
                        console.error("Geolocation error:", err.message);
                        alert("We need your location for better results.");
                    });
                } else {
                    alert("Geolocation not supported in this browser.");
                }
            }
        })
        .catch(err => console.error("Session check error:", err));
});

</script>