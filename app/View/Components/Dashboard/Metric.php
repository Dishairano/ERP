<?php

namespace App\View\Components\Dashboard;

class Metric extends BaseComponent
{
  /**
   * Get the view / contents that represent the component.
   */
  public function render()
  {
    return view('dashboard-components.metric', [
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
    if (!empty($this->settings['trend']['showSparkline'])) {
      return [
        'https://cdn.jsdelivr.net/npm/apexcharts'
      ];
    }

    return [];
  }
}
