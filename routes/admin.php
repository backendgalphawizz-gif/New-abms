<?php

use Illuminate\Support\Facades\Route;

// Do not register GET / here: tenant-web.php loads web.php first (storefront `home` on /).
// A second GET / overwrites that route and removes the `home` name, breaking admin layout
// partials that call route('home'). Root admin entry is GET /admin (see group below).

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::get('/', function () {
        return redirect()->route('admin.auth.login');
    });

    /*authentication*/
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/code/captcha/{tmp}', 'LoginController@captcha')->name('default-captcha');
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit')->middleware('actch');
        Route::get('logout', 'LoginController@logout')->name('logout');
    });

    /*authenticated*/
    Route::group(['middleware' => ['admin']], function () {

        //dashboard routes
        Route::get('/', 'DashboardController@dashboard')->name('dashboard'); //previous dashboard route
        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            Route::get('/', 'DashboardController@dashboard')->name('index');
            Route::post('order-stats', 'DashboardController@order_stats')->name('order-stats');
            Route::post('business-overview', 'DashboardController@business_overview')->name('business-overview');
            Route::get('earning-statistics', 'DashboardController@get_earning_statitics')->name('earning-statistics');
        });
        //system routes
        Route::get('import-search-function-data', 'SystemController@importSearchFunctionData')->name('import-search-function-data');
        Route::get('search-function', 'SystemController@search_function')->name('search-function');
        Route::get('maintenance-mode', 'SystemController@maintenance_mode')->name('maintenance-mode');
        Route::get('/get-order-data', 'SystemController@order_data')->name('get-order-data');

        Route::group(['prefix' => 'custom-role', 'as' => 'custom-role.', 'middleware' => ['module:user_section']], function () {
            Route::get('create', 'CustomRoleController@create')->name('create');
            Route::post('create', 'CustomRoleController@store')->name('store');
            Route::get('update/{id}', 'CustomRoleController@edit')->name('update');
            Route::post('update/{id}', 'CustomRoleController@update');
            Route::post('employee-role-status', 'CustomRoleController@employee_role_status_update')->name('employee-role-status');
            Route::get('export', 'CustomRoleController@export')->name('export');
            Route::post('delete', 'CustomRoleController@delete')->name('delete');
        });

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('view', 'ProfileController@view')->name('view');
            Route::get('update/{id}', 'ProfileController@edit')->name('update');
            Route::post('update/{id}', 'ProfileController@update');
            Route::post('settings-password', 'ProfileController@settings_password_update')->name('settings-password');
        });

        Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.', 'middleware' => ['module:user_section']], function () {
            Route::post('update/{id}', 'WithdrawController@update')->name('update');
            Route::post('request', 'WithdrawController@w_request')->name('request');
            Route::post('status-filter', 'WithdrawController@status_filter')->name('status-filter');
        });

        Route::group(['prefix' => 'deal', 'as' => 'deal.', 'middleware' => ['module:promotion_management']], function () {
            Route::get('flash', 'DealController@flash_index')->name('flash');
            Route::post('flash', 'DealController@flash_submit');

            // feature deal
            Route::get('feature', 'DealController@feature_index')->name('feature');

            Route::get('day', 'DealController@deal_of_day')->name('day');
            Route::post('day', 'DealController@deal_of_day_submit');
            Route::post('day-status-update', 'DealController@day_status_update')->name('day-status-update');

            Route::get('day-update/{id}', 'DealController@day_edit')->name('day-update');
            Route::post('day-update/{id}', 'DealController@day_update');
            Route::post('day-delete', 'DealController@day_delete')->name('day-delete');

            Route::get('update/{id}', 'DealController@edit')->name('update');
            Route::get('edit/{id}', 'DealController@feature_edit')->name('edit');

            Route::post('update/{id}', 'DealController@update')->name('update');
            Route::post('status-update', 'DealController@status_update')->name('status-update');
            Route::post('feature-status', 'DealController@feature_status')->name('feature-status');

            Route::post('featured-update', 'DealController@featured_update')->name('featured-update');
            Route::get('add-product/{deal_id}', 'DealController@add_product')->name('add-product');
            Route::post('add-product/{deal_id}', 'DealController@add_product_submit');
            Route::post('delete-product', 'DealController@delete_product')->name('delete-product');
        });

        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.', 'middleware' => ['module:promotion_management']], function () {
            Route::get('announcement', 'BusinessSettingsController@announcement')->name('announcement');
            Route::post('update-announcement', 'BusinessSettingsController@updateAnnouncement')->name('update-announcement');
        });

        Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
            Route::get('add-new', 'EmployeeController@add_new')->name('add-new');
            Route::post('add-new', 'EmployeeController@store');
            Route::get('list', 'EmployeeController@list')->name('list');
            Route::get('update/{id}', 'EmployeeController@edit')->name('update');
            Route::post('update/{id}', 'EmployeeController@update');
            Route::post('status', 'EmployeeController@status')->name('status');

            Route::post('get-areas-by-schemes', 'EmployeeController@getAreasBySchemes')->name('getAreasBySchemes');
            Route::post('get-scopes-by-areas', 'EmployeeController@getScopesByAreas')->name('getScopesByAreas');
        });

        Route::group(['prefix' => 'assessor', 'as' => 'assessor.'], function () {

            Route::get('add-assessor', 'EmployeeController@addAssessor')->name('add-assessor');
            Route::post('add-assessor', 'EmployeeController@storeAssessor');
            Route::get('assessor-list', 'EmployeeController@assessorList')->name('assessor-list');
            Route::get('assessor_update/{id}', 'EmployeeController@assessorEdit')->name('update-assessor');
            Route::post('assessor_update/{id}', 'EmployeeController@assessorUpdate');
            Route::get('assessor-view/{id}', 'EmployeeController@assessorProfile')->name('assessor-view');
            Route::post('save-competence-evaluation/{id}', 'EmployeeController@CompetenceEvaluationUpdate')->name('save-competence-evaluation');
            Route::post('update-status/{id}', 'EmployeeController@updateAssessorStatus')->name('update-status');

            Route::get('witness-list', 'EmployeeController@witnessList')->name('witness-list');
            Route::get('view-witness/{id}', 'EmployeeController@viewWitness')->name('view-witness');
            Route::post('update-witness-status/{id}', 'EmployeeController@updateWitnessStatus')->name('update-witness-status');
            Route::post('update-qualification-status/{id}', 'EmployeeController@updateQualificationStatus')->name('update-qualification-status');


        });

        Route::group(['prefix' => 'category', 'as' => 'category.', 'middleware' => ['module:product_management']], function () {
            Route::get('view', 'CategoryController@index')->name('view');
            Route::get('paginate', 'CategoryController@paginate')->name('paginate');

            Route::get('fetch', 'CategoryController@fetch')->name('fetch');
            Route::post('store', 'CategoryController@store')->name('store');
            Route::get('edit/{id}', 'CategoryController@edit')->name('edit');
            Route::post('update/{id}', 'CategoryController@update')->name('update');
            Route::post('delete', 'CategoryController@delete')->name('delete');
            Route::post('status', 'CategoryController@status')->name('status');
        });

        Route::group(['prefix' => 'scheme', 'as' => 'scheme.'], function () {
            Route::get('get-areas/{id}', 'SchemeController@getAreas')->name('getAreas');

            Route::get('view', 'SchemeController@index')->name('view');
            Route::post('store', 'SchemeController@store')->name('store');
            Route::post('update', 'SchemeController@update')->name('update');
            Route::post('delete', 'SchemeController@delete')->name('delete');

            Route::get('area/view', 'SchemeController@areaIndex')->name('area.view');
            Route::post('area/store', 'SchemeController@areaStore')->name('area.store');
            Route::post('area/update', 'SchemeController@areaUpdate')->name('area.update');
            Route::post('area/delete', 'SchemeController@areaDelete')->name('area.delete');

            Route::get('scope/view', 'SchemeController@scopeIndex')->name('scope.view');
            Route::post('scope/store', 'SchemeController@scopeStore')->name('scope.store');
            Route::post('scope/update', 'SchemeController@scopeUpdate')->name('scope.update');
            Route::post('scope/delete', 'SchemeController@scopeDelete')->name('scope.delete');

        });

        Route::group(['prefix' => 'training', 'as' => 'training.'], function () {
            Route::get('add', 'TrainingController@add')->name('add-training');
            Route::post('store', 'TrainingController@store')->name('store-training');
            Route::get('list', 'TrainingController@list')->name('list-training');
            Route::post('get-areas', 'TrainingController@getAreas')->name('getAreas');
            Route::post('get-scopes', 'TrainingController@getScopes')->name('getScopes');
            Route::post('get-area-scope', 'TrainingController@getAreaAndScope')->name('getAreaScope');
            Route::post('training/status-update', 'TrainingController@updateStatus')->name('status-update');
            Route::post('training/delete', 'TrainingController@delete')->name('delete');
            Route::get('edit/{id}', 'TrainingController@edit')->name('training-edit');
            Route::post('update/{id}', 'TrainingController@update')->name('training-update');
            
            Route::get('view/{id}/{tab?}', 'TrainingController@view')->name('training-view');
            Route::get('attempts/{id}', 'TrainingController@attempts')->name('attempts');
            Route::post('track-progress', 'TrainingController@trackProgress')->name('track-progress');


            // Questions
            Route::get('questions/{id}', 'TrainingController@questions')->name('questions-list');
            Route::post('questions/store', 'TrainingController@questionsStore')->name('questions-store');
            Route::get('questions/edit/{id}', 'TrainingController@questionsEdit')->name('questions-edit');
            Route::post('questions/update/{id}', 'TrainingController@questionsUpdate')->name('questions-update');
            Route::post('questions/delete', 'TrainingController@questionsDelete')->name('questions-delete');


            
           // Fee Structures using the same TrainingController
            Route::get('fee/list', 'TrainingController@feeList')->name('fee-list');
            Route::post('fee/update/{id}', 'TrainingController@feeUpdate')->name('fee-update');



        });

        Route::group(['prefix' => 'sub-category', 'as' => 'sub-category.', 'middleware' => ['module:product_management']], function () {
            Route::get('view', 'SubCategoryController@index')->name('view');
            Route::get('paginate', 'SubCategoryController@paginate')->name('paginate');
            Route::get('fetch', 'SubCategoryController@fetch')->name('fetch');
            Route::post('store', 'SubCategoryController@store')->name('store');
            Route::post('edit', 'SubCategoryController@edit')->name('edit');
            Route::post('update', 'SubCategoryController@update')->name('update');
            Route::post('delete', 'SubCategoryController@delete')->name('delete');
        });

        Route::group(['prefix' => 'sub-sub-category', 'as' => 'sub-sub-category.', 'middleware' => ['module:product_management']], function () {
            Route::get('view', 'SubSubCategoryController@index')->name('view');
            Route::get('fetch', 'SubSubCategoryController@fetch')->name('fetch');
            Route::post('store', 'SubSubCategoryController@store')->name('store');
            Route::post('edit', 'SubSubCategoryController@edit')->name('edit');
            Route::post('update', 'SubSubCategoryController@update')->name('update');
            Route::post('delete', 'SubSubCategoryController@delete')->name('delete');
            Route::post('get-sub-category', 'SubSubCategoryController@getSubCategory')->name('getSubCategory');
            Route::post('get-category-id', 'SubSubCategoryController@getCategoryId')->name('getCategoryId');
        });

        Route::group(['prefix' => 'brand', 'as' => 'brand.', 'middleware' => ['module:product_management']], function () {
            Route::get('add-new', 'BrandController@add_new')->name('add-new');
            Route::post('add-new', 'BrandController@store');
            Route::get('list', 'BrandController@list')->name('list');
            Route::get('update/{id}', 'BrandController@edit')->name('update');
            Route::post('update/{id}', 'BrandController@update');
            Route::post('delete', 'BrandController@delete')->name('delete');
            Route::get('export', 'BrandController@export')->name('export');
            Route::post('status-update', 'BrandController@status_update')->name('status-update');
        });

        Route::group(['prefix' => 'plan', 'as' => 'plan.', 'middleware' => ['module:product_management']], function () {
            Route::get('add-new', 'SubscriptionController@add_new')->name('add-new');
            Route::post('add-new', 'SubscriptionController@store');
            Route::get('list', 'SubscriptionController@list')->name('list');
            Route::get('update/{id}', 'SubscriptionController@edit')->name('update');
            Route::post('update/{id}', 'SubscriptionController@update');
            Route::post('delete', 'SubscriptionController@delete')->name('delete');
            Route::get('export', 'SubscriptionController@export')->name('export');
            Route::post('status-update', 'SubscriptionController@status_update')->name('status-update');
        });

        Route::group(['prefix' => 'banner', 'as' => 'banner.', 'middleware' => ['module:promotion_management']], function () {
            Route::post('add-new', 'BannerController@store')->name('store');
            Route::get('list', 'BannerController@list')->name('list');
            Route::post('delete', 'BannerController@delete')->name('delete');
            Route::post('status', 'BannerController@status')->name('status');
            Route::get('edit/{id}', 'BannerController@edit')->name('edit');
            Route::put('update/{id}', 'BannerController@update')->name('update');
        });

        Route::group(['prefix' => 'attribute', 'as' => 'attribute.', 'middleware' => ['module:product_management']], function () {
            Route::get('view', 'AttributeController@index')->name('view');
            Route::get('fetch', 'AttributeController@fetch')->name('fetch');
            Route::post('store', 'AttributeController@store')->name('store');
            Route::get('edit/{id}', 'AttributeController@edit')->name('edit');
            Route::post('update/{id}', 'AttributeController@update')->name('update');
            Route::post('delete', 'AttributeController@delete')->name('delete');
        });

        Route::group(['prefix' => 'coupon', 'as' => 'coupon.', 'middleware' => ['module:promotion_management']], function () {
            Route::get('add-new', 'CouponController@add_new')->name('add-new')->middleware('actch');
            Route::post('store-coupon', 'CouponController@store')->name('store-coupon');
            Route::get('update/{id}', 'CouponController@edit')->name('update')->middleware('actch');
            Route::post('update/{id}', 'CouponController@update');
            Route::get('quick-view-details', 'CouponController@quick_view_details')->name('quick-view-details');
            Route::get('status/{id}/{status}', 'CouponController@status')->name('status');
            Route::delete('delete/{id}', 'CouponController@delete')->name('delete');
            Route::post('ajax-get-seller', 'CouponController@ajax_get_seller')->name('ajax-get-seller');
        });

        Route::group(['prefix' => 'area', 'as' => 'area.'], function () {
            Route::get('view', 'AreaController@index')->name('view');
            Route::get('city', 'AreaController@city')->name('city');
            Route::get('fetch', 'AreaController@fetch')->name('fetch');
            Route::get('getCity', 'AreaController@getCity')->name('getCity');
            Route::post('getCityState', 'AreaController@getCityState')->name('getCityState');
            Route::post('getAreaCity', 'AreaController@getAreaCity')->name('getAreaCity');
            Route::get('search-area', 'AreaController@searchArea')->name('search');
            Route::post('store', 'AreaController@store')->name('store');
            Route::post('citystore', 'AreaController@citystore')->name('citystore');
            // Route::get('edit/{id}', 'AreaController@edit')->name('edit');
            Route::post('update', 'AreaController@update')->name('update');
            Route::post('cityupdate', 'AreaController@cityupdate')->name('cityupdate');
            Route::post('delete', 'AreaController@delete')->name('delete');
            Route::post('citydelete', 'AreaController@citydelete')->name('citydelete');
            // Route::post('status', 'AreaController@status')->name('status');

            Route::get('bulk-import', 'AreaController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'AreaController@bulk_store_areas');
        });

        Route::group(['prefix' => 'taxes', 'as' => 'taxes.', 'middleware' => ['module:tax_management']], function () {
            Route::get('add-new', 'TaxController@add_new')->name('add-new')->middleware('actch');
            Route::post('store-coupon', 'TaxController@store')->name('store');
            Route::get('update/{id}', 'TaxController@edit')->name('update')->middleware('actch');
            Route::post('update/{id}', 'TaxController@update');
            Route::get('quick-view-details', 'TaxController@quick_view_details')->name('quick-view-details');
            Route::get('status/{id}/{status}', 'TaxController@status')->name('status');
            Route::delete('delete/{id}', 'TaxController@delete')->name('delete');
            Route::post('ajax-get-seller', 'TaxController@ajax_get_seller')->name('ajax-get-seller');
        });

        Route::group(['prefix' => 'shiprocket', 'as' => 'shiprocket.'], function () {
            Route::post('login', 'ShipRocketController@login')->name('login');
            Route::get('dashboard', 'ShipRocketController@index')->name('index');
        });

        Route::group(['prefix' => 'social-login', 'as' => 'social-login.', 'middleware' => ['module:system_settings']], function () {
            Route::get('view', 'BusinessSettingsController@viewSocialLogin')->name('view');
            Route::post('update/{service}', 'BusinessSettingsController@updateSocialLogin')->name('update');

            Route::get('app-home', 'BusinessSettingsController@viewAppHome')->name('app-home');
            Route::post('update-app-home', 'BusinessSettingsController@updateAppHome')->name('update-app-home');
        });

        Route::group(['prefix' => 'social-media-chat', 'as' => 'social-media-chat.', 'middleware' => ['module:system_settings']], function () {
            Route::get('view', 'BusinessSettingsController@view_social_media_chat')->name('view');
            Route::post('update/{service}', 'BusinessSettingsController@update_social_media_chat')->name('update');
        });

        Route::group(['prefix' => 'product-settings', 'as' => 'product-settings.', 'middleware' => ['module:system_settings']], function () {
            Route::get('/', 'BusinessSettingsController@productSettings')->name('index');
            Route::get('inhouse-shop', 'InhouseShopController@edit')->name('inhouse-shop');
            Route::post('inhouse-shop', 'InhouseShopController@update');
            Route::post('inhouse-shop-temporary-close', 'InhouseShopController@temporary_close')->name('inhouse-shop-temporary-close');
            Route::post('vacation-add', 'InhouseShopController@vacation_add')->name('vacation-add');
            Route::post('stock-limit-warning', 'BusinessSettingsController@stock_limit_warning')->name('stock-limit-warning');
            Route::post('update-digital-product', 'BusinessSettingsController@updateDigitalProduct')->name('update-digital-product');
            Route::post('update-product-brand', 'BusinessSettingsController@updateProductBrand')->name('update-product-brand');
        });

        Route::group(['prefix' => 'currency', 'as' => 'currency.', 'middleware' => ['module:system_settings']], function () {
            Route::get('view', 'CurrencyController@index')->name('view')->middleware('actch');
            Route::get('fetch', 'CurrencyController@fetch')->name('fetch');
            Route::post('store', 'CurrencyController@store')->name('store');
            Route::get('edit/{id}', 'CurrencyController@edit')->name('edit');
            Route::post('update/{id}', 'CurrencyController@update')->name('update');
            Route::post('delete', 'CurrencyController@delete')->name('delete');
            Route::post('status', 'CurrencyController@status')->name('status');
            Route::post('system-currency-update', 'CurrencyController@systemCurrencyUpdate')->name('system-currency-update');
        });

        Route::group(['prefix' => 'support-ticket', 'as' => 'support-ticket.', 'middleware' => ['module:support_section']], function () {
            Route::get('view', 'SupportTicketController@index')->name('view');
            Route::post('status', 'SupportTicketController@status')->name('status');
            Route::get('single-ticket/{id}', 'SupportTicketController@single_ticket')->name('singleTicket');
            Route::post('single-ticket/{id}', 'SupportTicketController@replay_submit')->name('replay');
        });
        Route::group(['prefix' => 'notification', 'as' => 'notification.', 'middleware' => ['module:promotion_management']], function () {
            Route::get('add-new', 'NotificationController@index')->name('add-new');
            Route::post('store', 'NotificationController@store')->name('store');
            Route::get('edit/{id}', 'NotificationController@edit')->name('edit');
            Route::post('update/{id}', 'NotificationController@update')->name('update');
            Route::post('status', 'NotificationController@status')->name('status');
            Route::post('resend-notification', 'NotificationController@resendNotification')->name('resend-notification');
            Route::post('delete', 'NotificationController@delete')->name('delete');
        });
        Route::group(['prefix' => 'reviews', 'as' => 'reviews.', 'middleware' => ['module:user_section']], function () {
            Route::get('list', 'ReviewsController@list')->name('list')->middleware('actch');
            Route::get('create', 'ReviewsController@Create')->name('create');
            Route::post('/store', 'ReviewsController@store')->name('store');
            Route::get('edit/{id}', 'ReviewsController@edit')->name('edit');
            Route::post('update/{id}', 'ReviewsController@update')->name('update');


            Route::get('export', 'ReviewsController@export')->name('export')->middleware('actch');
            Route::get('status/{id}/{status}', 'ReviewsController@status')->name('status');
        });

        Route::group(['prefix' => 'company', 'as' => 'company.', 'middleware' => ['module:user_section']], function () {
            Route::get('/', 'CompanyController@index')->name('index');
            Route::get('/show/{companyProfile}', 'CompanyController@show')->name('show');
            Route::post('/update-status', 'CompanyController@status_update')->name('status_update');

        });
        Route::group(['prefix' => 'customer', 'as' => 'customer.', 'middleware' => ['module:user_section']], function () {
            Route::get('list', 'CustomerController@customer_list')->name('list');
            Route::get('add-new', 'CustomerController@add_new')->name('add_new');
            Route::post('store', 'CustomerController@store')->name('store');
            Route::get('edit-customer/{id}', 'CustomerController@edit_customer')->name('edit-customer');
            Route::post('update-customer', 'CustomerController@update_customer')->name('update-customer');
            Route::post('status-update', 'CustomerController@status_update')->name('status-update');
            Route::get('view/{user_id}', 'CustomerController@view')->name('view');
            Route::delete('delete/{id}', 'CustomerController@delete')->name('delete');
            Route::get('subscriber-list', 'CustomerController@subscriber_list')->name('subscriber-list');
            Route::get('customer-list-search', 'CustomerController@get_customers')->name('customer-list-search');

            Route::get('bulk-import', 'CustomerController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'CustomerController@bulk_store_customers');

            Route::get('export', 'CustomerController@export')->name('export');

            Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
                Route::post('add-fund', 'CustomerWalletController@add_fund')->name('add-fund');
                Route::get('report', 'CustomerWalletController@report')->name('report');
            });
            Route::group(['prefix' => 'loyalty', 'as' => 'loyalty.'], function () {
                Route::get('report', 'CustomerLoyaltyController@report')->name('report');
            });
        });

        Route::group(['prefix' => 'refund-section', 'as' => 'refund-section.', 'middleware' => ['module:order_management']], function () {
            Route::get('refund-index', 'RefundController@index')->name('refund-index');
            Route::post('refund-update', 'RefundController@update')->name('refund-update');

            Route::group(['prefix' => 'refund', 'as' => 'refund.'], function () {
                Route::get('list/{status}', 'RefundController@list')->name('list');
                Route::get('details/{id}', 'RefundController@details')->name('details');
                Route::get('inhouse-order-filter', 'RefundController@inhouse_order_filter')->name('inhouse-order-filter');
                Route::post('refund-status-update', 'RefundController@refund_status_update')->name('refund-status-update');

                Route::get('list-paginate/{status?}', 'RefundController@paginate')->name('list-paginate');
            });
        });

        Route::group(['middleware' => ['module:system_settings']], function () {
            Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
                Route::get('customer-settings', 'CustomerController@customer_settings')->name('customer-settings');
                Route::post('customer-settings-update', 'CustomerController@customer_update_settings')->name('customer-settings-update');
            });
        });

        ///Report
        Route::group(['prefix' => 'report', 'as' => 'report.', 'middleware' => ['module:report']], function () {
            Route::get('earning', 'ReportController@earning_index')->name('earning');
            Route::get('admin-earning', 'ReportController@admin_earning')->name('admin-earning');
            Route::get('admin-earning-excel-export', 'ReportController@admin_earning_excel_export')->name('admin-earning-excel-export');
            Route::post('admin-earning-duration-download-pdf', 'ReportController@admin_earning_duration_download_pdf')->name('admin-earning-duration-download-pdf');
            Route::get('seller-earning', 'ReportController@seller_earning')->name('seller-earning');
            Route::get('seller-earning-excel-export', 'ReportController@seller_earning_excel_export')->name('seller-earning-excel-export');
            Route::any('set-date', 'ReportController@set_date')->name('set-date');
            //sale report inhouse
            Route::get('inhoue-product-sale', 'InhouseProductSaleController@index')->name('inhoue-product-sale');
            Route::get('seller-report', 'SellerProductSaleReportController@seller_report')->name('seller-report');
            Route::get('seller-report-excel', 'SellerProductSaleReportController@seller_report_excel')->name('seller-report-excel');

            Route::get('all-product', 'ProductReportController@all_product')->name('all-product');
            Route::get('all-product-excel', 'ProductReportController@all_product_export_excel')->name('all-product-excel');

            Route::get('order', 'OrderReportController@order_list')->name('order');
            Route::get('order/paginate', 'OrderReportController@order_list_paginate')->name('order.paginate');
            Route::get('order-report-excel', 'OrderReportController@order_report_export_excel')->name('order-report-excel');
        });
        Route::group(['prefix' => 'stock', 'as' => 'stock.', 'middleware' => ['module:report']], function () {
            //product stock report
            Route::get('product-stock', 'ProductStockReportController@index')->name('product-stock');
            Route::get('product-stock-export', 'ProductStockReportController@export')->name('product-stock-export');
            Route::post('ps-filter', 'ProductStockReportController@filter')->name('ps-filter');
            //product in wishlist report
            Route::get('product-in-wishlist', 'ProductWishlistReportController@index')->name('product-in-wishlist');
            Route::get('wishlist-product-export', 'ProductWishlistReportController@export')->name('wishlist-product-export');
        });

        Route::group(['prefix' => 'sellers', 'as' => 'sellers.', 'middleware' => ['module:user_section']], function () {

            Route::get('seller-login/{seller_id?}', 'SellerController@sellerLogin')->name('seller-login');
            Route::get('requested-categories/{seller_id?}', 'SellerController@requestedCategories')->name('seller-request-categories');

            Route::get('seller-add', 'SellerController@add_seller')->name('seller-add');
            Route::get('seller-list', 'SellerController@index')->name('seller-list');
            Route::get('order-list/{seller_id}', 'SellerController@order_list')->name('order-list');
            Route::get('product-list/{seller_id}', 'SellerController@product_list')->name('product-list');

            Route::get('order-details/{order_id}/{seller_id}', 'SellerController@order_details')->name('order-details');
            Route::get('verification/{id}', 'SellerController@view')->name('verification');
            Route::get('view/{id}/{tab?}', 'SellerController@view')->name('view');
            Route::post('update-category-request', 'SellerController@updateCategoryRequest')->name('category-request.update');

            Route::post('update-status', 'SellerController@updateStatus')->name('updateStatus');
            Route::post('withdraw-status/{id}', 'SellerController@withdrawStatus')->name('withdraw_status');
            Route::get('withdraw_list', 'SellerController@withdraw')->name('withdraw_list');
            Route::get('withdraw-list-export-excel', 'SellerController@withdraw_list_export_excel')->name('withdraw-list-export-excel');
            Route::get('withdraw-view/{withdraw_id}/{seller_id}', 'SellerController@withdraw_view')->name('withdraw_view');

            Route::post('sales-commission-update/{id}', 'SellerController@sales_commission_update')->name('sales-commission-update');

            Route::group(['prefix' => 'withdraw-method', 'as' => 'withdraw-method.'], function () {
                Route::get('list', 'WithdrawalMethodController@list')->name('list');
                Route::get('create', 'WithdrawalMethodController@create')->name('create');
                Route::post('store', 'WithdrawalMethodController@store')->name('store');
                Route::get('edit/{id}', 'WithdrawalMethodController@edit')->name('edit');
                Route::put('update', 'WithdrawalMethodController@update')->name('update');
                Route::delete('delete/{id}', 'WithdrawalMethodController@delete')->name('delete');
                Route::post('status-update', 'WithdrawalMethodController@status_update')->name('status-update');
                Route::post('default-status-update', 'WithdrawalMethodController@default_status_update')->name('default-status-update');
            });
        });
        Route::group(['prefix' => 'product', 'as' => 'product.', 'middleware' => ['module:product_management']], function () {
            Route::get('add-new', 'ProductController@add_new')->name('add-new');
            Route::post('store', 'ProductController@store')->name('store');
            Route::get('remove-image', 'ProductController@remove_image')->name('remove-image');
            Route::post('status-update', 'ProductController@status_update')->name('status-update');
            Route::get('list/{type}', 'ProductController@list')->name('list');
            Route::get('list-paginate', 'ProductController@list_paginate')->name('list.paginate');
            Route::get('product-for-you', 'ProductController@product_for_you')->name('product-for-you');
            Route::post('product-for-you-submit', 'ProductController@product_for_you_submit')->name('product-for-you-submit');
            Route::post('product-for-you-delete', 'ProductController@product_for_you_delete')->name('product-for-you-delete');

            Route::get('export-excel/{type}', 'ProductController@export_excel')->name('export-excel');
            Route::get('stock-limit-list/{type}', 'ProductController@stock_limit_list')->name('stock-limit-list');
            Route::get('get-variations', 'ProductController@get_variations')->name('get-variations');
            Route::post('update-quantity', 'ProductController@update_quantity')->name('update-quantity');
            Route::post('update-price/{id}', 'ProductController@update_price')->name('update-price');
            Route::get('edit/{id}', 'ProductController@edit')->name('edit');
            Route::post('update/{id}', 'ProductController@update')->name('update');
            Route::post('featured-status', 'ProductController@featured_status')->name('featured-status');
            Route::get('approve-status', 'ProductController@approve_status')->name('approve-status');
            Route::post('deny', 'ProductController@deny')->name('deny');
            Route::post('sku-combination', 'ProductController@sku_combination')->name('sku-combination');
            Route::get('get-categories', 'ProductController@get_categories')->name('get-categories');
            Route::delete('delete/{id}', 'ProductController@delete')->name('delete');
            Route::get('updated-product-list', 'ProductController@updated_product_list')->name('updated-product-list');
            Route::post('updated-shipping', 'ProductController@updated_shipping')->name('updated-shipping');

            Route::get('view/{id}', 'ProductController@view')->name('view');
            Route::get('bulk-import', 'ProductController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'ProductController@bulk_import_data');
            Route::get('bulk-export', 'ProductController@bulk_export_data')->name('bulk-export');
            Route::get('barcode/{id}', 'ProductController@barcode')->name('barcode');
            Route::get('barcode/generate', 'ProductController@barcode_generate')->name('barcode.generate');
        });

        Route::group(['prefix' => 'transaction', 'as' => 'transaction.', 'middleware' => ['module:report']], function () {
            Route::get('order-transaction-list', 'TransactionReportController@order_transaction_list')->name('order-transaction-list');
            Route::get('pdf-order-wise-transaction', 'TransactionReportController@pdf_order_wise_transaction')->name('pdf-order-wise-transaction');
            Route::get('order-transaction-export-excel', 'TransactionReportController@order_transaction_export_excel')->name('order-transaction-export-excel');
            Route::get('order-transaction-summary-pdf', 'TransactionReportController@order_transaction_summary_pdf')->name('order-transaction-summary-pdf');

            Route::get('expense-transaction-list', 'TransactionReportController@expense_transaction_list')->name('expense-transaction-list');
            Route::get('pdf-order-wise-expense-transaction', 'TransactionReportController@pdf_order_wise_expense_transaction')->name('pdf-order-wise-expense-transaction');
            Route::get('expense-transaction-export-excel', 'TransactionReportController@expense_transaction_export_excel')->name('expense-transaction-export-excel');
            Route::get('expense-transaction-summary-pdf', 'TransactionReportController@expense_transaction_summary_pdf')->name('expense-transaction-summary-pdf');

            Route::get('refund-transaction-list', 'RefundTransactionController@refund_transaction_list')->name('refund-transaction-list');
            Route::get('refund-transaction-export-excel', 'RefundTransactionController@refund_transaction_export_excel')->name('refund-transaction-export-excel');
            Route::get('refund-transaction-summary-pdf', 'RefundTransactionController@refund_transaction_summary_pdf')->name('refund-transaction-summary-pdf');
        });


        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
            Route::group(['middleware' => ['module:system_settings']], function () {
                Route::get('sms-module', 'SMSModuleController@sms_index')->name('sms-module');
                Route::post('sms-module-update/{sms_module}', 'SMSModuleController@sms_update')->name('sms-module-update');
            });

            Route::group(['middleware' => ['module:system_settings']], function () {
                Route::get('cookie-settings', 'BusinessSettingsController@cookie_settings')->name('cookie-settings');
                Route::post('cookie-settings-update', 'BusinessSettingsController@cookie_setting_update')->name('cookie-settings-update');

                Route::get('all-pages-banner', 'BusinessSettingsController@all_pages_banner')->name('all-pages-banner');
                Route::post('all-pages-banner-store', 'BusinessSettingsController@all_pages_banner_store')->name('all-pages-banner-store');
                Route::get('all-pages-banner-edit/{id}', 'BusinessSettingsController@all_pages_banner_edit')->name('all-pages-banner-edit');
                Route::post('all-pages-banner-update', 'BusinessSettingsController@all_pages_banner_update')->name('all-pages-banner-update');
                Route::post('all-pages-banner-status', 'BusinessSettingsController@all_pages_banner_status')->name('all-pages-banner-status');
                Route::post('all-pages-banner-delete', 'BusinessSettingsController@all_pages_banner_delete')->name('all-pages-banner-delete');

                Route::get('otp-setup', 'BusinessSettingsController@otp_setup')->name('otp-setup');
                Route::post('otp-setup-update', 'BusinessSettingsController@otp_setup_update')->name('otp-setup-update');
            });

            Route::group(['prefix' => 'shipping-method', 'as' => 'shipping-method.', 'middleware' => ['module:system_settings']], function () {
                Route::get('by/admin', 'ShippingMethodController@index_admin')->name('by.admin');
                Route::post('add', 'ShippingMethodController@store')->name('add');
                Route::get('edit/{id}', 'ShippingMethodController@edit')->name('edit');
                Route::put('update/{id}', 'ShippingMethodController@update')->name('update');
                Route::post('delete', 'ShippingMethodController@delete')->name('delete');
                Route::post('status-update', 'ShippingMethodController@status_update')->name('status-update');
                Route::get('setting', 'ShippingMethodController@setting')->name('setting');
                Route::post('shipping-store', 'ShippingMethodController@shippingStore')->name('shipping-store');
            });

            Route::group(['prefix' => 'shipping-type', 'as' => 'shipping-type.', 'middleware' => ['module:system_settings']], function () {
                Route::post('store', 'ShippingTypeController@store')->name('store');
            });

            Route::group(['prefix' => 'category-shipping-cost', 'as' => 'category-shipping-cost.', 'middleware' => ['module:system_settings']], function () {
                Route::post('store', 'CategoryShippingCostController@store')->name('store');
            });

            Route::group(['prefix' => 'language', 'as' => 'language.', 'middleware' => ['module:system_settings']], function () {
                Route::get('', 'LanguageController@index')->name('index');
                Route::post('add-new', 'LanguageController@store')->name('add-new');
                Route::get('update-status', 'LanguageController@update_status')->name('update-status');
                Route::get('update-default-status', 'LanguageController@update_default_status')->name('update-default-status');
                Route::post('update', 'LanguageController@update')->name('update');
                Route::get('translate/{lang}', 'LanguageController@translate')->name('translate');
                Route::post('translate-submit/{lang}', 'LanguageController@translate_submit')->name('translate-submit');
                Route::post('remove-key/{lang}', 'LanguageController@translate_key_remove')->name('remove-key');
                Route::get('delete/{lang}', 'LanguageController@delete')->name('delete');
                Route::any('auto-translate/{lang}', 'LanguageController@auto_translate')->name('auto-translate');
            });

            Route::group(['prefix' => 'mail', 'as' => 'mail.', 'middleware' => ['module:system_settings']], function () {
                Route::get('/', 'MailController@index')->name('index');
                Route::post('update', 'MailController@update')->name('update');
                Route::post('update-sendgrid', 'MailController@update_sendgrid')->name('update-sendgrid');
                Route::post('send', 'MailController@send')->name('send');
            });

            Route::group(['prefix' => 'web-config', 'as' => 'web-config.', 'middleware' => ['module:system_settings']], function () {
                Route::get('/', 'BusinessSettingsController@companyInfo')->name('index')->middleware('actch');
                Route::post('update-colors', 'BusinessSettingsController@update_colors')->name('update-colors');
                Route::post('update-language', 'BusinessSettingsController@update_language')->name('update-language');
                Route::post('update-company', 'BusinessSettingsController@updateCompany')->name('company-update');
                Route::post('update-company-email', 'BusinessSettingsController@updateCompanyEmail')->name('company-email-update');
                Route::post('update-company-phone', 'BusinessSettingsController@updateCompanyPhone')->name('company-phone-update');
                Route::post('update-company-app-marque-text', 'BusinessSettingsController@updateCompanyappmarquetext')->name('company-app-marque-text');
                Route::post('upload-web-logo', 'BusinessSettingsController@uploadWebLogo')->name('company-web-logo-upload');
                Route::post('upload-mobile-logo', 'BusinessSettingsController@uploadMobileLogo')->name('company-mobile-logo-upload');
                Route::post('upload-footer-log', 'BusinessSettingsController@uploadFooterLog')->name('company-footer-logo-upload');
                Route::post('upload-fav-icon', 'BusinessSettingsController@uploadFavIcon')->name('company-fav-icon');
                Route::post('update-company-copyRight-text', 'BusinessSettingsController@updateCompanyCopyRight')->name('company-copy-right-update');
                Route::post('app-store/{name}', 'BusinessSettingsController@update')->name('app-store-update');
                Route::get('currency-symbol-position/{side}', 'BusinessSettingsController@currency_symbol_position')->name('currency-symbol-position');
                Route::post('shop-banner', 'BusinessSettingsController@shop_banner')->name('shop-banner');
                Route::get('app-settings', 'BusinessSettingsController@app_settings')->name('app-settings');

                Route::get('db-index', 'DatabaseSettingController@db_index')->name('db-index');
                Route::post('db-clean', 'DatabaseSettingController@clean_db')->name('clean-db');

                Route::get('environment-setup', 'EnvironmentSettingsController@environment_index')->name('environment-setup');
                Route::post('update-environment', 'EnvironmentSettingsController@environment_setup')->name('update-environment');

                //sitemap generate
                Route::get('mysitemap', 'SiteMapController@index')->name('mysitemap');
                Route::get('mysitemap-download', 'SiteMapController@download')->name('mysitemap-download');

                Route::group(['prefix' => 'theme', 'as' => 'theme.'], function () {
                    Route::get('setup', 'ThemeController@theme_index')->name('setup');
                    Route::post('setup', 'ThemeController@theme_setup');
                    Route::post('install', 'ThemeController@theme_install')->name('install');
                    Route::post('publish', 'ThemeController@publish')->name('publish');
                    Route::post('activation', 'ThemeController@activation')->name('activation');
                    Route::post('delete', 'ThemeController@delete_theme')->name('delete');
                });
            });

            Route::group(['prefix' => 'order-settings', 'as' => 'order-settings.', 'middleware' => ['module:system_settings']], function () {
                Route::get('index', 'OrderSettingsController@order_settings')->name('index');
                Route::post('update-order-settings', 'OrderSettingsController@update_order_settings')->name('update-order-settings');
            });

            Route::group(['prefix' => 'seller-settings', 'as' => 'seller-settings.', 'middleware' => ['module:system_settings']], function () {
                Route::get('/', 'BusinessSettingsController@seller_settings')->name('index')->middleware('actch');
                Route::post('update-seller-settings', 'BusinessSettingsController@sales_commission')->name('update-seller-settings');
                Route::post('update-seller-registration', 'BusinessSettingsController@seller_registration')->name('update-seller-registration');
                Route::post('seller-pos-settings', 'BusinessSettingsController@seller_pos_settings')->name('seller-pos-settings');
                Route::get('business-mode-settings/{mode}', 'BusinessSettingsController@business_mode_settings')->name('business-mode-settings');
                Route::post('product-approval', 'BusinessSettingsController@product_approval')->name('product-approval');
            });

            Route::group(['prefix' => 'payment-method', 'as' => 'payment-method.', 'middleware' => ['module:system_settings']], function () {
                Route::get('/', 'PaymentMethodController@index')->name('index')->middleware('actch');
                Route::post('{name}', 'PaymentMethodController@update')->name('update');
            });

            Route::group(['middleware' => ['module:system_settings']], function () {
                Route::get('general-settings', 'BusinessSettingsController@index')->name('general-settings')->middleware('actch');
                Route::get('update-language', 'BusinessSettingsController@update_language')->name('update-language');
                Route::get('about-us', 'BusinessSettingsController@about_us')->name('about-us');
                Route::post('about-us', 'BusinessSettingsController@about_usUpdate')->name('about-update');
                Route::post('update-info', 'BusinessSettingsController@updateInfo')->name('update-info');
                //Social Icon
                Route::get('social-media', 'BusinessSettingsController@social_media')->name('social-media');
                Route::get('fetch', 'BusinessSettingsController@fetch')->name('fetch');
                Route::post('social-media-store', 'BusinessSettingsController@social_media_store')->name('social-media-store');
                Route::post('social-media-edit', 'BusinessSettingsController@social_media_edit')->name('social-media-edit');
                Route::post('social-media-update', 'BusinessSettingsController@social_media_update')->name('social-media-update');
                Route::post('social-media-delete', 'BusinessSettingsController@social_media_delete')->name('social-media-delete');
                Route::post('social-media-status-update', 'BusinessSettingsController@social_media_status_update')->name('social-media-status-update');

                Route::get('page/{page}', 'BusinessSettingsController@page')->name('page');
                Route::post('page/{page}', 'BusinessSettingsController@page_update')->name('page-update');

                Route::get('vendor-page/{page}', 'BusinessSettingsController@vendor_page')->name('vendor.page');
                Route::post('vendor-page/{page}', 'BusinessSettingsController@vendor_page_update')->name('vendor.page-update');

                Route::get('driver-page/{page}', 'BusinessSettingsController@driver_page')->name('driver.page');
                Route::post('driver-page/{page}', 'BusinessSettingsController@driver_page_update')->name('driver.page-update');



                Route::get('terms-condition', 'BusinessSettingsController@terms_condition')->name('terms-condition');
                Route::post('terms-condition', 'BusinessSettingsController@updateTermsCondition')->name('update-terms');

                Route::get('contact-us', 'BusinessSettingsController@contact_us')->name('contact-us');
                Route::post('contact-us', 'BusinessSettingsController@updateContactus')->name('update-contact');

                Route::get('privacy-policy', 'BusinessSettingsController@privacy_policy')->name('privacy-policy');
                Route::post('privacy-policy', 'BusinessSettingsController@privacy_policy_update')->name('privacy-policy');

                Route::get('fcm-index', 'BusinessSettingsController@fcm_index')->name('fcm-index');
                Route::post('update-fcm', 'BusinessSettingsController@update_fcm')->name('update-fcm');

                //captcha
                Route::get('captcha', 'BusinessSettingsController@recaptcha_index')->name('captcha');
                Route::post('recaptcha-update', 'BusinessSettingsController@recaptcha_update')->name('recaptcha_update');
                //google map api
                Route::get('map-api', 'BusinessSettingsController@map_api')->name('map-api');
                Route::post('map-api-update', 'BusinessSettingsController@map_api_update')->name('map-api-update');

                Route::post('update-fcm-messages', 'BusinessSettingsController@update_fcm_messages')->name('update-fcm-messages');


                //analytics
                Route::get('analytics-index', 'BusinessSettingsController@analytics_index')->name('analytics-index');
                Route::post('analytics-update', 'BusinessSettingsController@analytics_update')->name('analytics-update');
                Route::post('analytics-update-google-tag', 'BusinessSettingsController@google_tag_analytics_update')->name('analytics-update-google-tag');

                // Features Section
                Route::get('features-section', 'BusinessSettingsController@features_section')->name('features-section');
                Route::post('features-section/submit', 'BusinessSettingsController@features_section_submit')->name('features-section.submit');
                Route::post('features-section/icon-remove', 'BusinessSettingsController@features_section_icon_remove')->name('features-section.icon-remove');
            });

            Route::group(['prefix' => 'delivery-restriction', 'as' => 'delivery-restriction.', 'middleware' => ['module:system_settings']], function () {
                Route::get('/', 'DeliveryRestrictionController@index')->name('index');
                Route::post('add-delivery-country', 'DeliveryRestrictionController@addDeliveryCountry')->name('add-delivery-country');
                Route::delete('delivery-country-delete', 'DeliveryRestrictionController@deliveryCountryDelete')->name('delivery-country-delete');
                Route::post('country-restriction-status-change', 'BusinessSettingsController@countryRestrictionStatusChange')->name('country-restriction-status-change');

                Route::post('add-zip-code', 'DeliveryRestrictionController@addZipCode')->name('add-zip-code');
                Route::delete('zip-code-delete', 'DeliveryRestrictionController@zipCodeDelete')->name('zip-code-delete');
                Route::post('zipcode-restriction-status-change', 'BusinessSettingsController@zipcodeRestrictionStatusChange')->name('zipcode-restriction-status-change');
            });
        });

        Route::group(['prefix' => 'system-settings', 'as' => 'system-settings.'], function () {
            Route::get('software-update', 'SoftwareUpdateController@index')->name('software-update');
            Route::post('software-update', 'SoftwareUpdateController@upload_and_update');

            /*Route::group(['prefix' => 'maintenance-mode', 'as' => 'maintenance-mode.'], function () {
                Route::get('activate','SoftwareUpdateController@activate_maintenance_mode')->name('activate');
            });*/
        });

        //order management
        Route::group(['prefix' => 'orders', 'as' => 'orders.', 'middleware' => ['module:order_management']], function () {
            Route::get('list/{status}', 'OrderController@list')->name('list');
            Route::get('paginate/{status?}', 'OrderController@paginate')->name('paginate');
            Route::get('details/{id}', 'OrderController@details')->name('details');
            Route::post('status', 'OrderController@status')->name('status');
            Route::post('amount-date-update', 'OrderController@amount_date_update')->name('amount-date-update');
            Route::post('payment-status', 'OrderController@payment_status')->name('payment-status');
            Route::post('productStatus', 'OrderController@productStatus')->name('productStatus');
            Route::get('generate-invoice/{id}', 'OrderController@generate_invoice')->name('generate-invoice')->withoutMiddleware(['module:order_management']);
            Route::get('inhouse-order-filter', 'OrderController@inhouse_order_filter')->name('inhouse-order-filter');
            Route::post('digital-file-upload-after-sell', 'OrderController@digital_file_upload_after_sell')->name('digital-file-upload-after-sell');

            Route::post('update-deliver-info', 'OrderController@update_deliver_info')->name('update-deliver-info');
            Route::get('add-delivery-man/{order_id}/{d_man_id}', 'OrderController@add_delivery_man')->name('add-delivery-man');

            Route::get('export-order-data/{status}', 'OrderController@bulk_export_data')->name('order-bulk-export');
        });

        //pos management
        Route::group(['prefix' => 'pos', 'as' => 'pos.', 'middleware' => ['module:pos_management']], function () {
            Route::get('/', 'POSController@index')->name('index');
            Route::get('quick-view', 'POSController@quick_view')->name('quick-view');
            Route::post('variant_price', 'POSController@variant_price')->name('variant_price');
            Route::post('add-to-cart', 'POSController@addToCart')->name('add-to-cart');
            Route::post('remove-from-cart', 'POSController@removeFromCart')->name('remove-from-cart');
            Route::post('cart-items', 'POSController@cart_items')->name('cart_items');
            Route::post('update-quantity', 'POSController@updateQuantity')->name('updateQuantity');
            Route::post('empty-cart', 'POSController@emptyCart')->name('emptyCart');
            Route::post('tax', 'POSController@update_tax')->name('tax');
            Route::post('discount', 'POSController@update_discount')->name('discount');
            Route::get('customers', 'POSController@get_customers')->name('customers');
            Route::post('order', 'POSController@place_order')->name('order');
            Route::get('orders', 'POSController@order_list')->name('orders');
            Route::get('order-details/{id}', 'POSController@order_details')->name('order-details');
            Route::post('digital-file-upload-after-sell', 'POSController@digital_file_upload_after_sell')->name('digital-file-upload-after-sell');
            Route::get('invoice/{id}', 'POSController@generate_invoice');
            Route::any('store-keys', 'POSController@store_keys')->name('store-keys');
            Route::get('search-products', 'POSController@search_product')->name('search-products');
            Route::get('order-bulk-export', 'POSController@bulk_export_data')->name('order-bulk-export');


            Route::post('coupon-discount', 'POSController@coupon_discount')->name('coupon-discount');
            Route::get('change-cart', 'POSController@change_cart')->name('change-cart');
            Route::get('new-cart-id', 'POSController@new_cart_id')->name('new-cart-id');
            Route::post('remove-discount', 'POSController@remove_discount')->name('remove-discount');
            Route::get('clear-cart-ids', 'POSController@clear_cart_ids')->name('clear-cart-ids');
            Route::get('get-cart-ids', 'POSController@get_cart_ids')->name('get-cart-ids');

            Route::post('customer-store', 'POSController@customer_store')->name('customer-store');
        });

        Route::group(['prefix' => 'helpTopic', 'as' => 'helpTopic.', 'middleware' => ['module:system_settings']], function () {
            Route::get('list', 'HelpTopicController@list')->name('list');
            Route::post('add-new', 'HelpTopicController@store')->name('add-new');
            Route::get('status/{id}', 'HelpTopicController@status');
            Route::get('edit/{id}', 'HelpTopicController@edit');
            Route::post('update/{id}', 'HelpTopicController@update');
            Route::post('delete', 'HelpTopicController@destroy')->name('delete');
        });
        Route::group(['prefix' => 'vendorhelpTopic', 'as' => 'vendorhelpTopic.', 'middleware' => ['module:system_settings']], function () {
            Route::get('list', 'VendorHelpTopicController@list')->name('list');
            Route::post('add-new', 'VendorHelpTopicController@store')->name('add-new');
            Route::get('status/{id}', 'VendorHelpTopicController@status');
            Route::get('edit/{id}', 'VendorHelpTopicController@edit');
            Route::post('update/{id}', 'VendorHelpTopicController@update');
            Route::post('delete', 'VendorHelpTopicController@destroy')->name('delete');
        });

        Route::group(['prefix' => 'contact', 'as' => 'contact.', 'middleware' => ['module:support_section']], function () {
            Route::post('contact-store', 'ContactController@store')->name('store');
            Route::get('list', 'ContactController@list')->name('list');
            Route::post('delete', 'ContactController@destroy')->name('delete');
            Route::get('view/{id}', 'ContactController@view')->name('view');
            Route::post('update/{id}', 'ContactController@update')->name('update');
            Route::post('send-mail/{id}', 'ContactController@send_mail')->name('send-mail');
        });

        Route::group(['prefix' => 'email-templates', 'as' => 'email-templates.', 'middleware' => ['module:support_section']], function () {
            Route::post('store', 'EmailTemplateController@store')->name('store');
            Route::get('list', 'EmailTemplateController@list')->name('list');
            Route::get('create', 'EmailTemplateController@create')->name('create');
            // Route::get('create', 'EmailTemplateController@create')->name('create');
            Route::post('delete', 'EmailTemplateController@destroy')->name('delete');
            Route::get('view/{id}', 'EmailTemplateController@view')->name('view');
            Route::post('update/{id}', 'EmailTemplateController@update')->name('update');
            Route::post('send-mail/{id}', 'EmailTemplateController@send_mail')->name('send-mail');
        });

        Route::group(['prefix' => 'sms-templates', 'as' => 'sms-templates.', 'middleware' => ['module:support_section']], function () {
            Route::post('store', 'SMSTemplateController@store')->name('store');
            Route::get('list', 'SMSTemplateController@list')->name('list');
            Route::get('create', 'SMSTemplateController@create')->name('create');
            // Route::get('create', 'SMSTemplateController@create')->name('create');
            Route::post('delete', 'SMSTemplateController@destroy')->name('delete');
            Route::get('view/{id}', 'SMSTemplateController@view')->name('view');
            Route::post('update/{id}', 'SMSTemplateController@update')->name('update');
            Route::post('send-mail/{id}', 'SMSTemplateController@send_mail')->name('send-mail');
        });

        Route::group(['prefix' => 'applicants', 'as' => 'application.'], function () {
            Route::get('assessment', 'ApplicationController@index')->name('assessment');
            Route::post('update-status', 'ApplicationController@update_status')->name('status_update');
            Route::post('update-accessor', 'ApplicationController@update_accessor')->name('update_accessor');
            Route::post('get-list', 'ApplicationController@get_accessor_list')->name('get-list');
            Route::get('approved', 'ApplicationController@approved_application')->name('approved-list');
            Route::get('view-detail/{application}', 'ApplicationController@view_detail')->name('view-detail');
            Route::post('update-comment', 'ApplicationController@updateComment')->name('update-comment');
            Route::post('admin-remark', 'ApplicationController@updateAdminRemark')->name('admin-remark');
            Route::post('get-comment-history', 'ApplicationController@getCommentHistory')->name('get-comment-history');
            Route::post('get-finding-history', 'ApplicationController@getFindingHistory')->name('get-finding-history');
            Route::post('update-payment-status', 'ApplicationController@updatePaymentStatus')->name('update-payment-status');
            Route::post('update-application-status','ApplicationController@updateApplicationStatus')->name('update-application-status');
            Route::post('start-surveillance','ApplicationController@startSurveillance')->name('start-surveillance');
            Route::get('witness-list', 'ApplicationController@clientWitnessList')->name('witness-list');
            Route::get('get-application', 'ApplicationController@getApplication')->name('getApplication');
            Route::get('get-client', 'ApplicationController@getClients')->name('getClient');
            Route::post('store-witness', 'ApplicationController@storeWitness')->name('store-witness');
            Route::post('update-witness-team','ApplicationController@updateWitnessTeam')->name('update-witness-team');
           Route::get('witness/view/{id}/{tab?}','ApplicationController@viewWitness')->name('witness-view');

            



            Route::get('office',function(){
                return view('admin-views.applicants.checklist');
            });
        });

        Route::get('applicants/assessment-details', 'ChattingController@assessment_details')->name('assessment-details');
        Route::get('applicants/assessment1', 'ChattingController@assessment')->name('assessment');
        
        Route::get('applicants/allview', 'ChattingController@allview')->name('allview');

        Route::get('customer/user-chat/{application_id?}', 'ChattingController@user_chat')->name('user-chat');
        Route::get('customer/delete-chat/{message_id?}', 'ChattingController@delete_chat')->name('delete-chat');
        Route::get('customer/delete-all/{application_id?}', 'ChattingController@delete_all')->name('delete-all');
        Route::get('customer/ajax-user-chat', 'ChattingController@ajax_user_chat')->name('ajax-user-chat');
        Route::post('customer/store-user-chat', 'ChattingController@store_user_chat')->name('store-user-chat');

        Route::group(['prefix' => 'delivery-man', 'as' => 'delivery-man.', 'middleware' => ['module:user_section']], function () {
            Route::get('get-user-chat', 'ChattingController@ajax_message_by_customer')->name('ajax-user-chat');
            Route::post('store-user-chat', 'ChattingController@ajax_admin_message_store_customer')->name('store-user-chat');
            Route::get('add', 'DeliveryManController@index')->name('add');
            Route::post('store', 'DeliveryManController@store')->name('store');
            Route::get('list', 'DeliveryManController@list')->name('list');
            Route::get('review-list/{id}', 'DeliveryManController@review_list')->name('review-list');
            Route::get('preview/{id}', 'DeliveryManController@preview')->name('preview');
            Route::get('edit/{id}', 'DeliveryManController@edit')->name('edit');
            Route::post('update/{id}', 'DeliveryManController@update')->name('update');
            Route::delete('delete/{id}', 'DeliveryManController@delete')->name('delete');
            Route::post('search', 'DeliveryManController@search')->name('search');
            Route::post('status-update', 'DeliveryManController@status')->name('status-update');
            Route::get('earning-statement-overview/{id}', 'DeliveryManController@earning_statement_overview')->name('earning-statement-overview');
            Route::get('collect-cash/{id}', 'DeliveryManCashCollectController@collect_cash')->name('collect-cash');
            Route::post('cash-receive/{id}', 'DeliveryManCashCollectController@cash_receive')->name('cash-receive');
            Route::get('order-history-log/{id}', 'DeliveryManController@order_history_log')->name('order-history-log');
            Route::get('order-wise-earning/{id}', 'DeliveryManController@order_wise_earning')->name('order-wise-earning');
            Route::get('ajax-order-status-history/{order}', 'DeliveryManController@ajax_order_status_history')->name('ajax-order-status-history');
            Route::get('assign-bulk-order', 'DeliveryManController@assign_bulk_order')->name('assign-bulk-order');
            Route::post('assign-bulk-order-store', 'DeliveryManController@assign_bulk_order_store')->name('assign-bulk-order-store');

            Route::get('withdraw-list', 'DeliverymanWithdrawController@withdraw')->name('withdraw-list');
            Route::get('withdraw-list-export', 'DeliverymanWithdrawController@export')->name('withdraw-list-export');
            Route::post('status-filter', 'DeliverymanWithdrawController@status_filter')->name('status-filter');
            Route::get('withdraw-view/{withdraw_id}', 'DeliverymanWithdrawController@withdraw_view')->name('withdraw-view');
            Route::post('withdraw-status/{id}', 'DeliverymanWithdrawController@withdraw_status')->name('withdraw_status');

            Route::get('chat', 'ChattingController@chat')->name('chat');
            Route::get('ajax-message-by-delivery-man', 'ChattingController@ajax_message_by_delivery_man')->name('ajax-message-by-delivery-man');
            Route::post('admin-message-store', 'ChattingController@ajax_admin_message_store')->name('ajax-admin-message-store');


            Route::group(['prefix' => 'emergency-contact', 'as' => 'emergency-contact.'], function () {
                Route::get('/', 'EmergencyContactController@emergency_contact')->name('index');
                Route::post('add', 'EmergencyContactController@add')->name('add');
                Route::post('ajax-status-change', 'EmergencyContactController@ajax_status_change')->name('ajax-status-change');
                Route::delete('destroy', 'EmergencyContactController@destroy')->name('destroy');
            });

            Route::get('rating/{id}', 'DeliveryManController@rating')->name('rating');
        });

        Route::group(['prefix' => 'file-manager', 'as' => 'file-manager.', 'middleware' => ['module:system_settings']], function () {
            Route::get('/download/{file_name}', 'FileManagerController@download')->name('download');
            Route::get('/index/{folder_path?}', 'FileManagerController@index')->name('index');
            Route::post('/image-upload', 'FileManagerController@upload')->name('image-upload');
            Route::delete('/delete/{file_path}', 'FileManagerController@destroy')->name('destroy');
        });

        Route::group(['prefix' => 'login-report', 'as' => 'login-report.', 'middleware' => ['module:system_settings']], function () {
            Route::get('report/{report_type}', 'CustomerController@loginReport')->name('user.report');
            Route::get('report-paginate/{report_type?}', 'CustomerController@loginReportPaginate')->name('user.paginate');
        });

        Route::group(['prefix' => 'user-active', 'as' => 'user-active.', 'middleware' => ['module:system_settings']], function () {
            Route::get('report/{report_type}', 'CustomerController@loginActiveReport')->name('user.report');
            Route::get('report-paginate/{report_type?}', 'CustomerController@loginActiveReportPaginate')->name('user.paginate');
        });

        Route::group(['prefix' => 'application/chat', 'as' => 'application-chat.'], function() {
            Route::get('{type}/{application_id}', 'RoomController@index')->name('index');
            Route::post('history', 'RoomController@getChatHistory')->name('history');
            Route::post('send-message', 'RoomController@sendMessage')->name('send-message');
            Route::post('delete-message', 'RoomController@deleteMessage')->name('delete-message');
        });

    });

    //for test

    /*Route::get('login', 'testController@login')->name('login');*/
});
