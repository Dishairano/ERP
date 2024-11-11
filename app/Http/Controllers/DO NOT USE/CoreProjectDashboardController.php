<?php

namespace App\Http\Controllers;

use App\Models\CoreProjectDashboardModal;
use App\Models\Task;
use App\Models\Risk;
use App\Models\TimeRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreProjectDashboardController extends Controller
{
  public function index()
  {
    $projects = CoreProjectDashboardModal::with(['manager', 'tasks', 'risks'])
      ->where('is_active', true)
      ->orderBy('priority', 'desc')
      ->get();

    $projectStats = [
      'total' => $projects->count(),
      'onTrack' => $projects->where('status', 'On Track')->count(),
      'delayed' => $projects->where('status', 'Delayed')->count(),
      'completed' => $projects->where('status', 'Completed')->count()
    ];

    $recentActivities = $this->getRecentActivities();
    $upcomingDeadlines = $this->getUpcomingDeadlines();
    $budgetOverview = $this->getBudgetOverview();
    $resourceUtilization = $this->getResourceUtilization();

    return view('core.projects.dashboard', compact(
      'projects',
      'projectStats',
      'recentActivities',
      'upcomingDeadlines',
      'budgetOverview',
      'resourceUtilization'
    ));
  }

  private function getRecentActivities()
  {
    return DB::table('activity_log')
      ->where('log_name', 'project')
      ->orderBy('created_at', 'desc')
      ->limit(10)
      ->get();
  }

  private function getUpcomingDeadlines()
  {
    $nextWeek = now()->addWeek();

    return Task::with('project')
      ->where('due_date', '<=', $nextWeek)
      ->where('status', '!=', 'completed')
      ->orderBy('due_date')
      ->limit(5)
      ->get();
  }

  private function getBudgetOverview()
  {
    $projects = CoreProjectDashboardModal::with('timeRegistrations')
      ->where('is_active', true)
      ->get();

    return [
      'totalBudget' => $projects->sum('budget'),
      'totalSpent' => $projects->sum(function ($project) {
        return $project->timeRegistrations->sum('cost');
      }),
      'projectsBudgets' => $projects->map(function ($project) {
        return [
          'name' => $project->name,
          'budget' => $project->budget,
          'spent' => $project->timeRegistrations->sum('cost')
        ];
      })
    ];
  }

  private function getResourceUtilization()
  {
    $timeRegistrations = TimeRegistration::with('user')
      ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
      ->get()
      ->groupBy('user_id');

    return $timeRegistrations->map(function ($userRegistrations) {
      $user = $userRegistrations->first()->user;
      $totalHours = $userRegistrations->sum('hours');
      return [
        'user' => $user->name,
        'hours' => $totalHours,
        'utilization' => ($totalHours / 40) * 100 // Assuming 40-hour work week
      ];
    });
  }

  public function projectDetails($id)
  {
    $project = CoreProjectDashboardModal::with([
      'manager',
      'client',
      'tasks',
      'risks',
      'team',
      'milestones',
      'documents',
      'timeRegistrations'
    ])->findOrFail($id);

    $taskStats = [
      'total' => $project->tasks->count(),
      'completed' => $project->tasks->where('status', 'completed')->count(),
      'inProgress' => $project->tasks->where('status', 'in_progress')->count(),
      'pending' => $project->tasks->where('status', 'pending')->count()
    ];

    $riskStats = [
      'total' => $project->risks->count(),
      'high' => $project->risks->where('severity', 'high')->count(),
      'medium' => $project->risks->where('severity', 'medium')->count(),
      'low' => $project->risks->where('severity', 'low')->count()
    ];

    return view('core.projects.details', compact('project', 'taskStats', 'riskStats'));
  }

  public function updateStatus(Request $request, $id)
  {
    $validated = $request->validate([
      'status' => 'required|in:On Track,Delayed,Completed,On Hold',
      'progress' => 'required|integer|min:0|max:100'
    ]);

    $project = CoreProjectDashboardModal::findOrFail($id);
    $project->update($validated);

    return redirect()->back()->with('success', 'Project status updated successfully');
  }

  public function updatePriority(Request $request, $id)
  {
    $validated = $request->validate([
      'priority' => 'required|in:High,Medium,Low'
    ]);

    $project = CoreProjectDashboardModal::findOrFail($id);
    $project->update($validated);

    return redirect()->back()->with('success', 'Project priority updated successfully');
  }
}
