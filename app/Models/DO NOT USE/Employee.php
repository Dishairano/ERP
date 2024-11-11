<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'phone',
    'department_id',
    'position_id',
    'start_date',
    'end_date',
    'status',
    'address',
    'city',
    'state',
    'postal_code',
    'country',
    'emergency_contact_name',
    'emergency_contact_phone',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the department that the employee belongs to.
   */
  public function department()
  {
    return $this->belongsTo(Department::class);
  }

  /**
   * Get the position that the employee holds.
   */
  public function position()
  {
    return $this->belongsTo(Position::class);
  }

  /**
   * Get the employee's full name.
   *
   * @return string
   */
  public function getFullNameAttribute()
  {
    return "{$this->first_name} {$this->last_name}";
  }

  /**
   * Get the payroll records for the employee.
   */
  public function payrollRecords()
  {
    return $this->hasMany(Payroll::class);
  }

  /**
   * Get the leave requests for the employee.
   */
  public function leaveRequests()
  {
    return $this->hasMany(LeaveRequest::class);
  }

  /**
   * Get the performance evaluations for the employee.
   */
  public function performanceEvaluations()
  {
    return $this->hasMany(PerformanceEvaluation::class);
  }

  /**
   * Get the training records for the employee.
   */
  public function trainingRecords()
  {
    return $this->hasMany(TrainingRecord::class);
  }
}
