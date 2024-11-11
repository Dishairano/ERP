<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskAssessmentUpdate extends Model
{
  use HasFactory;

  protected $fillable = [
    'risk_assessment_id',
    'status',
    'update_description',
    'changes',
    'updated_by'
  ];

  protected $casts = [
    'changes' => 'array'
  ];

  public function riskAssessment()
  {
    return $this->belongsTo(RiskAssessment::class);
  }

  public function updater()
  {
    return $this->belongsTo(User::class, 'updated_by');
  }
}
