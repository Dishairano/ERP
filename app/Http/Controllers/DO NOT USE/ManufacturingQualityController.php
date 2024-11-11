<?php

namespace App\Http\Controllers;

use App\Models\QualityInspection;
use App\Models\QualityStandard;
use App\Models\ControlChart;
use App\Models\NonConformance;
use Illuminate\Http\Request;

class ManufacturingQualityController extends Controller
{
  /**
   * Display quality inspections.
   *
   * @return \Illuminate\View\View
   */
  public function inspections()
  {
    $inspections = QualityInspection::with(['product', 'workOrder', 'inspector'])
      ->latest()
      ->paginate(10);

    return view('manufacturing-quality.inspections', compact('inspections'));
  }

  /**
   * Display quality standards.
   *
   * @return \Illuminate\View\View
   */
  public function standards()
  {
    $standards = QualityStandard::with(['product', 'approver'])
      ->latest()
      ->paginate(10);

    return view('manufacturing-quality.standards', compact('standards'));
  }

  /**
   * Display control charts.
   *
   * @return \Illuminate\View\View
   */
  public function controlCharts()
  {
    $charts = ControlChart::with(['product', 'measurements'])
      ->latest()
      ->paginate(10);

    return view('manufacturing-quality.control-charts', compact('charts'));
  }

  /**
   * Display non-conformance records.
   *
   * @return \Illuminate\View\View
   */
  public function nonConformance()
  {
    $records = NonConformance::with(['product', 'workOrder', 'reporter'])
      ->latest()
      ->paginate(10);

    return view('manufacturing-quality.non-conformance', compact('records'));
  }

  /**
   * Store a new quality inspection.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeInspection(Request $request)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'work_order_id' => 'required|exists:production_orders,id',
      'inspection_date' => 'required|date',
      'type' => 'required|string',
      'result' => 'required|in:pass,fail,conditional',
      'measurements' => 'required|array',
      'notes' => 'nullable|string'
    ]);

    $inspection = QualityInspection::create([
      ...$validated,
      'inspector_id' => auth()->id()
    ]);

    foreach ($validated['measurements'] as $measurement) {
      $inspection->measurements()->create($measurement);
    }

    return redirect()->route('manufacturing-quality.inspections')
      ->with('success', 'Quality inspection recorded successfully.');
  }

  /**
   * Store a new quality standard.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeStandard(Request $request)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'specifications' => 'required|array',
      'version' => 'required|string',
      'effective_date' => 'required|date',
      'status' => 'required|in:draft,active,obsolete'
    ]);

    QualityStandard::create([
      ...$validated,
      'approver_id' => auth()->id()
    ]);

    return redirect()->route('manufacturing-quality.standards')
      ->with('success', 'Quality standard created successfully.');
  }

  /**
   * Store a new control chart.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeControlChart(Request $request)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'name' => 'required|string|max:255',
      'type' => 'required|string',
      'parameter' => 'required|string',
      'ucl' => 'required|numeric',
      'lcl' => 'required|numeric',
      'target' => 'required|numeric',
      'measurement_frequency' => 'required|string'
    ]);

    ControlChart::create($validated);

    return redirect()->route('manufacturing-quality.control-charts')
      ->with('success', 'Control chart created successfully.');
  }

  /**
   * Store a new non-conformance record.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeNonConformance(Request $request)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'work_order_id' => 'required|exists:production_orders,id',
      'type' => 'required|string',
      'severity' => 'required|in:minor,major,critical',
      'description' => 'required|string',
      'immediate_action' => 'required|string',
      'root_cause' => 'nullable|string',
      'corrective_action' => 'nullable|string',
      'preventive_action' => 'nullable|string'
    ]);

    NonConformance::create([
      ...$validated,
      'reporter_id' => auth()->id(),
      'status' => 'open'
    ]);

    return redirect()->route('manufacturing-quality.non-conformance')
      ->with('success', 'Non-conformance record created successfully.');
  }
}
