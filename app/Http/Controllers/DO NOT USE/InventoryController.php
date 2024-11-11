<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLevel;
use App\Models\StockMovement;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
  /**
   * Display the inventory dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function dashboard()
  {
    $totalProducts = Product::count();
    $lowStockProducts = StockLevel::whereColumn('quantity', '<=', 'reorder_point')->count();
    $outOfStockProducts = StockLevel::where('quantity', 0)->count();
    $recentMovements = StockMovement::with(['product', 'warehouse'])
      ->latest()
      ->take(5)
      ->get();

    return view('inventory.dashboard', compact(
      'totalProducts',
      'lowStockProducts',
      'outOfStockProducts',
      'recentMovements'
    ));
  }

  /**
   * Display items and products.
   *
   * @return \Illuminate\View\View
   */
  public function items()
  {
    $products = Product::with(['stockLevels', 'category'])
      ->latest()
      ->paginate(10);

    return view('inventory.items', compact('products'));
  }

  /**
   * Display stock levels.
   *
   * @return \Illuminate\View\View
   */
  public function stockLevels()
  {
    $stockLevels = StockLevel::with(['product', 'warehouse', 'warehouseZone'])
      ->latest()
      ->paginate(10);

    return view('inventory.stock-levels', compact('stockLevels'));
  }

  /**
   * Display stock movements.
   *
   * @return \Illuminate\View\View
   */
  public function movements()
  {
    $movements = StockMovement::with(['product', 'warehouse'])
      ->latest()
      ->paginate(10);

    return view('inventory.movements', compact('movements'));
  }

  /**
   * Display stock adjustments.
   *
   * @return \Illuminate\View\View
   */
  public function adjustments()
  {
    $adjustments = StockAdjustment::with(['product', 'warehouse', 'approver'])
      ->latest()
      ->paginate(10);

    return view('inventory.adjustments', compact('adjustments'));
  }

  /**
   * Store a new stock adjustment.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeAdjustment(Request $request)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'warehouse_id' => 'required|exists:warehouses,id',
      'type' => 'required|in:addition,subtraction',
      'quantity' => 'required|numeric|min:0',
      'reason' => 'required|string',
      'notes' => 'nullable|string'
    ]);

    $adjustment = StockAdjustment::create([
      ...$validated,
      'status' => 'pending',
      'requested_by' => auth()->id()
    ]);

    return redirect()->route('inventory.adjustments')
      ->with('success', 'Stock adjustment requested successfully.');
  }

  /**
   * Approve a stock adjustment.
   *
   * @param  \App\Models\StockAdjustment  $adjustment
   * @return \Illuminate\Http\RedirectResponse
   */
  public function approveAdjustment(StockAdjustment $adjustment)
  {
    $adjustment->update([
      'status' => 'approved',
      'approved_by' => auth()->id(),
      'approved_at' => now()
    ]);

    // Create stock movement
    StockMovement::create([
      'product_id' => $adjustment->product_id,
      'warehouse_id' => $adjustment->warehouse_id,
      'type' => $adjustment->type === 'addition' ? 'in' : 'out',
      'quantity' => $adjustment->quantity,
      'reference_type' => 'stock_adjustment',
      'reference_id' => $adjustment->id
    ]);

    // Update stock level
    $stockLevel = StockLevel::firstOrCreate([
      'product_id' => $adjustment->product_id,
      'warehouse_id' => $adjustment->warehouse_id
    ]);

    if ($adjustment->type === 'addition') {
      $stockLevel->increment('quantity', $adjustment->quantity);
    } else {
      $stockLevel->decrement('quantity', $adjustment->quantity);
    }

    return redirect()->route('inventory.adjustments')
      ->with('success', 'Stock adjustment approved successfully.');
  }
}
