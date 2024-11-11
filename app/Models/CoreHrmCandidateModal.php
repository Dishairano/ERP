<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreHrmCandidateModal extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'hrm_candidates';

  protected $fillable = [
    'job_posting_id',
    'created_by',
    'first_name',
    'last_name',
    'email',
    'phone',
    'city',
    'state',
    'country',
    'address',
    'current_company',
    'current_position',
    'experience_years',
    'education_level',
    'field_of_study',
    'skills',
    'portfolio_url',
    'linkedin_url',
    'github_url',
    'resume_path',
    'cover_letter_path',
    'status',
    'rejection_reason'
  ];

  protected $casts = [
    'skills' => 'array',
    'experience_years' => 'integer',
  ];

  public function jobPosting()
  {
    return $this->belongsTo(CoreHrmJobPostingModal::class, 'job_posting_id');
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function interviews()
  {
    return $this->hasMany(CoreHrmInterviewModal::class, 'candidate_id');
  }

  public function assessments()
  {
    return $this->hasMany(CoreHrmAssessmentModal::class, 'candidate_id');
  }

  public function getFullNameAttribute()
  {
    return "{$this->first_name} {$this->last_name}";
  }

  public function scopeActive($query)
  {
    return $query->whereNotIn('status', ['rejected', 'withdrawn']);
  }

  public function scopeByStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  public function scopeRecent($query)
  {
    return $query->orderBy('created_at', 'desc');
  }
}
