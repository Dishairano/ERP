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
   * Typically, users are redirected here after authentication.
   *
   * @var string
   */
  public const HOME = '/dashboard/analytics';

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

      Route::middleware('web')
        ->group(base_path('routes/web.php'));

      // Core Module Routes
      Route::middleware('web')
        ->group(base_path('routes/core.php'));

      // Core Functions Routes
      Route::middleware('web')
        ->group(base_path('routes/coreFunctions.php'));

      // Time Registration Routes
      Route::middleware('web')
        ->group(base_path('routes/time-registrations.php'));

      // Warehousing Routes
      Route::middleware('web')
        ->group(base_path('routes/warehousing.php'));

      // Project Routes
      Route::middleware('web')
        ->group(base_path('routes/projects.php'));

      // Manufacturing Routes
      Route::middleware('web')
        ->group(base_path('routes/manufacturing.php'));

      // Sales Routes
      Route::middleware('web')
        ->group(base_path('routes/sales.php'));

      // Settings Routes
      Route::middleware('web')
        ->group(base_path('routes/settings.php'));

      // Analytics Routes
      Route::middleware('web')
        ->group(base_path('routes/analytics.php'));

      // Budget Routes
      Route::middleware('web')
        ->group(base_path('routes/budgets.php'));
    });
  }
}
