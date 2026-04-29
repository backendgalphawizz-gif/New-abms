<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'api\v1', 'prefix' => 'v1', 'middleware' => ['api_lang']], function () {
    Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
        Route::post('auditor-login', 'PassportAuthController@auditorLogin');
        Route::post('auditor-forgot-password', 'PassportAuthController@auditorForgotPassword');
    });

    Route::group(['prefix' => 'assessor', 'middleware' => 'assessor_api_auth'], function () {
        Route::get('get-profile', 'AssessorController@getProfile');
        Route::post('update-profile', 'AssessorController@updateProfile');
    });
});
