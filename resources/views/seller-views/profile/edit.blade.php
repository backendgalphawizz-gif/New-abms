@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Profile Settings'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<!-- Content -->
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="mb-3">
        <div class="row gy-2 align-items-center">
            <div class="col-sm">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img src="{{asset('/public/assets/back-end/img/support-ticket.png')}}" alt="">
                    {{\App\CPU\translate('Settings')}}
                </h2>
            </div>
            <!-- End Page Title -->

            <div class="col-sm-auto">
                <a class="btn btn--primary" href="{{route('seller.dashboard.index')}}">
                    <i class="tio-home mr-1"></i> {{\App\CPU\translate('Dashboard')}}
                </a>
            </div>
        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->

    <div class="row">
        <div class="col-lg-3">
            <!-- Navbar -->
            <div class="navbar-vertical navbar-expand-lg mb-3 mb-lg-5">
                <!-- Navbar Toggle -->
                <button type="button" class="navbar-toggler btn btn-block btn-white mb-3"
                    aria-label="Toggle navigation" aria-expanded="false" aria-controls="navbarVerticalNavMenu"
                    data-toggle="collapse" data-target="#navbarVerticalNavMenu">
                    <span class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0">{{\App\CPU\translate('Nav menu')}}</span>

                        <span class="navbar-toggle-default">
                            <i class="tio-menu-hamburger"></i>
                        </span>

                        <span class="navbar-toggle-toggled">
                            <i class="tio-clear"></i>
                        </span>
                    </span>
                </button>
                <!-- End Navbar Toggle -->

                <div id="navbarVerticalNavMenu" class="collapse navbar-collapse">
                    <!-- Navbar Nav -->
                    <ul id="navbarSettings"
                        class="js-sticky-block js-scrollspy navbar-nav navbar-nav-lg nav-tabs card card-navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:" id="generalSection">
                                <i class="tio-user-outlined nav-icon"></i>{{\App\CPU\translate('Basic')}} {{\App\CPU\translate('information')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:" id="bussinessSection">
                                <i class="tio-user-outlined nav-icon"></i>{{\App\CPU\translate('Bussiness')}} {{\App\CPU\translate('information')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:" id="passwordSection">
                                <i class="tio-lock-outlined nav-icon"></i> {{\App\CPU\translate('Password')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:" id="addressSection">
                                <i class="tio-user-outlined nav-icon"></i>{{\App\CPU\translate('Address')}} {{\App\CPU\translate('information')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:" id="bankSection">
                                <i class="tio-user-outlined nav-icon"></i>{{\App\CPU\translate('Bank')}} {{\App\CPU\translate('information')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:" id="otherSection">
                                <i class="tio-user-outlined nav-icon"></i>{{\App\CPU\translate('Other')}} {{\App\CPU\translate('setting')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Navbar Nav -->
                </div>
            </div>
            <!-- End Navbar -->
        </div>

        <div class="col-lg-9">
            <form action="{{route('seller.profile.update',[$data->id])}}" method="post" enctype="multipart/form-data" id="seller-profile-form">
                @csrf
                <!-- Card -->
                <div class="card mb-3 mb-lg-5" id="generalDiv">
                    <!-- Profile Cover -->
                    <div class="profile-cover">
                        @php($shop_banners = $shop_banner ? asset('storage/app/public/shop/banner/'.$shop_banner) : 'https://images.pexels.com/photos/866398/pexels-photo-866398.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1')

                        <div class="profile-cover-img-wrapper" style="background-image: url({{ $shop_banners }}); background-repeat: no-repeat; background-size: cover;">
                        </div>
                    </div>
                    <!-- End Profile Cover -->

                    <!-- Avatar -->
                    <label
                        class="avatar avatar-xxl avatar-circle avatar-border-lg avatar-uploader profile-cover-avatar"
                        for="avatarUploader">
                        <img id="viewer"
                            onerror="this.src='{{asset('public/assets/back-end/img/160x160/img1.jpg')}}'"
                            class="avatar-img"
                            src="{{asset('storage/app/public/seller')}}/{{$data->image}}"
                            alt="Image">
                    </label>
                    <!-- End Avatar -->
                </div>
                <!-- End Card -->

                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('Basic')}} {{\App\CPU\translate('information')}} (Referral Code: {{$data->shop->refferral ?? ''}})</h5>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <!-- Form -->
                        <!-- Form Group -->
                        <div class="row">
                            <label for="firstNameLabel" class="col-sm-3 col-form-label input-label">{{\App\CPU\translate('Full')}} {{\App\CPU\translate('Name')}}
                                <i class="tio-help-outlined text-body ml-1" data-toggle="tooltip" data-placement="top" title="Display name"></i>
                            </label>

                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="title-color d-none">{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="f_name" value="{{$data->f_name}}" placeholder="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Name')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Name')}}" class="form-control"
                                            id="name"
                                            required>
                                    </div>
                                    <!-- <div class="col-md-6 mb-3 d-none">
                                            <label for="name" class="title-color">{{\App\CPU\translate('Last')}} {{\App\CPU\translate('Name')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="l_name" value="{{$data->l_name}}" class="form-control"
                                                id="name"
                                                required>
                                        </div> -->
                                </div>
                            </div>
                        </div>
                        <!-- End Form Group -->

                        <!-- Form Group -->
                        <div class="row">
                            <label for="phoneLabel"
                                class="col-sm-3 col-form-label input-label">{{\App\CPU\translate('Phone')}} </label>

                            <div class="col-sm-9 mb-3">
                                <div class="d-none text-info mb-2">( * {{\App\CPU\translate('country_code_is_must')}} {{\App\CPU\translate('like_for_BD_880')}} )</div>
                                <input type="text" class="js-masked-input form-control" placeholder="{{\App\CPU\translate('password')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Password')}}" name="phone" id="phoneLabel"
                                    placeholder="+x(xxx)xxx-xx-xx"
                                    aria-label="+(xxx)xx-xxx-xxxxx"
                                    value="{{$data->phone}}"
                                    data-hs-mask-options='{
                                            "template": "+(880)00-000-00000"
                                        }'
                                    onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                    minlength="10" maxlength="10"
                                    required>
                            </div>
                        </div>
                        <!-- End Form Group -->

                        <div class="row form-group">
                            <label for="newEmailLabel"
                                class="col-sm-3 col-form-label input-label">{{\App\CPU\translate('Email')}}</label>

                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" id="newEmailLabel"
                                    value="{{$data->email}}"
                                    placeholder="{{\App\CPU\translate('Enter new email address')}}" aria-label="Enter new email address" >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-form-label">
                            </div>
                            <div class="form-group col-md-9" id="select-img">
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label"
                                        for="customFileUpload">{{\App\CPU\translate('image')}} {{\App\CPU\translate('Upload')}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 pt-2 d-flex justify-content-end">
                            <button type="button"
                                onclick="{{env('APP_MODE')!='demo'?"form_alert('seller-profile-form','Want to update seller info ?')":"call_demo()"}}"
                                class="btn btn--primary">{{\App\CPU\translate('Save changes')}}
                            </button>
                        </div>

                        <!-- End Form -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </form>

            <!-- Card -->
            <div id="bussinessSection" class="card mb-3 mb-lg-5">
                <div class="card-header" id="bussinessDiv">
                    <h5 class="mb-0">{{\App\CPU\translate('Bussiness')}} {{\App\CPU\translate('Information')}}</h5>
                </div>

                <!-- Body -->
                <div class="card-body">
                    <form id="shopBussinessProfile" action="{{route('seller.shop.update',[$data->shop->id])}}" method="post" enctype="multipart/form-data" id="seller-profile-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('Bussiness Email ID')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('bussiness_email')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('bussiness_email')}}" value="{{ $data->shop->email }}" name="bussiness_email" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('Bussiness Phone')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('Bussiness Phone')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Bussiness Phone')}}" value="{{ $data->shop->contact ?? '' }}" name="contact" class="form-control">
                            </div>
                            <!-- <div class="col-lg-6 d-none">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Bussiness Type')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('bussiness_type')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('bussiness_type')}}" value="{{ $data->shop->bussiness_type ?? '' }}" name="bussiness_type" class="form-control">
                                </div> -->
                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('Company Name Or Bussiness Name')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('Company_Name_Or_Bussiness_Name')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Company_Name_Or_Bussiness_Name')}}" value="{{ $data->shop->name ?? '' }}" name="name" class="form-control">
                            </div>
                            <!-- <div class="col-lg-6 d-none">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Bussiness Registeration Number (If Applicable)')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Bissiness_registeration_number')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Bissiness_registeration_number')}}" value="{{ $data->shop->registeration_number ?? '' }}" name="bissiness_registeration_number" class="form-control">
                                </div> -->
                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('GSTIN')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('GSTIN')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('GSTIN')}}" name="gst_in" value="{{ $data->shop->gst_in ?? '' }}" class="form-control">
                            </div>
                            <!-- <div class="col-lg-6 d-none">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Permanent Account Number (PAN)')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Permanent Account Number (PAN)')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Permanent Account Number (PAN)')}}" value="{{ $data->shop->tax_identification_number ?? '' }}" name="tax_identification_number" class="form-control">
                                </div> -->
                            <!-- <div class="col-lg-6 d-none">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Website')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Website')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Website')}}" name="website" value="{{ $data->shop->website_link ?? '' }}" class="form-control">
                                </div> -->
                            <div class="col-lg-6 mt-2">
                                <div class="form-group">
                                    <label for="name" class="title-color">{{\App\CPU\translate('Upload Logo')}} {{\App\CPU\translate('image')}}</label>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img class="upload-img-view" id="viewer"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/shop/'.$data->shop->image)}}" alt="Product thumbnail" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 pt-2 d-flex justify-content-end">
                            <button type="button"
                                onclick="{{env('APP_MODE')!='demo'?"form_alert('shopBussinessProfile','Want to update Bussiness Information ?')":"call_demo()"}}"
                                class="btn btn--primary">{{\App\CPU\translate('Save')}} {{\App\CPU\translate('changes')}}</button>
                        </div>
                    </form>
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->

            <!-- Card -->
            <!-- <div id="passwordDiv" class="card mb-3 mb-lg-5">
                <div class="card-header">
                    <h5 class="mb-0">{{\App\CPU\translate('Change')}} {{\App\CPU\translate('your')}} {{\App\CPU\translate('password')}}</h5>
                </div>

                
                <div class="card-body">
                  
                    <form id="changePasswordForm" action="{{route('seller.profile.settings-password')}}"
                        method="post"
                        enctype="multipart/form-data">
                        @csrf

                 
                        <div class="row form-group">
                            <label for="newPassword"
                                class="col-sm-3 mt-2 col-form-label input-label"> {{\App\CPU\translate('New')}}
                                {{\App\CPU\translate('password')}}</label>

                            <div class="col-sm-9">
                                <input type="password" class="js-pwstrength form-control" name="password"
                                    id="newPassword" placeholder="{{\App\CPU\translate('Enter new password')}}"
                                    aria-label="Enter new password"
                                    data-hs-pwstrength-options='{
                                           "ui": {
                                             "container": "#changePasswordForm",
                                             "viewports": {
                                               "progress": "#passwordStrengthProgress",
                                               "verdict": "#passwordStrengthVerdict"
                                             }
                                           }
                                         }'>

                                <p id="passwordStrengthVerdict" class="form-text mb-2"></p>

                                <div id="passwordStrengthProgress"></div>
                            </div>
                        </div>
                    

                       
                        <div class="row form-group">
                            <label for="confirmNewPasswordLabel"
                                class="col-sm-3 mt-2 col-form-label input-label pt-0"> {{\App\CPU\translate('Confirm')}}
                                {{\App\CPU\translate('password')}} </label>

                            <div class="col-sm-9">
                                <div class="mb-3">
                                    <input type="password" class="form-control" name="confirm_password"
                                        id="confirmNewPasswordLabel" placeholder="{{\App\CPU\translate('Confirm your new password')}}"
                                        aria-label="Confirm your new password">
                                </div>
                            </div>
                        </div>
                        

                        <div class="col-12 pt-2 d-flex justify-content-end">
                            <button type="button"
                                onclick="{{env('APP_MODE')!='demo'?"form_alert('changePasswordForm','Want to update admin password ?')":"call_demo()"}}"
                                class="btn btn--primary">{{\App\CPU\translate('Save')}} {{\App\CPU\translate('changes')}}</button>
                        </div>
                    </form>
                  
                </div>
                
            </div> -->
            <!-- End Card -->

            <!-- Card -->
            <div id="addressSectionDiv" class="card mb-3 mb-lg-5">
                <div class="card-header">
                    <h5 class="mb-0">{{\App\CPU\translate('Address')}} {{\App\CPU\translate('Information')}}</h5>
                </div>

                <!-- Body -->
                <div class="card-body">
                    <form id="editShopAddressForm" action="{{route('seller.shop.update',[$data->shop->id])}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('Store Address')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('Store Address')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Store Address')}}" name="address" value="{{ $data->shop->address ?? '' }}" class="form-control">
                            </div>

                            <!-- Hidden Country Field -->
                            <input type="hidden" name="country" value="{{ $data->country ?? old('country') }}">

                            <!-- State Dropdown -->
                            <div class="col-lg-6">
                                <label class="mt-2" for="state">{{ \App\CPU\translate('State') }}</label>
                                <select name="state" class="form-control" id="state">
                                    <option value="">-- Select state --</option>
                                    @foreach ($states as $value)
                                        <option value="{{ $value->id }}" 
                                            {{ old('state', $data->shop->state ?? '') == $value->id ? 'selected' : '' }}>
                                            {{ $value->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- City Dropdown -->
                            <div class="col-lg-6">
                                <label class="mt-2" for="city">{{ \App\CPU\translate('City') }}</label>
                                <select name="city" id="city" class="form-control">
                                    <option value="">-- Select city --</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}" 
                                            {{ old('city', $data->shop->city ?? '') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Area Dropdown -->
                            <div class="col-lg-6">
                                <label class="mt-2">{{ \App\CPU\translate('Area') }}</label>
                                <select name="area" id="area" class="form-control">
                                    <option value="">-- Select Area --</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" 
                                            {{ old('area', $data->shop->area ?? '') == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- <div class="col-lg-6 d-none">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Postal/Zip Code')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Postal/Zip Code')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Postal/Zip Code')}}" name="pin_code" value="{{ $data->shop->pincode ?? '' }}" class="form-control">
                                </div> -->
                            <div class="col-12 pt-2 d-flex justify-content-end">
                                <button type="button"
                                    onclick="{{env('APP_MODE')!='demo'?"form_alert('editShopAddressForm','Want to update shop profile ?')":"call_demo()"}}"
                                    class="btn btn--primary">{{\App\CPU\translate('Save')}} {{\App\CPU\translate('changes')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div id="bankSectionF" class="card mb-3 mb-lg-5">
                <div class="card-header">
                    <h5 class="mb-0">{{\App\CPU\translate('Bank')}} {{\App\CPU\translate('Information')}}</h5>
                </div>

                <!-- Body -->
                <div class="card-body">
                    <form id="bankUpdateForm" action="{{route('seller.profile.bank_update',[$data->id])}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('Bank Name')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('Bank Name')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Bank Name')}}" name="bank_name" value="{{$data->bank_name ?? ''}}" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('Branch')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('Branch')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Branch')}}" name="branch" value="{{$data->branch ?? ''}}" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('Account Type')}}</label>
                                <select placeholder="{{\App\CPU\translate('Account Type')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Account Type')}}" name="account_type" class="form-control">
                                    <option value="Current" {{$data->account_type == 'Current' ? 'selected' : ''}}>{{\App\CPU\translate('Current')}}</option>
                                    <!-- <option value="Savings" {{$data->account_type == 'Savings' ? 'selected' : ''}}>{{\App\CPU\translate('Saving')}}</option> -->
                                </select>
                            </div>
                            <!-- <div class="col-lg-6 d-none">
                                    <label class="mt-2" for="">{{\App\CPU\translate('MICR Code')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('MICR Code')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('MICR Code')}}" name="micr_code" value="{{$data->micr_code ?? ''}}" class="form-control">
                                </div> -->
                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('Bank Address')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('Bank Address')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Bank Address')}}" value="{{$data->bank_address ?? ''}}" name="bank_address" class="form-control">
                            </div>

                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('Account No.')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('Account No.')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Account No.')}}" name="account_no" value="{{$data->account_no ?? ''}}" class="form-control">
                            </div>

                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('IFSC Code')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('IFSC Code')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('IFSC Code')}}" name="ifsc_code" value="{{$data->ifsc_code ?? ''}}" class="form-control">
                            </div>

                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('Account Holder Name')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('Account Holder Name')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Account Holder Name')}}" value="{{$data->holder_name ?? ''}}" name="holder_name" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label class="mt-2" for="">{{\App\CPU\translate('UPI ID')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('UPI ID')}}" title="{{\App\CPU\translate('UPI')}}{{\App\CPU\translate('ID')}}" value="{{$data->upi_id ?? ''}}" name="upi_id" class="form-control">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <div class="form-group">
                                    <label for="upi_scanner" class="title-color">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('UPI Scanner Image')}}</label>
                                    <div class="custom-file text-left">
                                        <input type="file" name="upi_scanner" id="upiScannerUpload" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="upiScannerUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img class="upload-img-view" id="upiScannerViewer"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/seller/'.$data->upi_scanner)}}" alt="UPI Scanner Image" />
                                </div>
                            </div>

                            <div class="col-12 pt-2 d-flex justify-content-end">
                                <button type="button"
                                    onclick="{{env('APP_MODE')!='demo'?"form_alert('bankUpdateForm','Want to update bank detail ?')":"call_demo()"}}"
                                    class="btn btn--primary">{{\App\CPU\translate('Save')}} {{\App\CPU\translate('changes')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->


            <div id="otherSectionDiv" class="card mb-3 mb-lg-5">
                <div class="card-header">
                    <h5 class="mb-0">{{\App\CPU\translate('Other')}} {{\App\CPU\translate('Setting')}}</h5>
                </div>

                <!-- Body -->
                <div class="card-body">
                    <form id="editOtherForm" action="{{route('seller.profile.update2',[$data->id])}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">


                            <div class="col-lg-6 ">
                                <label class="mt-2" for="">{{\App\CPU\translate('Minimum order value ')}}</label>
                                <input type="text" placeholder="{{\App\CPU\translate('Minimum order value ')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Minimum order value ')}}" name="minimum_order_amount" value="{{ $data->minimum_order_amount ?? '' }}" class="form-control">
                            </div>
                            <div class="col-12 pt-2 d-flex justify-content-end">
                                <button type="button"
                                    onclick="{{env('APP_MODE')!='demo'?"form_alert('editOtherForm','Want to update Seller profile ?')":"call_demo()"}}"
                                    class="btn btn--primary">{{\App\CPU\translate('Save')}} {{\App\CPU\translate('changes')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- End Body -->
            </div>




            <!-- Sticky Block End Point -->
            <div id="stickyBlockEndPoint"></div>
        </div>
    </div>
    <!-- End Row -->
</div>
<!-- End Content -->
@endsection

@push('script_2')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#viewer').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileUpload").change(function() {
        readURL(this);
    });
</script>

<script>
    document.getElementById('upiScannerUpload').addEventListener('change', function(event){
        const [file] = event.target.files;
        if (file) {
            document.getElementById('upiScannerViewer').src = URL.createObjectURL(file);
        }
    });
</script>


<script>
    $("#generalSection").click(function() {
        // $("#passwordSection").removeClass("active");
        $('#bussinessSection').removeClass("active")
        $("#generalSection").addClass("active");
        $('#addressSection').removeClass("active")
        $('#bankSection').removeClass("active")
        $('#otherSection').removeClass("active")
        $('html, body').animate({
            scrollTop: $("#generalDiv").offset().top
        }, 2000);
    });

    // $("#passwordSection").click(function() {
    //     $("#generalSection").removeClass("active");
    //     $('#bussinessSection').removeClass("active")
    //     $('#bankSection').removeClass("active")
    //     $('#otherSection').removeClass("active")
    //     $('#addressSection').removeClass("active")
    //     $("#passwordSection").addClass("active");
    //     $('html, body').animate({
    //         scrollTop: $("#passwordDiv").offset().top
    //     }, 2000);
    // });

    $("#bussinessSection").click(function() {
        // $("#passwordSection").removeClass("active");
        $("#generalSection").removeClass("active");
        $('#bankSection').removeClass("active")
        $('#otherSection').removeClass("active")
        $('#addressSection').removeClass("active")
        $('#bussinessSection').addClass("active")
        $('html, body').animate({
            scrollTop: $("#bussinessDiv").offset().top
        }, 2000);
    });

    $("#addressSection").click(function() {
        // $("#passwordSection").removeClass("active");
        $("#generalSection").removeClass("active");
        $('#bussinessSection').removeClass("active")
        $('#addressSection').addClass("active")
        $('#bankSection').removeClass("active")
        $('#otherSection').removeClass("active")
        $('html, body').animate({
            scrollTop: $("#addressSectionDiv").offset().top
        }, 2000);
    });

    $("#bankSection").click(function() {
        // $("#passwordSection").removeClass("active");
        $('#bussinessSection').removeClass("active")
        $("#generalSection").removeClass("active");
        $('#addressSection').removeClass("active")
        $('#otherSection').removeClass("active")
        $('#bankSection').addClass("active")
        $('html, body').animate({
            scrollTop: $("#addressSectionDiv").offset().top
        }, 2000);
    });
    $("#otherSection").click(function() {
        // $("#passwordSection").removeClass("active");
        $('#bussinessSection').removeClass("active")
        $("#generalSection").removeClass("active");
        $('#addressSection').removeClass("active")
        $('#bankSection').removeClass("active")
        $('#otherSection').addClass("active")
        $('html, body').animate({
            scrollTop: $("#addressSectionDiv").offset().top
        }, 2000);
    });

    // bankSection
    otherSection
</script>
<script>
$(document).ready(function () {

    function loadCities(stateID, selectedCity = null) {
        if (stateID) {
            $.ajax({
                type: "POST",
                url: "{{ route('seller.city.list') }}",
                data: {
                    state_id: stateID,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $('#city').empty().append('<option value="">-- Select City --</option>');
                    
                    $.each(response.data, function (key, city) {
                        let selected = (selectedCity && selectedCity == city.id) ? 'selected' : '';
                        $('#city').append('<option value="'+ city.id +'" data-id="'+ city.id +'" '+selected+'>'+ city.name +'</option>');
                    });

                    // After cities are populated, optionally load areas if city is pre-selected
                    if (selectedCity) {
                        loadAreas(selectedCity, preSelectedArea);
                    }
                }
            });
        } else {
            $('#city').empty().append('<option value="">-- Select City --</option>');
            $('#area').empty().append('<option value="">-- Select Area --</option>');
        }
    }

    function loadAreas(cityId, selectedArea = null) {
        if (cityId) {
            $.ajax({
                type: "POST",
                url: "{{ route('seller.area.list') }}",
                data: {
                    city_id: cityId,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $('#area').empty().append('<option value="">-- Select Area --</option>');
                    
                    $.each(response.data, function (key, area) {
                        let selected = (selectedArea && selectedArea == area.id) ? 'selected' : '';
                        $('#area').append('<option value="'+ area.id +'" data-id="'+ area.id +'" '+selected+'>'+ area.name +'</option>');
                    });
                }
            });
        } else {
            $('#area').empty().append('<option value="">-- Select Area --</option>');
        }
    }

    // On state change, load cities
    $('#state').on('change', function () {
        let stateID = $(this).val();
        loadCities(stateID);
        $('#area').empty().append('<option value="">-- Select Area --</option>'); // reset area
    });

    // On city change, load areas
    $('#city').on('change', function () {
        let cityId = $(this).val();
        loadAreas(cityId);
    });

    // Handle pre-populated data
    let preSelectedState = "{{ old('state', $data->shop->state ?? '') }}";
    let preSelectedCity  = "{{ old('city', $data->shop->city ?? '') }}";
    let preSelectedArea  = "{{ old('area', $data->shop->area ?? '') }}";

    if (preSelectedState) {
        loadCities(preSelectedState, preSelectedCity);
    }

});
</script>

@endpush

@push('script')

@endpush