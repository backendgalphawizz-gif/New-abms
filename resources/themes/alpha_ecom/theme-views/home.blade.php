@extends('theme-views.layouts.app')

@section('title', $web_config['name']->value.' '.translate('Online Shopping').' | '.$web_config['name']->value.'
'.translate(' Ecommerce'))
@push('css_or_js')
<meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="og:title" content="Welcome To {{$web_config['name']->value}} Home" />
<meta property="og:url" content="{{env('APP_URL')}}">
<meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

<meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="twitter:title" content="Welcome To {{$web_config['name']->value}} Home" />
<meta property="twitter:url" content="{{env('APP_URL')}}">
<meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
@endpush

@section('content')
<main class="main-content d-flex flex-column gap-3 py-3 d-none">
    {{-- @include('theme-views.partials._main-banner') --}}

    @if (isset($web_config['flash_deals']))
    {{-- @include('theme-views.partials._flash-deals') --}}
    @endif
    {{-- @include('theme-views.partials._find-what-you-need') --}}
    @if (isset($web_config['business_mode']) && $web_config['business_mode'] == 'multi' && count($top_sellers) > 0)
    {{-- @include('theme-views.partials._top-stores') --}}
    @endif
    @if (isset($web_config['featured_deals']) && $web_config['featured_deals']->count()>0)
    {{-- @include('theme-views.partials._featured-deals') --}}
    @endif
    {{-- @include('theme-views.partials._recommended-product') --}}
    @if(isset($web_config['business_mode']) && $web_config['business_mode'] == 'multi')
    {{-- @include('theme-views.partials._more-stores') --}}
    @endif
    {{-- @include('theme-views.partials._top-rated-products') --}}
    {{-- @include('theme-views.partials._best-deal-just-for-you') --}}
    {{-- @include('theme-views.partials._home-categories') --}}
    @if (isset($main_section_banner))
        {{--
            <section class="">
                <div class="container">
                    <div class="py-5 rounded position-relative">
                        <img src="{{asset('storage/app/public/banner')}}/{{$main_section_banner ? $main_section_banner['photo'] : ''}}"
                            onerror="this.src='{{theme_asset('assets/img/main-section-banner-placeholder.png')}}'" alt=""
                            class="rounded position-absolute dark-support img-fit start-0 top-0 index-n1 flipX-in-rtl">
                        <div class="row justify-content-center">
                            <div class="col-10 py-4">
                                <h6 class="text-primary mb-2">{{ translate('Don’t_Miss_Todays_Deal') }}!</h6>
                                <h2 class="fs-2 mb-4 absolute-dark">{{ translate('Let’s_Shopping_Today') }}</h2>
                                <div class="d-flex">
                                    <a href="{{$main_section_banner ? $main_section_banner->url:''}}"
                                        class="btn btn-primary fs-16">{{ translate('Shop_Now') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        --}}
    @endif
</main>

<section class="banner-desktop">
    {{-- @include('theme-views.partials._main-banner') --}}
    <div class="owl-carousel banner-carousel owl-theme">
        @foreach($main_banner as $key=>$banner)
            <div class="item">
                <a href="#" class="h-100">
                    <img src="{{asset('storage/app/public/banner')}}/{{$banner['photo']}}" alt="" width="100%">
                </a>
            </div>
        @endforeach
        @if(count($main_banner)==0)
            <img src="{{theme_asset('assets/img/image-place-holder-2:1.png')}}" loading="lazy" alt="" class="dark-support rounded">
        @endif
    </div>
</section>



<section class="banner-mobile">
    <div class="owl-carousel banner-carousel-mobile  owl-theme">
        @foreach($main_banner as $key=>$banner)
            <div class="item">
                <a href="#" class="h-100">
                    <img src="{{asset('storage/app/public/banner')}}/{{$banner['photo']}}" alt="" width="100%">
                </a>
            </div>
        @endforeach
        @if(count($main_banner)==0)
            <img src="{{theme_asset('assets/img/image-place-holder-2:1.png')}}" loading="lazy" alt="" class="dark-support rounded">
        @endif
    </div>
</section>


<img class="shapeOne" src="{{ theme_asset('assets/images/shapeOne.png')}}" alt="">

<section class="pageSection featureBackground" style="background-image: url({{ theme_asset('assets/images/PB-Assets.png')}});">
    <div class="container">
         <img src="{{ theme_asset('assets/images/Special-Features.png')}}" alt="" style="width: 30%;" class="featuresimg">
        <div class="spacialContainer mt-4 ">
            <a href="{{ route('brands') }}" class="featureDiv">
                <div class="borderCustom">
                    <h6>Shop by Brands</h6>
                    <img src="{{ theme_asset('assets/images/feature1.png')}}" alt="">
                </div>
            </a>
            <a href="{{ route('products',['page'=>1]) }}" class="featureDiv ">
                <div class="borderCustom">
                    <h6>Shop by Categories</h6>
                    <img src="{{ theme_asset('assets/images/feature2.png')}}" alt="">
                </div>
            </a>
            <a href="{{ route('sellers') }}" class="featureDiv">
                <div class="borderCustom">
                    <h6>Shop by Distributer</h6>
                    <img src="{{ theme_asset('assets/images/feature3.png')}}" alt="" style="object-fit: none;">
                </div>
            </a>
        </div>
    </div>
</section>
<img class="shapeTwo" src="{{ theme_asset('assets/images/shapeOne.png')}}" alt="">
<section class="pageSection customBanner d-none">
    <img src="{{ theme_asset('assets/images/primeBanner.png')}}" alt="">
</section>


<!-- Special Offer -->
<section class="bg-special-offer" style="display: none;">
    @if (isset($flash_dealss) && $flash_dealss->count() > 0)
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <h2 class="text-heading-mark mb-4 pt-md-4">Special Offer</h2>
                </div>
                <div class="col-12">
                     <div class="owl-slider">
                        <div id="carousel-bg-special" class="owl-carousel">
                            @foreach($flash_dealss as $flashDeal)
                                <div class="item">
                                    <div class="position-relative hover-and-hidden">
                                        <img onerror=this.src="{{asset('public/assets/front-end/img/placeholder.png')}}" src="{{asset('storage/app/public/deal')}}/{{$flashDeal->banner}}" alt="" width="100%" class="sacle1">
                                        <div class="caption-box-content">
                                            <h6>{{ $flashDeal->title }}</h6>
                                            {{-- <p>{{ $flashDeal->title }}</p> --}}
                                            <a href="{{ route('products', ['id' => $flashDeal->id, 'data_from' => 'featured_deal', 'page' => 1] ) }}" type="button" class="btn-shop-now btn-12">Shop Now</a>

                                        </div>
                                    </div>
                                </div>
                            @endforeach  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div class="container mt-md-5 mt-4" data-aos="fade-up" data-aos-duration="3000">
        <div class="bg-banner-for-offer">
            <div class="row">
                <div class="col-12">
                    <div class="bg-white-set-padding">
                        <div class="to-welcome-autuman">
                            <h2>WELCOME AUTUMN</h2>
                            <p>Lorem ipsum dolor sit amet consectetur <br> adipisicing elit. Maxime unde amet
                                <br>dignissimos aut
                                accusantium perspiciatis.
                            </p>

                            <ul class="list-unstyled d-flex align-items-center p-0">
                                <li><a href=""><i class="fa-brands fa-whatsapp"></i></a></li>
                                <li><a href=""><i class="fa-brands fa-instagram"></i></a></li>
                                <li><a href=""><i class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href=""><i class="fa-brands fa-twitter"></i></a></li>
                            </ul>

                            <img src="{{ theme_asset('assets/images/dummy-couple-images.png')}}" alt="" class="cloudflare">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="pageSection productdiv mt-3">
    <div class="container-fluid productcontent">
    <h2 >New Arrivals <a href="{{ route('products') }}"><span>See All </span> <i class="fa-solid fa-angle-right"></i></a> </h2>
        <div class="row mt-4 productstyle">
            
           @foreach($newlyAddedProducts as $key => $product)
                @if($key <= 5)
                <div class="categoryCardDiv" data-aos="flip-left">
                     <div class="productCard productcartdiv">
                        <a href="{{ route('product', $product->slug) }}" class="productCardImg">
                       
                                <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                    onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" alt=""
                                    loading="lazy" class="control-heightimg">
                                     <div class="productlikeitem">
                                              <i class="fa-solid fa-heart"></i>
                                        </div>
                                    <div class="discountLable">
                                        Flat{{ $product->discount }} % Off
                                    </div>
                                    <div class="productHead mt-2">
                                <h6>{{ $product->name }}</h6>
                                <label for="">MRP price</label>
                                <div class="priceClass">
                                            <span>₹{{ $product->selling_price }}/-</span>
                                            <span class="lableSpan">₹{{ $product->unit_price }}</span>
                                        </div>
                                <div class="productBtn">
                                <button class="" href="{{ route('product', $product->slug) }}"><img src="{{ theme_asset('assets/images/add.png')}}" alt="">
                                    Add 
                                </button>
                                <button data-bs-toggle="modal" data-bs-target="#productCard"><img src="{{ theme_asset('assets/images/noteIcon.png')}}" alt=""></button>
                                </div>
                                    
                                </div>
                       
                    </a>
                     </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<section class="pageSection productdiv">
    <div class="container-fluid productcontent">
    <h2>Top Brands <a href="{{ route('brands') }}"><span>See All </span> <i class="fa-solid fa-angle-right"></i></a> </h2>
        <div class="brandContainer productstyle">
            @foreach($brands as $brand)
            <div class="brandcontent">
            <div class="brandDiv">
                <a href="{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}" class="brand_div">
                    <img src="{{asset("storage/app/public/brand/$brand->image")}}" alt="">
                   
                </a>
            </div>
            <div class="titlediv">
            <label for="">{{$brand->name}}</label>
            </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- PRODUCT FOR YOU -->
<section class="pageSection productdiv">
    <div class="container-fluid productcontent">
        <h2>Product For You <a href="{{ route('products', ['data_from' => 'product_for_you']) }}"><span>See All </span> <i class="fa-solid fa-angle-right"> </i></a></h2>
        <div class="row mt-4 productstyle">
            
            @if(isset($topProducts) && count($topProducts) > 0)
                @foreach($topProducts as $key => $product)
                    <div class="categoryCardDiv" data-aos="flip-left">
                        <div class="productCard productcartdiv">
                            <a href="{{ route('product', $product->slug) }}" class="productCardImg">
                                <img src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $product['thumbnail'] }}"
                                    onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" alt=""
                                    loading="lazy" class="control-heightimg">
                                <div class="productlikeitem">
                                    <i class="fa-solid fa-heart"></i>
                                </div>
                                <div class="discountLable">
                                    Flat {{ $product->discount }}% Off
                                </div>
                           
                                <div class="productHead mt-2">
                                    <h6>{{ $product->name }}</h6>
                                  
                                    <label for="">MRP price</label>
                                    <div class="priceClass">
                                        <span>₹{{ $product->selling_price }}/-</span>
                                        <span class="lableSpan">₹{{ $product->unit_price }}</span>
                                    </div>
                                    <div class="productBtn">
                                        <button href="{{ route('product', $product->slug) }}">
                                            <img src="{{ theme_asset('assets/images/add.png') }}" alt=""> Add 
                                        </button>
                                        <button data-bs-toggle="modal" data-bs-target="#productCard">
                                            <img src="{{ theme_asset('assets/images/noteIcon.png') }}" alt="">
                                        </button>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <p>No products found.</p>
            @endif
        </div>
    </div>
</section>


<!-- DAILY DEAL -->
<section style="display: none;">
    <div class="prime-time-deal">
        <h4>Prime Time Deals</h4>
    </div>

    <div class="daily-deal">
        <div class="container">
            <div class="col-md-10 mx-auto">
                <h2 class="text-heading-mark pt-md-4">Daily Deals</h2>
            </div>

            <div class="col-lg-10 col-md-11 mx-auto mt-md-2 mt-4 pt-md-4 pt-0">
                <div class="owl-carousel deal-primecardprimer">
                    <div class="item">
                        <div class="best-sale-bg">
                            <h4>BEST SALE</h4>
                            <p>UP TO 10% OFF</p>
                        </div>
                    </div>


                    <div class="item">
                        <div class="best-sale-bg bg-change-bst1">
                            <h4 class="mt-0">BEST OFFER</h4>
                        </div>
                    </div>


                    <div class="item">
                        <div class="best-sale-bg bg-change-bst2">
                            <h4>BIG SALE</h4>
                            <p>UP TO 20% OFF</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </div>
</section>

<!-- View your cart Items -->
@php($cart=\App\CPU\CartManager::get_cart())
@if($cart->count() > 0)
    <section style="display: none;" class="view-item-cart" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
        <div class="container">
            <div class="row cart-item-slider">
                <div class="col-12 ">

                <h2> {{translate('View_cart_Items')}}</h2>
                </div>
                <div class="col-12">
                <div class="owl-slider mx-auto">
                    <div id="cart-item-slider" class="owl-carousel">
                        @foreach($cart as $cart_item)
                            <div class="item">
                                <div class="hero-banner-images hight-300">
                                    <div class="card view-card-item">

                                        <div class="images-item">
                                            <a href="{{ route('product', $cart_item['slug']) }}">
                                                <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$cart_item['thumbnail']}}" onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'" alt="">
                                            </a>
                                        </div>
                                        <div class="item-detail-cart">

                                            <div class="d-flex justify-content-end  align-items-center">
                                                <div class="item-offspan">
                                                    <!-- <span>35% off </span> -->
                                                </div>
                                                <div class="item-like">
                                                     @php($wishlist = count($product->wish_list)>0 ? 1 : 0) 
                                                    <a
                                                        onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}')"
                                                        class="btn-wishlist stopPropagation add_to_wishlist cursor-pointer wishlist-{{$product['id']}} {{($wishlist == 1?'wishlist_icon_active':'')}}"
                                                        title="{{translate('add_to_wishlist')}}"
                                                    >
                                                        <i class="fa-solid fa-heart"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="content-item-cart">
                                                <p class="text-white name-item">{{ $cart_item['name'] }}</p>
                                                <p class="item-bill">
                                                    <span>
                                                {{ \App\CPU\Helpers::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($cart_item['price'] - $cart_item['discount'])) }} </span> 
                                                <s>{{ \App\CPU\Helpers::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($cart_item['price'])) }} </s>
                                                </p>
                                                <a href="javascript:void(0)" type="button" class="btn-add-item" onclick="updateCartQuantityList('1', '{{$cart_item['id']}}', '-1', 'delete')"> <img src="{{ theme_asset('assets/images/Delete.svg')}}" alt="">Remove</a>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> 
                </div>
            </div>
        </div>
        </div>
    </section>
@endif

<!-- UP TO SALE -->
<section class="mt-md-5 d-none">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="row">
                    <div class="col-md-7">
                        <div class="mobile-upto-sale" data-aos="zoom-out-up">
                            <div class="top-mobile-sale-page">
                                <h4>Sale <span>up to</span></h4>
                                <p>Lorem Ipsum is simply dummy text of the printing </p>
                            </div>
                            <div class="image-sale-item ">
                                <img src="{{ theme_asset('assets/images/mobile.png')}}" alt="">
                                <div class="badge-off">
                                    20% OFF
                                </div>
                            </div>



                        </div>
                    </div>

                    <div class="col-lg-5 col-md-5" data-aos="zoom-out-down">
                        <div class="top-jerry-sale-page">
                            <div class=" top-jerry-sale-page-content">
                                <h4>Sale <span>up to</span></h4>
                                <p>Lorem Ipsum is simply dummy text of the printing </p>
                                <span class="normal-badge">20% OFF</span>
                            </div>
                            <div class="image-sale-item ">
                                <img src="{{ theme_asset('assets/images/top-jerry.png')}}" alt="">

                            </div>



                        </div>
                    </div>



                    <div class="col-md-12 mt-md-4 mt-3" data-aos="zoom-out-left">
                        <div class="top-jerry-sale-page bg-change-sale-sofa">
                            <div class="pt-md-4">
                                <h4>Sale <span>up to</span></h4>
                                <p>Lorem Ipsum is simply dummy text of the printing </p>
                                <span class="normal-badge mt-md-5 mt-4 ">20% OFF</span>
                            </div>
                            <div class="funtuter-100">
                                <img src="{{ theme_asset('assets/images/funuture.png')}}" alt="">

                            </div>



                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-10 mt-md-3 mt-lg-1 mt-3" data-aos="zoom-out-right">
                <div class="lady-designer">
                    <h4>Sale <span>up to</span></h4>
                    <p>Lorem Ipsum is simply dummy text of the printing </p>
                    <img src="{{ theme_asset('assets/images/designer-dress.png')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LET THEM PICK THE PERFECT GIFT -->
@if(!empty($web_config['gift_products_query']))
    <section style="display: none;" class="gift-perfect-the" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5 ps-0">
                    <div class="bg-giftand-trofe">
                        <img src="{{ theme_asset('assets/images/gift-box.png')}}" alt="">
                        <h4>{{ $web_config['app_home']['gift_section']['gift_title'] ?? '' }}</h4>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="book-slider">
                        <div class="owl-carousel books-slider-carousel owl-theme">
                            @foreach($web_config['gift_products_query'] as $gProduct)
                                <div class="item">
                                    <a href="{{ route('product', $gProduct->slug) }}">
                                        <img width="100" src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$gProduct['thumbnail']}}"
                                            onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" alt=""
                                            loading="lazy" class="control-heightimg">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif


<!-- Top Deals of electronic appliances   -->
@if(isset($web_config['top_product_ids']) && !empty($web_config['top_product_ids']))
    <section class="productSection d-none">
        <div class="container">
            <h2>
                {{ $web_config['app_home']['top_deal_product']['title'] ?? ""}}
            </h2>
            <div class="row">
                @foreach($web_config['top_product_ids'] as $topKey => $topProduct)
                    @if($topKey < 4)
                        <div class="col-lg-3 col-md-4 mb-3 col-6" data-aos="flip-left">
                            <div class="productCard">
                                <!-- <a class="productCardImg" href="{{ route('product', $topProduct->slug) }}"> -->
                                <a class="productCardImg">
                                        <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$topProduct['thumbnail']}}" onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" alt="" loading="lazy" class="control-heightimg">
                                        <div class="productHead">
                                            <h6>{{ $topProduct->name }}</h6>
                                            <label for="">Deal Price</label>
                                            <div class="priceClass">
                                            <span>{{ \App\CPU\Helpers::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($topProduct->unit_price)) }}/-</span>
                                            <span class="lableSpan">Min QTY: 1</span>
                                        </div>
                                        <div class="productBtn">
                                            <button><img src="{{ theme_asset('assets/images/add.png')}}" alt="">Add</button>
                                            <button data-bs-toggle="modal" data-bs-target="#productCard"><img src="{{ theme_asset('assets/images/noteIcon.png')}}" alt=""></button>
                                        </div>
                                        </div>
                                    </a>
                            </div>  
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif


<div class="modal fade productCardModal" id="productCard" tabindex="-1" aria-labelledby="productCardLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6>Now request bulk quote from your seller</h6>
        <button type="button" data-bs-dismiss="modal" aria-label="Close"><img src="{{ theme_asset('assets/images/cancel.png')}}" alt=""></button>
      </div>
      <div class="productModal">
            <li>Submit your Bulk Quote request.</li>
            <li>The seller will review and respond with a special price.</li>
            <li>You'll get a notification in the app and on WhatsApp.</li>
            <li>Once approved, place your order at the quoted price.</li>
             <div class="reqbtndiv">
      <button class="reqbtn" href="#">Request</button>
      </div>
      </div>
     
    </div>
  </div>
</div>


<!-- <section class="pageSection">
    <div class="container">
        <div class="downloadContainer">
            <div class="col-md-4">
                <img src="{{ theme_asset('assets/images/dashboard.png')}}" alt="">
            </div>
            <div class="col-md-8"> 
                <img class="downloadLogo" src="{{ theme_asset('assets/images/primeLogo.png')}}" alt="">
                <h3>Download our App now!</h3>
                <p>We’re available on Android devices and platforms.<br>Shop from anywhere at your convenience.</p>
                <div class="dounloadBtn">
                <a href="">
                                <img src="{{ theme_asset('assets/images/google-play.svg')}}" alt=""></a>
                            <a href="">
                                <img src="{{ theme_asset('assets/images/app-store.svg')}}" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</section> -->


<img class="shapeThree" src="{{ theme_asset('assets/images/shapeOne.png')}}" alt="">
<section class="download-ecommerce-app">
    <div class="container">
        <div class="col-md-10 mx-auto">
            <div class="row">
                <div class="col-md-4" data-aos="fade-up-left">
                    <img style="width: 100%;" src="{{ theme_asset('assets/images/mobileFrame12.png')}}" alt="" class="mobile-app-image-fix">
                </div>
                
                <div class="col-md-8" data-aos="fade-down-right">
                    <div class="download-app-content">
                        <h4><span>Shop Anytime <br> Download our Application now!</span></h4>
                        
                        <p>We’re available on Android devices and platforms.<br>
                        Shop from anywhere at your convenience.</p>
                        <div class="btn-doanload-app">
                            <a href="" class="btn-app-download">
                                <img src="{{ theme_asset('assets/images/google-play.svg')}}" alt=""></a>
                                <a href="" class="btn-app-download">
                                    <img src="{{ theme_asset('assets/images/app-store.svg')}}" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <img class="shapeFour" src="{{ theme_asset('assets/images/shapeOne.png')}}" alt="">


<!-- OFFER SECTION -->
<section class="offer-section d-none py-3" data-aos="fade-up" data-aos-duration="3000">
    <div class="container">
        <div class="col-lg-10 col-md-11 mx-auto">
            <div class="row justify-content-center">
                <div class="col-md-4  my-2">
                    <div class="d-flex align-items-center">
                        <div class="poster-icon">
                            <img src="{{ theme_asset('assets/images/orginal-100.svg') }}" alt="" class="me-3">
                        </div>

                        <div class="poster-content">
                            <h4>{{ $web_config['app_home']['frame_one']['label_1'] ?? "" }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 ">
                    <div class="d-flex align-items-center my-2">
                        <div class="poster-icon">
                            <img src="{{ theme_asset('assets/images/free-sipping.svg') }}" alt="" class="me-3">
                        </div>

                        <div class="poster-content">
                            <h4>{{ $web_config['app_home']['frame_one']['label_2'] ?? "" }}</h4>
                        </div>
                    </div>
                </div>



                <div class="col-md-4 ">
                    <div class="d-flex align-items-center my-2">
                        <div class="poster-icon">
                            <img src="{{ theme_asset('assets/images/100to.svg') }}" alt="" class="me-3">
                        </div>

                        <div class="poster-content">
                            <h4>{{ $web_config['app_home']['frame_one']['label_3'] ?? "" }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('script')

<script>
    cartQuantityInitialize();

    function set_shipping_id(id, cart_group_id) {
        $.get({
            url: '{{url('/')}}/customer/set-shipping-method',
            dataType: 'json',
            data: {
                id: id,
                cart_group_id: cart_group_id
            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                location.reload();
            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }

    function updateCartQuantityList(minimum_order_qty, key, incr, e) {
        let quantity = parseInt($("#cartQuantity" + key).val())+parseInt(incr);
        let ex_quantity = $("#cartQuantity" + key);
        if(minimum_order_qty > quantity && e != 'delete' ) {
            swal.fire('{{translate("minimum_order_quantity_cannot_be_less_than_")}}' + minimum_order_qty, '', 'error')
            $("#cartQuantity" + key).val(minimum_order_qty);
            return false;
        }

        if (ex_quantity.val() == ex_quantity.data('min') && e == 'delete') {
            $.post("{{ route('cart.remove') }}", {
                _token: '{{ csrf_token() }}',
                key: key
            },
            function (response) {
                updateNavCart();
                swal.fire("{{translate('Item has been removed from cart ')}}", '', 'info')
                
                let segment_array = window.location.pathname.split('/');
                let segment = segment_array[segment_array.length - 1];
                location.reload();
            });
        }else{
            $.post('{{route('cart.updateQuantity')}}', {
                _token: '{{csrf_token()}}',
                key,
                quantity
            }, function (response) {
                if (response.status == 0) {

                    swal.fire(`${response.message}`, '', 'error')

                    // toastr.error(response.message, {
                    //     CloseButton: true,
                    //     ProgressBar: true
                    // });
                    $("#cartQuantity" + key).val(response['qty']);
                } else {
                    if (response['qty'] == ex_quantity.data('min')) {
                        ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-trash3-fill text-danger fs-10"></i>')
                    } else {
                        ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-dash"></i>')
                    }
                    updateNavCart();
                    console.log('response ', response)
                    $('#cart-summary').empty().html(response);
                }
            });
        }
    }

    function checkout() {
        let order_note = $('#order_note').val();
        //console.log(order_note);
        $.post({
            url: "{{route('order_note')}}",
            data: {
                _token: '{{csrf_token()}}',
                order_note: order_note,

            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                let url = "{{ route('checkout-details') }}";
                location.href = url;

            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }
</script>
@endpush
