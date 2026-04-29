@extends('theme-views.layouts.register-app')
@section('title', $web_config['name']->value.' '.translate('Seller_Apply').' | '.$web_config['name']->value.'
'.translate(' Ecommerce'))
@push('css_or_js')
<link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
<link href="{{ asset('public/assets/back-end/css/style.css')}}" rel="stylesheet">
<style>
   body {
   padding-top: 50px;
   }
   .select2-container {
   max-width: 100%;
   width: 100% !important;
   }
   /* Clearfix for the steps container */
   .steps::after {
   content: "";
   display: table;
   clear: both;
   }
   .select2-container--default .select2-selection--single .select2-selection__rendered {
   font-size: 16px !important;
   }
   /* Style for the steps container */
   .steps {
   margin: 20px 0;
   }
   .steps ul {
   margin: 20px 0;
   text-align: center;
   display: flex;
   align-items: center;
   justify-content: center;
   }
   /* Style for the steps list */
   .steps ul {
   list-style-type: none;
   margin: 0;
   padding: 0;
   }
   .select2-container .select2-selection--single {
   height: 47px !important;
   }
   .actions.clearfix {}
   .form-group {
   position: relative;
   }
   /* Style for each step item */
   .steps li {
   width: 20%;
   height: 50px;
   margin-bottom: 25px;
   padding: 0 15px;
   position: relative;
   }
   .wizard-title {
   font-size: 18px;
   font-weight: 600;
   }
   .content.clearfix {
   display: block !important;
   }
   label.error {
   position: absolute;
   bottom: -36px;
   color: red;
   left: 0;
   }
   .actions.clearfix ul {
   display: flex;
   gap: 40px;
   list-style-type: none;
   padding: 0;
   }
   .actions.clearfix ul li a {
   background: #282828;
   width: 100px;
   display: flex;
   align-items: center;
   justify-content: center;
   height: 38px;
   color: #FFF;
   }
   .actions.clearfix ul li:nth-child(1) {
   display: none;
   }
   .btn-primary{
   background-color: #282828;
   border-color: #282828;
   }
   .btn-primary:hover{
   background-color: #282828;
   border-color: #282828;
   }
   /* Style for the connecting line */
   .steps li:not(:last-child)::after {
   content: "";
   position: absolute;
   left: 90px;
   top: 30%;
   border-top: 2px solid #ccc;
   width: 81%;
   z-index: 0;
   /* transform: translateY(-50%); */
   }
   .disabled {
   display: block;
   }
   .body.current {}
   .title {
   display: none;
   }
   .current.title {
   display: block;
   }
   .steps .current:not(:last-child)::after {
   border-top: 2px solid #282828;
   }
   /* Style for the current step */
   .steps li a {
   cursor: default !important;
   display: block;
   width: 100%;
   }
   /* Style for disabled steps */
   .steps li.disabled a {
   cursor: not-allowed;
   }
   /* Style for the step numbers */
   .steps li span.number {
   font-size: 16px;
   font-weight: bold;
   display: block;
   background: #DDDDDD;
   width: 30px;
   height: 30px;
   margin: auto;
   border-radius: 50%;
   display: flex;
   align-items: center;
   justify-content: center;
   }
   .steps li.current span.number {
   background: #282828;
   color: #fff;
   }
   /* Style for the audible information (hidden) */
   .steps li span.current-info {
   display: none;
   }
   .actions.clearfix ul {
   margin-top: 10px;
   }
   /* Hover effect on step links */
   .steps li a:hover {
   background-color: #ddd !;
   }
   label.error {
   position: absolute;
   bottom: -28px;
   color: red;
   }
   img#default_recaptcha_id_regi {
   width: auto;
   }
   @media only screen and (max-width: 600px) {
   .steps ul {
   overflow-x: scroll !important;
   overflow-y: auto !important;
   justify-content: start !important;
   }
   .steps li:not(:last-child)::after {
   left: 66px !important;
   width: 70% !important;
   }
   .bg-aplly-pictrure {
   display: none !important;
   }
   .steps li {
   width: 100% !important;
   }
   .steps ul::-webkit-scrollbar {
   height: 0px !important;
   width: 0px !important;
   }
   }
</style>
@endpush
@section('content')
<!-- Main Content -->
<main class="file-control-heightviewpage">
   @php($e_commerce_logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)

   <div class="container">
      <div class="card height__control">
         <div class="card-body p-sm-4">
            <div class="row g-4">
               <div class="col-lg-6">
                  <div class="bg-light p-3 p-sm-4 rounded h-100 bg-aplly-pictrure">
                     <div class="d-flex justify-content-center w-100">
                        <div class="">
                           <div class="my-4 text-center">
                              <img width="100" src="{{theme_asset('public/assets/back-end/img/login-img/login-img.webp')}}"
                                 loading="lazy" alt="" class="dark-support" />
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-6 col-xl-6 px-md-5">
                  <form id="seller-registration" action="{{route('shop.apply')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                     @csrf
                     <div class="wizard">
                        <h3 class="wizard-title">{{ translate('Seller_Info') }}</h3>
                        <section>
                           <div class="form-heading">
                           </div>
                           <div class="row g-4">
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="firstName">{{translate('Name')}} *</label>
                                    <input class="form-control" type="text" id="firstName" name="f_name"
                                       value="{{old('f_name')}}"
                                       placeholder="{{translate('Ex').':'.translate(' Jhon')}}"
                                       autocomplete="off" title="Only alphabets allowed" maxlength="60" pattern="^[A-Za-z\s]+$" required />
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="email2">{{translate('Email')}} *</label>
                                    <input class="form-control" type="email" id="email2" name="email"
                                       value="{{old('email') ?? ''}}" placeholder="{{translate('Enter_email')}}"
                                       autocomplete="false" maxlength="70" onkeypress="return event.which != 32"
                                       required />
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="tel">{{translate('Phone')}} *</label>
                                    <div class="d-flex justify-content-end align-items-center">
                                       <input class="form-control" type="text" id="tel" name="phone"
                                          value="{{old('phone')}}"
                                          placeholder="{{translate('Enter_phone_number')}}"
                                          onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                          minlength="10" maxlength="10"  title="Only alphabets allowed" required />
                                       <button type="button" class="btn btn-primary btn-sm send-otp form-control  ">{{translate('Send OTP')}}</button>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="otp">{{translate('Enter OTP')}} *</label>
                                    <div class="d-flex justify-content-between align-items-center ">
                                       <input type="hidden" name="verified_otp" value="0">
                                       <input class="form-control" type="text" id="otp" name="otp"
                                          value="{{ old('otp') }}" placeholder="{{ translate('Enter OTP') }}"
                                          onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                          minlength="4" maxlength="4" required />
                                       <button type="button"
                                          class="btn btn-primary btn-sm  verify-otp form-control">{{translate('Verify OTP')}}</button>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-12 mt-md-3 mt-2">
                                 <div class="media gap-3 align-items-center">
                                 </div>
                              </div>
                           </div>
                        </section>
                        <h3 class="wizard-title">{{translate('Bussiness_Info')}}</h3>
                        <section>
                           <div class="form-heading">
                              <h4>Enter your detail</h4>
                           </div>
                           <div class="row g-4">
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="Bussiness_Email_ID">{{translate('Bussiness_Email_ID')}}
                                    *</label>
                                    <input class="form-control" type="email" id="Bussiness_Email_ID"
                                       name="bussiness_email_id"
                                       placeholder="{{ translate('Ex') }}: {{ translate('abc@gmail.com') }}"
                                       value="{{old('bussiness_email_id')}}" maxlength="70"
                                       autocomplete="off" onkeypress="return event.which != 32"
                                       required />
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="Bussiness_Phone">{{translate('Bussiness_Phone')}} </label>
                                    <input class="form-control" type="text" id="Bussiness_Phone"
                                       name="bussiness_phone"
                                       placeholder="{{ translate('Ex') }}: {{ translate('+91-8962277899') }}"
                                       value="{{old('bussiness_phone')}}"
                                       onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                       minlength="10" maxlength="10" required />
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="storeName">{{translate('Company_Name_or_Bussiness_name')}}
                                    *</label>
                                    <input class="form-control" type="text" id="storeName" name="shop_name"
                                       placeholder="{{translate('Ex')}}: {{translate('ABS & Company')}}"
                                       value="{{old('shop_name')}}" required />
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="gst_number">{{translate('GSTIN') }}</label>
                                    <input class="form-control" type="text" id="gst_number"
                                       name="gst_number" placeholder="{{ translate('GSTIN') }}" pattern="\"
                                       value="{{old('gst_number')}}" minlength="15" maxlength="15" required
                                       />
                                 </div>
                              </div>
                           </div>
                        </section>
                        <!-- <h3 class="wizard-title">{{translate('create_Password')}}</h3>
                           <section>
                               <div class="row g-4 d-none">
                                   <div class="col-lg-6">
                                       <div class="form-group">
                                           <label for="password">{{translate('Password')}} *</label>
                                           <div class="input-inner-end-ele">
                                               <input class="form-control" type="password" id="password"
                                                   name="password" value="{{old('password')}}"
                                                   placeholder="{{translate('Enter_password')}}"  autocomplete="false"/>
                           
                                               <i class="bi bi-eye-slash-fill togglePassword"></i>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="col-lg-6">
                                       <div class="form-group">
                                           <label for="repeat_password">{{translate('Confirm_Password')}} *</label>
                                           <div class="input-inner-end-ele">
                                               <input class="form-control" type="password" id="repeat_password"
                                                   name="repeat_password"
                                                   placeholder="{{translate('repeat_password')}}"  />
                           
                                               <i class="bi bi-eye-slash-fill togglePassword"></i>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </section> -->
                        <h3>{{translate('Address Information')}}</h3>
                        <section>
                           <div class="row g-4">
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="storeAddress">{{translate('Store_Address')}} *</label>
                                    <input class="form-control" type="text" id="storeAddress"
                                       name="shop_address" value="{{old('shop_address')}}"
                                       placeholder="{{translate('Ex').': '.translate('Shop').'-12, '.translate('Road').'-8'}}"
                                       required />
                                 </div>
                              </div>
                              <input type="hidden" name="country" value="India">
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="storeAddress">{{translate('State')}} </label>
                                    <select name="state" id="State" class="form-control">
                                    @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state', $selectedStateId ?? '') == $state->id ? 'selected' : '' }}>
                                    {{ $state->name }}
                                    </option>
                                    @endforeach
                                    </select>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="storeAddress">{{translate('City')}} </label>
                                    <select name="city" id="city" class="form-control">
                                       <option value="">-- Select City --</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="storeAddress">{{translate('Area')}} </label>
                                    <select name="area" id="area" class="js-example-responsive balloon form-control">
                                       <option value="">{{ translate('area') }}</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-12">
                                <label class="custom-checkbox">
                                <input id="acceptTerms" name="acceptTerms" type="checkbox" />
                                {{translate('I_agree_with_the')}} 
                                <a target="_blank" href="{{route('terms')}}">{{translate('terms_and_condition')}}.</a>
                                </label>
                            </div>
                           </div>
                        </section>
                        <!-- <h3>{{translate('Banking Information')}}</h3> -->
                        <!-- <section>
                           <div class="row g-4">
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="Bank_Name">{{translate('Bank_Name')}} *</label>
                                    <input class="form-control" type="text" id="Bank_Name" name="bank_name"
                                       value="{{ old('bank_name') }}"pattern="^[A-Za-z\s]+$" title="Only alphabets allowed" placeholder="{{translate('Ex').': State bank of India'}}"
                                       required />
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="Bank_branch">{{translate('Bank_branch')}} *</label>
                                    <input class="form-control" type="text" id="Bank_branch"
                                       name="bank_branch" value="{{ old('bank_branch') }}"
                                       placeholder="{{translate('Ex').': Palasia'}}" pattern="^[A-Za-z\s]+$" title="Only alphabets allowed" required />
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label value="">{{translate('Account_type')}} </label>
                                    <select name="account_type"
                                       class="js-example-responsive balloon form-control" required>
                                       <option value="">{{translate('Account_type')}} </option>
                                       <option value="Savings">Savings</option>
                                       <option value="Current">Current</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="Bank_Address">{{translate('Bank_Address')}} *</label>
                                    <input class="form-control" type="text" id="Bank_Address"
                                       name="bank_address" value="{{ old('bank_address') }}"
                                       placeholder="{{translate('Ex').': Palasia, Indore, Madhya Pradesh'}}"
                                       required />
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="Account_number">{{translate('Account_number')}} *</label>
                                    <input class="form-control" type="number" id="Account_number"
                                       name="account_number" pattern="^[0-9]+$" title="Only numbers allowed" value="{{old('account_number')}}"
                                       placeholder="{{translate('Ex').': 10235479658965'}}" required />
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="IFSC_CODE">{{translate('IFSC_CODE')}} *</label>
                                    <input class="form-control" type="text" id="IFSC_CODE" name="ifsc_code"
                                       value="{{old('ifsc_code')}}"
                                       placeholder="{{translate('Ex').': State bank of India'}}"
                                       required />
                                 </div>
                              </div>
                              <div class="col-12">
                                 <label class="custom-checkbox">
                                 <input id="acceptTerms" name="acceptTerms" type="checkbox"  />
                                 {{translate('I_agree_with_the')}} <a target="_blank"
                                    href="{{route('terms')}}">{{translate('terms_and_condition')}}.</a>
                                 </label>
                              </div>
                           </div>
                        </section> -->
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</main>
<!-- End Main Content -->
@endsection
@push('script')
<!-- Page Level Scripts -->
<script src="{{theme_asset('assets/plugins/jquery-step/jquery.validate.min.js')}}"></script>
<script src="{{theme_asset('assets/plugins/jquery-step/jquery.steps.min.js')}}"></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
@if($recaptcha['status'] == '1')
<script>
   var onloadCallback = function() {
       let reg_id = grecaptcha.render('recaptcha_element_seller_regi', {
           'sitekey': "{{ $recaptcha['site_key'] }}"
       });
       let login_id = grecaptcha.render('recaptcha_element_seller_login', {
           'sitekey': "{{ $recaptcha['site_key '] }}"
       });
   
       $('#recaptcha_element_seller_regi').attr('data-reg-id', reg_id);
       $('#recaptcha_element_seller_login').attr('data-login-id', login_id);
   };
</script>
@else
<script>
   function re_captcha_seller_regi() {
       $url = "{{ URL('/seller/auth/code/captcha/') }}";
       $url = $url + "/" + Math.random() + '?captcha_session_id=default_recaptcha_id_seller_regi';
   
       document.getElementById('default_recaptcha_id_regi').src = $url;
       // console.log('url: ' + $url);
   }
</script>
@endif
<script></script>
<script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
<script>
   let otp = ""
   $(document).on('click', '.send-otp, .resend-otp', function() {
       let evt = $(this)
       let mobile = $('input[name=phone]').val()
       otp = Math.floor(1000 + Math.random() * 9000);
       if (mobile.length > 9) {
   
           $.ajax({
               type: "POST",
               url: "{{ route('seller.register.valildate-mobile') }}",
               data: {
                   phone: mobile
               },
               dataType: "json",
               headers: {
                   'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
               },
               success: function(response) {
                   if (response.status == true) {
   
   
                       swal.fire(`OTP Sent success (${otp})`, '', 'success')
                       $('input[name=otp]').val(otp)
                   }
               },
               error: function(xhr, status, error) {
                   swal.fire(`${xhr.responseJSON.errors.phone[0]}`, '', 'error')
                   $('input[name=phone]').val("")
               }
           });
       } else {
           swal.fire(`Invalid Mobile No.`, '', 'error')
       }
   })
   
   $(document).on('click', '.verify-otp', function() {
       if($('input[name=otp]').val() == otp) {
           $('input[name=verified_otp]').val(1)
           swal.fire('OTP verified success', '', 'success')
   
           $(this).text("Verified")
           $(this).removeClass('verify-otp')
   
           $('.resend-otp').remove()
   
           return false
       }
   })
   
   $(document).on('submit', '#seller-registration', function(e) {
   
       console.log('form submit');
       e.preventDefault()
       var formdata = new FormData(this)
       $.ajax({
           type: "POST",
           url: $(this).attr('action'),
           data: formdata,
           dataType: "json",
           processData: false,
           contentType: false,
           headers: {
               'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
           },
           success: function(response) {
               if (response.status == true) {
                   swal.fire(response.message, '', 'success').then(function() {
                       window.location.href = "{{ route('seller.auth.login') }}"
                   })
               }
           },
           error: function(error) {
               // console.log(error)
               let errorText = ""
               isUniqueEmailValidate = true
               $.each(error.responseJSON.errors, function(ind, elm) {
                   errorText += `${elm[0]}\n`
               })
               swal.fire(errorText, '', 'error')
           }
       });
   })
   
   $(document).on('blur', 'input[name=email]', function() {
       if($(this).val().length > 0) {
           $.ajax({
               type: "POST",
               url: "{{ route('seller.register.valildate-email') }}",
               data: {
                   email: $(this).val()
               },
               dataType: "json",
               headers: {
                   'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
               },
               success: function(response) {
                   if (response.status == true) {
   
                   }
               },
               error: function(xhr, status, error) {
                   swal.fire(`${xhr.responseJSON.errors.email[0]}`, '', 'error')
                   $('input[name=email]').val("")
                   // $('<label id="email2-error" class="error" for="email2">' + xhr.responseJSON.errors.email[0] + '</label>').insertBefore('#email2');
               }
           });
       }
   })
   
   var isUniqueEmailValidate = false
   
   // Multi Step Form
   var form = $("#seller-registration");
   
   const validateEmail = (email) => {
       return email.match(
           /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
       );
   };
   
   function validateFormStep(currentIndex) {
       if(currentIndex == 0) {
           console.log('formstep-',currentIndex);
   const fName = $('input[name=f_name]').val() || '';
            if (fName.length <= 0) {
                swal.fire('Please enter vendor name', '', 'error');
                return false;
            }
            if (!/^[A-Za-z\s]+$/.test(fName)) {
                swal.fire('Name should contain only alphabets', '', 'error');
                return false;
            }
            if (fName.length > 60) {
                swal.fire('Name should not exceed 60 characters', '', 'error');
                return false;
            }
            const email = $('input[name=email]').val() || '';
            if (email.length <= 0) {
                swal.fire('Please enter email', '', 'error');
                return false;
            }
            if (email.length > 70) {
                swal.fire('Email should not exceed 70 characters', '', 'error');
                return false;
            }
            if (email.length > 0 && !validateEmail(email)) {
                swal.fire('Please enter valid email', '', 'error');
                return false;
            }
           const phone = $('input[name=phone]').val() || '';
            if (phone.length <= 0) {
                swal.fire('Please enter phone number', '', 'error');
                return false;
            }
            const otp = $('input[name=otp]').val() || '';
            if (otp.length <= 0) {
                swal.fire('Please enter otp', '', 'error');
                return false;
            }
            const verifiedOtp = $('input[name=verified_otp]').val() || '0';
            if (verifiedOtp == '0') {
                swal.fire('Please verify otp', '', 'error');
                return false;
            }
   
       }

       if(currentIndex == 1) {
            console.log('formstep-',currentIndex);
            const businessEmail = $('input[name=bussiness_email_id]').val() || '';
            if (businessEmail.length <= 0) {
                swal.fire('Please enter bussiness email id', '', 'error');
                return false;
            }
            if (businessEmail.length > 70) {
                swal.fire('Business email should not exceed 70 characters', '', 'error');
                return false;
            }
            const shopName = $('input[name=shop_name]').val() || '';
            if (shopName.length <= 0) {
                swal.fire('Please enter shop name', '', 'error');
                return false;
            }
            const businessPhone = $('input[name=bussiness_phone]').val() || '';
            if (businessPhone.length < 10) {
                swal.fire('Business phone must be exactly 10 digits', '', 'error');
                return false;
            }

            const gstNumber = $('input[name=gst_number]').val() || '';
            if (gstNumber.length < 15) {
                swal.fire('GST Number must be exactly 15 characters', '', 'error');
                return false;
            }

            const businessRegistrationNumber = $('input[name=bussiness_registeration_number]').val() || '';

            // const gstNumber = $('input[name=gst_number]').val() || '';
            const website = $('input[name=website]').val() || '';
   
        }

       if(currentIndex == 2) {
           console.log('formstep-',currentIndex);
           const shopAddress = $('input[name=shop_address]').val() || '';
           if (shopAddress.length <= 0) {
               swal.fire('Please enter shop address', '', 'error');
               return false;
           }
   
           const stateVal = $('select[name=state] option:selected').val() || '';
           if (stateVal.length <= 0 || stateVal <= 0) {
               swal.fire('Please select state', '', 'error');
               return false;
           }
   
           const cityVal = $('select[name=city] option:selected').val() || '';
           if (cityVal.length <= 0 || cityVal <= 0) {
               swal.fire('Please select city', '', 'error');
               return false;
           }
   
   
           const areaVal = $('select[name=area] option:selected').val() || '';
           if (areaVal.length <= 0 || areaVal <= 0) {
               swal.fire('Please select area', '', 'error');
               return false;
           }

            if (!$('#acceptTerms').is(':checked')) {
            swal.fire('You must agree to terms and conditions', '', 'error');
            return false;
        }
    }
    return true;
}

   
//        }
//        if(currentIndex == 3) {
//            console.log('formstep-',currentIndex);
//           const bankName = $('input[name=bank_name]').val() || '';
//             if (!/^[A-Za-z\s]+$/.test(bankName)) {
//                 swal.fire('Bank name must contain only alphabets', '', 'error');
//                 return false;
//             }

//             const bankBranch = $('input[name=bank_branch]').val() || '';
//             if (!/^[A-Za-z\s]+$/.test(bankBranch)) {
//                 swal.fire('Bank branch must contain only alphabets', '', 'error');
//                 return false;
//             }

//             const accountNumber = $('input[name=account_number]').val() || '';
//             if (!/^[0-9]+$/.test(accountNumber)) {
//                 swal.fire('Account number must contain only numbers', '', 'error');
    
//        }
//     }
//        return true
//    }

   // Form Wizard
   form.children(".wizard").steps({
       headerTag: "h3",
       bodyTag: "section",
       onStepChanging: function(event, currentIndex, newIndex) {
   
           console.log('newIndex',newIndex);
   
           $('[href="#finish"]').addClass('disabled');
   
           $('#acceptTerms').click(function() {
               if ($(this).is(':checked')) {
                   $('[href="#finish"]').removeClass('disabled');
               } else {
                   $('[href="#finish"]').addClass('disabled');
               }
           });
   
           if (currentIndex > newIndex) {
               return true;
           }
           if (currentIndex < newIndex) {
               form.find('.body:eq(' + newIndex + ') label.error').remove();
               form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
           }
   
           form.validate().settings.ignore = ":disabled,:hidden";
           return validateFormStep(currentIndex);
           return form.valid();
       },
       
       onFinishing: function(event, currentIndex) {
           console.log('currentIndex',currentIndex);
           form.validate().settings.ignore = ":disabled";
           return validateFormStep(currentIndex);
           return form.valid();
       },
       onFinished: function(event, currentIndex) {
        console.log('currentIndex1',currentIndex);
            
        // setTimeout(function() {
        //     $('#seller-registration').trigger('submit');
        // }, 0);
        console.log('Wizard finished, submitting via AJAX...');

        var form = $('#seller-registration')[0]; // raw DOM form
        var formdata = new FormData(form);

        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            data: formdata,
            dataType: "json",
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')
            },
            success: function(response) {
                if (response.status == true) {
                    swal.fire(response.message, '', 'success').then(function() {
                        window.location.href = "{{ route('seller.auth.login') }}";
                    });
                }
            },
            error: function(error) {
                let errorText = "";
                $.each(error.responseJSON.errors, function(ind, elm) {
                    errorText += `${elm[0]}\n`;
                });
                swal.fire(errorText, '', 'error');
            }
        });
        console.log('AfterSubmit');
       }
   });
   
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
                   dataType: "json",
                   success: function(response) {
                       $('#city').empty().append('<option value="">-- Select City --</option>');
                       if (response.status === true && response.data.length > 0) {
                           $.each(response.data, function(key, city) {
                               let selected = (selectedCity && selectedCity == city.id) ? 'selected' : '';
                               $('#city').append(
                                   `<option value="${city.id}" data-id="${city.id}" ${selected}>${city.name}</option>`
                               );
                           });
                       } else {
                           $('#city').append('<option value="">No cities found</option>');
                       }
   
                 
                       $('#area').empty().append('<option value="">-- Select Area --</option>');
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
                   dataType: "json",
                   success: function (response) {
                       $('#area').empty().append('<option value="">-- Select Area --</option>');
                       if (response.status === true && response.data.length > 0) {
                           $.each(response.data, function (key, area) {
                               let selected = (selectedArea && selectedArea == area.id) ? 'selected' : '';
                               $('#area').append(
                                   `<option value="${area.id}" data-id="${area.id}" ${selected}>${area.name}</option>`
                               );
                           });
                       } else {
                           $('#area').append('<option value="">No areas found</option>');
                       }
                   }
               });
           } else {
               $('#area').empty().append('<option value="">-- Select Area --</option>');
           }
       }
   
    
       $(document).on('change', '#State', function () {
           let stateID = $(this).val(); 
           loadCities(stateID);
       });
   
     
       $(document).on('change', '#city', function () {
           let cityId = $(this).val();
           loadAreas(cityId);
       });
   
      
       let preSelectedState = $('#State').val(); 
       let preSelectedCity  = "{{ old('city_id', $selectedCityId ?? '') }}"; 
       let preSelectedArea  = "{{ old('area_id', $selectedAreaId ?? '') }}"; 
   
       if (preSelectedState) {
           loadCities(preSelectedState, preSelectedCity);
       }
   
       if (preSelectedCity) {
           loadAreas(preSelectedCity, preSelectedArea);
       }
   });
</script>
@endpush