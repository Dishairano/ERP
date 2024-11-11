<?php

namespace App\View\Components\Dashboard;

use App\Models\DashboardComponent;
use App\Services\DashboardComponentService;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

abstract class BaseComponent extends Component
{
  protected DashboardComponentService $componentService;
  public DashboardComponent $component;
  public array $data;
  public array $settings;
  public string $type;

  public function __construct(
    DashboardComponentService $componentService,
    DashboardComponent $component
  ) {
    $this->componentService = $componentService;
    $this->component = $component;
    $this->data = $this->componentService->getComponentData($component);
    $this->settings = $component->settings;
    $this->type = $component->type;
  }

  /**
   * Get the view / contents that represent the component.
   */
  abstract public function render();

  /**
   * Get the component's unique identifier
   */
  public function getId(): string
  {
    return "component-{$this->component->id}";
  }

  /**
   * Get the component's size class
   */
  public function getSizeClass(): string
  {
    return config("dashboard.sizes.{$this->component->size}.class", 'col-md-6');
  }

  /**
   * Get the component's refresh interval in milliseconds
   */
  public function getRefreshInterval(): ?int
  {
    return $this->component->refresh_interval * 1000;
  }

  /**
   * Check if the component needs a refresh
   */
  public function needsRefresh(): bool
  {
    return $this->componentService->needsRefresh($this->component);
  }

  /**
   * Get component-specific scripts
   */
  public function getScripts(): array
  {
    return [];
  }

  /**
   * Get component-specific styles
   */
  public function getStyles(): array
  {
    return [];
  }

  /**
   * Get the component's custom styles
   */
  public function getCustomStyles(): string
  {
    $styles = $this->component->custom_styles ?? [];
    return collect($styles)->map(function ($value, $property) {
      return "{$property}: {$value};";
    })->implode(' ');
  }

  /**
   * Get the component's data attributes
   */
  public function getDataAttributes(): array
  {
    return [
      'id' => $this->getId(),
      'type' => $this->type,
      'refresh-url' => route('dashboard-components.refresh-data', [
        'dashboard' => $this->component->dashboard_id,
        'component' => $this->component->id
      ]),
      'refresh-interval' => $this->getRefreshInterval(),
      'settings' => json_encode($this->settings),
    ];
  }

  /**
   * Get the array of attributes for the component
   */
  protected function getAttributes(): array
  {
    return array_merge(
      $this->getDataAttributes(),
      [
        'class' => $this->getClasses(),
        'style' => $this->getCustomStyles(),
      ]
    );
  }

  /**
   * Get the component's CSS classes
   */
  protected function getClasses(): string
  {
    return implode(' ', [
      'dashboard-component',
      "component-{$this->type}",
      $this->getSizeClass(),
      $this->component->settings['display']['class'] ?? '',
    ]);
  }

  /**
   * Determine if the component should be rendered
   */
  public function shouldRender(): bool
  {
    return $this->component->is_enabled &&
      Auth::check() &&
      Gate::allows('view', $this->component);
  }
}
