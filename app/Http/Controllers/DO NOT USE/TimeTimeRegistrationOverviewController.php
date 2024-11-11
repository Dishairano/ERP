<?php

namespace App\Http\Controllers;

use App\Models\TimeTimeRegistrationOverviewModal;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TimeTimeRegistrationOverviewController extends Controller
{
  public function index(Request $request)
  {
    $user = Auth::user();

    // Get filter parameters
    $startDate = $request->input('start_date', Carbon::now()->startOfWeek());
    $endDate = $request->input('end_date', Carbon::now()->endOfWeek());
    $projectId = $request->input('project_id');
    $status = $request->input('status');

    // Base query
    $query = TimeTimeRegistrationOverviewModal::with(['project', 'task'])
      ->forUser($user->id)
      ->forPeriod($startDate, $endDate);

    // Apply filters
    if ($projectId) {
      $query->forProject($projectId);
    }

    if ($status) {
      if ($status === 'approved') {
        $query->approved();
      } elseif ($status === 'pending') {
        $query->pending();
      }
    }

    // Get time registrations
    $timeRegistrations = $query->orderBy('date', 'desc')
      ->orderBy('start_time', 'desc')
      ->paginate(15);

    // Calculate statistics
    $statistics = [
      'total_hours' => $query->sum('duration'),
      'billable_hours' => $query->billable()->sum('duration'),
      'total_cost' => $query->sum(DB::raw('duration * cost_rate')),
      'total_billable' => $query->billable()->sum(DB::raw('duration * bill_rate'))
    ];

    // Get projects for filter
    $projects = Project::whereHas('timeRegistrations', function ($query) use ($user) {
      $query->where('user_id', $user->id);
    })->get();

    return view('time.registration.overview', compact(
      'timeRegistrations',
      'statistics',
      'projects',
      'startDate',
      'endDate',
      'projectId',
      'status'
    ));
  }

  public function show(TimeTimeRegistrationOverviewModal $timeRegistration)
  {
    $user = Auth::user();
    if (!($user->role === User::ROLE_ADMIN || $user->id === $timeRegistration->user_id)) {
      return redirect()->back()->with('error', 'You do not have permission to view this time registration');
    }

    return view('time.registration.show', compact('timeRegistration'));
  }

  public function edit(TimeTimeRegistrationOverviewModal $timeRegistration)
  {
    $user = Auth::user();
    if (!($user->role === User::ROLE_ADMIN || $user->id === $timeRegistration->user_id)) {
      return redirect()->back()->with('error', 'You do not have permission to edit this time registration');
    }

    $projects = Project::active()->get();
    $tasks = Task::where('project_id', $timeRegistration->project_id)->get();

    return view('time.registration.edit', compact('timeRegistration', 'projects', 'tasks'));
  }

  public function update(Request $request, TimeTimeRegistrationOverviewModal $timeRegistration)
  {
    $user = Auth::user();
    if (!($user->role === User::ROLE_ADMIN || $user->id === $timeRegistration->user_id)) {
      return redirect()->back()->with('error', 'You do not have permission to update this time registration');
    }

    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'task_id' => 'required|exists:tasks,id',
      'date' => 'required|date',
      'start_time' => 'required|date_format:H:i',
      'end_time' => 'required|date_format:H:i|after:start_time',
      'description' => 'required|string|max:500',
      'billable' => 'boolean'
    ]);

    // Calculate duration
    $startTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
    $endTime = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);
    $duration = $endTime->floatDiffInHours($startTime);

    $timeRegistration->update([
      ...$validated,
      'duration' => $duration,
      'status' => 'pending',
      'approved_by' => null,
      'approved_at' => null
    ]);

    return redirect()->route('time-registration.overview')
      ->with('success', 'Time registration updated successfully');
  }

  public function destroy(TimeTimeRegistrationOverviewModal $timeRegistration)
  {
    $user = Auth::user();
    if (!($user->role === User::ROLE_ADMIN || $user->id === $timeRegistration->user_id)) {
      return redirect()->back()->with('error', 'You do not have permission to delete this time registration');
    }

    if (!$timeRegistration->isDeletable()) {
      return redirect()->back()
        ->with('error', 'This time registration cannot be deleted');
    }

    $timeRegistration->delete();

    return redirect()->route('time-registration.overview')
      ->with('success', 'Time registration deleted successfully');
  }

  public function export(Request $request)
  {
    $user = Auth::user();

    $startDate = $request->input('start_date', Carbon::now()->startOfWeek());
    $endDate = $request->input('end_date', Carbon::now()->endOfWeek());

    $timeRegistrations = TimeTimeRegistrationOverviewModal::with(['project', 'task'])
      ->forUser($user->id)
      ->forPeriod($startDate, $endDate)
      ->get();

    // Generate export file (implementation depends on your export library)

    return redirect()->back()->with('success', 'Export started successfully');
  }
}
