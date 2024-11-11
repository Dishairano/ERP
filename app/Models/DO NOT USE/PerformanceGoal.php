<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceGoal extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'employee_id',
    'title',
    'description',
    'category',
    'start_date',
    'end_date',
    'metrics',
    'target',
    'priority',
    'status',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'metrics' => 'array'
  ];

  /**
   * Get the employee who owns the goal.
   */
  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  /**
   * Get the user who created the goal.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the goal's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the goal's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
