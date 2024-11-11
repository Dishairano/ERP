<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectPerformanceMetric extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'project_id',
    'metric_type',
    'planned_value',
    'actual_value',
    'earned_value',
    'variance',
    'performance_index',
    'additional_data',
    'measurement_date',
    'notes',
    'created_by'
  ];

  protected $casts = [
    'planned_value' => 'decimal:2',
    'actual_value' => 'decimal:2',
    'earned_value' => 'decimal:2',
    'variance' => 'decimal:2',
    'performance_index' => 'decimal:4',
    'additional_data' => 'array',
    'measurement_date' => 'date'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function calculateVariance()
  {
    if ($this->planned_value && $this->actual_value) {
      $this->variance = $this->planned_value - $this->actual_value;
    }
  }

  public function calculatePerformanceIndex()
  {
    if ($this->planned_value && $this->actual_value && $this->actual_value != 0) {
      $this->performance_index = $this->earned_value / $this->actual_value;
    }
  }
}
