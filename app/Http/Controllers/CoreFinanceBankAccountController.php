<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceBankAccountModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceBankAccountController extends Controller
{
  /**
   * Display a listing of bank accounts.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceBankAccountModal::query()
      ->with(['creator']);

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by currency
    if ($request->has('currency')) {
      $query->where('currency', $request->currency);
    }

    // Filter by bank
    if ($request->has('bank_name')) {
      $query->where('bank_name', $request->bank_name);
    }

    // Filter accounts needing reconciliation
    if ($request->boolean('needs_reconciliation')) {
      $query->needsReconciliation();
    }

    // Filter accounts below minimum balance
    if ($request->boolean('below_minimum')) {
      $query->belowMinimum();
    }

    $accounts = $query->orderBy('bank_name')
      ->orderBy('name')
      ->paginate(10);

    $types = CoreFinanceBankAccountModal::getTypes();

    return view('core.finance.bank-accounts.index', compact('accounts', 'types'));
  }

  /**
   * Show the form for creating a new bank account.
   */
  public function create()
  {
    $types = CoreFinanceBankAccountModal::getTypes();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->where('category', 'bank')
      ->orderBy('code')
      ->get();

    return view('core.finance.bank-accounts.create', compact('types', 'accounts'));
  }

  /**
   * Store a newly created bank account.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_bank_accounts,code',
      'name' => 'required|string|max:255',
      'account_number' => 'required|string|max:50',
      'bank_name' => 'required|string|max:255',
      'branch_name' => 'nullable|string|max:255',
      'swift_code' => 'nullable|string|max:50',
      'iban' => 'nullable|string|max:50',
      'routing_number' => 'nullable|string|max:50',
      'currency' => 'required|string|size:3',
      'type' => 'required|string|in:' . implode(',', CoreFinanceBankAccountModal::getTypes()),
      'interest_rate' => 'nullable|numeric|min:0|max:100',
      'minimum_balance' => 'nullable|numeric|min:0',
      'opening_balance' => 'required|numeric',
      'contact_person' => 'nullable|string|max:255',
      'contact_phone' => 'nullable|string|max:50',
      'contact_email' => 'nullable|email|max:255',
      'address_line1' => 'nullable|string|max:255',
      'address_line2' => 'nullable|string|max:255',
      'city' => 'nullable|string|max:100',
      'state' => 'nullable|string|max:100',
      'postal_code' => 'nullable|string|max:20',
      'country' => 'nullable|string|max:100',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:active,inactive',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Create bank account
      $validated['created_by'] = Auth::id();
      $validated['current_balance'] = $validated['opening_balance'];
      $validated['available_balance'] = $validated['opening_balance'];
      $validated['reconciled_balance'] = $validated['opening_balance'];

      $account = CoreFinanceBankAccountModal::create($validated);

      // Create journal entry for opening balance
      if ($validated['opening_balance'] != 0) {
        $journalEntry = [
          'account_id' => $validated['account_id'],
          'type' => $validated['opening_balance'] > 0 ? 'debit' : 'credit',
          'amount' => abs($validated['opening_balance']),
          'currency' => $validated['currency'],
          'exchange_rate' => 1,
          'date' => now(),
          'description' => 'Opening balance for bank account: ' . $account->name,
          'reference_type' => 'bank_account',
          'reference_id' => $account->id,
          'status' => 'posted',
          'created_by' => Auth::id()
        ];

        $account->journalEntries()->create($journalEntry);
      }

      DB::commit();

      return redirect()
        ->route('finance.bank-accounts.show', $account)
        ->with('success', 'Bank account created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create bank account. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified bank account.
   */
  public function show(CoreFinanceBankAccountModal $account)
  {
    $account->load(['creator', 'transactions' => function ($query) {
      $query->latest()->limit(10);
    }]);

    return view('core.finance.bank-accounts.show', compact('account'));
  }

  /**
   * Show the form for editing the specified bank account.
   */
  public function edit(CoreFinanceBankAccountModal $account)
  {
    $types = CoreFinanceBankAccountModal::getTypes();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->where('category', 'bank')
      ->orderBy('code')
      ->get();

    return view('core.finance.bank-accounts.edit', compact('account', 'types', 'accounts'));
  }

  /**
   * Update the specified bank account.
   */
  public function update(Request $request, CoreFinanceBankAccountModal $account)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_bank_accounts,code,' . $account->id,
      'name' => 'required|string|max:255',
      'account_number' => 'required|string|max:50',
      'bank_name' => 'required|string|max:255',
      'branch_name' => 'nullable|string|max:255',
      'swift_code' => 'nullable|string|max:50',
      'iban' => 'nullable|string|max:50',
      'routing_number' => 'nullable|string|max:50',
      'currency' => 'required|string|size:3',
      'type' => 'required|string|in:' . implode(',', CoreFinanceBankAccountModal::getTypes()),
      'interest_rate' => 'nullable|numeric|min:0|max:100',
      'minimum_balance' => 'nullable|numeric|min:0',
      'contact_person' => 'nullable|string|max:255',
      'contact_phone' => 'nullable|string|max:50',
      'contact_email' => 'nullable|email|max:255',
      'address_line1' => 'nullable|string|max:255',
      'address_line2' => 'nullable|string|max:255',
      'city' => 'nullable|string|max:100',
      'state' => 'nullable|string|max:100',
      'postal_code' => 'nullable|string|max:20',
      'country' => 'nullable|string|max:100',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:active,inactive'
    ]);

    $account->update($validated);

    return redirect()
      ->route('finance.bank-accounts.show', $account)
      ->with('success', 'Bank account updated successfully');
  }

  /**
   * Remove the specified bank account.
   */
  public function destroy(CoreFinanceBankAccountModal $account)
  {
    if ($account->transactions()->exists()) {
      return back()->withErrors(['error' => 'Cannot delete bank account with transactions.']);
    }

    $account->delete();

    return redirect()
      ->route('finance.bank-accounts.index')
      ->with('success', 'Bank account deleted successfully');
  }

  /**
   * Display the bank statement.
   */
  public function statement(Request $request, CoreFinanceBankAccountModal $account)
  {
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now()->endOfMonth());

    $transactions = $account->transactions()
      ->with(['creator'])
      ->whereBetween('transaction_date', [$startDate, $endDate])
      ->orderBy('transaction_date')
      ->orderBy('id')
      ->get();

    return view('core.finance.bank-accounts.statement', compact('account', 'transactions', 'startDate', 'endDate'));
  }

  /**
   * Display the reconciliation report.
   */
  public function reconciliationReport(Request $request, CoreFinanceBankAccountModal $account)
  {
    $reconciliations = $account->reconciliations()
      ->with(['creator', 'completer'])
      ->orderBy('statement_date', 'desc')
      ->paginate(10);

    return view('core.finance.bank-accounts.reconciliation-report', compact('account', 'reconciliations'));
  }

  /**
   * Display the cash flow report.
   */
  public function cashFlow(Request $request, CoreFinanceBankAccountModal $account)
  {
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now()->endOfMonth());

    $transactions = $account->transactions()
      ->with(['creator'])
      ->whereBetween('transaction_date', [$startDate, $endDate])
      ->orderBy('transaction_date')
      ->get()
      ->groupBy(function ($transaction) {
        return $transaction->transaction_date->format('Y-m-d');
      });

    return view('core.finance.bank-accounts.cash-flow', compact('account', 'transactions', 'startDate', 'endDate'));
  }
}
