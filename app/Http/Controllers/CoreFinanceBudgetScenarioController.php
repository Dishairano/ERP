<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceBudgetModal;
use App\Models\CoreFinanceBudgetScenarioModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoreFinanceBudgetScenarioController extends Controller
{
  /**
   * Display a listing of budget scenarios.
   */
  public function index(CoreFinanceBudgetModal $budget)
  {
    $scenarios = $budget->scenarios()
      ->with(['creator', 'approver', 'lineItems'])
      ->when(request('type'), function ($query) {
        $query->where('type', request('type'));
      })
      ->when(request('status'), function ($query) {
        $query->where('status', request('status'));
      })
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $types = CoreFinanceBudgetScenarioModal::getTypes();

    return view('core.finance.budgets.scenarios.index', compact('budget', 'scenarios', 'types'));
  }

  /**
   * Show the form for creating a new scenario.
   */
  public function create(CoreFinanceBudgetModal $budget)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot create scenarios for a closed budget.']);
    }

    $types = CoreFinanceBudgetScenarioModal::getTypes();

    return view('core.finance.budgets.scenarios.create', compact('budget', 'types'));
  }

  /**
   * Store a newly created scenario.
   */
  public function store(Request $request, CoreFinanceBudgetModal $budget)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot create scenarios for a closed budget.']);
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'type' => 'required|string|in:' . implode(',', CoreFinanceBudgetScenarioModal::getTypes()),
      'adjustment_percentage' => 'nullable|numeric|between:-100,100',
      'status' => 'required|string|in:draft,active',
      'notes' => 'nullable|string'
    ]);

    $validated['created_by'] = Auth::id();
    $validated['budget_id'] = $budget->id;

    $scenario = CoreFinanceBudgetScenarioModal::create($validated);

    // Create line items based on budget line items
    $scenario->createLineItemsFromBudget();

    return redirect()
      ->route('finance.budgets.scenarios.show', [$budget, $scenario])
      ->with('success', 'Budget scenario created successfully');
  }

  /**
   * Display the specified scenario.
   */
  public function show(CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario)
  {
    $scenario->load(['creator', 'approver', 'lineItems']);

    return view('core.finance.budgets.scenarios.show', compact('budget', 'scenario'));
  }

  /**
   * Show the form for editing the specified scenario.
   */
  public function edit(CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot edit scenarios of a closed budget.']);
    }

    $types = CoreFinanceBudgetScenarioModal::getTypes();

    return view('core.finance.budgets.scenarios.edit', compact('budget', 'scenario', 'types'));
  }

  /**
   * Update the specified scenario.
   */
  public function update(Request $request, CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot edit scenarios of a closed budget.']);
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'type' => 'required|string|in:' . implode(',', CoreFinanceBudgetScenarioModal::getTypes()),
      'adjustment_percentage' => 'nullable|numeric|between:-100,100',
      'status' => 'required|string|in:draft,active',
      'notes' => 'nullable|string'
    ]);

    $scenario->update($validated);

    return redirect()
      ->route('finance.budgets.scenarios.show', [$budget, $scenario])
      ->with('success', 'Budget scenario updated successfully');
  }

  /**
   * Remove the specified scenario.
   */
  public function destroy(CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot delete scenarios from a closed budget.']);
    }

    $scenario->delete();

    return redirect()
      ->route('finance.budgets.scenarios.index', $budget)
      ->with('success', 'Budget scenario deleted successfully');
  }

  /**
   * Apply the scenario to the budget.
   */
  public function apply(CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot apply scenarios to a closed budget.']);
    }

    if (!$scenario->isApproved()) {
      return back()->withErrors(['error' => 'Only approved scenarios can be applied.']);
    }

    $scenario->applyToBudget();

    return redirect()
      ->route('finance.budgets.show', $budget)
      ->with('success', 'Budget scenario applied successfully');
  }

  /**
   * Approve the specified scenario.
   */
  public function approve(CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot approve scenarios of a closed budget.']);
    }

    $scenario->update([
      'approved_by' => Auth::id(),
      'approved_at' => now()
    ]);

    return redirect()
      ->route('finance.budgets.scenarios.show', [$budget, $scenario])
      ->with('success', 'Budget scenario approved successfully');
  }

  /**
   * Compare multiple scenarios.
   */
  public function compare(CoreFinanceBudgetModal $budget)
  {
    $scenarios = $budget->scenarios()
      ->whereIn('id', request('scenario_ids', []))
      ->with('lineItems')
      ->get();

    return view('core.finance.budgets.scenarios.compare', compact('budget', 'scenarios'));
  }
}
