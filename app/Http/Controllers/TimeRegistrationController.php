<?php

namespace App\Http\Controllers;

use App\Models\TimeRegistrationModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TimeRegistrationController extends Controller
{
  /**
   * Display the time registration dashboard.
   */
  public function dashboard()
  {
    $user = Auth::user();
    $today = Carbon::today();

    $weeklyRegistrations = TimeRegistrationModal::where('user_id', $user->id)
      ->whereBetween('date', [
        $today->copy()->startOfWeek(),
        $today->copy()->endOfWeek()
      ])
      ->get();

    $monthlyRegistrations = TimeRegistrationModal::where('user_id', $user->id)
      ->whereBetween('date', [
        $today->copy()->startOfMonth(),
        $today->copy()->endOfMonth()
      ])
      ->get();

    $weeklyTotal = $weeklyRegistrations->sum('hours');
    $monthlyTotal = $monthlyRegistrations->sum('hours');

    $recentRegistrations = TimeRegistrationModal::where('user_id', $user->id)
      ->with(['project', 'task'])
      ->orderBy('date', 'desc')
      ->limit(5)
      ->get();

    return view('time-registration.dashboard', compact(
      'weeklyRegistrations',
      'monthlyRegistrations',
      'weeklyTotal',
      'monthlyTotal',
      'recentRegistrations'
    ));
  }

  /**
   * Display a listing of time registrations.
   */
  public function index(Request $request)
  {
    $query = TimeRegistrationModal::query()
      ->with(['project', 'task', 'user']);

    // Filter by date range
    if ($request->has('start_date') && $request->has('end_date')) {
      $query->whereBetween('date', [
        Carbon::parse($request->start_date),
        Carbon::parse($request->end_date)
      ]);
    }

    // Filter by project
    if ($request->has('project_id')) {
      $query->where('project_id', $request->project_id);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    $registrations = $query->orderBy('date', 'desc')
      ->paginate(10);

    return view('time-registration.index', compact('registrations'));
  }

  /**
   * Show the form for creating a new time registration.
   */
  public function create()
  {
    return view('time-registration.create');
  }

  /**
   * Store a newly created time registration.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'task_id' => 'required|exists:tasks,id',
      'date' => 'required|date',
      'hours' => 'required|numeric|min:0.25|max:24',
      'description' => 'required|string',
      'billable' => 'required|boolean',
      'overtime' => 'required|boolean',
      'status' => 'required|string|in:draft,submitted,approved,rejected'
    ]);

    $validated['user_id'] = Auth::id();

    $registration = TimeRegistrationModal::create($validated);

    return redirect()
      ->route('time-registration.show', $registration)
      ->with('success', 'Time registration created successfully');
  }

  /**
   * Display the specified time registration.
   */
  public function show(TimeRegistrationModal $registration)
  {
    $registration->load(['project', 'task', 'user']);
    return view('time-registration.show', compact('registration'));
  }

  /**
   * Show the form for editing the specified time registration.
   */
  public function edit(TimeRegistrationModal $registration)
  {
    return view('time-registration.edit', compact('registration'));
  }

  /**
   * Update the specified time registration.
   */
  public function update(Request $request, TimeRegistrationModal $registration)
  {
    $validated = $request->validate([
      'project_id' => 'sometimes|exists:projects,id',
      'task_id' => 'sometimes|exists:tasks,id',
      'date' => 'sometimes|date',
      'hours' => 'sometimes|numeric|min:0.25|max:24',
      'description' => 'sometimes|string',
      'billable' => 'sometimes|boolean',
      'overtime' => 'sometimes|boolean',
      'status' => 'sometimes|string|in:draft,submitted,approved,rejected'
    ]);

    $registration->update($validated);

    return redirect()
      ->route('time-registration.show', $registration)
      ->with('success', 'Time registration updated successfully');
  }

  /**
   * Remove the specified time registration.
   */
  public function destroy(TimeRegistrationModal $registration)
  {
    $registration->delete();

    return redirect()
      ->route('time-registration.index')
      ->with('success', 'Time registration deleted successfully');
  }

  /**
   * Display the calendar view.
   */
  public function calendar()
  {
    $user = Auth::user();
    $registrations = TimeRegistrationModal::where('user_id', $user->id)
      ->with(['project', 'task'])
      ->get()
      ->map(function ($registration) {
        return [
          'id' => $registration->id,
          'title' => $registration->project->name . ' - ' . $registration->task->name,
          'start' => $registration->date->format('Y-m-d'),
          'hours' => $registration->hours,
          'description' => $registration->description,
          'status' => $registration->status,
          'backgroundColor' => $this->getStatusColor($registration->status)
        ];
      });

    return view('time-registration.calendar', compact('registrations'));
  }

  /**
   * Display the approvals page.
   */
  public function approvals()
  {
    $pendingApprovals = TimeRegistrationModal::where('status', 'submitted')
      ->with(['project', 'task', 'user'])
      ->orderBy('date', 'desc')
      ->paginate(10);

    return view('time-registration.approvals', compact('pendingApprovals'));
  }

  /**
   * Approve a time registration.
   */
  public function approve(TimeRegistrationModal $registration)
  {
    $registration->update(['status' => 'approved']);

    return redirect()
      ->route('time-registration.approvals')
      ->with('success', 'Time registration approved successfully');
  }

  /**
   * Reject a time registration.
   */
  public function reject(Request $request, TimeRegistrationModal $registration)
  {
    $validated = $request->validate([
      'rejection_reason' => 'required|string'
    ]);

    $registration->update([
      'status' => 'rejected',
      'rejection_reason' => $validated['rejection_reason']
    ]);

    return redirect()
      ->route('time-registration.approvals')
      ->with('success', 'Time registration rejected successfully');
  }

  /**
   * Submit a time registration for approval.
   */
  public function submit(TimeRegistrationModal $registration)
  {
    $registration->update(['status' => 'submitted']);

    return redirect()
      ->route('time-registration.show', $registration)
      ->with('success', 'Time registration submitted for approval');
  }

  /**
   * Get the color for a given status.
   */
  private function getStatusColor(string $status): string
  {
    return match ($status) {
      'draft' => '#6c757d',     // gray
      'submitted' => '#ffc107',  // yellow
      'approved' => '#28a745',   // green
      'rejected' => '#dc3545',   // red
      default => '#6c757d'
    };
  }
}
