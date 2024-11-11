<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'employee_id',
    'reviewer_id',
    'review_period',
    'review_date',
    'ratings',
    'strengths',
    'areas_for_improvement',
    'goals',
    'comments',
    'status'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'review_date' => 'date',
    'ratings' => 'array',
    'strengths' => 'array',
    'areas_for_improvement' => 'array',
    'goals' => 'array'
  ];

  /**
   * Get the employee being reviewed.
   */
  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  /**
   * Get the reviewer who performed the review.
   */
  public function reviewer()
  {
    return $this->belongsTo(User::class, 'reviewer_id');
  }

  /**
   * Get all of the review's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the review's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
