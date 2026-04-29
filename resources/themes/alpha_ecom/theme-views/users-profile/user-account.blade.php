@extends('theme-views.layouts.app')

@section('title', translate('Personal_Details').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))
@section('content')
<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 pt-3">
    <div class="container">
        <div class="row g-3">

        <div class="col-md-12">
              <div class="bread__crum">
                    <nav aria-label="breadcrumb">
                                <ol class="breadcrumb fs-12 mb-0">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}">{{ translate('home') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{translate('Account')}}</li>
                                </ol>
                        </nav>
              </div>
        </div>

            <!-- Sidebar-->
            @include('theme-views.partials._profile-aside')

            <div class="col-lg-9">
                <div class="card h-100 py-2 profilecard">
                    <div class="card-body pt-0">
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <h5 class="mb-0"><b>{{translate('Personal Info')}}</b></h5>
                            <span class="edit__icon d-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M11.457 17.0356H17.5009" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.65 3.16233C11.2964 2.38982 12.4583 2.27655 13.2469 2.90978C13.2905 2.94413 14.6912 4.03232 14.6912 4.03232C15.5575 4.55599 15.8266 5.66925 15.2912 6.51882C15.2627 6.56432 7.34329 16.4704 7.34329 16.4704C7.07981 16.7991 6.67986 16.9931 6.25242 16.9978L3.21961 17.0358L2.53628 14.1436C2.44055 13.7369 2.53628 13.3098 2.79975 12.9811L10.65 3.16233Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.18359 5.00098L13.7271 8.49025" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </div>

                        <div class="mt-4">
                            <form action="{{route('user-update')}}" method="post" enctype="multipart/form-data" id="user-update">
                                @csrf
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        @php
                                        $image = $customerDetail['image'] != "def.png" ?
                                        asset('storage/app/public/profile/' . $customerDetail['image']) :
                                        asset('storage/app/' . $customerDetail['image'])
                                        @endphp
                                        <div class="col-md-2">
                                           
                                            <div class="image__profile__avtar image-container">
                                                <img src="{{$image}}" alt="" class="rounded-circle img-fluid" id="selected-image"/> 
                                                <input type="file" name="image" id="image-input" accept="image/*" onchange="updateImage()">
                                                <i class="fa-solid fa-user-pen"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <!-- <label for="f_name2">{{translate('First_Name')}}</label> -->
                                        <input type="text" id="f_name" class="form-control  br-8 input__field"
                                            value="{{$customerDetail['f_name']}}" name="f_name"
                                            placeholder="{{translate('Contact Person Name')}}">
                                    </div>
                                    <!-- <div class="form-group col-md-4">
                                                <input type="text" class="form-control mt-md-0 mt-3 br-8 py-3" id="f_name" class="form-control" value="{{$customerDetail['f_name']}}" name="f_name" placeholder="{{translate('Contact Person Name')}}">
                                            </div> -->


                                    <!-- <div class="form-group">
                                                <label for="l_name2">{{translate('Last_Name')}}</label>
                                                <input type="text"  class="form-control" value="{{$customerDetail['l_name']}}" name="l_name" placeholder="{{translate('Contact Person Name')}}">
                                            </div> -->

                                    <div class="form-group col-md-6">
                                        <!-- <label for="phone2">{{translate('Phone')}}</label> -->
                                        <input type="text" id="phone" name="phone"
                                            class="form-control  br-8 input__field"
                                            value="{{$customerDetail['phone']}}"
                                            placeholder="{{translate('Ex:  01xxxxxxxxx')}}" onkeypress="return (event.charCode !=8 &amp;&amp; event.charCode ==0 || (event.charCode >= 48 &amp;&amp; event.charCode <= 57))" minlength="10" maxlength="10" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <!-- <label for="email2">{{translate('Email')}}</label> -->
                                            <input type="email" id="email2" class="form-control  br-8 input__field"
                                                value="{{$customerDetail['email']}}" disabled>
                                        </div>
                                    </div>

                                    

                                    <!-- Contry  -->


                                    <!-- <div class="form-group col-md-4 d-none">
                                        <select class="form-select  br-8 input__field" aria-label="Default select example" id="country" name="country" onclick="toggleDropdown()" required>
                                            <option value="">Country</option>
                                            @if($countries->count() > 0)
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->name }}" data-id="{{ $country->id }}" {{ $country->name == $customerDetail['country'] ? 'selected' : '' }}>{{ $country->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div> -->

                                    <!-- state -->



                                    <!-- <div class="form-group col-md-4 d-none">
                                        <select class="form-select  br-8 input__field" aria-label="Default select example" id="state" name="state">
                                            <option value="">State</option>
                                            @if(isset($states))
                                                @foreach($states as $state)
                                                    <option value="{{ $state->name }}" data-id="{{ $state->id }}" {{ $state->name == $customerDetail['state'] ? 'selected' : '' }}>{{ $state->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div> -->

                                    <!-- City -->
                                    <!-- <div class="form-group col-md-4 d-none">
                                        <select class="form-select  br-8 input__field" aria-label="Default select example" id="city" name="city">
                                            <option value="">City</option>
                                            @if(!empty($cities))
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->name }}" data-id="{{ $city->id }}" {{ $city->name == $customerDetail['city'] ? 'selected' : '' }}>{{ $city->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div> -->

                                    <!-- Pincode -->
                                    <!-- <div class="form-group col-md-4 d-none">
                                        <input type="text" class="form-control br-8 input__field" placeholder="Pincode" name="pincode" value="{{ $customerDetail['zip'] }}" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="6" maxlength="6">
                                    </div> -->

                                    <div class="form-group col-md-6">
                                        <p class="mb-0">Gender</p>

                                        <input type="radio" name="gender" value="male" {{ $customerDetail['gender'] == 'male' ? 'checked' : '' }} class="design-new-radio-btn" id="male">
                                        <label for="male" class="ff-primary fs-16 fw-600 pe-3 text-muted cursor-pointer">Male</label>

                                        <input type="radio" name="gender" value="female" {{ $customerDetail['gender'] == 'female' ? 'checked' : '' }} class="design-new-radio-btn" id="female">
                                        <label for="female" class="ff-primary fs-16 fw-600 pe-3 text-muted cursor-pointer">Female</label>

                                        <input type="radio" name="gender" value="unknown" {{ $customerDetail['gender'] == 'unknown' ? 'checked' : '' }} class="design-new-radio-btn" id="other">
                                        <label for="other" class="ff-primary fs-16 fw-600 pe-3 text-muted cursor-pointer">Other</label>

                                    </div>
                                    <div class="">
                                        <div class="btn__profile">
                                            <button type="submit" class="btn-add-cart2 btn__height mt-0">{{translate('Update')}}</button>
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





<style>
     .image-container {
    position: relative;
}

.image__profile__avtar i{
    color: #0a9494;
    position: absolute;
    bottom: 0;
    width: 92px;
    height: 92px;
    right: 0;
    display: flex;
    top: 0;
    border-radius: 50%;
    background: #00000030;
    align-items: center;
    justify-content: center;
    translate:ease-in-out 0.3s;
    opacity: 0;

}

.image__profile__avtar:hover  i {
opacity: 1;
 
}


#image-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 11;
}

.btn__height{
    height: 38px;
    width: fit-content;
    padding: 0.375rem 0.95rem;
}



.btn__profile{
    display: flex;
    gap:13px;
     

}

.edit__icon{
    width: 28px;
height: 28px;
flex-shrink: 0;
border-radius: 20px;
background: var(--green, #0A9494);
}

.edit__icon {
  width: 28px;
  height: 28px;
  flex-shrink: 0;
  border-radius: 20px;
  background: var(--green, #0A9494);
  display: flex;
  justify-content: center;
  align-items: center;
}

</style>
<!-- End Main Content -->
@endsection

@push('script')


<script>
     function updateImage() {
    const input = document.getElementById('image-input');
    const image = document.getElementById('selected-image');

    const file = input.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            image.src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
}
</script>



<script>
    function checkPasswordMatch() {
        var password = $("#password").val();
        var confirmPassword = $("#confirm_password").val();
        $("#message").removeAttr("style");
        $("#message").html("");
        if (confirmPassword == "") {
            $("#message").attr("style", "color:black");
            $("#message").html("{{translate('Please ReType Password')}}");

        } else if (password == "") {
            $("#message").removeAttr("style");
            $("#message").html("");

        } else if (password != confirmPassword) {
            $("#message").html("{{translate('Passwords do not match')}}!");
            $("#message").attr("style", "color:red");
        } else if (confirmPassword.length <= 6) {
            $("#message").html("{{translate('password Must Be 6 Character')}}");
            $("#message").attr("style", "color:red");
        } else {

            $("#message").html("{{translate('Passwords match')}}.");
            $("#message").attr("style", "color:green");
        }
    }
    $(document).ready(function() {
        $("#confirm_password").keyup(checkPasswordMatch);
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
                        html += `<option value="${elm.id}">${elm.name}</option>`
                    })
                    $('select[name=city]').html(html)
                }
            }
        });
    })

</script>
@endpush