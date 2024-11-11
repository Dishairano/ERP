<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
  /**
   * Display the project dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function dashboard()
  {
    $projects = Project::all();
    return view('projects.dashboard', compact('projects'));
  }

  /**
   * Display a listing of the projects.
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    $projects = Project::all();
    return view('projects.index', compact('projects'));
  }

  /**
   * Show the form for creating a new project.
   *
   * @return \Illuminate\View\View
   */
  public function create()
  {
    return view('projects.create');
  }

  /**
   * Store a newly created project in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|string|in:planned,in_progress,completed,on_hold',
      'priority' => 'required|string|in:low,medium,high',
      'budget' => 'required|numeric|min:0',
    ]);

    Project::create($validatedData);

    return redirect()->route('projects.index')->with('success', 'Project created successfully.');
  }

  /**
   * Display the specified project.
   *
   * @param  \App\Models\Project  $project
   * @return \Illuminate\View\View
   */
  public function show(Project $project)
  {
    return view('projects.show', compact('project'));
  }

  /**
   * Show the form for editing the specified project.
   *
   * @param  \App\Models\Project  $project
   * @return \Illuminate\View\View
   */
  public function edit(Project $project)
  {
    return view('projects.edit', compact('project'));
  }

  /**
   * Update the specified project in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Project  $project
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, Project $project)
  {
    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|string|in:planned,in_progress,completed,on_hold',
      'priority' => 'required|string|in:low,medium,high',
      'budget' => 'required|numeric|min:0',
    ]);

    $project->update($validatedData);

    return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
  }

  /**
   * Remove the specified project from storage.
   *
   * @param  \App\Models\Project  $project
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Project $project)
  {
    $project->delete();

    return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
  }
}
