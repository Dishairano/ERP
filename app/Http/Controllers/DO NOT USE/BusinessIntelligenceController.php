<?php

namespace App\Http\Controllers;

use App\Models\DataAnalysis;
use App\Models\DataVisualization;
use App\Models\PredictiveModel;
use App\Models\DataMiningJob;
use Illuminate\Http\Request;

class BusinessIntelligenceController extends Controller
{
  /**
   * Display data analysis.
   *
   * @return \Illuminate\View\View
   */
  public function analysis()
  {
    $analyses = DataAnalysis::with(['dataset', 'creator'])
      ->latest()
      ->paginate(10);

    return view('bi.analysis', compact('analyses'));
  }

  /**
   * Display data visualizations.
   *
   * @return \Illuminate\View\View
   */
  public function visualization()
  {
    $visualizations = DataVisualization::with(['dataset', 'creator'])
      ->latest()
      ->paginate(10);

    return view('bi.visualization', compact('visualizations'));
  }

  /**
   * Display predictive analytics.
   *
   * @return \Illuminate\View\View
   */
  public function predictive()
  {
    $models = PredictiveModel::with(['dataset', 'creator'])
      ->latest()
      ->paginate(10);

    return view('bi.predictive', compact('models'));
  }

  /**
   * Display data mining.
   *
   * @return \Illuminate\View\View
   */
  public function mining()
  {
    $jobs = DataMiningJob::with(['dataset', 'creator'])
      ->latest()
      ->paginate(10);

    return view('bi.mining', compact('jobs'));
  }

  /**
   * Store a new data analysis.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeAnalysis(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'dataset_id' => 'required|exists:datasets,id',
      'type' => 'required|string',
      'parameters' => 'required|array',
      'description' => 'nullable|string'
    ]);

    DataAnalysis::create([
      ...$validated,
      'status' => 'pending',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('bi.analysis')
      ->with('success', 'Data analysis created successfully.');
  }

  /**
   * Store a new data visualization.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeVisualization(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'dataset_id' => 'required|exists:datasets,id',
      'type' => 'required|string',
      'configuration' => 'required|array',
      'description' => 'nullable|string'
    ]);

    DataVisualization::create([
      ...$validated,
      'created_by' => auth()->id()
    ]);

    return redirect()->route('bi.visualization')
      ->with('success', 'Data visualization created successfully.');
  }

  /**
   * Store a new predictive model.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storePredictiveModel(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'dataset_id' => 'required|exists:datasets,id',
      'type' => 'required|string',
      'parameters' => 'required|array',
      'target_variable' => 'required|string',
      'features' => 'required|array',
      'description' => 'nullable|string'
    ]);

    PredictiveModel::create([
      ...$validated,
      'status' => 'training',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('bi.predictive')
      ->with('success', 'Predictive model created successfully.');
  }

  /**
   * Store a new data mining job.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeMiningJob(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'dataset_id' => 'required|exists:datasets,id',
      'algorithm' => 'required|string',
      'parameters' => 'required|array',
      'description' => 'nullable|string'
    ]);

    DataMiningJob::create([
      ...$validated,
      'status' => 'queued',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('bi.mining')
      ->with('success', 'Data mining job created successfully.');
  }
}
