@extends('theme-views.layouts.app')

@section('title', translate('contact_us').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 mb-sm-5">

    <div class="page-title  py-5 __opacity-half breadcrumdiv">
        <div class="container">
            <h3 class="absolute-white text-center mt-5">{{translate('Contact_Us')}}</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb fs-12 mb-0 mx-auto">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">{{ translate('Home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{translate('Contact Us')}}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pt-md-5 pt-4">

        <div class="row g-3 contactdiv">
            <div class="col-xl-4 col-lg-6">
                <div class="contact-one__list">
                    <div class="contact-one__item wow fadeInLeft animated" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-name: fadeInLeft;">
                        <div class="contact-one__item__icon">
                            <img src="{{ theme_asset('assets/images/phone.png')}}" alt="">
                        </div>
                        <div class="contact-one__item__content">
                            <h5 class="contact-one__item__title">{{ translate('call_us') }}</h5>
                            <!-- <p class="contact-one__item__text">901 N Pitt Str., Suite 170 Alexandria, USA</p> -->
                            <a href="tel:{{$web_config['phone']->value}}">{{$web_config['phone']->value}}</a>
                        </div>
                    </div>
                    <div class="contact-one__item wow fadeInLeft animated" data-wow-duration="1700ms" style="visibility: visible; animation-duration: 1700ms; animation-name: fadeInLeft;">
                        <div class="contact-one__item__icon">
                            <img src="{{ theme_asset('assets/images/mail.png')}}" alt="">
                        </div>
                        <div class="contact-one__item__content">
                            <h5 class="contact-one__item__title">{{ translate('mail_us') }}</h5>
                            <a href="mailto:{{\App\CPU\Helpers::get_business_settings('company_email')}}" class="contact-one__item__call">
                                {{\App\CPU\Helpers::get_business_settings('company_email')}}</a>
                        </div>
                    </div>
                    <div class="contact-one__item wow fadeInLeft animated" data-wow-duration="1900ms" style="visibility: visible; animation-duration: 1900ms; animation-name: fadeInLeft;">
                        <div class="contact-one__item__icon">
                            <img src="{{ theme_asset('assets/images/location.png')}}" alt="">
                        </div>
                        <div class="contact-one__item__content">
                            <h5 class="contact-one__item__title">{{ translate('Find_us') }}</h5>
                            <a href="#" class="contact-one__item__call">
                                {{ \App\CPU\Helpers::get_business_settings('shop_address')}}</a>
                        </div>
                    </div>
                </div>

                <!-- <div class="contact__us__content">
                    <div class="d-flex justify-content-lg-center text-primary">
                        <img width="300" class="dark-support svg mb-md-4 mb-3" src="{{ theme_asset('assets/img/media/contact-us.webp') }}" alt="">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="media">
                                <div class="media-body">
                                    <h6 class="text-muted">{{ translate('call_us') }}</h6>
                                    <a class="" href="tel:{{$web_config['phone']->value}}">{{$web_config['phone']->value}}</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="media">
                                <div class="media-body">
                                    <h6 class="text-muted">{{ translate('mail_us') }}</h6>
                                    <a href="mailto:{{\App\CPU\Helpers::get_business_settings('company_email')}}">{{\App\CPU\Helpers::get_business_settings('company_email')}}</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="media">
                                <div class="media-body">
                                    <h6 class="mb-0 text-muted">{{ translate('Find_us') }}</h6>
                                    <p>{{ \App\CPU\Helpers::get_business_settings('shop_address')}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex justify-content-end pe-5 gap-3">

                            <a href="" class="social__media__icon"><i class="fa-brands fa-twitter"></i> </a>
                            <a href="" class="social__media__icon"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="" class="social__media__icon"><i class="fa-brands fa-instagram"></i></a>

                        </div>
                    </div>

                </div> -->
            </div>

            <div class="col-xl-8 d-none">
                <div class="contact-one__inner">
                    <div class="contact-one__bg" style="background-image: url({{ theme_asset('assets/images/featureBg.png')}});"></div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="contact-one__form__thumb real-image">
                                <img src="assets/web/images/resources/header-2-2.jpg" height="451px" alt="rentol image">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div id="success-message" class="alert alert-success" style="display: none;">
                                Your message has been sent successfully.
                            </div>

                            <form class="contact-one__form contact-form-validated form-one wow fadeInUp animated" id="contact-form" action="https://pristin.pristineandaman.com/contact" method="POST" novalidate="novalidate" style="visibility: visible; animation-name: fadeInUp;">
                                <div class="form-one__group">
                                    <div class="form-one__control form-one__control--full">
                                        <input type="text" id="name" class="balloon form-control" name="name" value="{{ old('name') }}" placeholder="{{ translate('name') }}">
                                        <label for="name">{{ translate('name') }}</label>
                                        <!-- <input type="text" name="name" placeholder="name"> -->
                                    </div>
                                    <div class="form-one__control form-one__control--full">
                                        <input type="email" id="email" class="balloon form-control" name="email" value="{{ old('email') }}" placeholder="{{ translate('email_address') }}">
                                        <label for="email">{{ translate('email_address') }}</label>
                                        <!-- <input type="email" name="email" placeholder="Email address"> -->
                                    </div>
                                    <div class="form-one__control form-one__control--full">
                                        <input type="text" name="mobile_number" value="{{ old('mobile_number') }}" class="balloon form-control" rows="6" placeholder="{{ translate('contact_number') }}">
                                        <label for="message">{{ translate('contact_number') }}</label>
                                        <!-- <input type="text" name="subject" placeholder="select subject"> -->
                                    </div>
                                    <div class="form-one__control form-one__control--full">
                                        <input type="text" name="subject" value="{{ old('subject') }}" class="balloon form-control" rows="6" placeholder="{{ translate('short_title') }}">
                                        <label for="message">{{ translate('Subject') }}</label>
                                        <!-- <input type="text" name="subject" placeholder="select subject"> -->
                                    </div>
                                    <div class="form-one__control form-one__control--full">
                                        <textarea name="message" id="message" class="balloon form-control" rows="6" placeholder="{{ translate('type_your_message_here..') }}"> {{ old('subject') }} </textarea>
                                        <label for="message">{{ translate('message') }}</label>
                                    </div>
                                    <div class="form-one__control form-one__control--full">
                                        <button type="submit" class="rentol-btn rentol-btn--submite">{{ translate('Send') }} <i class="icon-right-arrow"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6 col-xl-8 order-1 login-page px-md-5 stylecard">
                <div class="card card-body cardstyle" style="background-image: url({{ theme_asset('assets/images/contactbg.png')}});">
                    <!-- <div class="contacthead">
                        <h4 class="">{{ translate('Contact_Us') }}</h4>
                    </div> -->

                    <form action="{{route('contact.store')}}" method="POST" id="getResponse">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <img src="{{ theme_asset('assets/images/contactimg2.png')}}" alt="" class="contactsideimg">
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="login-form">

                                    <input type="text" id="name" class="balloon form-control inputdiv" name="name" value="{{ old('name') }}" placeholder="{{ translate('name') }}">
                                    <label for="name">{{ translate('name') }}</label>
                                </div>

                                <div class="">
                                    <div class="login-form">

                                        <input type="email" id="email" class="balloon form-control inputdiv" name="email" value="{{ old('email') }}" placeholder="{{ translate('email_address') }}">
                                        <label for="email">{{ translate('email_address') }}</label>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="login-form">

                                        <input type="text" name="mobile_number" value="{{ old('mobile_number') }}" class="balloon form-control inputdiv" rows="6" placeholder="{{ translate('contact_number') }}">
                                        <label for="message">{{ translate('contact_number') }}</label>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="login-form">

                                        <input type="text" name="subject" value="{{ old('subject') }}" class="balloon form-control inputdiv" rows="6" placeholder="{{ translate('short_title') }}">
                                        <label for="message">{{ translate('Subject') }}</label>
                                    </div>
                                </div>
                                <div class="login-form">

                                    <textarea name="message" id="message" class="balloon form-control inputdiv" rows="6" placeholder="{{ translate('type_your_message_here..') }}"> {{ old('subject') }} </textarea>
                                    <label for="message">{{ translate('message') }}</label>
                                </div>

                                 <div class="sendbtndiv">
                            <button type="submit" class="btn btn-primary rounded px-5 sendbtn">{{ translate('Send') }}</button>
                        </div>
                            </div>

                        </div>


                        <!---
                                @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                    <div id="recaptcha_element_contact" class="w-100" data-type="image"></div>
                                    <br/>
                                @else
                                    <div class="row p-2">
                                        <div class="col-6 pr-0">
                                            <input type="text" class="form-control form-control-lg border-0" name="default_captcha_value" value=""
                                                   placeholder="{{translate('Enter captcha value')}}" autocomplete="off">
                                        </div>
                                        <div class="col-6 input-icons rounded">
                                            <a onclick="javascript:re_captcha();">
                                                <img src="{{ URL('/contact/code/captcha/1') }}" class="input-field __h-40" id="default_recaptcha_id">
                                                <i class="bi bi-arrow-repeat icon cursor-pointer p-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                    -->

                    </form>
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>

    <div class="location__section">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3679.5034996632025!2d75.89543707600245!3d22.746688526565997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3962fd315b9b97b3%3A0x802fe3df65171895!2sAlphawizz%20Technologies%20Pvt.%20Ltd.!5e0!3m2!1sen!2sin!4v1705664157519!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</main>
<!-- End Main Content -->
@endsection

@push('script')
{{-- recaptcha scripts start --}}
@if(isset($recaptcha) && $recaptcha['status'] == 1)
<script type="text/javascript">
    var onloadCallback = function() {
        grecaptcha.render('recaptcha_element_contact', {
            'sitekey': '{{ \App\CPU\Helpers::get_business_settings('
            recaptcha ')['
            site_key '] }}'
        });
    };
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async
    defer></script>
<script>
    $("#getResponse").on('submit', function(e) {
        var response = grecaptcha.getResponse();

        if (response.length === 0) {
            e.preventDefault();
            toastr.error("{{ translate('Please_check_the_recaptcha') }}");
        }
    });
</script>
@else
<script type="text/javascript">
    function re_captcha() {
        $url = "{{ URL('/contact/code/captcha') }}";
        $url = $url + "/" + Math.random();
        document.getElementById('default_recaptcha_id').src = $url;
        console.log('url: ' + $url);
    }
</script>
@endif
{{-- recaptcha scripts end --}}
@endpush