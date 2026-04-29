@extends('theme-views.layouts.app')

@section('title',translate('Shop Page'))


@push('css_or_js')
@if($shop['id'] != 0)
<meta property="og:image" content="{{asset('storage/app/public/shop')}}/{{$shop->image}}" />
<meta property="og:title" content="{{ $shop->name}} " />
<meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
@else
<meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}" />
<meta property="og:title" content="{{ $shop['name']}} " />
<meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
@endif
<meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">
@if($shop['id'] != 0)
<meta property="twitter:card" content="{{asset('storage/app/public/shop')}}/{{$shop->image}}" />
<meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}" />
<meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
@else
<meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}" />
<meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}" />
<meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
@endif
<meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
@endpush

@section('content')

<style type="text/css">
    .vendor-stor-details-img img {
        width: 130px;
        height: 130px;
        border-radius: 50% !important;
        border: 1px solid #0a9494;
    }

    .store-avatar h4 {
        text-align: left !important;
    }

    .store-avatar-card {
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.15);
    }

    .vender-cards-in-details-page {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }
</style>


<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 py-3 seller-page">
    <div class="container-fluid productcontent">
        <div class="row g-3">
            <div class="col-lg-3">
                @if($shop['id'] != 0)
                <div class="media card store-avatar-card p-2 text-left gap-3">
                    <div class="avatar rounded store-avatar">
                        <div class="position-relative vendor-stor-details-img">
                            <img src="{{asset('storage/app/public/shop')}}/{{$shop->image}}"
                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'"
                                class="dark-support rounded img-fit" alt="">

                            @if($seller_temporary_close || $inhouse_temporary_close)
                            <span class="temporary-closed position-absolute">
                                <span>{{translate('closed_now')}}</span>
                            </span>
                            @elseif(($seller_id==0 && $inhouse_vacation_status && $current_date >=
                            $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date) ||
                                $seller_id!=0 && $seller_vacation_status && $current_date>=
                                $seller_vacation_start_date && $current_date <= $seller_vacation_end_date) <span
                                    class="temporary-closed position-absolute">
                                    <span>{{translate('closed_now')}}</span>
                                    </span>
                                    @endif
                        </div>
                    </div>
                    <div class="media-body gap-2">
                        <h4>{{ $shop->name}}</h4>
                        <div class="d-flex gap-2 align-items-center justify-content-center">
                            <span class="star-rating text-gold fs-12">
                                @for ($i = 1; $i <= 5; $i++) @if ($i <=$avg_rating) <i class="bi bi-star-fill">
                                    </i>
                                    @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1 && $avg_rating>=
                                        ((int)$avg_rating+.30))
                                        <i class="bi bi-star-half"></i>
                                        @else
                                        <i class="bi bi-star"></i>
                                        @endif
                                        @endfor
                            </span>
                            <span class="text-muted fw-semibold">({{round($avg_rating,1)}})</span>
                        </div>

                        <div class=" text-center py-2  fs-12">

                            @if (auth('customer')->id() == '')
                            <button type="button" class="btn btn-outline-primary flex-lg-down-grow-1 fs-16"
                                data-bs-toggle="modal" data-bs-target="#loginModal"><i
                                    class="bi bi-plus-lg"></i> {{ translate('follow') }}</button>
                            @else
                            <button type="button"
                                class="btn btn-outline-primary flex-lg-down-grow-1 fs-16 follow_button"
                                data-status="{{$follow_status}}" data-titletext="{{translate('Are_you_sure')}}?"
                                data-titletext2="{{translate('Want_to_unfollow_this_shop')}}!"
                                onclick="shopFollowAction('{{$shop['id']}}')">
                                {{($follow_status == 0?translate('Follow'):translate('Unfollow'))}}</button>
                            @endif

                        </div>

                    </div>
                </div>
                @else
                <div class="media gap-3">
                    <div class="avatar rounded store-avatar">
                        <div class="position-relative vendor-stor-details-img">
                            <img src="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"
                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'"
                                class="dark-support rounded img-fit" alt="">

                            @if($seller_temporary_close || $inhouse_temporary_close)
                            <span class="temporary-closed position-absolute">
                                <span>{{translate('closed_now')}}</span>
                            </span>
                            @elseif(($seller_id==0 && $inhouse_vacation_status && $current_date >=
                            $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date) ||
                                $seller_id!=0 && $seller_vacation_status && $current_date>=
                                $seller_vacation_start_date && $current_date <= $seller_vacation_end_date) <span
                                    class="temporary-closed position-absolute">
                                    <span>{{translate('closed_now')}}</span>
                                    </span>
                                    @endif
                        </div>
                    </div>
                    <div class="media-body d-flex flex-column gap-2">
                        <h4>{{ $web_config['name']->value }}</h4>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="star-rating text-gold fs-12">
                                @for ($i = 1; $i <= 5; $i++) @if ($i <=$avg_rating) <i class="bi bi-star-fill">
                                    </i>
                                    @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1 && $avg_rating>=
                                        ((int)$avg_rating+.30))
                                        <i class="bi bi-star-half"></i>
                                        @else
                                        <i class="bi bi-star"></i>
                                        @endif
                                        @endfor
                            </span>
                            <span class="text-muted fw-semibold">({{round($avg_rating,1)}})</span>
                        </div>
                        <h6><span class="follower_count">{{$followers}}</span> {{translate('Followers')}}</h6>
                        <ul class="list-unstyled list-inline-dot fs-12">
                            <li>{{ $total_review}} {{translate('Reviews')}} </li>
                            <li>{{ $total_order}} {{translate('Orders')}} </li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-9 vender-cards-in-details-page  ">
                <div class="card">
                    <div class="card-body grid-center">
                        <div class="text-center">
                            <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{round(($avg_rating*100)/5)}}%
                            </h2>
                            <p class="text-muted"> <b>{{translate("Positive_Review")}}</b></p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body grid-center">
                        <div class="text-center">
                            <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{$followers}}</h2>
                            <p class="text-muted"><b>{{translate('Followers')}}</b></p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body grid-center">
                        <div class="text-center">
                            <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{ $total_review}} </h2>
                            <p class="text-muted"><b>{{translate('Reviews')}} </b></p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body grid-center">
                        <div class="text-center">
                            <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{$products_for_review}}</h2>
                            <p class="text-muted"><b>{{translate('Products')}}</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section>
        <div class="container-fluid productcontent">
            <div class="flexible-grid lg-down-1 gap-3" style="--width: 16rem">
                <div class="row g-3">
                    <div class="col-12 mb-md-4 mb-3 ">
                        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
                            <div class="">
                                <div class="d-flex gap-3 align-items-center">
                                    <h3 class="mb-1">{{translate('Search_Product')}}</h3>
                                    <a href="javascript:"
                                        class="text-primary text-decoration-underline fw-semibold">{{$products->count()}}
                                        {{translate('Item')}}</a>
                                </div>
                            </div>
                            <div class="">
                                <div class="d-flex flex-wrap flex-lg-nowrap gap-2">
                                    <div class="search-box">
                                        <form method="get" action="{{route('shopView',['id'=>$seller_id])}}">
                                            <div class="d-flex">
                                                <div class="select-wrap border d-flex align-items-center me-1 rounded">
                                                    <input type="search" class="form-control border-0 mx-w300 h-auto"
                                                        name="product_name" value="{{ request('product_name') }}"
                                                        placeholder="{{translate('Search_for_items')}} ...">
                                                </div>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="border rounded custom-ps-3 p-2 d-flex align-items-center">
                                        <div class="d-flex gap-2">
                                            <div class="flex-middle gap-2">
                                                <i class="bi bi-sort-up-alt"></i>
                                                <span class="d-none d-sm-inline-block">{{translate('Sort_by')}} :</span>
                                            </div>
                                            <div class="dropdown product_view_sort_by">
                                                <button type="button"
                                                    class="border-0 bg-transparent dropdown-toggle text-dark p-0 custom-pe-3"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{translate('default')}}
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end profile__drop"
                                                    id="sort_by_list">
                                                    <li class="sort_by-latest selected" data-value="latest">
                                                        <a class="d-flex dropdown-item" href="javascript:"
                                                            onclick="filter('latest','{{translate('default')}}')">
                                                            {{translate('default')}}
                                                        </a>
                                                    </li>

                                                    <li class="sort_by-high-low" data-value="high-low">
                                                        <a class="d-flex dropdown-item" href="javascript:"
                                                            onclick="filter('high-low','{{translate('High_to_Low_Price')}}')">
                                                            {{translate('High_to_Low_Price')}}
                                                        </a>
                                                    </li>
                                                    <li class="sort_by-low-high" data-value="low-high">
                                                        <a class="d-flex dropdown-item" href="javascript:"
                                                            onclick="filter('low-high','{{translate('Low_to_High_Price')}}')">
                                                            {{translate('Low_to_High_Price')}}
                                                        </a>
                                                    </li>
                                                    <li class="sort_by-a-z" data-value="a-z">
                                                        <a class="d-flex dropdown-item" href="javascript:"
                                                            onclick="filter('a-z','{{translate('A_to_Z_Order')}}')">
                                                            {{translate('A_to_Z_Order')}}
                                                        </a>
                                                    </li>
                                                    <li class="sort_by-z-a" data-value="z-a">
                                                        <a class="d-flex dropdown-item" href="javascript:"
                                                            onclick="filter('z-a','{{translate('Z_to_A_Order')}}')">
                                                            {{translate('Z_to_A_Order')}}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border rounded custom-ps-3 p-2 d-flex align-items-center gap-2">
                                        <div class="d-flex gap-2">
                                            <div class="flex-middle gap-2">
                                                <i class="bi bi-sort-up-alt"></i>
                                                <span class="d-none d-sm-inline-block">{{translate('Show_Product')}} :
                                                </span>
                                            </div>

                                            <div class="dropdown">
                                                <button type="button"
                                                    class="border-0 bg-transparent dropdown-toggle p-0 custom-pe-3"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{$data['data_from']=="best-selling"||$data['data_from']=="top-rated"||$data['data_from']=="featured_deal"||$data['data_from']=="latest"||$data['data_from']=="most-favorite"?
                                                    str_replace(['-', '_', '/'], ' ', translate($data['data_from'])):translate('Choose Option')}}
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end profile__drop">
                                                    <li class="{{$data['data_from']=='latest'? 'selected':''}}">
                                                        <a class="d-flex dropdown-item"
                                                            href="{{route('shopView',['id'=> $data['id'],'data_from'=>'latest','page'=>1])}}">
                                                            {{translate('Latest_Products')}}
                                                        </a>
                                                    </li>
                                                    <li class="{{$data['data_from']=='best-selling'? 'selected':''}}">
                                                        <a class="d-flex dropdown-item"
                                                            href="{{route('shopView',['id'=> $data['id'],'data_from'=>'best-selling','page'=>1])}}">
                                                            {{translate('Best_Selling')}}
                                                        </a>
                                                    </li>
                                                    <li class="{{$data['data_from']=='top-rated'? 'selected':''}}">
                                                        <a class="d-flex dropdown-item"
                                                            href="{{route('shopView',['id'=> $data['id'],'data_from'=>'top-rated','page'=>1])}}">
                                                            {{translate('Top_Rated')}}
                                                        </a>
                                                    </li>
                                                    <li class="{{$data['data_from']=='most-favorite'? 'selected':''}}">
                                                        <a class="d-flex dropdown-item"
                                                            href="{{route('shopView',['id'=> $data['id'],'data_from'=>'most-favorite','page'=>1])}}">
                                                            {{translate('Most_Favorite')}}
                                                        </a>
                                                    </li>
                                                    @if($web_config['featured_deals'])
                                                    <li class="{{$data['data_from']=='featured_deal'? 'selected':''}}">
                                                        <a class="d-flex dropdown-item"
                                                            href="{{route('shopView',['id'=> $data['id'],'data_from'=>'featured_deal','page'=>1])}}">
                                                            {{translate('Featured_Deal')}}
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="card filter-toggle-aside border-0">
                            <!-- div class="d-flex d-lg-none pb-0 p-3 justify-content-end">
                                <button class="filter-aside-close d-none border-0 bg-transparent">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div> -->

                            <!-- filter card -->
                            <div class="filter-section">
                                <nav class="navbar navbar-expand-lg  w-100">
                                    <span class="navbar-toggler dropdown-toggle" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                        aria-expanded="false" aria-label="Toggle navigation">
                                        Filters
                                    </span>
                                    <div class="collapse navbar-collapse w-100" id="navbarSupportedContent">
                                        <ul class="list-group br-12 w-100">
                                            <li class="list-group-item">
                                                <h4 class="my-1"> Filters </h2>
                                            </li>
                                            <li class="list-group-item pricerangeview pb-3">

                                                <label for="" class="lable-flider-vendor">Price</label>
                                                <div class="d-flex">
                                                    <div class="wrapper">
                                                        <div class="price-input mb-3">
                                                            <div class="field mb-0">
                                                                <input type="number" class="input-min" name="min_price" id="price_rangeMin" onchange="sortByfilterBy()" placeholder="Min" value="0">
                                                            </div>
                                                            <div class="separator">To</div>
                                                            <div class="field mb-0">
                                                                <input type="number" class="input-max" name="max_price" id="price_rangeMax" onchange="sortByfilterBy()" placeholder="Max" value="7500">
                                                            </div>
                                                        </div>
                                                        <div class="slider">
                                                            <div class="progress"></div>
                                                        </div>
                                                        <div class="range-input">
                                                            <input type="range" class="range-min" min="0" max="10000" onchange="sortByfilterBy()" value="0" step="100">
                                                            <input type="range" class="range-max" min="0" max="10000" onchange="sortByfilterBy()" value="7500" step="100">
                                                        </div>
                                                    </div>
                                                    <!--   Support Section -->

                                                </div>

                                            </li>
                                            <li class="list-group-item p-0">
                                                <div class="accordion border-0 accordion-main" id="accordionPanelsStayOpenExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header px-2">
                                                            <button class="accordion-button lable-flider-vendor" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#panelsStayOpen-collapseOne"
                                                                aria-expanded="true"
                                                                aria-controls="panelsStayOpen-collapseOne">
                                                                <b>{{translate('All_Categories')}}</b>
                                                            </button>
                                                        </h2>
                                                        @php($categories=\App\CPU\CategoryManager::parents())
                                                        <div id="panelsStayOpen-collapseOne"
                                                            class="accordion-collapse collapse active show">
                                                            <div class="accordion-body">
                                                                <div class="products_aside_categories">
                                                                    <ul class="list-group " id="for-show-more">
                                                                        @foreach($categories as $ckey => $category)
                                                                        <li class="list-group-item border-0 text-muted ps-2 py-0" id="for-show-more-item">
                                                                            <div class="accordion-item border-0">
                                                                                <h2 class="accordion-header">
                                                                                    <button
                                                                                        class="accordion-button py-2 collapsed"
                                                                                        type="button"
                                                                                        data-bs-toggle="collapse"
                                                                                        data-bs-target="#category_{{$ckey}}"
                                                                                        aria-expanded="true"
                                                                                        aria-controls="category_{{$ckey}}">
                                                                                        <div
                                                                                            class="d-flex justify-content-between">
                                                                                            <a href="{{ route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1]) }}" class=""><span>{{ $category['name'] }}</span><span> ({{$category['product_count']}})</span></a>
                                                                                        </div>
                                                                                    </button>
                                                                                </h2>

                                                                                <div id="category_{{$ckey}}"
                                                                                    class="accordion-collapse collapse">
                                                                                    <div class="accordion-body py-0 ">
                                                                                        <div
                                                                                            class="products_aside_categories">
                                                                                            @if($category->childes->count() > 0)
                                                                                            <ul class="sub_menu">
                                                                                                @foreach($category->childes as $child)
                                                                                                <li>
                                                                                                    <div class="d-flex justify-content-between collapsed">
                                                                                                        <a href="{{route('products',['id'=> $child['id'],'data_from'=>'category','page'=>1])}}" class="text-muted"><span>{{$child['name']}}</span><span> ({{ $child['sub_category_product_count'] }})</span></a>
                                                                                                    </div>

                                                                                                    @if($child->childes->count() > 0)
                                                                                                    <ul
                                                                                                        class="sub-sub sub_menu">
                                                                                                        @foreach($child->childes as $ch)
                                                                                                        <li>
                                                                                                            <label
                                                                                                                class="custom-checkbox">
                                                                                                                <a
                                                                                                                    href="{{route('products',['id'=> $ch['id'],'data_from'=>'category','page'=>1])}}" class="d-flex justify-content-between">
                                                                                                                    <span>{{$ch['name']}}

                                                                                                                    </span>
                                                                                                                    <span>({{ $ch['sub_sub_category_product_count'] }})</span></a>
                                                                                                            </label>
                                                                                                        </li>
                                                                                                        @endforeach
                                                                                                    </ul>
                                                                                                    @endif
                                                                                                </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- Sub Menu -->
                                                                        </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                                <!--   @if ($categories->count() > 10)
                                                                <div class="d-flex justify-content-center">
                                                                    <a
                                                                        class="proceed-to-buy mt-3">{{translate('More_Categories')}}...</a>
                                                                </div>
                                                                @endif -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item d-none">

                                                <label for="" class="lable-flider-vendor">Color</label>
                                                <ul class="list-group">
                                                    <li class="list-group-item border-0 text-muted ps-0">
                                                        <input class="form-check-input me-1" type="checkbox" value=""
                                                            aria-label="...">
                                                        Black
                                                    </li>
                                                    <li class="list-group-item border-0 text-muted ps-0">
                                                        <input class="form-check-input me-1" type="checkbox" value=""
                                                            aria-label="...">
                                                        Green
                                                    </li>
                                                    <li class="list-group-item border-0 text-muted ps-0">
                                                        <input class="form-check-input me-1" type="checkbox" value=""
                                                            aria-label="...">
                                                        Orange
                                                    </li>
                                                    <li class="list-group-item border-0 text-muted ps-0">
                                                        <input class="form-check-input me-1" type="checkbox" value=""
                                                            aria-label="...">
                                                        Yellow
                                                    </li>

                                                </ul>
                                            </li>

                                            <li class="list-group-item p-0">
                                                @if($web_config['brand_setting'])
                                                <!-- Brands -->
                                                <div class="brands-on-sidebar">
                                                    @php($brands = \App\CPU\BrandManager::get_active_brands())
                                                    <div class="accordion" id="accordionExample">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header px-2" id="headingOne">
                                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                    <label for="" class="lable-flider-vendor"><b>{{translate('Brands')}}</b></label>
                                                                </button>
                                                            </h2>
                                                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                                @foreach($brands as $brand)
                                                                <div class="accordion-body ">
                                                                    <div class="flex-between-gap-3 align-items-center px-2">
                                                                        <label class="custom-checkbox">
                                                                            <input class="form-check-input me-1 d-none" type="checkbox"
                                                                                value="" aria-label="...">
                                                                            <a href="{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}"
                                                                                class="text-dark ps-0">{{ $brand['name'] }}</a>
                                                                        </label>
                                                                        <span class="badge bg-secondary">{{ $brand['brand_products_count'] }}</span>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($brands->count() > 10)
                                                    <div class="d-flex justify-content-center">
                                                        <button
                                                            class="btn-link text-primary btn_products_aside_brands">{{translate('More_Brands')}}...</button>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif

                                            </li>

                                            <!-- brand end -->
                                            <li class="list-group-item">

                                                <label for="" class="lable-flider-vendor"><b>Rating</b></label>
                                                <ul class="list-group">
                                                    <li class="list-group-item border-0  ps-0" onclick="filterByRating(5)">
                                                        {{-- <input class="form-check-input me-1" type="checkbox" value="5" aria-label="..." > --}}
                                                        <i class="fa-solid fa-star text-muted"></i> 4 &amp; above
                                                    </li>
                                                    <li class="list-group-item border-0  ps-0" onclick="filterByRating(4)">
                                                        {{-- <input class="form-check-input me-1" type="checkbox" value="4" aria-label="..."> --}}
                                                        <i class="fa-solid fa-star text-muted"></i> 3 &amp; above
                                                    </li>
                                                    <li class="list-group-item border-0  ps-0" onclick="filterByRating(3)">
                                                        {{-- <input class="form-check-input me-1" type="checkbox" value="3" aria-label="..."> --}}
                                                        <i class="fa-solid fa-star text-muted"></i> 2 &amp; above
                                                    </li>


                                                </ul>
                                            </li>
                                            <li class="list-group-item d-none">

                                                <label for="" class="lable-flider-vendor">Display Size</label>
                                                <ul class="list-group">
                                                    <li class="list-group-item border-0  ps-0">
                                                        <input class="form-check-input me-1" type="checkbox" value=""
                                                            aria-label="...">
                                                        24-Inch
                                                    </li>
                                                    <li class="list-group-item border-0  ps-0">
                                                        <input class="form-check-input me-1" type="checkbox" value=""
                                                            aria-label="...">
                                                        27-Inch
                                                    </li>
                                                    <li class="list-group-item border-0  ps-0">
                                                        <input class="form-check-input me-1" type="checkbox" value=""
                                                            aria-label="...">
                                                        27-Inch
                                                    </li>


                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>


                            <div class="card-body d-flex flex-column gap-4 d-none">
                                <!-- Categories -->
                                <div>
                                    <h6 class="mb-3">{{translate('Categories')}}</h6>
                                    @php($categories=\App\CPU\CategoryManager::parents())
                                    <div class="products_aside_categories">
                                        <ul
                                            class="common-nav flex-column nav custom-scrollbar flex-nowrap custom_common_nav">
                                            @foreach($categories as $category)
                                            <li>
                                                <div class="d-flex justify-content-between">
                                                    <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">{{$category['name']}}</a>
                                                </div>
                                                <!-- Sub Menu -->
                                                @if ($category->childes->count() > 0)
                                                <ul class="sub_menu">
                                                    @foreach($category->childes as $child)
                                                    <li>
                                                        <div class="d-flex justify-content-between">
                                                            <a
                                                                href="{{route('products',['id'=> $child['id'],'data_from'=>'category','page'=>1])}}">{{$child['name']}}</a>
                                                            @if ($child->childes->count() > 0)

                                                            @endif
                                                        </div>

                                                        @if ($child->childes->count() > 0)
                                                        <ul class="sub_menu">
                                                            @foreach($child->childes as $ch)
                                                            <li>
                                                                <label class="custom-checkbox">
                                                                    <a href="{{route('products',['id'=> $ch['id'],'data_from'=>'category','page'=>1])}}">{{$ch['name']}}</a>
                                                                </label>
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
                                    </div>
                                    @if ($categories->count() > 10)
                                    <div class="d-flex justify-content-center">
                                        <button
                                            class="btn-link text-primary btn_products_aside_categories">{{translate('More_Categories')}}...</button>
                                    </div>
                                    @endif
                                </div>

                                @if($web_config['brand_setting'])
                                <!-- Brands -->
                                <div>
                                    @php($brands = \App\CPU\BrandManager::get_active_brands())
                                    <h6 class="mb-3">{{translate('Brands')}}</h6>
                                    <div class="products_aside_brands">
                                        <ul class="common-nav nav flex-column pe-2">
                                            @foreach($brands as $brand)
                                            <li>
                                                <div class="flex-between-gap-3 align-items-center">
                                                    <label class="custom-checkbox">
                                                        <a
                                                            href="{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}">{{ $brand['name'] }}</a>
                                                    </label>
                                                    <span
                                                        class="badge bg-badge rounded-pill text-dark">{{ $brand['brand_products_count'] }}</span>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    @if($brands->count() > 10)
                                    <div class="d-flex justify-content-center">
                                        <button
                                            class="btn-link text-primary btn_products_aside_brands">{{translate('More_Brands')}}...</button>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <!-- Ratings -->
                                <div id="ajax-review_partials">
                                    @include('theme-views.partials._products_review_partials', ['ratings'=>$ratings])
                                </div>

                                <!-- Price -->
                                {{-- <div>
                                    <h6 class="mb-3">{{translate('Price')}}</h6>
                                <div class="d-flex align-items-end gap-2">
                                    <div class="form-group">
                                        <label for="min_price" class="mb-1">{{translate('Min')}}</label>
                                        <input type="number" id="min_price" class="form-control form-control--sm"
                                            placeholder="$0">
                                    </div>
                                    <div class="mb-2">-</div>
                                    <div class="form-group">
                                        <label for="max_price" class="mb-1">{{translate('Max')}}</label>
                                        <input type="number" id="max_price" class="form-control form-control--sm"
                                            placeholder="$1,000">
                                    </div>
                                    <button class="btn btn-primary py-1 px-2 fs-13"
                                        onclick="sortByfilterBy()"></button>
                                </div>

                                <section class="range-slider">
                                    <span class="full-range"></span>
                                    <span class="incl-range"></span>
                                    <input name="rangeOne" value="0" min="0" max="10000" step="1" type="range"
                                        id="price_rangeMin">
                                    <input name="rangeTwo" value="5000" min="0" max="10000" step="1" type="range"
                                        id="price_rangeMax">
                                </section>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    @php($decimal_point_settings =
                    \App\CPU\Helpers::get_business_settings('decimal_point_settings'))

                    <div id="ajax-products-view">
                        @include('theme-views.product._ajax-products',['products'=>$products,'decimal_point_settings'=>$decimal_point_settings])
                    </div>
                </div>

            </div>


        </div>

        </div>
    </section>

    @if (count($featured_products) > 0)
    <section class="bg-primary-light">
        <div class="container-fluid productcontent">
            <div class="">
                <div class="">
                    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3 mb-sm-4">
                        <h2>{{translate('Featured_Products')}}</h2>
                        <div class="swiper-nav d-flex gap-2 align-items-center d-none">
                            <div class="swiper-button-prev top-rated-nav-prev position-static rounded-10"></div>
                            <div class="swiper-button-next top-rated-nav-next position-static rounded-10"></div>
                        </div>
                    </div>
                    @if (count($featured_products) > 0)
                    <div class="owl-slider">
                        <div id="carousel-featured_products" class="owl-carousel">
                            @foreach ($featured_products as $product)
                            <div class="item">
                                @include('theme-views.partials._product-large-card', ['product'=>$product])
                            </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="swiper-container d-none">
                        <!-- Swiper -->
                        <div class="position-relative">
                            <div class="swiper" data-swiper-loop="false" data-swiper-margin="20"
                                data-swiper-autoplay="true" data-swiper-pagination-el="null"
                                data-swiper-navigation-next=".top-rated-nav-next"
                                data-swiper-navigation-prev=".top-rated-nav-prev"
                                data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "320": {"slidesPerView": "2"}, "992": {"slidesPerView": "3"}, "1200": {"slidesPerView": "4"}, "1400": {"slidesPerView": "5"}}'>
                                <div class="swiper-wrapper">
                                    @foreach ($featured_products as $product)
                                    <div class="swiper-slide mx-w300">
                                        <!-- Single Product -->
                                        @include('theme-views.partials._product-large-card', ['product'=>$product])
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div>
                        {{translate('No_Featured_Product_Found')}}.
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
    @endif
</main>
<!-- End Main Content -->

<span id="filter_url" data-url="{{url('/')}}/shopView/{{$shop['id']}}"></span>
<span id="product_view_style_url" data-url="{{route('product_view_style')}}"></span>
<span id="shop_follow_url" data-url="{{route('shop_follow')}}"></span>
<input type="hidden" value="{{$data['data_from']}}" id="data_from">
<input type="hidden" value="{{$data['id']}}" id="data_id">
<input type="hidden" value="{{$data['name']}}" id="data_name">
<input type="hidden" value="{{$data['min_price']}}" id="data_min_price">
<input type="hidden" value="{{$data['max_price']}}" id="data_max_price">




<script type="text/javascript">
    const rangeInput = document.querySelectorAll(".range-input input"),
        priceInput = document.querySelectorAll(".price-input input"),
        range = document.querySelector(".slider .progress");
    let priceGap = 1000;

    priceInput.forEach((input) => {
        input.addEventListener("input", (e) => {
            let minPrice = parseInt(priceInput[0].value),
                maxPrice = parseInt(priceInput[1].value);

            if (maxPrice - minPrice >= priceGap && maxPrice <= rangeInput[1].max) {
                if (e.target.className === "input-min") {
                    rangeInput[0].value = minPrice;
                    range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
                } else {
                    rangeInput[1].value = maxPrice;
                    range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
                }

                $('#data_min_price').val(minPrice)
                $('#data_max_price').val(maxPrice)

            }
        });
    });

    rangeInput.forEach((input) => {
        input.addEventListener("input", (e) => {
            let minVal = parseInt(rangeInput[0].value),
                maxVal = parseInt(rangeInput[1].value);

            if (maxVal - minVal < priceGap) {
                if (e.target.className === "range-min") {
                    rangeInput[0].value = maxVal - priceGap;
                } else {
                    rangeInput[1].value = minVal + priceGap;
                }
            } else {
                priceInput[0].value = minVal;
                priceInput[1].value = maxVal;
                range.style.left = (minVal / rangeInput[0].max) * 100 + "%";
                range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
            }
            $('#data_min_price').val(minVal)
            $('#data_max_price').val(maxVal)
        });
    });
</script>

<!-- End Main Content -->
@endsection