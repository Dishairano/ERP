<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreHrmAssessmentModal extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'hrm_assessments';

  protected $fillable = [
    'candidate_id',
    'job_posting_id',
    'assessor_id',
    'created_by',
    'title',
    'description',
    'assessment_type',
    'scheduled_date',
    'scheduled_time',
    'duration_minutes',
    'platform',
    'access_link',
    'access_code',
    'expiry_date',
    'instructions',
    'questions',
    'max_score',
    'passing_score',
    'score',
    'skill_scores',
    'feedback',
    'recommendations',
    'attachments',
    'status'
  ];

  protected $casts = [
    'scheduled_date' => 'date',
    'scheduled_time' => 'datetime',
    'expiry_date' => 'date',
    'questions' => 'array',
    'skill_scores' => 'array',
    'attachments' => 'array',
    'max_score' => 'integer',
    'passing_score' => 'integer',
    'score' => 'integer'
  ];

  public function candidate()
  {
    return $this->belongsTo(CoreHrmCandidateModal::class, 'candidate_id');
  }

  public function jobPosting()
  {
    return $this->belongsTo(CoreHrmJobPostingModal::class, 'job_posting_id');
  }

  public function assessor()
  {
    return $this->belongsTo(User::class, 'assessor_id');
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function scopePending($query)
  {
    return $query->where('status', 'scheduled')
      ->where('scheduled_date', '>=', now())
      ->orderBy('scheduled_date')
      ->orderBy('scheduled_time');
  }

  public function scopeByStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  public function scopeForAssessor($query, $assessorId)
  {
    return $query->where('assessor_id', $assessorId);
  }

  public function getScheduledDateTimeAttribute()
  {
    return $this->scheduled_date->format('Y-m-d') . ' ' . $this->scheduled_time->format('H:i:s');
  }

  public function getFormattedDurationAttribute()
  {
    $hours = floor($this->duration_minutes / 60);
    $minutes = $this->duration_minutes % 60;
    return ($hours > 0 ? "{$hours}h " : '') . ($minutes > 0 ? "{$minutes}m" : '');
  }

  public function getPassedAttribute()
  {
    return $this->score >= $this->passing_score;
  }

  public function getScorePercentageAttribute()
  {
    return $this->max_score > 0 ? round(($this->score / $this->max_score) * 100, 1) : 0;
  }
}
