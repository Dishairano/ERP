<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiThreshold extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'kpi_definition_id',
    'warning_threshold',
    'critical_threshold',
    'comparison_operator',
    'is_active'
  ];

  protected $casts = [
    'warning_threshold' => 'decimal:4',
    'critical_threshold' => 'decimal:4',
    'is_active' => 'boolean'
  ];

  public function definition()
  {
    return $this->belongsTo(KpiDefinition::class, 'kpi_definition_id');
  }

  public function notifications()
  {
    return $this->hasMany(KpiNotification::class);
  }

  public function checkValue($value)
  {
    if (!$this->is_active) {
      return null;
    }

    $operator = $this->comparison_operator;
    $warning = $this->warning_threshold;
    $critical = $this->critical_threshold;

    switch ($operator) {
      case 'greater_than':
        if ($value > $critical) return 'critical';
        if ($value > $warning) return 'warning';
        break;
      case 'less_than':
        if ($value < $critical) return 'critical';
        if ($value < $warning) return 'warning';
        break;
    }

    return null;
  }
}
