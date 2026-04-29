@extends('theme-views.layouts.app')

@section('title', translate('About Us'))

@push('css_or_js')

<meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="og:title" content="About {{$web_config['name']->value}} " />
<meta property="og:url" content="{{env('APP_URL')}}">
<meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

<meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="twitter:title" content="about {{$web_config['name']->value}}" />
<meta property="twitter:url" content="{{env('APP_URL')}}">
<meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
@endpush

@section('content')

<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 pb-3 d-none">
    <div class="page-title py-5 __opacity-half" style="--opacity: .5" data-bg-img="{{theme_asset('assets/img/media/page-title-bg.png')}}">
        <div class="container">
            <h1 class="absolute-white text-center">{{translate('About_Our_Company')}}</h1>
        </div>
    </div>
    <div class="container">
        <div class="card my-4">
            <div class="card-body p-lg-4 text-dark page-paragraph">
                {!! $about_us['value'] !!}
            </div>
        </div>
    </div>
</main>

<!-- HERO SECTION START  -->

<section class="aboutus__hero__section">
    <article class="about__hero__banner">
        <div class="container">
            <div class="col-12">
                <div class="about__content">
                    <h1 class="about__content-heading">Elevate Your Shopping Experience with Prime Basket</h1>
                    <p> {!! $about_us['value'] !!}</p>
                    <!-- <p class="aboutcontentdiv">At Prime Basket, we believe shopping should be simple, affordable, and convenient. Our mission is to bring the best quality products to your doorstep with just a few clicks. From daily essentials to premium groceries, household items, personal care, and lifestyle products – we’ve created a one-stop solution for all your needs.

                        Prime Basket is more than just an online store – we’re your trusted partner in making everyday living easier. With a wide selection of products carefully curated for quality and value, we ensure you always get the best without compromise. <br>
                        Prime Basket is more than just an online store – we’re your trusted partner in making everyday living easier. With a wide selection of products carefully curated for quality and value, we ensure you always get the best without compromise. <br>
                        Prime Basket is more than just an online store – we’re your trusted partner in making everyday living easier. With a wide selection of products carefully curated for quality and value, we ensure you always get the best without compromise. <br>
                        Prime Basket is more than just an online store – we’re your trusted partner in making everyday living easier. With a wide selection of products carefully curated for quality and value, we ensure you always get the best without compromise.
                    </p> -->
                </div>
            </div>
        </div>
    </article>

    <!-- TRANSFORM TRANSLATE Y  -->
    <section class="images__section">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <picture>
                        <img src="{{theme_asset('assets/images/about-us/card-1.png')}}" alt="" width="100%" />
                    </picture>
                </div>
                <div class="col-4">
                    <picture>
                        <img src="{{theme_asset('assets/images/about-us/card-2.png')}}" alt="" width="100%" />
                    </picture>
                </div>
                <div class="col-4">
                    <picture>
                        <img src="{{theme_asset('assets/images/about-us/card-3.png')}}" alt="" width="100%" />
                    </picture>
                </div>
            </div>
        </div>
    </section>
</section>

<!-- HERO SECTION END  -->


<!-- PROJECT CUSTOMER START-->
<section class="project__customer">
    <div class="container">
        <div class="col-md-6 mx-auto">
            <h2 class="text-center">Why you choose Prime Basket</h2>
            <p class="text-center">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
                scrambled it to make a type specimen book. It has survived not only five centurie</p>
        </div>

        <div class="col-md-10 mx-auto">
            <div class="row ratingdiv">

                <!-- CUSTOMER  -->
                <div class="col-md-3 col-6">
                    <div class="project__card">
                        <div class="project__icon">
                            <picture>
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M19.183 30.4136C26.561 30.4136 32.867 31.5316 32.867 35.9976C32.867 40.4636 26.603 41.6136 19.183 41.6136C11.803 41.6136 5.49902 40.5056 5.49902 36.0376C5.49902 31.5696 11.761 30.4136 19.183 30.4136Z"
                                        stroke="#0A9494" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M19.1831 24.0395C14.3391 24.0395 10.4111 20.1135 10.4111 15.2695C10.4111 10.4255 14.3391 6.49951 19.1831 6.49951C24.0251 6.49951 27.9532 10.4255 27.9532 15.2695C27.9711 20.0955 24.0711 24.0215 19.2451 24.0395H19.1831Z"
                                        stroke="#0A9494" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M32.9663 21.7631C36.1683 21.3131 38.6343 18.5651 38.6403 15.2391C38.6403 11.9611 36.2503 9.24105 33.1163 8.72705"
                                        stroke="#0A9494" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M37.1909 29.4644C40.2929 29.9264 42.4589 31.0144 42.4589 33.2544C42.4589 34.7964 41.4389 35.7964 39.7909 36.4224"
                                        stroke="#0A9494" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </picture>
                        </div>

                        <div class="project__list">
                            <h3>126K</h3>
                            <p>{{translate('Customer')}} </p>
                        </div>
                    </div>
                </div>


                <!-- PRODUCT -->
                <div class="col-md-3 col-6">
                    <div class="project__card">
                        <div class="project__icon">
                            <picture>
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M33.0275 43.0001H16.3318C10.1991 43.0001 5.4943 40.785 6.83069 31.8696L8.38675 19.7873C9.21055 15.3387 12.0481 13.6362 14.5378 13.6362H34.8948C37.4211 13.6362 40.0938 15.4669 41.0458 19.7873L42.6019 31.8696C43.7369 39.7781 39.1602 43.0001 33.0275 43.0001Z"
                                        stroke="#0A9494" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M33.302 13.197C33.302 8.42489 29.4334 4.55623 24.6612 4.55623C22.3632 4.54657 20.156 5.45263 18.5276 7.07414C16.8992 8.69564 15.9839 10.899 15.9839 13.197"
                                        stroke="#0A9494" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M30.5928 22.2041H30.5012" stroke="#0A9494" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M18.9314 22.2041H18.8398" stroke="#0A9494" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </picture>
                        </div>

                        <div class="project__list">
                            <h3>126K</h3>
                            <p>{{translate('Products')}} </p>
                        </div>
                    </div>
                </div>


                <!-- DRIVES -->
                <div class="col-md-3 col-6">
                    <div class="project__card">
                        <div class="project__icon">
                            <picture>
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"
                                    fill="none">
                                    <path d="M31.4324 32.4468H16.9924" stroke="#0A9494" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M31.4324 24.0737H16.9924" stroke="#0A9494" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M22.5027 15.7202H16.9927" stroke="#0A9494" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M31.8172 5.49951C31.8172 5.49951 16.4632 5.50751 16.4392 5.50751C10.9192 5.54151 7.50122 9.17351 7.50122 14.7135V33.1055C7.50122 38.6735 10.9452 42.3195 16.5132 42.3195C16.5132 42.3195 31.8652 42.3135 31.8912 42.3135C37.4112 42.2795 40.8312 38.6455 40.8312 33.1055V14.7135C40.8312 9.14551 37.3852 5.49951 31.8172 5.49951Z"
                                        stroke="#0A9494" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </picture>
                        </div>

                        <div class="project__list">
                            <h3>126K</h3>
                            <p>{{translate('Drivers')}} </p>
                        </div>
                    </div>
                </div>


                <!-- Successfully delivered order  -->

                <div class="col-md-3 col-6 d-none">
                    <div class="project__card">
                        <div class="project__icon">
                            <picture>
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M26.2085 8.35401L29.8633 15.6555C30.2215 16.3723 30.9129 16.8694 31.7147 16.9844L39.8907 18.1612C41.9107 18.4529 42.7146 20.9011 41.2526 22.3039L35.3404 27.9849C34.7593 28.5435 34.4949 29.3466 34.6323 30.1353L36.0276 38.1556C36.3712 40.1397 34.2595 41.6534 32.454 40.7148L25.1464 36.9254C24.43 36.5536 23.572 36.5536 22.8536 36.9254L15.546 40.7148C13.7405 41.6534 11.6288 40.1397 11.9745 38.1556L13.3677 30.1353C13.5051 29.3466 13.2407 28.5435 12.6596 27.9849L6.74736 22.3039C5.28543 20.9011 6.08928 18.4529 8.10933 18.1612L16.2853 16.9844C17.0871 16.8694 17.7806 16.3723 18.1387 15.6555L21.7915 8.35401C22.6953 6.54866 25.3047 6.54866 26.2085 8.35401Z"
                                        stroke="#0A9494" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </picture>
                        </div>

                        <div class="project__list">
                            <h3>126K</h3>
                            <p>{{translate('Successfully_delivered_order')}} </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="section text-center p-0 mt-4 d-none">
    <div class="container">
        <div class="tryContainer mb-5">
            <div class="tryBg">
                <img class="tryLogoImg" src="images/logoMain.png" alt="">
                <h2>
                    Download our App now!
                </h2>
                <p>
                    We’re available on Android devices and platforms.
                </p>
                <div class="tryApp">
                    <a href="#">
                        <img src="images/google-play.svg" alt="">
                    </a>
                    <a href="#">
                        <img src="images/app-store.svg" alt="">
                    </a>
                </div>
            </div>
            <div class="tryImg">
                <img src="{{ asset('resources/themes/alpha_ecom/public/assets/images/aboutsection2.png') }}" alt="">
            </div>
        </div>
    </div>
</section>

<!-- What we do -->

<section class="what__we__do__section d-none">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7 p-0">

                <div class="what__we__do">
                    <div class="col-md-10 ms-md-auto">
                        <div class="content__whtwedo">
                            <h3>{{translate('What_we_do')}} </h3>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                has
                                been
                                the industry's standard dummy </p>
                            <p>hen an unknown printer took a galley of type and scrambled it to make a type specimen
                                book.
                                It
                                has survived not only five centuries, but also the leap into electronic typesetting,
                                remaining
                                essentially unchanged. It was popularised in the 1960s with the release of Letraset
                                sheets
                                containing Lorem Ipsum passages</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 p-0">
                <img src="{{theme_asset('assets/images/about-us/what-be-do.png')}}" alt="" width="100%" height="100%">
            </div>
        </div>
    </div>
</section>

<section class="vission__section">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="vission__content">
                    <h3>{{translate('Our Vision')}}</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, hen an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the </p>
                </div>
            </div>

            <div class="col-md-1"></div>
            <div class="col-md-6">
                <img src="{{theme_asset('assets/images/about-us/visionsection.jpeg ')}}" alt="" width="100%" class="visionlogo">
            </div>
        </div>
    </div>
</section>

<!-- VISSION SECTION -->

<section class="vission__section">
    <div class="container">
        <div class="row">

            <div class="col-md-6">
                <img src="{{theme_asset('assets/images/about-us/approch.jpg ')}}" alt="" width="100%">
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
                <div class="vission__content">
                    <h3>{{translate('Our Approach')}}</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, hen an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!--Our Advisor section  -->


<section class="our__adviser d-none">
    <div class="container">
        <div class="col-md-6 mx-auto mb-5">
            <div class="adviser__heading">
                <h3 class="text-center">{{translate('Our_Advisor')}}</h3>
                <p class="text-center">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, </p>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <img src="{{theme_asset('assets/images/about-us/adviser-1.png')}}" alt="" width="100%">
            </div>

            <div class="col-4">
                <img src="{{theme_asset('assets/images/about-us/adviser-2.png')}}" alt="" width="100%">
            </div>

            <div class="col-4">
                <img src="{{theme_asset('assets/images/about-us/adviser-3.png')}}" alt="" width="100%">
            </div>
        </div>
    </div>
</section>

<!-- OUR PARTNER SECTION  -->

<section class="pageSection d-none">
    <div class="container">
        <h2>Top Selling Brands</h2>
        <div class="brandContainer">
            <div class="brandDiv">
                <a href="https://prime-basket.developmentalphawizz.com/products?id=4&amp;data_from=brand&amp;page=1" class="brand_div">
                    <img src="https://prime-basket.developmentalphawizz.com/storage/app/public/brand/2025-09-02-68b67f47a4768.png" alt="">
                </a>
            </div>
            <div class="brandDiv">
                <a href="https://prime-basket.developmentalphawizz.com/products?id=5&amp;data_from=brand&amp;page=1" class="brand_div">
                    <img src="https://prime-basket.developmentalphawizz.com/storage/app/public/brand/2025-09-02-68b67f7a45df5.png" alt="">
                </a>
            </div>
            <div class="brandDiv">
                <a href="https://prime-basket.developmentalphawizz.com/products?id=6&amp;data_from=brand&amp;page=1" class="brand_div">
                    <img src="https://prime-basket.developmentalphawizz.com/storage/app/public/brand/2025-09-02-68b67f94a204e.png" alt="">
                </a>
            </div>
            <div class="brandDiv">
                <a href="https://prime-basket.developmentalphawizz.com/products?id=7&amp;data_from=brand&amp;page=1" class="brand_div">
                    <img src="https://prime-basket.developmentalphawizz.com/storage/app/public/brand/2025-09-02-68b67fa078e3b.png" alt="">
                </a>
            </div>
            <div class="brandDiv">
                <a href="https://prime-basket.developmentalphawizz.com/products?id=8&amp;data_from=brand&amp;page=1" class="brand_div">
                    <img src="https://prime-basket.developmentalphawizz.com/storage/app/public/brand/2025-09-02-68b67fb1e7961.png" alt="">
                </a>
            </div>
            <div class="brandDiv">
                <a href="https://prime-basket.developmentalphawizz.com/products?id=9&amp;data_from=brand&amp;page=1" class="brand_div">
                    <img src="https://prime-basket.developmentalphawizz.com/storage/app/public/brand/2025-09-01-68b5b297b6fc5.png" alt="">
                </a>
            </div>
            <div class="brandDiv">
                <a href="https://prime-basket.developmentalphawizz.com/products?id=10&amp;data_from=brand&amp;page=1" class="brand_div">
                    <img src="https://prime-basket.developmentalphawizz.com/storage/app/public/brand/2025-08-30-68b2932cb6629.png" alt="">
                </a>
            </div>
        </div>
    </div>
</section>

<section class="our__partner__section d-none">
    <div class="container">
        <div class="col-md-6 mx-auto">
            <div class="adviser__heading">
                <h3 class="text-center">{{translate('Our_Partner')}}</h3>
                <p class="text-center">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
            </div>
        </div>

        <div class="our-partner">
            <div class="owl-carousel our-partner-carousel">
                <div class="item">
                    <div class="our__partner__images">
                        <img src="{{theme_asset('assets/images/about-us/partner-1.png')}}" alt="">
                    </div>
                </div>

                <div class="item">
                    <div class="our__partner__images">
                        <img src="{{theme_asset('assets/images/about-us/partner-2.png')}}" alt="">
                    </div>
                </div>


                <div class="item">
                    <div class="our__partner__images">
                        <img src="{{theme_asset('assets/images/about-us/partner-3.png')}}" alt="">
                    </div>
                </div>


                <div class="item">
                    <div class="our__partner__images">
                        <img src="{{theme_asset('assets/images/about-us/partner-4.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection