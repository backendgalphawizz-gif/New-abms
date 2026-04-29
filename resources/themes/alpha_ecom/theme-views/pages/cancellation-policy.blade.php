@extends('theme-views.layouts.app')

@section('title', translate('Cancellation_Policy').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')

<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 pb-3">
    <div class="page-title py-5 __opacity-half breadcrumdiv" >
        <div class="container">
            <h3 class="absolute-white text-center mt-5">{{translate('Cancellation_Policy')}}</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb fs-12 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">{{ translate('home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{translate('Cancellation_Policy')}}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="card my-4 border-0">
            <div class="card-body p-lg-4 text-dark page-paragraph">
                {!! $cancellation_policy !!}
            </div>
        </div>
    </div>
</main>
<!-- End Main Content -->

@endsection
<!-- style="--opacity: .5" data-bg-img="{{theme_asset('assets/img/media/page-title-bg.png')}}" -->