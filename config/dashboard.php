<?php

return [
  /*
    |--------------------------------------------------------------------------
    | Dashboard Components Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the dashboard system.
    |
    */

  // Default cache settings
  'cache' => [
    'enabled' => true,
    'prefix' => 'dashboard_',
    'default_duration' => 300, // 5 minutes
  ],

  // Default refresh intervals (in seconds)
  'refresh_intervals' => [
    'realtime' => 5,
    'fast' => 30,
    'normal' => 300,
    'slow' => 900,
    'manual' => null,
  ],

  // Component size presets
  'sizes' => [
    'small' => [
      'width' => 1,
      'height' => 1,
      'class' => 'col-md-3'
    ],
    'medium' => [
      'width' => 2,
      'height' => 1,
      'class' => 'col-md-6'
    ],
    'large' => [
      'width' => 3,
      'height' => 2,
      'class' => 'col-md-9'
    ],
    'full' => [
      'width' => 4,
      'height' => 2,
      'class' => 'col-md-12'
    ],
  ],

  // Available chart libraries
  'chart_libraries' => [
    'chartjs' => [
      'version' => '3.7.0',
      'cdn' => 'https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js',
    ],
    'echarts' => [
      'version' => '5.2.2',
      'cdn' => 'https://cdn.jsdelivr.net/npm/echarts@5.2.2/dist/echarts.min.js',
    ],
  ],

  // Default color schemes
  'color_schemes' => [
    'default' => [
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
    ],
    'material' => [
      '#f44336',
      '#2196f3',
      '#4caf50',
      '#ffeb3b',
      '#9c27b0',
      '#ff9800',
      '#795548',
      '#607d8b',
      '#3f51b5',
      '#009688'
    ],
    'pastel' => [
      '#FFB3BA',
      '#BAFFC9',
      '#BAE1FF',
      '#FFFFBA',
      '#FFB3FF',
      '#B3FFB3',
      '#B3B3FF',
      '#FFC9BA',
      '#BAF7FF',
      '#FFE4BA'
    ],
  ],

  // Component type specific settings
  'components' => [
    'chart' => [
      'default_library' => 'chartjs',
      'default_type' => 'line',
      'animations_enabled' => true,
      'responsive' => true,
    ],
    'table' => [
      'default_page_size' => 10,
      'page_size_options' => [5, 10, 25, 50, 100],
      'default_sort_direction' => 'asc',
      'enable_column_search' => true,
    ],
    'metric' => [
      'default_precision' => 2,
      'enable_sparkline' => true,
      'enable_comparison' => true,
    ],
    'calendar' => [
      'first_day_of_week' => 1, // Monday
      'default_view' => 'month',
      'time_format' => '24h',
    ],
    'map' => [
      'default_provider' => 'leaflet',
      'default_zoom' => 8,
      'enable_clustering' => true,
    ],
  ],

  // Data source settings
  'data_sources' => [
    'cache_enabled' => true,
    'default_timeout' => 30, // seconds
    'max_retries' => 3,
    'retry_delay' => 5, // seconds
  ],

  // Export settings
  'exports' => [
    'enabled' => true,
    'formats' => ['csv', 'excel', 'pdf'],
    'chunk_size' => 1000,
    'max_rows' => 50000,
  ],

  // Dashboard layout settings
  'layout' => [
    'grid_columns' => 12,
    'row_height' => 100, // pixels
    'gutter' => 10, // pixels
    'responsive_breakpoints' => [
      'xs' => 0,
      'sm' => 576,
      'md' => 768,
      'lg' => 992,
      'xl' => 1200,
      'xxl' => 1400,
    ],
  ],

  // Feature flags
  'features' => [
    'enable_sharing' => true,
    'enable_templates' => true,
    'enable_export' => true,
    'enable_fullscreen' => true,
    'enable_dark_mode' => true,
    'enable_notifications' => true,
  ],

  // Permissions
  'permissions' => [
    'create_dashboard' => ['admin', 'manager'],
    'edit_dashboard' => ['admin', 'manager', 'editor'],
    'delete_dashboard' => ['admin'],
    'share_dashboard' => ['admin', 'manager'],
    'export_dashboard' => ['admin', 'manager', 'editor', 'viewer'],
  ],
];
