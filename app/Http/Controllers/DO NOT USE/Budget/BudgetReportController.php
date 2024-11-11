<?php

namespace App\Http\Controllers\Budget;

use App\Models\BudgetReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BudgetExport;
use Carbon\Carbon;

class BudgetReportController extends BaseBudgetController
{
  public function index()
  {
    $reports = BudgetReport::orderBy('created_at', 'desc')->get();
    return view('budgeting.reports.index', compact('reports'));
  }

  public function generate(Request $request)
  {
    // Implementation for generating reports
    return redirect()->route('budgets.reports')
      ->with('success', 'Report generated successfully');
  }

  public function export(Request $request)
  {
    $budgets = $this->getActiveBudgets()
      ->get()
      ->map(function ($budget) {
        return [
          'category' => $budget->category ?? 'N/A',
          'planned' => $budget->planned_amount ?? 0,
          'actual' => $budget->actual_amount ?? 0,
          'variance' => ($budget->planned_amount ?? 0) - ($budget->actual_amount ?? 0),
          'spent_percentage' => $this->calculateSpentPercentage(
            $budget->planned_amount,
            $budget->actual_amount
          ),
          'department' => $budget->department?->name ?? 'N/A',
          'project' => $budget->project?->name ?? 'N/A',
          'cost_category' => $budget->costCategory?->name ?? 'N/A'
        ];
      })->toArray();

    $format = $request->query('format', 'excel');
    $filename = 'budget-report-' . Carbon::now()->format('Y-m-d');

    if ($format === 'pdf') {
      $pdf = PDF::loadView('budgeting.reports.pdf', ['data' => $budgets]);
      return $pdf->download($filename . '.pdf');
    }

    return Excel::download(new BudgetExport($budgets), $filename . '.xlsx');
  }

  public function automatedReports()
  {
    $reports = BudgetReport::where('is_automated', true)
      ->orderBy('created_at', 'desc')
      ->get();
    return view('budgeting.reports.automated', compact('reports'));
  }
}
