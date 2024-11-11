<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
  public function index()
  {
    $leaveRequests = LeaveRequest::orderBy('created_at', 'desc')->get();
    return view('leave-requests.index', compact('leaveRequests'));
  }

  public function create()
  {
    return view('leave-requests.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'reason' => 'required|string|max:255',
      'type' => 'required|string',
    ]);

    LeaveRequest::create($validated);

    return redirect()->route('leave-requests.index')
      ->with('success', 'Verlofaanvraag succesvol ingediend.');
  }

  public function show(LeaveRequest $leaveRequest)
  {
    return view('leave-requests.show', compact('leaveRequest'));
  }

  public function update(Request $request, LeaveRequest $leaveRequest)
  {
    $validated = $request->validate([
      'status' => 'required|in:pending,approved,rejected',
      'comments' => 'nullable|string',
    ]);

    $leaveRequest->update($validated);

    return redirect()->route('leave-requests.index')
      ->with('success', 'Verlofaanvraag status bijgewerkt.');
  }
}
