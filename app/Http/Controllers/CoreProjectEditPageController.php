<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CoreProjectModal;
use Illuminate\Http\Request;

class CoreProjectEditPageController extends Controller
{
  public function edit(CoreProjectModal $project)
  {
    $users = User::all();
    return view('content.projects.edit', compact('project', 'users'));
  }

  public function update(Request $request, CoreProjectModal $project)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'manager_id' => 'required|exists:users,id',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|in:planning,active,on-hold,completed,cancelled',
      'priority' => 'required|in:low,medium,high',
      'budget' => 'required|numeric|min:0'
    ]);

    $project->update($validated);

    return redirect()
      ->route('projects.show', $project)
      ->with('success', 'Project updated successfully.');
  }

  public function destroy(CoreProjectModal $project)
  {
    $project->delete();

    return redirect()
      ->route('projects.index')
      ->with('success', 'Project deleted successfully.');
  }
}
