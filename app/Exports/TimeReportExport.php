<?php

namespace App\Exports;

use App\Models\CoreTimeRegistrationModal;
use App\Models\CoreProjectTimeEntryModal;
use App\Models\CoreOvertimeRecordModal;
use App\Models\CoreEmployeeScheduleModal;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class TimeReportExport implements FromCollection, WithHeadings, WithMapping
{
  protected $type;
  protected $startDate;
  protected $endDate;

  public function __construct(string $type)
  {
    $this->type = $type;
    $this->startDate = request('start_date', Carbon::now()->startOfMonth());
    $this->endDate = request('end_date', Carbon::now()->endOfMonth());
  }

  public function collection()
  {
    switch ($this->type) {
      case 'attendance':
        return $this->getAttendanceData();
      case 'overtime':
        return $this->getOvertimeData();
      case 'productivity':
        return $this->getProductivityData();
      case 'cost':
        return $this->getCostData();
      default:
        return collect([]);
    }
  }

  public function headings(): array
  {
    switch ($this->type) {
      case 'attendance':
        return [
          'Employee',
          'Date',
          'Shift',
          'Status',
          'Actual Start Time',
          'Actual End Time',
          'Late (Minutes)',
          'Notes'
        ];
      case 'overtime':
        return [
          'Employee',
          'Date',
          'Hours',
          'Rate Multiplier',
          'Status',
          'Approved By',
          'Approved At',
          'Reason'
        ];
      case 'productivity':
        return [
          'Employee',
          'Project',
          'Date',
          'Hours',
          'Billable Hours',
          'Activity Type',
          'Description'
        ];
      case 'cost':
        return [
          'Project',
          'Employee',
          'Date',
          'Hours',
          'Billable Hours',
          'Rate',
          'Cost',
          'Billable Amount'
        ];
      default:
        return [];
    }
  }

  public function map($row): array
  {
    switch ($this->type) {
      case 'attendance':
        return [
          $row->user->name,
          $row->date->format('Y-m-d'),
          $row->shift->name,
          $row->status,
          $row->actual_start_time?->format('H:i'),
          $row->actual_end_time?->format('H:i'),
          $row->late_minutes,
          $row->notes
        ];
      case 'overtime':
        return [
          $row->user->name,
          $row->date->format('Y-m-d'),
          $row->hours,
          $row->rate_multiplier,
          $row->status,
          $row->approver?->name,
          $row->approved_at?->format('Y-m-d H:i'),
          $row->reason
        ];
      case 'productivity':
        return [
          $row->user->name,
          $row->project->name,
          $row->date->format('Y-m-d'),
          $row->hours,
          $row->billable_hours,
          $row->activity_type,
          $row->description
        ];
      case 'cost':
        return [
          $row->project->name,
          $row->user->name,
          $row->date->format('Y-m-d'),
          $row->hours,
          $row->billable_hours,
          $row->rate,
          $row->hours * ($row->user->hourly_rate ?? 0),
          $row->billable_hours * ($row->rate ?? 0)
        ];
      default:
        return [];
    }
  }

  protected function getAttendanceData()
  {
    return CoreEmployeeScheduleModal::with(['user', 'shift'])
      ->whereBetween('date', [$this->startDate, $this->endDate])
      ->orderBy('date')
      ->get();
  }

  protected function getOvertimeData()
  {
    return CoreOvertimeRecordModal::with(['user', 'approver'])
      ->whereBetween('date', [$this->startDate, $this->endDate])
      ->orderBy('date')
      ->get();
  }

  protected function getProductivityData()
  {
    return CoreProjectTimeEntryModal::with(['user', 'project'])
      ->whereBetween('date', [$this->startDate, $this->endDate])
      ->orderBy('date')
      ->get();
  }

  protected function getCostData()
  {
    return CoreProjectTimeEntryModal::with(['user', 'project'])
      ->whereBetween('date', [$this->startDate, $this->endDate])
      ->orderBy('date')
      ->get();
  }
}
