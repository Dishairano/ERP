<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
  protected $fillable = [
    'name',
    'code',
    'description',
    'type',
    'base_unit_id',
    'conversion_factor',
    'status'
  ];

  protected $casts = [
    'conversion_factor' => 'decimal:4',
    'status' => 'boolean'
  ];

  public function baseUnit()
  {
    return $this->belongsTo(Unit::class, 'base_unit_id');
  }

  public function derivedUnits()
  {
    return $this->hasMany(Unit::class, 'base_unit_id');
  }

  public function items()
  {
    return $this->hasMany(Item::class);
  }

  public function scopeActive($query)
  {
    return $query->where('status', true);
  }

  public function scopeBaseUnits($query)
  {
    return $query->whereNull('base_unit_id');
  }

  public function scopeByType($query, $type)
  {
    return $query->where('type', $type);
  }

  public function convertTo($value, Unit $targetUnit)
  {
    if ($this->id === $targetUnit->id) {
      return $value;
    }

    if ($this->base_unit_id === $targetUnit->id) {
      return $value * $this->conversion_factor;
    }

    if ($targetUnit->base_unit_id === $this->id) {
      return $value / $targetUnit->conversion_factor;
    }

    if ($this->base_unit_id === $targetUnit->base_unit_id) {
      return ($value * $this->conversion_factor) / $targetUnit->conversion_factor;
    }

    throw new \Exception('Units are not compatible for conversion');
  }
}
