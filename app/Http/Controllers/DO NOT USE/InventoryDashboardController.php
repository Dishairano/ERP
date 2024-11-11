<?php

namespace App\Http\Controllers;

use App\Models\InventoryDashboardModal;
use App\Models\Item;
use App\Models\Warehouse;
use App\Models\StockMovement;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryDashboardController extends Controller
{
  public function index()
  {
    // Get stock value statistics
    $stockValueStats = InventoryDashboardModal::join('items', 'stock_levels.item_id', '=', 'items.id')
      ->select(
        DB::raw('SUM(stock_levels.quantity * items.unit_cost) as total_value'),
        DB::raw('COUNT(DISTINCT stock_levels.item_id) as total_items'),
        DB::raw('COUNT(DISTINCT stock_levels.warehouse_id) as total_warehouses')
      )
      ->first();

    // Get low stock items
    $lowStockItems = InventoryDashboardModal::with(['item', 'warehouse'])
      ->whereRaw('quantity <= reorder_point')
      ->orderBy('quantity')
      ->limit(10)
      ->get();

    // Get excess stock items
    $excessStockItems = InventoryDashboardModal::with(['item', 'warehouse'])
      ->whereRaw('quantity >= maximum_stock')
      ->orderByDesc('quantity')
      ->limit(10)
      ->get();

    // Get recent movements
    $recentMovements = StockMovement::with(['item', 'warehouse'])
      ->orderByDesc('created_at')
      ->limit(10)
      ->get();

    // Get recent adjustments
    $recentAdjustments = StockAdjustment::with(['item', 'warehouse', 'user'])
      ->orderByDesc('created_at')
      ->limit(10)
      ->get();

    // Get stock level by warehouse
    $stockByWarehouse = InventoryDashboardModal::join('items', 'stock_levels.item_id', '=', 'items.id')
      ->join('warehouses', 'stock_levels.warehouse_id', '=', 'warehouses.id')
      ->select(
        'warehouses.name',
        DB::raw('COUNT(DISTINCT stock_levels.item_id) as items_count'),
        DB::raw('SUM(stock_levels.quantity) as total_quantity'),
        DB::raw('SUM(stock_levels.quantity * items.unit_cost) as total_value')
      )
      ->groupBy('warehouses.id', 'warehouses.name')
      ->get();

    // Get items needing stock count
    $needsCounting = InventoryDashboardModal::with(['item', 'warehouse'])
      ->whereNull('last_counted_at')
      ->orWhere('last_counted_at', '<=', now()->subDays(30))
      ->orderBy('last_counted_at')
      ->limit(10)
      ->get();

    // Get movement trends
    $movementTrends = StockMovement::select(
      DB::raw('DATE(created_at) as date'),
      DB::raw('SUM(CASE WHEN type = "incoming" THEN quantity ELSE 0 END) as incoming'),
      DB::raw('SUM(CASE WHEN type = "outgoing" THEN quantity ELSE 0 END) as outgoing')
    )
      ->whereBetween('created_at', [now()->subDays(30), now()])
      ->groupBy('date')
      ->orderBy('date')
      ->get();

    return view('inventory.dashboard', compact(
      'stockValueStats',
      'lowStockItems',
      'excessStockItems',
      'recentMovements',
      'recentAdjustments',
      'stockByWarehouse',
      'needsCounting',
      'movementTrends'
    ));
  }

  public function getStockLevels(Request $request)
  {
    $query = InventoryDashboardModal::with(['item', 'warehouse']);

    if ($request->has('warehouse_id')) {
      $query->where('warehouse_id', $request->warehouse_id);
    }

    if ($request->has('status')) {
      switch ($request->status) {
        case 'low':
          $query->whereRaw('quantity <= reorder_point');
          break;
        case 'excess':
          $query->whereRaw('quantity >= maximum_stock');
          break;
        case 'normal':
          $query->whereRaw('quantity > reorder_point')
            ->whereRaw('quantity < maximum_stock');
          break;
      }
    }

    $stockLevels = $query->paginate(15);

    return response()->json($stockLevels);
  }

  public function getMovementTrends(Request $request)
  {
    $startDate = $request->input('start_date', now()->subDays(30));
    $endDate = $request->input('end_date', now());

    $trends = StockMovement::select(
      DB::raw('DATE(created_at) as date'),
      DB::raw('SUM(CASE WHEN type = "incoming" THEN quantity ELSE 0 END) as incoming'),
      DB::raw('SUM(CASE WHEN type = "outgoing" THEN quantity ELSE 0 END) as outgoing')
    )
      ->whereBetween('created_at', [$startDate, $endDate])
      ->groupBy('date')
      ->orderBy('date')
      ->get();

    return response()->json($trends);
  }

  public function getWarehouseStats(Request $request)
  {
    $warehouseId = $request->warehouse_id;

    $stats = InventoryDashboardModal::where('warehouse_id', $warehouseId)
      ->join('items', 'stock_levels.item_id', '=', 'items.id')
      ->select(
        DB::raw('COUNT(DISTINCT stock_levels.item_id) as items_count'),
        DB::raw('SUM(stock_levels.quantity) as total_quantity'),
        DB::raw('SUM(stock_levels.quantity * items.unit_cost) as total_value'),
        DB::raw('COUNT(CASE WHEN stock_levels.quantity <= stock_levels.reorder_point THEN 1 END) as low_stock_count')
      )
      ->first();

    return response()->json($stats);
  }
}
