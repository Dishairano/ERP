<?php

namespace App\Http\Controllers;

use App\Models\TimeTimeRegistrationCreateModal;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TimeTimeRegistrationCreateController extends Controller
{
  public function create()
  {
    $user = Auth::user();

    // Get active projects the user is assigned to
    $projects = Project::active()
      ->whereHas('team', function ($query) use ($user) {
        $query->where('user_id', $user->id);
      })
      ->orWhere('manager_id', $user->id)
      ->get();

    // Get recent time registrations for quick fill
    $recentRegistrations = TimeTimeRegistrationCreateModal::where('user_id', $user->id)
      ->orderBy('created_at', 'desc')
      ->limit(5)
      ->get();

    return view('time.registration.create', compact('projects', 'recentRegistrations'));
  }

  public function store(Request $request)
  {
    $user = Auth::user();

    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'task_id' => 'required|exists:tasks,id',
      'date' => 'required|date|before_or_equal:today',
      'start_time' => 'required|date_format:H:i',
      'end_time' => 'required|date_format:H:i|after:start_time',
      'description' => 'required|string|max:500',
      'billable' => 'boolean'
    ]);

    // Check for time overlaps
    if (TimeTimeRegistrationCreateModal::validateTimeOverlap(
      $user->id,
      $validated['date'],
      $validated['start_time'],
      $validated['end_time']
    )) {
      return redirect()->back()
        ->withInput()
        ->with('error', 'The selected time period overlaps with an existing registration');
    }

    // Calculate duration
    $duration = TimeTimeRegistrationCreateModal::calculateDuration(
      $validated['start_time'],
      $validated['end_time']
    );

    // Get default rates
    $rates = TimeTimeRegistrationCreateModal::getDefaultRates($validated['project_id'], $user->id);

    // Create time registration
    TimeTimeRegistrationCreateModal::create([
      ...$validated,
      'user_id' => $user->id,
      'duration' => $duration,
      'status' => 'pending',
      'cost_rate' => $rates['cost_rate'],
      'bill_rate' => $rates['bill_rate'],
      'billable' => $validated['billable'] ?? $rates['billable']
    ]);

    return redirect()->route('time-registration.overview')
      ->with('success', 'Time registration created successfully');
  }

  public function getTasks(Request $request)
  {
    $projectId = $request->input('project_id');

    $tasks = Task::where('project_id', $projectId)
      ->where('status', '!=', 'completed')
      ->get(['id', 'name', 'status']);

    return response()->json($tasks);
  }

  public function quickFill(Request $request)
  {
    $registrationId = $request->input('registration_id');

    $registration = TimeTimeRegistrationCreateModal::findOrFail($registrationId);

    // Return only necessary fields for quick fill
    return response()->json([
      'project_id' => $registration->project_id,
      'task_id' => $registration->task_id,
      'description' => $registration->description,
      'billable' => $registration->billable
    ]);
  }

  public function validateTime(Request $request)
  {
    $user = Auth::user();

    $validated = $request->validate([
      'date' => 'required|date',
      'start_time' => 'required|date_format:H:i',
      'end_time' => 'required|date_format:H:i|after:start_time'
    ]);

    $hasOverlap = TimeTimeRegistrationCreateModal::validateTimeOverlap(
      $user->id,
      $validated['date'],
      $validated['start_time'],
      $validated['end_time']
    );

    return response()->json(['has_overlap' => $hasOverlap]);
  }
}
