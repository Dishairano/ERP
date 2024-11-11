<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComplianceRisk extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'description',
    'category',
    'severity',
    'likelihood',
    'affected_areas',
    'mitigation_steps',
    'status'
  ];

  protected $casts = [
    'affected_areas' => 'json',
    'mitigation_steps' => 'json'
  ];

  public function getRiskLevel()
  {
    $severityMap = ['Low' => 1, 'Medium' => 2, 'High' => 3];
    $likelihoodMap = ['Low' => 1, 'Medium' => 2, 'High' => 3];

    $severityScore = $severityMap[$this->severity] ?? 1;
    $likelihoodScore = $likelihoodMap[$this->likelihood] ?? 1;

    $riskScore = $severityScore * $likelihoodScore;

    if ($riskScore <= 2) return 'Low';
    if ($riskScore <= 6) return 'Medium';
    return 'High';
  }

  public function needsAttention()
  {
    return $this->getRiskLevel() === 'High' && $this->status === 'Identified';
  }

  public function isMitigated()
  {
    return $this->status === 'Mitigated';
  }
}
