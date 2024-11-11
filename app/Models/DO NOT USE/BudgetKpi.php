<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetKpi extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'budget_id',
    'name',
    'target',
    'unit',
    'frequency',
    'actual',
    'status'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'target' => 'decimal:2',
    'actual' => 'decimal:2'
  ];

  /**
   * Get the budget that owns the KPI.
   */
  public function budget()
  {
    return $this->belongsTo(Budget::class);
  }

  /**
   * Get all of the KPI's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the KPI's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
