<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLevel;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class InventoryPlanningController extends Controller
{
  /**
   * Display reorder points management.
   *
   * @return \Illuminate\View\View
   */
  public function reorderPoints()
  {
    $products = Product::with(['stockLevels'])
      ->select('products.*')
      ->selectRaw('
                (SELECT SUM(quantity) FROM stock_movements
                WHERE product_id = products.id
                AND type = "out"
                AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                ) as monthly_consumption
            ')
      ->paginate(10);

    return view('inventory-planning.reorder-points', compact('products'));
  }

  /**
   * Display demand forecasting.
   *
   * @return \Illuminate\View\View
   */
  public function forecasting()
  {
    $products = Product::with(['stockMovements' => function ($query) {
      $query->where('created_at', '>=', now()->subMonths(6))
        ->orderBy('created_at');
    }])->paginate(10);

    return view('inventory-planning.forecasting', compact('products'));
  }

  /**
   * Display ABC analysis.
   *
   * @return \Illuminate\View\View
   */
  public function abcAnalysis()
  {
    // Get products with their total value
    $products = Product::select('products.*')
      ->selectRaw('
                products.unit_price * (
                    SELECT SUM(quantity) FROM stock_movements
                    WHERE product_id = products.id
                    AND type = "out"
                    AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                ) as annual_usage_value
            ')
      ->orderByDesc('annual_usage_value')
      ->get();

    // Calculate total value
    $totalValue = $products->sum('annual_usage_value');

    // Calculate cumulative percentage and assign ABC classification
    $cumulativeValue = 0;
    foreach ($products as $product) {
      $cumulativeValue += $product->annual_usage_value;
      $cumulativePercentage = ($cumulativeValue / $totalValue) * 100;

      if ($cumulativePercentage <= 80) {
        $product->classification = 'A';
      } elseif ($cumulativePercentage <= 95) {
        $product->classification = 'B';
      } else {
        $product->classification = 'C';
      }
    }

    // Group products by classification
    $groupedProducts = $products->groupBy('classification');

    return view('inventory-planning.abc-analysis', compact('groupedProducts', 'totalValue'));
  }

  /**
   * Update reorder points for products.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function updateReorderPoints(Request $request)
  {
    $validated = $request->validate([
      'reorder_points' => 'required|array',
      'reorder_points.*' => 'required|numeric|min:0'
    ]);

    foreach ($validated['reorder_points'] as $productId => $reorderPoint) {
      Product::where('id', $productId)->update([
        'reorder_point' => $reorderPoint
      ]);
    }

    return redirect()->route('inventory-planning.reorder-points')
      ->with('success', 'Reorder points updated successfully.');
  }

  /**
   * Generate forecast for a specific product.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\JsonResponse
   */
  public function generateForecast(Request $request, Product $product)
  {
    $historicalData = StockMovement::where('product_id', $product->id)
      ->where('type', 'out')
      ->where('created_at', '>=', now()->subMonths(6))
      ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(quantity) as total')
      ->groupBy('month')
      ->orderBy('month')
      ->get();

    // Simple moving average forecast
    $forecast = $this->calculateMovingAverageForecast($historicalData);

    return response()->json([
      'historical' => $historicalData,
      'forecast' => $forecast
    ]);
  }

  /**
   * Calculate moving average forecast.
   *
   * @param  \Illuminate\Support\Collection  $historicalData
   * @return array
   */
  private function calculateMovingAverageForecast($historicalData)
  {
    $period = 3; // 3-month moving average
    $forecast = [];

    if ($historicalData->count() >= $period) {
      $values = $historicalData->pluck('total')->toArray();
      $lastMonth = \Carbon\Carbon::createFromFormat('Y-m', $historicalData->last()->month);

      for ($i = 1; $i <= 3; $i++) { // Forecast next 3 months
        $sum = array_sum(array_slice($values, -$period));
        $average = $sum / $period;

        $forecastMonth = $lastMonth->copy()->addMonths($i)->format('Y-m');
        $forecast[] = [
          'month' => $forecastMonth,
          'total' => round($average, 2)
        ];

        array_push($values, $average);
        array_shift($values);
      }
    }

    return $forecast;
  }
}
