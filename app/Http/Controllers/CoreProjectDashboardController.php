<?php

namespace App\Http\Controllers;

use App\Models\CoreProjectDashboardModal;
use App\Models\Project;
use App\Models\Task;
use App\Models\Risk;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoreProjectDashboardController extends Controller
{
  public function index()
  {
    $dashboards = CoreProjectDashboardModal::with(['project', 'tasks', 'risks', 'milestones'])
      ->orderBy('created_at', 'desc')
      ->get();

    $projectStatistics = [
      'total' => $dashboards->count(),
      'on_track' => $dashboards->where('status', 'on_track')->count(),
      'at_risk' => $dashboards->where('status', 'at_risk')->count(),
      'delayed' => $dashboards->where('status', 'delayed')->count(),
      'completed' => $dashboards->where('status', 'completed')->count()
    ];

    $budgetOverview = [
      'total_allocated' => $dashboards->sum('budget_allocated'),
      'total_spent' => $dashboards->sum('budget_spent'),
      'total_remaining' => $dashboards->sum('budget_remaining')
    ];

    return view('core.projects.dashboard', compact(
      'dashboards',
      'projectStatistics',
      'budgetOverview'
    ));
  }

  public function show(Project $project)
  {
    $dashboard = CoreProjectDashboardModal::with(['project', 'tasks', 'risks', 'milestones'])
      ->where('project_id', $project->id)
      ->firstOrFail();

    $taskBreakdown = [
      'total' => $dashboard->total_tasks,
      'completed' => $dashboard->completed_tasks,
      'pending' => $dashboard->pending_tasks,
      'overdue' => $dashboard->overdue_tasks
    ];

    $timelineStatus = $dashboard->getTimelineStatus();
    $budgetStatus = $dashboard->calculateBudgetStatus();

    return view('core.projects.dashboard-detail', compact(
      'dashboard',
      'taskBreakdown',
      'timelineStatus',
      'budgetStatus'
    ));
  }

  public function update(Request $request, CoreProjectDashboardModal $dashboard)
  {
    $validated = $request->validate([
      'total_tasks' => 'sometimes|integer|min:0',
      'completed_tasks' => 'sometimes|integer|min:0',
      'pending_tasks' => 'sometimes|integer|min:0',
      'overdue_tasks' => 'sometimes|integer|min:0',
      'progress_percentage' => 'sometimes|numeric|min:0|max:100',
      'budget_allocated' => 'sometimes|numeric|min:0',
      'budget_spent' => 'sometimes|numeric|min:0',
      'budget_remaining' => 'sometimes|numeric|min:0',
      'status' => 'sometimes|string|in:pending,on_track,at_risk,delayed,completed',
      'priority' => 'sometimes|string|in:low,medium,high,critical',
      'team_members' => 'sometimes|array',
      'recent_activities' => 'sometimes|array',
      'upcoming_milestones' => 'sometimes|array',
      'risk_summary' => 'sometimes|array'
    ]);

    $dashboard->update($validated);

    return response()->json([
      'message' => 'Project dashboard updated successfully',
      'data' => $dashboard
    ]);
  }

  public function refreshMetrics(Project $project)
  {
    $dashboard = CoreProjectDashboardModal::where('project_id', $project->id)->firstOrFail();

    DB::transaction(function () use ($dashboard, $project) {
      // Update task metrics
      $taskMetrics = Task::where('project_id', $project->id)
        ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = "overdue" THEN 1 ELSE 0 END) as overdue
                ')
        ->first();

      // Update budget metrics
      $budgetMetrics = DB::table('time_registrations')
        ->where('project_id', $project->id)
        ->selectRaw('
                    SUM(hours * rate) as total_spent
                ')
        ->first();

      // Update dashboard
      $dashboard->update([
        'total_tasks' => $taskMetrics->total ?? 0,
        'completed_tasks' => $taskMetrics->completed ?? 0,
        'pending_tasks' => $taskMetrics->pending ?? 0,
        'overdue_tasks' => $taskMetrics->overdue ?? 0,
        'progress_percentage' => $taskMetrics->total > 0
          ? ($taskMetrics->completed / $taskMetrics->total) * 100
          : 0,
        'budget_spent' => $budgetMetrics->total_spent ?? 0,
        'budget_remaining' => $dashboard->budget_allocated - ($budgetMetrics->total_spent ?? 0)
      ]);

      // Update recent activities
      $recentActivities = Task::where('project_id', $project->id)
        ->orderBy('updated_at', 'desc')
        ->limit(5)
        ->get()
        ->map(function ($task) {
          return [
            'type' => 'task',
            'description' => $task->name,
            'status' => $task->status,
            'date' => $task->updated_at
          ];
        })
        ->toArray();

      $dashboard->update([
        'recent_activities' => $recentActivities
      ]);

      // Update upcoming milestones
      $upcomingMilestones = Milestone::where('project_id', $project->id)
        ->where('due_date', '>', now())
        ->orderBy('due_date', 'asc')
        ->limit(5)
        ->get()
        ->map(function ($milestone) {
          return [
            'name' => $milestone->name,
            'due_date' => $milestone->due_date,
            'status' => $milestone->status
          ];
        })
        ->toArray();

      $dashboard->update([
        'upcoming_milestones' => $upcomingMilestones
      ]);

      // Update risk summary
      $riskSummary = Risk::where('project_id', $project->id)
        ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN severity = "high" THEN 1 ELSE 0 END) as high_risks,
                    SUM(CASE WHEN severity = "medium" THEN 1 ELSE 0 END) as medium_risks,
                    SUM(CASE WHEN severity = "low" THEN 1 ELSE 0 END) as low_risks
                ')
        ->first();

      $dashboard->update([
        'risk_summary' => [
          'total' => $riskSummary->total ?? 0,
          'high' => $riskSummary->high_risks ?? 0,
          'medium' => $riskSummary->medium_risks ?? 0,
          'low' => $riskSummary->low_risks ?? 0
        ]
      ]);
    });

    return response()->json([
      'message' => 'Project metrics refreshed successfully',
      'data' => $dashboard
    ]);
  }

  public function getProjectTimeline(Project $project)
  {
    $milestones = Milestone::where('project_id', $project->id)
      ->orderBy('due_date', 'asc')
      ->get()
      ->map(function ($milestone) {
        return [
          'id' => $milestone->id,
          'name' => $milestone->name,
          'start_date' => $milestone->start_date,
          'due_date' => $milestone->due_date,
          'status' => $milestone->status,
          'progress' => $milestone->progress
        ];
      });

    return response()->json([
      'data' => $milestones
    ]);
  }

  public function getTeamPerformance(Project $project)
  {
    $teamPerformance = DB::table('time_registrations')
      ->join('users', 'time_registrations.user_id', '=', 'users.id')
      ->where('project_id', $project->id)
      ->groupBy('users.id', 'users.name')
      ->select(
        'users.id',
        'users.name',
        DB::raw('SUM(hours) as total_hours'),
        DB::raw('COUNT(DISTINCT task_id) as tasks_completed')
      )
      ->get();

    return response()->json([
      'data' => $teamPerformance
    ]);
  }
}
