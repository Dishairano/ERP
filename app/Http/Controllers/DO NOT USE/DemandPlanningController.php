<?php

namespace App\Http\Controllers;

use App\Models\DemandForecast;
use App\Models\HistoricalSale;
use App\Models\MarketTrend;
use App\Models\PromotionalEvent;
use App\Models\ForecastAccuracy;
use App\Models\DemandNotification;
use App\Models\DemandScenario;
use App\Models\DemandBudget;
use App\Models\Product;
use App\Models\Department;
use App\Exports\DemandForecastExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DemandPlanningController extends Controller
{
  public function index()
  {
    $forecasts = DemandForecast::with(['product', 'region', 'accuracy'])
      ->orderBy('forecast_date', 'desc')
      ->paginate(10);

    $notifications = DemandNotification::where('user_id', Auth::id())
      ->where('is_read', false)
      ->get();

    return view('demand-planning.index', compact('forecasts', 'notifications'));
  }

  public function create()
  {
    $products = Product::all();
    $regions = Department::all();
    $forecastMethods = DemandForecast::getForecastMethods();
    $marketTrends = MarketTrend::where('end_date', '>=', now())->get();
    $promotions = PromotionalEvent::where('end_date', '>=', now())->get();

    return view('demand-planning.create', compact(
      'products',
      'regions',
      'forecastMethods',
      'marketTrends',
      'promotions'
    ));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'region_id' => 'nullable|exists:departments,id',
      'forecast_date' => 'required|date',
      'forecast_quantity' => 'required|integer|min:0',
      'forecast_value' => 'required|numeric|min:0',
      'forecast_method' => 'required|string',
      'confidence_level' => 'required|numeric|between:0,100',
      'seasonal_factors' => 'nullable|array'
    ]);

    $validated['created_by'] = Auth::id();

    DB::transaction(function () use ($validated) {
      $forecast = DemandForecast::create($validated);

      if (isset($validated['budget_id'])) {
        DemandBudget::create([
          'forecast_id' => $forecast->id,
          'budget_id' => $validated['budget_id'],
          'allocated_amount' => $validated['allocated_amount'],
          'status' => 'planned'
        ]);
      }
    });

    return redirect()->route('demand-planning.index')
      ->with('success', 'Forecast created successfully');
  }

  public function show(DemandForecast $forecast)
  {
    $forecast->load(['product', 'region', 'accuracy', 'budget']);

    $historicalSales = HistoricalSale::where('product_id', $forecast->product_id)
      ->where('region_id', $forecast->region_id)
      ->orderBy('sale_date', 'desc')
      ->get();

    $accuracy = $forecast->accuracy;
    $relatedTrends = MarketTrend::whereJsonContains('affected_products', $forecast->product_id)
      ->orWhereJsonContains('affected_regions', $forecast->region_id)
      ->get();

    return view('demand-planning.show', compact(
      'forecast',
      'historicalSales',
      'accuracy',
      'relatedTrends'
    ));
  }

  public function edit(DemandForecast $forecast)
  {
    $products = Product::all();
    $regions = Department::all();
    $forecastMethods = DemandForecast::getForecastMethods();
    $marketTrends = MarketTrend::where('end_date', '>=', now())->get();
    $promotions = PromotionalEvent::where('end_date', '>=', now())->get();

    return view('demand-planning.edit', compact(
      'forecast',
      'products',
      'regions',
      'forecastMethods',
      'marketTrends',
      'promotions'
    ));
  }

  public function update(Request $request, DemandForecast $forecast)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'region_id' => 'nullable|exists:departments,id',
      'forecast_date' => 'required|date',
      'forecast_quantity' => 'required|integer|min:0',
      'forecast_value' => 'required|numeric|min:0',
      'forecast_method' => 'required|string',
      'confidence_level' => 'required|numeric|between:0,100',
      'seasonal_factors' => 'nullable|array'
    ]);

    DB::transaction(function () use ($forecast, $validated, $request) {
      $forecast->update($validated);

      if ($request->has('budget_id')) {
        $forecast->budget()->updateOrCreate(
          ['forecast_id' => $forecast->id],
          [
            'budget_id' => $request->budget_id,
            'allocated_amount' => $request->allocated_amount,
            'status' => 'updated'
          ]
        );
      }
    });

    return redirect()->route('demand-planning.show', ['forecast' => $forecast])
      ->with('success', 'Forecast updated successfully');
  }

  public function destroy(DemandForecast $forecast)
  {
    $forecast->delete();
    return redirect()->route('demand-planning.index')
      ->with('success', 'Forecast deleted successfully');
  }

  public function scenarios()
  {
    $scenarios = DemandScenario::with('creator')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('demand-planning.scenarios', compact('scenarios'));
  }

  public function createScenario()
  {
    $products = Product::all();
    $regions = Department::all();
    $marketTrends = MarketTrend::all();

    return view('demand-planning.create-scenario', compact(
      'products',
      'regions',
      'marketTrends'
    ));
  }

  public function storeScenario(Request $request)
  {
    $validated = $request->validate([
      'scenario_name' => 'required|string|max:255',
      'description' => 'required|string',
      'scenario_factors' => 'required|array',
      'results' => 'required|array'
    ]);

    $validated['created_by'] = Auth::id();

    DemandScenario::create($validated);

    return redirect()->route('demand-planning.scenarios')
      ->with('success', 'Scenario created successfully');
  }

  public function accuracy()
  {
    $accuracyReports = ForecastAccuracy::with(['forecast.product', 'forecast.region'])
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('demand-planning.accuracy', compact('accuracyReports'));
  }

  public function updateAccuracy(Request $request, DemandForecast $forecast)
  {
    $validated = $request->validate([
      'actual_quantity' => 'required|integer|min:0',
      'actual_value' => 'required|numeric|min:0',
      'variance_reason' => 'nullable|string'
    ]);

    $accuracy_percentage = (
      abs($forecast->forecast_quantity - $validated['actual_quantity']) /
      $forecast->forecast_quantity
    ) * 100;

    $bias = ($validated['actual_quantity'] - $forecast->forecast_quantity) /
      $forecast->forecast_quantity;

    $forecast->accuracy()->updateOrCreate(
      ['forecast_id' => $forecast->id],
      [
        'actual_quantity' => $validated['actual_quantity'],
        'actual_value' => $validated['actual_value'],
        'accuracy_percentage' => $accuracy_percentage,
        'bias' => $bias,
        'variance_reason' => $validated['variance_reason']
      ]
    );

    return redirect()->route('demand-planning.show', ['forecast' => $forecast])
      ->with('success', 'Accuracy updated successfully');
  }

  public function export()
  {
    $export = new DemandForecastExport();
    return Excel::download($export, 'demand-forecasts.xlsx');
  }

  public function dashboard()
  {
    $recentForecasts = DemandForecast::with(['product', 'region', 'accuracy'])
      ->orderBy('created_at', 'desc')
      ->take(5)
      ->get();

    $accuracyStats = DB::table('forecast_accuracy')
      ->selectRaw('
                AVG(accuracy_percentage) as avg_accuracy,
                AVG(bias) as avg_bias,
                COUNT(*) as total_forecasts
            ')->first();

    $trendingProducts = HistoricalSale::selectRaw('
            product_id,
            SUM(quantity_sold) as total_quantity,
            SUM(sale_value) as total_value
        ')
      ->groupBy('product_id')
      ->orderBy('total_value', 'desc')
      ->take(5)
      ->get();

    $activePromotions = PromotionalEvent::where('end_date', '>=', now())
      ->orderBy('start_date')
      ->get();

    return view('demand-planning.dashboard', compact(
      'recentForecasts',
      'accuracyStats',
      'trendingProducts',
      'activePromotions'
    ));
  }
}
