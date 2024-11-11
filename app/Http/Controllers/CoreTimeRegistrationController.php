<?php

namespace App\Http\Controllers;

use App\Models\CoreTimeRegistrationModal;
use App\Models\CoreProjectModal;
use App\Models\CoreProjectTaskModal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoreTimeRegistrationController extends Controller
{
  public function dashboard()
  {
    /** @var User $user */
    $user = Auth::user();
    $today = now()->format('Y-m-d');

    $data = [
      'todayHours' => CoreTimeRegistrationModal::forUser($user->id)
        ->forDate($today)
        ->sum('hours'),
      'weekHours' => CoreTimeRegistrationModal::forUser($user->id)
        ->forDateRange(now()->startOfWeek(), now()->endOfWeek())
        ->sum('hours'),
      'monthHours' => CoreTimeRegistrationModal::forUser($user->id)
        ->forDateRange(now()->startOfMonth(), now()->endOfMonth())
        ->sum('hours'),
      'pendingApprovals' => CoreTimeRegistrationModal::pending()
        ->when(!$user->hasRole('admin'), function ($query) use ($user) {
          return $query->forUser($user->id);
        })
        ->count()
    ];

    return view('content.time-registration.dashboard', $data);
  }

  public function index()
  {
    /** @var User $user */
    $user = Auth::user();

    $registrations = CoreTimeRegistrationModal::forUser($user->id)
      ->with(['project', 'task'])
      ->orderBy('date', 'desc')
      ->paginate(10);

    return view('content.time-registration.index', compact('registrations'));
  }

  public function create()
  {
    $projects = CoreProjectModal::active()->get();
    $tasks = CoreProjectTaskModal::whereIn('project_id', $projects->pluck('id'))->get();

    return view('content.time-registration.create', compact('projects', 'tasks'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'nullable|exists:projects,id',
      'task_id' => 'nullable|exists:project_tasks,id',
      'date' => 'required|date',
      'start_time' => 'required',
      'end_time' => 'required|after:start_time',
      'description' => 'nullable|string|max:500'
    ]);

    $registration = new CoreTimeRegistrationModal($validated);
    $registration->user_id = Auth::id();
    $registration->calculateHours();
    $registration->save();

    return redirect()->route('time-registration.index')
      ->with('success', 'Time registration created successfully.');
  }

  public function calendar()
  {
    /** @var User $user */
    $user = Auth::user();

    $registrations = CoreTimeRegistrationModal::forUser($user->id)
      ->with(['project', 'task'])
      ->get()
      ->map(function ($registration) {
        return [
          'id' => $registration->id,
          'title' => $registration->project ? $registration->project->name : 'No Project',
          'start' => $registration->date->format('Y-m-d') . ' ' . $registration->start_time->format('H:i:s'),
          'end' => $registration->date->format('Y-m-d') . ' ' . $registration->end_time->format('H:i:s'),
          'description' => $registration->description,
          'status' => $registration->status
        ];
      });

    return view('content.time-registration.calendar', compact('registrations'));
  }

  public function approvals()
  {
    $this->authorize('manage_time_registrations');

    /** @var User $user */
    $user = Auth::user();

    $pendingRegistrations = CoreTimeRegistrationModal::pending()
      ->with(['user', 'project', 'task'])
      ->when(!$user->hasRole('admin'), function ($query) use ($user) {
        return $query->whereHas('project', function ($q) use ($user) {
          $q->where('manager_id', $user->id);
        });
      })
      ->orderBy('date', 'desc')
      ->paginate(10);

    return view('content.time-registration.approvals', compact('pendingRegistrations'));
  }

  public function approve($id)
  {
    $this->authorize('manage_time_registrations');

    $registration = CoreTimeRegistrationModal::findOrFail($id);
    $registration->approve(Auth::id());

    return back()->with('success', 'Time registration approved successfully.');
  }

  public function reject(Request $request, $id)
  {
    $this->authorize('manage_time_registrations');

    $validated = $request->validate([
      'rejection_reason' => 'required|string|max:500'
    ]);

    $registration = CoreTimeRegistrationModal::findOrFail($id);
    $registration->reject(Auth::id(), $validated['rejection_reason']);

    return back()->with('success', 'Time registration rejected successfully.');
  }
}
