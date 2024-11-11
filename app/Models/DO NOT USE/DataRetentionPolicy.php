<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataRetentionPolicy extends Model
{
  protected $fillable = [
    'data_type',
    'retention_period_days',
    'action_after_expiry',
    'is_active',
    'description'
  ];

  protected $casts = [
    'is_active' => 'boolean'
  ];

  public function getExpiryDate()
  {
    return now()->addDays($this->retention_period_days);
  }

  public function shouldBeRetained($createdAt)
  {
    return $createdAt->addDays($this->retention_period_days)->isFuture();
  }

  public static function getActivePolicy($dataType)
  {
    return static::where('data_type', $dataType)
      ->where('is_active', true)
      ->first();
  }
}
