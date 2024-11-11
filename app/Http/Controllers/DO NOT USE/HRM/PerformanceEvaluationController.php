<?php

namespace App\Http\Controllers\HRM;

use App\Models\PerformanceEvaluation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PerformanceEvaluationController extends Controller
{
    public function index()
    {
        $evaluations = PerformanceEvaluation::all();
        return view('hrm.performance-evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        return view('hrm.performance-evaluations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required',
            'evaluation_score' => 'required|numeric',
            'evaluation_date' => 'required|date'
        ]);

        PerformanceEvaluation::create($request->all());

        return redirect()->route('hrm.performance-evaluations')->with('success', 'Evaluation added successfully.');
    }

    public function edit($id)
    {
        $evaluation = PerformanceEvaluation::findOrFail($id);
        return view('hrm.performance-evaluations.edit', compact('evaluation'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_name' => 'required',
            'evaluation_score' => 'required|numeric',
            'evaluation_date' => 'required|date'
        ]);

        $evaluation = PerformanceEvaluation::findOrFail($id);
        $evaluation->update($request->all());

        return redirect()->route('hrm.performance-evaluations')->with('success', 'Evaluation updated successfully.');
    }

    public function destroy($id)
    {
        $evaluation = PerformanceEvaluation::findOrFail($id);
        $evaluation->delete();

        return redirect()->route('hrm.performance-evaluations')->with('success', 'Evaluation deleted successfully.');
    }
}