<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceBudgetModal;
use App\Models\CoreFinanceBudgetScenarioModal;
use App\Models\CoreFinanceBudgetScenarioLineItemModal;
use Illuminate\Http\Request;

class CoreFinanceBudgetScenarioLineItemController extends Controller
{
  /**
   * Display a listing of scenario line items.
   */
  public function index(CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario)
  {
    $lineItems = $scenario->lineItems()
      ->when(request('category'), function ($query) {
        $query->where('category', request('category'));
      })
      ->when(request('status'), function ($query) {
        $query->where('status', request('status'));
      })
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $categories = CoreFinanceBudgetScenarioLineItemModal::getCategories();

    return view('core.finance.budgets.scenarios.line-items.index', compact('budget', 'scenario', 'lineItems', 'categories'));
  }

  /**
   * Show the form for creating a new line item.
   */
  public function create(CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot add line items to a closed budget scenario.']);
    }

    $categories = CoreFinanceBudgetScenarioLineItemModal::getCategories();

    return view('core.finance.budgets.scenarios.line-items.create', compact('budget', 'scenario', 'categories'));
  }

  /**
   * Store a newly created line item.
   */
  public function store(Request $request, CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot add line items to a closed budget scenario.']);
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'amount' => 'required|numeric|min:0',
      'category' => 'required|string|in:' . implode(',', CoreFinanceBudgetScenarioLineItemModal::getCategories()),
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|string|in:active,inactive',
      'notes' => 'nullable|string'
    ]);

    // Create line item
    $lineItem = $scenario->lineItems()->create($validated);

    // Update scenario total amount
    $scenario->updateTotalAmount();

    return redirect()
      ->route('finance.budgets.scenarios.line-items.index', [$budget, $scenario])
      ->with('success', 'Line item created successfully');
  }

  /**
   * Display the specified line item.
   */
  public function show(CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario, CoreFinanceBudgetScenarioLineItemModal $lineItem)
  {
    return view('core.finance.budgets.scenarios.line-items.show', compact('budget', 'scenario', 'lineItem'));
  }

  /**
   * Show the form for editing the specified line item.
   */
  public function edit(CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario, CoreFinanceBudgetScenarioLineItemModal $lineItem)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot edit line items of a closed budget scenario.']);
    }

    $categories = CoreFinanceBudgetScenarioLineItemModal::getCategories();

    return view('core.finance.budgets.scenarios.line-items.edit', compact('budget', 'scenario', 'lineItem', 'categories'));
  }

  /**
   * Update the specified line item.
   */
  public function update(Request $request, CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario, CoreFinanceBudgetScenarioLineItemModal $lineItem)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot edit line items of a closed budget scenario.']);
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'amount' => 'required|numeric|min:0',
      'category' => 'required|string|in:' . implode(',', CoreFinanceBudgetScenarioLineItemModal::getCategories()),
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|string|in:active,inactive',
      'notes' => 'nullable|string'
    ]);

    // Update line item
    $lineItem->update($validated);

    // Update scenario total amount
    $scenario->updateTotalAmount();

    return redirect()
      ->route('finance.budgets.scenarios.line-items.index', [$budget, $scenario])
      ->with('success', 'Line item updated successfully');
  }

  /**
   * Remove the specified line item.
   */
  public function destroy(CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario, CoreFinanceBudgetScenarioLineItemModal $lineItem)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot delete line items from a closed budget scenario.']);
    }

    $lineItem->delete();

    // Update scenario total amount
    $scenario->updateTotalAmount();

    return redirect()
      ->route('finance.budgets.scenarios.line-items.index', [$budget, $scenario])
      ->with('success', 'Line item deleted successfully');
  }

  /**
   * Bulk update line items.
   */
  public function bulkUpdate(Request $request, CoreFinanceBudgetModal $budget, CoreFinanceBudgetScenarioModal $scenario)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot update line items of a closed budget scenario.']);
    }

    $validated = $request->validate([
      'line_items' => 'required|array',
      'line_items.*.id' => 'required|exists:finance_budget_scenario_line_items,id',
      'line_items.*.amount' => 'required|numeric|min:0',
      'line_items.*.status' => 'required|string|in:active,inactive'
    ]);

    // Update all line items
    foreach ($validated['line_items'] as $item) {
      $lineItem = CoreFinanceBudgetScenarioLineItemModal::find($item['id']);
      $lineItem->update([
        'amount' => $item['amount'],
        'status' => $item['status']
      ]);
    }

    // Update scenario total amount
    $scenario->updateTotalAmount();

    return back()->with('success', 'Line items updated successfully');
  }
}
