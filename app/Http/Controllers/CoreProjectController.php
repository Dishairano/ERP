<?php

namespace App\Http\Controllers;

use App\Models\CoreProjectModal;
use App\Models\CoreProjectTaskModal;
use App\Models\CoreProjectRiskModal;
use App\Models\CoreProjectTemplateModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoreProjectController extends Controller
{
  public function dashboard()
  {
    $projects = CoreProjectModal::with(['tasks'])
      ->get()
      ->map(function ($project) {
        return [
          'id' => $project->id,
          'name' => $project->name,
          'progress' => $project->progress,
          'tasks_completed' => $project->tasks->where('status', 'completed')->count(),
          'tasks_total' => $project->tasks->count(),
          'budget_spent' => $project->budget_spent,
          'budget_total' => $project->budget,
          'status' => $project->status,
          'priority' => $project->priority
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
    $projectPerformance = CoreProjectModal::with('tasks')
      ->get()
      ->map(function ($project) {
        return [
          'name' => $project->name,
          'progress' => $project->progress,
          'budget_performance' => $project->budget > 0 ? ($project->budget_spent / $project->budget) * 100 : 0,
          'schedule_performance' => $this->calculateSchedulePerformance($project),
          'task_completion_rate' => $project->tasks->count() > 0
            ? ($project->tasks->where('status', 'completed')->count() / $project->tasks->count()) * 100
            : 0
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

  private function calculateSchedulePerformance($project)
  {
    $totalDuration = $project->end_date->diffInDays($project->start_date);
    $elapsedDuration = now()->diffInDays($project->start_date);

    if ($totalDuration == 0) {
      return 100;
    }

    $plannedProgress = ($elapsedDuration / $totalDuration) * 100;
    $actualProgress = $project->progress;

    if ($plannedProgress == 0) {
      return 100;
    }

    return ($actualProgress / $plannedProgress) * 100;
  }
}
