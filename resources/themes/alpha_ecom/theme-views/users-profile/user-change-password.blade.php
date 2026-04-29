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
                <div class="card h-100 border-0 profilecard py-3">
                    <div class="card-body pt-0">
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <h5 class="mb-0"><b>{{translate('Change Password')}}</b></h5>
                        </div>

                        <div class="mt-4">
                            <form action="{{route('user-update-password')}}" method="post" enctype="multipart/form-data" id="update-profile-password">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="password2">{{translate('Old Password')}}</label>
                                            <div class="input-inner-end-ele">
                                                <input type="password" minlength="6" id="old_password"
                                                    class="form-control  br-8 input__field" name="old_password"
                                                    placeholder="{{translate('Enter Old Password')}}">
                                                <i class="bi bi-eye-slash-fill togglePassword"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="password2">{{translate('Password')}}</label>
                                            <div class="input-inner-end-ele">
                                                <input type="password" minlength="6" id="password"
                                                    class="form-control  br-8 input__field" name="password"
                                                    placeholder="{{translate('Enter Password')}}">
                                                <i class="bi bi-eye-slash-fill togglePassword"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="confirm_password2">{{translate('Confirm_Password')}}</label>
                                            <div class="input-inner-end-ele">
                                                <input type="password" minlength="6" id="confirm_password"
                                                    name="confirm_password" class="form-control  br-8 input__field"
                                                    placeholder="{{translate('Enter Confirm Password')}}">
                                                <i class="bi bi-eye-slash-fill togglePassword"></i>
                                            </div>
                                        </div>
                                        <div id='message'></div>
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



#image-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
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

    $(document).on('submit','#update-profile-password', function(e) {
        e.preventDefault()
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if(response.status) {
                    swal.fire(`${response.message}`, '', 'success').then(function() {
                        window.location.reload()
                    })
                } else {
                    swal.fire(`${response.message}`, '', 'error')
                }
            },
            error: function(error) {
                swal.fire(`${response.message}`, '', 'error')
            }
        });
    })

</script>
@endpush