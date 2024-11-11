<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditSetting extends Model
{
  protected $fillable = [
    'key',
    'value',
    'description'
  ];

  protected $casts = [
    'value' => 'json'
  ];

  public static function getSetting($key, $default = null)
  {
    $setting = static::where('key', $key)->first();
    return $setting ? $setting->value : $default;
  }

  public static function setSetting($key, $value, $description = null)
  {
    return static::updateOrCreate(
      ['key' => $key],
      [
        'value' => $value,
        'description' => $description
      ]
    );
  }
}
