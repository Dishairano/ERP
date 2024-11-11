<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditNotification extends Model
{
  protected $fillable = [
    'user_id',
    'title',
    'message',
    'type',
    'read_at'
  ];

  protected $casts = [
    'read_at' => 'datetime'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function markAsRead()
  {
    $this->update(['read_at' => now()]);
  }

  public function scopeUnread($query)
  {
    return $query->whereNull('read_at');
  }

  public function scopeByType($query, $type)
  {
    return $query->where('type', $type);
  }

  public function scopeForUser($query, $userId)
  {
    return $query->where('user_id', $userId);
  }
}
