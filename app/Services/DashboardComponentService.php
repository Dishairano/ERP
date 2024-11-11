<?php

namespace App\Services;

use App\Models\DashboardComponent;
use App\Services\ComponentTypes\ComponentTypeFactory;
use App\Services\ComponentTypes\ComponentTypeInterface;
use Illuminate\Support\Facades\Cache;

class DashboardComponentService
{
  protected ComponentTypeFactory $componentTypeFactory;

  public function __construct(ComponentTypeFactory $componentTypeFactory)
  {
    $this->componentTypeFactory = $componentTypeFactory;
  }

  /**
   * Get available component types
   */
  public function getAvailableComponentTypes(): array
  {
    return $this->componentTypeFactory->getAvailableTypes();
  }

  /**
   * Get component type handler
   */
  public function getComponentTypeHandler(string $type): ComponentTypeInterface
  {
    return $this->componentTypeFactory->create($type);
  }

  /**
   * Process data based on component type and settings
   */
  public function processComponentData(DashboardComponent $component, array $data): array
  {
    $handler = $this->getComponentTypeHandler($component->type);
    return $handler->processData($data, $component->settings);
  }

  /**
   * Get default settings for a component type
   */
  public function getDefaultSettings(string $type): array
  {
    $handler = $this->getComponentTypeHandler($type);
    return $handler->getDefaultSettings();
  }

  /**
   * Merge default settings with provided settings
   */
  public function mergeWithDefaultSettings(string $type, array $settings): array
  {
    $defaultSettings = $this->getDefaultSettings($type);
    return array_replace_recursive($defaultSettings, $settings);
  }

  /**
   * Register a new component type
   */
  public function registerComponentType(string $type, string $handlerClass): void
  {
    $this->componentTypeFactory->register($type, $handlerClass);
  }

  /**
   * Get component data with caching
   */
  public function getComponentData(DashboardComponent $component, bool $forceFresh = false): array
  {
    $cacheKey = "component_data_{$component->id}";
    $cacheDuration = $component->cache_duration ?? config('dashboard.cache.default_duration', 300);

    if ($forceFresh) {
      $this->clearComponentCache($component);
    }

    return Cache::remember($cacheKey, $cacheDuration, function () use ($component) {
      $rawData = $component->dataSource?->fetchData($component->settings['query'] ?? []) ?? [];
      return $this->processComponentData($component, $rawData);
    });
  }

  /**
   * Clear component cache
   */
  public function clearComponentCache(DashboardComponent $component): void
  {
    Cache::forget("component_data_{$component->id}");
    Cache::forget("component_last_refresh_{$component->id}");
  }

  /**
   * Validate component settings
   */
  public function validateSettings(string $type, array $settings): bool
  {
    try {
      $handler = $this->getComponentTypeHandler($type);
      $defaultSettings = $handler->getDefaultSettings();

      // Basic structure validation
      foreach ($defaultSettings as $key => $value) {
        if (!array_key_exists($key, $settings)) {
          return false;
        }
      }

      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  /**
   * Check if component needs refresh
   */
  public function needsRefresh(DashboardComponent $component): bool
  {
    if (!$component->refresh_interval) {
      return false;
    }

    $lastRefresh = Cache::get("component_last_refresh_{$component->id}");
    if (!$lastRefresh) {
      return true;
    }

    return now()->diffInSeconds($lastRefresh) >= $component->refresh_interval;
  }

  /**
   * Update last refresh timestamp
   */
  public function updateLastRefresh(DashboardComponent $component): void
  {
    Cache::put("component_last_refresh_{$component->id}", now());
  }

  /**
   * Handle component type specific setup
   */
  public function handleTypeSpecificSetup(DashboardComponent $component): void
  {
    $handler = $this->getComponentTypeHandler($component->type);
    $handler->handleSetup($component);
  }

  /**
   * Handle component type specific update
   */
  public function handleTypeSpecificUpdate(DashboardComponent $component): void
  {
    $handler = $this->getComponentTypeHandler($component->type);
    $handler->handleUpdate($component);
  }

  /**
   * Get visualization options for component type
   */
  public function getVisualizationOptions(string $type): array
  {
    return match ($type) {
      'chart' => ['line', 'bar', 'pie', 'donut', 'area', 'scatter'],
      'table' => ['basic', 'sortable', 'filterable', 'paginated'],
      'metric' => ['single', 'multi', 'trend'],
      'list' => ['basic', 'ordered', 'grid', 'timeline'],
      'calendar' => ['month', 'week', 'agenda', 'timeline'],
      'map' => ['markers', 'heatmap', 'choropleth'],
      'custom' => ['custom'],
      default => []
    };
  }

  /**
   * Get size options
   */
  public function getSizeOptions(): array
  {
    return config('dashboard.sizes', [
      'small' => [
        'width' => 1,
        'height' => 1,
        'class' => 'col-md-3'
      ],
      'medium' => [
        'width' => 2,
        'height' => 1,
        'class' => 'col-md-6'
      ],
      'large' => [
        'width' => 3,
        'height' => 2,
        'class' => 'col-md-9'
      ]
    ]);
  }

  /**
   * Get refresh interval options
   */
  public function getRefreshIntervalOptions(): array
  {
    return config('dashboard.refresh_intervals', [
      'realtime' => 5,
      'fast' => 30,
      'normal' => 300,
      'slow' => 900,
      'manual' => null
    ]);
  }
}
