<?php

namespace App\Http\Controllers;

use App\Models\TimeRegistration;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TimeRegistrationController extends Controller
{
  public function dashboard()
  {
    $today = Carbon::today();
    $weekStart = Carbon::now()->startOfWeek();
    $weekEnd = Carbon::now()->endOfWeek();

    $stats = [
      'hours_today' => TimeRegistration::where('user_id', Auth::id())
        ->whereDate('date', $today)
        ->sum('hours'),
      'hours_this_week' => TimeRegistration::where('user_id', Auth::id())
        ->whereBetween('date', [$weekStart, $weekEnd])
        ->sum('hours'),
      'pending_approvals' => TimeRegistration::where('status', 'pending')->count(),
      'leave_balance' => 0 // This would be calculated based on your leave system
    ];

    $recentRegistrations = TimeRegistration::with(['project', 'task'])
      ->where('user_id', Auth::id())
      ->orderBy('date', 'desc')
      ->orderBy('created_at', 'desc')
      ->take(5)
      ->get();

    $weeklyData = TimeRegistration::where('user_id', Auth::id())
      ->whereBetween('date', [$weekStart, $weekEnd])
      ->select('date', DB::raw('SUM(hours) as total_hours'))
      ->groupBy('date')
      ->get()
      ->pluck('total_hours', 'date')
      ->toArray();

    return view('time-registration.dashboard', compact('stats', 'recentRegistrations', 'weeklyData'));
  }

  public function index(Request $request)
  {
    $query = TimeRegistration::with(['project', 'task', 'user'])
      ->when($request->project_id, function ($q) use ($request) {
        return $q->where('project_id', $request->project_id);
      })
      ->when($request->user_id, function ($q) use ($request) {
        return $q->where('user_id', $request->user_id);
      })
      ->when($request->status, function ($q) use ($request) {
        return $q->where('status', $request->status);
      })
      ->when($request->date_from, function ($q) use ($request) {
        return $q->where('date', '>=', $request->date_from);
      })
      ->when($request->date_to, function ($q) use ($request) {
        return $q->where('date', '<=', $request->date_to);
      });

    $timeRegistrations = $query->orderBy('date', 'desc')
      ->orderBy('created_at', 'desc')
      ->paginate(15);

    $projects = Project::select('id', 'name')->get();
    $users = User::select('id', 'name')->get();
    $stats = $this->getTimeStats();

    return view('time-registration.index', compact(
      'timeRegistrations',
      'projects',
      'users',
      'stats'
    ));
  }

  protected function getTimeStats()
  {
    $currentWeek = [now()->startOfWeek(), now()->endOfWeek()];
    $currentMonth = [now()->startOfMonth(), now()->endOfMonth()];

    return [
      'weekly_total' => TimeRegistration::whereBetween('date', $currentWeek)
        ->sum('hours'),
      'monthly_total' => TimeRegistration::whereBetween('date', $currentMonth)
        ->sum('hours'),
      'pending_approvals' => TimeRegistration::where('status', 'pending')
        ->count(),
      'project_distribution' => $this->getProjectTimeDistribution(),
      'recent_entries' => TimeRegistration::with(['project', 'task', 'user'])
        ->latest()
        ->take(5)
        ->get()
    ];
  }

  protected function getProjectTimeDistribution()
  {
    return TimeRegistration::select('project_id', DB::raw('SUM(hours) as total_hours'))
      ->with('project:id,name')
      ->whereMonth('date', now()->month)
      ->groupBy('project_id')
      ->orderByDesc('total_hours')
      ->limit(5)
      ->get();
  }

  public function create()
  {
    $projects = Project::select('id', 'name')->get();
    $tasks = Task::select('id', 'name', 'project_id')
      ->whereIn('status', ['pending', 'in_progress'])
      ->get()
      ->groupBy('project_id');

    return view('time-registration.create', compact('projects', 'tasks'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'task_id' => 'required|exists:tasks,id',
      'date' => 'required|date|before_or_equal:today',
      'hours' => 'required|numeric|min:0.25|max:24',
      'description' => 'required|string',
      'billable' => 'boolean'
    ]);

    $timeRegistration = TimeRegistration::create([
      ...$validated,
      'user_id' => Auth::id(),
      'status' => 'pending',
      'week_number' => Carbon::parse($validated['date'])->weekOfYear,
      'month' => Carbon::parse($validated['date'])->month
    ]);

    return redirect()->route('time-registrations.index')
      ->with('success', 'Time registration created successfully');
  }

  public function calendar()
  {
    $timeRegistrations = TimeRegistration::with(['project', 'task'])
      ->where('user_id', Auth::id())
      ->get()
      ->map(function ($registration) {
        return [
          'id' => $registration->id,
          'title' => $registration->project->name . ' - ' . $registration->task->name,
          'start' => $registration->date->format('Y-m-d'),
          'hours' => $registration->hours,
          'status' => $registration->status,
          'description' => $registration->description
        ];
      });

    return view('time-registration.calendar', compact('timeRegistrations'));
  }

  public function approvals()
  {
    $pendingApprovals = TimeRegistration::with(['project', 'task', 'user'])
      ->where('status', 'pending')
      ->orderBy('date', 'desc')
      ->paginate(15);

    $approvalStats = [
      'total_pending' => TimeRegistration::where('status', 'pending')->count(),
      'approved_today' => TimeRegistration::where('status', 'approved')
        ->whereDate('updated_at', today())
        ->count(),
      'rejected_today' => TimeRegistration::where('status', 'rejected')
        ->whereDate('updated_at', today())
        ->count()
    ];

    return view('time-registration.approvals', compact('pendingApprovals', 'approvalStats'));
  }

  public function updateStatus(Request $request, TimeRegistration $registration)
  {
    $validated = $request->validate([
      'status' => 'required|in:approved,rejected',
      'rejection_reason' => 'required_if:status,rejected|string'
    ]);

    if ($validated['status'] === 'approved') {
      $registration->update([
        'status' => 'approved',
        'approved_by' => Auth::id(),
        'approved_at' => now()
      ]);
    } else {
      $registration->update([
        'status' => 'rejected',
        'rejection_reason' => $validated['rejection_reason'],
        'rejected_by' => Auth::id(),
        'rejected_at' => now()
      ]);
    }

    return back()->with('success', 'Time registration ' . $validated['status'] . ' successfully');
  }

  public function approve(TimeRegistration $timeRegistration)
  {
    $timeRegistration->update([
      'status' => 'approved',
      'approved_by' => Auth::id(),
      'approved_at' => now()
    ]);

    return back()->with('success', 'Time registration approved successfully');
  }

  public function reject(Request $request, TimeRegistration $timeRegistration)
  {
    $validated = $request->validate([
      'rejection_reason' => 'required|string'
    ]);

    $timeRegistration->update([
      'status' => 'rejected',
      'rejection_reason' => $validated['rejection_reason'],
      'rejected_by' => Auth::id(),
      'rejected_at' => now()
    ]);

    return back()->with('success', 'Time registration rejected successfully');
  }

  public function bulkApprove(Request $request)
  {
    $validated = $request->validate([
      'registrations' => 'required|array',
      'registrations.*' => 'exists:time_registrations,id'
    ]);

    TimeRegistration::whereIn('id', $validated['registrations'])
      ->where('status', 'pending')
      ->update([
        'status' => 'approved',
        'approved_by' => Auth::id(),
        'approved_at' => now()
      ]);

    return back()->with('success', 'Selected time registrations approved successfully');
  }

  public function export(Request $request)
  {
    $query = TimeRegistration::with(['project', 'task', 'user'])
      ->when($request->project_id, function ($q) use ($request) {
        return $q->where('project_id', $request->project_id);
      })
      ->when($request->user_id, function ($q) use ($request) {
        return $q->where('user_id', $request->user_id);
      })
      ->when($request->status, function ($q) use ($request) {
        return $q->where('status', $request->status);
      })
      ->when($request->date_from, function ($q) use ($request) {
        return $q->where('date', '>=', $request->date_from);
      })
      ->when($request->date_to, function ($q) use ($request) {
        return $q->where('date', '<=', $request->date_to);
      });

    $timeRegistrations = $query->orderBy('date', 'desc')
      ->get();

    return response()->streamDownload(function () use ($timeRegistrations) {
      $output = fopen('php://output', 'w');

      // Headers
      fputcsv($output, [
        'Date',
        'Project',
        'Task',
        'User',
        'Hours',
        'Description',
        'Status',
        'Billable'
      ]);

      // Data
      foreach ($timeRegistrations as $registration) {
        fputcsv($output, [
          $registration->date->format('Y-m-d'),
          $registration->project->name,
          $registration->task->name,
          $registration->user->name,
          $registration->hours,
          $registration->description,
          $registration->status,
          $registration->billable ? 'Yes' : 'No'
        ]);
      }

      fclose($output);
    }, 'time-registrations.csv');
  }
}
