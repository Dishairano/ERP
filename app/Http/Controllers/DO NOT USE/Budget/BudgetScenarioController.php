<?php

namespace App\Http\Controllers\Budget;

use App\Models\Budget;
use App\Models\BudgetScenario;
use Illuminate\Http\Request;

class BudgetScenarioController extends BaseBudgetController
{
  public function index()
  {
    $scenarios = BudgetScenario::with(['budget' => function ($query) {
      $query->where('is_active', true);
    }])->latest()->get();

    $budgets = Budget::where('is_active', true)->get();

    return view('budgeting.scenarios.index', compact('scenarios', 'budgets'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'budget_id' => 'required|exists:budgets,id',
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'modified_amount' => 'required|numeric|min:0',
      'adjustment_type' => 'required|in:increase,decrease,fixed',
      'adjustment_percentage' => 'required_if:adjustment_type,increase,decrease|nullable|numeric|min:0|max:100',
      'reason' => 'required|string'
    ]);

    $budget = Budget::findOrFail($validated['budget_id']);
    $originalAmount = $budget->planned_amount;

    // Calculate modified amount based on adjustment type
    if ($validated['adjustment_type'] !== 'fixed') {
      $percentage = $validated['adjustment_percentage'] / 100;
      $validated['modified_amount'] = $validated['adjustment_type'] === 'increase'
        ? $originalAmount * (1 + $percentage)
        : $originalAmount * (1 - $percentage);
    }

    $scenario = BudgetScenario::create([
      'budget_id' => $validated['budget_id'],
      'name' => $validated['name'],
      'description' => $validated['description'],
      'original_amount' => $originalAmount,
      'modified_amount' => $validated['modified_amount'],
      'adjustment_type' => $validated['adjustment_type'],
      'adjustment_percentage' => $validated['adjustment_percentage'],
      'reason' => $validated['reason'],
      'status' => 'pending'
    ]);

    return redirect()->route('budgets.scenarios')
      ->with('success', 'Budget scenario created successfully');
  }

  public function show(BudgetScenario $scenario)
  {
    return view('budgeting.scenarios.show', compact('scenario'));
  }

  public function apply(BudgetScenario $scenario)
  {
    $budget = $scenario->budget;

    if (!$budget || !$budget->is_active) {
      return redirect()->route('budgets.scenarios')
        ->with('error', 'Cannot apply scenario: associated budget not found or inactive');
    }

    $budget->update([
      'planned_amount' => $scenario->modified_amount
    ]);

    $scenario->update([
      'status' => 'applied',
      'applied_at' => now()
    ]);

    $budget->logAuditTrail(
      'scenario_applied',
      "Applied scenario '{$scenario->name}' changing planned amount from {$scenario->original_amount} to {$scenario->modified_amount}"
    );

    return redirect()->route('budgets.scenarios')
      ->with('success', 'Budget scenario applied successfully');
  }

  public function reject(BudgetScenario $scenario)
  {
    $scenario->update([
      'status' => 'rejected',
      'rejected_at' => now()
    ]);

    return redirect()->route('budgets.scenarios')
      ->with('success', 'Budget scenario rejected');
  }
}
