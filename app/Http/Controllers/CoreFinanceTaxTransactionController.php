<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceTaxModal;
use App\Models\CoreFinanceTaxTransactionModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceTaxTransactionController extends Controller
{
  /**
   * Display a listing of tax transactions.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceTaxTransactionModal::query()
      ->with(['tax', 'creator']);

    // Filter by tax
    if ($request->has('tax_id')) {
      $query->where('tax_id', $request->tax_id);
    }

    // Filter by reference type
    if ($request->has('reference_type')) {
      $query->where('reference_type', $request->reference_type);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by filing period
    if ($request->has('filing_period')) {
      $query->where('filing_period', $request->filing_period);
    }

    // Filter by date range
    if ($request->has('start_date') && $request->has('end_date')) {
      $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    $transactions = $query->orderBy('date', 'desc')
      ->paginate(10);

    $taxes = CoreFinanceTaxModal::active()->orderBy('code')->get();
    $referenceTypes = CoreFinanceTaxTransactionModal::getReferenceTypes();
    $statuses = CoreFinanceTaxTransactionModal::getStatuses();

    return view('core.finance.taxes.transactions.index', compact('transactions', 'taxes', 'referenceTypes', 'statuses'));
  }

  /**
   * Show the form for creating a new tax transaction.
   */
  public function create()
  {
    $taxes = CoreFinanceTaxModal::active()
      ->orderBy('code')
      ->get();

    $referenceTypes = CoreFinanceTaxTransactionModal::getReferenceTypes();

    return view('core.finance.taxes.transactions.create', compact('taxes', 'referenceTypes'));
  }

  /**
   * Store a newly created tax transaction.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'tax_id' => 'required|exists:finance_taxes,id',
      'reference_type' => 'required|string|in:' . implode(',', CoreFinanceTaxTransactionModal::getReferenceTypes()),
      'reference_id' => 'required|integer',
      'date' => 'required|date',
      'base_amount' => 'required|numeric|min:0',
      'tax_amount' => 'required|numeric|min:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'is_inclusive' => 'boolean',
      'status' => 'required|string|in:' . implode(',', CoreFinanceTaxTransactionModal::getStatuses()),
      'filing_period' => 'required|string|regex:/^\d{4}-(0[1-9]|1[0-2]|Q[1-4])$/',
      'filing_date' => 'nullable|date',
      'payment_date' => 'nullable|date',
      'description' => 'nullable|string',
      'notes' => 'nullable|string'
    ]);

    $validated['created_by'] = Auth::id();

    try {
      DB::beginTransaction();

      // Create transaction
      $transaction = CoreFinanceTaxTransactionModal::create($validated);

      // Create journal entries if filed or paid
      if (in_array($validated['status'], ['filed', 'paid'])) {
        $tax = CoreFinanceTaxModal::findOrFail($validated['tax_id']);

        // Debit the tax expense/asset account
        $transaction->journalEntries()->create([
          'account_id' => config('finance.accounts.tax_expense'),
          'type' => 'debit',
          'amount' => $validated['tax_amount'],
          'date' => $validated['date'],
          'description' => 'Tax transaction: ' . $tax->name,
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        // Credit the tax liability account
        $transaction->journalEntries()->create([
          'account_id' => $tax->account_id,
          'type' => 'credit',
          'amount' => $validated['tax_amount'],
          'date' => $validated['date'],
          'description' => 'Tax transaction: ' . $tax->name,
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      }

      DB::commit();

      return redirect()
        ->route('finance.taxes.transactions.show', $transaction)
        ->with('success', 'Tax transaction created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create tax transaction. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified tax transaction.
   */
  public function show(CoreFinanceTaxTransactionModal $transaction)
  {
    $transaction->load(['tax', 'creator', 'journalEntries.account']);

    return view('core.finance.taxes.transactions.show', compact('transaction'));
  }

  /**
   * Show the form for editing the specified tax transaction.
   */
  public function edit(CoreFinanceTaxTransactionModal $transaction)
  {
    if ($transaction->status !== 'pending') {
      return back()->withErrors(['error' => 'Only pending transactions can be edited.']);
    }

    $taxes = CoreFinanceTaxModal::active()
      ->orderBy('code')
      ->get();

    $referenceTypes = CoreFinanceTaxTransactionModal::getReferenceTypes();

    return view('core.finance.taxes.transactions.edit', compact('transaction', 'taxes', 'referenceTypes'));
  }

  /**
   * Update the specified tax transaction.
   */
  public function update(Request $request, CoreFinanceTaxTransactionModal $transaction)
  {
    if ($transaction->status !== 'pending') {
      return back()->withErrors(['error' => 'Only pending transactions can be updated.']);
    }

    $validated = $request->validate([
      'tax_id' => 'required|exists:finance_taxes,id',
      'reference_type' => 'required|string|in:' . implode(',', CoreFinanceTaxTransactionModal::getReferenceTypes()),
      'reference_id' => 'required|integer',
      'date' => 'required|date',
      'base_amount' => 'required|numeric|min:0',
      'tax_amount' => 'required|numeric|min:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'is_inclusive' => 'boolean',
      'status' => 'required|string|in:' . implode(',', CoreFinanceTaxTransactionModal::getStatuses()),
      'filing_period' => 'required|string|regex:/^\d{4}-(0[1-9]|1[0-2]|Q[1-4])$/',
      'filing_date' => 'nullable|date',
      'payment_date' => 'nullable|date',
      'description' => 'nullable|string',
      'notes' => 'nullable|string'
    ]);

    try {
      DB::beginTransaction();

      // Update transaction
      $transaction->update($validated);

      // Create journal entries if status changed to filed or paid
      if (in_array($validated['status'], ['filed', 'paid']) && !$transaction->isFiled()) {
        $tax = CoreFinanceTaxModal::findOrFail($validated['tax_id']);

        // Debit the tax expense/asset account
        $transaction->journalEntries()->create([
          'account_id' => config('finance.accounts.tax_expense'),
          'type' => 'debit',
          'amount' => $validated['tax_amount'],
          'date' => $validated['date'],
          'description' => 'Tax transaction: ' . $tax->name,
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        // Credit the tax liability account
        $transaction->journalEntries()->create([
          'account_id' => $tax->account_id,
          'type' => 'credit',
          'amount' => $validated['tax_amount'],
          'date' => $validated['date'],
          'description' => 'Tax transaction: ' . $tax->name,
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      }

      DB::commit();

      return redirect()
        ->route('finance.taxes.transactions.show', $transaction)
        ->with('success', 'Tax transaction updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update tax transaction. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified tax transaction.
   */
  public function destroy(CoreFinanceTaxTransactionModal $transaction)
  {
    if ($transaction->status !== 'pending') {
      return back()->withErrors(['error' => 'Only pending transactions can be deleted.']);
    }

    $transaction->delete();

    return redirect()
      ->route('finance.taxes.transactions.index')
      ->with('success', 'Tax transaction deleted successfully');
  }

  /**
   * File the specified tax transaction.
   */
  public function file(CoreFinanceTaxTransactionModal $transaction)
  {
    if ($transaction->status !== 'pending') {
      return back()->withErrors(['error' => 'Only pending transactions can be filed.']);
    }

    try {
      DB::beginTransaction();

      $tax = $transaction->tax;

      // Update transaction status
      $transaction->update([
        'status' => 'filed',
        'filing_date' => now()
      ]);

      // Create journal entries
      $transaction->journalEntries()->create([
        'account_id' => config('finance.accounts.tax_expense'),
        'type' => 'debit',
        'amount' => $transaction->tax_amount,
        'date' => $transaction->date,
        'description' => 'Tax transaction: ' . $tax->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      $transaction->journalEntries()->create([
        'account_id' => $tax->account_id,
        'type' => 'credit',
        'amount' => $transaction->tax_amount,
        'date' => $transaction->date,
        'description' => 'Tax transaction: ' . $tax->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      DB::commit();

      return redirect()
        ->route('finance.taxes.transactions.show', $transaction)
        ->with('success', 'Tax transaction filed successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to file tax transaction. ' . $e->getMessage()]);
    }
  }

  /**
   * Pay the specified tax transaction.
   */
  public function pay(Request $request, CoreFinanceTaxTransactionModal $transaction)
  {
    if ($transaction->status !== 'filed') {
      return back()->withErrors(['error' => 'Only filed transactions can be paid.']);
    }

    $validated = $request->validate([
      'payment_date' => 'required|date',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Update transaction status
      $transaction->update([
        'status' => 'paid',
        'payment_date' => $validated['payment_date']
      ]);

      // Create journal entries
      $transaction->journalEntries()->create([
        'account_id' => $transaction->tax->account_id,
        'type' => 'debit',
        'amount' => $transaction->tax_amount,
        'date' => $validated['payment_date'],
        'description' => 'Tax payment: ' . $transaction->tax->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      $transaction->journalEntries()->create([
        'account_id' => $validated['account_id'],
        'type' => 'credit',
        'amount' => $transaction->tax_amount,
        'date' => $validated['payment_date'],
        'description' => 'Tax payment: ' . $transaction->tax->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      DB::commit();

      return redirect()
        ->route('finance.taxes.transactions.show', $transaction)
        ->with('success', 'Tax transaction paid successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to pay tax transaction. ' . $e->getMessage()]);
    }
  }
}
