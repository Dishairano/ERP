<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
  public function index()
  {
    $currencies = Currency::all();
    return view('budgeting.currencies.index', compact('currencies'));
  }

  public function create()
  {
    return view('budgeting.currencies.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:3|unique:currencies',
      'name' => 'required|string|max:255',
      'symbol' => 'required|string|max:10',
      'exchange_rate' => 'required|numeric|min:0',
      'is_default' => 'boolean',
      'is_active' => 'boolean'
    ]);

    if ($validated['is_default']) {
      Currency::where('is_default', true)->update(['is_default' => false]);
    }

    Currency::create($validated);

    return redirect()->route('currencies.index')
      ->with('success', 'Currency created successfully.');
  }

  public function edit(Currency $currency)
  {
    return view('budgeting.currencies.edit', compact('currency'));
  }

  public function update(Request $request, Currency $currency)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:3|unique:currencies,code,' . $currency->id,
      'name' => 'required|string|max:255',
      'symbol' => 'required|string|max:10',
      'exchange_rate' => 'required|numeric|min:0',
      'is_default' => 'boolean',
      'is_active' => 'boolean'
    ]);

    if ($validated['is_default']) {
      Currency::where('is_default', true)->update(['is_default' => false]);
    }

    $currency->update($validated);

    return redirect()->route('currencies.index')
      ->with('success', 'Currency updated successfully.');
  }

  public function destroy(Currency $currency)
  {
    if ($currency->is_default) {
      return redirect()->route('currencies.index')
        ->with('error', 'Cannot delete default currency.');
    }

    $currency->delete();

    return redirect()->route('currencies.index')
      ->with('success', 'Currency deleted successfully.');
  }

  public function convert(Request $request)
  {
    $validated = $request->validate([
      'amount' => 'required|numeric',
      'from_currency' => 'required|exists:currencies,id',
      'to_currency' => 'required|exists:currencies,id'
    ]);

    $fromCurrency = Currency::findOrFail($validated['from_currency']);
    $toCurrency = Currency::findOrFail($validated['to_currency']);

    // Convert to base currency (EUR) first, then to target currency
    $amountInBase = $validated['amount'] / $fromCurrency->exchange_rate;
    $convertedAmount = $amountInBase * $toCurrency->exchange_rate;

    return response()->json([
      'amount' => $validated['amount'],
      'from_currency' => $fromCurrency->code,
      'to_currency' => $toCurrency->code,
      'converted_amount' => round($convertedAmount, 2)
    ]);
  }
}
