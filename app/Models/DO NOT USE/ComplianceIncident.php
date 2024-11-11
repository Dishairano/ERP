<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComplianceIncident extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'description',
    'reported_by',
    'occurred_at',
    'severity',
    'status',
    'resolution',
    'affected_systems',
    'evidence'
  ];

  protected $casts = [
    'occurred_at' => 'datetime',
    'affected_systems' => 'json',
    'evidence' => 'json'
  ];

  public function reporter()
  {
    return $this->belongsTo(User::class, 'reported_by');
  }

  public function isResolved()
  {
    return in_array($this->status, ['Resolved', 'Closed']);
  }

  public function isCritical()
  {
    return $this->severity === 'Critical';
  }

  public function needsImmediateAttention()
  {
    return $this->isCritical() && !$this->isResolved();
  }

  public function isOpen()
  {
    return in_array($this->status, ['Open', 'In Progress']);
  }

  public function timeToResolution()
  {
    if (!$this->isResolved()) {
      return null;
    }

    return $this->updated_at->diffInHours($this->occurred_at);
  }
}
