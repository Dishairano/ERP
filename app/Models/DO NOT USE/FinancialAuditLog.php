<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialAuditLog extends Model
{
  protected $fillable = [
    'audit_log_id',
    'transaction_type',
    'amount',
    'currency',
    'status',
    'reference_number'
  ];

  public function auditLog(): BelongsTo
  {
    return $this->belongsTo(AuditLog::class);
  }

  public function scopeByTransactionType($query, $type)
  {
    return $query->where('transaction_type', $type);
  }

  public function scopeByStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  public function scopeByAmountRange($query, $min, $max)
  {
    return $query->whereBetween('amount', [$min, $max]);
  }

  public function scopeByCurrency($query, $currency)
  {
    return $query->where('currency', $currency);
  }
}
