<?php

namespace App\Traits;

use App\Models\CoreTimeRegistrationModal;
use App\Models\CoreProjectTimeEntryModal;
use App\Models\CoreLeaveRequestModal;
use App\Models\CoreLeaveBalanceModal;
use App\Models\CoreEmployeeScheduleModal;
use App\Models\CoreEmployeeAvailabilityModal;
use App\Models\CoreOvertimeRecordModal;

trait HasTimeManagement
{
  // Time Management Relations
  public function timeRegistrations()
  {
    return $this->hasMany(CoreTimeRegistrationModal::class);
  }

  public function projectTimeEntries()
  {
    return $this->hasMany(CoreProjectTimeEntryModal::class);
  }

  public function leaveRequests()
  {
    return $this->hasMany(CoreLeaveRequestModal::class);
  }

  public function leaveBalances()
  {
    return $this->hasMany(CoreLeaveBalanceModal::class);
  }

  public function schedules()
  {
    return $this->hasMany(CoreEmployeeScheduleModal::class);
  }

  public function availability()
  {
    return $this->hasMany(CoreEmployeeAvailabilityModal::class);
  }

  public function overtimeRecords()
  {
    return $this->hasMany(CoreOvertimeRecordModal::class);
  }

  // Time Management Methods
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
