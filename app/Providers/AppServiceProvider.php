<?php

namespace App\Providers;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Model\Banner;
use App\Model\BusinessSetting;
use App\Model\Category;
use App\Model\Currency;
use App\Model\FlashDeal;
use App\Model\Country;
use App\Model\FlashDealProduct;
use App\Model\SocialMedia;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Model\Shop;
use App\Model\Brand;
use App\Model\Coupon;
use App\Model\Product;
use App\Model\Tag;
use App\Model\UserNotification;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

ini_set('memory_limit', -1);
ini_set('upload_max_filesize', '180M');
ini_set('post_max_size', '200M');

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path('CPU/storefront_public_uri.php');

        if ($this->app->isLocal()) {
            $this->app->register(\Amirami\Localizator\ServiceProvider::class);
        }

        // Stancl v3.4 calls specifyParameters() twice on Seed/Rollback (breaks Artisan).
        if (class_exists(\Stancl\Tenancy\Commands\Seed::class)) {
            $this->app->singleton(\Stancl\Tenancy\Commands\Seed::class, function ($app) {
                return new \App\Tenancy\Console\TenantsSeedCommand($app['db']);
            });
        }
        if (class_exists(\Stancl\Tenancy\Commands\Rollback::class)) {
            $this->app->singleton(\Stancl\Tenancy\Commands\Rollback::class, function ($app) {
                return new \App\Tenancy\Console\TenantsRollbackCommand($app['migrator']);
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        Paginator::useBootstrap();

        View::composer('layouts.super-admin.partials._side-bar', function (\Illuminate\View\View $view) {
            $subdomainSidebarEntities = collect();
            if (Auth::guard('super_admin')->check()) {
                try {
                    $subdomainSidebarEntities = \App\Models\Entity::query()
                        ->with('domains')
                        ->orderBy('name')
                        ->get();
                } catch (\Throwable $e) {
                    $subdomainSidebarEntities = collect();
                }
            }
            $view->with('subdomainSidebarEntities', $subdomainSidebarEntities);
        });

        if (class_exists(\Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class)) {
            $kernel = $this->app[\Illuminate\Contracts\Http\Kernel::class];
            foreach (array_reverse([
                \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
                \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
            ]) as $middleware) {
                $kernel->prependToMiddlewarePriority($middleware);
            }
        }

        // Tenant host (e.g. rahul.localhost:8000) must drive asset()/URL() or CSS, captcha, and scripts 404 on APP_URL host.
        // When ASSET_URL is set, Laravel's asset() uses it only and ignores forceRootUrl — clear assetRoot for tenant requests.
        if (class_exists(\Stancl\Tenancy\Events\TenancyInitialized::class)) {
            Event::listen(\Stancl\Tenancy\Events\TenancyInitialized::class, function () {
                if (app()->runningInConsole()) {
                    return;
                }
                $request = request();
                if ($request) {
                    URL::forceRootUrl($request->getSchemeAndHttpHost());
                    self::setUrlGeneratorAssetRoot(null);
                }
            });
        }
        if (class_exists(\Stancl\Tenancy\Events\TenancyEnded::class)) {
            Event::listen(\Stancl\Tenancy\Events\TenancyEnded::class, function () {
                if (app()->runningInConsole()) {
                    return;
                }
                self::setUrlGeneratorAssetRoot(config('app.asset_url'));
                URL::forceRootUrl(rtrim((string) config('app.url'), '/'));
            });
        }

        try {
            if (Schema::hasTable('business_settings')) {

                $web = BusinessSetting::all();
                $settings = Helpers::get_settings($web, 'colors');
                $data = $settings ? (json_decode($settings['value'], true) ?: []) : [];
                if (!isset($data['primary'])) {
                    $data['primary'] = '#1b7fed';
                }
                if (!isset($data['secondary'])) {
                    $data['secondary'] = '#000000';
                }

                $countries = Schema::hasTable('countries') ? Country::query()->get() : collect();
                $coupons = null;
                if (Schema::hasTable('coupons')) {
                    $coupons = Coupon::where('status', 1)->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('expire_date', '>=', date('Y-m-d'))->first();
                }

                $settingRow = static function ($row) {
                    if ($row === null) {
                        return (object) ['value' => ''];
                    }

                    return is_object($row) ? $row : (object) ['value' => $row['value'] ?? ''];
                };

                $appHomeDefault = [
                    'gift_section' => ['gift_title' => '', 'gift_product_ids' => ''],
                    'top_deal_product' => ['title' => '', 'product_ids' => ''],
                ];
                $appHome = Helpers::get_business_settings('app_home_page');
                if (!is_array($appHome)) {
                    $appHome = $appHomeDefault;
                } else {
                    $appHome = array_replace_recursive($appHomeDefault, $appHome);
                }

                $web_config = [
                    'primary_color' => $data['primary'],
                    'secondary_color' => $data['secondary'],
                    'primary_color_light' => isset($data['primary_light']) ? $data['primary_light'] : '',
                    'name' => $settingRow(Helpers::get_settings($web, 'company_name')),
                    'phone' => $settingRow(Helpers::get_settings($web, 'company_phone')),
                    'web_logo' => $settingRow(Helpers::get_settings($web, 'company_web_logo')),
                    'mob_logo' => $settingRow(Helpers::get_settings($web, 'company_mobile_logo')),
                    'fav_icon' => $settingRow(Helpers::get_settings($web, 'company_fav_icon')),
                    'email' => $settingRow(Helpers::get_settings($web, 'company_email')),
                    'about' => $settingRow(Helpers::get_settings($web, 'about_us')),
                    'footer_logo' => $settingRow(Helpers::get_settings($web, 'company_footer_logo')),
                    'copyright_text' => $settingRow(Helpers::get_settings($web, 'company_copyright_text')),
                    'decimal_point_settings' => !empty(\App\CPU\Helpers::get_business_settings('decimal_point_settings')) ? \App\CPU\Helpers::get_business_settings('decimal_point_settings') : 0,
                    'seller_registration' => optional(BusinessSetting::where(['type' => 'seller_registration'])->first())->value ?? '0',
                    'wallet_status' => Helpers::get_business_settings('wallet_status'),
                    'loyalty_point_status' => Helpers::get_business_settings('loyalty_point_status'),
                    'app_home' => $appHome,
                ];

                
                

                if (!Request::is('admin') && !Request::is('admin/*') && !Request::is('seller/*')) {
                    $shops = collect();
                    $recaptcha = Helpers::get_business_settings('recaptcha') ?? ['status' => 0, 'site_key' => '', 'secret_key' => ''];
                    $socials_login = Helpers::get_business_settings('social_login') ?? [];
                    $social_login_text = false;
                    foreach ($socials_login as $socialLoginService) {
                        if (!empty($socialLoginService['status']) && $socialLoginService['status'] === true) {
                            $social_login_text = true;
                            break;
                        }
                    }

                    $flash_deals = []; 
                    // FlashDeal::with(['products.product.reviews', 'products.product' => function ($query) {
                    //     $query->active();
                    // }])->where(['deal_type' => 'flash_deal', 'status' => 1])
                    //     ->whereDate('start_date', '<=', date('Y-m-d'))
                    //     ->whereDate('end_date', '>=', date('Y-m-d'))
                    //     ->first();

                    $featured_deals = [];
                    // Product::active()
                    //     ->with([
                    //         'seller.shop',
                    //         'flash_deal_product.feature_deal',
                    //         'flash_deal_product.flash_deal' => function ($query) {
                    //             return $query->whereDate('start_date', '<=', date('Y-m-d'))
                    //                 ->whereDate('end_date', '>=', date('Y-m-d'));
                    //         }
                    //     ])
                    //     ->whereHas('flash_deal_product.feature_deal', function ($query) {
                    //         $query->whereDate('start_date', '<=', date('Y-m-d'))
                    //             ->whereDate('end_date', '>=', date('Y-m-d'));
                    //     })
                    //     ->get();

                    // if ($featured_deals) {
                    //     foreach ($featured_deals as $product) {
                    //         $flash_deal_status = 0;
                    //         $flash_deal_end_date = 0;

                    //         foreach ($product->flash_deal_product as $deal) {
                    //             $flash_deal_status = $deal->flash_deal ? 1 : $flash_deal_status;
                    //             $flash_deal_end_date = isset($deal->flash_deal->end_date) ? date('Y-m-d H:i:s', strtotime($deal->flash_deal->end_date)) : $flash_deal_end_date;
                    //         }

                    //         $product['flash_deal_status'] = $flash_deal_status;
                    //         $product['flash_deal_end_date'] = $flash_deal_end_date;
                    //     }
                    // }

                    // $shops = Shop::whereHas('seller', function ($query) {
                    //     return $query->approved();
                    // })->take(9)->get();

                    // $recaptcha = Helpers::get_business_settings('recaptcha');
                    // $socials_login = Helpers::get_business_settings('social_login');
                    // $social_login_text = false;
                    // foreach ($socials_login as $socialLoginService) {
                    //     if (isset($socialLoginService) && $socialLoginService['status'] == true) {
                    //         $social_login_text = true;
                    //     }
                    // }

                    $popup_banner = null;
                    $header_banner = null;
                    if (Schema::hasTable('banners')) {
                        $popup_banner = Banner::inRandomOrder()->where(['published' => 1, 'banner_type' => 'Popup Banner'])->first();
                        $header_banner = Banner::where('banner_type', 'Header Banner')->where('published', 1)->latest()->first();
                    }

                    $payments_name_list = ['ssl_commerz_payment', 'paypal', 'stripe', 'razor_pay', 'senang_pay',
                        'paytabs', 'paystack', 'paymob_accept', 'fawry_pay', 'mercadopago', 'liqpay', 'flutterwave',
                        'paytm', 'bkash'];
                    $payments_list = collect();
                    try {
                        $payments_list = BusinessSetting::whereIn('type', $payments_name_list)->whereJsonContains('value->status', '1')->pluck('type');
                    } catch (\Throwable $e) {
                        $payments_list = collect();
                    }

                    $mainCategories = collect();
                    if (Schema::hasTable('categories')) {
                        $mainCategories = Category::with(['childes.childes'])->where('position', 0)->priority()->get();
                    }

                    $discountProductCount = 0;
                    if (Schema::hasTable('products')) {
                        try {
                            $discountProductCount = Product::with(['reviews'])->active()->where('discount', '!=', 0)->count();
                        } catch (\Throwable $e) {
                            $discountProductCount = 0;
                        }
                    }

                    $web_config += [
                        'cookie_setting' => Helpers::get_settings($web, 'cookie_setting'),
                        'announcement' => Helpers::get_business_settings('announcement'),
                        'currency_model' => Helpers::get_business_settings('currency_model'),
                        'currencies' => Schema::hasTable('currencies') ? Currency::where('status', 1)->get() : collect(),
                        'main_categories' => $mainCategories,
                        'business_mode' => Helpers::get_business_settings('business_mode'),
                        'social_media' => Schema::hasTable('social_medias') ? SocialMedia::where('active_status', 1)->get() : collect(),
                        'ios' => Helpers::get_business_settings('download_app_apple_stroe'),
                        'android' => Helpers::get_business_settings('download_app_google_stroe'),
                        'refund_policy' => Helpers::get_business_settings('refund-policy'),
                        'return_policy' => Helpers::get_business_settings('return-policy'),
                        'cancellation_policy' => Helpers::get_business_settings('cancellation-policy'),
                        'flash_deals' => $flash_deals,
                        'featured_deals' => $featured_deals,
                        'shops' => $shops,
                        'brand_setting' => Helpers::get_business_settings('product_brand'),
                        'discount_product' => $discountProductCount,
                        'recaptcha' => $recaptcha,
                        'socials_login' => $socials_login,
                        'social_login_text' => $social_login_text,
                        'popup_banner' => $popup_banner,
                        'header_banner' => $header_banner,
                        'payments_list' => $payments_list, // fashion_theme
                    ];

                    if (theme_root_path() == "theme_fashion") {

                        $features_section = [
                            'features_section_top' => BusinessSetting::where('type', 'features_section_top')->first() ? BusinessSetting::where('type', 'features_section_top')->first()->value : [],
                            'features_section_middle' => BusinessSetting::where('type', 'features_section_middle')->first() ? BusinessSetting::where('type', 'features_section_middle')->first()->value : [],
                            'features_section_bottom' => BusinessSetting::where('type', 'features_section_bottom')->first() ? BusinessSetting::where('type', 'features_section_bottom')->first()->value : [],
                        ];

                        $tags = collect();
                        $total_discount_products = 0;
                        if (Schema::hasTable('tags')) {
                            $tags = Tag::orderBy('visit_count', 'desc')->take(15)->get();
                        }
                        if (Schema::hasTable('products')) {
                            try {
                                $total_discount_products = Product::with(['reviews'])->active()->where('discount', '!=', 0)->count();
                            } catch (\Throwable $e) {
                                $total_discount_products = 0;
                            }
                        }

                        $web_config += [
                            'tags' => $tags,
                            'features_section' => $features_section,
                            'total_discount_products' => $total_discount_products,
                        ];
                    }
                }

                //language
                $language = BusinessSetting::where('type', 'language')->first();

                //currency
                try {
                    \App\CPU\Helpers::currency_load();
                } catch (\Throwable $e) {
                    // Tenant may lack currencies / related tables until migrated.
                }

                // dd($web_config, !Request::is('admin'), !Request::is('admin/*'), Request::is('seller/*'));

                $giftIdsRaw = $web_config['app_home']['gift_section']['gift_product_ids'] ?? '';
                $topIdsRaw = $web_config['app_home']['top_deal_product']['product_ids'] ?? '';
                $gift_ids = array_filter(explode(',', (string) $giftIdsRaw), static function ($id) {
                    return $id !== '' && $id !== '0';
                });
                $top_product_ids = array_filter(explode(',', (string) $topIdsRaw), static function ($id) {
                    return $id !== '' && $id !== '0';
                });
                if (!empty($gift_ids) && Schema::hasTable('products')) {
                    try {
                        $web_config['gift_products_query'] = Product::with(['reviews'])->active()->whereIn('id', $gift_ids)->get();
                    } catch (\Throwable $e) {
                        $web_config['gift_products_query'] = collect();
                    }
                }

                if (!empty($top_product_ids) && Schema::hasTable('products')) {
                    try {
                        $web_config['top_product_ids'] = Product::with(['reviews'])->active()->whereIn('id', $top_product_ids)->get();
                    } catch (\Throwable $e) {
                        $web_config['top_product_ids'] = collect();
                    }
                }

                $notifications = [];
                $notification_count = 0;

                if (!isset($web_config['gift_products_query'])) {
                    $web_config['gift_products_query'] = collect();
                }
                if (!isset($web_config['top_product_ids'])) {
                    $web_config['top_product_ids'] = collect();
                }

                $normalizeAppStoreSetting = static function ($row) {
                    $defaults = ['status' => 0, 'link' => '#'];
                    if (!is_array($row)) {
                        return $defaults;
                    }

                    return [
                        'status' => !empty($row['status']),
                        'link' => isset($row['link']) ? (string) $row['link'] : '#',
                    ];
                };
                $web_config['ios'] = $normalizeAppStoreSetting($web_config['ios'] ?? null);
                $web_config['android'] = $normalizeAppStoreSetting($web_config['android'] ?? null);

                View::share(['notifications' => $notifications, 'notification_count' => $notification_count, 'coupons' => $coupons, 'web_config' => $web_config, 'language' => $language, 'countries' => $countries, 'zip_restrict_status' => false, 'default_location' => false]);

                Schema::defaultStringLength(191);
            }
        }catch (\Exception $exception){
            // dd($exception);
        }

        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */

        // Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
        //     $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

        //     return new LengthAwarePaginator(
        //         $this->forPage($page, $perPage),
        //         $total ?: $this->count(),
        //         $perPage,
        //         $page,
        //         [
        //             'path' => LengthAwarePaginator::resolveCurrentPath(),
        //             'pageName' => $pageName,
        //         ]
        //     );
        // });

    }

    /**
     * Laravel's UrlGenerator stores ASSET_URL in $assetRoot. When it is non-null, asset() ignores the
     * forced application URL, so tenant storefront pages would load CSS/JS from the wrong host.
     *
     * @param  string|null  $assetRoot  null = derive asset URLs from the current request root (after forceRootUrl).
     */
    private static function setUrlGeneratorAssetRoot(?string $assetRoot): void
    {
        $url = app('url');
        if (!$url instanceof \Illuminate\Routing\UrlGenerator) {
            return;
        }
        $ref = new \ReflectionClass($url);
        $property = $ref->getProperty('assetRoot');
        $property->setAccessible(true);
        $property->setValue($url, $assetRoot);
    }
}
