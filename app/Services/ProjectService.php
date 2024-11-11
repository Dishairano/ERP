<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectPhase;
use App\Models\ProjectTask;
use App\Models\ProjectRisk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectService
{
  /**
   * Create a new project with phases and initial setup
   */
  public function createProject(array $data): Project
  {
    DB::beginTransaction();
    try {
      // Generate project code
      $data['code'] = $this->generateProjectCode();

      // Create the project
      $project = Project::create($data);

      // Create default phases if provided
      if (isset($data['phases'])) {
        foreach ($data['phases'] as $index => $phase) {
          ProjectPhase::create([
            'project_id' => $project->id,
            'name' => $phase['name'],
            'description' => $phase['description'] ?? null,
            'start_date' => $phase['start_date'],
            'end_date' => $phase['end_date'],
            'order' => $index + 1,
            'status' => 'planned'
          ]);
        }
      }

      DB::commit();
      return $project;
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Update project and its related data
   */
  public function updateProject(Project $project, array $data): Project
  {
    DB::beginTransaction();
    try {
      $project->update($data);

      // Update phases if provided
      if (isset($data['phases'])) {
        $this->updateProjectPhases($project, $data['phases']);
      }

      DB::commit();
      return $project->fresh();
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Get project dashboard data
   */
  public function getDashboardData(Project $project): array
  {
    $project->load([
      'tasks' => function ($query) {
        $query->withCount(['assignments', 'dependencies']);
      },
      'risks',
      'phases.tasks'
    ]);

    return [
      'project' => $project,
      'tasksByStatus' => $this->getTasksByStatus($project),
      'tasksByPriority' => $this->getTasksByPriority($project),
      'phaseProgress' => $this->getPhaseProgress($project),
      'riskMatrix' => $this->generateRiskMatrix($project->risks),
      'timeline' => $this->generateTimeline($project),
      'metrics' => $this->calculateProjectMetrics($project)
    ];
  }

  /**
   * Generate project performance report
   */
  public function generatePerformanceReport(Project $project): array
  {
    $tasks = $project->tasks;
    $completedTasks = $tasks->where('status', 'completed');

    return [
      'progress' => $project->getProgress(),
      'budgetUtilization' => $project->getBudgetUtilization(),
      'taskCompletion' => [
        'total' => $tasks->count(),
        'completed' => $completedTasks->count(),
        'onTime' => $completedTasks->filter(function ($task) {
          return !$task->isOverdue();
        })->count()
      ],
      'timeMetrics' => [
        'estimatedHours' => $tasks->sum('estimated_hours'),
        'actualHours' => $tasks->sum('actual_hours'),
        'variance' => $tasks->sum('actual_hours') - $tasks->sum('estimated_hours')
      ],
      'riskMetrics' => [
        'total' => $project->risks->count(),
        'critical' => $project->risks->filter(function ($risk) {
          return $risk->getRiskScore() >= config('project.risks.critical_score_threshold');
        })->count(),
        'mitigated' => $project->risks->where('status', 'mitigated')->count()
      ]
    ];
  }

  /**
   * Calculate project health status
   */
  public function calculateProjectHealth(Project $project): string
  {
    $metrics = $this->calculateProjectMetrics($project);

    if ($metrics['overdueTasks'] > 3 || $metrics['budgetVariance'] < -20) {
      return 'critical';
    }

    if ($metrics['overdueTasks'] > 1 || $metrics['budgetVariance'] < -10) {
      return 'at_risk';
    }

    if ($metrics['progress'] < 80 && $project->end_date->isPast()) {
      return 'delayed';
    }

    return 'healthy';
  }

  /**
   * Private helper methods
   */
  private function generateProjectCode(): string
  {
    $prefix = config('project.code_prefix');
    $year = date(config('project.code_year_format'));
    $digits = config('project.code_sequence_digits');

    $lastProject = Project::whereYear('created_at', $year)
      ->orderBy('id', 'desc')
      ->first();

    $sequence = $lastProject ? intval(substr($lastProject->code, -$digits)) + 1 : 1;
    return $prefix . $year . str_pad($sequence, $digits, '0', STR_PAD_LEFT);
  }

  private function updateProjectPhases(Project $project, array $phases): void
  {
    $phaseIds = collect($phases)->pluck('id')->filter();
    $project->phases()->whereNotIn('id', $phaseIds)->delete();

    foreach ($phases as $index => $phaseData) {
      if (isset($phaseData['id'])) {
        $phase = ProjectPhase::find($phaseData['id']);
        $phase->update([
          'name' => $phaseData['name'],
          'description' => $phaseData['description'] ?? null,
          'start_date' => $phaseData['start_date'],
          'end_date' => $phaseData['end_date'],
          'order' => $index + 1
        ]);
      } else {
        ProjectPhase::create([
          'project_id' => $project->id,
          'name' => $phaseData['name'],
          'description' => $phaseData['description'] ?? null,
          'start_date' => $phaseData['start_date'],
          'end_date' => $phaseData['end_date'],
          'order' => $index + 1,
          'status' => 'planned'
        ]);
      }
    }
  }

  private function getTasksByStatus(Project $project): array
  {
    $statusConfig = config('project.tasks.statuses');
    $result = [];

    foreach ($statusConfig as $status => $config) {
      $tasks = $project->tasks->where('status', $status);
      $result[$status] = [
        'name' => $config['name'],
        'color' => $config['color'],
        'icon' => $config['icon'],
        'count' => $tasks->count(),
        'tasks' => $tasks
      ];
    }

    return $result;
  }

  private function getTasksByPriority(Project $project): array
  {
    $priorityConfig = config('project.tasks.priorities');
    $result = [];

    foreach ($priorityConfig as $priority => $config) {
      $tasks = $project->tasks->where('priority', $priority);
      $result[$priority] = [
        'name' => $config['name'],
        'color' => $config['color'],
        'icon' => $config['icon'],
        'count' => $tasks->count(),
        'tasks' => $tasks
      ];
    }

    return $result;
  }

  private function getPhaseProgress(Project $project): array
  {
    return $project->phases->map(function ($phase) {
      return [
        'name' => $phase->name,
        'progress' => $phase->getProgress(),
        'status' => $phase->status,
        'isOverdue' => $phase->isOverdue()
      ];
    })->toArray();
  }

  private function generateRiskMatrix($risks): array
  {
    $matrix = [
      'high' => ['high' => [], 'medium' => [], 'low' => []],
      'medium' => ['high' => [], 'medium' => [], 'low' => []],
      'low' => ['high' => [], 'medium' => [], 'low' => []]
    ];

    foreach ($risks as $risk) {
      $matrix[$risk->probability][$risk->impact][] = [
        'id' => $risk->id,
        'name' => $risk->name,
        'score' => $risk->getRiskScore(),
        'status' => $risk->status
      ];
    }

    return $matrix;
  }

  private function generateTimeline(Project $project): array
  {
    return $project->phases->map(function ($phase) {
      return [
        'name' => $phase->name,
        'start' => $phase->start_date->format('Y-m-d'),
        'end' => $phase->end_date->format('Y-m-d'),
        'progress' => $phase->getProgress(),
        'tasks' => $phase->tasks->map(function ($task) {
          return [
            'name' => $task->name,
            'start' => $task->start_date->format('Y-m-d'),
            'end' => $task->due_date->format('Y-m-d'),
            'progress' => $task->getProgress(),
            'dependencies' => $task->dependencies->pluck('dependent_task_id')
          ];
        })
      ];
    })->toArray();
  }

  private function calculateProjectMetrics(Project $project): array
  {
    $tasks = $project->tasks;
    $completedTasks = $tasks->where('status', 'completed');

    return [
      'progress' => $project->getProgress(),
      'budgetVariance' => $project->getBudgetUtilization() - 100,
      'overdueTasks' => $tasks->filter->isOverdue()->count(),
      'completionRate' => $tasks->count() > 0
        ? ($completedTasks->count() / $tasks->count()) * 100
        : 0,
      'estimatedVsActual' => $tasks->sum('estimated_hours') > 0
        ? ($tasks->sum('actual_hours') / $tasks->sum('estimated_hours')) * 100
        : 0
    ];
  }
}
