<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityPolicy extends Model
{
  protected $fillable = [
    'name',
    'type',
    'settings',
    'is_active',
    'description',
    'created_by'
  ];

  protected $casts = [
    'settings' => 'json',
    'is_active' => 'boolean'
  ];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function getSetting($key, $default = null)
  {
    return data_get($this->settings, $key, $default);
  }

  public static function getActive($type)
  {
    return static::where('type', $type)
      ->where('is_active', true)
      ->first();
  }
}
