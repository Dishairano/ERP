<?php

namespace App\View\Components\Dashboard;

class Chart extends BaseComponent
{
  /**
   * Get the view / contents that represent the component.
   */
  public function render()
  {
    return view('dashboard-components.chart', [
      'attributes' => $this->getAttributes(),
      'data' => $this->data,
      'settings' => $this->settings
    ]);
  }

  /**
   * Get component-specific scripts
   */
  public function getScripts(): array
  {
    $library = $this->settings['library'] ?? config('dashboard.components.chart.default_library');

    return match ($library) {
      'chartjs' => [
        config('dashboard.chart_libraries.chartjs.cdn')
      ],
      'echarts' => [
        config('dashboard.chart_libraries.echarts.cdn')
      ],
      default => []
    };
  }
}
