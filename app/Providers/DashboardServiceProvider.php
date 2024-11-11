<?php

namespace App\Providers;

use App\Services\ComponentTypes\ComponentTypeFactory;
use App\Services\ComponentTypes\ChartComponentType;
use App\Services\ComponentTypes\TableComponentType;
use App\Services\ComponentTypes\MetricComponentType;
use App\Services\ComponentTypes\ListComponentType;
use App\Services\DashboardComponentService;
use App\View\Components\Dashboard\Chart;
use App\View\Components\Dashboard\Table;
use App\View\Components\Dashboard\Metric;
use App\View\Components\Dashboard\ListComponent;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
  /**
   * Component type aliases for easier reference
   */
  protected array $componentAliases = [
    // Chart aliases
    'line-chart' => ChartComponentType::class,
    'bar-chart' => ChartComponentType::class,
    'pie-chart' => ChartComponentType::class,
    'area-chart' => ChartComponentType::class,
    'scatter-chart' => ChartComponentType::class,
    'bubble-chart' => ChartComponentType::class,
    'radar-chart' => ChartComponentType::class,
    'polar-chart' => ChartComponentType::class,
    'mixed-chart' => ChartComponentType::class,

    // Table aliases
    'data-table' => TableComponentType::class,
    'grid' => TableComponentType::class,
    'spreadsheet' => TableComponentType::class,

    // Metric aliases
    'kpi' => MetricComponentType::class,
    'counter' => MetricComponentType::class,
    'statistic' => MetricComponentType::class,
    'number-card' => MetricComponentType::class,

    // List aliases
    'feed' => ListComponentType::class,
    'timeline' => ListComponentType::class,
    'activity-feed' => ListComponentType::class,
    'notification-list' => ListComponentType::class
  ];

  /**
   * Register services.
   */
  public function register(): void
  {
    // Register ComponentTypeFactory as a singleton
    $this->app->singleton(ComponentTypeFactory::class, function ($app) {
      $factory = new ComponentTypeFactory();

      // Register base component types
      $factory->register('chart', ChartComponentType::class);
      $factory->register('table', TableComponentType::class);
      $factory->register('metric', MetricComponentType::class);
      $factory->register('list', ListComponentType::class);

      // Register component aliases
      foreach ($this->componentAliases as $alias => $class) {
        $factory->register($alias, $class);
      }

      // Additional component types will be registered here as they are created
      // $factory->register('calendar', CalendarComponentType::class);
      // $factory->register('map', MapComponentType::class);

      return $factory;
    });

    // Register DashboardComponentService
    $this->app->singleton(DashboardComponentService::class, function ($app) {
      return new DashboardComponentService(
        $app->make(ComponentTypeFactory::class)
      );
    });

    // Register config
    $this->mergeConfigFrom(
      __DIR__ . '/../../config/dashboard.php',
      'dashboard'
    );
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    // Register dashboard routes
    $this->loadRoutesFrom(base_path('routes/routes/dashboard-components.php'));

    // Register dashboard views
    $this->loadViewsFrom(resource_path('views/dashboard-components'), 'dashboard-components');

    // Register dashboard migrations
    $this->loadMigrationsFrom(database_path('migrations'));

    // Register dashboard policies
    $this->registerPolicies();

    // Publish dashboard assets
    $this->publishes([
      __DIR__ . '/../../resources/js/dashboard-components' => resource_path('js/dashboard-components'),
      __DIR__ . '/../../resources/css/dashboard-components' => resource_path('css/dashboard-components'),
      __DIR__ . '/../../resources/views/dashboard-components' => resource_path('views/vendor/dashboard-components'),
    ], 'dashboard-assets');

    // Publish dashboard config
    $this->publishes([
      __DIR__ . '/../../config/dashboard.php' => config_path('dashboard.php'),
    ], 'dashboard-config');

    // Register Blade components
    $this->registerBladeComponents();
  }

  /**
   * Register dashboard policies.
   */
  protected function registerPolicies(): void
  {
    $gate = $this->app['gate'];

    $gate->policy(\App\Models\Dashboard::class, \App\Policies\DashboardPolicy::class);
    $gate->policy(\App\Models\DashboardComponent::class, \App\Policies\DashboardComponentPolicy::class);
  }

  /**
   * Register Blade components.
   */
  protected function registerBladeComponents(): void
  {
    $this->loadViewComponentsAs('dashboard', [
      'chart' => Chart::class,
      'table' => Table::class,
      'metric' => Metric::class,
      'list' => ListComponent::class,
    ]);
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array<int, string>
   */
  public function provides(): array
  {
    return [
      ComponentTypeFactory::class,
      DashboardComponentService::class,
    ];
  }
}
