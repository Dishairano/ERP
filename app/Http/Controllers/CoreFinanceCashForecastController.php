<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceCashForecastModal;
use App\Models\CoreFinanceCashForecastItemModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceCashForecastController extends Controller
{
  /**
   * Display a listing of forecasts.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceCashForecastModal::query()
      ->with(['creator']);

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
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
      $query->whereBetween('forecast_date', [$request->start_date, $request->end_date]);
    }

    // Filter recurring forecasts
    if ($request->has('recurring')) {
      $query->where('is_recurring', $request->boolean('recurring'));
    }

    // Filter by probability threshold
    if ($request->has('min_probability')) {
      $query->where('probability', '>=', $request->min_probability);
    }

    $forecasts = $query->orderBy('forecast_date')
      ->paginate(10);

    $types = CoreFinanceCashForecastModal::getTypes();
    $categories = CoreFinanceCashForecastModal::getCategories();
    $recurrencePatterns = CoreFinanceCashForecastModal::getRecurrencePatterns();

    return view('core.finance.cash-forecasts.index', compact('forecasts', 'types', 'categories', 'recurrencePatterns'));
  }

  /**
   * Show the form for creating a new forecast.
   */
  public function create()
  {
    $types = CoreFinanceCashForecastModal::getTypes();
    $categories = CoreFinanceCashForecastModal::getCategories();
    $recurrencePatterns = CoreFinanceCashForecastModal::getRecurrencePatterns();

    return view('core.finance.cash-forecasts.create', compact('types', 'categories', 'recurrencePatterns'));
  }

  /**
   * Store a newly created forecast.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceCashForecastModal::getTypes()),
      'category' => 'required|string|in:' . implode(',', CoreFinanceCashForecastModal::getCategories()),
      'forecast_date' => 'required|date',
      'amount' => 'required|numeric|not_in:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'probability' => 'required|integer|between:0,100',
      'is_recurring' => 'boolean',
      'recurrence_pattern' => 'nullable|required_if:is_recurring,true|string|in:' . implode(',', CoreFinanceCashForecastModal::getRecurrencePatterns()),
      'recurrence_end_date' => 'nullable|required_if:is_recurring,true|date|after:forecast_date',
      'reference_type' => 'nullable|string|max:50',
      'reference_id' => 'nullable|integer',
      'description' => 'nullable|string',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:draft,confirmed,cancelled'
    ]);

    try {
      DB::beginTransaction();

      // Create forecast
      $validated['created_by'] = Auth::id();
      $forecast = CoreFinanceCashForecastModal::create($validated);

      // Create initial forecast item
      $itemData = [
        'forecast_id' => $forecast->id,
        'date' => $validated['forecast_date'],
        'amount' => $validated['amount'],
        'currency' => $validated['currency'],
        'exchange_rate' => $validated['exchange_rate'],
        'probability' => $validated['probability'],
        'description' => $validated['description'],
        'notes' => $validated['notes'],
        'status' => 'pending',
        'created_by' => Auth::id()
      ];

      CoreFinanceCashForecastItemModal::create($itemData);

      // Create recurring items if applicable
      if ($validated['is_recurring']) {
        $nextDate = $forecast->getNextOccurrenceDate();
        while ($nextDate && $nextDate <= $validated['recurrence_end_date']) {
          $itemData['date'] = $nextDate;
          CoreFinanceCashForecastItemModal::create($itemData);
          $nextDate = $forecast->getNextOccurrenceDate();
        }
      }

      DB::commit();

      return redirect()
        ->route('finance.cash-forecasts.show', $forecast)
        ->with('success', 'Cash forecast created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create cash forecast. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified forecast.
   */
  public function show(CoreFinanceCashForecastModal $forecast)
  {
    $forecast->load(['creator', 'items' => function ($query) {
      $query->orderBy('date');
    }]);

    return view('core.finance.cash-forecasts.show', compact('forecast'));
  }

  /**
   * Show the form for editing the specified forecast.
   */
  public function edit(CoreFinanceCashForecastModal $forecast)
  {
    $types = CoreFinanceCashForecastModal::getTypes();
    $categories = CoreFinanceCashForecastModal::getCategories();
    $recurrencePatterns = CoreFinanceCashForecastModal::getRecurrencePatterns();

    return view('core.finance.cash-forecasts.edit', compact('forecast', 'types', 'categories', 'recurrencePatterns'));
  }

  /**
   * Update the specified forecast.
   */
  public function update(Request $request, CoreFinanceCashForecastModal $forecast)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceCashForecastModal::getTypes()),
      'category' => 'required|string|in:' . implode(',', CoreFinanceCashForecastModal::getCategories()),
      'forecast_date' => 'required|date',
      'amount' => 'required|numeric|not_in:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'probability' => 'required|integer|between:0,100',
      'is_recurring' => 'boolean',
      'recurrence_pattern' => 'nullable|required_if:is_recurring,true|string|in:' . implode(',', CoreFinanceCashForecastModal::getRecurrencePatterns()),
      'recurrence_end_date' => 'nullable|required_if:is_recurring,true|date|after:forecast_date',
      'reference_type' => 'nullable|string|max:50',
      'reference_id' => 'nullable|integer',
      'description' => 'nullable|string',
      'notes' => 'nullable|string',
      'status' => 'required|string|in:draft,confirmed,cancelled'
    ]);

    try {
      DB::beginTransaction();

      // Update forecast
      $forecast->update($validated);

      // Update or create forecast items
      if ($validated['is_recurring']) {
        // Delete future items
        $forecast->items()
          ->where('date', '>', now())
          ->where('status', 'pending')
          ->delete();

        // Create new recurring items
        $itemData = [
          'forecast_id' => $forecast->id,
          'amount' => $validated['amount'],
          'currency' => $validated['currency'],
          'exchange_rate' => $validated['exchange_rate'],
          'probability' => $validated['probability'],
          'description' => $validated['description'],
          'notes' => $validated['notes'],
          'status' => 'pending',
          'created_by' => Auth::id()
        ];

        $nextDate = $forecast->getNextOccurrenceDate();
        while ($nextDate && $nextDate <= $validated['recurrence_end_date']) {
          $itemData['date'] = $nextDate;
          CoreFinanceCashForecastItemModal::create($itemData);
          $nextDate = $forecast->getNextOccurrenceDate();
        }
      }

      DB::commit();

      return redirect()
        ->route('finance.cash-forecasts.show', $forecast)
        ->with('success', 'Cash forecast updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update cash forecast. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified forecast.
   */
  public function destroy(CoreFinanceCashForecastModal $forecast)
  {
    try {
      DB::beginTransaction();

      // Delete forecast items
      $forecast->items()->delete();

      // Delete forecast
      $forecast->delete();

      DB::commit();

      return redirect()
        ->route('finance.cash-forecasts.index')
        ->with('success', 'Cash forecast deleted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to delete cash forecast. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the forecast analysis.
   */
  public function analysis(Request $request)
  {
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now()->endOfMonth());

    $forecasts = CoreFinanceCashForecastModal::with(['items'])
      ->whereHas('items', function ($query) use ($startDate, $endDate) {
        $query->whereBetween('date', [$startDate, $endDate]);
      })
      ->get();

    return view('core.finance.cash-forecasts.analysis', compact('forecasts', 'startDate', 'endDate'));
  }

  /**
   * Display the variance report.
   */
  public function variance(Request $request)
  {
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now()->endOfMonth());

    $items = CoreFinanceCashForecastItemModal::with(['forecast'])
      ->whereBetween('date', [$startDate, $endDate])
      ->where('status', 'realized')
      ->orderBy('date')
      ->get();

    return view('core.finance.cash-forecasts.variance', compact('items', 'startDate', 'endDate'));
  }

  /**
   * Display the accuracy report.
   */
  public function accuracy(Request $request)
  {
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now()->endOfMonth());

    $items = CoreFinanceCashForecastItemModal::with(['forecast'])
      ->whereBetween('date', [$startDate, $endDate])
      ->where('status', 'realized')
      ->orderBy('date')
      ->get()
      ->groupBy('forecast_id');

    return view('core.finance.cash-forecasts.accuracy', compact('items', 'startDate', 'endDate'));
  }
}
