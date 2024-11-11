<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceCashForecastModal;
use App\Models\CoreFinanceCashForecastItemModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceCashForecastItemController extends Controller
{
  /**
   * Display a listing of forecast items.
   */
  public function index(CoreFinanceCashForecastModal $forecast, Request $request)
  {
    $query = $forecast->items()
      ->with(['creator']);

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by date range
    if ($request->has('start_date') && $request->has('end_date')) {
      $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    // Filter by probability threshold
    if ($request->has('min_probability')) {
      $query->where('probability', '>=', $request->min_probability);
    }

    // Filter by variance threshold
    if ($request->has('variance_threshold')) {
      $query->where('status', 'realized')
        ->whereRaw('ABS(variance_percentage) >= ?', [$request->variance_threshold]);
    }

    $items = $query->orderBy('date')
      ->paginate(10);

    return view('core.finance.cash-forecasts.items.index', compact('forecast', 'items'));
  }

  /**
   * Show the form for creating a new forecast item.
   */
  public function create(CoreFinanceCashForecastModal $forecast)
  {
    return view('core.finance.cash-forecasts.items.create', compact('forecast'));
  }

  /**
   * Store a newly created forecast item.
   */
  public function store(Request $request, CoreFinanceCashForecastModal $forecast)
  {
    $validated = $request->validate([
      'date' => 'required|date',
      'amount' => 'required|numeric|not_in:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'probability' => 'required|integer|between:0,100',
      'description' => 'nullable|string',
      'notes' => 'nullable|string'
    ]);

    $validated['forecast_id'] = $forecast->id;
    $validated['created_by'] = Auth::id();
    $validated['status'] = 'pending';

    $item = CoreFinanceCashForecastItemModal::create($validated);

    return redirect()
      ->route('finance.cash-forecasts.items.show', [$forecast, $item])
      ->with('success', 'Forecast item created successfully');
  }

  /**
   * Display the specified forecast item.
   */
  public function show(CoreFinanceCashForecastModal $forecast, CoreFinanceCashForecastItemModal $item)
  {
    $item->load(['creator']);

    return view('core.finance.cash-forecasts.items.show', compact('forecast', 'item'));
  }

  /**
   * Show the form for editing the specified forecast item.
   */
  public function edit(CoreFinanceCashForecastModal $forecast, CoreFinanceCashForecastItemModal $item)
  {
    if ($item->status !== 'pending') {
      return back()->withErrors(['error' => 'Only pending items can be edited.']);
    }

    return view('core.finance.cash-forecasts.items.edit', compact('forecast', 'item'));
  }

  /**
   * Update the specified forecast item.
   */
  public function update(Request $request, CoreFinanceCashForecastModal $forecast, CoreFinanceCashForecastItemModal $item)
  {
    if ($item->status !== 'pending') {
      return back()->withErrors(['error' => 'Only pending items can be updated.']);
    }

    $validated = $request->validate([
      'date' => 'required|date',
      'amount' => 'required|numeric|not_in:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'probability' => 'required|integer|between:0,100',
      'description' => 'nullable|string',
      'notes' => 'nullable|string'
    ]);

    $item->update($validated);

    return redirect()
      ->route('finance.cash-forecasts.items.show', [$forecast, $item])
      ->with('success', 'Forecast item updated successfully');
  }

  /**
   * Remove the specified forecast item.
   */
  public function destroy(CoreFinanceCashForecastModal $forecast, CoreFinanceCashForecastItemModal $item)
  {
    if ($item->status !== 'pending') {
      return back()->withErrors(['error' => 'Only pending items can be deleted.']);
    }

    $item->delete();

    return redirect()
      ->route('finance.cash-forecasts.items.index', $forecast)
      ->with('success', 'Forecast item deleted successfully');
  }

  /**
   * Realize the specified forecast item.
   */
  public function realize(Request $request, CoreFinanceCashForecastModal $forecast, CoreFinanceCashForecastItemModal $item)
  {
    if ($item->status !== 'pending') {
      return back()->withErrors(['error' => 'Only pending items can be realized.']);
    }

    $validated = $request->validate([
      'realized_amount' => 'required|numeric|not_in:0',
      'realization_date' => 'nullable|date'
    ]);

    try {
      DB::beginTransaction();

      $item->realize($validated['realized_amount'], $validated['realization_date'] ?? null);

      DB::commit();

      return redirect()
        ->route('finance.cash-forecasts.items.show', [$forecast, $item])
        ->with('success', 'Forecast item realized successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to realize forecast item. ' . $e->getMessage()]);
    }
  }

  /**
   * Cancel the specified forecast item.
   */
  public function cancel(CoreFinanceCashForecastModal $forecast, CoreFinanceCashForecastItemModal $item)
  {
    if ($item->status !== 'pending') {
      return back()->withErrors(['error' => 'Only pending items can be cancelled.']);
    }

    $item->cancel();

    return redirect()
      ->route('finance.cash-forecasts.items.show', [$forecast, $item])
      ->with('success', 'Forecast item cancelled successfully');
  }

  /**
   * Display the variance analysis for the specified forecast item.
   */
  public function variance(CoreFinanceCashForecastModal $forecast, CoreFinanceCashForecastItemModal $item)
  {
    if ($item->status !== 'realized') {
      return back()->withErrors(['error' => 'Variance analysis is only available for realized items.']);
    }

    return view('core.finance.cash-forecasts.items.variance', compact('forecast', 'item'));
  }

  /**
   * Bulk update forecast items.
   */
  public function bulkUpdate(Request $request, CoreFinanceCashForecastModal $forecast)
  {
    $validated = $request->validate([
      'items' => 'required|array',
      'items.*.id' => 'required|exists:finance_cash_forecast_items,id',
      'items.*.amount' => 'required|numeric|not_in:0',
      'items.*.probability' => 'required|integer|between:0,100'
    ]);

    try {
      DB::beginTransaction();

      foreach ($validated['items'] as $itemData) {
        $item = CoreFinanceCashForecastItemModal::find($itemData['id']);
        if ($item && $item->status === 'pending') {
          $item->update([
            'amount' => $itemData['amount'],
            'probability' => $itemData['probability']
          ]);
        }
      }

      DB::commit();

      return redirect()
        ->route('finance.cash-forecasts.items.index', $forecast)
        ->with('success', 'Forecast items updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update forecast items. ' . $e->getMessage()]);
    }
  }
}
