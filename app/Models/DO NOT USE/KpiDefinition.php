<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiDefinition extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'code',
    'description',
    'category',
    'unit',
    'calculation_method',
    'data_source',
    'frequency',
    'visualization_settings',
    'is_active',
    'created_by'
  ];

  protected $casts = [
    'visualization_settings' => 'array',
    'is_active' => 'boolean'
  ];

  public function values()
  {
    return $this->hasMany(KpiValue::class);
  }

  public function thresholds()
  {
    return $this->hasMany(KpiThreshold::class);
  }

  public function notifications()
  {
    return $this->hasMany(KpiNotification::class);
  }

  public function targets()
  {
    return $this->hasMany(KpiTarget::class);
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function getLatestValue()
  {
    return $this->values()->latest('measurement_date')->first();
  }

  public function getCurrentTarget()
  {
    return $this->targets()
      ->where('start_date', '<=', now())
      ->where('end_date', '>=', now())
      ->where('is_active', true)
      ->first();
  }
}
