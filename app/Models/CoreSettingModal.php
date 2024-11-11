<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class CoreSettingModal extends Model
{
  use SoftDeletes;

  protected $table = 'settings';

  protected $fillable = [
    'key',
    'value',
    'group',
    'type',
    'description',
    'is_public',
    'is_system',
    'created_by',
    'updated_by'
  ];

  protected $casts = [
    'value' => 'json',
    'is_public' => 'boolean',
    'is_system' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime'
  ];

  public static function getSetting($key, $default = null)
  {
    $setting = self::where('key', $key)->first();
    return $setting ? $setting->value : $default;
  }

  public static function setSetting($key, $value, $group = 'general')
  {
    return self::updateOrCreate(
      ['key' => $key],
      [
        'value' => $value,
        'group' => $group,
        'updated_by' => optional(Auth::user())->id
      ]
    );
  }

  public static function getSettingsByGroup($group)
  {
    return self::where('group', $group)->get()->pluck('value', 'key');
  }

  public static function getPublicSettings()
  {
    return self::where('is_public', true)->get()->pluck('value', 'key');
  }

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->created_by = optional(Auth::user())->id;
      $model->updated_by = optional(Auth::user())->id;
    });

    static::updating(function ($model) {
      $model->updated_by = optional(Auth::user())->id;
    });
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function updater()
  {
    return $this->belongsTo(User::class, 'updated_by');
  }
}
