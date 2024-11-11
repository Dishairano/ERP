<?php

namespace App\Services\ComponentTypes;

class TableComponentType extends AbstractComponentType
{
  /**
   * Process data for the component type
   */
  public function processData(array $data, array $settings): array
  {
    $rows = $data['rows'] ?? [];

    // Apply filtering
    $rows = $this->applyFiltering($rows, $settings);

    // Apply sorting
    $rows = $this->applySorting($rows, $settings);

    // Calculate pagination
    $pagination = $this->calculatePagination(count($rows), $settings);

    // Slice rows for current page
    $start = ($pagination['currentPage'] - 1) * $pagination['perPage'];
    $rows = array_slice($rows, $start, $pagination['perPage']);

    return [
      'columns' => $data['columns'] ?? [],
      'rows' => $rows,
      'pagination' => $pagination
    ];
  }

  /**
   * Get default settings for the component type
   */
  public function getDefaultSettings(): array
  {
    return [
      'perPage' => 10,
      'currentPage' => 1,
      'sortable' => true,
      'filterable' => true,
      'showPagination' => true,
      'dense' => false,
      'striped' => true,
      'hoverable' => true,
      'bordered' => false,
      'columnSettings' => []
    ];
  }
}
