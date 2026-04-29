<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

/*Auth::routes();*/
Route::get('authentication-failed', function () {
    $errors = [];
    array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);

    $response = [
        'status' => false,
        'message' => 'Unauthorized',
        'data' => [],
        'errors' => $errors
    ];

    return response()->json($response, 401);
})->name('authentication-failed');

/*
| Short storefront auth URLs on each tenant host (e.g. rahul.localhost:8000/login).
| POST targets match GET so forms can use the same path. Long URLs under /customer/auth/* remain valid.
*/
Route::group(['namespace' => 'Customer\Auth', 'middleware' => ['guest:customer']], function () {
    Route::get('signup', 'RegisterController@register')->name('customer.signup');
    Route::get('sign-up', 'RegisterController@register')->name('customer.sign-up.short');
    Route::get('login', 'LoginController@login')->name('customer.login.short');

    Route::post('signup', 'RegisterController@submit');
    Route::post('sign-up', 'RegisterController@submit');
    Route::post('login', 'LoginController@submit');
});

Route::group(['namespace' => 'Customer', 'prefix' => 'customer', 'as' => 'customer.'], function () {

    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/code/captcha/{tmp}', 'LoginController@captcha')->name('default-captcha');
        Route::get('login', 'LoginController@login')->name('login');

        Route::post('login-otp', 'LoginController@send_login_otp')->name('login-otp');
        Route::post('login-request-otp', 'LoginController@loginRequestOtp')->name('login-request-otp');
        Route::post('login-verify-otp', 'LoginController@loginVerifyOtp')->name('login-verify-otp');
        Route::post('login-with-otp', 'LoginController@login_with_otp')->name('login-with-otp');
        
        
        Route::post('login', 'LoginController@submit');
        Route::get('logout', 'LoginController@logout')->name('logout');


        Route::post('send-register-otp', 'LoginController@send_otp')->name('register-otp');

        Route::get('sign-up', 'RegisterController@register')->name('sign-up');
        Route::post('sign-up', 'RegisterController@submit')->name('final-register');

        Route::get('check/{id}', 'RegisterController@check')->name('check');

        // Customer Default Verify
        Route::post('verify', 'RegisterController@verify')->name('verify');

        // Customer Ajax Verify for theme except default
        Route::post('ajax-verify', 'RegisterController@ajax_verify')->name('ajax_verify');
        Route::post('resend-otp', 'RegisterController@resend_otp')->name('resend_otp');

        Route::get('update-phone/{id}', 'SocialAuthController@editPhone')->name('update-phone');
        Route::post('update-phone/{id}', 'SocialAuthController@updatePhone');

        Route::get('login/{service}', 'SocialAuthController@redirectToProvider')->name('service-login');
        Route::get('login/{service}/callback', 'SocialAuthController@handleProviderCallback')->name('service-callback');

        Route::get('recover-password', 'ForgotPasswordController@reset_password')->name('recover-password');
        Route::post('forgot-password', 'ForgotPasswordController@reset_password_request')->name('forgot-password');
        Route::get('otp-verification', 'ForgotPasswordController@otp_verification')->name('otp-verification');
        Route::post('otp-verification', 'ForgotPasswordController@otp_verification_submit');
        Route::get('reset-password', 'ForgotPasswordController@reset_password_index')->name('reset-password');
        Route::post('reset-password', 'ForgotPasswordController@reset_password_submit')->name('update-recover-password');
        Route::post('resend-otp-reset-password', 'ForgotPasswordController@ajax_resend_otp')->name('resend-otp-reset-password');
    });

    Route::group(['prefix' => 'payment-mobile'], function () {
        Route::get('/', 'PaymentController@payment')->name('payment-mobile');
    });

    Route::group([], function () {
        Route::get('set-payment-method/{name}', 'SystemController@set_payment_method')->name('set-payment-method');
        Route::get('set-shipping-method', 'SystemController@set_shipping_method')->name('set-shipping-method');
        Route::post('choose-shipping-address', 'SystemController@choose_shipping_address')->name('choose-shipping-address');
        Route::post('choose-shipping-address-other', 'SystemController@choose_shipping_address_other')->name('choose-shipping-address-other');
        Route::post('choose-billing-address', 'SystemController@choose_billing_address')->name('choose-billing-address');

        Route::group(['prefix' => 'reward-points', 'as' => 'reward-points.', 'middleware' => ['auth:customer']], function () {
            Route::get('convert', 'RewardPointController@convert')->name('convert');
        });
    });
});

