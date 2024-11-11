<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpBlacklist extends Model
{
  protected $fillable = [
    'ip_address',
    'reason',
    'blocked_until',
    'blocked_by'
  ];

  protected $casts = [
    'blocked_until' => 'datetime'
  ];

  public function blockedByUser()
  {
    return $this->belongsTo(User::class, 'blocked_by');
  }

  public function isBlocked()
  {
    return !$this->blocked_until || $this->blocked_until->isFuture();
  }

  public static function isIpBlocked($ip)
  {
    return static::where('ip_address', $ip)
      ->where(function ($query) {
        $query->whereNull('blocked_until')
          ->orWhere('blocked_until', '>', now());
      })
      ->exists();
  }
}
