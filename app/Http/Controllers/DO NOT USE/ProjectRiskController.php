<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectRiskController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $risks = ProjectRisk::with(['project', 'owner'])
      ->orderBy('probability', 'desc')
      ->orderBy('impact', 'desc')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $projects = Project::select('id', 'name')->orderBy('name')->get();
    $users = User::select('id', 'name')->orderBy('name')->get();

    return view('projects.risks.index', compact('risks', 'projects', 'users'));
  }

  public function create()
  {
    $projects = Project::select('id', 'name')->orderBy('name')->get();
    return view('projects.risks.create', compact('projects'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'priority' => 'nullable|string|in:low,medium,high',
      'status' => 'required|string|in:open,in_progress,mitigated,closed',
      'identified_date' => 'required|date',
      'resolution_date' => 'nullable|date|after_or_equal:identified_date',
      'mitigation_strategy' => 'nullable|string'
    ]);

    ProjectRisk::create([
      ...$validated,
      'created_by' => Auth::id()
    ]);

    return redirect()->route('projects.risks')
      ->with('success', 'Risk created successfully.');
  }

  public function show(ProjectRisk $risk)
  {
    $risk->load(['project', 'createdBy']);
    return view('projects.risks.show', compact('risk'));
  }

  public function edit(ProjectRisk $risk)
  {
    $projects = Project::select('id', 'name')->orderBy('name')->get();
    return view('projects.risks.edit', compact('risk', 'projects'));
  }

  public function update(Request $request, ProjectRisk $risk)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'priority' => 'nullable|string|in:low,medium,high',
      'status' => 'required|string|in:open,in_progress,mitigated,closed',
      'identified_date' => 'required|date',
      'resolution_date' => 'nullable|date|after_or_equal:identified_date',
      'mitigation_strategy' => 'nullable|string'
    ]);

    $risk->update($validated);

    return redirect()->route('projects.risks')
      ->with('success', 'Risk updated successfully.');
  }

  public function destroy(ProjectRisk $risk)
  {
    $risk->delete();

    return redirect()->route('projects.risks')
      ->with('success', 'Risk deleted successfully.');
  }

  public function matrix()
  {
    $risks = ProjectRisk::with(['project'])
      ->orderBy('priority', 'desc')
      ->orderBy('created_at', 'desc')
      ->get();

    return view('projects.risks.matrix', compact('risks'));
  }

  public function report()
  {
    $risks = ProjectRisk::with(['project'])
      ->orderBy('created_at', 'desc')
      ->get();

    return view('projects.risks.report', compact('risks'));
  }
}
