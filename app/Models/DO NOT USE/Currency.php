<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
  protected $fillable = [
    'code',
    'name',
    'symbol',
    'exchange_rate',
    'is_default',
    'is_active'
  ];

  protected $casts = [
    'exchange_rate' => 'decimal:4',
    'is_default' => 'boolean',
    'is_active' => 'boolean'
  ];

  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeDefault($query)
  {
    return $query->where('is_default', true);
  }
}
