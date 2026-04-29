<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session()->get('direction') }}">
<head>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <title>
        @yield('title')
    </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- Meta Data -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="_token" content="{{csrf_token()}}">

    <!-- CSRF Token -->
    <meta name="base-url" content="{{ url('/') }}">

    <!-- Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/vertical-slider.css')}}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/style-vivek.css')}}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/aos.css')}}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/media-query.css')}}">
    <link rel="stylesheet" href="https://cdn.rawgit.com/StarPlugins/thumbelina/8b9c09d9/thumbelina.css">
   
    
    <link rel="stylesheet" href="{{ theme_asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ theme_asset('assets/css/price_range_style.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" type="text/css"
		media="all" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- sweet alert Css -->
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/sweet_alert/sweetalert2.css') }}">
    <!--Toastr -->
    <link rel="stylesheet" href="{{theme_asset('assets/css/toastr.css')}}"/>
    <link rel="stylesheet" href="{{theme_asset('assets/css/custom.css')}}"/>

    <!--Toastr -->
    <link rel="stylesheet" href="{{theme_asset('assets/css/toastr.css')}}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">

    <title>Alpha E-Commerece</title>
    <style>
        :root {
            --bs-primary: {{ $web_config['primary_color'] }};
            --bs-primary-rgb: {{ \App\CPU\hex_to_rgb($web_config['primary_color']) }};
            --primary-dark: {{ $web_config['primary_color'] }};
            --primary-light: {{ $web_config['primary_color_light'] }};
            --bs-secondary: {{ $web_config['secondary_color'] }};
            --bs-secondary-rgb: {{ \App\CPU\hex_to_rgb($web_config['secondary_color']) }};
        }
    </style>
    @php($google_tag_manager_id = \App\CPU\Helpers::get_business_settings('google_tag_manager_id'))
    @if($google_tag_manager_id )
        <!-- Google Tag Manager -->
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{$google_tag_manager_id}}');</script>
        <!-- End Google Tag Manager -->

    @endif

    @php($pixel_analytices_user_code =\App\CPU\Helpers::get_business_settings('pixel_analytics'))
    @if($pixel_analytices_user_code)
        <!-- Facebook Pixel Code -->
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{your-pixel-id-goes-here}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                 src="https://www.facebook.com/tr?id={your-pixel-id-goes-here}&ev=PageView&noscript=1"/>
        </noscript>
        <!-- End Facebook Pixel Code -->
    @endif
</head>
<!-- Body-->
<body >
<div id="preloader">
  <div id="loader"></div>
</div>

<!-- OVER LAY LOADER START -->
   
<div class="overlay d-none">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
          </div>
        </div>

<!-- OVER LAY LOADER END -->




<style type="text/css">
    
#preloader {
    position: fixed;
    background: #fff;
    top: 0;
    left: 0;
    z-index: 999;
    width: 100%;
    height: 100%;
}
#loader {
    display: block;
    position: relative;
    width: 150px;
    height: 150px;
    margin: -75px 0 0 -75px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: #ceeaea;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
}
#loader:before {
    content: "";
    position: absolute;
    top: 5px;
    left: 5px;
    right: 5px;
    bottom: 5px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color:#e89825;
    -webkit-animation: spin 3s linear infinite;
    animation: spin 3s linear infinite;
}
#loader:after {
    content: "";
    position: absolute;
    top: 15px;
    left: 15px;
    right: 15px;
    bottom: 15px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: #009688;
    -webkit-animation: spin 1.5s linear infinite;
    animation: spin 1.5s linear infinite;
}
@-webkit-keyframes spin {
    0%   {
        -webkit-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
@keyframes spin {
    0%   {
        -webkit-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
</style>


<!-- Header and top offer bar -->
@include('theme-views.layouts.partials._header')

<!-- Settings Sidebar -->
{{-- @include('theme-views.layouts.partials._settings-sidebar') --}}

<!-- Main Content -->
@yield('content')

<!-- Feature -->
{{-- @include('theme-views.layouts.partials._feature') --}}

<!-- Footer-->
@include('theme-views.layouts.partials._footer')

<!-- Back To Top Button -->


<!-- App Bar -->
<div class="app-bar px-sm-2 d-xl-none" id="mobile_app_bar">
    {{-- @include('theme-views.layouts.partials._app-bar') --}}
</div>

<!-- Cookies -->
@php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
@if($cookie && $cookie['status']==1)
    <section id="cookie-section"></section>
@endif


<!-- ======= All Modals ======= -->
{{--
<!-- Register Modal -->
@include('theme-views.layouts.partials.modal._register')

<!-- Login Modal -->
@include('theme-views.layouts.partials.modal._login')

<!-- Seller Login Modal -->
@include('theme-views.layouts.partials.modal._seller-login')

<!-- Quick View Modal -->
@include('theme-views.layouts.partials.modal._quick-view')

<!-- Initial Modal -->
@include('theme-views.layouts.partials.modal._initial')
        --}}

@include('theme-views.layouts.partials.modal._all_modal')



</div>

<span id="update_nav_cart_url" data-url="{{route('cart.nav-cart')}}"></span>
<span id="remove_from_cart_url" data-url="{{ route('cart.remove') }}"></span>
<span id="update_quantity_url" data-url="{{route('cart.updateQuantity.guest')}}"></span>
<span id="order_again_url" data-url="{{ route('cart.order-again') }}"></span>

@php($whatsapp = \App\CPU\Helpers::get_business_settings('whatsapp'))
<div class="social-chat-icons d-none">
    @if(isset($whatsapp['status']) && $whatsapp['status'] == 1 )
        <div class="">
            <a href="https://wa.me/{{ $whatsapp['phone'] }}?text=Hello%20there!" target="_blank">
                <img src="{{theme_asset('assets/img/whatsapp.svg')}}" width="35" class="chat-image-shadow"
                     alt="Chat with us on WhatsApp">
            </a>
        </div>
    @endif







<!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->

<script src="{{ theme_asset('assets/plugins/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/sweet_alert/sweetalert2.js') }}"></script>
<script src="{{ theme_asset('assets/js/main.js') }}"></script>
<script src="{{ theme_asset('assets/js/toastr.js') }}"></script>
<script src="{{ theme_asset('assets/js/custom.js') }}"></script> 
<script src="{{ theme_asset('assets/js/bootstrap.bundle.min.js') }}"></script> 
<script src="{{ theme_asset('assets/js/jquery-3.6.0.min.js') }}"></script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>


<!-- End Customized Theme Code -->

<script>
// -----Country Code Selection
    // $("#mobile_code").intlTelInput({
    //     initialCountry: "in",
    //     separateDialCode: true,
    //     // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
    // });
</script>

<!-- {!! Toastr::message() !!} -->

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
            Swal.fire('{{$error}}', '', 'error')
        // toastr.error('{{$error}}', Error, {
        //     CloseButton: true,
        //     ProgressBar: true
        // });
        @endforeach
    </script>
@endif

<script>
    @if(Request::is('/') &&  \Illuminate\Support\Facades\Cookie::has('popup_banner')==false)
    $(document).ready(function () {
        $('#initialModal').modal('show');
    });
    @php(\Illuminate\Support\Facades\Cookie::queue('popup_banner', 'off', 1))
    @endif
</script>

<script>
    @php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
    let cookie_content = `
        <div class="cookies active absolute-white py-4">
            <div class="container">
                <h4 class="absolute-white mb-3">{{translate('Your_Privacy_Matter')}}</h4>
                <p>{{ $cookie ? $cookie['cookie_text'] : '' }}</p>
                <div class="d-flex gap-3 justify-content-end mt-4">
                    <button type="button" class="btn absolute-white btn-link" id="cookie-reject">{{translate('no')}}, {{translate('thanks')}}</button>
                    <button type="button" class="btn btn-primary" id="cookie-accept">{{translate('yes')}}, {{translate('i_Accept')}}</button>
                </div>
            </div>
        </div>
        `;
    $(document).on('click', '#cookie-accept', function () {
        document.cookie = 'alphawizz_ecom=accepted; max-age=' + 60 * 60 * 24 * 30;
        $('#cookie-section').hide();
    });
    $(document).on('click', '#cookie-reject', function () {
        document.cookie = 'alphawizz_ecom=reject; max-age=' + 60 * 60 * 24;
        $('#cookie-section').hide();
    });

    $(document).ready(function () {
        if (document.cookie.indexOf("alphawizz_ecom=accepted") !== -1) {
            $('#cookie-section').hide();
        } else {
            $('#cookie-section').html(cookie_content).show();
        }
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

<script>

    function route_alert(route, message) {
        Swal.fire({
            title: '{{translate('Are you sure')}}?',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '{{$web_config['primary_color']}}',
            cancelButtonText: '{{translate('No')}}',
            confirmButtonText: '{{translate('Yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = route;
            }
        })
    }

    let currentStep = 1;

    function showStep(stepNumber) {
        const steps = document.querySelectorAll(".step");
        steps.forEach((step) => {
            step.style.display = "none";
        });

        const currentStepElement = document.getElementById(`step${stepNumber}`);
        if (currentStepElement) {
            currentStepElement.style.display = "block";
            currentStep = stepNumber;
        }
    }

    var status = true
    function nextStep(nextStepNumber) {
        if(nextStepNumber == 2) {
            sendOTP(nextStepNumber)
        }

        if(nextStepNumber == 3) {
            verifyOTP(nextStepNumber)
        }

        if(nextStepNumber == 3) {
            registerUser(nextStepNumber)
        }

        // if (nextStepNumber > currentStep && nextStepNumber <= 3 && JSON.parse(status)) {
        //     showStep(nextStepNumber);
        // }
    }

    function prevStep(prevStepNumber) {
        if (prevStepNumber < currentStep && prevStepNumber >= 1) {
            showStep(prevStepNumber);
        }
    }

    // Show the initial step
    showStep(currentStep);

    function sendOTP(step) {
        $.ajax({
            type: "POST",
            url: "{{ route('customer.auth.register-otp') }}",
            data: $('#register-otp').serialize(),
            dataType: "json",
            success: function (response) {
                if(response.status) {
                    $('span.register-mobile-response').text(response.data.mobile)
                    $('span.register-otp-response').text(response.data.otp)
                    swal.fire(`{{translate('OTP_sent_success')}}`,'','success');
                    showStep(step)
                } else {
                    swal.fire(`{{translate('Something went wrong')}}`,'','error');
                }
            },
            error: function(error) {
                swal.fire(`${error.responseJSON.errors.phone[0]}`,'','error')
            }
        });
    }

    function verifyOTP(step) {
        var mobile = $('#register-otp').find('input[name=phone]').val()
        
        $('#final-register-step').find('input[name=phone]').val(mobile)
        
        var otp = $('span.register-otp-response').text()
        var InputOTP = "";
        $('form#otp-verification').find('input').each(function(ind, elm){
            InputOTP += `${$(elm).val()}`
        })
        status = true
        if(InputOTP != $('span.register-otp-response').text()) {
            swal.fire(`{{translate('Invalid OTP')}}`,'','error');
            status = false
        } else {
            showStep(step)
        }
    }

    function registerUser(step) {
        $.ajax({
            type: "POST",
            url: "{{ route('customer.auth.final-register') }}",
            data: $('#final-register-step').serialize(),
            dataType: "json",
            success: function (response) {
                
            }
        });

    }

    $(document).on('submit', '#final-register-step', function(e) {
        e.preventDefault()
        $.ajax({
            type: "POST",
            url: "{{ route('customer.auth.final-register') }}",
            data: $('#final-register-step').serialize(),
            dataType: "json",
            success: function (response) {
                if(response.status) {
                    swal.fire("{{ translate('registration_success_login_now') }}", '', 'success')
                    setTimeout(() => {
                        window.location.reload()
                    }, 3000);
                } else {
                    var errorText = ""
                    $.each(response.errors, function(ind, elm) {
                        errorText += `${elm}\n`
                    })
                    swal.fire(`${errorText}`, '', 'error')
                }
            }, 
            error:function(error) {
                console.log(error.responseJSON)
            }
        });
    })
    
</script>
<script>
    $("#customer_login_modal").submit(function (e) {
        e.preventDefault();
        var customer_recaptcha = true;

        @if($web_config['recaptcha']['status'] == 1)
            var response_customer_login = grecaptcha.getResponse($('#recaptcha_element_customer_login').attr('data-login-id'));

            if (response_customer_login.length === 0) {
                e.preventDefault();
                toastr.error("{{\App\CPU\translate('Please check the recaptcha')}}");
                customer_recaptcha = false;
            }
        @endif

        if(customer_recaptcha === true) {
            let form = $(this);
            $.ajax({
                type: 'POST',
                url:`{{route('customer.login.short')}}`,
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
                },
                success: function (data) {
                    if (data.status === 'success') {
                        swal.fire(`{{translate('Login_successful')}}`, '','success').then(function() {
                            location.reload();
                        });
                    } else if (data.status === 'error') {
                        

                        data.redirect_url !== '' ? window.location.href = data.redirect_url : swal.fire(`${data.message}`, '','error');
                    }
                }
            });
        }
    });

    let oTP = ""
    $(document).on('submit','#login-with-otp', function(e){
        e.preventDefault()
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: {
                phone: $('#login-with-otp').find('input[name=phone]').val(),
                _token: $('#login-with-otp').find('input[name=_token]').val()
            },
            dataType: "json",
            success: function (response) {
                if(response.status) {
                    $('#login-with-otp').find('input[name=otp]').val(response.data.otp)
                    oTP = response.data.otp
                    swal.fire(`${response.message}`,'','success').then(function(){
                        $('#login-with-otp').find('span.otp-field').removeClass('d-none')

                        $('#login-with-otp').attr('id', 'submit-with-otp')

                    })
                } else {
                    swal.fire(`${response.message}`,'','error')
                }
            },
            error:function(error) {
                console.log(error.responseJSON)
            }
        });
    })

    function resendLoginOTP() {
        $.ajax({
            type: "POST",
            url: "{{ route('customer.auth.login-otp') }}",
            data: {
                phone: $('#submit-with-otp').find('input[name=phone]').val(),
                _token: $('meta[name="_token"]').attr('content')
            },
            dataType: "json",
            success: function (response) {
                if(response.status) {
                    $('#submit-with-otp').find('input[name=otp]').val(response.data.otp)
                    oTP = response.data.otp
                    swal.fire(`Resent OTP success`, '', 'success').then(function() {
                        $('#submit-with-otp').find('span.otp-field').removeClass('d-none')
                    })
                } else {
                    swal.fire(`${response.message}`,'','error')
                }
            },
            error:function(error) {
                console.log(error.responseJSON)
            }
        });
    }
    
    $(document).on('submit','#submit-with-otp', function(e){
        e.preventDefault()
        if(oTP == $('#submit-with-otp').find('input[name=otp]').val()) {
            $.ajax({
                type: "POST",
                url: "{{ route('customer.auth.login-with-otp') }}",
                data: {
                    phone: $('#submit-with-otp').find('input[name=phone]').val(),
                    otp: $('#submit-with-otp').find('input[name=otp]').val(),
                    _token: $('#submit-with-otp').find('input[name=_token]').val()
                },
                dataType: "json",
                success: function (response) {
                    if(response.status == 'success') {
                        swal.fire(`${response.message}`,'','success').then(function(){
                            window.location.reload()
                        })
                    } else {
                        swal.fire(`${response.message}`,'','error')
                    }
                },
                error:function(error) {

                    // swal.fire(`${response.message}`,'','success')
                }
            });
        } else {
            swal.fire(`Invalid OTP`,'','success')
        }
    })

    $(document).on('mouseover','.pic', function() {

        $('.pic').removeClass('img-active')

        $(this).addClass('img-active')


        // main picture
        var picture = document.querySelector('#pic');

        picture.src = $(this).attr('src');

        // Zoom window
        var zoom = document.querySelector('#zoom');
        zoom.style.backgroundImage = "url(" + $(this).attr('src') + ")";

    })





        // side pictures
        var picture1 = document.querySelector('.pic');
        
        // Main picture container
        var mainContainer = document.querySelector('#picture');
        
        // selector
        var rect = document.querySelector("#rect");
        
        // Zoom window
        var zoom = document.querySelector('#zoom');
        
        if(picture1 && mainContainer && rect && zoom) {
            // list of pictures 
            picList = [picture1]
    
            // Active side picture
            let picActive = 1;
    
            // Add a boxshodow to the first piture (Current active picture)
            picture1.classList.add('img-active');

        }


        // change image 
        function changeImage(imgSrc, n) {
            // This will change the main image
            picture.src = imgSrc;
            // This will change the background image of the zoom window
            zoom.style.backgroundImage = "url(" + imgSrc + ")";
            // removing box shodow from the previous active side picture
            picList[picActive - 1].classList.remove('img-active');
            // Add box shodow to active side picture
            picList[n - 1].classList.add('img-active');
            // update the active side picture 
            picActive = n;
        }
        if(mainContainer) {

            // Width and height of main picture in px
            let w1 = mainContainer.offsetWidth;
            let h1 = mainContainer.offsetHeight;
    
            // Zoom ratio
            let ratio = 3;
    
            // Zoom window background-image size
            zoom.style.backgroundSize = w1 * ratio + 'px ' + h1 * ratio + 'px';
    
            // Coordinates of mouse cursor
            let x, y, xx, yy;
    
            // Width and height of selector
            let w2 = rect.offsetWidth;
            let h2 = rect.offsetHeight;
    
            // zoom window width and height
            zoom.style.width = w2 * ratio + 'px';
            zoom.style.height = h2 * ratio + 'px';
    
            // half of selector shows outside the main picture
            // We need half of width and height
            w2 = w2 / 2;
            h2 = h2 / 2;
            
            // moving the selector box 
            function move(event) {
                // How far is the mouse cursor from an element
                // x how far the cursor from left of element
                x = event.offsetX;
                // y how far the cursor from the top of an element
                y = event.offsetY;
    
                xx = x - w2;
                yy = y - h2;
                // Keeping the selector inside the main picture
                // left of picture
                if (x < w2) {
                    x = w2;
                    // matching the zoom window with the selector
                    xx = 0;
                }
                // right of main picture
                if (x > w1 - w2) {
                    x = w1 - w2;
                    xx = x - w2;
                }
                // top of main picture 
                if (y < h2) {
                    y = h2;
                    yy = 0;
                }
                // bottom of main picture
                if (y > h1 - h2) {
                    y = h1 - h2;
                }
    
                xx = xx * ratio;
                yy = yy * ratio;
                // changing the position of the selector
                rect.style.left = x + 'px';
                rect.style.top = y + 'px';
                // changing background image of zoom window
                zoom.style.backgroundPosition = '-' + xx + 'px ' + '-' + yy + 'px';
            }

            mainContainer.addEventListener('mousemove', function() {
                move(event);
                addOpacity();
            })

            mainContainer.addEventListener('mouseout', function() {
                removeOpacity();
            })
        }


        // show selector
        // show zoom window
        function addOpacity() {
            rect.classList.add('rect-active');
            zoom.classList.add('rect-active');
        }

        // Hide the zoom window 
        function removeOpacity() {
            zoom.classList.remove('rect-active');
        }

        window.onload = function () {
            // Hide the preloader when the entire page has loaded
            var preloader = document.getElementById('preloader');
            preloader.style.display = 'none';
        };

        $(document).on('click','.save-later-product', function() {
            var productid = $(this).data('productid')
    
            $.ajax({
                type: "POST",
                url: "{{ route('save-later') }}",
                data: {
                    productid: productid
                },
                dataType: "json",
                headers:{
                    'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
                },
                success: function (response) {
                    if(response.status) {
                        swal.fire(`${response.message}`,'','success').then(function() {
                            window.location.reload()
                        })
                    } else {
                        swal.fire(`${response.message}`,'','error')
                    }
                },
                error: function(error) {
                    swal.fire(`${error.responseJSON.message}`,'','error')
                }
            });
    
        })
    
        $(document).on('click','.remove-save-later', function() {
            var productid = $(this).data('productid')
    
            $.ajax({
                type: "POST",
                url: "{{ route('remove-save-later') }}",
                data: {
                    productid: productid
                },
                dataType: "json",
                headers:{
                    'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
                },
                success: function (response) {
                    if(response.status) {
                        swal.fire(`${response.message}`,'','success').then(function() {
                            window.location.reload()
                        })
                    } else {
                        swal.fire(`${response.message}`,'','error')
                    }
                },
                error: function(error) {
                    swal.fire(`Something went wrong`,'','error')
                }
            });
    
        })
    </script>



@stack('script')




</body>
</html>
