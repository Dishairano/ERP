<?php

namespace App\Services\ComponentTypes;

class ListComponentType extends AbstractComponentType
{
  /**
   * Process data for the component type
   */
  public function processData(array $data, array $settings): array
  {
    $items = $data['items'] ?? [];

    // Apply filtering
    $items = $this->applyFiltering($items, $settings);

    // Apply sorting
    $items = $this->applySorting($items, $settings);

    // Calculate pagination if enabled
    $pagination = null;
    if ($settings['paginated'] ?? false) {
      $pagination = $this->calculatePagination(count($items), $settings);
      $start = ($pagination['currentPage'] - 1) * $pagination['perPage'];
      $items = array_slice($items, $start, $pagination['perPage']);
    }

    return [
      'items' => $items,
      'pagination' => $pagination,
      'template' => $settings['template'] ?? null
    ];
  }

  /**
   * Get default settings for the component type
   */
  public function getDefaultSettings(): array
  {
    return [
      'paginated' => false,
      'perPage' => 10,
      'currentPage' => 1,
      'sortable' => true,
      'filterable' => true,
      'template' => null,
      'itemSettings' => [
        'clickable' => false,
        'hoverable' => true,
        'selectable' => false
      ],
      'style' => [
        'dense' => false,
        'divider' => true,
        'rounded' => false
      ]
    ];
  }
}
