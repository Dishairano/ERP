<?php

namespace App\Http\Controllers;

use App\Models\ProjectTimeTracking;
use App\Models\ResourceAllocation;
use App\Models\TimeBudget;
use App\Models\TimeAnalysis;
use Illuminate\Http\Request;

class ProjectTimeController extends Controller
{
  /**
   * Display time tracking.
   *
   * @return \Illuminate\View\View
   */
  public function tracking()
  {
    $timeEntries = ProjectTimeTracking::with(['project', 'task', 'user'])
      ->latest()
      ->paginate(10);

    return view('project-time.tracking', compact('timeEntries'));
  }

  /**
   * Display resource allocation.
   *
   * @return \Illuminate\View\View
   */
  public function allocation()
  {
    $allocations = ResourceAllocation::with(['project', 'resource'])
      ->latest()
      ->paginate(10);

    return view('project-time.allocation', compact('allocations'));
  }

  /**
   * Display time budgets.
   *
   * @return \Illuminate\View\View
   */
  public function budgets()
  {
    $budgets = TimeBudget::with(['project', 'tasks'])
      ->latest()
      ->paginate(10);

    return view('project-time.budgets', compact('budgets'));
  }

  /**
   * Display time analysis.
   *
   * @return \Illuminate\View\View
   */
  public function analysis()
  {
    $analyses = TimeAnalysis::with(['project'])
      ->latest()
      ->paginate(10);

    return view('project-time.analysis', compact('analyses'));
  }

  /**
   * Store a new time tracking entry.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeTracking(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'task_id' => 'required|exists:project_tasks,id',
      'start_time' => 'required|date',
      'end_time' => 'required|date|after:start_time',
      'description' => 'nullable|string',
      'billable' => 'required|boolean'
    ]);

    ProjectTimeTracking::create([
      ...$validated,
      'user_id' => $request->user()->id,
      'duration' => now()->parse($validated['end_time'])->diffInMinutes($validated['start_time'])
    ]);

    return redirect()->route('project-time.tracking')
      ->with('success', 'Time entry created successfully.');
  }

  /**
   * Store a new resource allocation.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeAllocation(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'resource_id' => 'required|exists:resources,id',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'hours_per_day' => 'required|numeric|min:0|max:24',
      'role' => 'required|string',
      'notes' => 'nullable|string'
    ]);

    ResourceAllocation::create([
      ...$validated,
      'status' => 'pending',
      'created_by' => $request->user()->id
    ]);

    return redirect()->route('project-time.allocation')
      ->with('success', 'Resource allocation created successfully.');
  }

  /**
   * Store a new time budget.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeBudget(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'total_hours' => 'required|numeric|min:0',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'task_budgets' => 'required|array',
      'task_budgets.*.task_id' => 'required|exists:project_tasks,id',
      'task_budgets.*.hours' => 'required|numeric|min:0'
    ]);

    TimeBudget::create([
      ...$validated,
      'status' => 'draft',
      'created_by' => $request->user()->id
    ]);

    return redirect()->route('project-time.budgets')
      ->with('success', 'Time budget created successfully.');
  }

  /**
   * Store a new time analysis.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeAnalysis(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'period' => 'required|string',
      'metrics' => 'required|array',
      'parameters' => 'required|array',
      'notes' => 'nullable|string'
    ]);

    TimeAnalysis::create([
      ...$validated,
      'created_by' => $request->user()->id
    ]);

    return redirect()->route('project-time.analysis')
      ->with('success', 'Time analysis created successfully.');
  }
}
