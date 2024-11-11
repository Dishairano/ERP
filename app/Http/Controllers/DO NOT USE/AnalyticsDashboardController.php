<?php

namespace App\Http\Controllers;

use App\Models\KpiValue;
use App\Models\SalesOrder;
use App\Models\ProductionOrder;
use App\Models\FinancialMetric;
use App\Models\KpiDefinition;
use App\Models\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AnalyticsDashboardController extends Controller
{
  /**
   * Display the executive dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function executive()
  {
    // Get all executive KPIs
    $kpis = KpiValue::with('definition')
      ->whereHas('definition', function ($query) {
        $query->where('category', 'executive');
      })
      ->latest()
      ->get()
      ->groupBy('definition.name');

    // Ensure we have a Revenue entry even if empty
    if (!$kpis->has('Revenue')) {
      $revenueDefinition = KpiDefinition::firstOrCreate(
        ['name' => 'Revenue', 'category' => 'executive'],
        [
          'code' => 'REV',
          'description' => 'Total Revenue',
          'unit' => 'currency',
          'calculation_method' => 'sum',
          'data_source' => 'sales_orders',
          'frequency' => 'monthly',
          'is_active' => true,
          'created_by' => Auth::id()
        ]
      );

      $kpis['Revenue'] = new Collection();
    }

    return view('analytics.executive', compact('kpis'));
  }

  /**
   * Display the financial dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function financial()
  {
    $metrics = FinancialMetric::latest()
      ->get()
      ->groupBy('category');

    return view('analytics.financial', compact('metrics'));
  }

  /**
   * Display the operational dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function operational()
  {
    $productionMetrics = ProductionOrder::selectRaw('
            COUNT(*) as total_orders,
            COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_orders,
            AVG(CASE WHEN completion_date IS NOT NULL
                THEN DATEDIFF(completion_date, start_date)
                END) as avg_completion_time
        ')->first();

    $salesMetrics = SalesOrder::selectRaw('
            COUNT(*) as total_orders,
            SUM(total_amount) as total_sales,
            AVG(total_amount) as avg_order_value
        ')->first();

    return view('analytics.operational', compact(
      'productionMetrics',
      'salesMetrics'
    ));
  }

  /**
   * Display custom dashboards.
   *
   * @return \Illuminate\View\View
   */
  public function custom()
  {
    $dashboards = Auth::user()->dashboards()
      ->with(['components' => function ($query) {
        $query->orderBy('position');
      }])
      ->get();

    return view('analytics.custom', compact('dashboards'));
  }

  /**
   * Store a new custom dashboard.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'layout' => 'required|array',
      'components' => 'required|array',
      'components.*.type' => 'required|string',
      'components.*.position' => 'required|integer',
      'components.*.settings' => 'required|array'
    ]);

    $dashboard = Auth::user()->dashboards()->create([
      'name' => $validated['name'],
      'description' => $validated['description'],
      'layout' => $validated['layout']
    ]);

    foreach ($validated['components'] as $component) {
      $dashboard->components()->create($component);
    }

    return redirect()->route('analytics.custom')
      ->with('success', 'Dashboard created successfully.');
  }

  /**
   * Update a custom dashboard.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Dashboard  $dashboard
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, Dashboard $dashboard)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'layout' => 'required|array',
      'components' => 'required|array',
      'components.*.type' => 'required|string',
      'components.*.position' => 'required|integer',
      'components.*.settings' => 'required|array'
    ]);

    $dashboard->update([
      'name' => $validated['name'],
      'description' => $validated['description'],
      'layout' => $validated['layout']
    ]);

    // Update components
    $dashboard->components()->delete();
    foreach ($validated['components'] as $component) {
      $dashboard->components()->create($component);
    }

    return redirect()->route('analytics.custom')
      ->with('success', 'Dashboard updated successfully.');
  }
}
