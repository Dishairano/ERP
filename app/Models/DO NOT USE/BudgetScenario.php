<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetScenario extends Model
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
    'description',
    'created_by'
  ];

  /**
   * Get the budget that owns the scenario.
   */
  public function budget()
  {
    return $this->belongsTo(Budget::class);
  }

  /**
   * Get the user who created the scenario.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the scenario adjustments.
   */
  public function adjustments()
  {
    return $this->hasMany(BudgetScenarioAdjustment::class);
  }

  /**
   * Get all of the scenario's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the scenario's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
