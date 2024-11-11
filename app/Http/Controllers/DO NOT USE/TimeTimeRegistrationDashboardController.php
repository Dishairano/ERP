<?php

namespace App\Http\Controllers;

use App\Models\TimeTimeRegistrationDashboardModal;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TimeTimeRegistrationDashboardController extends Controller
{
  public function index()
  {
    $user = Auth::user();

    // Get current week's registrations
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    $weeklyRegistrations = TimeTimeRegistrationDashboardModal::with(['project', 'task'])
      ->forUser($user->id)
      ->forPeriod($startOfWeek, $endOfWeek)
      ->get();

    // Calculate weekly statistics
    $weeklyStats = [
      'total_hours' => $weeklyRegistrations->sum('duration'),
      'billable_hours' => $weeklyRegistrations->where('billable', true)->sum('duration'),
      'overtime_hours' => $weeklyRegistrations->sum('overtime_hours'),
      'projects_count' => $weeklyRegistrations->pluck('project_id')->unique()->count()
    ];

    // Get pending approvals for managers
    $pendingApprovals = [];
    if ($user->role === User::ROLE_ADMIN || $user->role === User::ROLE_MANAGER) {
      $pendingApprovals = TimeTimeRegistrationDashboardModal::with(['user', 'project'])
        ->pending()
        ->whereHas('project', function ($query) use ($user) {
          $query->where('manager_id', $user->id);
        })
        ->get();
    }

    // Get project time allocation
    $projectAllocation = TimeTimeRegistrationDashboardModal::with('project')
      ->forUser($user->id)
      ->forPeriod($startOfWeek, $endOfWeek)
      ->get()
      ->groupBy('project_id')
      ->map(function ($registrations) {
        return [
          'project_name' => $registrations->first()->project->name,
          'total_hours' => $registrations->sum('duration'),
          'percentage' => 0 // Will be calculated below
        ];
      });

    // Calculate percentages
    $totalHours = $projectAllocation->sum('total_hours');
    $projectAllocation = $projectAllocation->map(function ($project) use ($totalHours) {
      $project['percentage'] = $totalHours > 0 ?
        round(($project['total_hours'] / $totalHours) * 100, 2) : 0;
      return $project;
    });

    // Get recent activities
    $recentActivities = TimeTimeRegistrationDashboardModal::with(['user', 'project', 'task'])
      ->forUser($user->id)
      ->orderBy('created_at', 'desc')
      ->limit(10)
      ->get();

    return view('time.registration.dashboard', compact(
      'weeklyStats',
      'pendingApprovals',
      'projectAllocation',
      'recentActivities',
      'weeklyRegistrations'
    ));
  }

  public function approve(Request $request, TimeTimeRegistrationDashboardModal $timeRegistration)
  {
    $user = Auth::user();

    if (!($user->role === User::ROLE_ADMIN || $user->role === User::ROLE_MANAGER)) {
      return redirect()->back()->with('error', 'You do not have permission to approve time registrations');
    }

    $timeRegistration->update([
      'approved_by' => $user->id,
      'approved_at' => now(),
      'status' => 'approved'
    ]);

    return redirect()->back()->with('success', 'Time registration approved successfully');
  }

  public function reject(Request $request, TimeTimeRegistrationDashboardModal $timeRegistration)
  {
    $validated = $request->validate([
      'rejection_reason' => 'required|string|max:255'
    ]);

    $user = Auth::user();

    if (!($user->role === User::ROLE_ADMIN || $user->role === User::ROLE_MANAGER)) {
      return redirect()->back()->with('error', 'You do not have permission to reject time registrations');
    }

    $timeRegistration->update([
      'status' => 'rejected',
      'rejection_reason' => $validated['rejection_reason']
    ]);

    return redirect()->back()->with('success', 'Time registration rejected successfully');
  }
}
