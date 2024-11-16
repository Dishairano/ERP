<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Load web routes first without auth middleware
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Load core routes with auth middleware
            Route::middleware(['web', 'auth'])
                ->group(base_path('routes/core.php'));

            // Load other routes with auth middleware
            Route::middleware(['web', 'auth'])
                ->group(function () {
                    require base_path('routes/coreFunctions.php');
                    require base_path('routes/projects.php');
                    require base_path('routes/finance.php');
                    require base_path('routes/hrm.php');
                    require base_path('routes/leave-requests.php');
                    require base_path('routes/time-registrations.php');
                    require base_path('routes/settings.php');
                    require base_path('routes/scheduling.php');
                });
        });
    }
}
