<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceInvestmentAccountModal;
use App\Models\CoreFinanceInvestmentHoldingModal;
use App\Models\CoreFinanceInvestmentTransactionModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceInvestmentTransactionController extends Controller
{
  /**
   * Display a listing of transactions.
   */
  public function index(CoreFinanceInvestmentAccountModal $account, Request $request)
  {
    $query = $account->transactions()
      ->with(['creator', 'holding']);

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }

    // Filter by holding
    if ($request->has('holding_id')) {
      $query->where('holding_id', $request->holding_id);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by date range
    if ($request->has('start_date') && $request->has('end_date')) {
      $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
    }

    // Filter reinvested transactions
    if ($request->has('reinvested')) {
      $query->where('is_reinvested', $request->boolean('reinvested'));
    }

    $transactions = $query->orderBy('transaction_date', 'desc')
      ->orderBy('id', 'desc')
      ->paginate(10);

    $types = CoreFinanceInvestmentTransactionModal::getTypes();
    $holdings = $account->holdings()->active()->orderBy('symbol')->get();

    return view('core.finance.investments.transactions.index', compact('account', 'transactions', 'types', 'holdings'));
  }

  /**
   * Show the form for creating a new transaction.
   */
  public function create(CoreFinanceInvestmentAccountModal $account)
  {
    $types = CoreFinanceInvestmentTransactionModal::getTypes();
    $holdings = $account->holdings()->active()->orderBy('symbol')->get();

    return view('core.finance.investments.transactions.create', compact('account', 'types', 'holdings'));
  }

  /**
   * Store a newly created transaction.
   */
  public function store(Request $request, CoreFinanceInvestmentAccountModal $account)
  {
    $validated = $request->validate([
      'holding_id' => 'nullable|exists:finance_investment_holdings,id',
      'type' => 'required|string|in:' . implode(',', CoreFinanceInvestmentTransactionModal::getTypes()),
      'transaction_date' => 'required|date',
      'settlement_date' => 'nullable|date|after_or_equal:transaction_date',
      'quantity' => 'nullable|required_if:type,buy,sell|numeric',
      'price' => 'nullable|required_if:type,buy,sell|numeric|min:0',
      'amount' => 'required|numeric|not_in:0',
      'commission' => 'nullable|numeric|min:0',
      'fees' => 'nullable|numeric|min:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'is_reinvested' => 'boolean',
      'reference_number' => 'nullable|string|max:50',
      'description' => 'nullable|string',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:pending,settled,cancelled'
    ]);

    try {
      DB::beginTransaction();

      // Calculate total amount
      $validated['investment_account_id'] = $account->id;
      $validated['created_by'] = Auth::id();
      $validated['commission'] = $validated['commission'] ?? 0;
      $validated['fees'] = $validated['fees'] ?? 0;
      $validated['total_amount'] = $validated['amount'] + $validated['commission'] + $validated['fees'];

      // Create transaction
      $transaction = CoreFinanceInvestmentTransactionModal::create($validated);

      // Update holding if applicable
      if ($validated['holding_id'] && in_array($validated['type'], ['buy', 'sell'])) {
        $holding = CoreFinanceInvestmentHoldingModal::findOrFail($validated['holding_id']);
        $oldQuantity = $holding->quantity;
        $oldMarketValue = $holding->market_value;
        $oldUnrealizedGainLoss = $holding->unrealized_gain_loss;

        // Update quantity and cost basis
        if ($validated['type'] === 'buy') {
          $holding->quantity += $validated['quantity'];
          $holding->cost_basis += $validated['total_amount'];
        } else {
          $holding->quantity -= $validated['quantity'];
          $holding->cost_basis -= ($holding->average_cost * $validated['quantity']);
          $holding->realized_gain_loss += $validated['amount'] - ($holding->average_cost * $validated['quantity']);
        }

        // Update average cost and market value
        $holding->average_cost = $holding->quantity > 0 ? $holding->cost_basis / $holding->quantity : 0;
        $holding->market_value = $holding->quantity * $holding->current_price;
        $holding->unrealized_gain_loss = $holding->market_value - $holding->cost_basis;
        $holding->save();

        // Update account balances
        $account->market_value = $account->market_value - $oldMarketValue + $holding->market_value;
        $account->unrealized_gain_loss = $account->unrealized_gain_loss - $oldUnrealizedGainLoss + $holding->unrealized_gain_loss;
        if ($validated['type'] === 'sell') {
          $account->realized_gain_loss += $validated['amount'] - ($holding->average_cost * $validated['quantity']);
        }
      }

      // Update account balance
      if (!$validated['is_reinvested']) {
        if (in_array($validated['type'], ['buy', 'fee'])) {
          $account->current_balance -= $validated['total_amount'];
        } else {
          $account->current_balance += $validated['total_amount'];
        }
      }
      $account->save();

      DB::commit();

      return redirect()
        ->route('finance.investments.transactions.show', [$account, $transaction])
        ->with('success', 'Investment transaction created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create investment transaction. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified transaction.
   */
  public function show(CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentTransactionModal $transaction)
  {
    $transaction->load(['creator', 'holding']);

    return view('core.finance.investments.transactions.show', compact('account', 'transaction'));
  }

  /**
   * Show the form for editing the specified transaction.
   */
  public function edit(CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentTransactionModal $transaction)
  {
    if ($transaction->status === 'settled') {
      return back()->withErrors(['error' => 'Cannot edit settled transactions.']);
    }

    $types = CoreFinanceInvestmentTransactionModal::getTypes();
    $holdings = $account->holdings()->active()->orderBy('symbol')->get();

    return view('core.finance.investments.transactions.edit', compact('account', 'transaction', 'types', 'holdings'));
  }

  /**
   * Update the specified transaction.
   */
  public function update(Request $request, CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentTransactionModal $transaction)
  {
    if ($transaction->status === 'settled') {
      return back()->withErrors(['error' => 'Cannot update settled transactions.']);
    }

    $validated = $request->validate([
      'holding_id' => 'nullable|exists:finance_investment_holdings,id',
      'type' => 'required|string|in:' . implode(',', CoreFinanceInvestmentTransactionModal::getTypes()),
      'transaction_date' => 'required|date',
      'settlement_date' => 'nullable|date|after_or_equal:transaction_date',
      'quantity' => 'nullable|required_if:type,buy,sell|numeric',
      'price' => 'nullable|required_if:type,buy,sell|numeric|min:0',
      'amount' => 'required|numeric|not_in:0',
      'commission' => 'nullable|numeric|min:0',
      'fees' => 'nullable|numeric|min:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'is_reinvested' => 'boolean',
      'reference_number' => 'nullable|string|max:50',
      'description' => 'nullable|string',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:pending,settled,cancelled'
    ]);

    try {
      DB::beginTransaction();

      // Calculate total amount
      $validated['commission'] = $validated['commission'] ?? 0;
      $validated['fees'] = $validated['fees'] ?? 0;
      $validated['total_amount'] = $validated['amount'] + $validated['commission'] + $validated['fees'];

      // Update transaction
      $transaction->update($validated);

      // Update holding if applicable
      if ($validated['holding_id'] && in_array($validated['type'], ['buy', 'sell'])) {
        $holding = CoreFinanceInvestmentHoldingModal::findOrFail($validated['holding_id']);
        $oldQuantity = $holding->quantity;
        $oldMarketValue = $holding->market_value;
        $oldUnrealizedGainLoss = $holding->unrealized_gain_loss;

        // Revert previous transaction
        if ($transaction->type === 'buy') {
          $holding->quantity -= $transaction->quantity;
          $holding->cost_basis -= $transaction->total_amount;
        } else {
          $holding->quantity += $transaction->quantity;
          $holding->cost_basis += ($holding->average_cost * $transaction->quantity);
          $holding->realized_gain_loss -= $transaction->amount - ($holding->average_cost * $transaction->quantity);
        }

        // Apply new transaction
        if ($validated['type'] === 'buy') {
          $holding->quantity += $validated['quantity'];
          $holding->cost_basis += $validated['total_amount'];
        } else {
          $holding->quantity -= $validated['quantity'];
          $holding->cost_basis -= ($holding->average_cost * $validated['quantity']);
          $holding->realized_gain_loss += $validated['amount'] - ($holding->average_cost * $validated['quantity']);
        }

        // Update average cost and market value
        $holding->average_cost = $holding->quantity > 0 ? $holding->cost_basis / $holding->quantity : 0;
        $holding->market_value = $holding->quantity * $holding->current_price;
        $holding->unrealized_gain_loss = $holding->market_value - $holding->cost_basis;
        $holding->save();

        // Update account balances
        $account->market_value = $account->market_value - $oldMarketValue + $holding->market_value;
        $account->unrealized_gain_loss = $account->unrealized_gain_loss - $oldUnrealizedGainLoss + $holding->unrealized_gain_loss;
        if ($validated['type'] === 'sell') {
          $account->realized_gain_loss += $validated['amount'] - ($holding->average_cost * $validated['quantity']);
        }
      }

      // Update account balance
      if (!$validated['is_reinvested']) {
        // Revert previous transaction
        if (!$transaction->is_reinvested) {
          if (in_array($transaction->type, ['buy', 'fee'])) {
            $account->current_balance += $transaction->total_amount;
          } else {
            $account->current_balance -= $transaction->total_amount;
          }
        }

        // Apply new transaction
        if (in_array($validated['type'], ['buy', 'fee'])) {
          $account->current_balance -= $validated['total_amount'];
        } else {
          $account->current_balance += $validated['total_amount'];
        }
      }
      $account->save();

      DB::commit();

      return redirect()
        ->route('finance.investments.transactions.show', [$account, $transaction])
        ->with('success', 'Investment transaction updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update investment transaction. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified transaction.
   */
  public function destroy(CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentTransactionModal $transaction)
  {
    if ($transaction->status === 'settled') {
      return back()->withErrors(['error' => 'Cannot delete settled transactions.']);
    }

    try {
      DB::beginTransaction();

      // Update holding if applicable
      if ($transaction->holding_id && in_array($transaction->type, ['buy', 'sell'])) {
        $holding = $transaction->holding;
        $oldQuantity = $holding->quantity;
        $oldMarketValue = $holding->market_value;
        $oldUnrealizedGainLoss = $holding->unrealized_gain_loss;

        // Revert transaction
        if ($transaction->type === 'buy') {
          $holding->quantity -= $transaction->quantity;
          $holding->cost_basis -= $transaction->total_amount;
        } else {
          $holding->quantity += $transaction->quantity;
          $holding->cost_basis += ($holding->average_cost * $transaction->quantity);
          $holding->realized_gain_loss -= $transaction->amount - ($holding->average_cost * $transaction->quantity);
        }

        // Update average cost and market value
        $holding->average_cost = $holding->quantity > 0 ? $holding->cost_basis / $holding->quantity : 0;
        $holding->market_value = $holding->quantity * $holding->current_price;
        $holding->unrealized_gain_loss = $holding->market_value - $holding->cost_basis;
        $holding->save();

        // Update account balances
        $account->market_value = $account->market_value - $oldMarketValue + $holding->market_value;
        $account->unrealized_gain_loss = $account->unrealized_gain_loss - $oldUnrealizedGainLoss + $holding->unrealized_gain_loss;
        if ($transaction->type === 'sell') {
          $account->realized_gain_loss -= $transaction->amount - ($holding->average_cost * $transaction->quantity);
        }
      }

      // Update account balance
      if (!$transaction->is_reinvested) {
        if (in_array($transaction->type, ['buy', 'fee'])) {
          $account->current_balance += $transaction->total_amount;
        } else {
          $account->current_balance -= $transaction->total_amount;
        }
      }
      $account->save();

      // Delete transaction
      $transaction->delete();

      DB::commit();

      return redirect()
        ->route('finance.investments.transactions.index', $account)
        ->with('success', 'Investment transaction deleted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to delete investment transaction. ' . $e->getMessage()]);
    }
  }

  /**
   * Settle the specified transaction.
   */
  public function settle(CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentTransactionModal $transaction)
  {
    if ($transaction->status !== 'pending') {
      return back()->withErrors(['error' => 'Only pending transactions can be settled.']);
    }

    $transaction->update([
      'status' => 'settled',
      'settlement_date' => now()
    ]);

    return redirect()
      ->route('finance.investments.transactions.show', [$account, $transaction])
      ->with('success', 'Investment transaction settled successfully');
  }

  /**
   * Cancel the specified transaction.
   */
  public function cancel(CoreFinanceInvestmentAccountModal $account, CoreFinanceInvestmentTransactionModal $transaction)
  {
    if ($transaction->status !== 'pending') {
      return back()->withErrors(['error' => 'Only pending transactions can be cancelled.']);
    }

    try {
      DB::beginTransaction();

      // Update holding if applicable
      if ($transaction->holding_id && in_array($transaction->type, ['buy', 'sell'])) {
        $holding = $transaction->holding;
        $oldQuantity = $holding->quantity;
        $oldMarketValue = $holding->market_value;
        $oldUnrealizedGainLoss = $holding->unrealized_gain_loss;

        // Revert transaction
        if ($transaction->type === 'buy') {
          $holding->quantity -= $transaction->quantity;
          $holding->cost_basis -= $transaction->total_amount;
        } else {
          $holding->quantity += $transaction->quantity;
          $holding->cost_basis += ($holding->average_cost * $transaction->quantity);
          $holding->realized_gain_loss -= $transaction->amount - ($holding->average_cost * $transaction->quantity);
        }

        // Update average cost and market value
        $holding->average_cost = $holding->quantity > 0 ? $holding->cost_basis / $holding->quantity : 0;
        $holding->market_value = $holding->quantity * $holding->current_price;
        $holding->unrealized_gain_loss = $holding->market_value - $holding->cost_basis;
        $holding->save();

        // Update account balances
        $account->market_value = $account->market_value - $oldMarketValue + $holding->market_value;
        $account->unrealized_gain_loss = $account->unrealized_gain_loss - $oldUnrealizedGainLoss + $holding->unrealized_gain_loss;
        if ($transaction->type === 'sell') {
          $account->realized_gain_loss -= $transaction->amount - ($holding->average_cost * $transaction->quantity);
        }
      }

      // Update account balance
      if (!$transaction->is_reinvested) {
        if (in_array($transaction->type, ['buy', 'fee'])) {
          $account->current_balance += $transaction->total_amount;
        } else {
          $account->current_balance -= $transaction->total_amount;
        }
      }
      $account->save();

      // Update transaction status
      $transaction->update(['status' => 'cancelled']);

      DB::commit();

      return redirect()
        ->route('finance.investments.transactions.show', [$account, $transaction])
        ->with('success', 'Investment transaction cancelled successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to cancel investment transaction. ' . $e->getMessage()]);
    }
  }
}
