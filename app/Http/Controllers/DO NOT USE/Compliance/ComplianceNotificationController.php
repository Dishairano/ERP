<?php

namespace App\Http\Controllers\Compliance;

use App\Http\Controllers\Controller;
use App\Models\ComplianceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplianceNotificationController extends Controller
{
  public function index()
  {
    $notifications = ComplianceNotification::where('user_id', Auth::user()->id)
      ->latest()
      ->paginate(10);
    return view('compliance.notifications.index', compact('notifications'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'message' => 'required|string',
      'type' => 'required|string',
      'priority' => 'required|string',
      'action_required' => 'nullable|string',
      'due_date' => 'nullable|date'
    ]);

    $validated['user_id'] = Auth::user()->id;
    $validated['status'] = 'unread';

    ComplianceNotification::create($validated);

    return redirect()->route('compliance.notifications.index')
      ->with('success', 'Notification created successfully.');
  }

  public function markAsRead(ComplianceNotification $notification)
  {
    $notification->update([
      'status' => 'read',
      'read_at' => now()
    ]);

    return redirect()->route('compliance.notifications.index')
      ->with('success', 'Notification marked as read.');
  }

  public function destroy(ComplianceNotification $notification)
  {
    $notification->delete();

    return redirect()->route('compliance.notifications.index')
      ->with('success', 'Notification deleted successfully.');
  }
}
