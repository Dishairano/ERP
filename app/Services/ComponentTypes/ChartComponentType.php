<?php

namespace App\Services\ComponentTypes;

class ChartComponentType extends AbstractComponentType
{
  /**
   * Process data for the component type
   */
  public function processData(array $data, array $settings): array
  {
    $chartData = [
      'labels' => $data['labels'] ?? [],
      'datasets' => []
    ];

    foreach ($data['datasets'] ?? [] as $index => $dataset) {
      $colors = $this->generateColors(count($dataset['data'] ?? []));

      $chartData['datasets'][] = [
        'label' => $dataset['label'] ?? "Dataset {$index}",
        'data' => $dataset['data'] ?? [],
        'backgroundColor' => $colors,
        'borderColor' => $colors,
        'borderWidth' => 1
      ];
    }

    return [
      'type' => $settings['chartType'] ?? 'bar',
      'data' => $chartData,
      'options' => $this->getChartOptions($settings)
    ];
  }

  /**
   * Get default settings for the component type
   */
  public function getDefaultSettings(): array
  {
    return [
      'chartType' => 'bar',
      'showLegend' => true,
      'showGrid' => true,
      'aspectRatio' => 2,
      'animation' => true,
      'responsive' => true,
      'maintainAspectRatio' => true,
      'title' => [
        'display' => false,
        'text' => ''
      ],
      'scales' => [
        'y' => [
          'beginAtZero' => true
        ]
      ]
    ];
  }

  /**
   * Get chart options based on settings
   */
  private function getChartOptions(array $settings): array
  {
    return [
      'responsive' => $settings['responsive'] ?? true,
      'maintainAspectRatio' => $settings['maintainAspectRatio'] ?? true,
      'aspectRatio' => $settings['aspectRatio'] ?? 2,
      'animation' => $settings['animation'] ?? true,
      'plugins' => [
        'legend' => [
          'display' => $settings['showLegend'] ?? true
        ],
        'title' => [
          'display' => !empty($settings['title']['text']),
          'text' => $settings['title']['text'] ?? ''
        ]
      ],
      'scales' => [
        'y' => [
          'beginAtZero' => $settings['scales']['y']['beginAtZero'] ?? true,
          'grid' => [
            'display' => $settings['showGrid'] ?? true
          ]
        ],
        'x' => [
          'grid' => [
            'display' => $settings['showGrid'] ?? true
          ]
        ]
      ]
    ];
  }
}
