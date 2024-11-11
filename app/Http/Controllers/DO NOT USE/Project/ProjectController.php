<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use App\Models\Project\ProjectPhase;
use App\Models\Project\ProjectTask;
use App\Models\Project\ProjectRisk;
use App\Models\Project\ProjectDocument;
use App\Models\Project\ProjectFeedback;
use App\Models\Project\ProjectKpi;
use App\Models\Project\ProjectChange;
use App\Models\Project\ProjectNotification;
use App\Models\Project\ProjectTaskAssignment;
use App\Models\Project\ProjectTaskDependency;
use App\Models\Project\ProjectTimeRegistration;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
  public function index()
  {
    $projects = Project::select('projects.*')
      ->selectSub(
        ProjectTask::whereColumn('project_id', 'projects.id')
          ->whereNull('deleted_at')
          ->selectRaw('count(*)'),
        'tasks_count'
      )
      ->selectSub(
        ProjectPhase::whereColumn('project_id', 'projects.id')
          ->whereNull('deleted_at')
          ->selectRaw('count(*)'),
        'phases_count'
      )
      ->where('is_template', false)
      ->orderBy('created_at', 'desc')
      ->get();

    return view('projects.index', compact('projects'));
  }

  public function templates()
  {
    $templates = Project::select('projects.*')
      ->selectSub(
        ProjectTask::whereColumn('project_id', 'projects.id')
          ->whereNull('deleted_at')
          ->selectRaw('count(*)'),
        'tasks_count'
      )
      ->selectSub(
        ProjectPhase::whereColumn('project_id', 'projects.id')
          ->whereNull('deleted_at')
          ->selectRaw('count(*)'),
        'phases_count'
      )
      ->where('is_template', true)
      ->orderBy('created_at', 'desc')
      ->get();

    return view('projects.templates', compact('templates'));
  }

  public function dashboard()
  {
    $activeProjects = Project::where('status', 'in_progress')
      ->where('is_template', false)
      ->count();

    $completedProjects = Project::where('status', 'completed')
      ->where('is_template', false)
      ->count();

    $delayedProjects = Project::where('status', 'on_hold')
      ->where('is_template', false)
      ->count();

    // Calculate over budget projects using actual_cost column
    $overBudgetProjects = Project::where('is_template', false)
      ->whereRaw('actual_cost > budget')
      ->count();

    $upcomingDeadlines = ProjectTask::whereHas('project', function ($query) {
      $query->where('is_template', false);
    })
      ->where('end_date', '>=', now())
      ->where('end_date', '<=', now()->addDays(7))
      ->with('project')
      ->get();

    $recentActivities = ProjectChange::with(['project', 'user'])
      ->whereHas('project', function ($query) {
        $query->where('is_template', false);
      })
      ->orderBy('created_at', 'desc')
      ->limit(10)
      ->get();

    // Get project progress with actual cost calculation
    $projectProgress = Project::where('is_template', false)
      ->select('projects.*')
      ->selectRaw('(SELECT SUM(hours) FROM project_time_registrations WHERE project_id = projects.id) * 50 as actual_cost')
      ->get();

    // Calculate resource utilization
    $resourceUtilization = User::select('users.name as user')
      ->selectRaw('SUM(COALESCE(project_task_assignments.allocated_hours, 0)) as allocated_hours')
      ->selectRaw('SUM(COALESCE(project_time_registrations.hours, 0)) as actual_hours')
      ->leftJoin('project_task_assignments', 'users.id', '=', 'project_task_assignments.user_id')
      ->leftJoin('project_time_registrations', 'users.id', '=', 'project_time_registrations.user_id')
      ->groupBy('users.id', 'users.name')
      ->having('allocated_hours', '>', 0)
      ->get()
      ->map(function ($resource) {
        return [
          'user' => $resource->user,
          'allocated_hours' => $resource->allocated_hours,
          'actual_hours' => $resource->actual_hours
        ];
      });

    return view('projects.dashboard', compact(
      'activeProjects',
      'completedProjects',
      'delayedProjects',
      'overBudgetProjects',
      'upcomingDeadlines',
      'recentActivities',
      'projectProgress',
      'resourceUtilization'
    ));
  }

  public function createTemplate()
  {
    $clients = Customer::all();
    $managers = User::all();

    return view('projects.create-template', compact('clients', 'managers'));
  }

  public function storeTemplate(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'client_id' => 'required|exists:customers,id',
      'manager_id' => 'required|exists:users,id',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'budget' => 'required|numeric|min:0',
      'scope' => 'nullable|array',
      'status' => 'required|in:planned,in_progress,completed,on_hold'
    ]);

    // Set as template and initial progress to 0
    $validated['is_template'] = true;
    $validated['progress_percentage'] = 0;

    $template = Project::create($validated);

    return redirect()->route('projects.templates')
      ->with('success', 'Project template created successfully.');
  }

  public function convertToTemplate(Project $project)
  {
    // Create a new template from the existing project
    $template = $project->replicate();
    $template->is_template = true;
    $template->name = $project->name . ' (Template)';
    $template->save();

    // Copy phases
    foreach ($project->phases as $phase) {
      $newPhase = $phase->replicate();
      $newPhase->project_id = $template->id;
      $newPhase->save();
    }

    return redirect()->route('projects.templates')
      ->with('success', 'Project converted to template successfully.');
  }

  public function ganttChart(Project $project)
  {
    $project->load(['phases', 'tasks']);

    // Prepare tasks data for gantt chart
    $tasks = [];

    // Add project as the root task
    $tasks[] = [
      'id' => 'p' . $project->id,
      'text' => $project->name,
      'start_date' => $project->start_date->format('Y-m-d'),
      'duration' => $project->start_date->diffInDays($project->end_date),
      'progress' => $project->progress_percentage / 100,
      'type' => 'project'
    ];

    // Add phases
    foreach ($project->phases as $phase) {
      $tasks[] = [
        'id' => 'ph' . $phase->id,
        'text' => $phase->name,
        'start_date' => $phase->start_date->format('Y-m-d'),
        'duration' => $phase->start_date->diffInDays($phase->end_date),
        'parent' => 'p' . $project->id,
        'progress' => 0,
        'type' => 'phase'
      ];

      // Add tasks for this phase
      foreach ($project->tasks->where('phase_id', $phase->id) as $task) {
        $tasks[] = [
          'id' => 't' . $task->id,
          'text' => $task->name,
          'start_date' => $task->start_date->format('Y-m-d'),
          'duration' => $task->start_date->diffInDays($task->end_date),
          'parent' => 'ph' . $phase->id,
          'progress' => $task->progress_percentage / 100,
          'type' => 'task'
        ];
      }
    }

    // Add tasks without phases
    foreach ($project->tasks->whereNull('phase_id') as $task) {
      $tasks[] = [
        'id' => 't' . $task->id,
        'text' => $task->name,
        'start_date' => $task->start_date->format('Y-m-d'),
        'duration' => $task->start_date->diffInDays($task->end_date),
        'parent' => 'p' . $project->id,
        'progress' => $task->progress_percentage / 100,
        'type' => 'task'
      ];
    }

    return view('projects.gantt', compact('project', 'tasks'));
  }

  public function create()
  {
    $clients = Customer::all();
    $managers = User::all();
    $templates = Project::where('is_template', true)->get();

    return view('projects.create', compact('clients', 'managers', 'templates'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'client_id' => 'required|exists:customers,id',
      'manager_id' => 'required|exists:users,id',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'budget' => 'required|numeric|min:0',
      'scope' => 'nullable|array',
      'template_id' => 'nullable|exists:projects,id,is_template,1',
      'status' => 'required|in:planned,in_progress,completed,on_hold'
    ]);

    // If a template is selected, use it as a base
    if ($request->filled('template_id')) {
      $template = Project::findOrFail($request->template_id);
      $validated = array_merge([
        'scope' => $template->scope,
      ], $validated);
    }

    // Set initial progress to 0 and ensure it's not a template
    $validated['progress_percentage'] = 0;
    $validated['is_template'] = false;

    // Create the project
    $project = Project::create($validated);

    // If using a template, copy its phases
    if ($request->filled('template_id')) {
      $template = Project::findOrFail($request->template_id);
      foreach ($template->phases as $phase) {
        $project->phases()->create([
          'name' => $phase->name,
          'description' => $phase->description,
          'start_date' => $project->start_date,
          'end_date' => $project->end_date,
          'status' => 'planned'
        ]);
      }
    }

    return redirect()->route('projects.show', $project)
      ->with('success', 'Project created successfully.');
  }

  public function show(Project $project)
  {
    $project->load([
      'phases',
      'tasks',
      'risks',
      'documents',
      'feedback',
      'kpis',
      'changes',
      'notifications',
      'client',
      'manager'
    ]);

    return view('projects.show', compact('project'));
  }

  public function edit(Project $project)
  {
    $clients = Customer::all();
    $managers = User::all();
    $templates = Project::where('is_template', true)->where('id', '!=', $project->id)->get();

    return view('projects.edit', compact('project', 'clients', 'managers', 'templates'));
  }

  public function update(Request $request, Project $project)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'client_id' => 'required|exists:customers,id',
      'manager_id' => 'required|exists:users,id',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'budget' => 'required|numeric|min:0',
      'scope' => 'nullable|array',
      'status' => 'required|in:planned,in_progress,completed,on_hold'
    ]);

    $project->update($validated);

    return redirect()->route('projects.show', $project)
      ->with('success', 'Project updated successfully.');
  }

  public function destroy(Project $project)
  {
    $project->delete();
    return redirect()->route('projects.index')
      ->with('success', 'Project deleted successfully.');
  }

  public function addPhase(Request $request, Project $project)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|in:planned,in_progress,completed'
    ]);

    $phase = $project->phases()->create($validated);

    return redirect()->route('projects.show', $project)
      ->with('success', 'Phase added successfully.');
  }

  public function addTask(Request $request, Project $project)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'phase_id' => 'nullable|exists:project_phases,id',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'estimated_hours' => 'required|integer|min:1',
      'priority' => 'required|integer|min:1|max:5',
      'status' => 'required|string'
    ]);

    $task = $project->tasks()->create($validated);

    if ($request->has('assigned_users')) {
      foreach ($request->assigned_users as $userId) {
        ProjectTaskAssignment::create([
          'task_id' => $task->id,
          'user_id' => $userId,
          'allocated_hours' => $request->allocated_hours[$userId] ?? null
        ]);
      }
    }

    return redirect()->route('projects.show', $project)
      ->with('success', 'Task added successfully.');
  }

  public function addRisk(Request $request, Project $project)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'status' => 'required|string',
      'probability' => 'required|integer|min:1|max:5',
      'impact' => 'required|integer|min:1|max:5',
      'mitigation_strategy' => 'nullable|string',
      'owner_id' => 'required|exists:users,id',
      'identification_date' => 'required|date'
    ]);

    $project->risks()->create($validated);

    return redirect()->route('projects.show', $project)
      ->with('success', 'Risk added successfully.');
  }

  public function uploadDocument(Request $request, Project $project)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'file' => 'required|file|max:10240',
      'phase_id' => 'nullable|exists:project_phases,id',
      'task_id' => 'nullable|exists:project_tasks,id',
      'version' => 'required|string'
    ]);

    $path = $request->file('file')->store('project-documents');

    $project->documents()->create([
      'name' => $validated['name'],
      'file_path' => $path,
      'file_type' => $request->file('file')->getClientOriginalExtension(),
      'file_size' => $request->file('file')->getSize(),
      'version' => $validated['version'],
      'phase_id' => $validated['phase_id'] ?? null,
      'task_id' => $validated['task_id'] ?? null,
      'uploaded_by' => Auth::id()
    ]);

    return redirect()->route('projects.show', $project)
      ->with('success', 'Document uploaded successfully.');
  }
}
