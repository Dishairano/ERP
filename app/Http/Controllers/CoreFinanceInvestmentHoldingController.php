<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceInvestmentAccountModal;
use App\Models\CoreFinanceInvestmentHoldingModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceInvestmentHoldingController extends Controller
{
  /**
   * Display a listing of holdings.
   */
  public function index(CoreFinanceInvestmentAccountModal $account, Request $request)
  {
    $query = $account->holdings()
      ->with(['creator']);

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }

    // Filter by category
    if ($request->has('category')) {
      $query->where('category', $request->category);
    }

    // Filter by risk level
    if ($request->has('risk_level')) {
      $query->where('risk_level', $request->risk_level);
    }

    // Filter by sector
    if ($request->has('sector')) {
      $query->where('sector', $request->sector);
    }

    // Filter by industry
    if ($request->has('industry')) {
      $query->where('industry', $request->industry);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter holdings needing rebalancing
    if ($request->boolean('needs_rebalancing')) {
      $query->needsRebalancing();
    }

    $holdings = $query->orderBy('market_value', 'desc')
      ->paginate(10);

    $types = CoreFinanceInvestmentHoldingModal::getTypes();
    $categories = CoreFinanceInvestmentHoldingModal::getCategories();
    $riskLevels = CoreFinanceInvestmentHoldingModal::getRiskLevels();

    return view('core.finance.investments.holdings.index', compact('account', 'holdings', 'types', 'categories', 'riskLevels'));
  }

  /**
   * Show the form for creating a new holding.
   */
  public function create(CoreFinanceInvestmentAccountModal $account)
  {
    $types = CoreFinanceInvestmentHoldingModal::getTypes();
    $categories = CoreFinanceInvestmentHoldingModal::getCategories();
    $riskLevels = CoreFinanceInvestmentHoldingModal::getRiskLevels();
    $dividendFrequencies = CoreFinanceInvestmentHoldingModal::getDividendFrequencies();

    return view('core.finance.investments.holdings.create', compact('account', 'types', 'categories', 'riskLevels', 'dividendFrequencies'));
  }

  /**
   * Store a newly created holding.
   */
  public function store(Request $request, CoreFinanceInvestmentAccountModal $account)
  {
    $validated = $request->validate([
      'symbol' => 'required|string|max:50',
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceInvestmentHoldingModal::getTypes()),
      'category' => 'required|string|in:' . implode(',', CoreFinanceInvestmentHoldingModal::getCategories()),
      'quantity' => 'required|numeric|min:0',
      'cost_basis' => 'required|numeric|min:0',
      'current_price' => 'required|numeric|min:0',
      'annual_income' => 'nullable|numeric|min:0',
      'yield_percentage' => 'nullable|numeric|min:0',
      'allocation_percentage' => 'nullable|numeric|between:0,100',
      'target_allocation_percentage' => 'nullable|numeric|between:0,100',
      'last_trade_date' => 'nullable|date',
      'last_dividend_date' => 'nullable|date',
      'next_dividend_date' => 'nullable|date',
      'dividend_frequency' => 'nullable|string|in:' . implode(',', CoreFinanceInvestmentHoldingModal::getDividendFrequencies()),
      'risk_level' => 'required|string|in:' . implode(',', CoreFinanceInvestmentHoldingModal::getRiskLevels()),
      'sector' => 'nullable|string|max:100',
      'industry' => 'nullable|string|max:100',
      'country' => 'nullable|string|max:100',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'maturity_date' => 'nullable|date',
      'coupon_rate' => 'nullable|numeric|min:0',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:active,inactive'
    ]);

    try {
      DB::beginTransaction();

      // Calculate market value and unrealized gain/loss
      $validated['investment_account_id'] = $account->id;
      $validated['created_by'] = Auth::id();
      $validated['market_value'] = $validated['quantity'] * $validated['current_price'];
      $validated['unrealized_gain_loss'] = $validated['market_value'] - $validated['cost_basis'];
      $validated['average_cost'] = $validated['quantity'] > 0 ? $validated['cost_basis'] / $validated['quantity'] : 0;

      $holding = CoreFinanceInvestmentHoldingModal::create($validated);

      // Update account market value and unrealized gain/loss
      $account->market_value += $validated['market_value'];
      $account->unrealized_gain_loss += $validated['unrealized_gain_loss'];
      $account->save();

      DB::commit();

      return redirect()
        ->route('finance.investments.holdings.show', [$account, $holding])
        ->with('success', 'Investment holding created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create investment holding. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified holding.
   */
  public function show(CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentHoldingModal $holding)
  {
    $holding->load(['creator', 'transactions' => function ($query) {
      $query->latest()->limit(10);
    }]);

    return view('core.finance.investments.holdings.show', compact('account', 'holding'));
  }

  /**
   * Show the form for editing the specified holding.
   */
  public function edit(CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentHoldingModal $holding)
  {
    $types = CoreFinanceInvestmentHoldingModal::getTypes();
    $categories = CoreFinanceInvestmentHoldingModal::getCategories();
    $riskLevels = CoreFinanceInvestmentHoldingModal::getRiskLevels();
    $dividendFrequencies = CoreFinanceInvestmentHoldingModal::getDividendFrequencies();

    return view('core.finance.investments.holdings.edit', compact('account', 'holding', 'types', 'categories', 'riskLevels', 'dividendFrequencies'));
  }

  /**
   * Update the specified holding.
   */
  public function update(Request $request, CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentHoldingModal $holding)
  {
    $validated = $request->validate([
      'symbol' => 'required|string|max:50',
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceInvestmentHoldingModal::getTypes()),
      'category' => 'required|string|in:' . implode(',', CoreFinanceInvestmentHoldingModal::getCategories()),
      'current_price' => 'required|numeric|min:0',
      'annual_income' => 'nullable|numeric|min:0',
      'yield_percentage' => 'nullable|numeric|min:0',
      'allocation_percentage' => 'nullable|numeric|between:0,100',
      'target_allocation_percentage' => 'nullable|numeric|between:0,100',
      'last_trade_date' => 'nullable|date',
      'last_dividend_date' => 'nullable|date',
      'next_dividend_date' => 'nullable|date',
      'dividend_frequency' => 'nullable|string|in:' . implode(',', CoreFinanceInvestmentHoldingModal::getDividendFrequencies()),
      'risk_level' => 'required|string|in:' . implode(',', CoreFinanceInvestmentHoldingModal::getRiskLevels()),
      'sector' => 'nullable|string|max:100',
      'industry' => 'nullable|string|max:100',
      'country' => 'nullable|string|max:100',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'maturity_date' => 'nullable|date',
      'coupon_rate' => 'nullable|numeric|min:0',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:active,inactive'
    ]);

    try {
      DB::beginTransaction();

      // Calculate old market value and unrealized gain/loss
      $oldMarketValue = $holding->market_value;
      $oldUnrealizedGainLoss = $holding->unrealized_gain_loss;

      // Calculate new market value and unrealized gain/loss
      $validated['market_value'] = $holding->quantity * $validated['current_price'];
      $validated['unrealized_gain_loss'] = $validated['market_value'] - $holding->cost_basis;

      // Update holding
      $holding->update($validated);

      // Update account market value and unrealized gain/loss
      $account->market_value = $account->market_value - $oldMarketValue + $validated['market_value'];
      $account->unrealized_gain_loss = $account->unrealized_gain_loss - $oldUnrealizedGainLoss + $validated['unrealized_gain_loss'];
      $account->save();

      DB::commit();

      return redirect()
        ->route('finance.investments.holdings.show', [$account, $holding])
        ->with('success', 'Investment holding updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update investment holding. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified holding.
   */
  public function destroy(CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentHoldingModal $holding)
  {
    if ($holding->transactions()->exists()) {
      return back()->withErrors(['error' => 'Cannot delete holding with transactions.']);
    }

    try {
      DB::beginTransaction();

      // Update account market value and unrealized gain/loss
      $account->market_value -= $holding->market_value;
      $account->unrealized_gain_loss -= $holding->unrealized_gain_loss;
      $account->save();

      // Delete holding
      $holding->delete();

      DB::commit();

      return redirect()
        ->route('finance.investments.holdings.index', $account)
        ->with('success', 'Investment holding deleted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to delete investment holding. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the performance report.
   */
  public function performance(Request $request, CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentHoldingModal $holding)
  {
    $startDate = $request->get('start_date', now()->startOfYear());
    $endDate = $request->get('end_date', now());

    $transactions = $holding->transactions()
      ->whereBetween('transaction_date', [$startDate, $endDate])
      ->orderBy('transaction_date')
      ->get()
      ->groupBy(function ($transaction) {
        return $transaction->transaction_date->format('Y-m');
      });

    return view('core.finance.investments.holdings.performance', compact('account', 'holding', 'transactions', 'startDate', 'endDate'));
  }

  /**
   * Display the income report.
   */
  public function income(Request $request, CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentHoldingModal $holding)
  {
    $startDate = $request->get('start_date', now()->startOfYear());
    $endDate = $request->get('end_date', now());

    $transactions = $holding->transactions()
      ->whereIn('type', ['dividend', 'interest'])
      ->whereBetween('transaction_date', [$startDate, $endDate])
      ->orderBy('transaction_date')
      ->get()
      ->groupBy(function ($transaction) {
        return $transaction->transaction_date->format('Y-m');
      });

    return view('core.finance.investments.holdings.income', compact('account', 'holding', 'transactions', 'startDate', 'endDate'));
  }
}
