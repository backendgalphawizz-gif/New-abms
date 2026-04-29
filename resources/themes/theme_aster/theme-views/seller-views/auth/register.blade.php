@extends('theme-views.layouts.register-app')

@section('title', $web_config['name']->value.' '.translate('Seller_Apply').' | '.$web_config['name']->value.'
'.translate(' Ecommerce'))

@push('css_or_js')
<link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
<style>
.select2-container {
    max-width: 100%;
    width: 100% !important;
}

span.select2-selection.select2-selection--single {

    border: 1px solid #0a9494 !important;
}
</style>
@endpush

@section('content')

<!-- Main Content -->
<main class="file-control-heightviewpage">
    @php($e_commerce_logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
    <!-- <a class="d-flex justify-content-center mb-5" href="javascript:">
        <img class="z-index-2" height="40" src="{{asset("storage/app/public/company/".$e_commerce_logo)}}" alt="Logo"
            onerror="this.src='{{asset('public/assets/back-end/img/400x400/img2.jpg')}}'">
    </a> -->
    <div class="container mt">
        <div class="card height__control">
            <div class="card-body p-sm-4">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="bg-light p-3 p-sm-4 rounded h-100 bg-aplly-pictrure">
                            <div class="d-flex justify-content-center">
                                <div class="">
                                    <div class="my-4 text-center">
                                        <img width="100" src="{{theme_asset('assets/img/media/vendor-logo.png')}}"
                                            loading="lazy" alt="" class="dark-support">
                                    </div>
                                    <h2 class="mb-2 text welcome-line text-center">
                                        {{translate('Welcome to Alpha e-commerce')}}</h2>
                                    <!-- <p>{{translate('Create_your_own_store.')}} {{translate('Already_have_store?')}} <a
                                            class="text-primary fw-bold"
                                            href="{{route('seller.auth.login')}}">{{translate('Login')}}</a></p> -->


                                    <p class="text-muted text-center">{{translate('Open_your_and_start_selling.')}}
                                        {{translate('Create_your_own_business')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xl-7">
                        <form id="seller-registration" action="{{route('shop.apply')}}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="wizard">
                                <h3>{{ translate('Seller_Info') }}</h3>
                                <section>

                                    <div class="logo-image mb-md-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="68" height="68"
                                            viewBox="0 0 68 68" fill="none">
                                            <path
                                                d="M44.7212 58.249C46.1075 58.249 47.2314 57.1251 47.2314 55.7387C47.2314 54.3524 46.1075 53.2285 44.7212 53.2285C43.3348 53.2285 42.2109 54.3524 42.2109 55.7387C42.2109 57.1251 43.3348 58.249 44.7212 58.249Z"
                                                fill="#040D12" />
                                            <path
                                                d="M60.8383 58.249C62.2247 58.249 63.3486 57.1251 63.3486 55.7387C63.3486 54.3524 62.2247 53.2285 60.8383 53.2285C59.452 53.2285 58.3281 54.3524 58.3281 55.7387C58.3281 57.1251 59.452 58.249 60.8383 58.249Z"
                                                fill="#040D12" />
                                            <path
                                                d="M34.1225 13.4987C34.9512 14.9265 35.7771 16.3556 36.6045 17.7848C36.6693 18.0019 36.8511 17.9046 36.978 17.9046C43.5855 17.9103 50.193 17.9074 56.8018 17.9117C57.4107 17.9117 58.0168 17.875 58.6299 17.8905C60.7131 17.9399 62.7976 17.906 64.8822 17.9046C64.8808 17.8891 64.8794 17.8736 64.878 17.8581C64.8935 17.8468 64.9076 17.8299 64.9146 17.8031C64.9174 15.782 64.916 13.7594 64.916 11.7383C64.916 11.2788 64.9541 11.3352 64.5045 11.3352C54.1211 11.3338 43.7363 11.3352 33.3529 11.3352C33.2683 11.3352 33.1838 11.3324 33.0992 11.3366C32.9428 11.3422 32.9287 11.4296 32.9794 11.5522C33.3599 12.2006 33.7447 12.8475 34.1225 13.4987Z"
                                                fill="#0A9494" />
                                            <path
                                                d="M61.476 27.5527C58.333 27.5612 55.1885 27.5584 52.0454 27.5584C51.5169 27.5584 50.9869 27.5584 50.4584 27.5584C47.834 27.5584 45.2082 27.5584 42.5838 27.557C42.3978 27.557 42.2202 27.5316 42.3865 27.8191C43.5281 29.7867 44.6613 31.7599 45.7945 33.7317C45.9214 33.953 46.0651 34.0686 46.3583 34.0672C51.3971 34.0545 56.4344 34.0545 61.4732 34.0658C61.7875 34.0658 61.8382 33.953 61.8368 33.6782C61.827 31.7515 61.8256 29.8248 61.8382 27.8995C61.8411 27.6091 61.7466 27.5527 61.476 27.5527Z"
                                                fill="#0A9494" />
                                            <path
                                                d="M64.8798 43.7775C64.7952 43.7718 64.7121 43.7591 64.6275 43.7591C60.3273 43.7577 56.0285 43.7591 51.7283 43.7591H46.5218C45.8199 43.7591 45.118 43.7591 44.4161 43.7605C44.2413 43.7605 44.1187 43.745 44.0102 43.5534C43.0983 41.9466 42.1652 40.3525 41.2378 38.7542C40.3992 37.3081 39.5563 35.862 38.7191 34.4145C37.6592 32.5794 36.605 30.7429 35.5465 28.9064C34.6952 27.4307 33.841 25.955 32.9883 24.4793C31.9397 22.6625 30.8897 20.8458 29.8424 19.0276C29.1025 17.7422 28.3653 16.4539 27.6268 15.1685C26.9291 13.955 26.23 12.7443 25.5309 11.5321C25.4633 11.4152 25.4267 11.3658 25.3294 11.5364C24.7825 12.499 24.2131 13.449 23.6592 14.4088C22.6712 16.1185 21.6916 17.8324 20.705 19.542C19.7875 21.1319 18.86 22.7175 17.9425 24.3088C16.9559 26.0184 15.9763 27.7337 14.9883 29.4448C14.0693 31.0375 13.1419 32.6245 12.223 34.2172C11.1631 36.0523 10.1003 37.886 9.05595 39.7295C8.77124 40.2327 8.41184 40.6936 8.19901 41.2362C8.01015 41.4152 7.86638 41.621 7.82692 41.886C7.12783 43.0798 6.42452 44.2708 5.73107 45.4688C4.86849 46.9586 4.01296 48.454 3.15461 49.9466C3.1081 50.0269 3.01366 50.1031 3.05877 50.2003C3.10951 50.3074 3.22931 50.2524 3.31669 50.2539C4.44989 50.2581 5.58308 50.2567 6.71627 50.2567C15.3252 50.2567 23.9355 50.2567 32.5443 50.2539C32.6444 50.2539 32.7854 50.3102 32.8361 50.2045C32.8756 50.1228 32.7839 50.0199 32.7332 49.9311C31.5958 47.9593 30.4584 45.9861 29.3238 44.0114C29.2195 43.831 29.0982 43.7535 28.8741 43.7549C25.9721 43.7648 23.0715 43.7605 20.1694 43.7605C18.2949 43.7605 16.4203 43.7605 14.5457 43.7605C14.3977 43.7605 14.1751 43.8099 14.3357 43.5322C15.2406 41.9691 16.1497 40.4089 17.0545 38.8458C18.1032 37.0361 19.1518 35.2278 20.1948 33.4166C21.2265 31.6266 22.2484 29.8324 23.2815 28.0424C23.9552 26.8754 24.6444 25.7168 25.3153 24.547C25.4393 24.3313 25.4591 24.4018 25.5507 24.5625C26.1497 25.6139 26.7614 26.6583 27.366 27.7069C28.1596 29.0826 28.9517 30.461 29.7452 31.8366C30.4133 32.9938 31.0884 34.1467 31.7536 35.3053C32.1074 35.9198 32.4471 36.5428 32.7938 37.1615C32.9799 37.4899 33.063 37.8761 33.3407 38.1538C33.3604 38.1679 33.3787 38.1791 33.3971 38.1862C33.483 38.3426 33.5563 38.5061 33.6691 38.6457C33.6649 38.6753 33.6592 38.7049 33.655 38.7345C33.6959 38.8951 33.7832 39.0192 33.9341 39.0953C33.9496 39.1953 33.9636 39.2968 34.044 39.3603C34.0468 39.3729 34.0496 39.3856 34.0553 39.3983C34.0947 39.5548 34.2018 39.6661 34.309 39.7774C34.3738 39.8719 34.4429 39.9635 34.5007 40.0622C35.4112 41.6421 36.3174 43.225 37.2279 44.8049C38.2272 46.5386 39.2279 48.2708 40.2286 50.0044C40.2554 50.2144 40.371 50.3159 40.5866 50.3159C41.1603 50.3131 41.7353 50.3159 42.3104 50.3159C49.7311 50.3159 57.1532 50.3145 64.574 50.3215C64.8671 50.3215 64.9545 50.2398 64.9531 49.9466C64.9418 47.9875 64.9475 46.0297 64.9447 44.0706C64.9461 43.9677 64.9982 43.8507 64.8798 43.7775ZM41.6437 50.2088C41.6494 50.2017 41.6536 50.1961 41.6578 50.1876C41.662 50.182 41.6634 50.1749 41.6663 50.1693C41.6761 50.182 41.686 50.1947 41.6973 50.2088C41.679 50.2088 41.6606 50.2088 41.6437 50.2088ZM42.2864 50.1792C42.2907 50.1721 42.2935 50.1651 42.2963 50.1594C42.2991 50.1707 42.3019 50.1806 42.3062 50.189C42.2991 50.1848 42.2935 50.182 42.2864 50.1792Z"
                                                fill="#040D12" />
                                        </svg>
                                    </div>

                                    <div class="form-heading">
                                        <h4>Create your account</h4>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                        </p>
                                    </div>
                                    <div class="row">


                                        <div class="col-lg-6">
                                            <div class="form-group login-form">

                                                <span class="position-relative">

                                                    <input class="balloon form-control" type="text" id="firstName"
                                                        name="f_name" value="{{old('f_name')}}"
                                                        placeholder="{{translate('Ex').':'.translate(' Jhon')}}"
                                                        required>
                                                    <label for="firstName">{{translate('Name')}} *</label>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-none">
                                            <div class="form-group login-form ">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="lastName"
                                                        name="l_name" value="{{old('l_name')}}"
                                                        placeholder="{{translate('Ex').':'.translate(' Doe')}}">
                                                    <label for="lastName">{{translate('Last_Name')}}</label>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group login-form ">

                                                <span>
                                                    <input class="balloon form-control" type="email" id="email2"
                                                        name="email" value="{{old('email')}}"
                                                        placeholder="{{translate('Enter_email')}}" required>
                                                    <label for="email2">{{translate('Email')}} *</label>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group login-form ">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="tel"
                                                        name="phone" value="{{old('phone')}}"
                                                        placeholder="{{translate('Enter_phone_number')}}"
                                                        onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                                        minlength="10" maxlength="10" required>
                                                    <label for="tel">{{translate('Phone')}} *</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group login-form ">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="tel"
                                                        name="referral_code" value="{{old('referral_code')}}"
                                                        placeholder="{{translate('Referral Code')}}"
                                                        onkeypress="return (event.charCode !=32)" required>
                                                    <label for="tel">{{translate('Referral Code')}} *</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group login-form mb-4">
                                                <span>
                                                    <input class="balloon form-control" type="text" id="otp"
                                                        name="otp" value="{{ old('otp') }}"
                                                        placeholder="{{ translate('Enter OTP') }}"
                                                        onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                                        minlength="4" maxlength="4" required>
                                                    <label for="otp">{{translate('Enter OTP')}} *</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="media gap-3 align-items-center">
                                                <!-- <div class="upload-file">
                                                    <input type="file" class="upload-file__input" name="image"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                        required>
                                                    <div class="upload-file__img">
                                                        <div class="temp-img-box">
                                                            <div class="d-flex align-items-center flex-column gap-2">
                                                                <i class="bi bi-upload fs-30"></i>
                                                                <div class="fs-12 text-muted">
                                                                    {{translate('Upload_File')}}</div>
                                                            </div>
                                                        </div>
                                                        <img src="#" class="dark-support img-fit-contain border" alt=""
                                                            hidden>
                                                    </div>
                                                </div> -->

                                                <!-- <div class="media-body d-flex flex-column gap-1 upload-img-content">
                                                    <h5 class="text-uppercase mb-1">{{translate('Seller_Image')}}</h5>
                                                    <div class="text-muted">{{translate('Image_Ration')}} 1:1</div>
                                                    <div class="text-muted">
                                                        NB: {{translate('Image_size_must_be_within')}} 2 MB <br>
                                                        NB: {{translate('Image_type_must_be_within')}} .jpg, .png,
                                                        .jpeg, .gif, .bmp, .tif, .tiff
                                                    </div>
                                                </div> -->

                                                <p class="received-otpcode">Didn’t received Otp? <a href="">Resend
                                                        OTP</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <h3>{{translate('Bussiness_Info')}}</h3>
                                <section>


                                    <div class="logo-image mb-md-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="68" height="68"
                                            viewBox="0 0 68 68" fill="none">
                                            <path
                                                d="M44.7212 58.249C46.1075 58.249 47.2314 57.1251 47.2314 55.7387C47.2314 54.3524 46.1075 53.2285 44.7212 53.2285C43.3348 53.2285 42.2109 54.3524 42.2109 55.7387C42.2109 57.1251 43.3348 58.249 44.7212 58.249Z"
                                                fill="#040D12" />
                                            <path
                                                d="M60.8383 58.249C62.2247 58.249 63.3486 57.1251 63.3486 55.7387C63.3486 54.3524 62.2247 53.2285 60.8383 53.2285C59.452 53.2285 58.3281 54.3524 58.3281 55.7387C58.3281 57.1251 59.452 58.249 60.8383 58.249Z"
                                                fill="#040D12" />
                                            <path
                                                d="M34.1225 13.4987C34.9512 14.9265 35.7771 16.3556 36.6045 17.7848C36.6693 18.0019 36.8511 17.9046 36.978 17.9046C43.5855 17.9103 50.193 17.9074 56.8018 17.9117C57.4107 17.9117 58.0168 17.875 58.6299 17.8905C60.7131 17.9399 62.7976 17.906 64.8822 17.9046C64.8808 17.8891 64.8794 17.8736 64.878 17.8581C64.8935 17.8468 64.9076 17.8299 64.9146 17.8031C64.9174 15.782 64.916 13.7594 64.916 11.7383C64.916 11.2788 64.9541 11.3352 64.5045 11.3352C54.1211 11.3338 43.7363 11.3352 33.3529 11.3352C33.2683 11.3352 33.1838 11.3324 33.0992 11.3366C32.9428 11.3422 32.9287 11.4296 32.9794 11.5522C33.3599 12.2006 33.7447 12.8475 34.1225 13.4987Z"
                                                fill="#0A9494" />
                                            <path
                                                d="M61.476 27.5527C58.333 27.5612 55.1885 27.5584 52.0454 27.5584C51.5169 27.5584 50.9869 27.5584 50.4584 27.5584C47.834 27.5584 45.2082 27.5584 42.5838 27.557C42.3978 27.557 42.2202 27.5316 42.3865 27.8191C43.5281 29.7867 44.6613 31.7599 45.7945 33.7317C45.9214 33.953 46.0651 34.0686 46.3583 34.0672C51.3971 34.0545 56.4344 34.0545 61.4732 34.0658C61.7875 34.0658 61.8382 33.953 61.8368 33.6782C61.827 31.7515 61.8256 29.8248 61.8382 27.8995C61.8411 27.6091 61.7466 27.5527 61.476 27.5527Z"
                                                fill="#0A9494" />
                                            <path
                                                d="M64.8798 43.7775C64.7952 43.7718 64.7121 43.7591 64.6275 43.7591C60.3273 43.7577 56.0285 43.7591 51.7283 43.7591H46.5218C45.8199 43.7591 45.118 43.7591 44.4161 43.7605C44.2413 43.7605 44.1187 43.745 44.0102 43.5534C43.0983 41.9466 42.1652 40.3525 41.2378 38.7542C40.3992 37.3081 39.5563 35.862 38.7191 34.4145C37.6592 32.5794 36.605 30.7429 35.5465 28.9064C34.6952 27.4307 33.841 25.955 32.9883 24.4793C31.9397 22.6625 30.8897 20.8458 29.8424 19.0276C29.1025 17.7422 28.3653 16.4539 27.6268 15.1685C26.9291 13.955 26.23 12.7443 25.5309 11.5321C25.4633 11.4152 25.4267 11.3658 25.3294 11.5364C24.7825 12.499 24.2131 13.449 23.6592 14.4088C22.6712 16.1185 21.6916 17.8324 20.705 19.542C19.7875 21.1319 18.86 22.7175 17.9425 24.3088C16.9559 26.0184 15.9763 27.7337 14.9883 29.4448C14.0693 31.0375 13.1419 32.6245 12.223 34.2172C11.1631 36.0523 10.1003 37.886 9.05595 39.7295C8.77124 40.2327 8.41184 40.6936 8.19901 41.2362C8.01015 41.4152 7.86638 41.621 7.82692 41.886C7.12783 43.0798 6.42452 44.2708 5.73107 45.4688C4.86849 46.9586 4.01296 48.454 3.15461 49.9466C3.1081 50.0269 3.01366 50.1031 3.05877 50.2003C3.10951 50.3074 3.22931 50.2524 3.31669 50.2539C4.44989 50.2581 5.58308 50.2567 6.71627 50.2567C15.3252 50.2567 23.9355 50.2567 32.5443 50.2539C32.6444 50.2539 32.7854 50.3102 32.8361 50.2045C32.8756 50.1228 32.7839 50.0199 32.7332 49.9311C31.5958 47.9593 30.4584 45.9861 29.3238 44.0114C29.2195 43.831 29.0982 43.7535 28.8741 43.7549C25.9721 43.7648 23.0715 43.7605 20.1694 43.7605C18.2949 43.7605 16.4203 43.7605 14.5457 43.7605C14.3977 43.7605 14.1751 43.8099 14.3357 43.5322C15.2406 41.9691 16.1497 40.4089 17.0545 38.8458C18.1032 37.0361 19.1518 35.2278 20.1948 33.4166C21.2265 31.6266 22.2484 29.8324 23.2815 28.0424C23.9552 26.8754 24.6444 25.7168 25.3153 24.547C25.4393 24.3313 25.4591 24.4018 25.5507 24.5625C26.1497 25.6139 26.7614 26.6583 27.366 27.7069C28.1596 29.0826 28.9517 30.461 29.7452 31.8366C30.4133 32.9938 31.0884 34.1467 31.7536 35.3053C32.1074 35.9198 32.4471 36.5428 32.7938 37.1615C32.9799 37.4899 33.063 37.8761 33.3407 38.1538C33.3604 38.1679 33.3787 38.1791 33.3971 38.1862C33.483 38.3426 33.5563 38.5061 33.6691 38.6457C33.6649 38.6753 33.6592 38.7049 33.655 38.7345C33.6959 38.8951 33.7832 39.0192 33.9341 39.0953C33.9496 39.1953 33.9636 39.2968 34.044 39.3603C34.0468 39.3729 34.0496 39.3856 34.0553 39.3983C34.0947 39.5548 34.2018 39.6661 34.309 39.7774C34.3738 39.8719 34.4429 39.9635 34.5007 40.0622C35.4112 41.6421 36.3174 43.225 37.2279 44.8049C38.2272 46.5386 39.2279 48.2708 40.2286 50.0044C40.2554 50.2144 40.371 50.3159 40.5866 50.3159C41.1603 50.3131 41.7353 50.3159 42.3104 50.3159C49.7311 50.3159 57.1532 50.3145 64.574 50.3215C64.8671 50.3215 64.9545 50.2398 64.9531 49.9466C64.9418 47.9875 64.9475 46.0297 64.9447 44.0706C64.9461 43.9677 64.9982 43.8507 64.8798 43.7775ZM41.6437 50.2088C41.6494 50.2017 41.6536 50.1961 41.6578 50.1876C41.662 50.182 41.6634 50.1749 41.6663 50.1693C41.6761 50.182 41.686 50.1947 41.6973 50.2088C41.679 50.2088 41.6606 50.2088 41.6437 50.2088ZM42.2864 50.1792C42.2907 50.1721 42.2935 50.1651 42.2963 50.1594C42.2991 50.1707 42.3019 50.1806 42.3062 50.189C42.2991 50.1848 42.2935 50.182 42.2864 50.1792Z"
                                                fill="#040D12" />
                                        </svg>
                                    </div>

                                    <div class="form-heading">
                                        <h4>Enter your detail</h4>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                        </p>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="form-group mb-4 login-form">
                                                <span>

                                                    <input class="balloon form-control" type="text"
                                                        id="Bussiness_Email_ID" name="bussiness_email_id"
                                                        placeholder="{{ translate('Ex') }}: {{ translate('abc@gmail.com') }}"
                                                        value="{{old('bussiness_email_id')}}" required>
                                                    <label for="Bussiness_Email_ID">{{translate('Bussiness_Email_ID')}}
                                                        *</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group mb-4 login-form">
                                                <span>

                                                    <input class="balloon form-control" type="text" id="Bussiness_Phone"
                                                        name="bussiness_phone"
                                                        placeholder="{{ translate('Ex') }}: {{ translate('+91-8962277899') }}"
                                                        value="{{old('bussiness_phone')}}"
                                                        onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                                        minlength="10" maxlength="10" required>
                                                    <label for=" Bussiness_Phone">{{translate('Bussiness_Phone')}}
                                                        *</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group mb-4 login-form">
                                                <span>

                                                    <input class="balloon form-control" type="text" id="Bussiness_type"
                                                        name="bussiness_type"
                                                        placeholder="{{ translate('Ex') }}: {{ translate('Foody Restraunt') }}"
                                                        value="{{old('bussiness_type')}}" required>
                                                    <label for="Bussiness_type">{{translate('Bussiness_type')}}
                                                        *</label>
                                                </span>
                                            </div>
                                        </div>


                                        <div class="col-lg-6">
                                            <div class="form-group mb-4 login-form">
                                                <span>

                                                    <input class="balloon form-control" type="text" id="storeName"
                                                        name="shop_name"
                                                        placeholder="{{translate('Ex')}}: {{translate('halar')}}"
                                                        value="{{old('shop_name')}}" required>
                                                    <label
                                                        for="storeName">{{translate('Company_Name_or_Bussiness_name')}}
                                                        *</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group mb-4 login-form">
                                                <span>

                                                    <input class="balloon form-control" type="text"
                                                        id="bussiness_registeration_number"
                                                        name="bussiness_registeration_number"
                                                        placeholder="{{translate('Ex')}}: {{translate('8979465654987')}}"
                                                        minlength="21" maxlength="21"
                                                        value="{{old('bussiness_registeration_number')}}" required>
                                                    <label
                                                        for="bussiness_registeration_number">{{translate('bussiness_registeration_number') }}
                                                        ({{translate('if applicable') }})</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group mb-4 login-form">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="gst_number"
                                                        name="gst_number" placeholder="{{ translate('GSTIN') }}"
                                                        value="{{old('gst_number')}}" minlength="15" maxlength="15"
                                                        required>
                                                    <label for="gst_number">{{translate('GSTIN') }}*</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group mb-4 login-form">

                                                <span>
                                                    <input class="balloon form-control" type="text"
                                                        id="tax_identification_number" name="tax_identification_number"
                                                        placeholder="{{ translate('Tax Identification Number') }}"
                                                        minlength="11" maxlength="11"
                                                        value="{{old('tax_identification_number')}}" required>
                                                    <label
                                                        for="tax_identification_number">{{translate('Tax Identification Number') }}
                                                        ({{translate('TIN') }}) *</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group mb-4 login-form">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="website"
                                                        name="website"
                                                        placeholder="{{translate('Ex')}}: {{translate('https://website.com')}}"
                                                        value="{{old('website')}}" required>
                                                    <label for="website">{{translate('Website') }}*</label>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- <div class="col-lg-6 mb-4">
                                            <div class="d-flex flex-column gap-3 align-items-center">
                                                <div class="upload-file">
                                                    <input type="file" class="upload-file__input" name="banner"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                        required>
                                                    <div class="upload-file__img style--two">
                                                        <div class="temp-img-box">
                                                            <div class="d-flex align-items-center flex-column gap-2">
                                                                <i class="bi bi-upload fs-30"></i>
                                                                <div class="fs-12 text-muted">
                                                                    {{translate('Upload_File')}}</div>
                                                            </div>
                                                        </div>
                                                        <img src="#" class="dark-support img-fit-contain border" alt=""
                                                            hidden>
                                                    </div>
                                                </div>

                                                <div class="text-center">
                                                    <h5 class="text-uppercase mb-1">{{translate('Store_Banner')}}</h5>
                                                    <div class="text-muted">{{translate('Image_Ratio')}} 3:1</div>
                                                </div>
                                            </div>
                                        </div> -->

                                        @if(theme_root_path() == "theme_aster")
                                        <!-- <div class="col-lg-6 mb-4">
                                            <div class="d-flex flex-column gap-3 align-items-center">
                                                <div class="upload-file">
                                                    <input type="file" class="upload-file__input" name="bottom_banner"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                        required>
                                                    <div class="upload-file__img style--two">
                                                        <div class="temp-img-box">
                                                            <div class="d-flex align-items-center flex-column gap-2">
                                                                <i class="bi bi-upload fs-30"></i>
                                                                <div class="fs-12 text-muted">
                                                                    {{translate('Upload_File')}}</div>
                                                            </div>
                                                        </div>
                                                        <img src="#" class="dark-support img-fit-contain border" alt=""
                                                            hidden>
                                                    </div>
                                                </div>

                                                <div class="text-center">
                                                    <h5 class="text-uppercase mb-1">
                                                        {{translate('Store_Secondary_Banner')}}</h5>
                                                    <div class="text-muted">{{translate('Image_Ratio')}} 3:1</div>
                                                </div>
                                            </div>
                                        </div> -->
                                        @endif

                                        <!-- <div class="col-lg-6 mb-4">
                                            <div class="d-flex flex-column gap-3 align-items-center">
                                                <div class="upload-file">
                                                    <input type="file" class="upload-file__input" name="logo"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                        required>
                                                    <div class="upload-file__img">
                                                        <div class="temp-img-box">
                                                            <div class="d-flex align-items-center flex-column gap-2">
                                                                <i class="bi bi-upload fs-30"></i>
                                                                <div class="fs-12 text-muted">
                                                                    {{translate('Upload_File')}}</div>
                                                            </div>
                                                        </div>
                                                        <img src="#" class="dark-support img-fit-contain border" alt=""
                                                            hidden>
                                                    </div>
                                                </div>

                                                <div class="text-center">
                                                    <h5 class="text-uppercase mb-1">{{translate('Store_Logo')}}</h5>
                                                    <div class="text-muted">{{translate('Image_Ratio')}} 1:1</div>
                                                </div>
                                            </div>
                                        </div> -->

                                    </div>
                                </section>

                                <h3>{{translate('create_Password')}}</h3>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-4">

                                                <div class="input-inner-end-ele">

                                                    <input class="balloon  form-control" type="password" id="password"
                                                        name="password" value="{{old('password')}}"
                                                        placeholder="{{translate('Enter_password')}}" required>
                                                    <label for="password">{{translate('Password')}} *</label>
                                                    <i class="bi bi-eye-slash-fill togglePassword"></i>



                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-4">

                                                <div class="input-inner-end-ele">
                                                    <input class="balloon form-control" type="password"
                                                        id="repeat_password" name="repeat_password"
                                                        placeholder="{{translate('repeat_password')}}" required>
                                                    <label for="repeat_password">{{translate('Confirm_Password')}}
                                                        *</label>
                                                    <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <h3>{{translate('Address Information')}}</h3>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-4 login-form">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="storeAddress"
                                                        name="shop_address" value="{{old('shop_address')}}"
                                                        placeholder="{{translate('Ex').': '.translate('Shop').'-12, '.translate('Road').'-8'}}"
                                                        required>
                                                    <label for="storeAddress">{{translate('Store_Address')}} *</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group login-form">

                                                <span class="">

                                                    <select name="country"
                                                        class="js-example-responsive balloon form-control">
                                                        <option value="">{{ translate('Country') }}</option>
                                                        @foreach($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    <!-- <label for="Country">{{ translate('Country') }} *</label> -->
                                                </span>

                                                <!-- <input class="js-example-responsive" type="text" id="Country" name="country" value="{{old('country')}}" placeholder="{{translate('Ex').': India'}}" required> -->
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group login-form mb-4">
                                                <span class="">

                                                    <select name="state"
                                                        class="js-example-responsive balloon form-control">
                                                        <option value="">State/ Province</option>
                                                    </select>
                                                    <!-- <input class="js-example-responsive" type="text" id="State" name="state" value="{{old('state')}}" placeholder="{{translate('Ex').'Madhya Pradesh'}}" required> -->
                                                    <!-- <label for="State">{{ translate('State/ Province') }} *</label> -->
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group login-form mb-4">

                                                <span>
                                                    <select name="city"
                                                        class="js-example-responsive balloon form-control">
                                                        <option value="">{{ translate('City') }}</option>
                                                    </select>

                                                   
                                                </span>
                                                <!-- <input class="js-example-responsive" type="text" id="City" name="city" value="{{old('city')}}" placeholder="{{translate('Ex').': Indore'}}" required> -->
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group login-form mb-4">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="Pincode"
                                                        name="pincode" value="{{old('pincode')}}"
                                                        placeholder="{{translate('Ex').': 452003'}}" required>
                                                    <label for="Pincode">{{translate('Postal/Zip Code')}} *</label>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </section>

                                <h3>{{translate('Banking Information')}}</h3>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group login-form mb-4">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="Bank_Name"
                                                        name="bank_name" value="{{ old('bank_name') }}"
                                                        placeholder="{{translate('Ex').': State bank of India'}}"
                                                        required>
                                                    <label for="Bank_Name">{{translate('Bank_Name')}} *</label>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group login-form mb-4">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="Bank_branch"
                                                        name="bank_branch" value="{{ old('bank_branch') }}"
                                                        placeholder="{{translate('Ex').': Palasia'}}" required>
                                                    <label for="Bank_branch">{{translate('Bank_branch')}} *</label>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group login-form mb-4">

                                                <span>
                                                    {{-- <input class="balloon form-control" type="text" id="Account_type" name="account_type" value="{{ old('account_type') }}" placeholder="{{translate('Ex').': Savings'}}" required> --}}
                                                 
                                                    <select name="account_type" class="js-example-responsive balloon form-control" required>
                                                        <option value="">{{translate('Account_type')}}</option>
                                                        <option value="Savings">Savings</option>
                                                        <option value="Current">Current</option>
                                                    </select>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group login-form mb-4">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="Micr_code"
                                                        name="micr_code" value="{{ old('micr_code') }}"
                                                        placeholder="{{translate('Ex').': ABC111100008'}}" required>
                                                    <label for="Micr_code">{{translate('Micr_code')}} *</label>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group login-form mb-4">

                                                <input class="balloon form-control" type="text" id="Bank_Address"
                                                    name="bank_address" value="{{ old('bank_address') }}"
                                                    placeholder="{{translate('Ex').': Palasia, Indore, Madhya Pradesh'}}"
                                                    required>
                                                <label for="Bank_Address">{{translate('Bank_Address')}} *</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group login-form mb-4">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="Account_number"
                                                        name="account_number" value="{{old('account_number')}}"
                                                        placeholder="{{translate('Ex').': 10235479658965'}}" required>
                                                    <label for="Account_number">{{translate('Account_number')}}
                                                        *</label>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group login-form mb-4">

                                                <span>
                                                    <input class="balloon form-control" type="text" id="IFSC_CODE"
                                                        name="ifsc_code" value="{{old('ifsc_code')}}"
                                                        placeholder="{{translate('Ex').': State bank of India'}}"
                                                        required>
                                                    <label for="IFSC_CODE">{{translate('IFSC_CODE')}} *</label>
                                                </span>
                                            </div>
                                        </div>

                                        @php($recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha'))
                                        @if(isset($recaptcha) && !empty($recaptcha) && $recaptcha['status'] == 1)
                                        <div class="col-12">
                                            <div id="recaptcha_element_seller_regi" class="w-100 mt-4"
                                                data-type="image">
                                            </div>
                                            <br />
                                        </div>
                                        @else
                                        <div class="col-12">
                                            <div class="row py-2 mt-4">
                                                <div class="col-6 pr-2">
                                                    <input type="text" class="balloon form-control border __h-40"
                                                        name="default_recaptcha_id_seller_regi" value=""
                                                        placeholder="{{\App\CPU\translate('Enter captcha value')}}"
                                                        autocomplete="off" required>
                                                </div>
                                                <div class="col-6 input-icons mb-2 rounded bg-white">
                                                    <a onclick="re_captcha_seller_regi();"
                                                        class="d-flex align-items-center align-items-center">
                                                        <img src="{{ URL('/seller/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_seller_regi') }}"
                                                            class="input-field rounded __h-40"
                                                            id="default_recaptcha_id_regi">
                                                        <i class="bi bi-arrow-repeat icon cursor-pointer p-2"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-12">
                                            <label class="custom-checkbox">
                                                <input id="acceptTerms" name="acceptTerms" type="checkbox" required>
                                                {{translate('I_agree_with_the')}} <a target="_blank"
                                                    href="{{route('terms')}}">{{translate('terms_and_condition')}}.</a>
                                            </label>
                                        </div>

                                    </div>
                                </section>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- End Main Content -->
@endsection

@push('script')
<!-- Page Level Scripts -->

<script src="{{theme_asset('assets/plugins/jquery-step/jquery.validate.min.js')}}"></script>
<script src="{{theme_asset('assets/plugins/jquery-step/jquery.steps.min.js')}}"></script>
<script src="{{asset('public/assets/back-end/js/select2.min.js')}}"></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

@if($recaptcha['status'] == '1')
<script>
var onloadCallback = function() {
    let reg_id = grecaptcha.render('recaptcha_element_seller_regi', {
        'sitekey': '{{ $recaptcha['
        site_key '] }}'
    });
    let login_id = grecaptcha.render('recaptcha_element_seller_login', {
        'sitekey': '{{ $recaptcha['
        site_key '] }}'
    });

    $('#recaptcha_element_seller_regi').attr('data-reg-id', reg_id);
    $('#recaptcha_element_seller_login').attr('data-login-id', login_id);
};
</script>
@else
<script>
function re_captcha_seller_regi() {
    $url = "{{ URL('/seller/auth/code/captcha/') }}";
    $url = $url + "/" + Math.random() + '?captcha_session_id=default_recaptcha_id_seller_regi';

    document.getElementById('default_recaptcha_id_regi').src = $url;
    console.log('url: ' + $url);
}
</script>
@endif

<script>
var isUniqueEmailValidate = false

// Multi Step Form
var form = $("#seller-registration");
form.validate({
    errorPlacement: function errorPlacement(error, element) {
        element.before(error);
    },
    rules: {
        repeat_password: {
            equalTo: "#password"
        }
    }
});

// Form Wizard
form.children(".wizard").steps({
    headerTag: "h3",
    bodyTag: "section",
    onStepChanging: function(event, currentIndex, newIndex) {

        $('[href="#finish"]').addClass('disabled');

        $('#acceptTerms').click(function() {
            if ($(this).is(':checked')) {
                $('[href="#finish"]').removeClass('disabled');
            } else {
                $('[href="#finish"]').addClass('disabled');
            }
        });

        if (currentIndex > newIndex) {
            return true;
        }
        if (currentIndex < newIndex) {
            form.find('.body:eq(' + newIndex + ') label.error').remove();
            form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
        }

        console.log('currentIndex, newIndex', currentIndex, newIndex)

        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function(event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function(event, currentIndex) {
        @if($recaptcha['status'] == '1')
        if (currentIndex > 0) {
            var response = grecaptcha.getResponse($('#recaptcha_element_seller_regi').attr('data-reg-id'))
            if (response.length === 0) {
                toastr.error("{{ translate('Please_check_the_recaptcha') }}")
            } else {
                $('#seller-registration').submit()
            }
        }
        @else
        $('#seller-registration').submit()
        @endif
    }
});

$(document).on('submit', '#seller-registration', function(e) {
    e.preventDefault()
    var formdata = new FormData(this)
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: formdata,
        dataType: "json",
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
        },
        success: function(response) {
            if (response.status == true) {
                swal.fire(response.message, '', 'success').then(function() {
                    window.location.href = "{{ route('seller.auth.login') }}"
                })
            }
        },
        error: function(error) {
            console.log(error)
            let errorText = ""
            isUniqueEmailValidate = true
            $.each(error.responseJSON.errors, function(ind, elm) {
                errorText += `${elm[0]}\n`
            })
            swal.fire(errorText, '', 'error')
        }
    });
})

$(document).on('blur', 'input[name=email]', function() {
    $.ajax({
        type: "POST",
        url: "{{ route('seller.register.valildate-email') }}",
        data: {
            email: $(this).val()
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
        },
        success: function(response) {
            if (response.status == true) {

            }
        },
        error: function(error) {
            $.each(error.errors, function(ind, elm) {
                console.log('elm ', elm)
            })
        }
    });
})

$(document).on('change', 'select[name=country]', function() {
    let countryId = $(this).val()

    $.ajax({
        type: "POST",
        url: "{{ route('seller.state.list') }}",
        data: {
            country_id: countryId
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
        },
        dataType: "json",
        success: function(response) {
            console.log('response', response)
            if (response.status == true) {
                let html = "<option> Select </option>";
                $.each(response.data, function(ind, elm) {
                    html += `<option value="${elm.id}"> ${elm.name} </option>`;
                })
                $('select[name=state]').html(html)
            }
        }
    })
})

$(document).on('change', 'select[name=state]', function() {
    let countryId = $(this).val()

    $.ajax({
        type: "POST",
        url: "{{ route('seller.city.list') }}",
        data: {
            state_id: countryId
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
        },
        dataType: "json",
        success: function(response) {
            if (response.status == true) {
                let html = "<option> Select </option>";
                $.each(response.data, function(ind, elm) {
                    html += `<option value="${elm.id}"> ${elm.name} </option>`;
                })
                $('select[name=city]').html(html)
            }
        }
    })
})

</script>
<script>
    $(".js-example-responsive").select2({
        width: 'resolve'
    });

    // Add class to the element
    $(".js-example-responsive").addClass("balloon");

    

    $(document).on('blur','input[name=phone]', function() {
        let mobile = $(this).val()
        let otp = Math.floor(1000 + Math.random() * 9000);
        if(mobile.length > 9) {
            swal.fire(`OTP Sent success (${otp})`, '', 'success')
        } else {
            swal.fire(`Invalid Mobile No.`, '', 'error')
        }
    })

</script>

@endpush