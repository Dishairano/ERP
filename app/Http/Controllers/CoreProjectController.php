<?php

namespace App\Http\Controllers;

use App\Models\CoreProjectModal;
use App\Models\CoreProjectTaskModal;
use App\Models\CoreProjectRiskModal;
use App\Models\CoreProjectDashboardModal;
use App\Models\CoreProjectTemplateModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoreProjectController extends Controller
{
  public function dashboard()
  {
    $projects = CoreProjectDashboardModal::with(['project', 'tasks'])
      ->get()
      ->map(function ($dashboard) {
        return [
          'name' => $dashboard->project->name,
          'progress' => $dashboard->progress_percentage,
          'tasks_completed' => $dashboard->completed_tasks,
          'tasks_total' => $dashboard->total_tasks,
          'budget_spent' => $dashboard->budget_spent,
          'budget_total' => $dashboard->budget_allocated,
          'status' => $dashboard->status,
          'priority' => $dashboard->priority,
          'upcoming_milestones' => $dashboard->upcoming_milestones
        ];
      });

    $tasksByStatus = CoreProjectTaskModal::select('status', DB::raw('count(*) as count'))
      ->groupBy('status')
      ->get();

    $upcomingDeadlines = CoreProjectTaskModal::where('due_date', '>=', now())
      ->where('due_date', '<=', now()->addDays(7))
      ->orderBy('due_date')
      ->limit(5)
      ->get();

    return view('content.projects.dashboard', compact('projects', 'tasksByStatus', 'upcomingDeadlines'));
  }

  public function taskList()
  {
    $tasks = CoreProjectTaskModal::with(['project', 'assignedTo'])
      ->orderBy('due_date')
      ->paginate(10);

    return view('content.projects.tasks.index', compact('tasks'));
  }

  public function createTask()
  {
    $projects = CoreProjectModal::all();
    return view('content.projects.tasks.create', compact('projects'));
  }

  public function storeTask(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'assigned_to' => 'required|exists:users,id',
      'due_date' => 'required|date',
      'priority' => 'required|in:low,medium,high',
      'estimated_hours' => 'required|integer|min:1'
    ]);

    CoreProjectTaskModal::create($validated);

    return redirect()->route('projects.tasks')->with('success', 'Task created successfully');
  }

  public function performance()
  {
    $projectPerformance = CoreProjectDashboardModal::with('project')
      ->get()
      ->map(function ($dashboard) {
        return [
          'name' => $dashboard->project->name,
          'progress' => $dashboard->progress_percentage,
          'budget_performance' => ($dashboard->budget_spent / $dashboard->budget_allocated) * 100,
          'schedule_performance' => $this->calculateSchedulePerformance($dashboard),
          'task_completion_rate' => ($dashboard->completed_tasks / max($dashboard->total_tasks, 1)) * 100
        ];
      });

    $teamPerformance = DB::table('project_tasks')
      ->join('users', 'project_tasks.assigned_to', '=', 'users.id')
      ->select(
        'users.name',
        DB::raw('count(*) as total_tasks'),
        DB::raw('sum(case when status = "completed" then 1 else 0 end) as completed_tasks'),
        DB::raw('avg(case when actual_hours > 0 then actual_hours/estimated_hours else null end) as efficiency_ratio')
      )
      ->groupBy('users.id', 'users.name')
      ->get();

    return view('content.projects.performance', compact('projectPerformance', 'teamPerformance'));
  }

  public function riskMatrix()
  {
    $risks = CoreProjectRiskModal::with('project')
      ->get()
      ->groupBy(function ($risk) {
        return $risk->impact . '-' . $risk->probability;
      });

    $matrix = [
      'high' => ['high' => [], 'medium' => [], 'low' => []],
      'medium' => ['high' => [], 'medium' => [], 'low' => []],
      'low' => ['high' => [], 'medium' => [], 'low' => []]
    ];

    foreach ($risks as $key => $riskGroup) {
      list($impact, $probability) = explode('-', $key);
      $matrix[$impact][$probability] = $riskGroup;
    }

    return view('content.projects.risks.matrix', compact('matrix'));
  }

  public function riskReport()
  {
    $risks = CoreProjectRiskModal::with(['project'])
      ->get()
      ->groupBy('project.name');

    $riskTrends = DB::table('project_risks')
      ->select(
        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
        DB::raw('count(*) as total_risks'),
        DB::raw('sum(case when status = "mitigated" then 1 else 0 end) as mitigated_risks')
      )
      ->groupBy('month')
      ->orderBy('month')
      ->get();

    $risksByImpact = CoreProjectRiskModal::select('impact', DB::raw('count(*) as count'))
      ->groupBy('impact')
      ->get();

    return view('content.projects.risks.report', compact('risks', 'riskTrends', 'risksByImpact'));
  }

  private function calculateSchedulePerformance($dashboard)
  {
    $plannedProgress = $dashboard->project->planned_progress_percentage ?? 0;
    $actualProgress = $dashboard->progress_percentage;

    if ($plannedProgress == 0) {
      return 100;
    }

    return ($actualProgress / $plannedProgress) * 100;
  }
}
