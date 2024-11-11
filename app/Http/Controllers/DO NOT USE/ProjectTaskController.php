<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AccessControlList;

class ProjectTaskController extends Controller
{
  public function __construct()
  {
    $this->middleware('role:admin,project_manager,team_member');
  }

  public function index(Project $project)
  {
    try {
      // If user is not admin, only show tasks they're assigned to
      if (!Auth::user()->hasRole('admin')) {
        $tasks = $project->tasks()->whereHas('assignments', function ($query) {
          $query->where('user_id', Auth::id());
        })->orderBy('due_date')
          ->orderBy('priority', 'desc')
          ->get();
      } else {
        $tasks = $project->tasks()->orderBy('due_date')
          ->orderBy('priority', 'desc')
          ->get();
      }

      $users = $project->taskAssignments()->distinct('user_id')->pluck('user_id');

      return view('projects.tasks.index', compact('tasks', 'project', 'users'));
    } catch (\Exception $e) {
      return back()->with('error', 'Error loading tasks: ' . $e->getMessage());
    }
  }

  public function create(Project $project)
  {
    // Only admin and project manager can create tasks
    if (!Auth::user()->hasAnyRole(['admin', 'project_manager'])) {
      return redirect()->route('projects.tasks.index', $project)
        ->with('error', 'You do not have permission to create tasks.');
    }

    return view('projects.tasks.create', compact('project'));
  }

  public function store(Request $request, Project $project)
  {
    // Only admin and project manager can create tasks
    if (!Auth::user()->hasAnyRole(['admin', 'project_manager'])) {
      return redirect()->route('projects.tasks.index', $project)
        ->with('error', 'You do not have permission to create tasks.');
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'due_date' => 'required|date',
      'priority' => 'required|in:low,medium,high,critical',
      'status' => 'required|in:todo,in-progress,review,completed',
      'phase_id' => 'nullable|exists:project_phases,id',
      'assigned_to' => 'nullable|array|exists:users,id'
    ]);

    try {
      $task = $project->tasks()->create($validated);

      if (!empty($validated['assigned_to'])) {
        $task->assignments()->sync($validated['assigned_to']);
      }

      return redirect()
        ->route('projects.tasks.index', $project)
        ->with('success', 'Task created successfully.');
    } catch (\Exception $e) {
      return back()
        ->withInput()
        ->with('error', 'Failed to create task: ' . $e->getMessage());
    }
  }

  public function edit(Project $project, ProjectTask $task)
  {
    // Only admin, project manager, or assigned team member can edit tasks
    if (
      !Auth::user()->hasAnyRole(['admin', 'project_manager']) &&
      !$task->assignments()->where('user_id', Auth::id())->exists()
    ) {
      return redirect()->route('projects.tasks.index', $project)
        ->with('error', 'You do not have permission to edit this task.');
    }

    return view('projects.tasks.edit', compact('project', 'task'));
  }

  public function update(Request $request, Project $project, ProjectTask $task)
  {
    // Only admin, project manager, or assigned team member can update tasks
    if (
      !Auth::user()->hasAnyRole(['admin', 'project_manager']) &&
      !$task->assignments()->where('user_id', Auth::id())->exists()
    ) {
      return redirect()->route('projects.tasks.index', $project)
        ->with('error', 'You do not have permission to update this task.');
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'due_date' => 'required|date',
      'priority' => 'required|in:low,medium,high,critical',
      'status' => 'required|in:todo,in-progress,review,completed',
      'phase_id' => 'nullable|exists:project_phases,id',
      'assigned_to' => 'nullable|array|exists:users,id'
    ]);

    try {
      $task->update($validated);

      if (!empty($validated['assigned_to'])) {
        $task->assignments()->sync($validated['assigned_to']);
      }

      return redirect()
        ->route('projects.tasks.index', $project)
        ->with('success', 'Task updated successfully.');
    } catch (\Exception $e) {
      return back()
        ->withInput()
        ->with('error', 'Failed to update task: ' . $e->getMessage());
    }
  }

  public function destroy(Project $project, ProjectTask $task)
  {
    // Only admin and project manager can delete tasks
    if (!Auth::user()->hasAnyRole(['admin', 'project_manager'])) {
      return redirect()->route('projects.tasks.index', $project)
        ->with('error', 'You do not have permission to delete tasks.');
    }

    try {
      $task->delete();

      return redirect()
        ->route('projects.tasks.index', $project)
        ->with('success', 'Task deleted successfully.');
    } catch (\Exception $e) {
      return back()
        ->with('error', 'Failed to delete task: ' . $e->getMessage());
    }
  }
}
