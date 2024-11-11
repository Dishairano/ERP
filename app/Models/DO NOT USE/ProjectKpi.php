<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectKpi extends Model
{
  use HasFactory;

  protected $fillable = [
    'project_id',
    'name',
    'metric_type',
    'target_value',
    'actual_value',
    'status',
    'measurement_date',
    'notes'
  ];

  protected $casts = [
    'target_value' => 'decimal:2',
    'actual_value' => 'decimal:2',
    'measurement_date' => 'date'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function getAchievementPercentage()
  {
    if ($this->target_value == 0) return 0;
    return ($this->actual_value / $this->target_value) * 100;
  }

  public function updateStatus()
  {
    $percentage = $this->getAchievementPercentage();
    if ($percentage >= 90) {
      $this->status = 'on_track';
    } elseif ($percentage >= 70) {
      $this->status = 'at_risk';
    } else {
      $this->status = 'off_track';
    }
    $this->save();
  }
}
