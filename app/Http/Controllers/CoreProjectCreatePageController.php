<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CoreProjectModal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CoreProjectCreatePageController extends Controller
{
  public function index()
  {
    $users = User::all();
    return view('content.projects.create', compact('users'));
  }

  public function store(Request $request)
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

    // Convert dates to proper format
    $validated['start_date'] = Carbon::parse($validated['start_date']);
    $validated['end_date'] = Carbon::parse($validated['end_date']);

    // Ensure numeric values are properly formatted
    $validated['budget'] = (float) $validated['budget'];

    // Set initial progress to 0
    $validated['progress'] = 0;

    $project = CoreProjectModal::create($validated);

    return redirect()
      ->route('projects.show', $project)
      ->with('success', 'Project created successfully.');
  }
}
