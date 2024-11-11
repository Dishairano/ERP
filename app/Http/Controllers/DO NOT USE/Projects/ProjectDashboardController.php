<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\ProjectRisk;
use App\Models\TimeRegistration;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectDashboardController extends Controller
{
  public function index()
  {
    $projectMetrics = $this->getProjectMetrics();
    $recentActivities = $this->getRecentActivities();
    $upcomingDeadlines = $this->getUpcomingDeadlines();
    $riskSummary = $this->getRiskSummary();
    $timeTracking = $this->getTimeTrackingSummary();

    return view('projects.dashboard', compact(
      'projectMetrics',
      'recentActivities',
      'upcomingDeadlines',
      'riskSummary',
      'timeTracking'
    ));
  }

  protected function getProjectMetrics()
  {
    return [
      'total' => Project::count(),
      'active' => Project::where('status', 'active')->count(),
      'completed' => Project::where('status', 'completed')->count(),
      'delayed' => Project::where('status', 'delayed')->count(),
      'completion_rate' => $this->calculateCompletionRate(),
      'budget_variance' => $this->calculateBudgetVariance(),
    ];
  }

  protected function calculateCompletionRate()
  {
    $totalTasks = Task::count();
    if ($totalTasks === 0) return 0;

    $completedTasks = Task::where('status', 'completed')->count();
    return round(($completedTasks / $totalTasks) * 100, 2);
  }

  protected function calculateBudgetVariance()
  {
    $projects = Project::with('timeRegistrations')->get();
    $totalVariance = 0;
    $projectCount = $projects->count();

    foreach ($projects as $project) {
      $plannedHours = $project->planned_hours ?? 0;
      $actualHours = $project->timeRegistrations->sum('hours') ?? 0;

      if ($plannedHours > 0) {
        $variance = (($actualHours - $plannedHours) / $plannedHours) * 100;
        $totalVariance += $variance;
      }
    }

    return $projectCount > 0 ? round($totalVariance / $projectCount, 2) : 0;
  }

  protected function getRecentActivities()
  {
    return Activity::with(['user', 'project', 'subject'])
      ->withinDays(7)
      ->recent()
      ->limit(10)
      ->get()
      ->map(function ($activity) {
        return [
          'description' => $activity->description,
          'user_name' => $activity->user->name,
          'created_at' => $activity->created_at,
          'type' => $activity->type,
          'icon_class' => $activity->getIconClass(),
          'project_name' => $activity->project ? $activity->project->name : null,
          'metadata' => $activity->metadata,
          'changes' => $activity->getFormattedChanges()
        ];
      });
  }

  protected function getUpcomingDeadlines()
  {
    return Task::with(['project', 'assignedTo'])
      ->where('due_date', '>=', now())
      ->where('due_date', '<=', now()->addDays(14))
      ->where('status', '!=', 'completed')
      ->orderBy('due_date')
      ->limit(5)
      ->get();
  }

  protected function getRiskSummary()
  {
    return [
      'high' => ProjectRisk::where('priority', 'high')->count(),
      'medium' => ProjectRisk::where('priority', 'medium')->count(),
      'low' => ProjectRisk::where('priority', 'low')->count(),
      'mitigated' => ProjectRisk::where('status', 'mitigated')->count(),
      'recent' => ProjectRisk::with(['project'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get()
    ];
  }

  protected function getTimeTrackingSummary()
  {
    $currentWeek = now()->startOfWeek();

    return [
      'weekly_total' => TimeRegistration::whereBetween('date', [
        $currentWeek,
        $currentWeek->copy()->endOfWeek()
      ])->sum('hours'),
      'pending_approvals' => TimeRegistration::where('status', 'pending')->count(),
      'project_distribution' => $this->getProjectTimeDistribution(),
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
}
