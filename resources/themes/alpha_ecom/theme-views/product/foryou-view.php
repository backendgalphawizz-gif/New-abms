@extends('theme-views.layouts.app')

@section('title',translate(str_replace(['-', '_', '/'],' ',$data['data_from'])).' '.translate('products'))

@push('css_or_js')
<meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']}}" />
<meta property="og:title" content="Products of {{$web_config['name']}} " />
<meta property="og:url" content="{{env('APP_URL')}}">
<meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

<meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']}}" />
<meta property="twitter:title" content="Products of {{$web_config['name']}}" />
<meta property="twitter:url" content="{{env('APP_URL')}}">
<meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
@endpush

@section('content')
<main class="main-content d-flex flex-column gap-3 pt-md-3 customSection">
    <section>
        <div class="container-fluid productcontent">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row gy-2 align-items-center">
                        <div class="col-md-6">
                            <h3 class="mb-1">{{ translate('all_Categories') }}</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb fs-12 mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ translate('Categories') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-lg-6">


                            <div class="d-flex justify-content-lg-end flex-wrap gap-2">
                                <div class="border rounded custom-ps-3 p-2 ">
                                    <div class="d-flex gap-2 ">
                                        <div class="flex-middle gap-2">
                                            <i class="bi bi-sort-up-alt"></i>
                                            <span class="d-none d-sm-inline-block">{{translate('Sort_by')}} :</span>
                                        </div>
                                        <div class="dropdown product_view_sort_by ">
                                            <button type="button"
                                                class="border-0 bg-transparent text-dark p-0 custom-pe-3 d-flex justify-content-between align-items-center gap-2"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <span>{{translate('default')}}</span>
                                                <i class="fa-solid fa-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-start profile__drop" id="sort_by_list">
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
                                <div class="border rounded custom-ps-3 p-2">
                                    <div class="d-flex gap-2">
                                        <div class="flex-middle gap-2">
                                            <i class="bi bi-sort-up-alt"></i>
                                            <span class="d-none d-sm-inline-block">{{translate('Show_Product')}}
                                                :</span>
                                        </div>
                                        <div class="dropdown">
                                            <button type="button"
                                                class="border-0 bg-transparent d-flex align-items-center justify-content-between custom-pe-3"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                {{$data['data_from']=="best-selling"||$data['data_from']=="top-rated"||$data['data_from']=="featured_deal"||$data['data_from']=="latest"||$data['data_from']=="most-favorite"?
                        str_replace(['-', '_', '/'], ' ', translate($data['data_from'])):translate('Choose Option')}}<i class="fa-solid fa-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-start profile__drop">
                                                <li class="{{$data['data_from']=='latest'? 'selected':''}}">
                                                    <a class="d-flex dropdown-item"
                                                        href="{{route('products',['id'=> $data['id'],'data_from'=>'latest','page'=>1])}}">
                                                        {{translate('Latest_Products')}}
                                                    </a>
                                                </li>
                                                <li class="{{$data['data_from']=='best-selling'? 'selected':''}}">
                                                    <a class="d-flex dropdown-item"
                                                        href="{{route('products',['id'=> $data['id'],'data_from'=>'best-selling','page'=>1])}}">
                                                        {{translate('Best_Selling')}}
                                                    </a>
                                                </li>
                                                <li class="{{$data['data_from']=='top-rated'? 'selected':''}}">
                                                    <a class="d-flex dropdown-item"
                                                        href="{{route('products',['id'=> $data['id'],'data_from'=>'top-rated','page'=>1])}}">
                                                        {{translate('Top_Rated')}}
                                                    </a>
                                                </li>
                                                <li class="{{$data['data_from']=='most-favorite'? 'selected':''}}">
                                                    <a class="d-flex dropdown-item"
                                                        href="{{route('products',['id'=> $data['id'],'data_from'=>'most-favorite','page'=>1])}}">
                                                        {{translate('Most_Favorite')}}
                                                    </a>
                                                </li>
                                                @if($web_config['featured_deals'])
                                                <li class="{{$data['data_from']=='featured_deal'? 'selected':''}}">
                                                    <a class="d-flex dropdown-item"
                                                        href="{{route('products',['id'=> $data['id'],'data_from'=>'featured_deal','page'=>1])}}">
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
            </div>


            <div class="flexible-grid lg-down-1 gap-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card filter-toggle-aside border-0">

                            <div class="filter-section">
                                <nav class="navbar navbar-expand-lg pb-0 w-100">
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
                                                                                            <a href="{{ route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1]) }}" class="filterdiv"><span>{{ $category['name'] }}</span><span> ({{$category['product_count']}})</span></a>
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
                                                                                class="text-dark ps-0 filterdiv">{{ $brand['name'] }}</a>
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
                                            <li class="list-group-item" style="border-radius: 0 0 10px 10px;">

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

                <div class="col-md-9">
                    <div
                        class="d-none flex-wrap flex-lg-nowrap align-items-start justify-content-between gap-3 mb-2">
                        <div class="flex-middle gap-3"></div>
                        <div class="d-flex align-items-center mb-3 mb-md-0 flex-wrap flex-md-nowrap gap-3">
                            <ul class="product-view-option option-select-btn gap-3">
                                <li>
                                    <label>
                                        <input type="radio" name="product_view" value="grid-view" hidden=""
                                            {{(session()->get('product_view_style') == 'grid-view'?'checked':'')}}
                                            id="grid-view">
                                        <span class="py-2 d-flex align-items-center gap-2"><i
                                                class="bi bi-grid-fill"></i> {{translate('Grid_View')}}</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="product_view" value="list-view" hidden=""
                                            {{(session()->get('product_view_style') == 'list-view'?'checked':'')}}
                                            id="list-view">
                                        <span class="py-2 d-flex align-items-center gap-2"><i
                                                class="bi bi-list-ul"></i> {{translate('List_View')}}</span>
                                    </label>
                                </li>
                            </ul>
                            <button class="toggle-filter square-btn btn btn-outline-primary rounded d-lg-none">
                                <i class="bi bi-funnel"></i>
                            </button>
                        </div>
                    </div>
                    @php($decimal_point_settings =
                    \App\CPU\Helpers::get_business_settings('decimal_point_settings'))
                    <div id="ajax-products-view">
                  

                        @include('theme-views.product._ajax-products', ['products' => $productsForYou,'decimal_point_settings'=>$decimal_point_settings])
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>




</main>

<span id="filter_url" data-url="{{url('/')}}/products"></span>
<span id="product_view_style_url" data-url="{{route('product_view_style')}}"></span>
<input type="hidden" value="{{ implode(',', $data['id']) }}" id="data_id">

<input type="hidden" value="{{$data['name']}}" id="data_name">
<input type="hidden" value="{{$data['data_from'] ?? '' }}" id="data_from">
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