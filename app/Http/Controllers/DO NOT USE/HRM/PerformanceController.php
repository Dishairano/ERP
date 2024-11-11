<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\PerformanceEvaluation;
use Illuminate\Http\Request;

class PerformanceController extends Controller
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
    $validated = $request->validate([
      'employee_id' => 'required|exists:users,id',
      'evaluation_date' => 'required|date',
      'evaluator_id' => 'required|exists:users,id',
      'performance_score' => 'required|numeric|min:1|max:5',
      'comments' => 'required|string'
    ]);

    PerformanceEvaluation::create($validated);

    return redirect()->route('hrm.performance-evaluations')->with('success', 'Performance evaluation created successfully');
  }

  public function show($id)
  {
    $evaluation = PerformanceEvaluation::findOrFail($id);
    return view('hrm.performance-evaluations.show', compact('evaluation'));
  }

  public function edit($id)
  {
    $evaluation = PerformanceEvaluation::findOrFail($id);
    return view('hrm.performance-evaluations.edit', compact('evaluation'));
  }

  public function update(Request $request, $id)
  {
    $evaluation = PerformanceEvaluation::findOrFail($id);

    $validated = $request->validate([
      'employee_id' => 'required|exists:users,id',
      'evaluation_date' => 'required|date',
      'evaluator_id' => 'required|exists:users,id',
      'performance_score' => 'required|numeric|min:1|max:5',
      'comments' => 'required|string'
    ]);

    $evaluation->update($validated);

    return redirect()->route('hrm.performance-evaluations')->with('success', 'Performance evaluation updated successfully');
  }

  public function destroy($id)
  {
    $evaluation = PerformanceEvaluation::findOrFail($id);
    $evaluation->delete();

    return redirect()->route('hrm.performance-evaluations')->with('success', 'Performance evaluation deleted successfully');
  }
}
