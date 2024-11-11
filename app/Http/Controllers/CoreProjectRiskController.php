<?php

namespace App\Http\Controllers;

use App\Models\CoreProjectModal;
use App\Models\CoreProjectRiskModal;
use Illuminate\Http\Request;

class CoreProjectRiskController extends Controller
{
  public function index(CoreProjectModal $project)
  {
    $risks = $project->risks()->with('project')->paginate(10);
    return view('content.projects.risks.index', compact('project', 'risks'));
  }

  public function create(CoreProjectModal $project)
  {
    return view('content.projects.risks.create', compact('project'));
  }

  public function store(Request $request, CoreProjectModal $project)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'category' => 'required|string|max:50',
      'severity' => 'required|integer|min:1|max:5',
      'likelihood' => 'required|integer|min:1|max:5',
      'impact' => 'required|string',
      'mitigation_strategy' => 'required|string',
      'status' => 'required|string|in:identified,assessed,mitigated,closed',
      'due_date' => 'required|date',
      'owner' => 'required|string|max:255'
    ]);

    $validated['project_id'] = $project->id;
    $risk = CoreProjectRiskModal::create($validated);

    return redirect()
      ->route('projects.risks.show', [$project->id, $risk->id])
      ->with('success', 'Risk created successfully');
  }

  public function show(CoreProjectModal $project, CoreProjectRiskModal $risk)
  {
    if ($risk->project_id !== $project->id) {
      abort(404);
    }

    return view('content.projects.risks.show', compact('project', 'risk'));
  }

  public function edit(CoreProjectModal $project, CoreProjectRiskModal $risk)
  {
    if ($risk->project_id !== $project->id) {
      abort(404);
    }

    return view('content.projects.risks.edit', compact('project', 'risk'));
  }

  public function update(Request $request, CoreProjectModal $project, CoreProjectRiskModal $risk)
  {
    if ($risk->project_id !== $project->id) {
      abort(404);
    }

    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'category' => 'required|string|max:50',
      'severity' => 'required|integer|min:1|max:5',
      'likelihood' => 'required|integer|min:1|max:5',
      'impact' => 'required|string',
      'mitigation_strategy' => 'required|string',
      'status' => 'required|string|in:identified,assessed,mitigated,closed',
      'due_date' => 'required|date',
      'owner' => 'required|string|max:255'
    ]);

    $risk->update($validated);

    return redirect()
      ->route('projects.risks.show', [$project->id, $risk->id])
      ->with('success', 'Risk updated successfully');
  }

  public function destroy(CoreProjectModal $project, CoreProjectRiskModal $risk)
  {
    if ($risk->project_id !== $project->id) {
      abort(404);
    }

    $risk->delete();

    return redirect()
      ->route('projects.risks.index', $project->id)
      ->with('success', 'Risk deleted successfully');
  }

  public function matrix(CoreProjectModal $project)
  {
    $risks = $project->risks()
      ->get()
      ->groupBy(function ($risk) {
        return $risk->severity . '-' . $risk->likelihood;
      });

    return view('content.projects.risks.matrix', compact('project', 'risks'));
  }

  public function report(CoreProjectModal $project)
  {
    $risks = $project->risks;
    $risksByCategory = $risks->groupBy('category');
    $risksByStatus = $risks->groupBy('status');
    $highPriorityRisks = $risks->filter(function ($risk) {
      return $risk->severity * $risk->likelihood >= 16;
    });

    return view('content.projects.risks.report', compact(
      'project',
      'risksByCategory',
      'risksByStatus',
      'highPriorityRisks'
    ));
  }

  public function overallReport()
  {
    return view('content.projects.risks.report');
  }
}
