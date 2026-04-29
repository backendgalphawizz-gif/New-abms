
@extends('theme-views.layouts.app')

@section('title', translate($product['name']))


@push('css_or_js')

<meta name="description" content="{{$product->slug}}">
<meta name="keywords" content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
@if($product->added_by=='seller')
<meta name="author" content="{{ $product->seller->shop?$product->seller->shop->name:$product->seller->f_name}}">
@elseif($product->added_by=='admin')
<meta name="author" content="{{$web_config['name']->value}}">
@endif
<!-- Viewport-->

@if($product['meta_image'])
<meta property="og:image" content="{{asset("storage/app/public/product/meta")}}/{{$product->meta_image}}" />
<meta property="twitter:card" content="{{asset("storage/app/public/product/meta")}}/{{$product->meta_image}}" />
@else
<meta property="og:image" content="{{asset("storage/app/public/product/thumbnail")}}/{{$product->thumbnail}}" />
<meta property="twitter:card" content="{{asset("storage/app/public/product/thumbnail/")}}/{{$product->thumbnail}}" />
@endif

@if($product['meta_title'])
<meta property="og:title" content="{{$product->meta_title}}" />
<meta property="twitter:title" content="{{$product->meta_title}}" />
@else
<meta property="og:title" content="{{$product->name}}" />
<meta property="twitter:title" content="{{$product->name}}" />
@endif
<meta property="og:url" content="{{route('product',[$product->slug])}}">

@if($product['meta_description'])
<meta property="twitter:description" content="{!! $product['meta_description'] !!}">
<meta property="og:description" content="{!! $product['meta_description'] !!}">
@else
<meta property="og:description"
    content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
<meta property="twitter:description"
    content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
@endif
<meta property="twitter:url" content="{{route('product',[$product->slug])}}">
<!-- <link rel="stylesheet" href="{{ theme_asset('assets/css/lightbox.min.css') }}"> -->
@endpush


@section('content')
<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 pt-3">
    <div class="">

        <section class="mt-4">
            <div class="container-fluid productcontent">
                <div class="row g-3">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="backto-button">
                                <h4><a href="{{ route('home') }}">{{translate('HOME')}}</a> /
                                    <a href="">{{ $product->categroy ? $product->categroy->name.'/' : ''}}</a>
                                    <a href="">{{ $product->sub_categroy ? $product->sub_categroy->name.'/' : ''}}</a>
                                    <a href="" class="active-color">{{$product->name}}</a>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 top-left-product-details">
                        <div class="share-likes-button">
                            <ul>@php
                                $wishlist=count($product->wish_list)>0?1:0;
                                @endphp
                                <li>

                                    <a onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}')"
                                        class="btn-wishlist stopPropagation add_to_wishlist cursor-pointer wishlist-{{$product['id']}} {{($wishlist == 1?'wishlist_icon_active':'')}}"
                                        title="{{translate('add_to_wishlist')}}">
                                        <i class="fa-solid fa-heart"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="top-sticky ">

                            <div class="details-img-full ">

                                <div class="pdp-image-gallery-block ">

                                    <!-- Gallery -->
                                    <div class="gallery_pdp_container">
                                        <div id="gallery_pdp">
                                            @php
                                            $photo1 = asset("storage/app/public/product/thumbnail/$product->thumbnail");
                                            @endphp
                                            <a href="#" data-image="{{$photo1}}" data-zoom-image="{{$photo1}}">
                                                <img id="" src="{{$photo1}}" width="90px" height="70px" style="border-radius: 0; margin:5px 0 ; border: 1px solid #f1f1f1; object-fit: contain;">
                                            </a>

                                            @if($product->images!="[null]" && $product->images!=null && json_decode($product->images)>0)
                                            @if(json_decode($product->colors) && $product->color_image)
                                            @foreach (json_decode($product->color_image) as $ckey => $photo)
                                            @php
                                            $photo = asset("storage/app/public/product/$photo->image_name")
                                            @endphp
                                            <a href="#" data-image="{{$photo}}" data-zoom-image="{{$photo}}">
                                                <img id="{{$ckey}}" src="{{$photo}}" width="90px" height="70px" style=" border-radius: 0; margin:5px 0 ;border: 1px solid #f1f1f1; object-fit: contain; ">
                                            </a>
                                            @endforeach
                                            @else
                                            @foreach (json_decode($product->images) as $key => $photo)
                                            @php
                                            $photo = asset("storage/app/public/product/$photo")
                                            @endphp
                                            <a href="#" data-image="{{$photo}}" data-zoom-image="{{$photo}}">
                                                <img id="{{$key}}" src="{{$photo}}" width="90px" height="70px" style=" border-radius: 0; margin:5px 0 ; border: 1px solid #f1f1f1; object-fit: contain;">
                                            </a>
                                            @endforeach
                                            @endif
                                            @endif

                                        </div>
                                        <!-- Up and down button for vertical carousel -->
                                        <a href="#" id="ui-carousel-next" style="display: inline;"><i class="fa-solid fa-chevron-down"></i> </a>
                                        <a href="#" id="ui-carousel-prev" style="display: inline;"><i class="fa-solid fa-chevron-up"></i></a>
                                    </div>

                                    <!-- Gallery -->
                                    <!-- gallery Viewer -->
                                    <div class="gallery-viewer">
                                        <img
                                            id="zoom_10" class="w-100"
                                            src="{{$photo1}}"
                                            data-zoom-image="{{$photo1}}"
                                            href="{{$photo1}}"
                                            max-width="100%"
                                            height="400px"
                                            style="object-fit: contain; ">

                                    </div>
                                </div>

                                <div class="">
                                    <div class="height-cudty d-none">
                                        <div class="container-carousel">
                                            <div class="pictures">

                                                @php
                                                $photo1 = asset("storage/app/public/product/thumbnail/$product->thumbnail");
                                                @endphp
                                                <img id="pic1254" onmouseover="changeImage('{{ $photo1 }}', 1)" src="{{ $photo1 }}"
                                                    class="w-100 pic" onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">

                                                @if($product->images!="[null]" && $product->images!=null && json_decode($product->images)>0)
                                                @if(json_decode($product->colors) && $product->color_image)
                                                @foreach (json_decode($product->color_image) as $key => $photo)
                                                @php
                                                $photo = asset("storage/app/public/product/$photo->image_name")
                                                @endphp
                                                <img id="pic{{$key}}" onmouseover="changeImage('{{ $photo }}', 1)" src="{{ $photo }}"
                                                    class="w-100 pic" onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                @endforeach
                                                @else
                                                @foreach (json_decode($product->images) as $key => $photo)
                                                @php
                                                $photo = asset("storage/app/public/product/$photo")
                                                @endphp
                                                <img id="pic{{$key}}" onmouseover="changeImage('{{ $photo }}', 1)" src="{{ $photo }}"
                                                    class="w-100 pic" onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                @endforeach
                                                @endif
                                                @endif
                                            </div>
                                            <div class="picture" id="picture">
                                                <div class="rect" id="rect"></div>
                                                <img id="pic" src="{{ $photo1 }}" onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                            </div>
                                            <div class="zoom" id="zoom"></div>

                                        </div>
                                        <!-- <button class="btn" data-bs-toggle="modal" data-bs-target="#view360"
                                        data-bs-dismiss="modal"><img src="{{ theme_asset('
                                            images/brand-logo/360.png')}}" class="view360-button"></button> -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="detail-for-product">
                            <div class="share-likes-button">
                                <ul>
                                    <li>
                                        <a onclick="sharePage()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                                <path d="M18.6891 9.41066L12.0224 2.74399C11.9059 2.62748 11.7574 2.54814 11.5958 2.516C11.4342 2.48386 11.2666 2.50037 11.1144 2.56342C10.9621 2.62648 10.832 2.73327 10.7404 2.87027C10.6489 3.00728 10.6 3.16836 10.5999 3.33316V6.28732C8.32351 6.49798 6.20762 7.55054 4.66634 9.23901C3.12506 10.9275 2.26933 13.1303 2.2666 15.4165V16.6665C2.26673 16.8395 2.32069 17.0081 2.421 17.1491C2.5213 17.29 2.66298 17.3962 2.82637 17.453C2.98977 17.5098 3.16679 17.5143 3.33288 17.466C3.49896 17.4176 3.64589 17.3188 3.75327 17.1832C4.56968 16.2123 5.5714 15.4139 6.69989 14.8345C7.82838 14.2552 9.06101 13.9065 10.3258 13.809C10.3674 13.804 10.4716 13.7957 10.5999 13.7873V16.6665C10.6 16.8313 10.6489 16.9924 10.7404 17.1294C10.832 17.2664 10.9621 17.3732 11.1144 17.4362C11.2666 17.4993 11.4342 17.5158 11.5958 17.4836C11.7574 17.4515 11.9059 17.3722 12.0224 17.2557L18.6891 10.589C18.8453 10.4327 18.9331 10.2208 18.9331 9.99982C18.9331 9.77885 18.8453 9.56693 18.6891 9.41066ZM12.2666 14.6548V12.9165C12.2666 12.6955 12.1788 12.4835 12.0225 12.3272C11.8662 12.171 11.6543 12.0832 11.4333 12.0832C11.2208 12.0832 10.3533 12.1248 10.1316 12.154C7.88565 12.3609 5.74778 13.2142 3.9766 14.6107C4.17764 12.7727 5.04955 11.0734 6.4254 9.83829C7.80124 8.60313 9.58433 7.91886 11.4333 7.91649C11.6543 7.91649 11.8662 7.82869 12.0225 7.67241C12.1788 7.51613 12.2666 7.30417 12.2666 7.08316V5.34482L16.9216 9.99982L12.2666 14.6548Z" fill="#040D12" />
                                            </svg>
                                        </a>
                                    </li>
                                    <!-- <li>  @php($wishlist=count($product->wish_list)>0 ? 1 : 0)
                                    <a onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}')"
                                        class="btn-wishlist stopPropagation add_to_wishlist cursor-pointer wishlist-{{$product['id']}} {{($wishlist == 1?'wishlist_icon_active':'')}}"
                                        title="{{translate('add_to_wishlist')}}">
                                        <i class="fa-solid fa-heart"></i>
                                    </a>
                                </li> -->
                                </ul>
                            </div>
                            <h5>{{$product->name}}</h5>
                            <div class="d-flex align-items-center mt-3">
                                <div class="icon-andstar reviews">
                                    <!-- <img src="{{ theme_asset('assets/images/product-detail-images/icon-rate-top.png')}}" alt=""> -->
                                    <p>
                                        <i class="fas fa-star {{ $overallRating[0] >= 1 ? 'fill' : ''}}"></i>
                                        <i class="fas fa-star {{ $overallRating[0] >= 2 ? 'fill' : ''}}"></i>
                                        <i class="fas fa-star {{ $overallRating[0] >= 3 ? 'fill' : ''}}"></i>
                                        <i class="fas fa-star {{ $overallRating[0] >= 4 ? 'fill' : ''}}"></i>
                                        <i class="fas fa-star {{ $overallRating[0] >= 5 ? 'fill' : ''}}"></i>
                                        {{round($overallRating[0], 1)}}
                                    </p>
                                </div>
                                <div class="review-first-rate d-flex gap-2 align-items-center">
                                    <div class="">
                                        <span>({{$product->reviews_count}})</span>
                                    </div>
                                    <p>{{$overallRating[0]}} Ratings & {{$reviews_of_product->total()}} Reviews</p>
                                </div>
                            </div>
                            <!-- Add to Cart Form -->
                            <form class="cart add_to_cart_form" action="{{ route('cart.add') }}" id="add-to-cart-form"
                                data-redirecturl="{{route('checkout-details')}}"
                                data-varianturl="{{ route('cart.variant_price') }}"
                                data-errormessage="{{translate('please_choose_all_the_options')}}"
                                data-outofstock="{{translate('Sorry').', '.translate('Out_of_stock')}}.">
                                @csrf
                                <div class="">
                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                    <div class="d-flex  justify-content-between mt-3">
                                        <div class="">
                                            <div class="prize-and-rate-reward">
                                                <h4 class="dollar static_price">
                                                    {{\App\CPU\Helpers::get_price_range($product) }}
                                                </h4>
                                                <span>
                                                    <s class="unit_price">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</s></span>
                                                <p class="org-off-color">
                                                    <span>
                                                        @if ($product->discount > 0 && $product->discount_type ===
                                                        "percent")
                                                        {{$product->discount}}%</span>
                                                    <span>
                                                        @elseif($product->discount > 0)
                                                        {{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                                    @endif off
                                                </p>

                                                <div class="mx-w d-none">
                                                    <div class="bg-light w-100 rounded p-4">
                                                        <div class="flex-between-gap-3">
                                                            <div class="">
                                                                <h6 class="flex-middle-gap-2 mb-2">
                                                                    <span
                                                                        class="text-muted">{{translate('total_price')}}:</span>
                                                                    <span
                                                                        class="total_price">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</span>
                                                                </h6>
                                                                <h6 class="flex-middle-gap-2">
                                                                    <span
                                                                        class="text-muted">{{translate('Tax')}}:</span>
                                                                    <span
                                                                        class="product_vat">{{ $product->tax_model == 'include' ? 'incl.' : \App\CPU\Helpers::currency_converter($product->tax)}}</span>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center pay-and-reward ps-md-4 d-none">
                                                    <p>or Pay $100 + </p>
                                                    <p><img src="{{ theme_asset('assets/images/product-detail-images/reward-icon.svg') }}" alt="">20
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if (count(json_decode($product->colors)) > 0)
                                    <div class="d-flex justify-content-between flex-wrap  mt-3">
                                        <div class="color-select">
                                            <label for="" class="labelfor-product">{{translate('color')}}</label>
                                            <div
                                                class="option-select-btn custom_01_option d-flex align-items-center mt-2 pt-1">


                                                @foreach (json_decode($product->colors) as $key => $color)
                                                <label>
                                                    <input type="radio" hidden=""
                                                        id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                        name="color" value="{{ $color }}"
                                                        {{ $key == 0 ? 'checked' : '' }}>
                                                    <span class="circle-50 d-block brown-bg-circle"
                                                        style="background: {{ $color }};"></span>
                                                </label>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <!--  -->
                                    @foreach (json_decode($product->choice_options) as $key => $choice)
                                    <div class="d-flex gap-4 flex-wrap align-items-center mt-3">
                                        <h6 class="fw-semibold">{{translate($choice->title)}}</h6>
                                        <div class="option-select-btn custom_01_option display-size-btn gap-3">
                                            @foreach ($choice->options as $key => $option)
                                            <label>
                                                <input type="radio" hidden="" id="{{ $choice->name }}-{{ $option }}"
                                                    name="{{ $choice->name }}" value="{{ $option }}" @if($key==0)
                                                    checked @endif>
                                                <span class=" size-btnbg d-block ">{{$option}}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="qunty mt-3">
                                        <div class="quantity quantity--style-two d-inline-flex">
                                            <span class="quantity__minus ">
                                                <i class="bi bi-dash"></i>
                                            </span>
                                            <input type="text" class="quantity__qty" value="1" name="quantity" id="cartQuantity117" onchange="updateCartQuantityList('1', '117', '0')" data-min="1">
                                            <span class="quantity__plus">
                                                <i class="bi bi-plus"></i>
                                            </span>
                                        </div>

                                        <div class="btn-group">
                                        <div class="mx-auto d-flex flex-wrap all__btn__gap" style="--width: 24rem">
                                            @if(($product->added_by == 'seller' && ($seller_temporary_close ||
                                            (isset($product->seller->shop) && $product->seller->shop->vacation_status &&
                                            $current_date >= $seller_vacation_start_date && $current_date <=
                                                $seller_vacation_end_date))) || ($product->added_by == 'admin' &&
                                                ($inhouse_temporary_close || ($inhouse_vacation_status && $current_date
                                                >= $inhouse_vacation_start_date && $current_date <=
                                                    $inhouse_vacation_end_date))))
                                                    <button type="button" class="buy_now_button btn btn-secondary fs-16 flex-grow-1" disabled>
                                                    <span> {{translate('buy_now')}}</span>
                                                    </button>
                                                    <button type="button"
                                                        class="update_cart_button btn btn-primary fs-16 flex-grow-1"
                                                        disabled><span>{{translate('add_to_Cart')}}</span>
                                                    </button>
                                                    @else
                                                    <button type="button" class="btn-buynow-cardproduct ms-0"
                                                        onclick="buy_now('add-to-cart-form', {{(Auth::guard('customer')->check()?'true':'false')}}, '{{route('buy-cart', ['is_cart' => 0])}}')">
                                                        <span>{{translate('buy_now')}}</span>
                                                    </button>

                                                    <button type="button"
                                                        class="btn-add-to-cardproduct"
                                                        onclick="addToCart('add-to-cart-form')">{{translate('add_to_Cart')}}
                                                    </button>

                                                    <button type="button"
                                                        class="btn-add-to-cardproduct save-later-product"
                                                        data-productid="{{ $product->id }}">{{translate('Save For Later')}}
                                                    </button>
                                                    @endif
                                        </div>
                                    </div>
                                </div>
                               <div class="totalPrice">
                                   <span>Total Price</span>
                               <h4 class="dollar total_price">
                                                {{\App\CPU\Helpers::get_price_range($product) }}
                                            </h4>
                                            <!-- <img src="{{ theme_asset('assets/images/arrow-right.png')}}" alt=""> -->
                               </div>

                                    <div class="col-md-7 p-0 my-3">
                                        <div class="deal-card d-flex justify-content-between align-items-center p-3">
                                            <div>
                                                <span class="badge-deal">Daily Deal</span>
                                                <p class="mb-1 mt-2">5-10 Bag</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="mb-0 price">
                                                    ₹2,195.00 <span class="discount">15.6% OFF</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7 p-0 my-3">
                                        <div class="deal-card d-flex justify-content-between align-items-center p-3">
                                            <div>
                                                <span class="badge-deal">Daily Deal</span>
                                                <p class="mb-1 mt-2">5-10 Bag</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="mb-0 price">
                                                    ₹2,195.00 <span class="discount">15.6% OFF</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                  
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="use-delevery mt-4 pe-2">
                            <h3 class="d-none">
                                <b>{{translate('Use pincode to check delivery info')}} </b>
                            </h3>
                            <form class="d-flex mt-3 d-none">
                                <input class="form-control me-3" type="search" placeholder="Enter Delivery Pincode"
                                    aria-label="Search">
                                <button class="btn btn-primary px-4" type="submit">Check</button>
                            </form>
                            <div class="deliverys-day mt-3 d-none">
                                <p class="fw-16"><b>Delivery by25 Nov, Saturday</b></p>
                                <span>
                                    <div class="fw-500 fs-16">if ordered before 3:37 PM</div>
                                </span>
                            </div>
                            <div class="customPageCard">
                                <div class=" d-flex justify-content-between align-items-center align-items-center">
                                    <div class="mart-sunshine">
                                        <div class="d-flex gap-2 align-items-center mart-sunshine-1r">
                                            <img src="{{ asset('storage/app/public/shop/' . $product->seller->shop->image) }}"
                                                alt=""
                                                class="dark-support img-fit rounded-circle w-25"
                                                width="25%"
                                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                            <h4>{{ $product->seller->shop->name ?? "" }}</h4>
                                        </div>
                                    </div>
                                    <div class=" d-flex justify-content-between align-items-center align-items-center">
                                        <div class="mart-sunshine">
                                            <div class="rating-point">
                                                @php($product_ids=$product->seller->shop->product()->pluck('id'))
                                                @php($review_data = \App\Model\Review::whereIn('product_id', $product_ids)->where('status',1))
                                                @php($avg_rating = $review_data->avg('rating'))
                                                @php($total_review = $review_data->count())
                                                <span> <i class="fa-solid fa-star"></i> {{ round($avg_rating ?? 0, 1) }}</span>
                                                <p> {{translate('Rating')}}</p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="warranty-brand-policy d-none">
                                    <a href="" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalfree">
                                        <div class="free-delevery">
                                            <img src="{{ theme_asset('assets/images/product-detail-images/free-delevery-icon.svg')}}" alt="">
                                            <p>Free Delivery</p>
                                        </div>
                                    </a>
                                    <div class="line-brk"></div>
                                    <a href="" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalReturnable">
                                        <div class="free-delevery">
                                            <img src="{{ theme_asset('assets/images/product-detail-images/non-retrun.svg')}}" alt="">
                                            <p>Non - Returnable</p>
                                        </div>
                                    </a>
                                    <div class="line-brk"></div>
                                    <a href="" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalPolicy">
                                        <div class="free-delevery">
                                            <img src="{{ theme_asset('assets/images/product-detail-images/warranty-icon.svg')}}" alt="">
                                            <p>Warranty Policy</p>
                                        </div>
                                    </a>
                                    <div class="line-brk"></div>
                                    <a href="" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalBrand">
                                        <div class="free-delevery">
                                            <img src=" {{ theme_asset('assets/images/product-detail-images/top-brand-icon.svg')}}" alt="">
                                            <p>Top Brand</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="Specification">
                                <div class="customPageCard">
                                    <h3 class="ganral__heading">{{translate('Review')}}</h3>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="rate-number w-30">

                                            <h4>{{round($overallRating[0], 1)}}</h4>
                                            <p>{{$overallRating[0]}} Ratings & <br> {{$reviews_of_product->total()}} Reviews</p>
                                        </div>

                                        <div class="div-review-part-line">

                                        </div>

                                        <div class="w-50">

                                            <div class="d-flex align-items-center w-100 mb-4 gap-2">

                                                <div class="number">5</div>

                                                <div class="w-100">
                                                    <div class="progress" role="progressbar"
                                                        aria-label="Success example" aria-valuenow="25"
                                                        aria-valuemin="0" aria-valuemax="100" style="height: 5px;">
                                                        <div class="progress-bar"
                                                            style="width: {{($rating[0] != 0?number_format($rating[0]*100 / array_sum($rating)):0)}}%; height: 5px; background-color: #0A9494;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="public-number">
                                                    {{$rating[0]}}
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-center w-100 mb-4 gap-2">

                                                <div class="number">4</div>

                                                <div class="w-100">
                                                    <div class="progress" role="progressbar"
                                                        aria-label="Success example" aria-valuenow="25"
                                                        aria-valuemin="0" aria-valuemax="100" style="height: 5px;">
                                                        <div class="progress-bar"
                                                            style="width: {{($rating[1] != 0?number_format($rating[1]*100 / array_sum($rating)):0)}}%; height: 5px; background-color: #0A9494;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="public-number">
                                                    {{$rating[1]}}
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center w-100 mb-4 gap-2">

                                                <div class="number">3</div>

                                                <div class="w-100">
                                                    <div class="progress" role="progressbar"
                                                        aria-label="Success example" aria-valuenow="25"
                                                        aria-valuemin="0" aria-valuemax="100" style="height: 5px;">
                                                        <div class="progress-bar"
                                                            style="width: {{($rating[2] != 0?number_format($rating[2]*100 / array_sum($rating)):0)}}%; height: 5px; background-color: #37D747;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="public-number">
                                                    {{$rating[2]}}
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center w-100 mb-4 gap-2">

                                                <div class="number">2</div>

                                                <div class="w-100">
                                                    <div class="progress" role="progressbar"
                                                        aria-label="Success example" aria-valuenow="25"
                                                        aria-valuemin="0" aria-valuemax="100" style="height: 5px;">
                                                        <div class="progress-bar"
                                                            style="width: {{($rating[3] != 0?number_format($rating[3]*100 / array_sum($rating)):0)}}%; height: 5px; background-color: #FFA412;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="public-number">
                                                    {{$rating[3]}}
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center w-100 mb-4 gap-2">

                                                <div class="number">1</div>

                                                <div class="w-100">
                                                    <div class="progress" role="progressbar"
                                                        aria-label="Success example" aria-valuenow="25"
                                                        aria-valuemin="0" aria-valuemax="100" style="height: 5px;">
                                                        <div class="progress-bar"
                                                            style="width: {{($rating[4] != 0?number_format($rating[4]*100 / array_sum($rating)):0)}}%; height: 5px; background-color: #E53950;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="public-number">
                                                    {{$rating[4]}}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="customPageCard testimonial__review__allcontrol">
                                    <div class="card-body p-0">
                                        <div class="testimonialContainer">
                                            @if(isset($product->reviews) && !empty($product->reviews))
                                            @foreach($product->reviews as $review)
                                            <div class="testimonial-box">
                                                <div class="box-top">
                                                    <div class="profile">
                                                        <div class="profile-img">
                                                            <img src="{{ asset('storage/app/public/shop/' . $product->seller->shop->image) }}"
                                                                alt=""
                                                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'" style="width: 60px !important;">
                                                        </div>
                                                        <p>{{ $review->comment }}</p>
                                                        <div class="name-user">
                                                            <strong>{{ $review->customer->f_name ?? ""}}</strong>
                                                            <div class="reviews">
                                                                <i class="fas fa-star {{ $review->rating >= 1 ? 'fill' : '' }}"></i>
                                                                <i class="fas fa-star {{ $review->rating >= 2 ? 'fill' : '' }}"></i>
                                                                <i class="fas fa-star {{ $review->rating >= 3 ? 'fill' : '' }}"></i>
                                                                <i class="fas fa-star {{ $review->rating >= 4 ? 'fill' : '' }}"></i>
                                                                <i class="fas fa-star {{ $review->rating >= 5 ? 'fill' : '' }}"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="products-comments-img" id="gallery">
                                                            @foreach(json_decode($review->attachment) as $img)
                                                            @if(file_exists(base_path("storage/app/public/review/".$img)))
                                                                <a href="{{ asset("storage/app/public/review/".$img) }}" data-lightbox="gallery" class="product__review__set">
                                                                    <img src="{{ asset("storage/app/public/review/".$img) }}"
                                                                        class="remove-mask-img"
                                                                        onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'">
                                                                </a>
                                                            @endif
                                                            @endforeach
                                                     
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="describtion mb-4">
                            <h3 class="ganral__heading"> Product Description </h3>
                            {!! $product->details !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recommended Section -->
        <section class="">
            <div class="container-fluid productcontent">
                <h4 class="heading-tore"><b>{{translate('Recommended Accessories')}}</b></h4>
                <div class="owl-carousel cart-item-carousel owl-theme">

                    @if (count($more_product_from_seller)>0)
                    @foreach($more_product_from_seller as $key => $item)

                    <div class="item" onclick="window.location.href={{route('product',$item->slug)}}">
                        <div class="hero-banner-images">
                            <div class="card view-card-item-white-theme">
                                <a href="{{route('product',$item->slug)}}">
                                    <div class="images-item-white-theme">
                                        <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$item['thumbnail']}}"
                                            alt="" class="img-fit dark-support rounded img-fluid overflow-hidden"
                                            onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                    </div>
                                </a>
                                <div class="item-detail-cart">
                                    <div
                                        class="d-flex justify-content-between  align-items-center cartlike-height-moblie">

                                        <!-- <div class="item-offspan">
                                            <span><i class="fa-solid fa-star"></i>
                                                <p class="black-text mb-0">({{$item->reviews_count}})</p>
                                            </span>
                                        </div> -->
                                        <div class="item-like likeitem">
                                            @php($wishlist=count($item->wish_list)>0 ? 1 : 0)
                                            <a onclick="addWishlist('{{$item['id']}}','{{route('store-wishlist')}}')"
                                                class="btn-wishlist stopPropagation add_to_wishlist cursor-pointer wishlist-{{$item['id']}} {{($wishlist == 1?'wishlist_icon_active':'')}}"
                                                title="{{translate('add_to_wishlist')}}">
                                                <i class="fa-solid fa-heart"></i>
                                            </a>
                                        </div>

                                        <div class="item-offspan itemdiscount">
                                            @if($product->discount > 0)
                                            <span>
                                                @if ($product->discount_type == 'percent') {{round($product->discount, (!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}% @elseif($product->discount_type =='flat')
                                                {{\App\CPU\Helpers::currency_converter($product->discount)}} @endif off
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="content-item-cart">
                                        <p class="name-item black-text">{{ Str::limit($item['name'], 18) }}</p>
                                        <label for="" class="dealpricediv">MRP price</label>
                                        <p class="item-bill itempricediv"><span class="pricediv">{{\App\CPU\Helpers::currency_converter(
                                                        $item->unit_price-(\App\CPU\Helpers::get_product_discount($item,$item->unit_price))
                                                    )}} </span>
                                            <s>{{ \App\CPU\Helpers::currency_converter($item->unit_price) }} </s>
                                        </p>
                                        <a href="" type="button" class="btn-add-item white-theme-btn d-none"> <img
                                                src="{{ theme_asset('assets/images/product-detail-images/Buyblack.svg')}}" alt=""> ADD</a>
                                        <div class="productBtn">
                                            <button class="" href="{{ route('product', $product->slug) }}"><img src="{{ theme_asset('assets/images/add.png')}}" alt="">
                                                Add
                                            </button>
                                            <button data-bs-toggle="modal" data-bs-target="#productCard"><img src="{{ theme_asset('assets/images/noteIcon.png')}}" alt=""></button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        {{-- <div class="card border-primary-light flex-grow-1 d-none">
                                    <a href="{{route('product',$item->slug)}}"
                        class="media align-items-centr gap-3 p-3">
                        <div class="avatar" style="--size: 4.375rem">
                            <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$item['thumbnail']}}"
                                alt="" class="img-fit dark-support rounded img-fluid overflow-hidden"
                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                        </div>
                        @php($item_review = \App\CPU\ProductManager::get_overall_rating($item->reviews))

                        <div class="media-body d-flex flex-column gap-2">
                            <h6 class="text-capitalize">{{ Str::limit($item['name'], 18) }}</h6>
                            <div class="d-flex gap-2 align-items-center">
                                <div class="star-rating text-gold fs-12">
                                    @for ($i = 1; $i <= 5; $i++) @if ($i <=(int)$item_review[0]) <i
                                        class="bi bi-star-fill"></i>
                                        @elseif ($item_review[0] != 0 && $i <= (int)$item_review[0] + 1.1 &&
                                            $item_review[0]> ((int)$item_review[0]))
                                            <i class="bi bi-star-half"></i>
                                            @else
                                            <i class="bi bi-star"></i>
                                            @endif
                                            @endfor
                                </div>
                                <span>({{$item->reviews_count}})</span>
                            </div>
                            <div class="product__price">
                                <ins class="product__new-price">
                                    {{\App\CPU\Helpers::currency_converter(
                                                        $item->unit_price-(\App\CPU\Helpers::get_product_discount($item,$item->unit_price))
                                                    )}}
                                </ins>
                            </div>
                        </div>
                        </a>
                    </div> --}}
                </div>

                @endforeach
                @else
                <div class="item">
                    <div class="card border-primary-light flex-grow-1">
                        <a href="javaScript:void(0)" class="media align-items-centr gap-3 p-3">
                            <div class="media-body d-flex flex-column gap-2">
                                <h6>{{translate('similar_product_not_available')}}</h6>
                            </div>
                        </a>
                    </div>
                </div>

                @endif



            </div>
    </div>
    </section>
    <!-- Recommended Section -->

    <div class="row gx-3 gy-4 d-none">
        <div class="col-lg-8 col-xl-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="quickview-content">
                        <div class="row gy-4">
                            <div class="col-lg-5">
                                <!-- Product Details Image Wrap -->
                                <div class="pd-img-wrap position-relative h-100">
                                    <div class="swiper-container quickviewSlider2 border rounded"
                                        style="--bs-border-color: #d6d6d6">
                                        <div class="product__actions d-flex flex-column gap-2">
                                            <a onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}')"
                                                id="wishlist-{{$product['id']}}"
                                                class="btn-wishlist add_to_wishlist cursor-pointer wishlist-{{$product['id']}} {{($wishlist_status == 1?'wishlist_icon_active':'')}}"
                                                title="{{translate('add_to_wishlist')}}">
                                                <i class="bi bi-heart"></i>
                                            </a>
                                            <div class="product-share-icons">
                                                <a href="javascript:" title="Share">
                                                    <i class="bi bi-share-fill"></i>
                                                </a>

                                                <ul>
                                                    <li>
                                                        <a href="javascript:"
                                                            onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'facebook.com/sharer/sharer.php?u='); return false;">
                                                            <i class="bi bi-facebook"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:"
                                                            onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'twitter.com/intent/tweet?text='); return false;">
                                                            <i class="bi bi-twitter"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:"
                                                            onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'linkedin.com/shareArticle?mini=true&url='); return false;">
                                                            <i class="bi bi-linkedin"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:"
                                                            onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'api.whatsapp.com/send?text='); return false;">
                                                            <i class="bi bi-whatsapp"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        @if($product->images!=null && json_decode($product->images)>0)
                                        <div class="swiper-wrapper">
                                            @if(json_decode($product->colors) && $product->color_image)
                                            @foreach (json_decode($product->color_image) as $key => $photo)
                                            @if($photo->color != null)
                                            <div class="swiper-slide position-relative">
                                                @if ($product->discount > 0 && $product->discount_type === "percent")
                                                <span class="product__discount-badge">-{{$product->discount}}%</span>
                                                @elseif($product->discount > 0)
                                                <span
                                                    class="product__discount-badge">-{{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                                @endif
                                                <img src="{{asset("storage/app/public/product/$photo->image_name")}}"
                                                    class="dark-support rounded" alt=""
                                                    onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                            </div>
                                            @else
                                            <div class="swiper-slide position-relative">
                                                @if ($product->discount > 0 && $product->discount_type === "percent")
                                                <span class="product__discount-badge">-{{$product->discount}}%</span>
                                                @elseif($product->discount > 0)
                                                <span
                                                    class="product__discount-badge">-{{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                                @endif
                                                <img src="{{asset("storage/app/public/product/$photo->image_name")}}"
                                                    class="dark-support rounded" alt=""
                                                    onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                            </div>
                                            @endif
                                            @endforeach
                                            @else
                                            @foreach (json_decode($product->images) as $key => $photo)
                                            <div class="swiper-slide position-relative">
                                                @if ($product->discount > 0 && $product->discount_type === "percent")
                                                <span class="product__discount-badge">-{{$product->discount}}%</span>
                                                @elseif($product->discount > 0)
                                                <span
                                                    class="product__discount-badge">-{{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                                @endif
                                                <img src="{{asset("storage/app/public/product/$photo")}}"
                                                    class="dark-support rounded" alt=""
                                                    onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                        @endif
                                    </div>

                                    <div class="mt-2">
                                        <div class="quickviewSliderThumb2 swiper-container position-relative ">
                                            @if($product->images!=null && json_decode($product->images)>0)
                                            <div class="swiper-wrapper auto-item-width justify-content-center"
                                                style="--width: 4rem; --bs-border-color: #d6d6d6">
                                                @if(json_decode($product->colors) && $product->color_image)
                                                @foreach (json_decode($product->color_image) as $key => $photo)
                                                @if($photo->color != null)
                                                <div class="swiper-slide" id="preview-img{{$key}}">
                                                    <img src="{{asset("storage/app/public/product/$photo->image_name")}}"
                                                        class="dark-support rounded border" alt="Product thumb"
                                                        onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                </div>
                                                @else
                                                <div class="swiper-slide">
                                                    <img src="{{asset("storage/app/public/product/$photo->image_name")}}"
                                                        class="dark-support rounded border" alt="Product thumb"
                                                        onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                </div>
                                                @endif
                                                @endforeach
                                                @else
                                                @foreach (json_decode($product->images) as $key => $photo)
                                                <div class="swiper-slide" id="preview-img{{$key}}">
                                                    <img src="{{asset("storage/app/public/product/$photo")}}"
                                                        class="dark-support rounded border" alt="Product thumb"
                                                        onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                            @endif

                                            <div class="swiper-button-next swiper-quickview-button-next"
                                                style="--size: 1.5rem"></div>
                                            <div class="swiper-button-prev swiper-quickview-button-prev"
                                                style="--size: 1.5rem"></div>
                                        </div>
                                    </div>

                                </div>
                                <!-- End Product Details Image Wrap -->
                            </div>

                            <div class="col-lg-7">
                                <!-- Product Details Content -->
                                <div class="product-details-content position-relative">

                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                        <h2 class="product_title">{{$product->name}}</h2>

                                        @if ($product->discount > 0 && $product->discount_type === "percent")
                                        <span class="product__save-amount">{{translate('save')}}
                                            {{$product->discount}}%</span>
                                        @elseif($product->discount > 0)
                                        <span class="product__save-amount">{{translate('save')}}
                                            {{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                        @endif

                                    </div>

                                    <div class="d-flex gap-2 align-items-center mb-2">
                                        <div class="star-rating text-gold fs-12">
                                            @for ($i = 1; $i <= 5; $i++) @if ($i <=(int)$overallRating[0]) <i
                                                class="bi bi-star-fill"></i>
                                                @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 &&
                                                    $overallRating[0]> ((int)$overallRating[0]))
                                                    <i class="bi bi-star-half"></i>
                                                    @else
                                                    <i class="bi bi-star"></i>
                                                    @endif
                                                    @endfor
                                        </div>
                                        <span>({{$product->reviews_count}})</span>
                                    </div>
                                    @if(($product['product_type'] == 'physical') && ($product['current_stock']<=0))
                                        <p class="fw-semibold text-muted">{{translate('out_of_stock')}}</p>
                                        @else
                                        @if($product['product_type'] == 'physical')
                                        <p class="fw-semibold text-muted"><span class="in_stock_status">{{$product->current_stock}}</span>
                                            {{translate('in_Stock')}}
                                        </p>
                                        @endif
                                        @endif

                                        <div class="product__price d-flex flex-wrap align-items-end gap-2 mb-4">
                                            <del
                                                class="product__old-price">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</del>
                                            <ins
                                                class="product__new-price">{{\App\CPU\Helpers::get_price_range($product) }}</ins>
                                        </div>

                                        <!-- Add to Cart Form -->
                                        <form class="cart " action="{{ route('cart.add') }}" id=""
                                            data-redirecturl="{{route('checkout-details')}}"
                                            data-varianturl="{{ route('cart.variant_price') }}"
                                            data-errormessage="{{translate('please_choose_all_the_options')}}"
                                            data-outofstock="{{translate('Sorry').', '.translate('Out_of_stock')}}.">
                                            @csrf
                                            <div class="">
                                                <input type="hidden" name="id" value="{{ $product->id }}">
                                                @if (count(json_decode($product->colors)) > 0)
                                                <div class="d-flex gap-4 flex-wrap align-items-center mb-3">
                                                    <h6 class="fw-semibold">{{translate('color')}}</h6>
                                                    <ul
                                                        class="option-select-btn custom_01_option flex-wrap weight-style--two gap-2 pt-2">
                                                        @foreach (json_decode($product->colors) as $key => $color)
                                                        <li>
                                                            <label>
                                                                <input type="radio" hidden=""
                                                                    id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                                    name="color" value="{{ $color }}"
                                                                    {{ $key == 0 ? 'checked' : '' }}>
                                                                <span
                                                                    class="color_variants rounded-circle p-0 {{ $key == 0 ? 'color_variant_active':''}}"
                                                                    style="background: {{ $color }};"
                                                                    for="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                                    onclick="focus_preview_image_by_color('{{ $key }}')"
                                                                    id="color_variants_{{ $key }}"></span>
                                                            </label>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                                <!--  -->
                                                @foreach (json_decode($product->choice_options) as $key => $choice)
                                                <div class="d-flex gap-4 flex-wrap align-items-center mb-4">
                                                    <h6 class="fw-semibold">{{translate($choice->title)}}</h6>
                                                    <ul
                                                        class="option-select-btn custom_01_option flex-wrap weight-style--two gap-2">
                                                        @foreach ($choice->options as $key => $option)
                                                        <li>
                                                            <label>
                                                                <input type="radio" hidden=""
                                                                    id="{{ $choice->name }}-{{ $option }}"
                                                                    name="{{ $choice->name }}" value="{{ $option }}"
                                                                    @if($key==0) checked @endif>
                                                                <span>{{$option}}</span>
                                                            </label>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endforeach
                                                <div class="d-flex gap-4 flex-wrap align-items-center mb-4">
                                                    <h6 class="fw-semibold">{{translate('quantity')}}</h6>

                                                    <div class="quantity quantity--style-two">
                                                        <span class="quantity__minus single_quantity__minus">
                                                            <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                                        </span>
                                                        <input type="text" class="quantity__qty product_quantity__qty"
                                                            name="quantity"
                                                            value="{{ $product->minimum_order_qty ?? 1 }}"
                                                            min="{{ $product->minimum_order_qty ?? 1 }}"
                                                            max="{{$product['product_type'] == 'physical' ? $product->current_stock : 100}}">
                                                        <span class="quantity__plus single_quantity__plus"
                                                            {{($product->current_stock == 1?'disabled':'')}}>
                                                            <i class="bi bi-plus"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="mx-w" style="--width: 24rem">
                                                    <div class="bg-light w-100 rounded p-4">
                                                        <div class="flex-between-gap-3">
                                                            <div class="">
                                                                <h6 class="flex-middle-gap-2 mb-2">
                                                                    <span
                                                                        class="text-muted">{{translate('total_price')}}:</span>
                                                                    <span
                                                                        class="total_price">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</span>
                                                                </h6>
                                                                <h6 class="flex-middle-gap-2">
                                                                    <span
                                                                        class="text-muted">{{translate('Tax')}}:</span>
                                                                    <span
                                                                        class="product_vat">{{ $product->tax_model == 'include' ? 'incl.' : \App\CPU\Helpers::currency_converter($product->tax)}}</span>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mx-w d-flex flex-wrap gap-3 mt-4" style="--width: 24rem">
                                                    @if(($product->added_by == 'seller' && ($seller_temporary_close ||
                                                    (isset($product->seller->shop) &&
                                                    $product->seller->shop->vacation_status && $current_date >=
                                                    $seller_vacation_start_date && $current_date <=
                                                        $seller_vacation_end_date))) || ($product->added_by == 'admin'
                                                        && ($inhouse_temporary_close || ($inhouse_vacation_status &&
                                                        $current_date >= $inhouse_vacation_start_date && $current_date
                                                        <= $inhouse_vacation_end_date)))) <button type="button"
                                                            class="buy_now_button btn btn-secondary fs-16 flex-grow-1"
                                                            disabled>{{translate('buy_now')}}</span></button>
                                                            <button type="button"
                                                                class="update_cart_button btn btn-primary fs-16 flex-grow-1"
                                                                disabled>{{translate('add_to_Cart')}}</button>
                                                            @else
                                                            <button type="button"
                                                                class="buy_now_button btn btn-secondary fs-16"
                                                                onclick="buy_now('add-to-cart-form', {{(Auth::guard('customer')->check()?'true':'false')}}, '{{route('checkout-details')}}')">{{translate('buy_now')}}</span></button>

                                                            <button type="button"
                                                                class="update_cart_button btn btn-primary fs-16"
                                                                onclick="addToCart('add-to-cart-form')">{{translate('add_to_Cart')}}</button>
                                                            @endif
                                                </div>
                                                @if(($product->added_by == 'seller' && ($seller_temporary_close ||
                                                (isset($product->seller->shop) &&
                                                $product->seller->shop->vacation_status && $current_date >=
                                                $seller_vacation_start_date && $current_date <=
                                                    $seller_vacation_end_date))) || ($product->added_by == 'admin' &&
                                                    ($inhouse_temporary_close || ($inhouse_vacation_status &&
                                                    $current_date >= $inhouse_vacation_start_date && $current_date <=
                                                        $inhouse_vacation_end_date)))) <div
                                                        class="alert alert-danger mt-3" role="alert">
                                                        {{translate('this_shop_is_temporary_closed_or_on_vacation')}}
                                                        .
                                                        {{translate('You_cannot_add_product_to_cart_from_this_shop_for_now')}}
                                            </div>
                                            @endif
                                </div>
                                </form>
                            </div>
                            <!-- End Product Details Content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <nav>
                    <div class="nav justify-content-center gap-4 nav--tabs" id="nav-tab" role="tablist">
                        <button class="active" id="product-details-tab" data-bs-toggle="tab"
                            data-bs-target="#product-details" type="button" role="tab" aria-controls="product-details"
                            aria-selected="true">{{translate('Product_Details')}}</button>
                        <button id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab"
                            aria-controls="reviews" aria-selected="false">{{translate("reviews")}}</button>
                    </div>
                </nav>
                <div class="tab-content mt-3" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="product-details" role="tabpanel"
                        aria-labelledby="product-details-tab" tabindex="0">
                        <div class="details-content-wrap custom-height ov-hidden show-more--content active">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">{{translate('details_Description')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                @if($product->video_url!=null)
                                                <div class="col-12 mb-4 text-center">
                                                    <iframe width="560" height="315" src="{{$product->video_url}}">
                                                    </iframe>
                                                </div>
                                                @endif
                                                {!! $product->details !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-2">
                            <button class="btn btn-outline-primary see-more-details">{{translate('See_More')}}</button>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
                        <div class="details-content-wrap custom-height ov-hidden show-more--content active">
                            <div class="row gy-4">
                                <div class="col-lg-5">
                                    <div class="rating-review mx-auto text-center mb-30">
                                        <h2 class="rating-review__title"><span
                                                class="rating-review__out-of">{{round($overallRating[0], 1)}}</span>/5
                                        </h2>
                                        <div class="rating text-gold mb-2">
                                            @for ($i = 1; $i <= 5; $i++) @if ($i <=(int)$overallRating[0]) <i
                                                class="bi bi-star-fill"></i>
                                                @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 &&
                                                    $overallRating[0]> ((int)$overallRating[0]))
                                                    <i class="bi bi-star-half"></i>
                                                    @else
                                                    <i class="bi bi-star"></i>
                                                    @endif
                                                    @endfor
                                        </div>
                                        <div class="rating-review__info">
                                            <span>{{$reviews_of_product->total()}} {{translate('ratings')}}</span>
                                        </div>
                                    </div>


                                    <ul class="list-rating gap-10">
                                        <li>
                                            <span class="review-name">5 {{translate('star')}}</span>

                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{($rating[0] != 0?number_format($rating[0]*100 / array_sum($rating)):0)}}%"
                                                    aria-valuenow="95" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="review-name">4 {{translate('star')}}</span>

                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{($rating[1] != 0?number_format($rating[1]*100 / array_sum($rating)):0)}}%"
                                                    aria-valuenow="35" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="review-name">3 {{translate('star')}}</span>

                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{($rating[2] != 0?number_format($rating[2]*100 / array_sum($rating)):0)}}%"
                                                    aria-valuenow="35" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="review-name">2 {{translate('star')}}</span>

                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{($rating[3] != 0?number_format($rating[3]*100 / array_sum($rating)):0)}}%"
                                                    aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="review-name">1 {{translate('star')}}</span>

                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{($rating[4] != 0?number_format($rating[4]*100 / array_sum($rating)):0)}}%"
                                                    aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-lg-7">
                                    <div class="d-flex flex-wrap gap-3" id="product-review-list">
                                        @foreach ($reviews_of_product as $item)
                                        <div class="card border-primary-light flex-grow-1">
                                            <div class="media flex-wrap align-items-centr gap-3 p-3">
                                                <div class="avatar overflow-hidden border rounded-circle"
                                                    style="--size: 3.437rem">
                                                    <img src="{{asset("storage/app/public/profile")}}/{{(isset($item->user)?$item->user->image:'')}}"
                                                        alt="" class="img-fit dark-support"
                                                        onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                </div>
                                                <div class="media-body d-flex flex-column gap-2">
                                                    <div
                                                        class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                {{isset($item->user)?$item->user->f_name:translate('User_Not_Exist')}}
                                                            </h6>
                                                            <div class="d-flex gap-2 align-items-center">
                                                                <div class="star-rating text-gold fs-12">
                                                                    @for ($inc=0; $inc < 5; $inc++) @if ($inc < $item->
                                                                        rating)
                                                                        <i class="bi bi-star-fill"></i>
                                                                        @else
                                                                        <i class="bi bi-star"></i>
                                                                        @endif
                                                                        @endfor
                                                                </div>
                                                                <span>({{$item->rating}}/5)</span>
                                                            </div>
                                                        </div>
                                                        <div>{{$item->updated_at->format("d M Y h:i:s A")}}</div>
                                                    </div>
                                                    <p>{{$item->comment}}</p>

                                                    <div class="d-flex flex-wrap gap-2 products-comments-img">
                                                        @foreach(json_decode($item->attachment) as $img)
                                                        @if(file_exists(base_path("storage/app/public/review/".$img)))
                                                        <a href="{{asset("storage/app/public/review/".$img)}}"
                                                            data-lightbox="">
                                                            <img src="{{asset("storage/app/public/review/".$img)}}"
                                                                class="remove-mask-img"
                                                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                        </a>
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach

                                        @if(count($product->reviews)==0)
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="text-danger text-center m-0">
                                                    {{translate('product_review_not_available')}}
                                                </h6>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-2">
                            @if(count($product->reviews) > 2)
                            <button class="btn btn-outline-primary see-more-details-review m-1 view_text"
                                onclick="seemore()" data-productid="{{$product->id}}"
                                data-routename="{{route('review-list-product')}}"
                                data-afterextend="{{translate('See_Less')}}" data-seemore="{{translate('See_More')}}"
                                data-onerror="{{translate('no_more_review_remain_to_load')}}">{{translate('See_More')}}</button>
                            @else
                            <button
                                class="btn btn-outline-primary see-more-details m-1">{{translate('see_More')}}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xl-3 d-flex flex-column gap-3">
        <div class="card order-1 order-sm-0">
            <div class="card-body">
                <h5 class="mb-3">{{translate('More_from_the_Store')}}</h5>

                <div class="d-flex flex-wrap gap-3">
                    @if (count($more_product_from_seller)>0)
                    @foreach($more_product_from_seller as $key => $item)
                    <div class="card border-primary-light flex-grow-1">
                        <a href="{{route('product',$item->slug)}}" class="media align-items-centr gap-3 p-3">
                            <div class="avatar" style="--size: 4.375rem">
                                <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$item['thumbnail']}}"
                                    alt="" class="img-fit dark-support rounded img-fluid overflow-hidden"
                                    onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                            </div>
                            @php($item_review = \App\CPU\ProductManager::get_overall_rating($item->reviews))

                            <div class="media-body d-flex flex-column gap-2">
                                <h6 class="text-capitalize">{{ Str::limit($item['name'], 18) }}</h6>
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="star-rating text-gold fs-12">
                                        @for ($i = 1; $i <= 5; $i++) @if ($i <=(int)$item_review[0]) <i
                                            class="bi bi-star-fill"></i>
                                            @elseif ($item_review[0] != 0 && $i <= (int)$item_review[0] + 1.1 &&
                                                $item_review[0]> ((int)$item_review[0]))
                                                <i class="bi bi-star-half"></i>
                                                @else
                                                <i class="bi bi-star"></i>
                                                @endif
                                                @endfor
                                    </div>
                                    <span>({{$item->reviews_count}})</span>
                                </div>
                                <div class="product__price">
                                    <ins class="product__new-price">
                                        {{\App\CPU\Helpers::currency_converter(
                                                                $item->unit_price-(\App\CPU\Helpers::get_product_discount($item,$item->unit_price))
                                                            )}}
                                    </ins>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    @else
                    <div class="card border-primary-light flex-grow-1">
                        <a href="javaScript:void(0)" class="media align-items-centr gap-3 p-3">
                            <div class="media-body d-flex flex-column gap-2">
                                <h6>{{translate('similar_product_not_available')}}</h6>
                            </div>
                        </a>
                    </div>

                    @endif
                </div>
            </div>
        </div>
        @if($product->added_by=='seller')
        @if(isset($product->seller->shop))
        <div class="card order-0 order-sm-1">
            <div class="card-body">
                <div class="p-2 overlay shop-bg-card"
                    data-bg-img="{{asset('storage/app/public/shop/banner')}}/{{$product->seller->shop->banner}}">
                    <div class="media flex-wrap gap-3 p-2">
                        <div class="avatar border rounded-circle" style="--size: 3.437rem">
                            <img src="{{asset('storage/app/public/shop')}}/{{$product->seller->shop->image}}" alt=""
                                class="img-fit dark-support rounded-circle"
                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                        </div>
                        <div class="media-body d-flex flex-column gap-2 text-absolute-whtie">
                            <div class="d-flex flex-column gap-1 justify-content-start">
                                <h5 class="">{{$product->seller->shop->name}}</h5>
                                <div class="d-flex gap-2 align-items-center ">
                                    <div class="star-rating text-gold fs-12">
                                        @for ($i = 1; $i <= 5; $i++) @if ($i <=(int)$avg_rating) <i
                                            class="bi bi-star-fill"></i>
                                            @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1.1 && $avg_rating>
                                                ((int)$avg_rating))
                                                <i class="bi bi-star-half"></i>
                                                @else
                                                <i class="bi bi-star"></i>
                                                @endif
                                                @endfor
                                    </div>
                                    <span class="">({{$total_reviews}})</span>
                                </div>
                                <h6 class="fw-semibold">{{$products_for_review->count()}} {{translate('products')}}</h6>
                            </div>

                            <div class="mb-3">
                                <div class="text-center d-inline-block">
                                    <h3 class="mb-1">{{round($rating_percentage)}}%</h3>
                                    <div class="fs-12">{{translate('positive_review')}}</div>
                                </div>
                            </div>
                        </div>
                        @if (auth('customer')->id() == '')
                        <div class="btn-circle chat-btn" style="--size: 2.5rem" data-bs-toggle="modal"
                            data-bs-target="#loginModal">
                            <i class="bi bi-chat-square-dots"></i>
                        </div>
                        @else
                        <div class="btn-circle chat-btn" style="--size: 2.5rem" data-bs-toggle="modal"
                            data-bs-target="#contact_sellerModal">
                            <i class="bi bi-chat-square-dots"></i>
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('shopView',[$product->seller->id]) }}"
                        class="btn btn-primary btn-block">{{translate('Visit_Store')}}</a>
                </div>
            </div>
        </div>

        @include('theme-views.layouts.partials.modal._chat-with-seller',['seller_id'=>$product->seller->id,'shop_id'=>$product->seller->shop->id])

        @endif
        @else
        <div class="card  order-0 order-sm-1">
            <div class="card-body">
                <div class="p-2 overlay shop-bg-card"
                    data-bg-img="{{asset("storage/app/public/shop/")}}/{{ \App\CPU\Helpers::get_business_settings('shop_banner') }}">
                    <div class="media flex-wrap gap-3 p-2">
                        <div class="avatar border rounded-circle" style="--size: 3.437rem">
                            <img src="{{asset("storage/app/public/company")}}/{{$web_config['fav_icon']->value}}" alt=""
                                class="img-fit dark-support rounded-circle"
                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                        </div>

                        <div class="media-body d-flex flex-column gap-2 text-absolute-whtie">
                            <div class="d-flex flex-column gap-1 justify-content-start">
                                <h5 class="">{{$web_config['name']->value}}</h5>
                                <div class="d-flex gap-2 align-items-center ">
                                    <div class="star-rating text-gold fs-12">
                                        @for ($i = 1; $i <= 5; $i++) @if ($i <=(int)$avg_rating) <i
                                            class="bi bi-star-fill"></i>
                                            @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1.1 && $avg_rating>
                                                ((int)$avg_rating))
                                                <i class="bi bi-star-half"></i>
                                                @else
                                                <i class="bi bi-star"></i>
                                                @endif
                                                @endfor
                                    </div>

                                    <span>({{$total_reviews}})</span>
                                </div>
                                <h6 class="fw-semibold">{{$products_for_review->count()}} {{translate('Products')}}</h6>

                                <div class="mb-3">
                                    <div class="text-center d-inline-block">
                                        <h3 class="mb-1">{{round($rating_percentage)}}
                                            %</h3>
                                        <div class="fs-12">{{translate('positive_review')}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('shopView',[0]) }}"
                        class="btn btn-primary btn-block">{{translate('Visit_Store')}}</a>
                </div>
            </div>
        </div>
        @endif
    </div>
    </div>

    <!-- Similar Products From Other Stores -->
    <div class="py-4 mt-3 d-none">
        <div class="d-flex justify-content-between gap-3 mb-4">
            <h2>{{translate('similar_Products_From_Other_Stores')}}</h2>
            <div class="swiper-nav d-flex gap-2 align-items-center">
                <div class="swiper-button-prev top-rated-nav-prev position-static rounded-10"></div>
                <div class="swiper-button-next top-rated-nav-next position-static rounded-10"></div>
            </div>
        </div>
        <div class="swiper-container">
            <!-- Swiper -->
            <div class="position-relative">
                <div class="swiper" data-swiper-loop="false" data-swiper-margin="20" data-swiper-autoplay="true"
                    data-swiper-pagination-el="null" data-swiper-navigation-next=".top-rated-nav-next"
                    data-swiper-navigation-prev=".top-rated-nav-prev"
                    data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "320": {"slidesPerView": "2"}, "992": {"slidesPerView": "3"}, "1200": {"slidesPerView": "4"}, "1400": {"slidesPerView": "5"}}'>
                    <div class="swiper-wrapper">
                        @if (count($relatedProducts)>0)
                        @foreach($relatedProducts as $key=>$product)
                        <div class="swiper-slide">
                            @include('theme-views.partials._similar-product-large-card',['product'=>$product])
                        </div>
                        @endforeach
                        @else
                        <div class="card w-100 px-3 py-4">
                            <h5 class="text-muted">{{translate('no_similar_products_found')}}.</h5>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


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


</main>

<!-- End Main Content -->
@endsection

@push('script')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper50 = new Swiper(".reviewSwiper", {
    slidesPerView: 2,
    spaceBetween: 10,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>

<script>
    $('.remove-mask-img').on('click', function() {
        $('.show-more--content').removeClass('active')
    })
</script>

<script>
    getVariantPrice();

    $(document).on('click', '.follow-vendor-by-user', function() {
        let shopid = $(this).data('vendor');
        $.ajax({
            type: "POST",
            url: "{{ route('shop_follow') }}",
            data: {
                shop_id: shopid,
                _token: $('meta[name=_token]').attr('content')
            },
            dataType: "json",
            success: function(response) {
                swal.fire(response.message, '', 'success')
                $('.follow-vendor-by-user').text(response.text)
                $('.follower-text').find('span').text(response.followers)
            }
        });
    })
</script>



<script>
    $(document).ready(function() {

        $("#gallery").popupLightbox({
            'fadeDuration': 0, // Set the fade duration to 0 for an instant open
            'resizeDuration': 0, // Set the resize duration to 0 for an instant open
        });


    });
</script>



@endpush