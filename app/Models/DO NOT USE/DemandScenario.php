<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandScenario extends Model
{
  protected $fillable = [
    'scenario_name',
    'description',
    'created_by',
    'scenario_factors',
    'results',
    'is_active'
  ];

  protected $casts = [
    'scenario_factors' => 'array',
    'results' => 'array',
    'is_active' => 'boolean'
  ];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
