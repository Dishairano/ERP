<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceBankAccountModal;
use App\Models\CoreFinanceBankReconciliationModal;
use App\Models\CoreFinanceBankTransactionModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceBankReconciliationController extends Controller
{
  /**
   * Display a listing of reconciliations.
   */
  public function index(CoreFinanceBankAccountModal $account, Request $request)
  {
    $query = $account->reconciliations()
      ->with(['creator', 'completer']);

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by date range
    if ($request->has('start_date') && $request->has('end_date')) {
      $query->whereBetween('statement_date', [$request->start_date, $request->end_date]);
    }

    $reconciliations = $query->orderBy('statement_date', 'desc')
      ->paginate(10);

    return view('core.finance.bank-accounts.reconciliations.index', compact('account', 'reconciliations'));
  }

  /**
   * Show the form for creating a new reconciliation.
   */
  public function create(CoreFinanceBankAccountModal $account)
  {
    // Check if there's an in-progress reconciliation
    if ($account->reconciliations()->where('status', 'in_progress')->exists()) {
      return back()->withErrors(['error' => 'There is already a reconciliation in progress.']);
    }

    // Get unreconciled transactions
    $transactions = $account->transactions()
      ->where('is_reconciled', false)
      ->orderBy('transaction_date')
      ->orderBy('id')
      ->get();

    return view('core.finance.bank-accounts.reconciliations.create', compact('account', 'transactions'));
  }

  /**
   * Store a newly created reconciliation.
   */
  public function store(Request $request, CoreFinanceBankAccountModal $account)
  {
    $validated = $request->validate([
      'statement_date' => 'required|date',
      'statement_balance' => 'required|numeric',
      'bank_balance' => 'required|numeric',
      'book_balance' => 'required|numeric',
      'unreconciled_deposits' => 'required|numeric|min:0',
      'unreconciled_withdrawals' => 'required|numeric|min:0',
      'outstanding_checks' => 'required|numeric|min:0',
      'notes' => 'nullable|string',
      'transactions' => 'required|array',
      'transactions.*' => 'exists:finance_bank_transactions,id'
    ]);

    try {
      DB::beginTransaction();

      // Create reconciliation
      $validated['bank_account_id'] = $account->id;
      $validated['created_by'] = Auth::id();
      $validated['status'] = 'in_progress';
      $validated['adjusted_balance'] = $validated['book_balance'] +
        $validated['unreconciled_deposits'] -
        $validated['unreconciled_withdrawals'] -
        $validated['outstanding_checks'];
      $validated['difference'] = $validated['statement_balance'] - $validated['adjusted_balance'];

      $reconciliation = CoreFinanceBankReconciliationModal::create($validated);

      // Mark selected transactions as reconciled
      CoreFinanceBankTransactionModal::whereIn('id', $validated['transactions'])
        ->update([
          'is_reconciled' => true,
          'reconciliation_id' => $reconciliation->id,
          'reconciliation_date' => now()
        ]);

      DB::commit();

      return redirect()
        ->route('finance.bank-accounts.reconciliations.show', [$account, $reconciliation])
        ->with('success', 'Reconciliation created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create reconciliation. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified reconciliation.
   */
  public function show(CoreFinanceBankAccountModal $account, CoreFinanceBankReconciliationModal $reconciliation)
  {
    $reconciliation->load(['creator', 'completer', 'transactions']);

    return view('core.finance.bank-accounts.reconciliations.show', compact('account', 'reconciliation'));
  }

  /**
   * Show the form for editing the specified reconciliation.
   */
  public function edit(CoreFinanceBankAccountModal $account, CoreFinanceBankReconciliationModal $reconciliation)
  {
    if ($reconciliation->status !== 'in_progress') {
      return back()->withErrors(['error' => 'Only in-progress reconciliations can be edited.']);
    }

    // Get all unreconciled transactions and transactions in this reconciliation
    $transactions = $account->transactions()
      ->where(function ($query) use ($reconciliation) {
        $query->where('is_reconciled', false)
          ->orWhere('reconciliation_id', $reconciliation->id);
      })
      ->orderBy('transaction_date')
      ->orderBy('id')
      ->get();

    return view('core.finance.bank-accounts.reconciliations.edit', compact('account', 'reconciliation', 'transactions'));
  }

  /**
   * Update the specified reconciliation.
   */
  public function update(Request $request, CoreFinanceBankAccountModal $account, CoreFinanceBankReconciliationModal $reconciliation)
  {
    if ($reconciliation->status !== 'in_progress') {
      return back()->withErrors(['error' => 'Only in-progress reconciliations can be updated.']);
    }

    $validated = $request->validate([
      'statement_date' => 'required|date',
      'statement_balance' => 'required|numeric',
      'bank_balance' => 'required|numeric',
      'book_balance' => 'required|numeric',
      'unreconciled_deposits' => 'required|numeric|min:0',
      'unreconciled_withdrawals' => 'required|numeric|min:0',
      'outstanding_checks' => 'required|numeric|min:0',
      'notes' => 'nullable|string',
      'transactions' => 'required|array',
      'transactions.*' => 'exists:finance_bank_transactions,id'
    ]);

    try {
      DB::beginTransaction();

      // Update reconciliation
      $validated['adjusted_balance'] = $validated['book_balance'] +
        $validated['unreconciled_deposits'] -
        $validated['unreconciled_withdrawals'] -
        $validated['outstanding_checks'];
      $validated['difference'] = $validated['statement_balance'] - $validated['adjusted_balance'];

      $reconciliation->update($validated);

      // Reset previously reconciled transactions
      CoreFinanceBankTransactionModal::where('reconciliation_id', $reconciliation->id)
        ->update([
          'is_reconciled' => false,
          'reconciliation_id' => null,
          'reconciliation_date' => null
        ]);

      // Mark selected transactions as reconciled
      CoreFinanceBankTransactionModal::whereIn('id', $validated['transactions'])
        ->update([
          'is_reconciled' => true,
          'reconciliation_id' => $reconciliation->id,
          'reconciliation_date' => now()
        ]);

      DB::commit();

      return redirect()
        ->route('finance.bank-accounts.reconciliations.show', [$account, $reconciliation])
        ->with('success', 'Reconciliation updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update reconciliation. ' . $e->getMessage()]);
    }
  }

  /**
   * Complete the specified reconciliation.
   */
  public function complete(Request $request, CoreFinanceBankAccountModal $account, CoreFinanceBankReconciliationModal $reconciliation)
  {
    if ($reconciliation->status !== 'in_progress') {
      return back()->withErrors(['error' => 'Only in-progress reconciliations can be completed.']);
    }

    if (!$reconciliation->isBalanced()) {
      return back()->withErrors(['error' => 'Cannot complete unbalanced reconciliation.']);
    }

    try {
      DB::beginTransaction();

      // Update reconciliation
      $reconciliation->update([
        'status' => 'completed',
        'completed_at' => now(),
        'completed_by' => Auth::id()
      ]);

      // Update bank account
      $account->update([
        'last_reconciliation_date' => $reconciliation->statement_date,
        'reconciled_balance' => $reconciliation->statement_balance
      ]);

      DB::commit();

      return redirect()
        ->route('finance.bank-accounts.reconciliations.show', [$account, $reconciliation])
        ->with('success', 'Reconciliation completed successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to complete reconciliation. ' . $e->getMessage()]);
    }
  }

  /**
   * Cancel the specified reconciliation.
   */
  public function cancel(CoreFinanceBankAccountModal $account, CoreFinanceBankReconciliationModal $reconciliation)
  {
    if ($reconciliation->status !== 'in_progress') {
      return back()->withErrors(['error' => 'Only in-progress reconciliations can be cancelled.']);
    }

    try {
      DB::beginTransaction();

      // Reset reconciled transactions
      CoreFinanceBankTransactionModal::where('reconciliation_id', $reconciliation->id)
        ->update([
          'is_reconciled' => false,
          'reconciliation_id' => null,
          'reconciliation_date' => null
        ]);

      // Update reconciliation status
      $reconciliation->update(['status' => 'cancelled']);

      DB::commit();

      return redirect()
        ->route('finance.bank-accounts.reconciliations.index', $account)
        ->with('success', 'Reconciliation cancelled successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to cancel reconciliation. ' . $e->getMessage()]);
    }
  }
}
