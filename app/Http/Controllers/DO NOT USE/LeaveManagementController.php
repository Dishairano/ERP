<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveManagementController extends Controller
{
  public function index()
  {
    $leaveRequests = LeaveRequest::with(['user', 'leaveType'])
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('leave-management.index', compact('leaveRequests'));
  }

  public function create()
  {
    return view('leave-management.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'leave_type_id' => 'required|exists:leave_types,id',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'reason' => 'required|string|max:1000',
    ]);

    $leaveRequest = new LeaveRequest($validated);
    $leaveRequest->user_id = Auth::id();
    $leaveRequest->status = 'pending';
    $leaveRequest->save();

    return redirect()->route('leave-requests.index')
      ->with('success', 'Leave request submitted successfully');
  }

  public function show(LeaveRequest $leaveRequest)
  {
    return view('leave-management.show', compact('leaveRequest'));
  }

  public function update(Request $request, LeaveRequest $leaveRequest)
  {
    $validated = $request->validate([
      'status' => 'required|in:approved,rejected',
      'comments' => 'nullable|string|max:1000',
    ]);

    $leaveRequest->update($validated);

    return redirect()->route('leave-requests.index')
      ->with('success', 'Leave request updated successfully');
  }

  public function destroy(LeaveRequest $leaveRequest)
  {
    $leaveRequest->delete();

    return redirect()->route('leave-requests.index')
      ->with('success', 'Leave request cancelled successfully');
  }

  public function calendar()
  {
    $leaveRequests = LeaveRequest::with(['user', 'leaveType'])
      ->where('status', 'approved')
      ->get();

    return view('leave-management.calendar', compact('leaveRequests'));
  }

  public function balances()
  {
    $user = Auth::user();
    $leaveBalances = $user->leaveBalances;

    return view('leave-management.balances', compact('leaveBalances'));
  }

  public function policies()
  {
    return view('leave-management.policies');
  }
}
