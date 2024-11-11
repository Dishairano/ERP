<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplianceAudit extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'audit_type',
    'status',
    'scheduled_date',
    'completion_date',
    'findings',
    'recommendations',
    'auditor_name',
    'department',
    'scope',
    'action_items',
    'follow_up_date'
  ];

  protected $casts = [
    'scheduled_date' => 'date',
    'completion_date' => 'date',
    'follow_up_date' => 'date',
  ];

  public function requirement()
  {
    return $this->belongsTo(ComplianceRequirement::class);
  }

  public function documents()
  {
    return $this->hasMany(ComplianceDocument::class);
  }
}
