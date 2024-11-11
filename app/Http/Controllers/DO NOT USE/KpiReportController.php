<?php

namespace App\Http\Controllers;

use App\Models\KpiReport;
use App\Models\KpiDefinition;
use App\Models\KpiReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class KpiReportController extends Controller
{
  public function index()
  {
    $reports = KpiReport::with('creator')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('kpi.reports.index', compact('reports'));
  }

  public function create()
  {
    $kpis = KpiDefinition::where('is_active', true)
      ->orderBy('category')
      ->orderBy('name')
      ->get();

    return view('kpi.reports.create', compact('kpis'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'kpi_definitions' => 'required|array',
      'kpi_definitions.*' => 'exists:kpi_definitions,id',
      'filters' => 'nullable|array',
      'visualization_settings' => 'required|array',
      'frequency' => 'nullable|string',
      'recipients' => 'nullable|array'
    ]);

    $validated['created_by'] = Auth::id();

    $report = KpiReport::create($validated);

    return redirect()->route('kpi.reports.show', $report)
      ->with('success', 'Report created successfully.');
  }

  public function show(KpiReport $report)
  {
    $report->load('creator', 'exports');
    $kpis = KpiDefinition::whereIn('id', $report->kpi_definitions)
      ->with(['values' => function ($query) use ($report) {
        $query->when(isset($report->filters['date_from']), function ($q) use ($report) {
          $q->where('measurement_date', '>=', $report->filters['date_from']);
        })
          ->when(isset($report->filters['date_to']), function ($q) use ($report) {
            $q->where('measurement_date', '<=', $report->filters['date_to']);
          });
      }])
      ->get();

    return view('kpi.reports.show', compact('report', 'kpis'));
  }

  public function export(Request $request, KpiReport $report)
  {
    $validated = $request->validate([
      'format' => 'required|in:pdf,excel'
    ]);

    // TODO: Implement actual export logic
    $export = $report->exports()->create([
      'format' => $validated['format'],
      'file_path' => 'path/to/exported/file',
      'created_by' => Auth::id()
    ]);

    return response()->json([
      'message' => 'Export started successfully',
      'export_id' => $export->id
    ]);
  }

  public function schedule(Request $request, KpiReport $report)
  {
    $validated = $request->validate([
      'frequency' => 'required|string',
      'recipients' => 'required|array',
      'recipients.*' => 'exists:users,id'
    ]);

    $report->update([
      'frequency' => $validated['frequency'],
      'recipients' => $validated['recipients']
    ]);

    return redirect()->route('kpi.reports.show', $report)
      ->with('success', 'Report schedule updated successfully.');
  }
}
