<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceCashFlowModal;
use App\Models\CoreFinanceCashFlowEntryModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceCashFlowEntryController extends Controller
{
  /**
   * Display a listing of cash flow entries.
   */
  public function index(CoreFinanceCashFlowModal $cashFlow, Request $request)
  {
    $query = $cashFlow->entries()
      ->with(['creator']);

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }

    // Filter by category
    if ($request->has('category')) {
      $query->where('category', $request->category);
    }

    // Filter by date range
    if ($request->has('start_date') && $request->has('end_date')) {
      $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    // Filter by reference type
    if ($request->has('reference_type')) {
      $query->where('reference_type', $request->reference_type);
    }

    // Filter by amount range
    if ($request->has('min_amount')) {
      $query->where('amount', '>=', $request->min_amount);
    }
    if ($request->has('max_amount')) {
      $query->where('amount', '<=', $request->max_amount);
    }

    $entries = $query->orderBy('date')
      ->paginate(10);

    $types = CoreFinanceCashFlowEntryModal::getTypes();
    $categories = CoreFinanceCashFlowEntryModal::getCategories();
    $referenceTypes = CoreFinanceCashFlowEntryModal::getReferenceTypes();

    return view('core.finance.cash-flows.entries.index', compact('cashFlow', 'entries', 'types', 'categories', 'referenceTypes'));
  }

  /**
   * Show the form for creating a new cash flow entry.
   */
  public function create(CoreFinanceCashFlowModal $cashFlow)
  {
    if ($cashFlow->status === 'archived') {
      return back()->withErrors(['error' => 'Cannot add entries to archived cash flows.']);
    }

    $types = CoreFinanceCashFlowEntryModal::getTypes();
    $categories = CoreFinanceCashFlowEntryModal::getCategories();
    $referenceTypes = CoreFinanceCashFlowEntryModal::getReferenceTypes();

    return view('core.finance.cash-flows.entries.create', compact('cashFlow', 'types', 'categories', 'referenceTypes'));
  }

  /**
   * Store a newly created cash flow entry.
   */
  public function store(Request $request, CoreFinanceCashFlowModal $cashFlow)
  {
    if ($cashFlow->status === 'archived') {
      return back()->withErrors(['error' => 'Cannot add entries to archived cash flows.']);
    }

    $validated = $request->validate([
      'type' => 'required|string|in:' . implode(',', CoreFinanceCashFlowEntryModal::getTypes()),
      'category' => 'required|string|in:' . implode(',', CoreFinanceCashFlowEntryModal::getCategories()),
      'date' => 'required|date|between:' . $cashFlow->start_date->format('Y-m-d') . ',' . $cashFlow->end_date->format('Y-m-d'),
      'amount' => 'required|numeric|not_in:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'reference_type' => 'nullable|string|in:' . implode(',', CoreFinanceCashFlowEntryModal::getReferenceTypes()),
      'reference_id' => 'nullable|integer',
      'description' => 'nullable|string',
      'notes' => 'nullable|string'
    ]);

    try {
      DB::beginTransaction();

      // Create entry
      $validated['cash_flow_id'] = $cashFlow->id;
      $validated['created_by'] = Auth::id();

      $entry = CoreFinanceCashFlowEntryModal::create($validated);

      // Update cash flow totals
      $this->updateCashFlowTotals($cashFlow);

      DB::commit();

      return redirect()
        ->route('finance.cash-flows.entries.show', [$cashFlow, $entry])
        ->with('success', 'Cash flow entry created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create cash flow entry. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified cash flow entry.
   */
  public function show(CoreFinanceCashFlowModal $cashFlow, CoreFinanceCashFlowEntryModal $entry)
  {
    $entry->load(['creator']);

    return view('core.finance.cash-flows.entries.show', compact('cashFlow', 'entry'));
  }

  /**
   * Show the form for editing the specified cash flow entry.
   */
  public function edit(CoreFinanceCashFlowModal $cashFlow, CoreFinanceCashFlowEntryModal $entry)
  {
    if ($cashFlow->status === 'archived') {
      return back()->withErrors(['error' => 'Cannot edit entries of archived cash flows.']);
    }

    $types = CoreFinanceCashFlowEntryModal::getTypes();
    $categories = CoreFinanceCashFlowEntryModal::getCategories();
    $referenceTypes = CoreFinanceCashFlowEntryModal::getReferenceTypes();

    return view('core.finance.cash-flows.entries.edit', compact('cashFlow', 'entry', 'types', 'categories', 'referenceTypes'));
  }

  /**
   * Update the specified cash flow entry.
   */
  public function update(Request $request, CoreFinanceCashFlowModal $cashFlow, CoreFinanceCashFlowEntryModal $entry)
  {
    if ($cashFlow->status === 'archived') {
      return back()->withErrors(['error' => 'Cannot update entries of archived cash flows.']);
    }

    $validated = $request->validate([
      'type' => 'required|string|in:' . implode(',', CoreFinanceCashFlowEntryModal::getTypes()),
      'category' => 'required|string|in:' . implode(',', CoreFinanceCashFlowEntryModal::getCategories()),
      'date' => 'required|date|between:' . $cashFlow->start_date->format('Y-m-d') . ',' . $cashFlow->end_date->format('Y-m-d'),
      'amount' => 'required|numeric|not_in:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'reference_type' => 'nullable|string|in:' . implode(',', CoreFinanceCashFlowEntryModal::getReferenceTypes()),
      'reference_id' => 'nullable|integer',
      'description' => 'nullable|string',
      'notes' => 'nullable|string'
    ]);

    try {
      DB::beginTransaction();

      // Update entry
      $entry->update($validated);

      // Update cash flow totals
      $this->updateCashFlowTotals($cashFlow);

      DB::commit();

      return redirect()
        ->route('finance.cash-flows.entries.show', [$cashFlow, $entry])
        ->with('success', 'Cash flow entry updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update cash flow entry. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified cash flow entry.
   */
  public function destroy(CoreFinanceCashFlowModal $cashFlow, CoreFinanceCashFlowEntryModal $entry)
  {
    if ($cashFlow->status === 'archived') {
      return back()->withErrors(['error' => 'Cannot delete entries from archived cash flows.']);
    }

    try {
      DB::beginTransaction();

      // Delete entry
      $entry->delete();

      // Update cash flow totals
      $this->updateCashFlowTotals($cashFlow);

      DB::commit();

      return redirect()
        ->route('finance.cash-flows.entries.index', $cashFlow)
        ->with('success', 'Cash flow entry deleted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to delete cash flow entry. ' . $e->getMessage()]);
    }
  }

  /**
   * Bulk import cash flow entries.
   */
  public function import(Request $request, CoreFinanceCashFlowModal $cashFlow)
  {
    if ($cashFlow->status === 'archived') {
      return back()->withErrors(['error' => 'Cannot import entries to archived cash flows.']);
    }

    $validated = $request->validate([
      'entries' => 'required|array',
      'entries.*.type' => 'required|string|in:' . implode(',', CoreFinanceCashFlowEntryModal::getTypes()),
      'entries.*.category' => 'required|string|in:' . implode(',', CoreFinanceCashFlowEntryModal::getCategories()),
      'entries.*.date' => 'required|date|between:' . $cashFlow->start_date->format('Y-m-d') . ',' . $cashFlow->end_date->format('Y-m-d'),
      'entries.*.amount' => 'required|numeric|not_in:0',
      'entries.*.currency' => 'required|string|size:3',
      'entries.*.exchange_rate' => 'required|numeric|min:0',
      'entries.*.description' => 'nullable|string'
    ]);

    try {
      DB::beginTransaction();

      foreach ($validated['entries'] as $entryData) {
        $entryData['cash_flow_id'] = $cashFlow->id;
        $entryData['created_by'] = Auth::id();
        CoreFinanceCashFlowEntryModal::create($entryData);
      }

      // Update cash flow totals
      $this->updateCashFlowTotals($cashFlow);

      DB::commit();

      return redirect()
        ->route('finance.cash-flows.entries.index', $cashFlow)
        ->with('success', 'Cash flow entries imported successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to import cash flow entries. ' . $e->getMessage()]);
    }
  }

  /**
   * Update cash flow totals based on entries.
   */
  private function updateCashFlowTotals(CoreFinanceCashFlowModal $cashFlow): void
  {
    $entries = $cashFlow->entries;

    $operatingCashFlow = $entries->where('type', 'operating')->sum('amount');
    $investingCashFlow = $entries->where('type', 'investing')->sum('amount');
    $financingCashFlow = $entries->where('type', 'financing')->sum('amount');
    $netCashFlow = $operatingCashFlow + $investingCashFlow + $financingCashFlow;

    $cashFlow->update([
      'closing_balance' => $cashFlow->opening_balance + $netCashFlow,
      'net_cash_flow' => $netCashFlow,
      'operating_cash_flow' => $operatingCashFlow,
      'investing_cash_flow' => $investingCashFlow,
      'financing_cash_flow' => $financingCashFlow
    ]);
  }
}
