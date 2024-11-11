<?php

namespace App\Http\Controllers\HRM;

use App\Models\TrainingDevelopment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrainingDevelopmentController extends Controller
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
        $request->validate([
            'training_name' => 'required',
            'trainer' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        TrainingDevelopment::create($request->all());

        return redirect()->route('hrm.training-development')->with('success', 'Training added successfully.');
    }

    public function edit($id)
    {
        $training = TrainingDevelopment::findOrFail($id);
        return view('hrm.training-development.edit', compact('training'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'training_name' => 'required',
            'trainer' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $training = TrainingDevelopment::findOrFail($id);
        $training->update($request->all());

        return redirect()->route('hrm.training-development')->with('success', 'Training updated successfully.');
    }

    public function destroy($id)
    {
        $training = TrainingDevelopment::findOrFail($id);
        $training->delete();

        return redirect()->route('hrm.training-development')->with('success', 'Training deleted successfully.');
    }
}