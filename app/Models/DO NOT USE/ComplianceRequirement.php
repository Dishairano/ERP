<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplianceRequirement extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'description',
    'regulation_type',
    'status',
    'effective_date',
    'review_date',
    'requirements',
    'actions_needed',
    'is_mandatory',
    'risk_level',
    'department_scope'
  ];

  protected $casts = [
    'effective_date' => 'date',
    'review_date' => 'date',
    'is_mandatory' => 'boolean',
  ];

  public function audits()
  {
    return $this->hasMany(ComplianceAudit::class);
  }

  public function documents()
  {
    return $this->hasMany(ComplianceDocument::class);
  }

  public function notifications()
  {
    return $this->hasMany(ComplianceNotification::class);
  }
}
