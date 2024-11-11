<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskManagementController extends Controller
{
  public function index(Request $request)
  {
    $query = Task::with(['project', 'assignedTo', 'creator'])
      ->when($request->project_id, function ($q) use ($request) {
        return $q->where('project_id', $request->project_id);
      })
      ->when($request->status, function ($q) use ($request) {
        return $q->where('status', $request->status);
      })
      ->when($request->priority, function ($q) use ($request) {
        return $q->where('priority', $request->priority);
      })
      ->when($request->assigned_to, function ($q) use ($request) {
        return $q->where('assigned_to', $request->assigned_to);
      });

    $tasks = $query->orderBy('due_date')->paginate(15);
    $projects = Project::select('id', 'name')->get();
    $users = User::select('id', 'name')->get();

    $taskStats = $this->getTaskStatistics();

    return view('projects.tasks.index', compact('tasks', 'projects', 'users', 'taskStats'));
  }

  protected function getTaskStatistics()
  {
    return [
      'total' => Task::count(),
      'completed' => Task::where('status', 'completed')->count(),
      'in_progress' => Task::where('status', 'in_progress')->count(),
      'overdue' => Task::where('due_date', '<', now())
        ->where('status', '!=', 'completed')
        ->count(),
      'high_priority' => Task::where('priority', 'high')
        ->where('status', '!=', 'completed')
        ->count(),
      'completion_rate' => $this->calculateCompletionRate(),
      'average_completion_time' => $this->calculateAverageCompletionTime()
    ];
  }

  protected function calculateCompletionRate()
  {
    $totalTasks = Task::count();
    if ($totalTasks === 0) return 0;

    $completedTasks = Task::where('status', 'completed')->count();
    return round(($completedTasks / $totalTasks) * 100, 2);
  }

  protected function calculateAverageCompletionTime()
  {
    return Task::where('status', 'completed')
      ->whereNotNull('completed_at')
      ->select(DB::raw('AVG(TIMESTAMPDIFF(DAY, created_at, completed_at)) as avg_days'))
      ->first()
      ->avg_days ?? 0;
  }

  public function create()
  {
    $projects = Project::select('id', 'name')->get();
    $users = User::select('id', 'name')->get();

    return view('projects.tasks.create', compact('projects', 'users'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'project_id' => 'required|exists:projects,id',
      'assigned_to' => 'required|exists:users,id',
      'priority' => 'required|in:low,medium,high',
      'status' => 'required|in:pending,in_progress,completed,on_hold',
      'due_date' => 'required|date|after:today',
      'estimated_hours' => 'required|numeric|min:0',
      'dependencies' => 'nullable|array',
      'dependencies.*' => 'exists:tasks,id'
    ]);

    $task = Task::create([
      ...$validated,
      'creator_id' => Auth::id(),
      'completed_at' => $validated['status'] === 'completed' ? now() : null
    ]);

    if (!empty($validated['dependencies'])) {
      $task->dependencies()->attach($validated['dependencies']);
    }

    return redirect()->route('projects.tasks.index')
      ->with('success', 'Task created successfully');
  }

  public function edit(Task $task)
  {
    $task->load(['project', 'assignedTo', 'dependencies']);
    $projects = Project::select('id', 'name')->get();
    $users = User::select('id', 'name')->get();
    $availableTasks = Task::where('id', '!=', $task->id)
      ->where('project_id', $task->project_id)
      ->get();

    return view('projects.tasks.edit', compact('task', 'projects', 'users', 'availableTasks'));
  }

  public function update(Request $request, Task $task)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'project_id' => 'required|exists:projects,id',
      'assigned_to' => 'required|exists:users,id',
      'priority' => 'required|in:low,medium,high',
      'status' => 'required|in:pending,in_progress,completed,on_hold',
      'due_date' => 'required|date',
      'estimated_hours' => 'required|numeric|min:0',
      'dependencies' => 'nullable|array',
      'dependencies.*' => 'exists:tasks,id'
    ]);

    // Update completed_at if status changes to completed
    if ($validated['status'] === 'completed' && $task->status !== 'completed') {
      $validated['completed_at'] = now();
    } elseif ($validated['status'] !== 'completed') {
      $validated['completed_at'] = null;
    }

    $task->update($validated);

    // Sync dependencies
    if (isset($validated['dependencies'])) {
      $task->dependencies()->sync($validated['dependencies']);
    }

    return redirect()->route('projects.tasks.index')
      ->with('success', 'Task updated successfully');
  }

  public function destroy(Task $task)
  {
    $task->dependencies()->detach();
    $task->delete();

    return redirect()->route('projects.tasks.index')
      ->with('success', 'Task deleted successfully');
  }

  public function updateStatus(Request $request, Task $task)
  {
    $validated = $request->validate([
      'status' => 'required|in:pending,in_progress,completed,on_hold'
    ]);

    $task->update([
      'status' => $validated['status'],
      'completed_at' => $validated['status'] === 'completed' ? now() : null
    ]);

    return response()->json(['message' => 'Task status updated successfully']);
  }

  public function getProjectTasks(Project $project)
  {
    $tasks = $project->tasks()
      ->with(['assignedTo'])
      ->get()
      ->map(function ($task) {
        return [
          'id' => $task->id,
          'name' => $task->name,
          'assigned_to' => $task->assignedTo->name,
          'status' => $task->status,
          'priority' => $task->priority,
          'due_date' => $task->due_date->format('Y-m-d'),
          'progress' => $task->progress
        ];
      });

    return response()->json($tasks);
  }

  public function getDependencyGraph(Task $task)
  {
    $nodes = collect([$task]);
    $edges = collect();

    $this->buildDependencyGraph($task, $nodes, $edges);

    return response()->json([
      'nodes' => $nodes->unique('id')->values(),
      'edges' => $edges->unique()->values()
    ]);
  }

  protected function buildDependencyGraph($task, &$nodes, &$edges, $depth = 0)
  {
    if ($depth > 5) return; // Prevent infinite recursion

    foreach ($task->dependencies as $dependency) {
      $nodes->push($dependency);
      $edges->push([
        'from' => $task->id,
        'to' => $dependency->id
      ]);

      $this->buildDependencyGraph($dependency, $nodes, $edges, $depth + 1);
    }
  }
}
