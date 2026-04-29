@extends('theme-views.layouts.register-app')

@section('title', $web_config['name']->value.' '.translate('Seller_Apply').' | '.$web_config['name']->value.'
'.translate(' Ecommerce'))

@section('content')

<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 py-3 mb-sm-5">
    @php($e_commerce_logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
    <a class="d-flex justify-content-center mb-5" href="javascript:">
        <img class="z-index-2" height="40" src="{{asset("storage/app/public/company/".$e_commerce_logo)}}" alt="Logo"
            onerror="this.src='{{asset('public/assets/back-end/img/400x400/img2.jpg')}}'">
    </a>
    <div class="container">
        <div class="card">
            <div class="card-body p-sm-4">
                <div class="row justify-content-between gy-4">
                    <div class="col-lg-12">
                        <section id="myDiv">
                            <div class="container mt-4">
                                <div class="col-lg-12">
                                    <div class="home-and-back-btn">
                                        <p class="mb-4">
                                            <a href="" class="me-2">Home</a>/ <a href=""
                                                class="ms-2"><span>Subscription</span></a>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="">
                                            <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
                                                @foreach($plans as $plan)
                                                    <div class="col-lg-4">
                                                        <div
                                                            class="card mb-4 rounded-3 shadow-sm subscription-plan-card">
                                                            <div class="card-header py-3">
                                                                <h4 class="my-0 fw-normal">{{ $plan->title }}</h4>
                                                                <h3 class="card-title pricing-card-title">{{ $plan->price }}<small class="text-muted fw-light">/{{ $plan->type }}</small>
                                                                </h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <ul class="list-unstyled mt-3 mb-4">
                                                                    <li>
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="16" height="16"
                                                                            viewBox="0 0 16 16" fill="none">
                                                                            <path
                                                                                d="M14.2507 8.75005C13.7507 11.25 11.8657 13.604 9.22071 14.13C7.9307 14.3869 6.59252 14.2303 5.39672 13.6824C4.20091 13.1346 3.20843 12.2234 2.56061 11.0786C1.91278 9.93389 1.64263 8.61393 1.78862 7.30672C1.93461 5.99951 2.4893 4.77167 3.37371 3.79805C5.18771 1.80005 8.25071 1.25005 10.7507 2.25005"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M5.75 7.75L8.25 10.25L14.25 3.75"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                        <p>Lorem Ipsum is simply dummy text</p>
                                                                    </li>
                                                                    <li>
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="16" height="16"
                                                                            viewBox="0 0 16 16" fill="none">
                                                                            <path
                                                                                d="M14.2507 8.75005C13.7507 11.25 11.8657 13.604 9.22071 14.13C7.9307 14.3869 6.59252 14.2303 5.39672 13.6824C4.20091 13.1346 3.20843 12.2234 2.56061 11.0786C1.91278 9.93389 1.64263 8.61393 1.78862 7.30672C1.93461 5.99951 2.4893 4.77167 3.37371 3.79805C5.18771 1.80005 8.25071 1.25005 10.7507 2.25005"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M5.75 7.75L8.25 10.25L14.25 3.75"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                        <p>Lorem Ipsum is simply dummy text</p>
                                                                    </li>
                                                                    <li>
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="16" height="16"
                                                                            viewBox="0 0 16 16" fill="none">
                                                                            <path
                                                                                d="M14.2507 8.75005C13.7507 11.25 11.8657 13.604 9.22071 14.13C7.9307 14.3869 6.59252 14.2303 5.39672 13.6824C4.20091 13.1346 3.20843 12.2234 2.56061 11.0786C1.91278 9.93389 1.64263 8.61393 1.78862 7.30672C1.93461 5.99951 2.4893 4.77167 3.37371 3.79805C5.18771 1.80005 8.25071 1.25005 10.7507 2.25005"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M5.75 7.75L8.25 10.25L14.25 3.75"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                        <p>Lorem Ipsum is simply dummy text</p>
                                                                    </li>
                                                                    <li>
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="16" height="16"
                                                                            viewBox="0 0 16 16" fill="none">
                                                                            <path
                                                                                d="M14.2507 8.75005C13.7507 11.25 11.8657 13.604 9.22071 14.13C7.9307 14.3869 6.59252 14.2303 5.39672 13.6824C4.20091 13.1346 3.20843 12.2234 2.56061 11.0786C1.91278 9.93389 1.64263 8.61393 1.78862 7.30672C1.93461 5.99951 2.4893 4.77167 3.37371 3.79805C5.18771 1.80005 8.25071 1.25005 10.7507 2.25005"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M5.75 7.75L8.25 10.25L14.25 3.75"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                        <p>Lorem Ipsum is simply dummy text</p>
                                                                    </li>
                                                                    <li>
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="16" height="16"
                                                                            viewBox="0 0 16 16" fill="none">
                                                                            <path
                                                                                d="M14.2507 8.75005C13.7507 11.25 11.8657 13.604 9.22071 14.13C7.9307 14.3869 6.59252 14.2303 5.39672 13.6824C4.20091 13.1346 3.20843 12.2234 2.56061 11.0786C1.91278 9.93389 1.64263 8.61393 1.78862 7.30672C1.93461 5.99951 2.4893 4.77167 3.37371 3.79805C5.18771 1.80005 8.25071 1.25005 10.7507 2.25005"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M5.75 7.75L8.25 10.25L14.25 3.75"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                        <p>Lorem Ipsum is simply dummy text</p>
                                                                    </li>
                                                                    <li>
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="16" height="16"
                                                                            viewBox="0 0 16 16" fill="none">
                                                                            <path
                                                                                d="M14.2507 8.75005C13.7507 11.25 11.8657 13.604 9.22071 14.13C7.9307 14.3869 6.59252 14.2303 5.39672 13.6824C4.20091 13.1346 3.20843 12.2234 2.56061 11.0786C1.91278 9.93389 1.64263 8.61393 1.78862 7.30672C1.93461 5.99951 2.4893 4.77167 3.37371 3.79805C5.18771 1.80005 8.25071 1.25005 10.7507 2.25005"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M5.75 7.75L8.25 10.25L14.25 3.75"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                        <p>Lorem Ipsum is simply dummy text</p>
                                                                    </li>
                                                                    <li>
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="16" height="16"
                                                                            viewBox="0 0 16 16" fill="none">
                                                                            <path
                                                                                d="M14.2507 8.75005C13.7507 11.25 11.8657 13.604 9.22071 14.13C7.9307 14.3869 6.59252 14.2303 5.39672 13.6824C4.20091 13.1346 3.20843 12.2234 2.56061 11.0786C1.91278 9.93389 1.64263 8.61393 1.78862 7.30672C1.93461 5.99951 2.4893 4.77167 3.37371 3.79805C5.18771 1.80005 8.25071 1.25005 10.7507 2.25005"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M5.75 7.75L8.25 10.25L14.25 3.75"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                        <p>Lorem Ipsum is simply dummy text</p>
                                                                    </li>
                                                                    <li>
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="16" height="16"
                                                                            viewBox="0 0 16 16" fill="none">
                                                                            <path
                                                                                d="M14.2507 8.75005C13.7507 11.25 11.8657 13.604 9.22071 14.13C7.9307 14.3869 6.59252 14.2303 5.39672 13.6824C4.20091 13.1346 3.20843 12.2234 2.56061 11.0786C1.91278 9.93389 1.64263 8.61393 1.78862 7.30672C1.93461 5.99951 2.4893 4.77167 3.37371 3.79805C5.18771 1.80005 8.25071 1.25005 10.7507 2.25005"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M5.75 7.75L8.25 10.25L14.25 3.75"
                                                                                stroke="#0A9494" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                        <p>Lorem Ipsum is simply dummy text</p>
                                                                    </li>
                                                                </ul>
                                                                <button type="button"
                                                                    class="w-100 btn btn-lg btn-primary">Get
                                                                    started</button>
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

@endpush