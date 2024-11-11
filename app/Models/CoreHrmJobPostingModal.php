<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\CoreHrmCandidateModal;
use App\Models\CoreHrmInterviewModal;
use App\Models\CoreHrmAssessmentModal;

class CoreHrmJobPostingModal extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'hrm_job_postings';

  protected $fillable = [
    'created_by',
    'title',
    'description',
    'department',
    'location',
    'employment_type',
    'experience_level',
    'salary_min',
    'salary_max',
    'required_skills',
    'responsibilities',
    'qualifications',
    'benefits',
    'posting_date',
    'closing_date',
    'status',
    'positions_available',
    'positions_filled'
  ];

  protected $casts = [
    'required_skills' => 'array',
    'responsibilities' => 'array',
    'qualifications' => 'array',
    'benefits' => 'array',
    'posting_date' => 'date',
    'closing_date' => 'date',
    'salary_min' => 'decimal:2',
    'salary_max' => 'decimal:2',
  ];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function candidates()
  {
    return $this->hasMany(CoreHrmCandidateModal::class, 'job_posting_id');
  }

  public function interviews()
  {
    return $this->hasMany(CoreHrmInterviewModal::class, 'job_posting_id');
  }

  public function assessments()
  {
    return $this->hasMany(CoreHrmAssessmentModal::class, 'job_posting_id');
  }

  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  public function scopeOpen($query)
  {
    return $query->where('status', 'active')
      ->where('closing_date', '>=', now())
      ->whereRaw('positions_filled < positions_available');
  }
}
