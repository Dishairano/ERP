<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreHrmEmployeeModal extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'hrm_employees';

  protected $fillable = [
    'user_id',
    'department_id',
    'employee_id',
    'first_name',
    'last_name',
    'email',
    'phone',
    'date_of_birth',
    'gender',
    'address',
    'city',
    'state',
    'country',
    'postal_code',
    'hire_date',
    'employment_status',
    'job_title',
    'salary',
    'benefits',
    'emergency_contact_name',
    'emergency_contact_phone',
    'notes',
    'is_active'
  ];

  protected $casts = [
    'date_of_birth' => 'date',
    'hire_date' => 'date',
    'benefits' => 'array',
    'salary' => 'decimal:2',
    'is_active' => 'boolean'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function department()
  {
    return $this->belongsTo(CoreHrmDepartmentModal::class, 'department_id');
  }

  public function salaries()
  {
    return $this->hasMany(CoreHrmSalaryModal::class, 'employee_id');
  }

  public function benefits()
  {
    return $this->hasMany(CoreHrmEmployeeBenefitModal::class, 'employee_id');
  }

  public function leaveRequests()
  {
    return $this->hasMany(CoreHrmLeaveRequestModal::class, 'employee_id');
  }

  public function attendances()
  {
    return $this->hasMany(CoreHrmAttendanceModal::class, 'employee_id');
  }

  public function performanceReviews()
  {
    return $this->hasMany(CoreHrmPerformanceReviewModal::class, 'employee_id');
  }

  public function trainingRecords()
  {
    return $this->hasMany(CoreHrmTrainingRecordModal::class, 'employee_id');
  }

  public function documents()
  {
    return $this->hasMany(CoreHrmEmployeeDocumentModal::class, 'employee_id');
  }

  public function getFullNameAttribute()
  {
    return "{$this->first_name} {$this->last_name}";
  }

  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeByDepartment($query, $departmentId)
  {
    return $query->where('department_id', $departmentId);
  }

  public function scopeByStatus($query, $status)
  {
    return $query->where('employment_status', $status);
  }

  public function getCurrentSalaryAttribute()
  {
    return $this->salaries()->latest()->first()?->amount ?? $this->salary;
  }

  public function getActiveBenefitsAttribute()
  {
    return $this->benefits()->active()->get();
  }

  public function getYearsOfServiceAttribute()
  {
    return $this->hire_date ? $this->hire_date->diffInYears(now()) : 0;
  }
}
