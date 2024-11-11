<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\SystemBackup;
use App\Models\BackupSchedule;
use App\Models\BackupRestoration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BackupSettingsController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth', 'role:admin']);
  }

  public function index()
  {
    $backups = SystemBackup::with('initiator')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $schedules = BackupSchedule::with('creator')
      ->orderBy('created_at', 'desc')
      ->get();

    return view('settings.backup.index', compact('backups', 'schedules'));
  }

  public function createBackup(Request $request)
  {
    $validated = $request->validate([
      'backup_type' => 'required|in:full,incremental,differential',
      'included_tables' => 'nullable|array',
      'excluded_tables' => 'nullable|array',
    ]);

    $backup = new SystemBackup([
      'backup_name' => 'backup_' . now()->format('Y_m_d_His'),
      'backup_type' => $validated['backup_type'],
      'included_tables' => $validated['included_tables'] ?? null,
      'excluded_tables' => $validated['excluded_tables'] ?? null,
      'status' => 'pending',
      'initiated_by' => Auth::id(),
      'started_at' => now(),
    ]);

    $backup->save();

    // Trigger backup job here
    // BackupJob::dispatch($backup);

    return redirect()
      ->route('settings.system.backup')
      ->with('success', 'Backup process initiated successfully.');
  }

  public function restore(Request $request, SystemBackup $backup)
  {
    $restoration = new BackupRestoration([
      'system_backup_id' => $backup->id,
      'status' => 'pending',
      'initiated_by' => Auth::id(),
      'started_at' => now(),
    ]);

    $restoration->save();

    // Trigger restore job here
    // RestoreJob::dispatch($restoration);

    return redirect()
      ->route('settings.system.backup')
      ->with('success', 'Restoration process initiated successfully.');
  }

  public function download(SystemBackup $backup)
  {
    if (!Storage::exists($backup->file_path)) {
      return redirect()
        ->route('settings.system.backup')
        ->with('error', 'Backup file not found.');
    }

    return Storage::download($backup->file_path, $backup->backup_name);
  }

  public function destroy(SystemBackup $backup)
  {
    if (Storage::exists($backup->file_path)) {
      Storage::delete($backup->file_path);
    }

    $backup->delete();

    return redirect()
      ->route('settings.system.backup')
      ->with('success', 'Backup deleted successfully.');
  }

  public function schedules()
  {
    $schedules = BackupSchedule::with('creator')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('settings.backup.schedules', compact('schedules'));
  }

  public function storeSchedule(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'frequency' => 'required|in:daily,weekly,monthly',
      'backup_type' => 'required|in:full,incremental,differential',
      'scheduled_time' => 'required|date_format:H:i',
      'configuration' => 'nullable|array',
    ]);

    $schedule = new BackupSchedule($validated);
    $schedule->created_by = Auth::id();
    $schedule->next_run = $schedule->calculateNextRun();
    $schedule->save();

    return redirect()
      ->route('settings.system.backup.schedules')
      ->with('success', 'Backup schedule created successfully.');
  }

  public function updateSchedule(Request $request, BackupSchedule $schedule)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'frequency' => 'required|in:daily,weekly,monthly',
      'backup_type' => 'required|in:full,incremental,differential',
      'scheduled_time' => 'required|date_format:H:i',
      'configuration' => 'nullable|array',
      'is_active' => 'boolean',
    ]);

    $schedule->update($validated);
    $schedule->next_run = $schedule->calculateNextRun();
    $schedule->save();

    return redirect()
      ->route('settings.system.backup.schedules')
      ->with('success', 'Backup schedule updated successfully.');
  }

  public function destroySchedule(BackupSchedule $schedule)
  {
    $schedule->delete();

    return redirect()
      ->route('settings.system.backup.schedules')
      ->with('success', 'Backup schedule deleted successfully.');
  }
}
