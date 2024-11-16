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
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CoreLeaveManagementController extends Controller
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

            if (!Gate::allows('viewAny', LeaveRequest::class)) {
                abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    /**
     * Display the leave management dashboard.
     */
    public function dashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();

        // Get recent leave requests
        $recentRequests = LeaveRequest::where('user_id', $user->id)
            ->with(['leaveType'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get pending approvals if user has permission
        $pendingApprovals = [];
        if (Gate::allows('approve', LeaveRequest::class)) {
            $pendingApprovals = LeaveRequest::where('status', 'submitted')
                ->with(['user', 'leaveType'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Get leave balances
        $leaveBalances = $user->getCurrentLeaveBalances();

        return view('content.leave-requests.dashboard', compact(
            'recentRequests',
            'pendingApprovals',
            'leaveBalances'
        ));
    }

    /**
     * Display the calendar view.
     */
    public function calendar(): View
    {
        return view('content.leave-requests.calendar');
    }

    /**
     * Display the leave balances.
     */
    public function balances(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $year = request('year', date('Y'));

        // Get all leave types
        $leaveTypes = LeaveType::all();

        // Get balances for current user
        $balances = [];
        foreach ($leaveTypes as $leaveType) {
            $balance = LeaveBalance::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'year' => $year
                ],
                [
                    'total_days' => $leaveType->days_per_year,
                    'used_days' => 0,
                    'pending_days' => 0,
                    'remaining_days' => $leaveType->days_per_year
                ]
            );

            $balances[] = [
                'type' => $leaveType,
                'balance' => $balance
            ];
        }

        // Get all users' balances if has permission
        $allBalances = [];
        if (Gate::allows('viewAllBalances', LeaveRequest::class)) {
            $allBalances = LeaveBalance::where('year', $year)
                ->with(['user', 'leaveType'])
                ->get()
                ->groupBy('user_id');
        }

        return view('content.leave-requests.balances', compact('balances', 'allBalances', 'year'));
    }

    /**
     * Get leave requests for calendar.
     */
    public function getCalendarEvents(Request $request): JsonResponse
    {
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        $query = LeaveRequest::whereBetween('start_date', [$start, $end])
            ->orWhereBetween('end_date', [$start, $end])
            ->with(['user', 'leaveType']);

        /** @var User $user */
        $user = Auth::user();

        // Show only own requests unless has permission to view all
        if (!Gate::allows('viewAllBalances', LeaveRequest::class)) {
            $query->where('user_id', $user->id);
        }

        $leaveRequests = $query->get();

        return response()->json($leaveRequests->map(function ($request) {
            return [
                'id' => $request->id,
                'title' => $request->user->name . ' - ' . $request->leaveType->name,
                'start' => $request->start_date->format('Y-m-d'),
                'end' => $request->end_date->addDay()->format('Y-m-d'),
                'className' => $this->getEventClass($request->status),
                'extendedProps' => [
                    'status' => $request->status,
                    'reason' => $request->reason
                ]
            ];
        }));
    }

    /**
     * Get the CSS class for calendar events based on status.
     */
    private function getEventClass(string $status): string
    {
        return match($status) {
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'submitted' => 'bg-warning',
            default => 'bg-secondary'
        };
    }
}
