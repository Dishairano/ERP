<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreHrmPerformanceReviewModal extends Model
{
  use HasFactory;

  protected $table = 'hrm_performance_reviews';

  protected $fillable = [
    'employee_id',
    'reviewer_id',
    'review_period_start',
    'review_period_end',
    'review_date',
    'review_type', // annual, semi-annual, quarterly, monthly, probation
    'overall_rating',
    'performance_summary',
    'achievements',
    'areas_of_improvement',
    'goals_met',
    'goals_unmet',
    'technical_skills_rating',
    'technical_skills_comments',
    'soft_skills_rating',
    'soft_skills_comments',
    'leadership_rating',
    'leadership_comments',
    'productivity_rating',
    'productivity_comments',
    'quality_rating',
    'quality_comments',
    'attendance_rating',
    'attendance_comments',
    'communication_rating',
    'communication_comments',
    'initiative_rating',
    'initiative_comments',
    'teamwork_rating',
    'teamwork_comments',
    'development_plan',
    'training_needs',
    'employee_comments',
    'reviewer_comments',
    'next_review_date',
    'salary_review_due',
    'promotion_recommended',
    'promotion_comments',
    'attachments',
    'acknowledgment_date',
    'status', // draft, in_review, completed, acknowledged
    'created_by'
  ];

  protected $casts = [
    'review_period_start' => 'date',
    'review_period_end' => 'date',
    'review_date' => 'date',
    'next_review_date' => 'date',
    'acknowledgment_date' => 'date',
    'overall_rating' => 'decimal:1',
    'technical_skills_rating' => 'decimal:1',
    'soft_skills_rating' => 'decimal:1',
    'leadership_rating' => 'decimal:1',
    'productivity_rating' => 'decimal:1',
    'quality_rating' => 'decimal:1',
    'attendance_rating' => 'decimal:1',
    'communication_rating' => 'decimal:1',
    'initiative_rating' => 'decimal:1',
    'teamwork_rating' => 'decimal:1',
    'salary_review_due' => 'boolean',
    'promotion_recommended' => 'boolean',
    'attachments' => 'array',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the employee being reviewed.
   */
  public function employee(): BelongsTo
  {
    return $this->belongsTo(CoreHrmEmployeeModal::class, 'employee_id');
  }

  /**
   * Get the reviewer.
   */
  public function reviewer(): BelongsTo
  {
    return $this->belongsTo(CoreHrmEmployeeModal::class, 'reviewer_id');
  }

  /**
   * Get the creator of the record.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all available review types.
   */
  public static function getReviewTypes(): array
  {
    return [
      'annual',
      'semi-annual',
      'quarterly',
      'monthly',
      'probation'
    ];
  }

  /**
   * Get all available review statuses.
   */
  public static function getStatuses(): array
  {
    return [
      'draft',
      'in_review',
      'completed',
      'acknowledged'
    ];
  }

  /**
   * Calculate average rating from all categories.
   */
  public function calculateOverallRating(): float
  {
    $ratings = [
      $this->technical_skills_rating,
      $this->soft_skills_rating,
      $this->leadership_rating,
      $this->productivity_rating,
      $this->quality_rating,
      $this->attendance_rating,
      $this->communication_rating,
      $this->initiative_rating,
      $this->teamwork_rating
    ];

    $validRatings = array_filter($ratings, function ($rating) {
      return !is_null($rating);
    });

    if (empty($validRatings)) {
      return 0;
    }

    return round(array_sum($validRatings) / count($validRatings), 1);
  }

  /**
   * Get the performance rating description.
   */
  public function getRatingDescription(): string
  {
    if ($this->overall_rating >= 4.5) return 'Outstanding';
    if ($this->overall_rating >= 3.5) return 'Exceeds Expectations';
    if ($this->overall_rating >= 2.5) return 'Meets Expectations';
    if ($this->overall_rating >= 1.5) return 'Needs Improvement';
    return 'Unsatisfactory';
  }

  /**
   * Get the performance rating color.
   */
  public function getRatingColor(): string
  {
    if ($this->overall_rating >= 4.5) return 'green';
    if ($this->overall_rating >= 3.5) return 'blue';
    if ($this->overall_rating >= 2.5) return 'yellow';
    if ($this->overall_rating >= 1.5) return 'orange';
    return 'red';
  }

  /**
   * Check if the review is overdue.
   */
  public function isOverdue(): bool
  {
    return $this->review_date && $this->review_date->isPast() &&
      $this->status !== 'completed' && $this->status !== 'acknowledged';
  }

  /**
   * Check if the review needs acknowledgment.
   */
  public function needsAcknowledgment(): bool
  {
    return $this->status === 'completed' && !$this->acknowledgment_date;
  }

  /**
   * Check if salary review is due based on performance.
   */
  public function shouldReviewSalary(): bool
  {
    return $this->overall_rating >= 4.0 || $this->salary_review_due;
  }

  /**
   * Check if promotion should be considered based on performance.
   */
  public function shouldConsiderPromotion(): bool
  {
    return $this->overall_rating >= 4.5 || $this->promotion_recommended;
  }

  /**
   * Scope a query to only include reviews of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('review_type', $type);
  }

  /**
   * Scope a query to only include reviews with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include reviews within a date range.
   */
  public function scopeInPeriod($query, $startDate, $endDate)
  {
    return $query->whereBetween('review_date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include overdue reviews.
   */
  public function scopeOverdue($query)
  {
    return $query->where('review_date', '<', now())
      ->whereNotIn('status', ['completed', 'acknowledged']);
  }

  /**
   * Scope a query to only include reviews pending acknowledgment.
   */
  public function scopePendingAcknowledgment($query)
  {
    return $query->where('status', 'completed')
      ->whereNull('acknowledgment_date');
  }

  /**
   * Scope a query to only include reviews with high ratings.
   */
  public function scopeHighPerformers($query, $minRating = 4.0)
  {
    return $query->where('overall_rating', '>=', $minRating);
  }

  /**
   * Scope a query to only include reviews with low ratings.
   */
  public function scopeLowPerformers($query, $maxRating = 2.0)
  {
    return $query->where('overall_rating', '<=', $maxRating);
  }

  /**
   * Scope a query to only include reviews recommending promotion.
   */
  public function scopePromotionRecommended($query)
  {
    return $query->where('promotion_recommended', true);
  }

  /**
   * Scope a query to only include reviews due for salary review.
   */
  public function scopeSalaryReviewDue($query)
  {
    return $query->where('salary_review_due', true);
  }
}
