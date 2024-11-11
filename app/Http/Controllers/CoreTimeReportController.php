<?php

namespace App\Http\Controllers;

use App\Models\CoreTimeRegistrationModal;
use App\Models\CoreProjectTimeEntryModal;
use App\Models\CoreOvertimeRecordModal;
use App\Models\CoreEmployeeScheduleModal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\TimeReportExport;
use Maatwebsite\Excel\Facades\Excel;

class CoreTimeReportController extends Controller
{
  public function attendance()
  {
    $this->authorize('view_reports');

    $users = User::all();
    $startDate = request('start_date', Carbon::now()->startOfMonth());
    $endDate = request('end_date', Carbon::now()->endOfMonth());

    $attendanceData = CoreEmployeeScheduleModal::with(['user', 'shift'])
      ->whereBetween('date', [$startDate, $endDate])
      ->get()
      ->groupBy('user_id')
      ->map(function ($schedules) {
        $totalScheduled = $schedules->count();
        $completed = $schedules->where('status', 'completed')->count();
        $absent = $schedules->where('status', 'absent')->count();
        $late = $schedules->filter(function ($schedule) {
          return $schedule->is_late;
        })->count();

        return [
          'total_scheduled' => $totalScheduled,
          'completed' => $completed,
          'absent' => $absent,
          'late' => $late,
          'attendance_rate' => $totalScheduled ? round(($completed / $totalScheduled) * 100, 2) : 0
        ];
      });

    return view('content.time-reports.attendance', compact('users', 'attendanceData', 'startDate', 'endDate'));
  }

  public function overtime()
  {
    $this->authorize('view_reports');

    $users = User::all();
    $startDate = request('start_date', Carbon::now()->startOfMonth());
    $endDate = request('end_date', Carbon::now()->endOfMonth());

    $overtimeData = CoreOvertimeRecordModal::with('user')
      ->whereBetween('date', [$startDate, $endDate])
      ->get()
      ->groupBy('user_id')
      ->map(function ($records) {
        return [
          'total_hours' => $records->sum('hours'),
          'approved_hours' => $records->where('status', 'approved')->sum('hours'),
          'pending_hours' => $records->where('status', 'pending')->sum('hours'),
          'rejected_hours' => $records->where('status', 'rejected')->sum('hours'),
          'total_cost' => $records->where('status', 'approved')->sum(function ($record) {
            return $record->hours * ($record->user->hourly_rate ?? 0) * $record->rate_multiplier;
          })
        ];
      });

    return view('content.time-reports.overtime', compact('users', 'overtimeData', 'startDate', 'endDate'));
  }

  public function productivity()
  {
    $this->authorize('view_reports');

    $users = User::all();
    $startDate = request('start_date', Carbon::now()->startOfMonth());
    $endDate = request('end_date', Carbon::now()->endOfMonth());

    $productivityData = CoreProjectTimeEntryModal::with(['user', 'project'])
      ->whereBetween('date', [$startDate, $endDate])
      ->get()
      ->groupBy('user_id')
      ->map(function ($entries) {
        $totalHours = $entries->sum('hours');
        $billableHours = $entries->sum('billable_hours');

        return [
          'total_hours' => $totalHours,
          'billable_hours' => $billableHours,
          'non_billable_hours' => $totalHours - $billableHours,
          'billable_percentage' => $totalHours ? round(($billableHours / $totalHours) * 100, 2) : 0,
          'projects' => $entries->groupBy('project_id')->map(function ($projectEntries) {
            return [
              'hours' => $projectEntries->sum('hours'),
              'billable_hours' => $projectEntries->sum('billable_hours')
            ];
          })
        ];
      });

    return view('content.time-reports.productivity', compact('users', 'productivityData', 'startDate', 'endDate'));
  }

  public function cost()
  {
    $this->authorize('view_reports');

    $users = User::all();
    $startDate = request('start_date', Carbon::now()->startOfMonth());
    $endDate = request('end_date', Carbon::now()->endOfMonth());

    $costData = CoreProjectTimeEntryModal::with(['user', 'project'])
      ->whereBetween('date', [$startDate, $endDate])
      ->get()
      ->groupBy('project_id')
      ->map(function ($entries) {
        $totalHours = $entries->sum('hours');
        $billableHours = $entries->sum('billable_hours');
        $totalCost = $entries->sum(function ($entry) {
          return $entry->hours * ($entry->user->hourly_rate ?? 0);
        });
        $billableAmount = $entries->sum(function ($entry) {
          return $entry->billable_hours * ($entry->rate ?? 0);
        });

        return [
          'total_hours' => $totalHours,
          'billable_hours' => $billableHours,
          'total_cost' => $totalCost,
          'billable_amount' => $billableAmount,
          'profit' => $billableAmount - $totalCost
        ];
      });

    return view('content.time-reports.cost', compact('users', 'costData', 'startDate', 'endDate'));
  }

  public function exportAttendance()
  {
    $this->authorize('export_reports');
    return Excel::download(new TimeReportExport('attendance'), 'attendance-report.xlsx');
  }

  public function exportOvertime()
  {
    $this->authorize('export_reports');
    return Excel::download(new TimeReportExport('overtime'), 'overtime-report.xlsx');
  }

  public function exportProductivity()
  {
    $this->authorize('export_reports');
    return Excel::download(new TimeReportExport('productivity'), 'productivity-report.xlsx');
  }

  public function exportCost()
  {
    $this->authorize('export_reports');
    return Excel::download(new TimeReportExport('cost'), 'cost-report.xlsx');
  }
}
