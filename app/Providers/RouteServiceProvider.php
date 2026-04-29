<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapCentralRoutes();
        $this->mapCentralApiRoutes();
        $this->mapTenantApplicationRoutes();
    }

    /**
     * Super Admin and other routes that must only run on central domains.
     *
     * @return void
     */
    protected function mapCentralRoutes()
    {
        foreach ($this->centralDomains() as $domain) {
            Route::middleware('web')
                ->namespace($this->namespace)
                ->domain($domain)
                ->group(base_path('routes/central.php'));
        }
    }

    /**
     * Central domain API routes.
     *
     * @return void
     */
    protected function mapCentralApiRoutes()
    {
        foreach ($this->centralDomains() as $domain) {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->domain($domain)
                ->group(base_path('routes/central-api.php'));
        }
    }

    /**
     * @return array<int, string>
     */
    protected function centralDomains(): array
    {
        $domains = config('tenancy.central_domains', ['127.0.0.1', 'localhost']);

        return array_values(array_filter($domains));
    }

    /**
     * Storefront, tenant admin, APIs — tenant identified by full host (e.g. rahul.localhost).
     * Domains are stored as FQDN in `domains.domain`; InitializeTenancyByDomain matches that.
     *
     * @return void
     */
    protected function mapTenantApplicationRoutes()
    {
        $tenancy = [
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
            \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
        ];

        Route::middleware(array_merge(['web'], $tenancy))
            ->namespace($this->namespace)
            ->group(base_path('routes/tenant-web.php'));

        Route::prefix('api')
            ->middleware(array_merge(['api'], $tenancy))
            ->namespace($this->namespace)
            ->group(base_path('routes/tenant-api.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }

    protected function mapSellerRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/seller.php'));
    }

    protected function mapCustomerRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/customer.php'));
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapSharedRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/shared.php'));
    }

    protected function mapTestRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/test.php'));
    }

    protected function mapInstallRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/install.php'));
    }

    protected function mapUpdateRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/update.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/api.php'));
    }

    protected function mapApiv2Routes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v2/api.php'));
    }

    protected function mapApiv3Routes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v3/seller.php'));
    }

    protected function mapApiv4Routes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v4/api.php'));
    }
}
