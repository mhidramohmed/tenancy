<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain; // Tenant middleware
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // --------------------------
            // Central Routes
            // --------------------------
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // --------------------------
            // Tenant Routes
            // --------------------------
            Route::middleware([
                'web',
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class
            ])
            ->group(base_path('routes/tenant.php'));

            Route::prefix('api')
                ->middleware([
                    'api',
                    InitializeTenancyByDomain::class,
                    PreventAccessFromCentralDomains::class
                ])
                ->group(base_path('routes/tenant_api.php'));
        });
    }
}
