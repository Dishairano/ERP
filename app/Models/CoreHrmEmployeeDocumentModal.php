<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreHrmEmployeeDocumentModal extends Model
{
  use HasFactory;

  protected $table = 'hrm_employee_documents';

  protected $fillable = [
    'employee_id',
    'document_type', // contract, id_proof, resume, certificate, visa, passport, etc.
    'title',
    'description',
    'file_path',
    'file_name',
    'file_type',
    'file_size',
    'issue_date',
    'expiry_date',
    'issuing_authority',
    'document_number',
    'verification_status', // pending, verified, rejected
    'verified_by',
    'verified_at',
    'rejection_reason',
    'is_confidential',
    'access_level', // public, restricted, confidential
    'tags',
    'metadata',
    'version',
    'status', // active, archived, expired
    'notes',
    'created_by'
  ];

  protected $casts = [
    'issue_date' => 'date',
    'expiry_date' => 'date',
    'verified_at' => 'datetime',
    'file_size' => 'integer',
    'is_confidential' => 'boolean',
    'tags' => 'array',
    'metadata' => 'array',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the employee that owns the document.
   */
  public function employee(): BelongsTo
  {
    return $this->belongsTo(CoreHrmEmployeeModal::class, 'employee_id');
  }

  /**
   * Get the user who verified the document.
   */
  public function verifier(): BelongsTo
  {
    return $this->belongsTo(User::class, 'verified_by');
  }

  /**
   * Get the creator of the record.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all available document types.
   */
  public static function getDocumentTypes(): array
  {
    return [
      'contract',
      'id_proof',
      'resume',
      'certificate',
      'visa',
      'passport',
      'work_permit',
      'education_document',
      'medical_record',
      'background_check',
      'tax_document',
      'bank_document',
      'insurance_document',
      'performance_review',
      'disciplinary_record',
      'training_certificate',
      'other'
    ];
  }

  /**
   * Get all available verification statuses.
   */
  public static function getVerificationStatuses(): array
  {
    return [
      'pending',
      'verified',
      'rejected'
    ];
  }

  /**
   * Get all available access levels.
   */
  public static function getAccessLevels(): array
  {
    return [
      'public',
      'restricted',
      'confidential'
    ];
  }

  /**
   * Get all available document statuses.
   */
  public static function getStatuses(): array
  {
    return [
      'active',
      'archived',
      'expired'
    ];
  }

  /**
   * Get formatted file size.
   */
  public function getFormattedFileSizeAttribute(): string
  {
    $bytes = $this->file_size;
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    for ($i = 0; $bytes > 1024; $i++) {
      $bytes /= 1024;
    }

    return round($bytes, 2) . ' ' . $units[$i];
  }

  /**
   * Check if the document is expired.
   */
  public function isExpired(): bool
  {
    return $this->expiry_date && $this->expiry_date->isPast();
  }

  /**
   * Check if the document is expiring soon.
   */
  public function isExpiringSoon(int $days = 30): bool
  {
    return $this->expiry_date &&
      $this->expiry_date->isFuture() &&
      $this->expiry_date->diffInDays(now()) <= $days;
  }

  /**
   * Check if the document is verified.
   */
  public function isVerified(): bool
  {
    return $this->verification_status === 'verified';
  }

  /**
   * Check if the document needs verification.
   */
  public function needsVerification(): bool
  {
    return $this->verification_status === 'pending';
  }

  /**
   * Check if the document was rejected.
   */
  public function isRejected(): bool
  {
    return $this->verification_status === 'rejected';
  }

  /**
   * Get the document status with color code.
   */
  public function getStatusWithColor(): array
  {
    $colors = [
      'active' => 'green',
      'archived' => 'gray',
      'expired' => 'red'
    ];

    return [
      'status' => $this->status,
      'color' => $colors[$this->status] ?? 'gray'
    ];
  }

  /**
   * Scope a query to only include documents of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('document_type', $type);
  }

  /**
   * Scope a query to only include documents with a specific verification status.
   */
  public function scopeWithVerificationStatus($query, $status)
  {
    return $query->where('verification_status', $status);
  }

  /**
   * Scope a query to only include documents with a specific access level.
   */
  public function scopeWithAccessLevel($query, $level)
  {
    return $query->where('access_level', $level);
  }

  /**
   * Scope a query to only include active documents.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include archived documents.
   */
  public function scopeArchived($query)
  {
    return $query->where('status', 'archived');
  }

  /**
   * Scope a query to only include expired documents.
   */
  public function scopeExpired($query)
  {
    return $query->where(function ($q) {
      $q->where('status', 'expired')
        ->orWhere(function ($q) {
          $q->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now());
        });
    });
  }

  /**
   * Scope a query to only include documents expiring soon.
   */
  public function scopeExpiringSoon($query, int $days = 30)
  {
    return $query->whereNotNull('expiry_date')
      ->where('expiry_date', '>', now())
      ->where('expiry_date', '<=', now()->addDays($days));
  }

  /**
   * Scope a query to only include confidential documents.
   */
  public function scopeConfidential($query)
  {
    return $query->where('is_confidential', true);
  }

  /**
   * Scope a query to only include documents with specific tags.
   */
  public function scopeWithTags($query, array $tags)
  {
    return $query->where(function ($q) use ($tags) {
      foreach ($tags as $tag) {
        $q->whereJsonContains('tags', $tag);
      }
    });
  }

  /**
   * Scope a query to only include documents issued within a date range.
   */
  public function scopeIssuedBetween($query, $startDate, $endDate)
  {
    return $query->whereBetween('issue_date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include documents verified by a specific user.
   */
  public function scopeVerifiedBy($query, $userId)
  {
    return $query->where('verified_by', $userId);
  }

  /**
   * Scope a query to only include documents that need verification.
   */
  public function scopeNeedsVerification($query)
  {
    return $query->where('verification_status', 'pending');
  }
}
