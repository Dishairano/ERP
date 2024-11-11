<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\TrainingDevelopment;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
  public function index()
  {
    $trainings = TrainingDevelopment::all();
    return view('hrm.training-development.index', compact('trainings'));
  }

  public function create()
  {
    return view('hrm.training-development.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'trainer' => 'required|string|max:255',
      'location' => 'required|string|max:255',
      'max_participants' => 'required|integer|min:1'
    ]);

    TrainingDevelopment::create($validated);

    return redirect()->route('hrm.training-development')->with('success', 'Training program created successfully');
  }

  public function show($id)
  {
    $training = TrainingDevelopment::findOrFail($id);
    return view('hrm.training-development.show', compact('training'));
  }

  public function edit($id)
  {
    $training = TrainingDevelopment::findOrFail($id);
    return view('hrm.training-development.edit', compact('training'));
  }

  public function update(Request $request, $id)
  {
    $training = TrainingDevelopment::findOrFail($id);

    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'trainer' => 'required|string|max:255',
      'location' => 'required|string|max:255',
      'max_participants' => 'required|integer|min:1'
    ]);

    $training->update($validated);

    return redirect()->route('hrm.training-development')->with('success', 'Training program updated successfully');
  }

  public function destroy($id)
  {
    $training = TrainingDevelopment::findOrFail($id);
    $training->delete();

    return redirect()->route('hrm.training-development')->with('success', 'Training program deleted successfully');
  }
}
