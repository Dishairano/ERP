<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreProjectRiskModal extends Model
{
  use SoftDeletes;

  protected $table = 'project_risks';

  protected $fillable = [
    'project_id',
    'title',
    'description',
    'category',
    'severity',
    'likelihood',
    'impact',
    'mitigation_strategy',
    'status',
    'due_date',
    'owner'
  ];

  protected $casts = [
    'due_date' => 'datetime',
    'severity' => 'integer',
    'likelihood' => 'integer'
  ];

  // Relationships
  public function project()
  {
    return $this->belongsTo(CoreProjectModal::class, 'project_id');
  }

  // Accessors
  public function getRiskLevelAttribute()
  {
    return $this->severity * $this->likelihood;
  }

  public function getRiskLevelTextAttribute()
  {
    $level = $this->risk_level;
    if ($level >= 16) return 'Critical';
    if ($level >= 9) return 'High';
    if ($level >= 4) return 'Medium';
    return 'Low';
  }

  public function getRiskLevelColorAttribute()
  {
    $level = $this->risk_level;
    if ($level >= 16) return 'danger';
    if ($level >= 9) return 'warning';
    if ($level >= 4) return 'info';
    return 'success';
  }

  // Scopes
  public function scopeHighRisk($query)
  {
    return $query->whereRaw('severity * likelihood >= ?', [16]);
  }

  public function scopeMediumRisk($query)
  {
    return $query->whereRaw('severity * likelihood >= ? AND severity * likelihood < ?', [9, 16]);
  }

  public function scopeLowRisk($query)
  {
    return $query->whereRaw('severity * likelihood < ?', [9]);
  }
}
