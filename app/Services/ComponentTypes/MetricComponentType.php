<?php

namespace App\Services\ComponentTypes;

class MetricComponentType extends AbstractComponentType
{
  /**
   * Process data for the component type
   */
  public function processData(array $data, array $settings): array
  {
    $value = $data['value'] ?? 0;
    $format = $settings['format'] ?? [];

    // Calculate comparison if enabled
    $comparison = $this->calculateComparison($data, $settings);

    // Calculate trend if historical data is provided
    $trend = !empty($data['history']) ? $this->calculateTrend($data['history']) : null;

    return [
      'value' => $this->formatValue($value, $format),
      'label' => $data['label'] ?? '',
      'comparison' => $comparison,
      'trend' => $trend,
      'icon' => $settings['icon'] ?? null,
      'color' => $settings['color'] ?? 'primary'
    ];
  }

  /**
   * Get default settings for the component type
   */
  public function getDefaultSettings(): array
  {
    return [
      'format' => [
        'type' => 'number',
        'precision' => 0,
        'prefix' => '',
        'suffix' => ''
      ],
      'comparison' => [
        'enabled' => true,
        'showPercentage' => true
      ],
      'showIcon' => true,
      'showTrend' => true,
      'color' => 'primary',
      'size' => 'medium'
    ];
  }
}
