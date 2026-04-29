@extends('theme-views.layouts.app')

@section('title', translate('My_Address').' | '.$web_config['name']->value.' '.translate('Ecommerce'))

@push('css_or_js')
<style>
    .locationInput {
        position: relative;
    }
    .locationInput img {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
    }
    .location-input {
        padding-left: 35px;
    }
</style>
@endpush

@section('content')
<main class="main-content d-flex flex-column gap-3 py-3">
    <div class="container">
        <div class="row g-3">
            <div class="bread__crum">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb fs-12 mb-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">{{ translate('home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{translate('Account')}}</li>
                    </ol>
                </nav>
            </div>

            @include('theme-views.partials._profile-aside')

            <div class="col-lg-9">
                <div class="card h-100">
                    <div class="card-header"><h5>{{ translate('Edit Address') }}</h5></div>
                    <div class="card-body p-lg-4">
                        <form action="{{ route('address-update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="return" value="{{ request()->query('return') }}">
                            <input type="hidden" name="id" value="{{ $shippingAddress->id }}">

                            <div class="row gy-4">
                                <!-- Contact Person -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="contact_person">{{ translate('Contact_Person') }}</label>
                                        <input type="text" id="contact_person" name="name" class="form-control" value="{{ $shippingAddress->contact_person_name }}" required placeholder="{{ translate('Ex:_Jhon_Doe') }}">
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone_primary">{{ translate('Phone') }}</label>
                                        <input type="tel" id="phone_primary" class="form-control" name="phone" value="{{ $shippingAddress->phone }}" required pattern="[0-9]{10}" placeholder="{{ translate('Ex:01xxxxxxxxx') }}">
                                    </div>
                                </div>

                                <!-- Alternate Phone -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone_alternate">{{ translate('Alternate_Phone') }}</label>
                                        <input type="tel" id="phone_alternate" class="form-control" name="alt_phone" value="{{ $shippingAddress->alt_phone }}" pattern="[0-9]{10}"  placeholder="{{ translate('Ex:_01xxxxxxxxx') }}">
                                    </div>
                                </div>

                                <!-- Search Address (Google Autocomplete) -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address_search">{{ translate('Search_Address') }}</label>
                                        <div class="locationInput">
                                            <img src="{{ theme_asset('assets/images/location-pin.png') }}" alt="">
                                            <input type="text" id="address_search" class="form-control location-input" placeholder="{{ translate('Search your location') }}" value="{{ $shippingAddress->address }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Confirm Address -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address_confirm">{{ translate('Confirm_Address') }}</label>
                                        <input type="text" id="address_confirm" name="address" class="form-control" placeholder="{{ translate('Enter full address here') }}" value="{{ $shippingAddress->address }}" required>
                                    </div>
                                </div>

                                <!-- Address Line -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address_area">{{ translate('Address_Line/Area_1') }}</label>
                                        <input type="text" id="address_area" name="address_line_1" class="form-control" placeholder="{{ translate('Enter area or landmark') }}" value="{{ $shippingAddress->address1 ?? '' }}">
                                    </div>
                                </div>

                                <!-- State -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state_input">{{ translate('State') }}</label>
                                        <input type="text" id="state_input" name="state" class="form-control" value="{{ $shippingAddress->state }}" required placeholder="{{ translate('Enter state') }}">
                                    </div>
                                </div>

                                <!-- City -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city_input">{{ translate('City') }}</label>
                                        <input type="text" id="city_input" name="city" class="form-control" value="{{ $shippingAddress->city }}" required placeholder="{{ translate('Enter city') }}">
                                    </div>
                                </div>

                                <!-- Zip Code -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="zip_input">{{ translate('Zip_Code') }}</label>
                                        <input type="text" id="zip_input" name="zip" class="form-control" value="{{ $shippingAddress->zip }}" required placeholder="{{ translate('Enter zip code') }}">
                                    </div>
                                </div>

                                <!-- Hidden Inputs -->
                                <input type="hidden" id="latitude_input" name="latitude" value="{{ $shippingAddress->latitude ?? 0 }}">
                                <input type="hidden" id="longitude_input" name="longitude" value="{{ $shippingAddress->longitude ?? 0 }}">
                                <input type="hidden" id="area_input" name="area" value="{{ $shippingAddress->area ?? '' }}">

                                <!-- Buttons -->
                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-3 justify-content-end">
                                        <button type="reset" class="btn btn-secondary">{{ translate('Reset') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ translate('Update_Address') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col-lg-9 -->
        </div> <!-- row -->
    </div> <!-- container -->
</main>
@endsection

@push('script')
<script src="https://maps.google.com/maps/api/js?libraries=places&key=AIzaSyAsMSsS1jOb_HJvHbTXubVhROavNlW7baE"></script>
<script type="text/javascript">
    google.maps.event.addDomListener(window, 'load', function () {
        var input = document.getElementById('address_search');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place.geometry) return;

            var address = place.formatted_address;
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();

            var area = "", city = "", state = "", zip = "";

            place.address_components.forEach(function (component) {
                var types = component.types;
                if (types.includes("sublocality") || types.includes("neighborhood")) {
                    area = component.long_name;
                }
                if (types.includes("locality")) {
                    city = component.long_name;
                }
                if (types.includes("administrative_area_level_1")) {
                    state = component.long_name;
                }
                if (types.includes("postal_code")) {
                    zip = component.long_name;
                }
            });

            // Fill relevant fields
            document.getElementById('address_search').value = address;
            document.getElementById('address_confirm').value = address;
            document.getElementById('latitude_input').value = latitude;
            document.getElementById('longitude_input').value = longitude;
            document.getElementById('area_input').value = area;
            document.getElementById('city_input').value = city;
            document.getElementById('state_input').value = state;
            document.getElementById('zip_input').value = zip;
        });
    });
</script>
@endpush
