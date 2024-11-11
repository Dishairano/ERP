<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequirement;
use App\Models\MaterialPlan;
use App\Models\MrpForecast;
use App\Models\MrpScenario;
use Illuminate\Http\Request;

class MrpController extends Controller
{
  /**
   * Display material planning.
   *
   * @return \Illuminate\View\View
   */
  public function planning()
  {
    $plans = MaterialPlan::with(['product', 'workOrder'])
      ->latest()
      ->paginate(10);

    return view('mrp.planning', compact('plans'));
  }

  /**
   * Display material requirements.
   *
   * @return \Illuminate\View\View
   */
  public function requirements()
  {
    $requirements = MaterialRequirement::with(['product', 'workOrder'])
      ->latest()
      ->paginate(10);

    return view('mrp.requirements', compact('requirements'));
  }

  /**
   * Display MRP forecasting.
   *
   * @return \Illuminate\View\View
   */
  public function forecasting()
  {
    $forecasts = MrpForecast::with(['product'])
      ->latest()
      ->paginate(10);

    return view('mrp.forecasting', compact('forecasts'));
  }

  /**
   * Display what-if scenarios.
   *
   * @return \Illuminate\View\View
   */
  public function scenarios()
  {
    $scenarios = MrpScenario::with(['creator'])
      ->latest()
      ->paginate(10);

    return view('mrp.scenarios', compact('scenarios'));
  }

  /**
   * Store a new material plan.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storePlan(Request $request)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'work_order_id' => 'required|exists:production_orders,id',
      'quantity' => 'required|numeric|min:0',
      'due_date' => 'required|date',
      'priority' => 'required|in:low,medium,high',
      'notes' => 'nullable|string'
    ]);

    MaterialPlan::create([
      ...$validated,
      'status' => 'draft',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('mrp.planning')
      ->with('success', 'Material plan created successfully.');
  }

  /**
   * Store a new material requirement.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeRequirement(Request $request)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'work_order_id' => 'required|exists:production_orders,id',
      'quantity' => 'required|numeric|min:0',
      'required_date' => 'required|date',
      'source' => 'required|string',
      'notes' => 'nullable|string'
    ]);

    MaterialRequirement::create([
      ...$validated,
      'status' => 'pending',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('mrp.requirements')
      ->with('success', 'Material requirement created successfully.');
  }

  /**
   * Store a new MRP forecast.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeForecast(Request $request)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'period' => 'required|string',
      'quantity' => 'required|numeric|min:0',
      'confidence' => 'required|numeric|min:0|max:100',
      'method' => 'required|string',
      'parameters' => 'required|array',
      'notes' => 'nullable|string'
    ]);

    MrpForecast::create([
      ...$validated,
      'created_by' => auth()->id()
    ]);

    return redirect()->route('mrp.forecasting')
      ->with('success', 'MRP forecast created successfully.');
  }

  /**
   * Store a new what-if scenario.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeScenario(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'parameters' => 'required|array',
      'assumptions' => 'required|array',
      'constraints' => 'nullable|array'
    ]);

    MrpScenario::create([
      ...$validated,
      'status' => 'draft',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('mrp.scenarios')
      ->with('success', 'What-if scenario created successfully.');
  }
}
