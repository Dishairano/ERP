<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceFeedback extends Model
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
    'relationship',
    'period',
    'competencies',
    'strengths',
    'improvements',
    'comments',
    'status'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'competencies' => 'array',
    'strengths' => 'array',
    'improvements' => 'array'
  ];

  /**
   * Get the employee being reviewed.
   */
  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  /**
   * Get the reviewer who provided the feedback.
   */
  public function reviewer()
  {
    return $this->belongsTo(User::class, 'reviewer_id');
  }

  /**
   * Get all of the feedback's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the feedback's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
