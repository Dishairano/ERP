<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceReportModal;
use App\Models\CoreFinanceReportSectionModal;
use App\Models\CoreFinanceAccountModal;
use App\Exports\FinanceReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class CoreFinanceReportController extends Controller
{
  /**
   * Display a listing of reports.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceReportModal::query()
      ->with(['creator', 'sections']);

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter templates
    if ($request->boolean('templates')) {
      $query->where('is_template', true);
    }

    $reports = $query->orderBy('name')
      ->paginate(10);

    $types = CoreFinanceReportModal::getTypes();

    return view('core.finance.reports.index', compact('reports', 'types'));
  }

  /**
   * Show the form for creating a new report.
   */
  public function create()
  {
    $types = CoreFinanceReportModal::getTypes();
    $dateRangeTypes = CoreFinanceReportModal::getDateRangeTypes();
    $comparisonTypes = CoreFinanceReportModal::getComparisonTypes();
    $accounts = CoreFinanceAccountModal::active()->orderBy('code')->get();
    $templates = CoreFinanceReportModal::templates()->get();

    return view('core.finance.reports.create', compact('types', 'dateRangeTypes', 'comparisonTypes', 'accounts', 'templates'));
  }

  /**
   * Store a newly created report.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceReportModal::getTypes()),
      'template' => 'nullable|json',
      'parameters' => 'nullable|json',
      'filters' => 'nullable|json',
      'grouping' => 'nullable|json',
      'sorting' => 'nullable|json',
      'date_range_type' => 'required|string|in:' . implode(',', CoreFinanceReportModal::getDateRangeTypes()),
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'comparison_type' => 'required|string|in:' . implode(',', CoreFinanceReportModal::getComparisonTypes()),
      'comparison_date_range_type' => 'nullable|required_unless:comparison_type,none|string|in:' . implode(',', CoreFinanceReportModal::getDateRangeTypes()),
      'comparison_start_date' => 'nullable|required_unless:comparison_type,none|date',
      'comparison_end_date' => 'nullable|required_unless:comparison_type,none|date|after_or_equal:comparison_start_date',
      'show_percentages' => 'boolean',
      'show_variances' => 'boolean',
      'include_zero_balances' => 'boolean',
      'notes' => 'nullable|string',
      'is_template' => 'boolean',
      'status' => 'required|string|in:active,inactive',
      'sections' => 'required|array|min:1',
      'sections.*.name' => 'required|string|max:255',
      'sections.*.type' => 'required|string|in:accounts_list,calculation,custom_query',
      'sections.*.sequence' => 'required|integer|min:0',
      'sections.*.accounts' => 'nullable|required_if:sections.*.type,accounts_list|array',
      'sections.*.calculation' => 'nullable|required_if:sections.*.type,calculation|string',
      'sections.*.query' => 'nullable|required_if:sections.*.type,custom_query|string',
      'sections.*.parameters' => 'nullable|json',
      'sections.*.filters' => 'nullable|json',
      'sections.*.grouping' => 'nullable|json',
      'sections.*.sorting' => 'nullable|json',
      'sections.*.show_subtotal' => 'boolean',
      'sections.*.subtotal_label' => 'nullable|string|max:255',
      'sections.*.show_total' => 'boolean',
      'sections.*.total_label' => 'nullable|string|max:255',
      'sections.*.notes' => 'nullable|string'
    ]);

    try {
      DB::beginTransaction();

      // Create report
      $validated['created_by'] = Auth::id();
      $report = CoreFinanceReportModal::create($validated);

      // Create sections
      foreach ($validated['sections'] as $section) {
        $section['created_by'] = Auth::id();
        $report->sections()->create($section);
      }

      DB::commit();

      return redirect()
        ->route('finance.reports.show', $report)
        ->with('success', 'Report created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create report. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified report.
   */
  public function show(CoreFinanceReportModal $report)
  {
    $report->load(['creator', 'sections' => function ($query) {
      $query->ordered();
    }]);

    $data = $report->generateData();

    return view('core.finance.reports.show', compact('report', 'data'));
  }

  /**
   * Show the form for editing the specified report.
   */
  public function edit(CoreFinanceReportModal $report)
  {
    $report->load(['sections' => function ($query) {
      $query->ordered();
    }]);

    $types = CoreFinanceReportModal::getTypes();
    $dateRangeTypes = CoreFinanceReportModal::getDateRangeTypes();
    $comparisonTypes = CoreFinanceReportModal::getComparisonTypes();
    $accounts = CoreFinanceAccountModal::active()->orderBy('code')->get();
    $templates = CoreFinanceReportModal::templates()->get();

    return view('core.finance.reports.edit', compact('report', 'types', 'dateRangeTypes', 'comparisonTypes', 'accounts', 'templates'));
  }

  /**
   * Update the specified report.
   */
  public function update(Request $request, CoreFinanceReportModal $report)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceReportModal::getTypes()),
      'template' => 'nullable|json',
      'parameters' => 'nullable|json',
      'filters' => 'nullable|json',
      'grouping' => 'nullable|json',
      'sorting' => 'nullable|json',
      'date_range_type' => 'required|string|in:' . implode(',', CoreFinanceReportModal::getDateRangeTypes()),
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'comparison_type' => 'required|string|in:' . implode(',', CoreFinanceReportModal::getComparisonTypes()),
      'comparison_date_range_type' => 'nullable|required_unless:comparison_type,none|string|in:' . implode(',', CoreFinanceReportModal::getDateRangeTypes()),
      'comparison_start_date' => 'nullable|required_unless:comparison_type,none|date',
      'comparison_end_date' => 'nullable|required_unless:comparison_type,none|date|after_or_equal:comparison_start_date',
      'show_percentages' => 'boolean',
      'show_variances' => 'boolean',
      'include_zero_balances' => 'boolean',
      'notes' => 'nullable|string',
      'is_template' => 'boolean',
      'status' => 'required|string|in:active,inactive',
      'sections' => 'required|array|min:1',
      'sections.*.id' => 'nullable|exists:finance_report_sections,id',
      'sections.*.name' => 'required|string|max:255',
      'sections.*.type' => 'required|string|in:accounts_list,calculation,custom_query',
      'sections.*.sequence' => 'required|integer|min:0',
      'sections.*.accounts' => 'nullable|required_if:sections.*.type,accounts_list|array',
      'sections.*.calculation' => 'nullable|required_if:sections.*.type,calculation|string',
      'sections.*.query' => 'nullable|required_if:sections.*.type,custom_query|string',
      'sections.*.parameters' => 'nullable|json',
      'sections.*.filters' => 'nullable|json',
      'sections.*.grouping' => 'nullable|json',
      'sections.*.sorting' => 'nullable|json',
      'sections.*.show_subtotal' => 'boolean',
      'sections.*.subtotal_label' => 'nullable|string|max:255',
      'sections.*.show_total' => 'boolean',
      'sections.*.total_label' => 'nullable|string|max:255',
      'sections.*.notes' => 'nullable|string'
    ]);

    try {
      DB::beginTransaction();

      // Update report
      $report->update($validated);

      // Get current section IDs
      $currentSectionIds = $report->sections()->pluck('id')->toArray();
      $updatedSectionIds = [];

      // Update or create sections
      foreach ($validated['sections'] as $section) {
        if (isset($section['id'])) {
          // Update existing section
          $report->sections()->where('id', $section['id'])->update($section);
          $updatedSectionIds[] = $section['id'];
        } else {
          // Create new section
          $section['created_by'] = Auth::id();
          $report->sections()->create($section);
        }
      }

      // Delete removed sections
      $removedSectionIds = array_diff($currentSectionIds, $updatedSectionIds);
      if (!empty($removedSectionIds)) {
        $report->sections()->whereIn('id', $removedSectionIds)->delete();
      }

      DB::commit();

      return redirect()
        ->route('finance.reports.show', $report)
        ->with('success', 'Report updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update report. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified report.
   */
  public function destroy(CoreFinanceReportModal $report)
  {
    $report->delete();

    return redirect()
      ->route('finance.reports.index')
      ->with('success', 'Report deleted successfully');
  }

  /**
   * Generate a report preview.
   */
  public function preview(Request $request)
  {
    $validated = $request->validate([
      'type' => 'required|string|in:' . implode(',', CoreFinanceReportModal::getTypes()),
      'template' => 'nullable|json',
      'parameters' => 'nullable|json',
      'filters' => 'nullable|json',
      'grouping' => 'nullable|json',
      'sorting' => 'nullable|json',
      'date_range_type' => 'required|string|in:' . implode(',', CoreFinanceReportModal::getDateRangeTypes()),
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'comparison_type' => 'required|string|in:' . implode(',', CoreFinanceReportModal::getComparisonTypes()),
      'comparison_date_range_type' => 'nullable|required_unless:comparison_type,none|string|in:' . implode(',', CoreFinanceReportModal::getDateRangeTypes()),
      'comparison_start_date' => 'nullable|required_unless:comparison_type,none|date',
      'comparison_end_date' => 'nullable|required_unless:comparison_type,none|date|after_or_equal:comparison_start_date',
      'show_percentages' => 'boolean',
      'show_variances' => 'boolean',
      'include_zero_balances' => 'boolean',
      'sections' => 'required|array|min:1',
      'sections.*.name' => 'required|string|max:255',
      'sections.*.type' => 'required|string|in:accounts_list,calculation,custom_query',
      'sections.*.sequence' => 'required|integer|min:0',
      'sections.*.accounts' => 'nullable|required_if:sections.*.type,accounts_list|array',
      'sections.*.calculation' => 'nullable|required_if:sections.*.type,calculation|string',
      'sections.*.query' => 'nullable|required_if:sections.*.type,custom_query|string',
      'sections.*.parameters' => 'nullable|json',
      'sections.*.filters' => 'nullable|json',
      'sections.*.grouping' => 'nullable|json',
      'sections.*.sorting' => 'nullable|json',
      'sections.*.show_subtotal' => 'boolean',
      'sections.*.subtotal_label' => 'nullable|string|max:255',
      'sections.*.show_total' => 'boolean',
      'sections.*.total_label' => 'nullable|string|max:255'
    ]);

    try {
      // Create temporary report instance
      $report = new CoreFinanceReportModal($validated);
      $report->setRelation('sections', collect($validated['sections'])->map(function ($section) {
        return new CoreFinanceReportSectionModal($section);
      }));

      // Generate report data
      $data = $report->generateData();

      return response()->json([
        'success' => true,
        'data' => $data
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => 'Failed to generate report preview. ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Export the report to PDF.
   */
  public function exportPdf(CoreFinanceReportModal $report)
  {
    $report->load(['creator', 'sections' => function ($query) {
      $query->ordered();
    }]);

    $data = $report->generateData();

    $pdf = Pdf::loadView('core.finance.reports.pdf', compact('report', 'data'));

    return $pdf->download($report->name . '.pdf');
  }

  /**
   * Export the report to Excel.
   */
  public function exportExcel(CoreFinanceReportModal $report)
  {
    $report->load(['creator', 'sections' => function ($query) {
      $query->ordered();
    }]);

    $data = $report->generateData();

    return Excel::download(new FinanceReportExport($report, $data), $report->name . '.xlsx');
  }
}
