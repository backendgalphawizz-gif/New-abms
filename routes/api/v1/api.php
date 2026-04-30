<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['namespace' => 'api\v1', 'prefix' => 'v1', 'middleware' => ['api_lang']], function () {
    Route::post('send-notification', 'BrandController@send_notification');
    Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
        Route::post('register', 'PassportAuthController@register');
        Route::post('login', 'PassportAuthController@login');
        
        Route::post('assessor-register', 'PassportAuthController@assessorRegister');
        Route::post('assessor-login', 'PassportAuthController@assessorLogin');
        Route::post('auditor-login', 'PassportAuthController@auditorLogin');
        Route::post('auditor-forgot-password', 'PassportAuthController@auditorForgotPassword');

        Route::post('send-otp', 'PassportAuthController@send_otp');
        Route::post('send-login-otp', 'PassportAuthController@send_login_otp');
        Route::post('verify-login-otp', 'PassportAuthController@verify_login_otp');

        Route::post('check-phone', 'PhoneVerificationController@check_phone');
        Route::post('resend-otp-check-phone', 'PhoneVerificationController@resend_otp_check_phone');
        Route::post('verify-phone', 'PhoneVerificationController@verify_phone');

        Route::post('check-email', 'EmailVerificationController@check_email');
        Route::post('resend-otp-check-email', 'EmailVerificationController@resend_otp_check_email');
        Route::post('verify-email', 'EmailVerificationController@verify_email');

        Route::post('forgot-password', 'ForgotPassword@reset_password_request');
        Route::post('verify-otp', 'ForgotPassword@otp_verification_submit');
        Route::put('reset-password', 'ForgotPassword@reset_password_submit');

        Route::any('social-login', 'SocialAuthController@social_login');
        Route::post('update-phone', 'SocialAuthController@update_phone');
    });

    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@configuration');
        Route::get('app-home', 'ConfigController@appHome');
        Route::get('privacy-policy-pages', 'ConfigController@Policies');
    });

    Route::get('applications/get-scheme', 'FormController@schemeList');
    Route::post('applications/get-area', 'FormController@schemeAreaList');
    Route::get('applications/get-assessors', 'FormController@AssessorList');
    Route::post('applications/store-media','FormController@storeMedia');
    Route::post('applications/get_checklist_structure','FormController@getChecklistStructure');

    Route::group(['prefix' => 'applications', 'middleware' => 'auth:api'], function () {
        
        Route::get('get-documents','FormController@documents');

        Route::post('application-store','FormController@applicationStore');
        Route::get('my-applied-applications','FormController@applicationAppliedList');
        Route::get('applications-detail/{id}','FormController@applicationDetail');
        Route::post('application-update','FormController@applicationUpdate');
        Route::post('update-assessor-status','FormController@updateAssessorStatus');
        
        Route::get('dashboard','FormController@dashboardCounter');

        //checklist routes
        // Route::post('get_checklist_structure','FormController@getChecklistStructure');
        Route::post('submit_checklist','FormController@submitChecklist');
        Route::post('update_checklist_clause','FormController@updateChecklistClause');
        

        // finding routes
        Route::get('list-assessment-findings/{id}','FormController@listAssessmentFinding');
        Route::post('update-finding','FormController@updateFindingResponse');
        Route::get('view-findings/{id}','FormController@viewFindings');


        Route::post('update_payment_detail','FormController@updatePaymentDetail');
        

        //witness route
        Route::get('get-application-witness/{id}','FormController@applicationWitness');
        Route::post('update-witness','FormController@updatewitness');

        // test route 
        Route::post('test-scheme','FormController@testScheme');
        Route::post('dummy_checklist','FormController@testCheckList');
        Route::post('get_clause','FormController@getClauseDetails');
        
    });


    Route::group(['prefix'=>'assessor','middleware' => 'assessor_api_auth'], function() {
        Route::get('get-profile','AssessorController@getProfile');
        Route::get('iso-standards','AssessorController@isoStandards');
        Route::get('get-chat','AssessorController@getChat');
        Route::post('send-message','AssessorController@sendMessage');
        Route::delete('delete-chat/{id}','AssessorController@deleteChat');

        Route::post('update-profile','AssessorController@updateProfile');
        Route::post('upload-profile-documents','AssessorController@uploadProfileDocuments');
        Route::post('update-resume','AssessorController@updateResume');

        Route::get('get-applications','AssessorController@getApplications');
        Route::post('update-application-status','AssessorController@updateApplicationStatus');
        Route::get('get-applications-details','AssessorController@getApplicationDetail');
        Route::post('update-assessor-comment','AssessorController@updateComment');
        Route::post('update-assessment','AssessorController@updateAssessment');
        Route::post('checklist-comment-history','AssessorController@checklistCommentHistory');
        Route::post('update-application-remark','AssessorController@updateApplicationRemark');

        Route::post('add-assessment-findings','AssessorController@addAssessmentFinding');
        Route::get('list-assessment-findings/{id}/{type}','AssessorController@listAssessmentFinding');
        Route::post('update-finding-response','AssessorController@updateFindingResponse');
        Route::get('view-findings/{id}','AssessorController@viewFindings');
        
        Route::post('witness-request','AssessorController@witnessRequest');
        Route::get('my-witness-request-list','AssessorController@witnessRequestList');

        Route::get('get-application-witness/{id}','AssessorController@applicationWitness');
        Route::post('update-witness-assessment','AssessorController@updateWitnessAssessment');
        // Route::get('get-application-witness/{id}','AssessorController@applicationWitness');

        Route::get('trainings','AssessorController@trainingList');
        Route::get('training-details','AssessorController@trainingDetail');
        Route::post('start-training','AssessorController@startTraining');
        Route::post('complete-training','AssessorController@completeTraining');

    });

    Route::group(['prefix' => 'countries'], function () {
        Route::get('/', 'CustomerController@getCountries');
    });
    Route::group(['prefix' => 'areas'], function () {
        Route::get('/', 'CustomerController@getAreas');
    });

    Route::group(['prefix' => 'states'], function () {
        Route::get('/', 'CustomerController@getStates');
    });

    Route::group(['prefix' => 'cities'], function () {
        Route::get('/', 'CustomerController@getCities');
    });

    Route::get('faq', 'GeneralController@faq');
    Route::get('header-colors', 'GeneralController@headerColors');
    Route::get('header-text', 'GeneralController@header_text_update');
    Route::get('pages/{slug}', 'GeneralController@faq');
    Route::post('training/attempt', 'GeneralController@saveAttempt');
    Route::post('training/assessor-attempt', 'GeneralController@assessorTrainingAttempt');

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', 'NotificationController@get_notifications')->middleware('auth:api');
        Route::get('mark-read', 'NotificationController@mark_as_read')->middleware('auth:api');
        
    });


    Route::group(['prefix' => 'customer', 'middleware' => 'auth:api'], function () {
        Route::get('info', 'CustomerController@info');
        Route::post('company-profile', 'CustomerController@companyProfileUpdate');
        Route::post('update-profile', 'CustomerController@update_profile');
        Route::post('update-profile-image', 'CustomerController@upload_profile_image');
        
        Route::put('cm-firebase-token', 'CustomerController@update_cm_firebase_token');
        Route::get('account-delete/{id}','CustomerController@account_delete');

        Route::put('update-password', 'CustomerController@update_password');
        Route::put('reset-password', 'CustomerController@reset_password');


        Route::group(['prefix' => 'support-ticket'], function () {
            Route::post('create', 'CustomerController@create_support_ticket');
            Route::get('get', 'CustomerController@get_support_tickets');
            Route::get('conv/{ticket_id}', 'CustomerController@get_support_ticket_conv');
            Route::post('reply/{ticket_id}', 'CustomerController@reply_support_ticket');
        });

        // Chatting
        Route::group(['prefix' => 'chat'], function () {
            Route::get('list/{type}', 'ChatController@list');
            Route::get('get-messages/{type}/{id}', 'ChatController@get_message');
            Route::post('send-message/{type}', 'ChatController@send_message');
            Route::get('search/{type}', 'ChatController@search');
            Route::delete('delete-chat/{id}','ChatController@deleteChat');
            
            Route::get('get-admin-messages/{type}/{id}', 'ChatController@get_admin_message');
            Route::post('send-admin-messages/{type}', 'ChatController@send_admin_message');
            

        });

    });

    Route::group(['prefix' => 'mapapi'], function () {
        Route::get('place-api-autocomplete', 'MapApiController@place_api_autocomplete');
        Route::get('distance-api', 'MapApiController@distance_api');
        Route::get('place-api-details', 'MapApiController@place_api_details');
        Route::get('geocode-api', 'MapApiController@geocode_api');
    });

    Route::group(['prefix' => 'contact', 'as' => 'contact.'], function () {
        Route::post('store', 'CustomerController@contact_store')->name('store');
        // Route::get('/code/captcha/{tmp}', 'WebController@captcha')->name('default-captcha');
    });

});
