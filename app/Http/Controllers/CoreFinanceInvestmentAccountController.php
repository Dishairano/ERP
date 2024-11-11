<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceInvestmentAccountModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceInvestmentAccountController extends Controller
{
  /**
   * Display a listing of investment accounts.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceInvestmentAccountModal::query()
      ->with(['creator']);

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by risk level
    if ($request->has('risk_level')) {
      $query->where('risk_level', $request->risk_level);
    }

    // Filter by broker
    if ($request->has('broker_name')) {
      $query->where('broker_name', $request->broker_name);
    }

    // Filter accounts needing rebalancing
    if ($request->boolean('needs_rebalancing')) {
      $query->needsRebalancing();
    }

    $accounts = $query->orderBy('name')
      ->paginate(10);

    $types = CoreFinanceInvestmentAccountModal::getTypes();
    $riskLevels = CoreFinanceInvestmentAccountModal::getRiskLevels();
    $rebalancingFrequencies = CoreFinanceInvestmentAccountModal::getRebalancingFrequencies();

    return view('core.finance.investments.accounts.index', compact('accounts', 'types', 'riskLevels', 'rebalancingFrequencies'));
  }

  /**
   * Show the form for creating a new investment account.
   */
  public function create()
  {
    $types = CoreFinanceInvestmentAccountModal::getTypes();
    $riskLevels = CoreFinanceInvestmentAccountModal::getRiskLevels();
    $rebalancingFrequencies = CoreFinanceInvestmentAccountModal::getRebalancingFrequencies();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->where('category', 'investment')
      ->orderBy('code')
      ->get();

    return view('core.finance.investments.accounts.create', compact('types', 'riskLevels', 'rebalancingFrequencies', 'accounts'));
  }

  /**
   * Store a newly created investment account.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_investment_accounts,code',
      'name' => 'required|string|max:255',
      'account_number' => 'required|string|max:50',
      'broker_name' => 'required|string|max:255',
      'broker_code' => 'nullable|string|max:50',
      'type' => 'required|string|in:' . implode(',', CoreFinanceInvestmentAccountModal::getTypes()),
      'currency' => 'required|string|size:3',
      'opening_balance' => 'required|numeric|min:0',
      'risk_level' => 'required|string|in:' . implode(',', CoreFinanceInvestmentAccountModal::getRiskLevels()),
      'investment_strategy' => 'nullable|string',
      'target_allocation' => 'nullable|json',
      'rebalancing_frequency' => 'nullable|string|in:' . implode(',', CoreFinanceInvestmentAccountModal::getRebalancingFrequencies()),
      'contact_person' => 'nullable|string|max:255',
      'contact_phone' => 'nullable|string|max:50',
      'contact_email' => 'nullable|email|max:255',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:active,inactive',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Create investment account
      $validated['created_by'] = Auth::id();
      $validated['current_balance'] = $validated['opening_balance'];
      $validated['market_value'] = $validated['opening_balance'];

      $account = CoreFinanceInvestmentAccountModal::create($validated);

      // Create journal entry for opening balance
      if ($validated['opening_balance'] != 0) {
        $journalEntry = [
          'account_id' => $validated['account_id'],
          'type' => 'debit',
          'amount' => $validated['opening_balance'],
          'currency' => $validated['currency'],
          'exchange_rate' => 1,
          'date' => now(),
          'description' => 'Opening balance for investment account: ' . $account->name,
          'reference_type' => 'investment_account',
          'reference_id' => $account->id,
          'status' => 'posted',
          'created_by' => Auth::id()
        ];

        $account->journalEntries()->create($journalEntry);
      }

      DB::commit();

      return redirect()
        ->route('finance.investments.accounts.show', $account)
        ->with('success', 'Investment account created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create investment account. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified investment account.
   */
  public function show(CoreFinanceInvestmentAccountModal $account)
  {
    $account->load(['creator', 'holdings', 'transactions' => function ($query) {
      $query->latest()->limit(10);
    }]);

    return view('core.finance.investments.accounts.show', compact('account'));
  }

  /**
   * Show the form for editing the specified investment account.
   */
  public function edit(CoreFinanceInvestmentAccountModal $account)
  {
    $types = CoreFinanceInvestmentAccountModal::getTypes();
    $riskLevels = CoreFinanceInvestmentAccountModal::getRiskLevels();
    $rebalancingFrequencies = CoreFinanceInvestmentAccountModal::getRebalancingFrequencies();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->where('category', 'investment')
      ->orderBy('code')
      ->get();

    return view('core.finance.investments.accounts.edit', compact('account', 'types', 'riskLevels', 'rebalancingFrequencies', 'accounts'));
  }

  /**
   * Update the specified investment account.
   */
  public function update(Request $request, CoreFinanceInvestmentAccountModal $account)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_investment_accounts,code,' . $account->id,
      'name' => 'required|string|max:255',
      'account_number' => 'required|string|max:50',
      'broker_name' => 'required|string|max:255',
      'broker_code' => 'nullable|string|max:50',
      'type' => 'required|string|in:' . implode(',', CoreFinanceInvestmentAccountModal::getTypes()),
      'currency' => 'required|string|size:3',
      'risk_level' => 'required|string|in:' . implode(',', CoreFinanceInvestmentAccountModal::getRiskLevels()),
      'investment_strategy' => 'nullable|string',
      'target_allocation' => 'nullable|json',
      'rebalancing_frequency' => 'nullable|string|in:' . implode(',', CoreFinanceInvestmentAccountModal::getRebalancingFrequencies()),
      'contact_person' => 'nullable|string|max:255',
      'contact_phone' => 'nullable|string|max:50',
      'contact_email' => 'nullable|email|max:255',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:active,inactive'
    ]);

    $account->update($validated);

    return redirect()
      ->route('finance.investments.accounts.show', $account)
      ->with('success', 'Investment account updated successfully');
  }

  /**
   * Remove the specified investment account.
   */
  public function destroy(CoreFinanceInvestmentAccountModal $account)
  {
    if ($account->holdings()->exists() || $account->transactions()->exists()) {
      return back()->withErrors(['error' => 'Cannot delete account with holdings or transactions.']);
    }

    $account->delete();

    return redirect()
      ->route('finance.investments.accounts.index')
      ->with('success', 'Investment account deleted successfully');
  }

  /**
   * Display the portfolio summary.
   */
  public function portfolio(CoreFinanceInvestmentAccountModal $account)
  {
    $account->load(['holdings' => function ($query) {
      $query->active()->orderBy('market_value', 'desc');
    }]);

    return view('core.finance.investments.accounts.portfolio', compact('account'));
  }

  /**
   * Display the performance report.
   */
  public function performance(Request $request, CoreFinanceInvestmentAccountModal $account)
  {
    $startDate = $request->get('start_date', now()->startOfYear());
    $endDate = $request->get('end_date', now());

    $transactions = $account->transactions()
      ->with(['holding'])
      ->whereBetween('transaction_date', [$startDate, $endDate])
      ->orderBy('transaction_date')
      ->get()
      ->groupBy(function ($transaction) {
        return $transaction->transaction_date->format('Y-m');
      });

    return view('core.finance.investments.accounts.performance', compact('account', 'transactions', 'startDate', 'endDate'));
  }

  /**
   * Display the rebalancing report.
   */
  public function rebalancing(CoreFinanceInvestmentAccountModal $account)
  {
    $account->load(['holdings' => function ($query) {
      $query->active()->orderBy('allocation_percentage', 'desc');
    }]);

    return view('core.finance.investments.accounts.rebalancing', compact('account'));
  }

  /**
   * Display the income report.
   */
  public function income(Request $request, CoreFinanceInvestmentAccountModal $account)
  {
    $startDate = $request->get('start_date', now()->startOfYear());
    $endDate = $request->get('end_date', now());

    $transactions = $account->transactions()
      ->with(['holding'])
      ->whereIn('type', ['dividend', 'interest'])
      ->whereBetween('transaction_date', [$startDate, $endDate])
      ->orderBy('transaction_date')
      ->get()
      ->groupBy(function ($transaction) {
        return $transaction->transaction_date->format('Y-m');
      });

    return view('core.finance.investments.accounts.income', compact('account', 'transactions', 'startDate', 'endDate'));
  }
}
