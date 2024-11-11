<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\AuditSetting;
use App\Models\AuditNotification;
use App\Exports\AuditTrailExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class AuditTrailController extends Controller
{
  public function index()
  {
    $auditLogs = AuditLog::with('user')
      ->orderBy('created_at', 'desc')
      ->paginate(15);

    return view('audit-trail.index', compact('auditLogs'));
  }

  public function show($id)
  {
    $log = AuditLog::with('user')->findOrFail($id);
    return view('audit-trail.show', compact('log'));
  }

  public function settings()
  {
    $settings = AuditSetting::first();
    return view('audit-trail.settings', compact('settings'));
  }

  public function updateSettings(Request $request)
  {
    $request->validate([
      'retention_period' => 'required|integer|min:1',
      'log_level' => 'required|string',
    ]);

    $settings = AuditSetting::first();
    $settings->update($request->all());

    return redirect()->route('audit-trail.settings')
      ->with('success', 'Audit trail settings updated successfully');
  }

  public function notifications()
  {
    $notifications = AuditNotification::all();
    return view('audit-trail.notifications', compact('notifications'));
  }

  public function updateNotifications(Request $request)
  {
    $request->validate([
      'notifications' => 'required|array',
      'notifications.*.event' => 'required|string',
      'notifications.*.enabled' => 'required|boolean',
    ]);

    foreach ($request->notifications as $notification) {
      AuditNotification::where('event', $notification['event'])
        ->update(['enabled' => $notification['enabled']]);
    }

    return redirect()->route('audit-trail.notifications')
      ->with('success', 'Notification settings updated successfully');
  }

  public function export()
  {
    return Excel::download(new AuditTrailExport, 'audit-trail.xlsx');
  }
}
