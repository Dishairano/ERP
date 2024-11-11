<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiValue extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'kpi_definition_id',
    'value',
    'measurement_date',
    'dimension_type',
    'dimension_id',
    'additional_data'
  ];

  protected $casts = [
    'value' => 'decimal:4',
    'measurement_date' => 'datetime',
    'additional_data' => 'array'
  ];

  public function definition()
  {
    return $this->belongsTo(KpiDefinition::class, 'kpi_definition_id');
  }

  public function notifications()
  {
    return $this->hasMany(KpiNotification::class);
  }

  public function dimension()
  {
    return $this->morphTo();
  }
}
