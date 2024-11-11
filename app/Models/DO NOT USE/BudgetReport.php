<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetReport extends Model
{
  protected $fillable = [
    'name',
    'type',
    'parameters',
    'data',
    'format',
    'frequency',
    'last_generated_at',
    'next_generation_at',
    'created_by'
  ];

  protected $casts = [
    'parameters' => 'array',
    'data' => 'array',
    'last_generated_at' => 'datetime',
    'next_generation_at' => 'datetime'
  ];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
