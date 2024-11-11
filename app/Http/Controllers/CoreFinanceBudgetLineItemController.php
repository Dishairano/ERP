<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceBudgetModal;
use App\Models\CoreFinanceBudgetLineItemModal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CoreFinanceBudgetLineItemController extends Controller
{
  /**
   * Display a listing of budget line items.
   */
  public function index(CoreFinanceBudgetModal $budget)
  {
    $lineItems = $budget->lineItems()
      ->when(request('category'), function ($query) {
        $query->where('category', request('category'));
      })
      ->when(request('status'), function ($query) {
        $query->where('status', request('status'));
      })
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $categories = CoreFinanceBudgetLineItemModal::getCategories();

    return view('core.finance.budgets.line-items.index', compact('budget', 'lineItems', 'categories'));
  }

  /**
   * Show the form for creating a new line item.
   */
  public function create(CoreFinanceBudgetModal $budget)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot add line items to a closed budget.']);
    }

    $categories = CoreFinanceBudgetLineItemModal::getCategories();

    return view('core.finance.budgets.line-items.create', compact('budget', 'categories'));
  }

  /**
   * Store a newly created line item.
   */
  public function store(Request $request, CoreFinanceBudgetModal $budget)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot add line items to a closed budget.']);
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'amount' => 'required|numeric|min:0',
      'category' => 'required|string|in:' . implode(',', CoreFinanceBudgetLineItemModal::getCategories()),
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|string|in:active,inactive',
      'notes' => 'nullable|string'
    ]);

    // Check if the budget has enough remaining amount
    if ($budget->remaining_amount < $validated['amount']) {
      return back()
        ->withInput()
        ->withErrors(['amount' => 'Amount exceeds budget remaining amount.']);
    }

    // Create line item
    $lineItem = $budget->lineItems()->create($validated);

    // Update budget amounts
    $budget->allocated_amount += $lineItem->amount;
    $budget->remaining_amount = $budget->total_amount - $budget->allocated_amount;
    $budget->save();

    return redirect()
      ->route('finance.budgets.line-items.index', $budget)
      ->with('success', 'Line item created successfully');
  }

  /**
   * Display the specified line item.
   */
  public function show(CoreFinanceBudgetModal $budget, CoreFinanceBudgetLineItemModal $lineItem)
  {
    return view('core.finance.budgets.line-items.show', compact('budget', 'lineItem'));
  }

  /**
   * Show the form for editing the specified line item.
   */
  public function edit(CoreFinanceBudgetModal $budget, CoreFinanceBudgetLineItemModal $lineItem)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot edit line items of a closed budget.']);
    }

    $categories = CoreFinanceBudgetLineItemModal::getCategories();

    return view('core.finance.budgets.line-items.edit', compact('budget', 'lineItem', 'categories'));
  }

  /**
   * Update the specified line item.
   */
  public function update(Request $request, CoreFinanceBudgetModal $budget, CoreFinanceBudgetLineItemModal $lineItem)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot edit line items of a closed budget.']);
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'amount' => 'required|numeric|min:0',
      'category' => 'required|string|in:' . implode(',', CoreFinanceBudgetLineItemModal::getCategories()),
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|string|in:active,inactive',
      'notes' => 'nullable|string'
    ]);

    // Check if the budget has enough remaining amount for the change
    $amountDifference = $validated['amount'] - $lineItem->amount;
    if ($budget->remaining_amount < $amountDifference) {
      return back()
        ->withInput()
        ->withErrors(['amount' => 'New amount exceeds budget remaining amount.']);
    }

    // Update line item
    $lineItem->update($validated);

    // Update budget amounts
    $budget->allocated_amount += $amountDifference;
    $budget->remaining_amount = $budget->total_amount - $budget->allocated_amount;
    $budget->save();

    return redirect()
      ->route('finance.budgets.line-items.index', $budget)
      ->with('success', 'Line item updated successfully');
  }

  /**
   * Remove the specified line item.
   */
  public function destroy(CoreFinanceBudgetModal $budget, CoreFinanceBudgetLineItemModal $lineItem)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot delete line items from a closed budget.']);
    }

    // Update budget amounts before deleting the line item
    $budget->allocated_amount -= $lineItem->amount;
    $budget->remaining_amount = $budget->total_amount - $budget->allocated_amount;
    $budget->save();

    $lineItem->delete();

    return redirect()
      ->route('finance.budgets.line-items.index', $budget)
      ->with('success', 'Line item deleted successfully');
  }

  /**
   * Bulk update line items.
   */
  public function bulkUpdate(Request $request, CoreFinanceBudgetModal $budget)
  {
    if ($budget->status === 'closed') {
      return back()->withErrors(['error' => 'Cannot update line items of a closed budget.']);
    }

    $validated = $request->validate([
      'line_items' => 'required|array',
      'line_items.*.id' => 'required|exists:finance_budget_line_items,id',
      'line_items.*.amount' => 'required|numeric|min:0',
      'line_items.*.status' => 'required|string|in:active,inactive'
    ]);

    $totalChange = 0;
    foreach ($validated['line_items'] as $item) {
      $lineItem = CoreFinanceBudgetLineItemModal::find($item['id']);
      $totalChange += $item['amount'] - $lineItem->amount;
    }

    // Check if the budget has enough remaining amount for all changes
    if ($budget->remaining_amount < $totalChange) {
      return back()->withErrors(['error' => 'Total changes exceed budget remaining amount.']);
    }

    // Update all line items
    foreach ($validated['line_items'] as $item) {
      $lineItem = CoreFinanceBudgetLineItemModal::find($item['id']);
      $lineItem->update([
        'amount' => $item['amount'],
        'status' => $item['status']
      ]);
    }

    // Update budget amounts
    $budget->allocated_amount += $totalChange;
    $budget->remaining_amount = $budget->total_amount - $budget->allocated_amount;
    $budget->save();

    return back()->with('success', 'Line items updated successfully');
  }
}
