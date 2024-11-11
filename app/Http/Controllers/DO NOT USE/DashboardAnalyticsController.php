<?php

namespace App\Http\Controllers;

use App\Models\ProjectRisk;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsController extends Controller
{
  public function index()
  {
    // Get high priority risks using the priority attribute
    $highPriorityRisks = ProjectRisk::whereHas('project')
      ->where('priority', 'high')
      ->take(5)
      ->get();

    $projectMetrics = $this->getProjectMetrics();
    $timeMetrics = $this->getTimeMetrics();
    $riskMetrics = $this->getRiskMetrics();

    return view('dashboard.analytics.index', compact(
      'projectMetrics',
      'timeMetrics',
      'riskMetrics',
      'highPriorityRisks'
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
      'budget_variance' => $this->calculateBudgetVariance()
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

  protected function getTimeMetrics()
  {
    $currentWeek = now()->startOfWeek();
    $lastWeek = now()->subWeek()->startOfWeek();

    return [
      'weekly_total' => TimeRegistration::whereBetween('date', [
        $currentWeek,
        $currentWeek->copy()->endOfWeek()
      ])->sum('hours'),
      'weekly_variance' => $this->calculateWeeklyVariance($currentWeek, $lastWeek),
      'pending_approvals' => TimeRegistration::where('status', 'pending')->count(),
      'project_distribution' => $this->getProjectTimeDistribution()
    ];
  }

  protected function calculateWeeklyVariance($currentWeek, $lastWeek)
  {
    $currentWeekHours = TimeRegistration::whereBetween('date', [
      $currentWeek,
      $currentWeek->copy()->endOfWeek()
    ])->sum('hours');

    $lastWeekHours = TimeRegistration::whereBetween('date', [
      $lastWeek,
      $lastWeek->copy()->endOfWeek()
    ])->sum('hours');

    if ($lastWeekHours === 0) return 0;

    return round((($currentWeekHours - $lastWeekHours) / $lastWeekHours) * 100, 2);
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

  protected function getRiskMetrics()
  {
    return [
      'total' => ProjectRisk::count(),
      'high_priority' => ProjectRisk::where('priority', 'high')->count(),
      'medium_priority' => ProjectRisk::where('priority', 'medium')->count(),
      'low_priority' => ProjectRisk::where('priority', 'low')->count(),
      'mitigated' => ProjectRisk::where('status', 'mitigated')->count(),
      'risk_trend' => $this->calculateRiskTrend()
    ];
  }

  protected function calculateRiskTrend()
  {
    $currentMonth = now()->startOfMonth();
    $lastMonth = now()->subMonth()->startOfMonth();

    $currentMonthRisks = ProjectRisk::where('created_at', '>=', $currentMonth)->count();
    $lastMonthRisks = ProjectRisk::whereBetween('created_at', [
      $lastMonth,
      $currentMonth
    ])->count();

    if ($lastMonthRisks === 0) return 0;

    return round((($currentMonthRisks - $lastMonthRisks) / $lastMonthRisks) * 100, 2);
  }
}
