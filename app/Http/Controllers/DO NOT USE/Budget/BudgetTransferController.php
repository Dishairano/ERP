<?php

namespace App\Http\Controllers\Budget;

use App\Models\BudgetTransfer;
use Illuminate\Http\Request;

class BudgetTransferController extends BaseBudgetController
{
  public function index()
  {
    $transfers = BudgetTransfer::with(['fromBudget', 'toBudget'])->get();
    return view('budgeting.transfers.index', compact('transfers'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'from_budget_id' => 'required|exists:budgets,id',
      'to_budget_id' => 'required|exists:budgets,id|different:from_budget_id',
      'amount' => 'required|numeric|min:0',
      'reason' => 'required|string'
    ]);

    BudgetTransfer::create($validated);

    return redirect()->route('budgets.transfers')
      ->with('success', 'Budget transfer completed successfully');
  }

  public function history()
  {
    $transfers = BudgetTransfer::with(['fromBudget', 'toBudget'])
      ->orderBy('created_at', 'desc')
      ->get();
    return view('budgeting.transfers.history', compact('transfers'));
  }
}
