<?php

namespace App\Services\ComponentTypes;

use App\Models\DashboardComponent;

abstract class AbstractComponentType implements ComponentTypeInterface
{
  /**
   * Process data for the component type
   */
  abstract public function processData(array $data, array $settings): array;

  /**
   * Get default settings for the component type
   */
  abstract public function getDefaultSettings(): array;

  /**
   * Handle component setup
   */
  public function handleSetup(DashboardComponent $component): void
  {
    // Default implementation - can be overridden by child classes
    $component->settings = array_merge(
      $this->getDefaultSettings(),
      $component->settings ?? []
    );
    $component->save();
  }

  /**
   * Handle component update
   */
  public function handleUpdate(DashboardComponent $component): void
  {
    // Default implementation - can be overridden by child classes
    $component->settings = array_merge(
      $this->getDefaultSettings(),
      $component->settings ?? []
    );
    $component->save();
  }

  /**
   * Generate a color palette
   */
  protected function generateColors(int $count): array
  {
    $colors = [
      '#FF6384',
      '#36A2EB',
      '#FFCE56',
      '#4BC0C0',
      '#9966FF',
      '#FF9F40',
      '#FF6384',
      '#C9CBCF',
      '#4BC0C0',
      '#FF9F40'
    ];

    return array_slice($colors, 0, $count);
  }

  /**
   * Format a value according to settings
   */
  protected function formatValue($value, array $format): string
  {
    $type = $format['type'] ?? 'number';
    $prefix = $format['prefix'] ?? '';
    $suffix = $format['suffix'] ?? '';

    $formatted = match ($type) {
      'currency' => number_format($value, 2),
      'percentage' => number_format($value, 1) . '%',
      'number' => number_format($value, $format['precision'] ?? 0),
      default => $value
    };

    return $prefix . $formatted . $suffix;
  }

  /**
   * Apply a template to data
   */
  protected function applyTemplate(string $template, array $data): string
  {
    return preg_replace_callback('/\{([^}]+)\}/', function ($matches) use ($data) {
      return $data[$matches[1]] ?? '';
    }, $template);
  }

  /**
   * Apply sorting to data
   */
  protected function applySorting(array $rows, array $settings): array
  {
    if (empty($settings['column'])) {
      return $rows;
    }

    $column = $settings['column'];
    $direction = $settings['direction'] ?? 'asc';

    return collect($rows)
      ->sortBy($column, SORT_REGULAR, $direction === 'desc')
      ->values()
      ->toArray();
  }

  /**
   * Apply filtering to data
   */
  protected function applyFiltering(array $rows, array $settings): array
  {
    if (empty($settings['filters'])) {
      return $rows;
    }

    return collect($rows)->filter(function ($row) use ($settings) {
      foreach ($settings['filters'] as $column => $value) {
        if (!$this->matchesFilter($row[$column] ?? null, $value)) {
          return false;
        }
      }
      return true;
    })->values()->toArray();
  }

  /**
   * Check if a value matches a filter
   */
  protected function matchesFilter($value, $filter): bool
  {
    if (is_array($filter)) {
      return match ($filter['operator']) {
        'equals' => $value == $filter['value'],
        'contains' => str_contains(strtolower($value), strtolower($filter['value'])),
        'greater' => $value > $filter['value'],
        'less' => $value < $filter['value'],
        'between' => $value >= $filter['min'] && $value <= $filter['max'],
        default => true
      };
    }

    return str_contains(strtolower($value), strtolower($filter));
  }

  /**
   * Calculate comparison between current and previous values
   */
  protected function calculateComparison(array $data, array $settings): ?array
  {
    if (empty($settings['enabled'])) {
      return null;
    }

    $current = $data['value'] ?? 0;
    $previous = $data['previous'] ?? 0;
    $difference = $current - $previous;
    $percentage = $previous != 0 ? ($difference / $previous) * 100 : 0;

    return [
      'value' => $difference,
      'percentage' => $percentage,
      'direction' => $difference >= 0 ? 'up' : 'down'
    ];
  }

  /**
   * Calculate trend from historical data
   */
  protected function calculateTrend(array $history): array
  {
    $values = array_column($history, 'value');
    $count = count($values);

    if ($count < 2) {
      return ['direction' => 'neutral', 'strength' => 0];
    }

    $direction = $values[$count - 1] >= $values[$count - 2] ? 'up' : 'down';
    $strength = abs($values[$count - 1] - $values[$count - 2]) / $values[$count - 2];

    return [
      'direction' => $direction,
      'strength' => $strength
    ];
  }

  /**
   * Calculate pagination information
   */
  protected function calculatePagination(int $totalItems, array $settings): array
  {
    $perPage = $settings['perPage'] ?? config('dashboard.components.table.default_page_size', 10);
    $currentPage = $settings['currentPage'] ?? 1;
    $totalPages = ceil($totalItems / $perPage);

    return [
      'totalItems' => $totalItems,
      'perPage' => $perPage,
      'currentPage' => $currentPage,
      'totalPages' => $totalPages,
      'hasNextPage' => $currentPage < $totalPages,
      'hasPreviousPage' => $currentPage > 1,
      'firstItem' => ($currentPage - 1) * $perPage + 1,
      'lastItem' => min($currentPage * $perPage, $totalItems),
      'pageRange' => $this->calculatePageRange($currentPage, $totalPages),
      'pageInfo' => sprintf(
        'Showing %d to %d of %d entries',
        ($currentPage - 1) * $perPage + 1,
        min($currentPage * $perPage, $totalItems),
        $totalItems
      )
    ];
  }

  /**
   * Calculate page range for pagination
   */
  protected function calculatePageRange(int $currentPage, int $totalPages, int $range = 5): array
  {
    $start = max(1, min($currentPage - floor($range / 2), $totalPages - $range + 1));
    $end = min($totalPages, $start + $range - 1);

    return range($start, $end);
  }
}
