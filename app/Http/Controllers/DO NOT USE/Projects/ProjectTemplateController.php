<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\ProjectTemplate;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectTemplateController extends Controller
{
  public function index()
  {
    $templates = ProjectTemplate::withCount(['projects', 'tasks'])
      ->with(['creator', 'lastModifiedBy'])
      ->orderBy('name')
      ->paginate(10);

    $templateStats = $this->getTemplateStats();

    return view('projects.templates.index', compact('templates', 'templateStats'));
  }

  protected function getTemplateStats()
  {
    return [
      'total' => ProjectTemplate::count(),
      'active' => ProjectTemplate::where('is_active', true)->count(),
      'usage_count' => Project::whereNotNull('template_id')->count(),
      'avg_tasks' => ProjectTemplate::withCount('tasks')
        ->having('tasks_count', '>', 0)
        ->avg('tasks_count') ?? 0,
      'most_used' => ProjectTemplate::withCount('projects')
        ->orderByDesc('projects_count')
        ->first(),
      'recent_templates' => ProjectTemplate::with(['creator'])
        ->latest()
        ->take(5)
        ->get()
    ];
  }

  public function create()
  {
    return view('projects.templates.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'estimated_duration' => 'required|integer|min:1',
      'duration_unit' => 'required|in:days,weeks,months',
      'category' => 'required|string|max:50',
      'is_active' => 'boolean',
      'tasks' => 'required|array|min:1',
      'tasks.*.name' => 'required|string|max:255',
      'tasks.*.description' => 'required|string',
      'tasks.*.estimated_hours' => 'required|numeric|min:0',
      'tasks.*.order' => 'required|integer|min:0',
      'risks' => 'array',
      'risks.*.title' => 'required|string|max:255',
      'risks.*.description' => 'required|string',
      'risks.*.priority' => 'required|in:low,medium,high',
      'risks.*.probability' => 'required|integer|between:1,10',
      'risks.*.impact' => 'required|integer|between:1,10',
      'milestones' => 'array',
      'milestones.*.name' => 'required|string|max:255',
      'milestones.*.description' => 'required|string',
      'milestones.*.due_day' => 'required|integer|min:1'
    ]);

    DB::beginTransaction();

    try {
      $template = ProjectTemplate::create([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'estimated_duration' => $validated['estimated_duration'],
        'duration_unit' => $validated['duration_unit'],
        'category' => $validated['category'],
        'is_active' => $validated['is_active'] ?? true,
        'created_by' => Auth::id(),
        'last_modified_by' => Auth::id()
      ]);

      // Create template tasks
      foreach ($validated['tasks'] as $taskData) {
        $template->tasks()->create($taskData);
      }

      // Create template risks if any
      if (!empty($validated['risks'])) {
        foreach ($validated['risks'] as $riskData) {
          $template->risks()->create($riskData);
        }
      }

      // Create template milestones if any
      if (!empty($validated['milestones'])) {
        foreach ($validated['milestones'] as $milestoneData) {
          $template->milestones()->create($milestoneData);
        }
      }

      DB::commit();

      return redirect()->route('projects.templates.index')
        ->with('success', 'Project template created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withInput()
        ->with('error', 'Failed to create project template: ' . $e->getMessage());
    }
  }

  public function show(ProjectTemplate $template)
  {
    $template->load(['tasks', 'risks', 'milestones', 'creator', 'lastModifiedBy']);
    $usageStats = $this->getTemplateUsageStats($template);

    return view('projects.templates.show', compact('template', 'usageStats'));
  }

  protected function getTemplateUsageStats(ProjectTemplate $template)
  {
    $projects = $template->projects()
      ->with(['tasks', 'risks'])
      ->get();

    return [
      'total_projects' => $projects->count(),
      'avg_completion_time' => $this->calculateAvgCompletionTime($projects),
      'success_rate' => $this->calculateSuccessRate($projects),
      'task_completion_rates' => $this->calculateTaskCompletionRates($template, $projects),
      'risk_occurrence_rates' => $this->calculateRiskOccurrenceRates($template, $projects)
    ];
  }

  protected function calculateAvgCompletionTime($projects)
  {
    $completedProjects = $projects->filter(function ($project) {
      return $project->status === 'completed' && $project->completed_at;
    });

    if ($completedProjects->isEmpty()) {
      return 0;
    }

    $totalDays = $completedProjects->sum(function ($project) {
      return $project->completed_at->diffInDays($project->start_date);
    });

    return round($totalDays / $completedProjects->count(), 1);
  }

  protected function calculateSuccessRate($projects)
  {
    if ($projects->isEmpty()) {
      return 0;
    }

    $successfulProjects = $projects->filter(function ($project) {
      return $project->status === 'completed' && !$project->is_delayed;
    })->count();

    return round(($successfulProjects / $projects->count()) * 100, 1);
  }

  protected function calculateTaskCompletionRates(ProjectTemplate $template, $projects)
  {
    $templateTasks = $template->tasks;
    $rates = [];

    foreach ($templateTasks as $templateTask) {
      $totalOccurrences = 0;
      $totalCompletions = 0;
      $avgDuration = 0;

      foreach ($projects as $project) {
        $matchingTask = $project->tasks
          ->where('name', $templateTask->name)
          ->first();

        if ($matchingTask) {
          $totalOccurrences++;
          if ($matchingTask->status === 'completed') {
            $totalCompletions++;
            $avgDuration += $matchingTask->actual_hours ?? 0;
          }
        }
      }

      $rates[$templateTask->name] = [
        'completion_rate' => $totalOccurrences > 0 ?
          round(($totalCompletions / $totalOccurrences) * 100, 1) : 0,
        'avg_duration' => $totalCompletions > 0 ?
          round($avgDuration / $totalCompletions, 1) : 0
      ];
    }

    return $rates;
  }

  protected function calculateRiskOccurrenceRates(ProjectTemplate $template, $projects)
  {
    $templateRisks = $template->risks;
    $rates = [];

    foreach ($templateRisks as $templateRisk) {
      $totalOccurrences = 0;
      $totalMitigated = 0;

      foreach ($projects as $project) {
        $matchingRisk = $project->risks
          ->where('title', $templateRisk->title)
          ->first();

        if ($matchingRisk) {
          $totalOccurrences++;
          if ($matchingRisk->status === 'mitigated') {
            $totalMitigated++;
          }
        }
      }

      $rates[$templateRisk->title] = [
        'occurrence_rate' => $projects->count() > 0 ?
          round(($totalOccurrences / $projects->count()) * 100, 1) : 0,
        'mitigation_rate' => $totalOccurrences > 0 ?
          round(($totalMitigated / $totalOccurrences) * 100, 1) : 0
      ];
    }

    return $rates;
  }

  public function edit(ProjectTemplate $template)
  {
    $template->load(['tasks', 'risks', 'milestones']);
    return view('projects.templates.edit', compact('template'));
  }

  public function update(Request $request, ProjectTemplate $template)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'estimated_duration' => 'required|integer|min:1',
      'duration_unit' => 'required|in:days,weeks,months',
      'category' => 'required|string|max:50',
      'is_active' => 'boolean',
      'tasks' => 'required|array|min:1',
      'tasks.*.id' => 'sometimes|exists:template_tasks,id',
      'tasks.*.name' => 'required|string|max:255',
      'tasks.*.description' => 'required|string',
      'tasks.*.estimated_hours' => 'required|numeric|min:0',
      'tasks.*.order' => 'required|integer|min:0',
      'risks' => 'array',
      'risks.*.id' => 'sometimes|exists:template_risks,id',
      'risks.*.title' => 'required|string|max:255',
      'risks.*.description' => 'required|string',
      'risks.*.priority' => 'required|in:low,medium,high',
      'risks.*.probability' => 'required|integer|between:1,10',
      'risks.*.impact' => 'required|integer|between:1,10',
      'milestones' => 'array',
      'milestones.*.id' => 'sometimes|exists:template_milestones,id',
      'milestones.*.name' => 'required|string|max:255',
      'milestones.*.description' => 'required|string',
      'milestones.*.due_day' => 'required|integer|min:1'
    ]);

    DB::beginTransaction();

    try {
      $template->update([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'estimated_duration' => $validated['estimated_duration'],
        'duration_unit' => $validated['duration_unit'],
        'category' => $validated['category'],
        'is_active' => $validated['is_active'] ?? true,
        'last_modified_by' => Auth::id()
      ]);

      // Update tasks
      $this->syncTemplateTasks($template, $validated['tasks']);

      // Update risks
      if (isset($validated['risks'])) {
        $this->syncTemplateRisks($template, $validated['risks']);
      }

      // Update milestones
      if (isset($validated['milestones'])) {
        $this->syncTemplateMilestones($template, $validated['milestones']);
      }

      DB::commit();

      return redirect()->route('projects.templates.index')
        ->with('success', 'Project template updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withInput()
        ->with('error', 'Failed to update project template: ' . $e->getMessage());
    }
  }

  protected function syncTemplateTasks(ProjectTemplate $template, array $tasks)
  {
    $existingIds = [];

    foreach ($tasks as $taskData) {
      if (isset($taskData['id'])) {
        $task = $template->tasks()->find($taskData['id']);
        if ($task) {
          $task->update($taskData);
          $existingIds[] = $task->id;
        }
      } else {
        $task = $template->tasks()->create($taskData);
        $existingIds[] = $task->id;
      }
    }

    // Remove tasks that weren't included in the update
    $template->tasks()->whereNotIn('id', $existingIds)->delete();
  }

  protected function syncTemplateRisks(ProjectTemplate $template, array $risks)
  {
    $existingIds = [];

    foreach ($risks as $riskData) {
      if (isset($riskData['id'])) {
        $risk = $template->risks()->find($riskData['id']);
        if ($risk) {
          $risk->update($riskData);
          $existingIds[] = $risk->id;
        }
      } else {
        $risk = $template->risks()->create($riskData);
        $existingIds[] = $risk->id;
      }
    }

    $template->risks()->whereNotIn('id', $existingIds)->delete();
  }

  protected function syncTemplateMilestones(ProjectTemplate $template, array $milestones)
  {
    $existingIds = [];

    foreach ($milestones as $milestoneData) {
      if (isset($milestoneData['id'])) {
        $milestone = $template->milestones()->find($milestoneData['id']);
        if ($milestone) {
          $milestone->update($milestoneData);
          $existingIds[] = $milestone->id;
        }
      } else {
        $milestone = $template->milestones()->create($milestoneData);
        $existingIds[] = $milestone->id;
      }
    }

    $template->milestones()->whereNotIn('id', $existingIds)->delete();
  }

  public function destroy(ProjectTemplate $template)
  {
    if ($template->projects()->exists()) {
      return back()->with('error', 'Cannot delete template that is being used by projects');
    }

    DB::beginTransaction();

    try {
      $template->tasks()->delete();
      $template->risks()->delete();
      $template->milestones()->delete();
      $template->delete();

      DB::commit();

      return redirect()->route('projects.templates.index')
        ->with('success', 'Project template deleted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Failed to delete project template');
    }
  }
}
