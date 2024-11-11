<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditExport extends Model
{
  protected $fillable = [
    'user_id',
    'file_name',
    'file_path',
    'format',
    'filters',
    'downloaded_at'
  ];

  protected $casts = [
    'filters' => 'array',
    'downloaded_at' => 'datetime'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function markAsDownloaded()
  {
    $this->update(['downloaded_at' => now()]);
  }

  public function scopeByFormat($query, $format)
  {
    return $query->where('format', $format);
  }

  public function scopeNotDownloaded($query)
  {
    return $query->whereNull('downloaded_at');
  }

  public function scopeByUser($query, $userId)
  {
    return $query->where('user_id', $userId);
  }
}
