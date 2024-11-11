<?php

return [
  /*
    |--------------------------------------------------------------------------
    | Project Management Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains various settings and configurations for the project
    | management system, including defaults, limits, and customization options.
    |
    */

  // Default hourly rate used for budget calculations
  'hourly_rate' => env('PROJECT_DEFAULT_HOURLY_RATE', 100),

  // Project Code Settings
  'code_prefix' => env('PROJECT_CODE_PREFIX', 'PRJ'),
  'code_year_format' => 'Y',
  'code_sequence_digits' => 4,

  // Task Management
  'tasks' => [
    'statuses' => [
      'todo' => [
        'name' => 'To Do',
        'color' => 'secondary',
        'icon' => 'ri-checkbox-blank-line'
      ],
      'in_progress' => [
        'name' => 'In Progress',
        'color' => 'primary',
        'icon' => 'ri-loader-4-line'
      ],
      'review' => [
        'name' => 'Review',
        'color' => 'info',
        'icon' => 'ri-eye-line'
      ],
      'completed' => [
        'name' => 'Completed',
        'color' => 'success',
        'icon' => 'ri-checkbox-circle-line'
      ]
    ],
    'priorities' => [
      'low' => [
        'name' => 'Low',
        'color' => 'success',
        'icon' => 'ri-arrow-down-line'
      ],
      'medium' => [
        'name' => 'Medium',
        'color' => 'warning',
        'icon' => 'ri-drag-move-line'
      ],
      'high' => [
        'name' => 'High',
        'color' => 'danger',
        'icon' => 'ri-arrow-up-line'
      ],
      'critical' => [
        'name' => 'Critical',
        'color' => 'dark',
        'icon' => 'ri-alarm-warning-line'
      ]
    ],
    'dependency_types' => [
      'finish_to_start' => [
        'name' => 'Finish to Start',
        'description' => 'Task cannot start until dependent task is finished'
      ],
      'start_to_start' => [
        'name' => 'Start to Start',
        'description' => 'Task cannot start until dependent task starts'
      ],
      'finish_to_finish' => [
        'name' => 'Finish to Finish',
        'description' => 'Task cannot finish until dependent task finishes'
      ],
      'start_to_finish' => [
        'name' => 'Start to Finish',
        'description' => 'Task cannot finish until dependent task starts'
      ]
    ]
  ],

  // Risk Management
  'risks' => [
    'probability_levels' => [
      'low' => [
        'name' => 'Low',
        'color' => 'success',
        'score' => 1
      ],
      'medium' => [
        'name' => 'Medium',
        'color' => 'warning',
        'score' => 2
      ],
      'high' => [
        'name' => 'High',
        'color' => 'danger',
        'score' => 3
      ]
    ],
    'impact_levels' => [
      'low' => [
        'name' => 'Low',
        'color' => 'success',
        'score' => 1
      ],
      'medium' => [
        'name' => 'Medium',
        'color' => 'warning',
        'score' => 2
      ],
      'high' => [
        'name' => 'High',
        'color' => 'danger',
        'score' => 3
      ]
    ],
    'statuses' => [
      'identified' => [
        'name' => 'Identified',
        'color' => 'secondary',
        'icon' => 'ri-error-warning-line'
      ],
      'assessed' => [
        'name' => 'Assessed',
        'color' => 'info',
        'icon' => 'ri-scales-line'
      ],
      'mitigated' => [
        'name' => 'Mitigated',
        'color' => 'success',
        'icon' => 'ri-shield-check-line'
      ],
      'closed' => [
        'name' => 'Closed',
        'color' => 'dark',
        'icon' => 'ri-lock-line'
      ]
    ],
    'critical_score_threshold' => 6
  ],

  // Project Settings
  'statuses' => [
    'draft' => [
      'name' => 'Draft',
      'color' => 'secondary',
      'icon' => 'ri-draft-line'
    ],
    'active' => [
      'name' => 'Active',
      'color' => 'success',
      'icon' => 'ri-play-circle-line'
    ],
    'on_hold' => [
      'name' => 'On Hold',
      'color' => 'warning',
      'icon' => 'ri-pause-circle-line'
    ],
    'completed' => [
      'name' => 'Completed',
      'color' => 'info',
      'icon' => 'ri-checkbox-circle-line'
    ],
    'cancelled' => [
      'name' => 'Cancelled',
      'color' => 'danger',
      'icon' => 'ri-close-circle-line'
    ]
  ],

  // Notification Settings
  'notifications' => [
    'channels' => ['mail', 'database'],
    'events' => [
      'task_assigned' => true,
      'task_completed' => true,
      'task_overdue' => true,
      'risk_identified' => true,
      'risk_critical' => true,
      'project_milestone' => true,
      'budget_threshold' => true
    ]
  ],

  // File Upload Settings
  'uploads' => [
    'max_size' => 10240, // 10MB
    'allowed_types' => [
      'pdf',
      'doc',
      'docx',
      'xls',
      'xlsx',
      'ppt',
      'pptx',
      'txt',
      'csv',
      'jpg',
      'jpeg',
      'png',
      'gif'
    ],
    'path' => 'project-documents'
  ],

  // Dashboard Settings
  'dashboard' => [
    'default_widgets' => [
      'project_progress',
      'task_status',
      'risk_matrix',
      'budget_utilization',
      'upcoming_deadlines',
      'team_workload'
    ],
    'refresh_interval' => 300 // 5 minutes
  ],

  // Audit Settings
  'audit' => [
    'enabled' => true,
    'events' => [
      'project.created',
      'project.updated',
      'project.deleted',
      'task.status_changed',
      'risk.identified',
      'risk.mitigated'
    ]
  ]
];
