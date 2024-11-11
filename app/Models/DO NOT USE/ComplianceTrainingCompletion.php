<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComplianceTrainingCompletion extends Model
{
  use HasFactory;

  protected $fillable = [
    'training_id',
    'user_id',
    'completed_at',
    'valid_until',
    'status',
    'score',
    'certificate_details'
  ];

  protected $casts = [
    'completed_at' => 'datetime',
    'valid_until' => 'date',
    'certificate_details' => 'json'
  ];

  public function training()
  {
    return $this->belongsTo(ComplianceTraining::class, 'training_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function isExpired()
  {
    return now()->greaterThan($this->valid_until);
  }

  public function needsRenewal()
  {
    return now()->addDays(30)->greaterThanOrEqual($this->valid_until);
  }

  public function hasPassed()
  {
    return $this->score >= 70;
  }
}
