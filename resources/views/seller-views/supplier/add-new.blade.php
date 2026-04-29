@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Coupon Add'))

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/coupon_setup.png')}}" alt="">
                {{\App\CPU\translate('Suppliers')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ isset($coupon->id) ? route('seller.supplier.update', $coupon->id) : route('seller.supplier.store-coupon') }}" method="post" class="form-submit-event">
                            @csrf

                            <div class="row">
                                
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name" class="title-color font-weight-medium d-flex">{{\App\CPU\translate('Name')}}</label>
                                    <input type="text" name="name" class="form-control" value="{{ $coupon->name ?? old('name') }}" id="name" placeholder="{{\App\CPU\translate('Enter')}} {{\App\CPU\translate('Name')}}" required>
                                </div>

                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="phone" class="title-color font-weight-medium d-flex">{{\App\CPU\translate('Phone')}}</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $coupon->phone ?? old('phone') }}" id="phone" placeholder="{{\App\CPU\translate('Enter')}} {{\App\CPU\translate('Phone')}}" required onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="10">
                                </div>

                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="email" class="title-color font-weight-medium d-flex">{{\App\CPU\translate('Email')}}</label>
                                    <input type="email" name="email" class="form-control" value="{{ $coupon->email ?? old('email') }}" id="email" placeholder="{{\App\CPU\translate('Enter')}} {{\App\CPU\translate('Email')}}" required>
                                </div>

                                <div class="col-md-4 col-lg-4 form-group">
                                    <label for="country" class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('Country') }}</label>
                                    <select name="country" class="js-example-responsive">
                                        <option value="">Select</option>
                                        @foreach($countries as $id => $country)
                                            <option value="{{ $id }}" {{ old('country') == $id || (isset($coupon->country_id) && $coupon->country_id == $id) ? "selected" : "" }}>{{ $country }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 col-lg-4 form-group">
                                    <label for="state" class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('State') }}</label>
                                    <select name="state" class="js-example-responsive">
                                        <option value="">Select</option>
                                        @foreach($states as $id => $state)
                                            <option value="{{ $id }}" {{ old('state') == $id || (isset($coupon->state_id) && $coupon->state_id == $id) ? "selected" : "" }}>{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 col-lg-4 form-group">
                                    <label for="city" class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('City') }}</label>
                                    <select name="city" class="js-example-responsive">
                                        <option value="">Select</option>
                                        @foreach($cities as $id => $city)
                                            <option value="{{ $id }}" {{ old('city') == $id || (isset($coupon->city_id) && $coupon->city_id == $id) ? "selected" : "" }}>{{ $city }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="address" class="title-color font-weight-medium d-flex">{{\App\CPU\translate('Address')}}</label>
                                    <input type="text" name="address" class="form-control" value="{{ $coupon->address ?? old('address') }}" id="address" placeholder="{{\App\CPU\translate('Enter')}} {{\App\CPU\translate('Address')}}" required>
                                </div>

                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="pin_code" class="title-color font-weight-medium d-flex">{{\App\CPU\translate('Pin Code')}}</label>
                                    <input type="text" name="pin_code" class="form-control" value="{{ $coupon->pincode ?? old('pin_code') }}" id="pin_code" placeholder="{{\App\CPU\translate('Pin Code')}}" required>
                                </div>

                            </div>

                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-10">
                                <button type="reset" class="btn btn-secondary px-4">{{\App\CPU\translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="mb-0 text-capitalize d-flex gap-2">
                                    {{ \App\CPU\translate('supplier_list') }}
                                    <span
                                        class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $coupons->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{\App\CPU\translate('Search by Title or Code or Discount Type')}}"
                                               value="{{ $search }}" aria-label="Search orders" required>
                                        <button type="submit"
                                                class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table {{ Session::get('direction') === 'rtl' ? 'text-right' : 'text-left' }}">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('name')}}</th>
                                <th>{{\App\CPU\translate('email')}}</th>
                                <th>{{\App\CPU\translate('phone')}}</th>
                                <th>{{\App\CPU\translate('address')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coupons as $k=>$coupon)
                                <tr>
                                    <td>{{$coupons->firstItem() + $k}}</td>
                                    <td>
                                        <div>{{substr($coupon['name'],0,20)}}</div>
                                    </td>
                                    <td class="text-capitalize">{{ $coupon['email'] }}</td>
                                    <td class="text-capitalize">{{ $coupon['phone'] }}</td>
                                    <td>{{ $coupon['address'] ?? "" }} {{ $coupon->city->name ?? "" }} {{ $coupon->state->name ?? "" }} {{ $coupon->country->name ?? "" }}</td>
                                    <td>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input"
                                                    onclick="location.href='{{route('seller.supplier.status', [ $coupon['id'], $coupon->status ? 0 : 1])}}'"
                                                    class="toggle-switch-input" {{ $coupon->status ? 'checked' : '' }}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-10 justify-content-center">

                                                <a class="btn btn-outline--primary btn-sm edit"
                                                   href="{{route('seller.supplier.update',[$coupon['id']])}}"
                                                   title="{{ \App\CPU\translate('Edit')}}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm delete"
                                                   href="javascript:"
                                                   onclick="form_alert('supplier-{{$coupon['id']}}','Want to delete this coupon ?')"
                                                   title="{{\App\CPU\translate('delete')}}">
                                                    <i class="tio-delete"></i>
                                                </a>
                                                <form action="{{route('seller.supplier.delete',[$coupon['id']])}}"
                                                      method="post" id="coupon-{{$coupon['id']}}">
                                                    @csrf @method('delete')
                                                </form>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="modal fade" id="quick-view" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered coupon-details" role="document">
                                <div class="modal-content" id="quick-view-modal">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$coupons->links()}}
                        </div>
                    </div>

                    @if(count($coupons)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160"
                                 src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                 alt="Image Description">
                            <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{asset('public/assets/back-end')}}/js/demo/datatables-demo.js"></script>

    <script>

        $(document).on('change', 'select[name=country]', function() {
            let country_id = $(this).val()
            $.ajax({
                type: "POST",
                url: "{{ route('seller.state.list') }}",
                data: {country_id:country_id},
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function (response) {
                    let html = `<option>Select</option>`
                    if(response.status) {
                        $.each(response.data, function(index, elm) {
                            html += `<option value="${elm.id}">${elm.name}</option>`
                        })
                    }
                    $('select[name=state]').html(html)
                }
            });
        })

        $(document).on('change', 'select[name=state]', function() {
            let state_id = $(this).val()
            $.ajax({
                type: "POST",
                url: "{{ route('seller.city.list') }}",
                data: {state_id:state_id},
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function (response) {
                    let html = `<option>Select</option>`
                    if(response.status) {
                        $.each(response.data, function(index, elm) {
                            html += `<option value="${elm.id}">${elm.name}</option>`
                        })
                    }
                    $('select[name=city]').html(html)
                }
            });
        })

    </script>
@endpush
