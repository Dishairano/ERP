<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of leave requests.
     */
    public function index(Request $request): View
    {
        if (!Gate::allows('viewAny', LeaveRequest::class)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        /** @var User $user */
        $user = Auth::user();

        $query = LeaveRequest::query()
            ->with(['leaveType', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Show only own requests unless has permission to view all
        if (!Gate::allows('viewAllBalances', LeaveRequest::class)) {
            $query->where('user_id', $user->id);
        }

        $leaveRequests = $query->paginate(10);

        return view('content.leave-requests.index', compact('leaveRequests'));
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create(): View
    {
        if (!Gate::allows('create', LeaveRequest::class)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        $leaveTypes = LeaveType::all();
        return view('content.leave-requests.create', compact('leaveTypes'));
    }

    /**
     * Store a newly created leave request.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Gate::allows('create', LeaveRequest::class)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $leaveRequest = new LeaveRequest($validated);
        $leaveRequest->user_id = $user->id;
        $leaveRequest->status = 'draft';
        $leaveRequest->total_days = Carbon::parse($validated['end_date'])->diffInDays(Carbon::parse($validated['start_date'])) + 1;
        $leaveRequest->save();

        return redirect()
            ->route('leave-requests.show', $leaveRequest)
            ->with('success', 'Leave request created successfully.');
    }

    /**
     * Display the specified leave request.
     */
    public function show(LeaveRequest $leaveRequest): View
    {
        if (!Gate::allows('view', $leaveRequest)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        // Get the leave balance for this type and year
        $leaveBalance = LeaveBalance::firstOrCreate(
            [
                'user_id' => $leaveRequest->user_id,
                'leave_type_id' => $leaveRequest->leave_type_id,
                'year' => Carbon::parse($leaveRequest->start_date)->year
            ],
            [
                'total_days' => $leaveRequest->leaveType->days_per_year,
                'used_days' => 0,
                'pending_days' => 0,
                'remaining_days' => $leaveRequest->leaveType->days_per_year
            ]
        );

        return view('content.leave-requests.show', compact('leaveRequest', 'leaveBalance'));
    }

    /**
     * Show the form for editing the specified leave request.
     */
    public function edit(LeaveRequest $leaveRequest): View
    {
        if (!Gate::allows('update', $leaveRequest)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        $leaveTypes = LeaveType::all();
        return view('content.leave-requests.edit', compact('leaveRequest', 'leaveTypes'));
    }

    /**
     * Update the specified leave request.
     */
    public function update(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        if (!Gate::allows('update', $leaveRequest)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        $leaveRequest->fill($validated);
        $leaveRequest->total_days = Carbon::parse($validated['end_date'])->diffInDays(Carbon::parse($validated['start_date'])) + 1;
        $leaveRequest->save();

        return redirect()
            ->route('leave-requests.show', $leaveRequest)
            ->with('success', 'Leave request updated successfully.');
    }

    /**
     * Submit the leave request for approval.
     */
    public function submit(LeaveRequest $leaveRequest): RedirectResponse
    {
        if (!Gate::allows('submit', $leaveRequest)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        $leaveRequest->status = 'submitted';
        $leaveRequest->save();

        // Update pending days in leave balance
        $leaveBalance = LeaveBalance::where([
            'user_id' => $leaveRequest->user_id,
            'leave_type_id' => $leaveRequest->leave_type_id,
            'year' => Carbon::parse($leaveRequest->start_date)->year
        ])->first();

        if ($leaveBalance) {
            $leaveBalance->pending_days += $leaveRequest->total_days;
            $leaveBalance->remaining_days -= $leaveRequest->total_days;
            $leaveBalance->save();
        }

        return redirect()
            ->route('leave-requests.show', $leaveRequest)
            ->with('success', 'Leave request submitted for approval.');
    }

    /**
     * Approve the specified leave request.
     */
    public function approve(LeaveRequest $leaveRequest): RedirectResponse
    {
        if (!Gate::allows('approve', $leaveRequest)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        /** @var User $user */
        $user = Auth::user();

        $leaveRequest->status = 'approved';
        $leaveRequest->approved_by = $user->id;
        $leaveRequest->approved_at = now();
        $leaveRequest->save();

        // Update leave balance
        $leaveBalance = LeaveBalance::where([
            'user_id' => $leaveRequest->user_id,
            'leave_type_id' => $leaveRequest->leave_type_id,
            'year' => Carbon::parse($leaveRequest->start_date)->year
        ])->first();

        if ($leaveBalance) {
            $leaveBalance->pending_days -= $leaveRequest->total_days;
            $leaveBalance->used_days += $leaveRequest->total_days;
            $leaveBalance->save();
        }

        return redirect()
            ->route('leave-requests.show', $leaveRequest)
            ->with('success', 'Leave request approved successfully.');
    }

    /**
     * Reject the specified leave request.
     */
    public function reject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        if (!Gate::allows('reject', $leaveRequest)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        /** @var User $user */
        $user = Auth::user();

        $leaveRequest->status = 'rejected';
        $leaveRequest->approved_by = $user->id;
        $leaveRequest->approved_at = now();
        $leaveRequest->rejection_reason = $validated['rejection_reason'];
        $leaveRequest->save();

        // Update leave balance
        $leaveBalance = LeaveBalance::where([
            'user_id' => $leaveRequest->user_id,
            'leave_type_id' => $leaveRequest->leave_type_id,
            'year' => Carbon::parse($leaveRequest->start_date)->year
        ])->first();

        if ($leaveBalance) {
            $leaveBalance->pending_days -= $leaveRequest->total_days;
            $leaveBalance->remaining_days += $leaveRequest->total_days;
            $leaveBalance->save();
        }

        return redirect()
            ->route('leave-requests.show', $leaveRequest)
            ->with('success', 'Leave request rejected.');
    }
}
