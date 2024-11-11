<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\WorkCenter;
use App\Models\BillOfMaterial;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
  /**
   * Display the production dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function dashboard()
  {
    $totalOrders = ProductionOrder::count();
    $pendingOrders = ProductionOrder::where('status', 'pending')->count();
    $inProgressOrders = ProductionOrder::where('status', 'in_progress')->count();
    $completedOrders = ProductionOrder::where('status', 'completed')->count();

    return view('production.dashboard', compact(
      'totalOrders',
      'pendingOrders',
      'inProgressOrders',
      'completedOrders'
    ));
  }

  /**
   * Display a listing of production orders.
   *
   * @return \Illuminate\View\View
   */
  public function orders()
  {
    $orders = ProductionOrder::with(['product', 'workCenter'])
      ->latest()
      ->paginate(10);

    return view('production.orders', compact('orders'));
  }

  /**
   * Display production planning.
   *
   * @return \Illuminate\View\View
   */
  public function planning()
  {
    $workCenters = WorkCenter::with(['productionOrders' => function ($query) {
      $query->where('status', '!=', 'completed')
        ->orderBy('scheduled_date');
    }])->get();

    return view('production.planning', compact('workCenters'));
  }

  /**
   * Display production scheduling.
   *
   * @return \Illuminate\View\View
   */
  public function scheduling()
  {
    $workCenters = WorkCenter::with(['productionOrders' => function ($query) {
      $query->where('status', '!=', 'completed')
        ->orderBy('scheduled_date');
    }])->get();

    return view('production.scheduling', compact('workCenters'));
  }

  /**
   * Store a new production order.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'quantity' => 'required|numeric|min:1',
      'work_center_id' => 'required|exists:work_centers,id',
      'scheduled_date' => 'required|date',
      'priority' => 'required|in:low,medium,high',
      'notes' => 'nullable|string'
    ]);

    ProductionOrder::create($validated);

    return redirect()->route('production.orders')
      ->with('success', 'Production order created successfully.');
  }

  /**
   * Update the specified production order.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\ProductionOrder  $order
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, ProductionOrder $order)
  {
    $validated = $request->validate([
      'status' => 'required|in:pending,in_progress,completed,cancelled',
      'actual_quantity' => 'nullable|numeric|min:0',
      'completion_date' => 'nullable|date',
      'notes' => 'nullable|string'
    ]);

    $order->update($validated);

    return redirect()->route('production.orders')
      ->with('success', 'Production order updated successfully.');
  }
}
