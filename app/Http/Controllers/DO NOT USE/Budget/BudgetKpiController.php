<?php

namespace App\Http\Controllers\Budget;

use App\Models\Budget;
use App\Models\BudgetKpi;
use Illuminate\Http\Request;

class BudgetKpiController extends BaseBudgetController
{
  public function dashboard()
  {
    $kpis = BudgetKpi::with('budget')->get();
    return view('budgeting.kpis.dashboard', compact('kpis'));
  }

  public function store(Request $request, Budget $budget)
  {
    $validatedData = $request->validate([
      'name' => 'required|string',
      'target_value' => 'required|numeric',
      'actual_value' => 'required|numeric',
      'status' => 'required|in:on_track,warning,critical'
    ]);

    $kpi = $budget->kpis()->create($validatedData);
    $budget->logAuditTrail('kpi_created', "KPI '{$kpi->name}' created");

    return redirect()->route('budgets.show', $budget)
      ->with('success', 'Budget KPI created successfully.');
  }

  public function performance()
  {
    $kpis = BudgetKpi::with('budget')->get();
    return view('budgeting.kpis.performance', compact('kpis'));
  }
}
