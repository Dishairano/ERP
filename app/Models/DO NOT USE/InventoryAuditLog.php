<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAuditLog extends Model
{
  protected $fillable = [
    'audit_log_id',
    'item_code',
    'movement_type',
    'quantity',
    'location',
    'reason'
  ];

  public function auditLog(): BelongsTo
  {
    return $this->belongsTo(AuditLog::class);
  }

  public function scopeByItemCode($query, $itemCode)
  {
    return $query->where('item_code', $itemCode);
  }

  public function scopeByMovementType($query, $type)
  {
    return $query->where('movement_type', $type);
  }

  public function scopeByLocation($query, $location)
  {
    return $query->where('location', $location);
  }

  public function scopeByQuantityRange($query, $min, $max)
  {
    return $query->whereBetween('quantity', [$min, $max]);
  }
}
