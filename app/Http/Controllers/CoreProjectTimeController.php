<?php

namespace App\Http\Controllers;

use App\Models\CoreProjectTimeEntryModal;
use App\Models\CoreProjectModal;
use App\Models\CoreProjectTaskModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreProjectTimeController extends Controller
{
  public function tracking()
  {
    $user = Auth::user();
    $projects = CoreProjectModal::active()
      ->with(['tasks' => function ($query) use ($user) {
        $query->where('assignee_id', $user->id);
      }])
      ->get();

    $timeEntries = CoreProjectTimeEntryModal::forUser($user->id)
      ->with(['project', 'user'])
      ->orderBy('date', 'desc')
      ->paginate(10);

    return view('content.project-time.tracking', compact('projects', 'timeEntries'));
  }

  public function storeTracking(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'date' => 'required|date',
      'hours' => 'required|numeric|min:0.1|max:24',
      'activity_type' => 'required|string',
      'description' => 'nullable|string|max:500',
      'billable_hours' => 'nullable|numeric|min:0|max:24',
      'rate' => 'nullable|numeric|min:0'
    ]);

    $timeEntry = new CoreProjectTimeEntryModal($validated);
    $timeEntry->user_id = Auth::id();
    $timeEntry->save();

    return redirect()->route('project-time.tracking')
      ->with('success', 'Time entry recorded successfully.');
  }

  public function allocation()
  {
    $projects = CoreProjectModal::active()
      ->with(['timeEntries', 'timeBudget'])
      ->get()
      ->map(function ($project) {
        return [
          'id' => $project->id,
          'name' => $project->name,
          'allocated_hours' => $project->timeBudget?->allocated_hours ?? 0,
          'used_hours' => $project->timeEntries->sum('hours'),
          'remaining_hours' => ($project->timeBudget?->allocated_hours ?? 0) - $project->timeEntries->sum('hours')
        ];
      });

    return view('content.project-time.allocation', compact('projects'));
  }

  public function storeAllocation(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'allocated_hours' => 'required|numeric|min:0',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date'
    ]);

    DB::transaction(function () use ($validated) {
      $project = CoreProjectModal::findOrFail($validated['project_id']);

      $project->timeBudget()->updateOrCreate(
        ['project_id' => $project->id],
        [
          'allocated_hours' => $validated['allocated_hours'],
          'start_date' => $validated['start_date'],
          'end_date' => $validated['end_date']
        ]
      );
    });

    return redirect()->route('project-time.allocation')
      ->with('success', 'Time allocation updated successfully.');
  }

  public function budgets()
  {
    $projects = CoreProjectModal::active()
      ->with(['timeBudget', 'timeEntries'])
      ->get();

    return view('content.project-time.budgets', compact('projects'));
  }

  public function storeBudget(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'allocated_hours' => 'required|numeric|min:0',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date'
    ]);

    DB::transaction(function () use ($validated) {
      $project = CoreProjectModal::findOrFail($validated['project_id']);

      $project->timeBudget()->updateOrCreate(
        ['project_id' => $project->id],
        [
          'allocated_hours' => $validated['allocated_hours'],
          'start_date' => $validated['start_date'],
          'end_date' => $validated['end_date']
        ]
      );
    });

    return redirect()->route('project-time.budgets')
      ->with('success', 'Time budget updated successfully.');
  }

  public function analysis()
  {
    $projects = CoreProjectModal::active()
      ->with(['timeEntries', 'timeBudget'])
      ->get();

    $analysis = [
      'total_hours' => $projects->sum(function ($project) {
        return $project->timeEntries->sum('hours');
      }),
      'total_billable_hours' => $projects->sum(function ($project) {
        return $project->timeEntries->sum('billable_hours');
      }),
      'total_allocated_hours' => $projects->sum(function ($project) {
        return $project->timeBudget?->allocated_hours ?? 0;
      }),
      'project_breakdown' => $projects->map(function ($project) {
        return [
          'name' => $project->name,
          'total_hours' => $project->timeEntries->sum('hours'),
          'billable_hours' => $project->timeEntries->sum('billable_hours'),
          'allocated_hours' => $project->timeBudget?->allocated_hours ?? 0,
          'utilization' => $project->timeBudget?->allocated_hours
            ? round(($project->timeEntries->sum('hours') / $project->timeBudget->allocated_hours) * 100, 2)
            : 0
        ];
      })
    ];

    return view('content.project-time.analysis', compact('analysis'));
  }
}
