<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\AuditLogArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth', 'role:admin']);
  }

  public function index(Request $request)
  {
    $query = AuditLog::with(['user'])
      ->when($request->filled('event_type'), function ($q) use ($request) {
        $q->where('event_type', $request->event_type);
      })
      ->when($request->filled('user_id'), function ($q) use ($request) {
        $q->where('user_id', $request->user_id);
      })
      ->when($request->filled('date_from'), function ($q) use ($request) {
        $q->whereDate('created_at', '>=', $request->date_from);
      })
      ->when($request->filled('date_to'), function ($q) use ($request) {
        $q->whereDate('created_at', '<=', $request->date_to);
      });

    $auditLogs = $query->orderBy('created_at', 'desc')->paginate(20);

    return view('settings.audit-log.index', compact('auditLogs'));
  }

  public function export(Request $request)
  {
    $query = AuditLog::with(['user'])
      ->when($request->filled('event_type'), function ($q) use ($request) {
        $q->where('event_type', $request->event_type);
      })
      ->when($request->filled('user_id'), function ($q) use ($request) {
        $q->where('user_id', $request->user_id);
      })
      ->when($request->filled('date_from'), function ($q) use ($request) {
        $q->whereDate('created_at', '>=', $request->date_from);
      })
      ->when($request->filled('date_to'), function ($q) use ($request) {
        $q->whereDate('created_at', '<=', $request->date_to);
      });

    $logs = $query->orderBy('created_at', 'desc')->get();

    $filename = 'audit_logs_' . now()->format('Y_m_d_His') . '.csv';
    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => "attachment; filename=\"$filename\""
    ];

    $callback = function () use ($logs) {
      $file = fopen('php://output', 'w');

      // Headers
      fputcsv($file, [
        'ID',
        'Event Type',
        'User',
        'Description',
        'IP Address',
        'User Agent',
        'Created At'
      ]);

      // Data
      foreach ($logs as $log) {
        fputcsv($file, [
          $log->id,
          $log->event_type,
          $log->user ? $log->user->name : 'System',
          $log->description,
          $log->ip_address,
          $log->user_agent,
          $log->created_at->format('Y-m-d H:i:s')
        ]);
      }

      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  public function archives()
  {
    $archives = AuditLogArchive::with('creator')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('settings.audit-log.archives', compact('archives'));
  }

  public function createArchive(Request $request)
  {
    $validated = $request->validate([
      'date_from' => 'required|date',
      'date_to' => 'required|date|after_or_equal:date_from',
    ]);

    DB::transaction(function () use ($validated) {
      $logs = AuditLog::whereBetween('created_at', [
        $validated['date_from'],
        $validated['date_to']
      ])->get();

      if ($logs->isEmpty()) {
        throw new \Exception('No logs found in the specified date range.');
      }

      $archive = new AuditLogArchive([
        'archive_date' => now(),
        'records_count' => $logs->count(),
        'status' => 'pending',
        'created_by' => auth()->id()
      ]);

      $archive->save();

      // Trigger archive job here
      // ArchiveAuditLogsJob::dispatch($archive, $logs);
    });

    return redirect()
      ->route('settings.access.audit-log.archives')
      ->with('success', 'Archive process initiated successfully.');
  }

  public function downloadArchive(AuditLogArchive $archive)
  {
    if (!Storage::exists($archive->file_path)) {
      return redirect()
        ->route('settings.access.audit-log.archives')
        ->with('error', 'Archive file not found.');
    }

    return Storage::download($archive->file_path);
  }

  public function destroyArchive(AuditLogArchive $archive)
  {
    if (Storage::exists($archive->file_path)) {
      Storage::delete($archive->file_path);
    }

    $archive->delete();

    return redirect()
      ->route('settings.access.audit-log.archives')
      ->with('success', 'Archive deleted successfully.');
  }
}
