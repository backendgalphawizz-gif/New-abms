<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('super-admin.auth.login');
});

Route::group(['namespace' => 'SuperAdmin', 'prefix' => 'super-admin', 'as' => 'super-admin.'], function () {
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit')->name('submit');
        Route::get('logout', 'LoginController@logout')->name('logout');
    });

    Route::group(['middleware' => ['super_admin']], function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard', 'DashboardController@index');

        Route::get('subdomains/{entityId}/dashboard', 'SubdomainManagementController@dashboard')->name('subdomains.dashboard');
        Route::get('subdomains/{entityId}/applications', 'SubdomainManagementController@applications')->name('subdomains.applications');
        Route::get('subdomains/{entityId}/orders', 'SubdomainManagementController@orders')->name('subdomains.orders');
        Route::get('subdomains/{entityId}/products', 'SubdomainManagementController@products')->name('subdomains.products');
        Route::get('subdomains/{entityId}/customers', 'SubdomainManagementController@customers')->name('subdomains.customers');
        Route::get('subdomains/{entityId}/sellers', 'SubdomainManagementController@sellers')->name('subdomains.sellers');
        Route::get('subdomains/{entityId}/employees', 'SubdomainManagementController@employees')->name('subdomains.employees');
        Route::get('subdomains/{entityId}', function (string $entityId) {
            return redirect()->route('super-admin.subdomains.dashboard', $entityId);
        })->name('subdomains.show');

        // Legacy URLs (pre subdomains/* routes)
        Route::get('subadmins', function () {
            return redirect()->route('super-admin.dashboard');
        });
        Route::get('subadmins/{entityId}/dashboard', function (string $entityId) {
            return redirect()->route('super-admin.subdomains.dashboard', $entityId, 301);
        });
        Route::get('subadmins/{entityId}/applications', function (string $entityId) {
            return redirect()->route('super-admin.subdomains.applications', $entityId, 301);
        });
        Route::get('subadmins/{entityId}/orders', function (string $entityId) {
            return redirect()->route('super-admin.subdomains.orders', $entityId, 301);
        });
        Route::get('subadmins/{entityId}/products', function (string $entityId) {
            return redirect()->route('super-admin.subdomains.products', $entityId, 301);
        });
        Route::get('subadmins/{entityId}/customers', function (string $entityId) {
            return redirect()->route('super-admin.subdomains.customers', $entityId, 301);
        });
        Route::get('subadmins/{entityId}/sellers', function (string $entityId) {
            return redirect()->route('super-admin.subdomains.sellers', $entityId, 301);
        });
        Route::get('subadmins/{entityId}/employees', function (string $entityId) {
            return redirect()->route('super-admin.subdomains.employees', $entityId, 301);
        });
        Route::get('subadmins/{entityId}', function (string $entityId) {
            return redirect()->route('super-admin.subdomains.dashboard', $entityId, 301);
        });

        Route::resource('entities', 'EntityController')->only(['index', 'create', 'store', 'destroy']);
        Route::resource('iso-standards', 'IsoStandardController')->except(['show']);
        Route::get('auditors', 'AuditorController@index')->name('auditors.index');
        Route::get('auditors/create', 'AuditorController@create')->name('auditors.create');
        Route::post('auditors', 'AuditorController@store')->name('auditors.store');
        Route::get('auditors/{id}/edit', 'AuditorController@edit')->name('auditors.edit');
        Route::put('auditors/{id}', 'AuditorController@update')->name('auditors.update');
        Route::post('auditors/{id}/profile-review', 'AuditorController@reviewProfile')->name('auditors.profile-review');
        Route::get('auditors/{id}/status/{status}', 'AuditorController@status')->name('auditors.status');
        Route::delete('auditors/{id}', 'AuditorController@destroy')->name('auditors.destroy');
    });
});

/*
 * Same Super Admin area as /super-admin/… but under /super_admin/… (underscore).
 * Explicit routes avoid relying on a catch-all regex, which can fail to match in some setups.
 */
Route::group(['namespace' => 'SuperAdmin', 'prefix' => 'super_admin', 'middleware' => ['super_admin']], function () {
    Route::get('subdomains/{entityId}/dashboard', 'SubdomainManagementController@dashboard');
    Route::get('subdomains/{entityId}/applications', 'SubdomainManagementController@applications');
    Route::get('subdomains/{entityId}/orders', 'SubdomainManagementController@orders');
    Route::get('subdomains/{entityId}/products', 'SubdomainManagementController@products');
    Route::get('subdomains/{entityId}/customers', 'SubdomainManagementController@customers');
    Route::get('subdomains/{entityId}/sellers', 'SubdomainManagementController@sellers');
    Route::get('subdomains/{entityId}/employees', 'SubdomainManagementController@employees');
    Route::get('subdomains/{entityId}', function (string $entityId) {
        return redirect()->route('super-admin.subdomains.dashboard', $entityId);
    });
});

Route::redirect('super_admin', '/super-admin/dashboard', 302);

Route::middleware(['web'])->get('super_admin/{path}', function (Request $request, string $path) {
    $target = $request->getSchemeAndHttpHost().'/super-admin/'.ltrim($path, '/');
    if ($request->getQueryString()) {
        $target .= '?'.$request->getQueryString();
    }

    return redirect()->to($target, 301);
})->where('path', '.*');
