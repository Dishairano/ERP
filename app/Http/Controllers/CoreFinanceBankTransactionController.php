<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceBankAccountModal;
use App\Models\CoreFinanceBankTransactionModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceBankTransactionController extends Controller
{
  /**
   * Display a listing of transactions.
   */
  public function index(CoreFinanceBankAccountModal $account, Request $request)
  {
    $query = $account->transactions()
      ->with(['creator']);

    // Filter by type
    if ($request->has('reference_type')) {
      $query->where('reference_type', $request->reference_type);
    }

    // Filter by category
    if ($request->has('category')) {
      $query->where('category', $request->category);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by date range
    if ($request->has('start_date') && $request->has('end_date')) {
      $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
    }

    // Filter by reconciliation status
    if ($request->has('reconciled')) {
      $query->where('is_reconciled', $request->boolean('reconciled'));
    }

    // Filter by payee
    if ($request->has('payee')) {
      $query->where('payee', $request->payee);
    }

    $transactions = $query->orderBy('transaction_date', 'desc')
      ->orderBy('id', 'desc')
      ->paginate(10);

    $referenceTypes = CoreFinanceBankTransactionModal::getReferenceTypes();
    $categories = CoreFinanceBankTransactionModal::getCategories();

    return view('core.finance.bank-accounts.transactions.index', compact('account', 'transactions', 'referenceTypes', 'categories'));
  }

  /**
   * Show the form for creating a new transaction.
   */
  public function create(CoreFinanceBankAccountModal $account)
  {
    $referenceTypes = CoreFinanceBankTransactionModal::getReferenceTypes();
    $categories = CoreFinanceBankTransactionModal::getCategories();
    $accounts = CoreFinanceAccountModal::active()->orderBy('code')->get();

    return view('core.finance.bank-accounts.transactions.create', compact('account', 'referenceTypes', 'categories', 'accounts'));
  }

  /**
   * Store a newly created transaction.
   */
  public function store(Request $request, CoreFinanceBankAccountModal $account)
  {
    $validated = $request->validate([
      'reference_type' => 'required|string|in:' . implode(',', CoreFinanceBankTransactionModal::getReferenceTypes()),
      'reference_id' => 'nullable|integer',
      'transaction_date' => 'required|date',
      'value_date' => 'nullable|date',
      'amount' => 'required|numeric|not_in:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'description' => 'nullable|string',
      'reference_number' => 'nullable|string|max:50',
      'check_number' => 'nullable|string|max:50',
      'payee' => 'nullable|string|max:255',
      'category' => 'nullable|string|in:' . implode(',', CoreFinanceBankTransactionModal::getCategories()),
      'notes' => 'nullable|string',
      'status' => 'required|string|in:pending,posted,cleared,voided',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Create transaction
      $validated['bank_account_id'] = $account->id;
      $validated['created_by'] = Auth::id();

      $transaction = CoreFinanceBankTransactionModal::create($validated);

      // Update bank account balance
      $amount = $validated['amount'];
      if (in_array($validated['reference_type'], ['withdrawal', 'transfer', 'payment', 'fee'])) {
        $amount = -$amount;
      }

      $account->current_balance += $amount;
      $account->available_balance += $amount;
      $account->save();

      // Create journal entries if posted
      if ($validated['status'] === 'posted') {
        // Determine debit and credit accounts based on transaction type
        if ($amount > 0) {
          $debitAccountId = $validated['account_id'];
          $creditAccountId = config('finance.accounts.bank');
        } else {
          $debitAccountId = config('finance.accounts.bank');
          $creditAccountId = $validated['account_id'];
        }

        // Create debit entry
        $transaction->journalEntries()->create([
          'account_id' => $debitAccountId,
          'type' => 'debit',
          'amount' => abs($amount),
          'currency' => $validated['currency'],
          'exchange_rate' => $validated['exchange_rate'],
          'date' => $validated['transaction_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        // Create credit entry
        $transaction->journalEntries()->create([
          'account_id' => $creditAccountId,
          'type' => 'credit',
          'amount' => abs($amount),
          'currency' => $validated['currency'],
          'exchange_rate' => $validated['exchange_rate'],
          'date' => $validated['transaction_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      }

      DB::commit();

      return redirect()
        ->route('finance.bank-accounts.transactions.show', [$account, $transaction])
        ->with('success', 'Transaction created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create transaction. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified transaction.
   */
  public function show(CoreFinanceBankAccountModal $account, CoreFinanceBankTransactionModal $transaction)
  {
    $transaction->load(['creator', 'journalEntries.account']);

    return view('core.finance.bank-accounts.transactions.show', compact('account', 'transaction'));
  }

  /**
   * Show the form for editing the specified transaction.
   */
  public function edit(CoreFinanceBankAccountModal $account, CoreFinanceBankTransactionModal $transaction)
  {
    if ($transaction->is_reconciled) {
      return back()->withErrors(['error' => 'Cannot edit reconciled transactions.']);
    }

    $referenceTypes = CoreFinanceBankTransactionModal::getReferenceTypes();
    $categories = CoreFinanceBankTransactionModal::getCategories();
    $accounts = CoreFinanceAccountModal::active()->orderBy('code')->get();

    return view('core.finance.bank-accounts.transactions.edit', compact('account', 'transaction', 'referenceTypes', 'categories', 'accounts'));
  }

  /**
   * Update the specified transaction.
   */
  public function update(Request $request, CoreFinanceBankAccountModal $account, CoreFinanceBankTransactionModal $transaction)
  {
    if ($transaction->is_reconciled) {
      return back()->withErrors(['error' => 'Cannot update reconciled transactions.']);
    }

    $validated = $request->validate([
      'reference_type' => 'required|string|in:' . implode(',', CoreFinanceBankTransactionModal::getReferenceTypes()),
      'reference_id' => 'nullable|integer',
      'transaction_date' => 'required|date',
      'value_date' => 'nullable|date',
      'amount' => 'required|numeric|not_in:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'description' => 'nullable|string',
      'reference_number' => 'nullable|string|max:50',
      'check_number' => 'nullable|string|max:50',
      'payee' => 'nullable|string|max:255',
      'category' => 'nullable|string|in:' . implode(',', CoreFinanceBankTransactionModal::getCategories()),
      'notes' => 'nullable|string',
      'status' => 'required|string|in:pending,posted,cleared,voided',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Calculate balance adjustment
      $oldAmount = $transaction->amount;
      if (in_array($transaction->reference_type, ['withdrawal', 'transfer', 'payment', 'fee'])) {
        $oldAmount = -$oldAmount;
      }

      $newAmount = $validated['amount'];
      if (in_array($validated['reference_type'], ['withdrawal', 'transfer', 'payment', 'fee'])) {
        $newAmount = -$newAmount;
      }

      $balanceAdjustment = $newAmount - $oldAmount;

      // Update transaction
      $transaction->update($validated);

      // Update bank account balance
      $account->current_balance += $balanceAdjustment;
      $account->available_balance += $balanceAdjustment;
      $account->save();

      // Update journal entries if posted
      if ($validated['status'] === 'posted') {
        // Delete existing entries
        $transaction->journalEntries()->delete();

        // Determine debit and credit accounts based on transaction type
        if ($newAmount > 0) {
          $debitAccountId = $validated['account_id'];
          $creditAccountId = config('finance.accounts.bank');
        } else {
          $debitAccountId = config('finance.accounts.bank');
          $creditAccountId = $validated['account_id'];
        }

        // Create debit entry
        $transaction->journalEntries()->create([
          'account_id' => $debitAccountId,
          'type' => 'debit',
          'amount' => abs($newAmount),
          'currency' => $validated['currency'],
          'exchange_rate' => $validated['exchange_rate'],
          'date' => $validated['transaction_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        // Create credit entry
        $transaction->journalEntries()->create([
          'account_id' => $creditAccountId,
          'type' => 'credit',
          'amount' => abs($newAmount),
          'currency' => $validated['currency'],
          'exchange_rate' => $validated['exchange_rate'],
          'date' => $validated['transaction_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      }

      DB::commit();

      return redirect()
        ->route('finance.bank-accounts.transactions.show', [$account, $transaction])
        ->with('success', 'Transaction updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update transaction. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified transaction.
   */
  public function destroy(CoreFinanceBankAccountModal $account, CoreFinanceBankTransactionModal $transaction)
  {
    if ($transaction->is_reconciled) {
      return back()->withErrors(['error' => 'Cannot delete reconciled transactions.']);
    }

    try {
      DB::beginTransaction();

      // Calculate balance adjustment
      $amount = $transaction->amount;
      if (in_array($transaction->reference_type, ['withdrawal', 'transfer', 'payment', 'fee'])) {
        $amount = -$amount;
      }

      // Update bank account balance
      $account->current_balance -= $amount;
      $account->available_balance -= $amount;
      $account->save();

      // Delete journal entries
      $transaction->journalEntries()->delete();

      // Delete transaction
      $transaction->delete();

      DB::commit();

      return redirect()
        ->route('finance.bank-accounts.transactions.index', $account)
        ->with('success', 'Transaction deleted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to delete transaction. ' . $e->getMessage()]);
    }
  }

  /**
   * Void the specified transaction.
   */
  public function void(CoreFinanceBankAccountModal $account, CoreFinanceBankTransactionModal $transaction)
  {
    if ($transaction->is_reconciled) {
      return back()->withErrors(['error' => 'Cannot void reconciled transactions.']);
    }

    if ($transaction->status === 'voided') {
      return back()->withErrors(['error' => 'Transaction is already voided.']);
    }

    try {
      DB::beginTransaction();

      // Calculate balance adjustment
      $amount = $transaction->amount;
      if (in_array($transaction->reference_type, ['withdrawal', 'transfer', 'payment', 'fee'])) {
        $amount = -$amount;
      }

      // Update bank account balance
      $account->current_balance -= $amount;
      $account->available_balance -= $amount;
      $account->save();

      // Update transaction status
      $transaction->update(['status' => 'voided']);

      // Delete journal entries
      $transaction->journalEntries()->delete();

      DB::commit();

      return redirect()
        ->route('finance.bank-accounts.transactions.show', [$account, $transaction])
        ->with('success', 'Transaction voided successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to void transaction. ' . $e->getMessage()]);
    }
  }
}
