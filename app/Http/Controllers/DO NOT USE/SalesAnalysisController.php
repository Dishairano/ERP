<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class SalesAnalysisController extends Controller
{
  /**
   * Display the sales performance dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function performance()
  {
    $totalSales = SalesOrder::sum('total_amount');
    $monthlySales = SalesOrder::whereMonth('created_at', now()->month)->sum('total_amount');
    $topCustomers = Customer::withSum('salesOrders', 'total_amount')
      ->orderByDesc('sales_orders_sum_total_amount')
      ->take(5)
      ->get();
    $topProducts = Product::withSum('salesOrderItems', 'quantity')
      ->orderByDesc('sales_order_items_sum_quantity')
      ->take(5)
      ->get();

    return view('sales-analysis.performance', compact(
      'totalSales',
      'monthlySales',
      'topCustomers',
      'topProducts'
    ));
  }

  /**
   * Display sales trends analysis.
   *
   * @return \Illuminate\View\View
   */
  public function trends()
  {
    $salesTrends = SalesOrder::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
      ->groupBy('month')
      ->orderBy('month')
      ->get();

    return view('sales-analysis.trends', compact('salesTrends'));
  }

  /**
   * Display sales forecasting.
   *
   * @return \Illuminate\View\View
   */
  public function forecasting()
  {
    $historicalSales = SalesOrder::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
      ->groupBy('month')
      ->orderBy('month')
      ->get();

    return view('sales-analysis.forecasting', compact('historicalSales'));
  }

  /**
   * Display sales reports.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\View\View
   */
  public function reports(Request $request)
  {
    $startDate = $request->input('start_date', now()->startOfMonth());
    $endDate = $request->input('end_date', now()->endOfMonth());

    $salesData = SalesOrder::whereBetween('created_at', [$startDate, $endDate])
      ->with(['customer', 'items.product'])
      ->get();

    return view('sales-analysis.reports', compact('salesData', 'startDate', 'endDate'));
  }
}
