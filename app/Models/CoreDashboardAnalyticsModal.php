<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoreDashboardAnalyticsModal extends Model
{
  use HasFactory;

  protected $table = 'dashboard_analytics';

  protected $fillable = [
    'metric_name',
    'metric_value',
    'metric_type',
    'time_period',
    'comparison_value',
    'percentage_change',
    'status',
    'chart_data',
    'last_updated_at'
  ];

  protected $casts = [
    'chart_data' => 'array',
    'last_updated_at' => 'datetime',
    'metric_value' => 'float',
    'comparison_value' => 'float',
    'percentage_change' => 'float'
  ];
}
