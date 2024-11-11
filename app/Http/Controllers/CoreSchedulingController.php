<?php

namespace App\Http\Controllers;

use App\Models\CoreWorkShiftModal;
use App\Models\CoreEmployeeScheduleModal;
use App\Models\CoreEmployeeAvailabilityModal;
use App\Models\CoreScheduleTemplateModal;
use App\Models\CoreOvertimeRecordModal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CoreSchedulingController extends Controller
{
  public function shifts()
  {
    $this->authorize('manage_schedules');

    $shifts = CoreWorkShiftModal::orderBy('start_time')->get();
    return view('content.scheduling.shifts', compact('shifts'));
  }

  public function storeShift(Request $request)
  {
    $this->authorize('manage_schedules');

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'start_time' => 'required|date_format:H:i',
      'end_time' => 'required|date_format:H:i|after:start_time',
      'break_times' => 'nullable|array',
      'break_times.*.start' => 'required_with:break_times|date_format:H:i',
      'break_times.*.end' => 'required_with:break_times|date_format:H:i|after:break_times.*.start',
      'is_night_shift' => 'boolean'
    ]);

    CoreWorkShiftModal::create($validated);

    return redirect()->route('scheduling.shifts')
      ->with('success', 'Shift created successfully.');
  }

  public function updateShift(Request $request, $id)
  {
    $this->authorize('manage_schedules');

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'start_time' => 'required|date_format:H:i',
      'end_time' => 'required|date_format:H:i|after:start_time',
      'break_times' => 'nullable|array',
      'break_times.*.start' => 'required_with:break_times|date_format:H:i',
      'break_times.*.end' => 'required_with:break_times|date_format:H:i|after:break_times.*.start',
      'is_night_shift' => 'boolean'
    ]);

    $shift = CoreWorkShiftModal::findOrFail($id);
    $shift->update($validated);

    return redirect()->route('scheduling.shifts')
      ->with('success', 'Shift updated successfully.');
  }

  public function roster()
  {
    /** @var User $user */
    $user = Auth::user();

    $schedules = CoreEmployeeScheduleModal::with(['user', 'shift'])
      ->when(!$user->can('manage_schedules'), function ($query) use ($user) {
        return $query->where('user_id', $user->id);
      })
      ->orderBy('date')
      ->paginate(10);

    $shifts = CoreWorkShiftModal::all();
    $employees = User::all();

    return view('content.scheduling.roster', compact('schedules', 'shifts', 'employees'));
  }

  public function generateRoster(Request $request)
  {
    $this->authorize('manage_schedules');

    $validated = $request->validate([
      'template_id' => 'required|exists:schedule_templates,id',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'user_ids' => 'required|array',
      'user_ids.*' => 'exists:users,id'
    ]);

    $template = CoreScheduleTemplateModal::findOrFail($validated['template_id']);
    $template->generateSchedules(
      $validated['user_ids'],
      new Carbon($validated['start_date']),
      new Carbon($validated['end_date'])
    );

    return redirect()->route('scheduling.roster')
      ->with('success', 'Roster generated successfully.');
  }

  public function publishRoster(Request $request)
  {
    $this->authorize('manage_schedules');

    $validated = $request->validate([
      'schedule_ids' => 'required|array',
      'schedule_ids.*' => 'exists:employee_schedules,id'
    ]);

    CoreEmployeeScheduleModal::whereIn('id', $validated['schedule_ids'])
      ->update(['status' => 'published']);

    return back()->with('success', 'Roster published successfully.');
  }

  public function availability()
  {
    /** @var User $user */
    $user = Auth::user();

    $availability = CoreEmployeeAvailabilityModal::with('user')
      ->when(!$user->can('manage_schedules'), function ($query) use ($user) {
        return $query->where('user_id', $user->id);
      })
      ->orderBy('date')
      ->paginate(10);

    return view('content.scheduling.availability', compact('availability'));
  }

  public function storeAvailability(Request $request)
  {
    $validated = $request->validate([
      'date' => 'required|date',
      'start_time' => 'required|date_format:H:i',
      'end_time' => 'required|date_format:H:i|after:start_time',
      'availability_type' => 'required|in:available,unavailable,preferred',
      'notes' => 'nullable|string|max:500'
    ]);

    $availability = new CoreEmployeeAvailabilityModal($validated);
    $availability->user_id = Auth::id();
    $availability->save();

    return redirect()->route('scheduling.availability')
      ->with('success', 'Availability recorded successfully.');
  }

  public function overtime()
  {
    /** @var User $user */
    $user = Auth::user();

    $overtimeRecords = CoreOvertimeRecordModal::with(['user', 'schedule'])
      ->when(!$user->can('manage_schedules'), function ($query) use ($user) {
        return $query->where('user_id', $user->id);
      })
      ->orderBy('date', 'desc')
      ->paginate(10);

    return view('content.scheduling.overtime', compact('overtimeRecords'));
  }

  public function approveOvertime($id)
  {
    $this->authorize('manage_schedules');

    $overtime = CoreOvertimeRecordModal::findOrFail($id);
    $overtime->approve(Auth::id());

    return back()->with('success', 'Overtime approved successfully.');
  }

  public function rejectOvertime(Request $request, $id)
  {
    $this->authorize('manage_schedules');

    $validated = $request->validate([
      'reason' => 'required|string|max:500'
    ]);

    $overtime = CoreOvertimeRecordModal::findOrFail($id);
    $overtime->reject(Auth::id(), $validated['reason']);

    return back()->with('success', 'Overtime rejected successfully.');
  }
}
