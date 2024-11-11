<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreHrmTrainingRecordModal extends Model
{
  use HasFactory;

  protected $table = 'hrm_training_records';

  protected $fillable = [
    'employee_id',
    'training_type', // course, workshop, seminar, certification, on-job, mentoring
    'title',
    'description',
    'provider',
    'location',
    'start_date',
    'end_date',
    'duration_hours',
    'cost',
    'currency',
    'status', // planned, in_progress, completed, cancelled
    'completion_date',
    'certification_obtained',
    'certification_number',
    'certification_expiry',
    'score',
    'grade',
    'trainer_name',
    'trainer_contact',
    'materials',
    'attachments',
    'feedback',
    'effectiveness_rating',
    'skills_acquired',
    'knowledge_areas',
    'completion_certificate',
    'reimbursement_status', // not_applicable, pending, approved, reimbursed
    'reimbursement_amount',
    'reimbursement_date',
    'notes',
    'created_by'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'completion_date' => 'date',
    'certification_expiry' => 'date',
    'reimbursement_date' => 'date',
    'duration_hours' => 'decimal:1',
    'cost' => 'decimal:2',
    'reimbursement_amount' => 'decimal:2',
    'score' => 'decimal:1',
    'effectiveness_rating' => 'integer',
    'materials' => 'array',
    'attachments' => 'array',
    'skills_acquired' => 'array',
    'knowledge_areas' => 'array',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the employee that owns the training record.
   */
  public function employee(): BelongsTo
  {
    return $this->belongsTo(CoreHrmEmployeeModal::class, 'employee_id');
  }

  /**
   * Get the creator of the record.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all available training types.
   */
  public static function getTrainingTypes(): array
  {
    return [
      'course',
      'workshop',
      'seminar',
      'certification',
      'on-job',
      'mentoring'
    ];
  }

  /**
   * Get all available training statuses.
   */
  public static function getStatuses(): array
  {
    return [
      'planned',
      'in_progress',
      'completed',
      'cancelled'
    ];
  }

  /**
   * Get all available reimbursement statuses.
   */
  public static function getReimbursementStatuses(): array
  {
    return [
      'not_applicable',
      'pending',
      'approved',
      'reimbursed'
    ];
  }

  /**
   * Calculate training duration in days.
   */
  public function getDurationDaysAttribute(): int
  {
    return $this->start_date->diffInDays($this->end_date) + 1;
  }

  /**
   * Check if the training is currently active.
   */
  public function isActive(): bool
  {
    $now = now();
    return $this->status === 'in_progress' &&
      $this->start_date->lte($now) &&
      $this->end_date->gte($now);
  }

  /**
   * Check if the training is upcoming.
   */
  public function isUpcoming(): bool
  {
    return $this->status === 'planned' &&
      $this->start_date->gt(now());
  }

  /**
   * Check if the certification is expired or about to expire.
   */
  public function isCertificationExpired(): bool
  {
    return $this->certification_expiry &&
      $this->certification_expiry->isPast();
  }

  /**
   * Check if the certification is expiring soon.
   */
  public function isCertificationExpiringSoon(int $days = 30): bool
  {
    return $this->certification_expiry &&
      $this->certification_expiry->isFuture() &&
      $this->certification_expiry->diffInDays(now()) <= $days;
  }

  /**
   * Get the effectiveness rating description.
   */
  public function getEffectivenessDescription(): string
  {
    switch ($this->effectiveness_rating) {
      case 5:
        return 'Excellent';
      case 4:
        return 'Good';
      case 3:
        return 'Satisfactory';
      case 2:
        return 'Fair';
      case 1:
        return 'Poor';
      default:
        return 'Not Rated';
    }
  }

  /**
   * Calculate reimbursement percentage.
   */
  public function getReimbursementPercentageAttribute(): float
  {
    if (!$this->cost || !$this->reimbursement_amount) {
      return 0;
    }
    return round(($this->reimbursement_amount / $this->cost) * 100, 2);
  }

  /**
   * Scope a query to only include training records of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('training_type', $type);
  }

  /**
   * Scope a query to only include training records with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include active training records.
   */
  public function scopeActive($query)
  {
    $now = now();
    return $query->where('status', 'in_progress')
      ->where('start_date', '<=', $now)
      ->where('end_date', '>=', $now);
  }

  /**
   * Scope a query to only include upcoming training records.
   */
  public function scopeUpcoming($query)
  {
    return $query->where('status', 'planned')
      ->where('start_date', '>', now());
  }

  /**
   * Scope a query to only include completed training records.
   */
  public function scopeCompleted($query)
  {
    return $query->where('status', 'completed');
  }

  /**
   * Scope a query to only include training records within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->where(function ($q) use ($startDate, $endDate) {
      $q->whereBetween('start_date', [$startDate, $endDate])
        ->orWhereBetween('end_date', [$startDate, $endDate])
        ->orWhere(function ($q) use ($startDate, $endDate) {
          $q->where('start_date', '<=', $startDate)
            ->where('end_date', '>=', $endDate);
        });
    });
  }

  /**
   * Scope a query to only include certifications.
   */
  public function scopeCertifications($query)
  {
    return $query->where('certification_obtained', true);
  }

  /**
   * Scope a query to only include expired certifications.
   */
  public function scopeExpiredCertifications($query)
  {
    return $query->where('certification_obtained', true)
      ->whereNotNull('certification_expiry')
      ->where('certification_expiry', '<', now());
  }

  /**
   * Scope a query to only include certifications expiring soon.
   */
  public function scopeExpiringCertifications($query, int $days = 30)
  {
    return $query->where('certification_obtained', true)
      ->whereNotNull('certification_expiry')
      ->where('certification_expiry', '>', now())
      ->where('certification_expiry', '<=', now()->addDays($days));
  }

  /**
   * Scope a query to only include training records pending reimbursement.
   */
  public function scopePendingReimbursement($query)
  {
    return $query->where('reimbursement_status', 'pending');
  }
}
