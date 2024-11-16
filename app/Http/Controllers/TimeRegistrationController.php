<?php

namespace App\Http\Controllers;

use App\Models\TimeRegistrationModal;
use App\Models\CoreProjectModal;
use App\Models\CoreProjectTaskModal;
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

        // Today's hours
        $todayHours = TimeRegistrationModal::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->sum('hours');

        // Week's hours
        $weekHours = TimeRegistrationModal::where('user_id', $user->id)
            ->whereBetween('date', [
                $today->copy()->startOfWeek(),
                $today->copy()->endOfWeek()
            ])
            ->sum('hours');

        // Month's hours
        $monthHours = TimeRegistrationModal::where('user_id', $user->id)
            ->whereBetween('date', [
                $today->copy()->startOfMonth(),
                $today->copy()->endOfMonth()
            ])
            ->sum('hours');

        // Pending approvals count - show all for managers/admins, only own for regular users
        $pendingApprovals = TimeRegistrationModal::where('status', 'submitted')
            ->where('user_id', $user->id) // For now, only show user's own pending approvals
            ->count();

        // Recent registrations
        $recentRegistrations = TimeRegistrationModal::where('user_id', $user->id)
            ->with(['project', 'task'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        return view('content.time-registration.dashboard', compact(
            'todayHours',
            'weekHours',
            'monthHours',
            'pendingApprovals',
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

        return view('content.time-registration.index', compact('registrations'));
    }

    /**
     * Show the form for creating a new time registration.
     */
    public function create()
    {
        // Load active projects
        $projects = CoreProjectModal::active()->get();

        // Load tasks for all active projects
        $tasks = CoreProjectTaskModal::whereIn('project_id', $projects->pluck('id'))
            ->orderBy('title')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->title // Map title to name for consistency in view
                ];
            });

        return view('content.time-registration.create', compact('projects', 'tasks'));
    }

    /**
     * Get tasks for a specific project (API endpoint)
     */
    public function getProjectTasks($projectId)
    {
        $tasks = CoreProjectTaskModal::where('project_id', $projectId)
            ->orderBy('title')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->title
                ];
            });

        return response()->json($tasks);
    }

    /**
     * Store a newly created time registration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'required|exists:project_tasks,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'required|string',
            'billable' => 'boolean',
            'overtime' => 'boolean',
            'status' => 'required|string|in:draft,submitted,approved,rejected'
        ]);

        $validated['user_id'] = Auth::id();

        // Convert time inputs to proper format
        $validated['start_time'] = Carbon::parse($validated['date'] . ' ' . $validated['start_time'])->format('H:i:s');
        $validated['end_time'] = Carbon::parse($validated['date'] . ' ' . $validated['end_time'])->format('H:i:s');

        // Set default values
        $validated['billable'] = $validated['billable'] ?? false;
        $validated['overtime'] = $validated['overtime'] ?? false;

        // Create the registration
        $registration = new TimeRegistrationModal($validated);
        $registration->calculateHours();
        $registration->save();

        return redirect()
            ->route('time-registration.show', $registration)
            ->with('success', 'Time registration created successfully');
    }

    /**
     * Display the specified time registration.
     */
    public function show(TimeRegistrationModal $timeRegistration)
    {
        $timeRegistration->load(['project', 'task', 'user']);
        return view('content.time-registration.show', ['registration' => $timeRegistration]);
    }

    /**
     * Show the form for editing the specified time registration.
     */
    public function edit(TimeRegistrationModal $timeRegistration)
    {
        $projects = CoreProjectModal::active()->get();
        $tasks = CoreProjectTaskModal::where('project_id', $timeRegistration->project_id)->get();

        return view('content.time-registration.edit', [
            'registration' => $timeRegistration,
            'projects' => $projects,
            'tasks' => $tasks
        ]);
    }

    /**
     * Update the specified time registration.
     */
    public function update(Request $request, TimeRegistrationModal $timeRegistration)
    {
        $validated = $request->validate([
            'project_id' => 'sometimes|exists:projects,id',
            'task_id' => 'sometimes|exists:project_tasks,id',
            'date' => 'sometimes|date',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'description' => 'sometimes|string',
            'billable' => 'boolean',
            'overtime' => 'boolean',
            'status' => 'sometimes|string|in:draft,submitted,approved,rejected'
        ]);

        if (isset($validated['start_time']) || isset($validated['end_time'])) {
            $validated['start_time'] = Carbon::parse($validated['date'] . ' ' . $validated['start_time'])->format('H:i:s');
            $validated['end_time'] = Carbon::parse($validated['date'] . ' ' . $validated['end_time'])->format('H:i:s');

            $timeRegistration->fill($validated);
            $timeRegistration->calculateHours();
            $timeRegistration->save();
        } else {
            $timeRegistration->update($validated);
        }

        return redirect()
            ->route('time-registration.show', $timeRegistration)
            ->with('success', 'Time registration updated successfully');
    }

    /**
     * Remove the specified time registration.
     */
    public function destroy(TimeRegistrationModal $timeRegistration)
    {
        $timeRegistration->delete();

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
                    'title' => optional($registration->project)->name . ' - ' . optional($registration->task)->title,
                    'start' => $registration->date->format('Y-m-d'),
                    'hours' => $registration->hours,
                    'description' => $registration->description,
                    'status' => $registration->status,
                    'backgroundColor' => $this->getStatusColor($registration->status)
                ];
            });

        return view('content.time-registration.calendar', compact('registrations'));
    }

    /**
     * Display the approvals page.
     */
    public function approvals()
    {
        $user = Auth::user();

        // For now, users can only see their own submissions
        $pendingApprovals = TimeRegistrationModal::where('status', 'submitted')
            ->where('user_id', $user->id)
            ->with(['project', 'task', 'user'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('content.time-registration.approvals', compact('pendingApprovals'));
    }

    /**
     * Approve a time registration.
     */
    public function approve(TimeRegistrationModal $timeRegistration)
    {
        $timeRegistration->update(['status' => 'approved']);

        return redirect()
            ->route('time-registration.approvals')
            ->with('success', 'Time registration approved successfully');
    }

    /**
     * Reject a time registration.
     */
    public function reject(Request $request, TimeRegistrationModal $timeRegistration)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $timeRegistration->update([
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
    public function submit(TimeRegistrationModal $timeRegistration)
    {
        $timeRegistration->update(['status' => 'submitted']);

        return redirect()
            ->route('time-registration.show', $timeRegistration)
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
