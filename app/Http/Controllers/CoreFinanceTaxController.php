<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceTaxModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceTaxController extends Controller
{
  /**
   * Display a listing of taxes.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceTaxModal::query()
      ->with(['account', 'creator']);

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by applies_to
    if ($request->has('applies_to')) {
      $query->appliesTo($request->applies_to);
    }

    // Filter by country
    if ($request->has('country')) {
      $query->where('country', $request->country);
    }

    // Filter by region
    if ($request->has('region')) {
      $query->where('region', $request->region);
    }

    // Filter by effective date
    if ($request->has('effective_date')) {
      $query->effectiveAt($request->effective_date);
    }

    $taxes = $query->orderBy('code')
      ->paginate(10);

    $types = CoreFinanceTaxModal::getTypes();
    $appliesTo = CoreFinanceTaxModal::getAppliesTo();

    return view('core.finance.taxes.index', compact('taxes', 'types', 'appliesTo'));
  }

  /**
   * Show the form for creating a new tax.
   */
  public function create()
  {
    $types = CoreFinanceTaxModal::getTypes();
    $appliesTo = CoreFinanceTaxModal::getAppliesTo();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'liability')
      ->orderBy('code')
      ->get();

    return view('core.finance.taxes.create', compact('types', 'appliesTo', 'accounts'));
  }

  /**
   * Store a newly created tax.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_taxes,code',
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceTaxModal::getTypes()),
      'rate' => 'required|numeric|between:0,100',
      'effective_from' => 'required|date',
      'effective_to' => 'nullable|date|after:effective_from',
      'account_id' => 'required|exists:finance_accounts,id',
      'is_recoverable' => 'boolean',
      'is_compound' => 'boolean',
      'applies_to' => 'required|string|in:' . implode(',', CoreFinanceTaxModal::getAppliesTo()),
      'country' => 'nullable|string|max:100',
      'region' => 'nullable|string|max:100',
      'description' => 'nullable|string',
      'status' => 'required|string|in:active,inactive'
    ]);

    $validated['created_by'] = Auth::id();

    $tax = CoreFinanceTaxModal::create($validated);

    return redirect()
      ->route('finance.taxes.show', $tax)
      ->with('success', 'Tax created successfully');
  }

  /**
   * Display the specified tax.
   */
  public function show(CoreFinanceTaxModal $tax)
  {
    $tax->load(['account', 'creator', 'transactions' => function ($query) {
      $query->latest()->limit(10);
    }]);

    return view('core.finance.taxes.show', compact('tax'));
  }

  /**
   * Show the form for editing the specified tax.
   */
  public function edit(CoreFinanceTaxModal $tax)
  {
    $types = CoreFinanceTaxModal::getTypes();
    $appliesTo = CoreFinanceTaxModal::getAppliesTo();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'liability')
      ->orderBy('code')
      ->get();

    return view('core.finance.taxes.edit', compact('tax', 'types', 'appliesTo', 'accounts'));
  }

  /**
   * Update the specified tax.
   */
  public function update(Request $request, CoreFinanceTaxModal $tax)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_taxes,code,' . $tax->id,
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceTaxModal::getTypes()),
      'rate' => 'required|numeric|between:0,100',
      'effective_from' => 'required|date',
      'effective_to' => 'nullable|date|after:effective_from',
      'account_id' => 'required|exists:finance_accounts,id',
      'is_recoverable' => 'boolean',
      'is_compound' => 'boolean',
      'applies_to' => 'required|string|in:' . implode(',', CoreFinanceTaxModal::getAppliesTo()),
      'country' => 'nullable|string|max:100',
      'region' => 'nullable|string|max:100',
      'description' => 'nullable|string',
      'status' => 'required|string|in:active,inactive'
    ]);

    $tax->update($validated);

    return redirect()
      ->route('finance.taxes.show', $tax)
      ->with('success', 'Tax updated successfully');
  }

  /**
   * Remove the specified tax.
   */
  public function destroy(CoreFinanceTaxModal $tax)
  {
    if ($tax->transactions()->exists()) {
      return back()->withErrors(['error' => 'Cannot delete tax with associated transactions.']);
    }

    $tax->delete();

    return redirect()
      ->route('finance.taxes.index')
      ->with('success', 'Tax deleted successfully');
  }

  /**
   * Display the tax filing report.
   */
  public function filingReport(Request $request)
  {
    $period = $request->get('period', now()->format('Y-m'));
    $type = $request->get('type');

    $query = CoreFinanceTaxModal::query()
      ->with(['transactions' => function ($query) use ($period) {
        $query->where('filing_period', $period);
      }]);

    if ($type) {
      $query->where('type', $type);
    }

    $taxes = $query->get()
      ->map(function ($tax) {
        $transactions = $tax->transactions;
        return [
          'tax' => $tax,
          'total_base_amount' => $transactions->sum('base_amount'),
          'total_tax_amount' => $transactions->sum('tax_amount'),
          'pending_transactions' => $transactions->where('status', 'pending')->count(),
          'filed_transactions' => $transactions->where('status', 'filed')->count(),
          'paid_transactions' => $transactions->where('status', 'paid')->count()
        ];
      });

    $types = CoreFinanceTaxModal::getTypes();

    return view('core.finance.taxes.filing-report', compact('taxes', 'types', 'period'));
  }

  /**
   * Display the tax summary report.
   */
  public function summaryReport(Request $request)
  {
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now()->endOfMonth());
    $type = $request->get('type');

    $query = CoreFinanceTaxModal::query()
      ->with(['transactions' => function ($query) use ($startDate, $endDate) {
        $query->whereBetween('date', [$startDate, $endDate]);
      }]);

    if ($type) {
      $query->where('type', $type);
    }

    $taxes = $query->get()
      ->map(function ($tax) {
        $transactions = $tax->transactions;
        return [
          'tax' => $tax,
          'total_base_amount' => $transactions->sum('base_amount'),
          'total_tax_amount' => $transactions->sum('tax_amount'),
          'sales_transactions' => $transactions->where('reference_type', 'receivable')->count(),
          'purchase_transactions' => $transactions->where('reference_type', 'payable')->count(),
          'other_transactions' => $transactions->whereNotIn('reference_type', ['receivable', 'payable'])->count()
        ];
      });

    $types = CoreFinanceTaxModal::getTypes();

    return view('core.finance.taxes.summary-report', compact('taxes', 'types', 'startDate', 'endDate'));
  }
}
