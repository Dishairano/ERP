<?php

namespace App\Http\Controllers;

use App\Models\CoreFinancePresetDepartmentModal;
use App\Models\CoreFinancePresetLineItemModal;
use Illuminate\Http\Request;

class CoreFinancePresetLineItemController extends Controller
{
  /**
   * Display a listing of preset line items.
   */
  public function index(CoreFinancePresetDepartmentModal $preset)
  {
    $lineItems = $preset->lineItems()
      ->when(request('category'), function ($query) {
        $query->where('category', request('category'));
      })
      ->when(request('status'), function ($query) {
        $query->where('status', request('status'));
      })
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $categories = CoreFinancePresetLineItemModal::getCategories();

    return view('core.finance.presets.line-items.index', compact('preset', 'lineItems', 'categories'));
  }

  /**
   * Show the form for creating a new line item.
   */
  public function create(CoreFinancePresetDepartmentModal $preset)
  {
    if ($preset->isApproved()) {
      return back()->withErrors(['error' => 'Cannot add line items to an approved preset.']);
    }

    $categories = CoreFinancePresetLineItemModal::getCategories();

    return view('core.finance.presets.line-items.create', compact('preset', 'categories'));
  }

  /**
   * Store a newly created line item.
   */
  public function store(Request $request, CoreFinancePresetDepartmentModal $preset)
  {
    if ($preset->isApproved()) {
      return back()->withErrors(['error' => 'Cannot add line items to an approved preset.']);
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'amount' => 'required|numeric|min:0',
      'category' => 'required|string|in:' . implode(',', CoreFinancePresetLineItemModal::getCategories()),
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|string|in:active,inactive',
      'notes' => 'nullable|string'
    ]);

    // Create line item
    $lineItem = $preset->lineItems()->create($validated);

    // Update preset total amount
    $preset->updateTotalAmount();

    return redirect()
      ->route('finance.presets.line-items.index', $preset)
      ->with('success', 'Line item created successfully');
  }

  /**
   * Display the specified line item.
   */
  public function show(CoreFinancePresetDepartmentModal $preset, CoreFinancePresetLineItemModal $lineItem)
  {
    return view('core.finance.presets.line-items.show', compact('preset', 'lineItem'));
  }

  /**
   * Show the form for editing the specified line item.
   */
  public function edit(CoreFinancePresetDepartmentModal $preset, CoreFinancePresetLineItemModal $lineItem)
  {
    if ($preset->isApproved()) {
      return back()->withErrors(['error' => 'Cannot edit line items of an approved preset.']);
    }

    $categories = CoreFinancePresetLineItemModal::getCategories();

    return view('core.finance.presets.line-items.edit', compact('preset', 'lineItem', 'categories'));
  }

  /**
   * Update the specified line item.
   */
  public function update(Request $request, CoreFinancePresetDepartmentModal $preset, CoreFinancePresetLineItemModal $lineItem)
  {
    if ($preset->isApproved()) {
      return back()->withErrors(['error' => 'Cannot edit line items of an approved preset.']);
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'amount' => 'required|numeric|min:0',
      'category' => 'required|string|in:' . implode(',', CoreFinancePresetLineItemModal::getCategories()),
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|string|in:active,inactive',
      'notes' => 'nullable|string'
    ]);

    // Update line item
    $lineItem->update($validated);

    // Update preset total amount
    $preset->updateTotalAmount();

    return redirect()
      ->route('finance.presets.line-items.index', $preset)
      ->with('success', 'Line item updated successfully');
  }

  /**
   * Remove the specified line item.
   */
  public function destroy(CoreFinancePresetDepartmentModal $preset, CoreFinancePresetLineItemModal $lineItem)
  {
    if ($preset->isApproved()) {
      return back()->withErrors(['error' => 'Cannot delete line items from an approved preset.']);
    }

    $lineItem->delete();

    // Update preset total amount
    $preset->updateTotalAmount();

    return redirect()
      ->route('finance.presets.line-items.index', $preset)
      ->with('success', 'Line item deleted successfully');
  }

  /**
   * Bulk update line items.
   */
  public function bulkUpdate(Request $request, CoreFinancePresetDepartmentModal $preset)
  {
    if ($preset->isApproved()) {
      return back()->withErrors(['error' => 'Cannot update line items of an approved preset.']);
    }

    $validated = $request->validate([
      'line_items' => 'required|array',
      'line_items.*.id' => 'required|exists:finance_preset_line_items,id',
      'line_items.*.amount' => 'required|numeric|min:0',
      'line_items.*.status' => 'required|string|in:active,inactive'
    ]);

    // Update all line items
    foreach ($validated['line_items'] as $item) {
      $lineItem = CoreFinancePresetLineItemModal::find($item['id']);
      $lineItem->update([
        'amount' => $item['amount'],
        'status' => $item['status']
      ]);
    }

    // Update preset total amount
    $preset->updateTotalAmount();

    return back()->with('success', 'Line items updated successfully');
  }
}
