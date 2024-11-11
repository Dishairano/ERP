<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceCashFlowModal;
use App\Models\CoreFinanceCashFlowEntryModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceCashFlowController extends Controller
{
  /**
   * Display a listing of cash flows.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceCashFlowModal::query()
      ->with(['creator']);

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }

    // Filter by category
    if ($request->has('category')) {
      $query->where('category', $request->category);
    }

    // Filter by period type
    if ($request->has('period_type')) {
      $query->where('period_type', $request->period_type);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by date range
    if ($request->has('start_date') && $request->has('end_date')) {
      $query->where(function ($q) use ($request) {
        $q->whereBetween('start_date', [$request->start_date, $request->end_date])
          ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
          ->orWhere(function ($q) use ($request) {
            $q->where('start_date', '<=', $request->start_date)
              ->where('end_date', '>=', $request->end_date);
          });
      });
    }

    $cashFlows = $query->orderBy('start_date', 'desc')
      ->paginate(10);

    $types = CoreFinanceCashFlowModal::getTypes();
    $categories = CoreFinanceCashFlowModal::getCategories();
    $periodTypes = CoreFinanceCashFlowModal::getPeriodTypes();

    return view('core.finance.cash-flows.index', compact('cashFlows', 'types', 'categories', 'periodTypes'));
  }

  /**
   * Show the form for creating a new cash flow.
   */
  public function create()
  {
    $types = CoreFinanceCashFlowModal::getTypes();
    $categories = CoreFinanceCashFlowModal::getCategories();
    $periodTypes = CoreFinanceCashFlowModal::getPeriodTypes();

    return view('core.finance.cash-flows.create', compact('types', 'categories', 'periodTypes'));
  }

  /**
   * Store a newly created cash flow.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceCashFlowModal::getTypes()),
      'category' => 'required|string|in:' . implode(',', CoreFinanceCashFlowModal::getCategories()),
      'period_type' => 'required|string|in:' . implode(',', CoreFinanceCashFlowModal::getPeriodTypes()),
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'opening_balance' => 'required|numeric',
      'description' => 'nullable|string',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:draft,published,archived'
    ]);

    try {
      DB::beginTransaction();

      // Create cash flow
      $validated['created_by'] = Auth::id();
      $validated['closing_balance'] = $validated['opening_balance'];
      $validated['net_cash_flow'] = 0;
      $validated['operating_cash_flow'] = 0;
      $validated['investing_cash_flow'] = 0;
      $validated['financing_cash_flow'] = 0;

      $cashFlow = CoreFinanceCashFlowModal::create($validated);

      DB::commit();

      return redirect()
        ->route('finance.cash-flows.show', $cashFlow)
        ->with('success', 'Cash flow created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create cash flow. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified cash flow.
   */
  public function show(CoreFinanceCashFlowModal $cashFlow)
  {
    $cashFlow->load(['creator', 'entries' => function ($query) {
      $query->orderBy('date');
    }]);

    return view('core.finance.cash-flows.show', compact('cashFlow'));
  }

  /**
   * Show the form for editing the specified cash flow.
   */
  public function edit(CoreFinanceCashFlowModal $cashFlow)
  {
    if ($cashFlow->status === 'archived') {
      return back()->withErrors(['error' => 'Archived cash flows cannot be edited.']);
    }

    $types = CoreFinanceCashFlowModal::getTypes();
    $categories = CoreFinanceCashFlowModal::getCategories();
    $periodTypes = CoreFinanceCashFlowModal::getPeriodTypes();

    return view('core.finance.cash-flows.edit', compact('cashFlow', 'types', 'categories', 'periodTypes'));
  }

  /**
   * Update the specified cash flow.
   */
  public function update(Request $request, CoreFinanceCashFlowModal $cashFlow)
  {
    if ($cashFlow->status === 'archived') {
      return back()->withErrors(['error' => 'Archived cash flows cannot be updated.']);
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceCashFlowModal::getTypes()),
      'category' => 'required|string|in:' . implode(',', CoreFinanceCashFlowModal::getCategories()),
      'period_type' => 'required|string|in:' . implode(',', CoreFinanceCashFlowModal::getPeriodTypes()),
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'opening_balance' => 'required|numeric',
      'description' => 'nullable|string',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:draft,published,archived'
    ]);

    try {
      DB::beginTransaction();

      // Calculate cash flow totals
      $entries = $cashFlow->entries;
      $operatingCashFlow = $entries->where('type', 'operating')->sum('amount');
      $investingCashFlow = $entries->where('type', 'investing')->sum('amount');
      $financingCashFlow = $entries->where('type', 'financing')->sum('amount');
      $netCashFlow = $operatingCashFlow + $investingCashFlow + $financingCashFlow;

      // Update cash flow
      $validated['closing_balance'] = $validated['opening_balance'] + $netCashFlow;
      $validated['net_cash_flow'] = $netCashFlow;
      $validated['operating_cash_flow'] = $operatingCashFlow;
      $validated['investing_cash_flow'] = $investingCashFlow;
      $validated['financing_cash_flow'] = $financingCashFlow;

      $cashFlow->update($validated);

      DB::commit();

      return redirect()
        ->route('finance.cash-flows.show', $cashFlow)
        ->with('success', 'Cash flow updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update cash flow. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified cash flow.
   */
  public function destroy(CoreFinanceCashFlowModal $cashFlow)
  {
    try {
      DB::beginTransaction();

      // Delete entries
      $cashFlow->entries()->delete();

      // Delete cash flow
      $cashFlow->delete();

      DB::commit();

      return redirect()
        ->route('finance.cash-flows.index')
        ->with('success', 'Cash flow deleted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to delete cash flow. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the cash flow analysis.
   */
  public function analysis(CoreFinanceCashFlowModal $cashFlow)
  {
    $cashFlow->load(['entries' => function ($query) {
      $query->orderBy('date');
    }]);

    return view('core.finance.cash-flows.analysis', compact('cashFlow'));
  }

  /**
   * Display the cash flow trend report.
   */
  public function trend(Request $request)
  {
    $startDate = $request->get('start_date', now()->startOfYear());
    $endDate = $request->get('end_date', now()->endOfYear());

    $cashFlows = CoreFinanceCashFlowModal::with(['entries'])
      ->where(function ($query) use ($startDate, $endDate) {
        $query->whereBetween('start_date', [$startDate, $endDate])
          ->orWhereBetween('end_date', [$startDate, $endDate])
          ->orWhere(function ($query) use ($startDate, $endDate) {
            $query->where('start_date', '<=', $startDate)
              ->where('end_date', '>=', $endDate);
          });
      })
      ->get();

    return view('core.finance.cash-flows.trend', compact('cashFlows', 'startDate', 'endDate'));
  }

  /**
   * Display the cash flow ratio analysis.
   */
  public function ratios(CoreFinanceCashFlowModal $cashFlow)
  {
    $cashFlow->load(['entries' => function ($query) {
      $query->orderBy('date');
    }]);

    return view('core.finance.cash-flows.ratios', compact('cashFlow'));
  }

  /**
   * Display the cash flow projection report.
   */
  public function projection(Request $request)
  {
    $startDate = $request->get('start_date', now());
    $periods = $request->get('periods', 12);
    $periodType = $request->get('period_type', 'monthly');

    $cashFlows = CoreFinanceCashFlowModal::with(['entries'])
      ->where('end_date', '>=', $startDate)
      ->get();

    return view('core.finance.cash-flows.projection', compact('cashFlows', 'startDate', 'periods', 'periodType'));
  }
}
