@extends('theme-views.layouts.app')

@section('title', translate('My_Address').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/select2/css/select2.min.css') }}">
    <style>
        .select2{
            max-width: 100%;
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
        }
    </style>
@endpush

@section('content')
    <!-- Main Content -->
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
                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')

                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>Edit Address</h5>
                        </div>
                        <div class="card-body p-lg-4">
                            <div class="mt-4">
                                <form action="{{route('address-update')}}" method="post">
                                    @csrf
                                    <div class="row gy-4">
                                        <!-- <div class="col-md-12"> -->
                                            <input type="hidden" name="id" value="{{$shippingAddress->id}}">
                                            <!-- <div class=""> -->
                                                <!-- <h6 class="fw-semibold text-muted">{{translate('Choose_Label')}}</h6> -->
                                                <!-- <ul class="option-select-btn  style--two "> -->
                                                    <!-- <li>
                                                        <label>
                                                            <input type="radio" name="addressAs"  value="home" {{$shippingAddress->address_type == 'home' ? 'checked':''}}>
                                                            <span><i class="bi bi-house"></i></span> 
                                                            {{translate('Shipping_Address')}}
                                                        </label>
                                                    </li> -->
                                                    <!-- <li>
                                                        <label>
                                                            <input type="radio" name="addressAs"  value="office" {{$shippingAddress->address_type == 'office' ? 'checked':''}}>
                                                            <span><i class="bi bi-briefcase"></i></span>
                                                            {{translate('Office')}}
                                                        </label>
                                                    </li> -->
                                                    <!-- <li class="d-none">
                                                        <label>
                                                            <input type="radio" name="addressAs"  value="permanent" {{$shippingAddress->address_type == 'permanent' ? 'checked':''}}>
                                                            <span><i class="bi bi-paperclip"></i></span>
                                                            {{translate('Permanent')}}
                                                        </label>
                                                    </li> -->

                                                    <!-- <li>
                                                        <label>
                                                            <input type="radio" name="addressAs"  value="other" {{ $shippingAddress->address_type }} {{$shippingAddress->address_type == 'other' ? 'checked':''}}>
                                                            <span><i class="bi bi-paperclip"></i></span>
                                                            {{translate('other')}}
                                                        </label>
                                                    </li> -->
                                                    
                                                <!-- </ul> -->
                                            <!-- </div> -->
                                        <!-- </div> -->
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="name">{{translate('Contact_Person')}}</label>
                                                <input type="text" id="name" name="name" class="form-control" value="{{$shippingAddress['contact_person_name']}}" placeholder="{{translate('Ex:_Jhon_Doe')}}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone2">{{translate('Phone')}}</label>
                                                <input type="tel" id="phone" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="10" class="form-control" name="phone" value="{{ $shippingAddress['phone'] }}" placeholder="{{translate('Ex:01xxxxxxxxx')}}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label>{{translate('Alternate_Phone')}}</label>
                                                <input type="tel" class="form-control " onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="10" name="alt_phone" required placeholder="{{translate('Ex:_01xxxxxxxxx')}}" value="{{$shippingAddress['alt_phone']}}">
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="address">{{translate('Address Line 1')}}</label>
                                                <textarea name="address" id="address" rows="2" class="form-control" placeholder="{{translate('House No, Building name')}}" required>{{$shippingAddress['address']}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="address">{{translate('Address Line 2')}}</label>
                                                <textarea name="address1" id="address1" rows="2" class="form-control" placeholder="{{translate('Road Name, Area Colony')}}" required>{{$shippingAddress['address1']}}</textarea>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="country">{{translate('Country')}}</label>
                                                <select name="country" id="country"  class="form-select select2 ">
                                                    <option value="" disabled selected>{{translate('Select_Country')}}</option>
                                                    @if($country_restrict_status)
                                                        @foreach($delivery_countries as $country)
                                                            <option value="{{$country['name']}}" {{ $country['name'] == $shippingAddress->country? 'selected' : ''}}>{{$country['name']}}</option>
                                                        @endforeach
                                                    @else
                                                        @foreach(COUNTRIES as $country)
                                                            <option value="{{ $country['name'] }}" {{ $shippingAddress->country == $country['name']? 'selected' : '' }}>{{ $country['name'] }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div> -->
                                           <input type="hidden" name="country" value="India">
                                           <input type="hidden" name="zip" value="101">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="state">{{translate('State')}}</label>
                                                @php($country=\App\Model\Country::where('name', $shippingAddress->country)->first())
                                                @php($state=\App\Model\State::where('name', $shippingAddress->state)->first())
                                                @php($states = $country->states ?? [])
                                                @php($cities = $state->cities ?? [])
                                                <select name="state" id="state" class="form-control" required>
                                                    <option value="">{{translate('Select_State')}}</option>
                                                    @foreach($states as $state)
                                                        <option value="{{ $state->name }}" data-id="{{$state->id}}" {{ $state->name == $shippingAddress->state ? 'selected' : '' }}>{{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="city">{{translate('City')}}</label>
                                                <select name="city" id="city" class="form-control" required>
                                                    <option value=""  selected>{{translate('Select_City')}}</option>
                                                    @foreach($cities as $city)
                                                        <option value="{{ $city->name }}" data-id="{{$city->id}}" {{ $city->name == $shippingAddress->city ? 'selected' : '' }}>{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="zip-code">{{translate('Zip_Code')}}</label>
                                                @if($zip_restrict_status)
                                                    <select name="zip" class="form-control select2 select_picker" data-live-search="true" id="" required>
                                                        @foreach($delivery_zipcodes as $zip)
                                                            <option value="{{ $zip->zipcode }}" {{ $zip->zipcode == $shippingAddress->zip? 'selected' : ''}}>{{ $zip->zipcode }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="6" maxlength="6" type="text" id="zip_code" name="zip" value="{{$shippingAddress->zip}}" required>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="col-md-6 d-none">
                                            <h6 class="fw-semibold text-muted">{{translate('Choose_Address_Type')}}</h6>
                                            <div class="d-flex flex-wrap style--two gap-4">
                                                <div>
                                                    <label class="d-flex align-items-baseline gap-2 cursor-pointer">
                                                        <input type="radio" name="is_billing" value="1" {{ $shippingAddress->is_billing == '1' ? 'checked' : ''}} >
                                                        {{translate('Billing_Address')}}
                                                    </label>
                                                </div>
                                                <div>
                                                    <label class="d-flex align-items-baseline gap-2 cursor-pointer">
                                                        <input type="radio" name="is_billing" value="0" {{ $shippingAddress->is_billing == '0' ? 'checked' : ''}} >
                                                        {{translate('Shipping_Address')}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-none">
                                            <div class="mb-3 ">
                                                <input id="pac-input" class="controls rounded __inline-46 " title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}"/>
                                                <div class="dark-support rounded w-100 __h-14rem" id="location_map_canvas"></div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <input type="hidden" id="latitude"
                                               name="latitude" class="form-control d-inline"
                                               placeholder="Ex : -94.22213" value="{{$shippingAddress->latitude??0}}" required readonly>
                                            <input type="hidden"
                                               name="longitude" class="form-control"
                                               placeholder="Ex : 103.344322" id="longitude" value="{{$shippingAddress->longitude??0}}" required readonly>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
                                                <label class="custom-checkbox"></label>

                                                <div class="d-flex justify-content-end gap-3">
                                                    <button type="reset" class="btn btn-secondary">{{translate('Reset')}}</button>
                                                    <button type="submit" class="btn btn-primary">{{translate('Update_Address')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection
@push('script')
    <script src="{{ theme_asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select_picker').select2();
        });

        $(".select_picker").select2({
            theme: "classic"
        });
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&callback=initAutocomplete&libraries=places&v=3.49" defer></script>
    <script>
        function initAutocomplete() {
            var myLatLng = {
                lat: "", // {{$shippingAddress->latitude??'-33.8688'}},
                lng: "" // {{$shippingAddress->longitude??'151.2195'}}
            };

            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: {
                    lat: "", //  {{$shippingAddress->latitude??'-33.8688'}},
                    lng: "" // {{$shippingAddress->longitude??'151.2195'}}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap( map );
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
                marker.setPosition( latlng );
                map.panTo( latlng );

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').value = results[1].formatted_address;
                            console.log(results[1].formatted_address);
                        }
                    }
                });
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });

                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on('ready', function () {
            initAutocomplete();
        });
        $(document).on("keydown", "input", function(e) {
            if (e.which==13) e.preventDefault();
        });

        $(document).on('change','select[name=country]', function() {
            let id = $('select[name=country] option:selected').data('id')
            $.ajax({
                type: "POST",
                url: "{{ route('seller.state.list') }}",
                data: {
                    country_id:id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
                },
                dataType: "json",
                success: function (response) {
                    console.log('response ', response)
                    if(response.status) {
                        let html = "<option value=''>State</option>"
                        $.each(response.data, function(ind,elm) {
                            html += `<option value="${elm.name}" data-id="${elm.id}">${elm.name}</option>`
                        })
                        $('select[name=state]').html(html)
                    }
                }
            });
        })

        $(document).on('change','select[name=state]', function() {
            let id = $('select[name=state] option:selected').data('id')
            console.log('Selected city ID:', id); 
            $.ajax({
                type: "POST",
                url: "{{ route('seller.city.list') }}",
                data: {
                    state_id:id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
                },
                dataType: "json",
                success: function (response) {
                    console.log('response ', response)
                    if(response.status) {
                        let html = "<option value=''>City</option>"
                        $.each(response.data, function(ind,elm) {
                            html += `<option value="${elm.name}">${elm.name}</option>`
                        })
                        $('select[name=city]').html(html)
                    }
                }
            });
        })

        $(document).on('change', 'select[name=city]', function() {
     let id = $('select[name=city] option:selected').data('id')
        // console.log('Selected city ID:', id); 

        $.ajax({
            type: "POST",
            url: "{{ route('seller.area.list') }}",
            data: {
                city_id:id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
            },
            dataType: "json",
            success: function(response) {
                console.log('Area response:', response); 
                if (response.status && response.data.length > 0) {
                    let html = "<option value=''>Select Area</option>";
                    $.each(response.data, function(ind, elm) {
                        html += `<option value="${elm.id}">${elm.name}</option>`;
                    });
                    $('select[name=area]').html(html);
                } else {
                    $('select[name=area]').html("<option value=''>No Areas Available</option>");
                }
            }
        });
    });

    </script>
@endpush
