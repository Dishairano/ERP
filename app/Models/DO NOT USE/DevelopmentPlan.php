<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevelopmentPlan extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'employee_id',
    'mentor_id',
    'objectives',
    'start_date',
    'end_date',
    'activities',
    'resources',
    'milestones',
    'success_criteria',
    'status',
    'notes',
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
    'objectives' => 'array',
    'activities' => 'array',
    'resources' => 'array',
    'milestones' => 'array',
    'success_criteria' => 'array'
  ];

  /**
   * Get the employee who owns the plan.
   */
  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  /**
   * Get the mentor assigned to this plan.
   */
  public function mentor()
  {
    return $this->belongsTo(Employee::class, 'mentor_id');
  }

  /**
   * Get the user who created the plan.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the plan's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the plan's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
