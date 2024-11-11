<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseZone;
use App\Models\WarehouseBin;
use App\Models\PickingOrder;
use App\Models\PutawayOrder;
use App\Models\User;
use App\Models\Order;
use App\Models\GoodsReceipt;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WarehousingController extends Controller
{
  // ... (keep all other methods the same)

  /**
   * Store a new picking order.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storePicking(Request $request)
  {
    $validated = $request->validate([
      'order_id' => 'required|exists:orders,id',
      'warehouse_id' => 'required|exists:warehouses,id',
      'priority' => 'required|in:low,medium,high',
      'picker_id' => 'nullable|exists:users,id',
      'notes' => 'nullable|string',
      'items' => 'required|array',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.bin_id' => 'required|exists:warehouse_bins,id',
      'items.*.quantity' => 'required|numeric|min:1'
    ]);

    DB::transaction(function () use ($validated) {
      $order = PickingOrder::create([
        'order_id' => $validated['order_id'],
        'warehouse_id' => $validated['warehouse_id'],
        'priority' => $validated['priority'],
        'picker_id' => $validated['picker_id'],
        'notes' => $validated['notes'],
        'status' => 'pending',
        'created_by' => Auth::id()
      ]);

      foreach ($validated['items'] as $item) {
        $order->items()->create([
          'product_id' => $item['product_id'],
          'bin_id' => $item['bin_id'],
          'quantity' => $item['quantity'],
          'picked_quantity' => 0,
          'status' => 'pending'
        ]);
      }
    });

    return redirect()->route('warehousing.picking')
      ->with('success', 'Picking order created successfully.');
  }

  /**
   * Store a new putaway order.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storePutaway(Request $request)
  {
    $validated = $request->validate([
      'receipt_id' => 'required|exists:goods_receipts,id',
      'warehouse_id' => 'required|exists:warehouses,id',
      'priority' => 'required|in:low,medium,high',
      'handler_id' => 'nullable|exists:users,id',
      'notes' => 'nullable|string',
      'items' => 'required|array',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.bin_id' => 'required|exists:warehouse_bins,id',
      'items.*.quantity' => 'required|numeric|min:1'
    ]);

    DB::transaction(function () use ($validated) {
      $order = PutawayOrder::create([
        'receipt_id' => $validated['receipt_id'],
        'warehouse_id' => $validated['warehouse_id'],
        'priority' => $validated['priority'],
        'handler_id' => $validated['handler_id'],
        'notes' => $validated['notes'],
        'status' => 'pending',
        'created_by' => Auth::id()
      ]);

      foreach ($validated['items'] as $item) {
        $order->items()->create([
          'product_id' => $item['product_id'],
          'bin_id' => $item['bin_id'],
          'quantity' => $item['quantity'],
          'putaway_quantity' => 0,
          'status' => 'pending'
        ]);
      }
    });

    return redirect()->route('warehousing.putaway')
      ->with('success', 'Putaway order created successfully.');
  }

  // ... (keep all other methods the same)
}
