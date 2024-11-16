<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CoreSchedulingController extends Controller
{
    /**
     * Constructor to ensure user is authenticated
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            $user = Auth::user();
            if (!$user instanceof User) {
                abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid user model type.');
            }

            return $next($request);
        });
    }

    /**
     * Display the shifts management page.
     */
    public function shifts(): View
    {
        /** @var User $user */
        $user = Auth::user();

        // Get upcoming shifts
        $upcomingShifts = WorkShift::where('start_time', '>=', now())
            ->with(['user'])
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        // Get user's shifts
        $userShifts = WorkShift::where('user_id', $user->id)
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        return view('content.scheduling.shifts', compact(
            'upcomingShifts',
            'userShifts'
        ));
    }

    /**
     * Get events for the calendar.
     */
    public function events(Request $request): JsonResponse
    {
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        $query = WorkShift::whereBetween('start_time', [$start, $end])
            ->orWhereBetween('end_time', [$start, $end])
            ->with(['user']);

        /** @var User $user */
        $user = Auth::user();

        // Show only own shifts unless has permission to view all
        if (!$user->hasPermission('view_all_shifts')) {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->location) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        $shifts = $query->get();

        return response()->json($shifts->map(function ($shift) {
            return [
                'id' => $shift->id,
                'title' => $shift->user->name . ' - ' . $shift->typeDisplay,
                'start' => $shift->start_time->format('Y-m-d H:i'),
                'end' => $shift->end_time->format('Y-m-d H:i'),
                'className' => $this->getShiftClass($shift->type),
                'extendedProps' => [
                    'type' => $shift->type,
                    'location' => $shift->location,
                    'notes' => $shift->notes
                ]
            ];
        }));
    }

    /**
     * Show the form for creating a new shift.
     */
    public function create(): View
    {
        if (!Gate::allows('create_shifts')) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        $users = User::all();
        return view('content.scheduling.create', compact('users'));
    }

    /**
     * Store a newly created shift.
     */
    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create_shifts')) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:morning,afternoon,evening,night',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Check for overlapping shifts
        $start = Carbon::parse($validated['start_time']);
        $end = Carbon::parse($validated['end_time']);

        if (WorkShift::findOverlappingShifts($start, $end, $validated['user_id'])->isNotEmpty()) {
            return response()->json([
                'message' => 'This time slot overlaps with an existing shift.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $shift = WorkShift::create($validated);

        return response()->json([
            'message' => 'Shift created successfully.',
            'shift' => $shift
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified shift.
     */
    public function show(WorkShift $shift): View
    {
        if (!Gate::allows('view', $shift)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        return view('content.scheduling.show', compact('shift'));
    }

    /**
     * Show the form for editing the specified shift.
     */
    public function edit(WorkShift $shift): View
    {
        if (!Gate::allows('update', $shift)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        $users = User::all();
        return view('content.scheduling.edit', compact('shift', 'users'));
    }

    /**
     * Update the specified shift.
     */
    public function update(Request $request, WorkShift $shift): JsonResponse
    {
        if (!Gate::allows('update', $shift)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:morning,afternoon,evening,night',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Check for overlapping shifts
        $start = Carbon::parse($validated['start_time']);
        $end = Carbon::parse($validated['end_time']);

        if (WorkShift::findOverlappingShifts($start, $end, $validated['user_id'], $shift->id)->isNotEmpty()) {
            return response()->json([
                'message' => 'This time slot overlaps with an existing shift.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $shift->update($validated);

        return response()->json([
            'message' => 'Shift updated successfully.',
            'shift' => $shift
        ]);
    }

    /**
     * Remove the specified shift.
     */
    public function destroy(WorkShift $shift): JsonResponse
    {
        if (!Gate::allows('delete', $shift)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        $shift->delete();

        return response()->json([
            'message' => 'Shift deleted successfully.'
        ]);
    }

    /**
     * Start the specified shift.
     */
    public function startShift(WorkShift $shift): JsonResponse
    {
        if (!Gate::allows('update', $shift)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        if ($shift->status !== 'scheduled') {
            return response()->json([
                'message' => 'This shift cannot be started.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $shift->update(['status' => 'in_progress']);

        return response()->json([
            'message' => 'Shift started successfully.',
            'shift' => $shift
        ]);
    }

    /**
     * Complete the specified shift.
     */
    public function completeShift(WorkShift $shift): JsonResponse
    {
        if (!Gate::allows('update', $shift)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        if ($shift->status !== 'in_progress') {
            return response()->json([
                'message' => 'This shift cannot be completed.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $shift->update(['status' => 'completed']);

        return response()->json([
            'message' => 'Shift completed successfully.',
            'shift' => $shift
        ]);
    }

    /**
     * Cancel the specified shift.
     */
    public function cancelShift(WorkShift $shift): JsonResponse
    {
        if (!Gate::allows('update', $shift)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        if (!in_array($shift->status, ['scheduled', 'in_progress'])) {
            return response()->json([
                'message' => 'This shift cannot be cancelled.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $shift->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Shift cancelled successfully.',
            'shift' => $shift
        ]);
    }

    /**
     * Get the CSS class for shift types.
     */
    private function getShiftClass(string $type): string
    {
        return match($type) {
            'morning' => 'bg-primary',
            'afternoon' => 'bg-success',
            'evening' => 'bg-warning',
            'night' => 'bg-info',
            default => 'bg-secondary'
        };
    }
}
