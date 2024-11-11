<?php

namespace App\Http\Controllers;

use App\Models\CoreProjectTemplateModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoreProjectTemplateController extends Controller
{
  public function index()
  {
    $templates = CoreProjectTemplateModal::orderBy('name')
      ->paginate(10);

    return view('core.projects.templates.index', compact('templates'));
  }

  public function create()
  {
    return view('core.projects.templates.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'category' => 'required|string|max:50',
      'estimated_duration' => 'required|integer|min:1',
      'duration_unit' => 'required|string|in:days,weeks,months',
      'default_tasks' => 'required|array',
      'default_tasks.*.name' => 'required|string|max:255',
      'default_tasks.*.description' => 'required|string',
      'default_tasks.*.duration' => 'required|integer|min:1',
      'default_tasks.*.duration_unit' => 'required|string|in:hours,days,weeks',
      'default_tasks.*.priority' => 'required|string|in:low,medium,high',
      'default_milestones' => 'required|array',
      'default_milestones.*.name' => 'required|string|max:255',
      'default_milestones.*.description' => 'required|string',
      'default_milestones.*.due_day' => 'required|integer|min:1',
      'status' => 'required|string|in:active,archived',
      'tags' => 'nullable|array',
      'tags.*' => 'string|max:50'
    ]);

    $template = CoreProjectTemplateModal::create($validated);

    return redirect()
      ->route('projects.templates.show', $template)
      ->with('success', 'Project template created successfully');
  }

  public function show(CoreProjectTemplateModal $template)
  {
    return view('core.projects.templates.show', compact('template'));
  }

  public function edit(CoreProjectTemplateModal $template)
  {
    return view('core.projects.templates.edit', compact('template'));
  }

  public function update(Request $request, CoreProjectTemplateModal $template)
  {
    $validated = $request->validate([
      'name' => 'sometimes|string|max:255',
      'description' => 'sometimes|string',
      'category' => 'sometimes|string|max:50',
      'estimated_duration' => 'sometimes|integer|min:1',
      'duration_unit' => 'sometimes|string|in:days,weeks,months',
      'default_tasks' => 'sometimes|array',
      'default_tasks.*.name' => 'required|string|max:255',
      'default_tasks.*.description' => 'required|string',
      'default_tasks.*.duration' => 'required|integer|min:1',
      'default_tasks.*.duration_unit' => 'required|string|in:hours,days,weeks',
      'default_tasks.*.priority' => 'required|string|in:low,medium,high',
      'default_milestones' => 'sometimes|array',
      'default_milestones.*.name' => 'required|string|max:255',
      'default_milestones.*.description' => 'required|string',
      'default_milestones.*.due_day' => 'required|integer|min:1',
      'status' => 'sometimes|string|in:active,archived',
      'tags' => 'nullable|array',
      'tags.*' => 'string|max:50'
    ]);

    $template->update($validated);

    return redirect()
      ->route('projects.templates.show', $template)
      ->with('success', 'Project template updated successfully');
  }

  public function destroy(CoreProjectTemplateModal $template)
  {
    $template->delete();

    return redirect()
      ->route('projects.templates.index')
      ->with('success', 'Project template deleted successfully');
  }

  public function duplicate(CoreProjectTemplateModal $template)
  {
    $newTemplate = $template->replicate();
    $newTemplate->name = $template->name . ' (Copy)';
    $newTemplate->save();

    return redirect()
      ->route('projects.templates.edit', $newTemplate)
      ->with('success', 'Project template duplicated successfully');
  }

  public function archive(CoreProjectTemplateModal $template)
  {
    $template->update(['status' => 'archived']);

    return redirect()
      ->route('projects.templates.index')
      ->with('success', 'Project template archived successfully');
  }

  public function restore(CoreProjectTemplateModal $template)
  {
    $template->update(['status' => 'active']);

    return redirect()
      ->route('projects.templates.index')
      ->with('success', 'Project template restored successfully');
  }
}
