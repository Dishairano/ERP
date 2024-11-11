<?php

namespace App\Traits;

use App\Models\CoreProjectModal;
use App\Models\CoreProjectTaskModal;
use App\Models\CoreHrmDepartmentModal;
use App\Models\CoreHrmPerformanceReviewModal;
use App\Models\CoreHrmTrainingRecordModal;
use App\Models\CoreEmployeeScheduleModal;

trait HasUserFeatures
{
  use HasTimeManagement;
  use HasAuthorization;

  // Project Relations
  public function managedProjects()
  {
    return $this->hasMany(CoreProjectModal::class, 'manager_id');
  }

  public function assignedTasks()
  {
    return $this->hasMany(CoreProjectTaskModal::class, 'assignee_id');
  }

  // HRM Relations
  public function department()
  {
    return $this->belongsTo(CoreHrmDepartmentModal::class, 'department_id');
  }

  public function performanceReviews()
  {
    return $this->hasMany(CoreHrmPerformanceReviewModal::class, 'employee_id');
  }

  public function trainingRecords()
  {
    return $this->hasMany(CoreHrmTrainingRecordModal::class, 'employee_id');
  }

  // Methods
  public function updateLastLogin(): bool
  {
    return $this->update([
      'last_login_at' => now()
    ]);
  }

  public function getTotalHoursForPeriod($startDate, $endDate): float
  {
    return $this->timeRegistrations()
      ->whereBetween('date', [$startDate, $endDate])
      ->sum('hours');
  }

  public function getLeaveBalanceForType($leaveTypeId, $year = null): float
  {
    $year = $year ?? date('Y');

    $balance = $this->leaveBalances()
      ->where('leave_type_id', $leaveTypeId)
      ->where('year', $year)
      ->first();

    return $balance ? $balance->remaining_days : 0;
  }

  public function getCurrentSchedule(): ?CoreEmployeeScheduleModal
  {
    return $this->schedules()
      ->where('date', now()->format('Y-m-d'))
      ->first();
  }

  public function isAvailableOn($date, $startTime, $endTime): bool
  {
    return $this->availability()
      ->where('date', $date)
      ->where('availability_type', '!=', 'unavailable')
      ->where('start_time', '<=', $startTime)
      ->where('end_time', '>=', $endTime)
      ->exists();
  }

  public function getPendingOvertimeHours(): float
  {
    return $this->overtimeRecords()
      ->where('status', 'pending')
      ->sum('hours');
  }

  public function getApprovedOvertimeHours($startDate = null, $endDate = null): float
  {
    $query = $this->overtimeRecords()->where('status', 'approved');

    if ($startDate && $endDate) {
      $query->whereBetween('date', [$startDate, $endDate]);
    }

    return $query->sum('hours');
  }
}
