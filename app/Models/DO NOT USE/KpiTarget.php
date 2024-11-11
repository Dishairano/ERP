<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiTarget extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'kpi_definition_id',
    'target_value',
    'start_date',
    'end_date',
    'dimension_type',
    'dimension_id',
    'description',
    'is_active'
  ];

  protected $casts = [
    'target_value' => 'decimal:4',
    'start_date' => 'date',
    'end_date' => 'date',
    'is_active' => 'boolean'
  ];

  public function definition()
  {
    return $this->belongsTo(KpiDefinition::class, 'kpi_definition_id');
  }

  public function dimension()
  {
    return $this->morphTo();
  }

  public function getProgress()
  {
    $latestValue = $this->definition->getLatestValue();
    if (!$latestValue) return 0;

    return ($latestValue->value / $this->target_value) * 100;
  }
}
