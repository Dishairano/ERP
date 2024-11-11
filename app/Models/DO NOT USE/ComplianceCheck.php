<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComplianceCheck extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'requirement_id',
    'user_id',
    'status',
    'findings',
    'recommendations',
    'evidence',
    'scheduled_at',
    'completed_at'
  ];

  protected $casts = [
    'evidence' => 'json',
    'scheduled_at' => 'datetime',
    'completed_at' => 'datetime'
  ];

  public function requirement()
  {
    return $this->belongsTo(ComplianceRequirement::class, 'requirement_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function isPending()
  {
    return $this->status === 'In Progress';
  }

  public function isCompleted()
  {
    return !is_null($this->completed_at);
  }

  public function hasPassed()
  {
    return $this->status === 'Passed';
  }
}
