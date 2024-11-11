<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentAuditLog extends Model
{
  protected $fillable = [
    'audit_log_id',
    'document_type',
    'document_name',
    'action',
    'version_info'
  ];

  protected $casts = [
    'version_info' => 'array'
  ];

  public function auditLog(): BelongsTo
  {
    return $this->belongsTo(AuditLog::class);
  }

  public function scopeByDocumentType($query, $type)
  {
    return $query->where('document_type', $type);
  }

  public function scopeByAction($query, $action)
  {
    return $query->where('action', $action);
  }

  public function scopeByDocumentName($query, $name)
  {
    return $query->where('document_name', 'like', "%{$name}%");
  }
}
