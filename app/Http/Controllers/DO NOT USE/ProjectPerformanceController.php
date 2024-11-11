<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectPerformanceMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectPerformanceController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index(Project $project)
  {
    $metrics = ProjectPerformanceMetric::where('project_id', $project->id)
      ->orderBy('measurement_date', 'desc')
      ->paginate(10);

    $performanceData = ProjectPerformanceMetric::where('project_id', $project->id)
      ->select(
        DB::raw('DATE_FORMAT(measurement_date, "%Y-%m") as month'),
        DB::raw('AVG(performance_index) as avg_performance'),
        DB::raw('SUM(variance) as total_variance')
      )
      ->groupBy('month')
      ->orderBy('month')
      ->get();

    return view('projects.performance.index', compact('project', 'metrics', 'performanceData'));
  }

  public function store(Request $request, Project $project)
  {
    $validated = $request->validate([
      'metric_type' => 'required|string',
      'planned_value' => 'required|numeric',
      'actual_value' => 'required|numeric',
      'earned_value' => 'required|numeric',
      'measurement_date' => 'required|date',
      'notes' => 'nullable|string'
    ]);

    $metric = new ProjectPerformanceMetric($validated);
    $metric->project_id = $project->id;
    $metric->created_by = Auth::id();
    $metric->calculateVariance();
    $metric->calculatePerformanceIndex();
    $metric->save();

    return redirect()
      ->route('projects.performance.index', $project)
      ->with('success', 'Performance metric recorded successfully.');
  }

  public function show(Project $project, ProjectPerformanceMetric $metric)
  {
    return view('projects.performance.show', compact('project', 'metric'));
  }

  public function update(Request $request, Project $project, ProjectPerformanceMetric $metric)
  {
    $validated = $request->validate([
      'metric_type' => 'required|string',
      'planned_value' => 'required|numeric',
      'actual_value' => 'required|numeric',
      'earned_value' => 'required|numeric',
      'measurement_date' => 'required|date',
      'notes' => 'nullable|string'
    ]);

    $metric->fill($validated);
    $metric->calculateVariance();
    $metric->calculatePerformanceIndex();
    $metric->save();

    return redirect()
      ->route('projects.performance.show', [$project, $metric])
      ->with('success', 'Performance metric updated successfully.');
  }

  public function destroy(Project $project, ProjectPerformanceMetric $metric)
  {
    $metric->delete();

    return redirect()
      ->route('projects.performance.index', $project)
      ->with('success', 'Performance metric deleted successfully.');
  }

  public function export(Project $project)
  {
    $metrics = ProjectPerformanceMetric::where('project_id', $project->id)
      ->orderBy('measurement_date')
      ->get();

    // Generate CSV data
    $csvData = [];
    $csvData[] = [
      'Date',
      'Metric Type',
      'Planned Value',
      'Actual Value',
      'Earned Value',
      'Variance',
      'Performance Index',
      'Notes'
    ];

    foreach ($metrics as $metric) {
      $csvData[] = [
        $metric->measurement_date->format('Y-m-d'),
        $metric->metric_type,
        $metric->planned_value,
        $metric->actual_value,
        $metric->earned_value,
        $metric->variance,
        $metric->performance_index,
        $metric->notes
      ];
    }

    $filename = "project_{$project->id}_performance_metrics.csv";
    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => "attachment; filename=\"$filename\""
    ];

    $callback = function () use ($csvData) {
      $file = fopen('php://output', 'w');
      foreach ($csvData as $row) {
        fputcsv($file, $row);
      }
      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }
}
