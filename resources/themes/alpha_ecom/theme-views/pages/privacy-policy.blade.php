


@extends('theme-views.layouts.app')

@section('title', translate('Privacy_Policy').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')

<!-- Main Content -->
<main class="main-content d-flex flex-column pb-3">
    <div class="page-title  py-5 __opacity-half breadcrumdiv">
        <div class="container">
            <h1 class="absolute-white text-center mt-5">{{translate('Privacy Policy')}}</h1>
            <nav aria-label="breadcrumb">
             <ol class="breadcrumb fs-12 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">{{ translate('home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{translate('Privacy Policy')}}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="card my-4 border-0">
            <div class="card-body p-lg-4 text-dark page-paragraph">
                {!!$privacy_policy->value!!}
            </div>
        </div>
    </div>
</main>
<!-- End Main Content -->

@endsection
<!-- data-bg-img="{{theme_asset('assets/img/media/page-title-bg.png')}}" -->