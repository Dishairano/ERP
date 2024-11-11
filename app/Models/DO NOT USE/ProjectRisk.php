<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectRisk extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'description',
    'category',
    'probability',
    'impact',
    'owner_id',
    'status',
    'mitigation_strategy',
    'contingency_plan',
    'trigger_events'
  ];

  protected $casts = [
    'probability' => 'integer',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function owner()
  {
    return $this->belongsTo(User::class, 'owner_id');
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function getSeverityAttribute()
  {
    $score = $this->probability * $this->getImpactScore();

    if ($score <= 20) return 'low';
    if ($score <= 50) return 'medium';
    return 'high';
  }

  protected function getImpactScore()
  {
    switch ($this->impact) {
      case 'low':
        return 1;
      case 'medium':
        return 2;
      case 'high':
        return 3;
      default:
        return 1;
    }
  }
}
