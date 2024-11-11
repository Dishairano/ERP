<?php

namespace App\Http\Controllers;

use App\Models\ProjectTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectTemplateController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $templates = ProjectTemplate::with(['creator', 'updater'])
      ->orderBy('name')
      ->paginate(10);

    return view('projects.templates.index', compact('templates'));
  }

  public function create()
  {
    return view('projects.templates.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'default_phases' => 'nullable|array',
      'default_tasks' => 'nullable|array',
      'default_milestones' => 'nullable|array',
      'default_risks' => 'nullable|array',
      'default_team_structure' => 'nullable|array',
      'default_budget_allocation' => 'nullable|array',
      'is_active' => 'boolean'
    ]);

    $template = new ProjectTemplate($validated);
    $template->created_by = Auth::id();
    $template->updated_by = Auth::id();
    $template->save();

    return redirect()
      ->route('projects.templates.show', $template)
      ->with('success', 'Project template created successfully.');
  }

  public function show(ProjectTemplate $template)
  {
    $template->load(['creator', 'updater', 'projects']);
    return view('projects.templates.show', compact('template'));
  }

  public function edit(ProjectTemplate $template)
  {
    return view('projects.templates.edit', compact('template'));
  }

  public function update(Request $request, ProjectTemplate $template)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'default_phases' => 'nullable|array',
      'default_tasks' => 'nullable|array',
      'default_milestones' => 'nullable|array',
      'default_risks' => 'nullable|array',
      'default_team_structure' => 'nullable|array',
      'default_budget_allocation' => 'nullable|array',
      'is_active' => 'boolean'
    ]);

    $template->fill($validated);
    $template->updated_by = Auth::id();
    $template->save();

    return redirect()
      ->route('projects.templates.show', $template)
      ->with('success', 'Project template updated successfully.');
  }

  public function destroy(ProjectTemplate $template)
  {
    $template->delete();

    return redirect()
      ->route('projects.templates.index')
      ->with('success', 'Project template deleted successfully.');
  }

  public function duplicate(ProjectTemplate $template)
  {
    $newTemplate = $template->replicate();
    $newTemplate->name = $template->name . ' (Copy)';
    $newTemplate->created_by = Auth::id();
    $newTemplate->updated_by = Auth::id();
    $newTemplate->save();

    return redirect()
      ->route('projects.templates.edit', $newTemplate)
      ->with('success', 'Project template duplicated successfully.');
  }
}
