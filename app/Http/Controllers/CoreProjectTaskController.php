<?php

namespace App\Http\Controllers;

use App\Models\CoreProjectModal;
use App\Models\CoreProjectTaskModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoreProjectTaskController extends Controller
{
  public function index(CoreProjectModal $project)
  {
    $tasks = CoreProjectTaskModal::where('project_id', $project->id)
      ->with(['assignedTo'])
      ->paginate(10);

    return view('content.projects.tasks.index', compact('project', 'tasks'));
  }

  public function create(CoreProjectModal $project)
  {
    return view('content.projects.tasks.create', compact('project'));
  }

  public function store(Request $request, CoreProjectModal $project)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'assigned_to' => 'required|exists:users,id',
      'due_date' => 'required|date',
      'priority' => 'required|in:low,medium,high',
      'estimated_hours' => 'required|integer|min:1'
    ]);

    $validated['project_id'] = $project->id;
    $task = CoreProjectTaskModal::create($validated);

    return redirect()
      ->route('projects.tasks.show', [$project->id, $task->id])
      ->with('success', 'Task created successfully');
  }

  public function show(CoreProjectModal $project, CoreProjectTaskModal $task)
  {
    if ($task->project_id !== $project->id) {
      abort(404);
    }

    return view('content.projects.tasks.show', compact('project', 'task'));
  }

  public function edit(CoreProjectModal $project, CoreProjectTaskModal $task)
  {
    if ($task->project_id !== $project->id) {
      abort(404);
    }

    return view('content.projects.tasks.edit', compact('project', 'task'));
  }

  public function update(Request $request, CoreProjectModal $project, CoreProjectTaskModal $task)
  {
    if ($task->project_id !== $project->id) {
      abort(404);
    }

    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'assigned_to' => 'required|exists:users,id',
      'due_date' => 'required|date',
      'priority' => 'required|in:low,medium,high',
      'estimated_hours' => 'required|integer|min:1',
      'actual_hours' => 'nullable|integer|min:0',
      'status' => 'required|in:pending,in_progress,completed,cancelled'
    ]);

    $task->update($validated);

    return redirect()
      ->route('projects.tasks.show', [$project->id, $task->id])
      ->with('success', 'Task updated successfully');
  }

  public function destroy(CoreProjectModal $project, CoreProjectTaskModal $task)
  {
    if ($task->project_id !== $project->id) {
      abort(404);
    }

    $task->delete();

    return redirect()
      ->route('projects.tasks.index', $project->id)
      ->with('success', 'Task deleted successfully');
  }

  public function addComment(Request $request, CoreProjectModal $project, CoreProjectTaskModal $task)
  {
    if ($task->project_id !== $project->id) {
      abort(404);
    }

    $validated = $request->validate([
      'comment' => 'required|string'
    ]);

    $comments = $task->comments ?? [];
    $comments[] = [
      'user_id' => Auth::id(),
      'comment' => $validated['comment'],
      'created_at' => now()->toDateTimeString()
    ];

    $task->update(['comments' => $comments]);

    return redirect()
      ->route('projects.tasks.show', [$project->id, $task->id])
      ->with('success', 'Comment added successfully');
  }

  public function addAttachment(Request $request, CoreProjectModal $project, CoreProjectTaskModal $task)
  {
    if ($task->project_id !== $project->id) {
      abort(404);
    }

    $validated = $request->validate([
      'attachment' => 'required|file|max:10240'
    ]);

    $path = $request->file('attachment')->store('task-attachments');

    $attachments = $task->attachments ?? [];
    $attachments[] = [
      'user_id' => Auth::id(),
      'file_path' => $path,
      'file_name' => $request->file('attachment')->getClientOriginalName(),
      'file_size' => $request->file('attachment')->getSize(),
      'uploaded_at' => now()->toDateTimeString()
    ];

    $task->update(['attachments' => $attachments]);

    return redirect()
      ->route('projects.tasks.show', [$project->id, $task->id])
      ->with('success', 'Attachment added successfully');
  }

  public function updateProgress(Request $request, CoreProjectModal $project, CoreProjectTaskModal $task)
  {
    if ($task->project_id !== $project->id) {
      abort(404);
    }

    $validated = $request->validate([
      'actual_hours' => 'required|integer|min:0',
      'completion_notes' => 'nullable|string'
    ]);

    $task->update($validated);

    return redirect()
      ->route('projects.tasks.show', [$project->id, $task->id])
      ->with('success', 'Progress updated successfully');
  }
}
