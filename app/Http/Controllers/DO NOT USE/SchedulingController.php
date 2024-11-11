<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Roster;
use App\Models\Availability;
use App\Models\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchedulingController extends Controller
{
  public function shifts()
  {
    $shifts = Shift::orderBy('start_time')->get();
    return view('scheduling.shifts', compact('shifts'));
  }

  public function storeShift(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'start_time' => 'required|date_format:H:i',
      'end_time' => 'required|date_format:H:i|after:start_time',
      'description' => 'nullable|string|max:1000'
    ]);

    Shift::create($validated);

    return redirect()->route('scheduling.shifts')
      ->with('success', 'Shift created successfully');
  }

  public function roster()
  {
    $roster = Roster::with(['user', 'shift'])
      ->orderBy('date')
      ->get();

    return view('scheduling.roster', compact('roster'));
  }

  public function storeRoster(Request $request)
  {
    $validated = $request->validate([
      'user_id' => 'required|exists:users,id',
      'shift_id' => 'required|exists:shifts,id',
      'date' => 'required|date',
      'notes' => 'nullable|string|max:1000'
    ]);

    Roster::create($validated);

    return redirect()->route('scheduling.roster')
      ->with('success', 'Roster entry created successfully');
  }

  public function availability()
  {
    $availability = Availability::with('user')
      ->orderBy('date')
      ->get();

    return view('scheduling.availability', compact('availability'));
  }

  public function storeAvailability(Request $request)
  {
    $validated = $request->validate([
      'date' => 'required|date',
      'start_time' => 'required|date_format:H:i',
      'end_time' => 'required|date_format:H:i|after:start_time',
      'status' => 'required|in:available,unavailable',
      'reason' => 'nullable|string|max:1000'
    ]);

    $availability = new Availability($validated);
    $availability->user_id = Auth::id();
    $availability->save();

    return redirect()->route('scheduling.availability')
      ->with('success', 'Availability updated successfully');
  }

  public function overtime()
  {
    $overtime = Overtime::with('user')
      ->orderBy('date')
      ->get();

    return view('scheduling.overtime', compact('overtime'));
  }

  public function storeOvertime(Request $request)
  {
    $validated = $request->validate([
      'date' => 'required|date',
      'hours' => 'required|numeric|min:0|max:24',
      'reason' => 'required|string|max:1000',
      'status' => 'required|in:pending,approved,rejected'
    ]);

    $overtime = new Overtime($validated);
    $overtime->user_id = Auth::id();
    $overtime->save();

    return redirect()->route('scheduling.overtime')
      ->with('success', 'Overtime request submitted successfully');
  }
}
