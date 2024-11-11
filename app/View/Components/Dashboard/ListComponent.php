<?php

namespace App\View\Components\Dashboard;

class ListComponent extends BaseComponent
{
  /**
   * Get the view / contents that represent the component.
   */
  public function render()
  {
    return view('dashboard-components.list', [
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
    $scripts = [];

    if ($this->settings['display']['dragAndDrop'] ?? false) {
      $scripts[] = 'https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js';
    }

    if ($this->settings['display']['animation'] ?? false) {
      $scripts[] = 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js';
    }

    return $scripts;
  }

  /**
   * Get component-specific styles
   */
  public function getStyles(): array
  {
    $styles = [];

    if ($this->settings['display']['animation'] ?? false) {
      $styles[] = 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css';
    }

    return $styles;
  }
}
