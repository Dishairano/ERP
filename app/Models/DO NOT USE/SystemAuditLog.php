<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemAuditLog extends Model
{
  protected $fillable = [
    'audit_log_id',
    'component',
    'action_type',
    'technical_details',
    'requires_attention'
  ];

  protected $casts = [
    'requires_attention' => 'boolean',
    'technical_details' => 'array'
  ];

  public function auditLog(): BelongsTo
  {
    return $this->belongsTo(AuditLog::class);
  }

  public function scopeByComponent($query, $component)
  {
    return $query->where('component', $component);
  }

  public function scopeByActionType($query, $actionType)
  {
    return $query->where('action_type', $actionType);
  }

  public function scopeRequiringAttention($query)
  {
    return $query->where('requires_attention', true);
  }
}
