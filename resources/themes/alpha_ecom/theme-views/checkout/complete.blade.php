@extends('theme-views.layouts.app')

@section('title', translate('Order Complete').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
<main class="main-content d-flex flex-column gap-3 py-3 mb-5">
    <div class="container">
        <div class="col-md-6 mx-auto pt-md-5">
            <div class="order__successfully__placed">
                <div class="card-body p-md-5">
                    <div class="text-center d-flex flex-column align-items-center gap-3">
                           <div class="successfully-msg-image">
                                <img width="300" src="{{ theme_asset('assets/img/icons/confirm-msg.gif') }}" class="dark-support" alt="" />
                           </div>
                        <h3>{{ translate('Your_Order_Placed_Successfully') }}</h3>
                        <p class="text-muted">
                            {{ translate('thank_you_for_your_order') }}! {{ translate('your_order_is_being_processed_and_will_be_completed_within_3-6_hours') }}. {{
                            translate('you_will_receive_an_email_confirmation_when_your_order_is_completed') }}.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{route('home')}}" class="btn-add-to-cardproduct">{{ translate('Continue_Shopping') }}</a>
                            <a href="{{route('account-oder')}}" class="btn btn-primary">{{ translate('Track_Order') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@push('script')

@endpush
