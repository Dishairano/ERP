<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PushNotificationSetting extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'user_id',
    'device_token',
    'platform',
    'notification_preferences',
    'is_active'
  ];

  protected $casts = [
    'notification_preferences' => 'array',
    'is_active' => 'boolean'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeForPlatform($query, string $platform)
  {
    return $query->where('platform', $platform);
  }
}
