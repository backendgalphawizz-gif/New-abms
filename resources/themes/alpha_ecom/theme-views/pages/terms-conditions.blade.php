@extends('theme-views.layouts.app')

@section('title', translate('Terms_&_Conditions').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')

<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 pb-3">
    <div class="page-title  py-5 __opacity-half breadcrumdiv" >
        <div class="container">
            <h3 class="absolute-white text-center mt-5">{{translate('Terms_&_Conditions')}}</h3>
            <nav aria-label="breadcrumb">
             <ol class="breadcrumb fs-12 mb-0 mx-auto">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">{{ translate('home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{translate('Terms_&_Conditions')}}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="card my-4 border-0">
            <div class="card-body p-lg-4 text-dark page-paragraph">
                {!!$terms_condition->value!!}
            </div>
        </div>
    </div>
</main>
<!-- End Main Content -->

@endsection
<!-- data-bg-img="{{theme_asset('assets/img/media/page-title-bg.png')}}" -->