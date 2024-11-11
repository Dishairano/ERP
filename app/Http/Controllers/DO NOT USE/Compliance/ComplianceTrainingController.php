<?php

namespace App\Http\Controllers\Compliance;

use App\Http\Controllers\Controller;
use App\Models\ComplianceTraining;
use App\Models\ComplianceTrainingCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplianceTrainingController extends Controller
{
  public function index()
  {
    $trainings = ComplianceTraining::latest()->paginate(10);
    return view('compliance.trainings.index', compact('trainings'));
  }

  public function create()
  {
    return view('compliance.trainings.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'training_type' => 'required|string',
      'due_date' => 'required|date',
      'content' => 'required|string',
      'department' => 'required|string',
      'is_mandatory' => 'boolean',
      'duration_minutes' => 'required|integer'
    ]);

    $validated['status'] = 'active';

    ComplianceTraining::create($validated);

    return redirect()->route('compliance.trainings.index')
      ->with('success', 'Training created successfully.');
  }

  public function show(ComplianceTraining $training)
  {
    return view('compliance.trainings.show', compact('training'));
  }

  public function edit(ComplianceTraining $training)
  {
    return view('compliance.trainings.edit', compact('training'));
  }

  public function update(Request $request, ComplianceTraining $training)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'training_type' => 'required|string',
      'due_date' => 'required|date',
      'content' => 'required|string',
      'department' => 'required|string',
      'is_mandatory' => 'boolean',
      'duration_minutes' => 'required|integer'
    ]);

    $training->update($validated);

    return redirect()->route('compliance.trainings.index')
      ->with('success', 'Training updated successfully.');
  }

  public function destroy(ComplianceTraining $training)
  {
    $training->delete();

    return redirect()->route('compliance.trainings.index')
      ->with('success', 'Training deleted successfully.');
  }

  public function complete(ComplianceTraining $training)
  {
    ComplianceTrainingCompletion::create([
      'training_id' => $training->id,
      'user_id' => Auth::user()->id,
      'completed_at' => now()
    ]);

    return redirect()->route('compliance.trainings.index')
      ->with('success', 'Training marked as completed.');
  }
}
