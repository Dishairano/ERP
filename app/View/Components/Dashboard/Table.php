<?php

namespace App\View\Components\Dashboard;

class Table extends BaseComponent
{
  /**
   * Get the view / contents that represent the component.
   */
  public function render()
  {
    return view('dashboard-components.table', [
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
    return [
      'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js',
      'https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js'
    ];
  }

  /**
   * Get component-specific styles
   */
  public function getStyles(): array
  {
    return [
      'https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css'
    ];
  }
}
