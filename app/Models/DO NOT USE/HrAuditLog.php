<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HrAuditLog extends Model
{
  protected $fillable = [
    'audit_log_id',
    'personnel_action',
    'employee_id',
    'department',
    'details'
  ];

  protected $casts = [
    'details' => 'array'
  ];

  public function auditLog(): BelongsTo
  {
    return $this->belongsTo(AuditLog::class);
  }

  public function employee(): BelongsTo
  {
    return $this->belongsTo(User::class, 'employee_id');
  }

  public function scopeByAction($query, $action)
  {
    return $query->where('personnel_action', $action);
  }

  public function scopeByDepartment($query, $department)
  {
    return $query->where('department', $department);
  }

  public function scopeByEmployee($query, $employeeId)
  {
    return $query->where('employee_id', $employeeId);
  }
}
