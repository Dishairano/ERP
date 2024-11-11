<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectKpi extends Model
{
  use HasFactory;

  protected $table = 'project_kpis';

  protected $fillable = [
    'project_id',
    'name',
    'description',
    'target_value',
    'actual_value',
    'unit',
    'status',
    'measurement_frequency',
    'last_measured_at'
  ];

  protected $casts = [
    'target_value' => 'float',
    'actual_value' => 'float',
    'last_measured_at' => 'datetime'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }
}
