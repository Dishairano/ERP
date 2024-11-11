<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiskAssessment extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'project_id',
    'risk_id',
    'probability',
    'impact',
    'risk_score',
    'risk_level',
    'mitigation_strategies',
    'contingency_plans',
    'estimated_cost',
    'assessment_date',
    'next_review_date',
    'assessed_by'
  ];

  protected $casts = [
    'mitigation_strategies' => 'array',
    'contingency_plans' => 'array',
    'estimated_cost' => 'decimal:2',
    'assessment_date' => 'date',
    'next_review_date' => 'date'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function risk()
  {
    return $this->belongsTo(ProjectRisk::class, 'risk_id');
  }

  public function assessor()
  {
    return $this->belongsTo(User::class, 'assessed_by');
  }

  public function updates()
  {
    return $this->hasMany(RiskAssessmentUpdate::class);
  }

  public function calculateRiskScore()
  {
    $this->risk_score = $this->probability * $this->impact;
    $this->risk_level = $this->determineRiskLevel($this->risk_score);
    return $this->risk_score;
  }

  protected function determineRiskLevel($score)
  {
    if ($score <= 4) return 'Low';
    if ($score <= 8) return 'Medium';
    if ($score <= 15) return 'High';
    return 'Critical';
  }
}
