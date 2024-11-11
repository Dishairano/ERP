<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreHrmInterviewModal extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'hrm_interviews';

  protected $fillable = [
    'candidate_id',
    'job_posting_id',
    'interviewer_id',
    'created_by',
    'interview_type',
    'round_number',
    'scheduled_date',
    'scheduled_time',
    'duration_minutes',
    'location',
    'meeting_link',
    'meeting_id',
    'meeting_password',
    'preparation_notes',
    'questions',
    'evaluation_criteria',
    'technical_skills_rating',
    'soft_skills_rating',
    'cultural_fit_rating',
    'overall_rating',
    'interviewer_notes',
    'candidate_feedback',
    'next_steps',
    'status',
    'cancellation_reason'
  ];

  protected $casts = [
    'scheduled_date' => 'date',
    'scheduled_time' => 'datetime',
    'questions' => 'array',
    'evaluation_criteria' => 'array',
    'technical_skills_rating' => 'decimal:1',
    'soft_skills_rating' => 'decimal:1',
    'cultural_fit_rating' => 'decimal:1',
    'overall_rating' => 'decimal:1',
  ];

  public function candidate()
  {
    return $this->belongsTo(CoreHrmCandidateModal::class, 'candidate_id');
  }

  public function jobPosting()
  {
    return $this->belongsTo(CoreHrmJobPostingModal::class, 'job_posting_id');
  }

  public function interviewer()
  {
    return $this->belongsTo(User::class, 'interviewer_id');
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function scopeUpcoming($query)
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

  public function scopeForInterviewer($query, $interviewerId)
  {
    return $query->where('interviewer_id', $interviewerId);
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
}
