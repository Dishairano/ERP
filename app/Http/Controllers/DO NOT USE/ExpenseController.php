<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
  public function index()
  {
    $expenses = Expense::with(['budget', 'creator'])
      ->where('status', 'pending')
      ->latest()
      ->get();

    return view('budgeting.expenses.approval', compact('expenses'));
  }

  public function approve(Expense $expense)
  {
    $expense->update([
      'status' => 'approved',
      'approved_by' => Auth::id(),
      'approved_at' => now()
    ]);

    return redirect()->route('expenses.index')
      ->with('success', 'Expense approved successfully');
  }

  public function reject(Request $request, Expense $expense)
  {
    $validated = $request->validate([
      'rejection_reason' => 'required|string'
    ]);

    $expense->update([
      'status' => 'rejected',
      'approved_by' => Auth::id(),
      'approved_at' => now(),
      'rejection_reason' => $validated['rejection_reason']
    ]);

    return redirect()->route('expenses.index')
      ->with('success', 'Expense rejected successfully');
  }
}
