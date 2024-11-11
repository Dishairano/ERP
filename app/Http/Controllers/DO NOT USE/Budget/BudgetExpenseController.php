<?php

namespace App\Http\Controllers\Budget;

use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Http\Request;

class BudgetExpenseController extends BaseBudgetController
{
  public function index(Budget $budget)
  {
    $expenses = $budget->expenses()->orderBy('created_at', 'desc')->get();
    return view('budgeting.expenses.index', compact('budget', 'expenses'));
  }

  public function approvals()
  {
    $pendingExpenses = Expense::with('budget')
      ->where('status', 'pending')
      ->orderBy('created_at', 'desc')
      ->get();
    return view('budgeting.expenses.approval', compact('pendingExpenses'));
  }

  public function approve(Request $request, Expense $expense)
  {
    $expense->update(['status' => 'approved']);
    return redirect()->route('budgets.expenses.approvals')
      ->with('success', 'Expense approved successfully');
  }

  public function reject(Request $request, Expense $expense)
  {
    $expense->update(['status' => 'rejected']);
    return redirect()->route('budgets.expenses.approvals')
      ->with('success', 'Expense rejected successfully');
  }
}
