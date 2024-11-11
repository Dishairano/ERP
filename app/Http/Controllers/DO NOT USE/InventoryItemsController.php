<?php

namespace App\Http\Controllers;

use App\Models\InventoryItemsModal;
use App\Models\ItemCategory;
use App\Models\Unit;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryItemsController extends Controller
{
  public function index(Request $request)
  {
    $query = InventoryItemsModal::with(['category', 'unit', 'supplier', 'stockLevels']);

    // Apply filters
    if ($request->has('category')) {
      $query->byCategory($request->category);
    }

    if ($request->has('supplier')) {
      $query->bySupplier($request->supplier);
    }

    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    if ($request->has('stock_status')) {
      switch ($request->stock_status) {
        case 'low':
          $query->lowStock();
          break;
        case 'excess':
          $query->whereHas('stockLevels', function ($q) {
            $q->whereRaw('quantity >= maximum_stock');
          });
          break;
      }
    }

    if ($request->has('search')) {
      $query->search($request->search);
    }

    // Get items with pagination
    $items = $query->orderBy('name')->paginate(15);

    // Get related data for filters
    $categories = ItemCategory::active()->get();
    $suppliers = Supplier::active()->get();
    $units = Unit::active()->get();

    // Calculate statistics
    $statistics = [
      'total_items' => InventoryItemsModal::count(),
      'low_stock' => InventoryItemsModal::lowStock()->count(),
      'total_value' => InventoryItemsModal::join('stock_levels', 'items.id', '=', 'stock_levels.item_id')
        ->sum(DB::raw('stock_levels.quantity * items.unit_cost')),
      'active_items' => InventoryItemsModal::where('status', 'active')->count()
    ];

    return view('inventory.items.index', compact(
      'items',
      'categories',
      'suppliers',
      'units',
      'statistics'
    ));
  }

  public function create()
  {
    $categories = ItemCategory::active()->get();
    $suppliers = Supplier::active()->get();
    $units = Unit::active()->get();

    return view('inventory.items.create', compact('categories', 'suppliers', 'units'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|unique:items',
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'category_id' => 'required|exists:item_categories,id',
      'unit_id' => 'required|exists:units,id',
      'unit_cost' => 'required|numeric|min:0',
      'unit_price' => 'required|numeric|min:0',
      'status' => 'required|in:active,inactive,discontinued',
      'barcode' => 'nullable|string|unique:items',
      'manufacturer' => 'nullable|string',
      'supplier_id' => 'nullable|exists:suppliers,id',
      'weight' => 'nullable|numeric|min:0',
      'dimensions' => 'nullable|string',
      'is_stockable' => 'boolean',
      'is_purchasable' => 'boolean',
      'is_sellable' => 'boolean',
      'tax_rate' => 'nullable|numeric|min:0',
      'notes' => 'nullable|string'
    ]);

    $item = InventoryItemsModal::create($validated);

    // Create initial stock levels if item is stockable
    if ($item->is_stockable) {
      foreach ($request->stock_levels ?? [] as $stockLevel) {
        $item->stockLevels()->create([
          'warehouse_id' => $stockLevel['warehouse_id'],
          'quantity' => $stockLevel['quantity'] ?? 0,
          'minimum_stock' => $stockLevel['minimum_stock'] ?? 0,
          'maximum_stock' => $stockLevel['maximum_stock'] ?? 0,
          'reorder_point' => $stockLevel['reorder_point'] ?? 0
        ]);
      }
    }

    return redirect()->route('inventory.items.index')
      ->with('success', 'Item created successfully');
  }

  public function show(InventoryItemsModal $item)
  {
    $item->load(['category', 'unit', 'supplier', 'stockLevels.warehouse']);

    // Get recent movements
    $recentMovements = $item->movements()
      ->with('warehouse')
      ->orderByDesc('created_at')
      ->limit(10)
      ->get();

    // Get recent adjustments
    $recentAdjustments = $item->adjustments()
      ->with(['warehouse', 'user'])
      ->orderByDesc('created_at')
      ->limit(10)
      ->get();

    return view('inventory.items.show', compact(
      'item',
      'recentMovements',
      'recentAdjustments'
    ));
  }

  public function edit(InventoryItemsModal $item)
  {
    $item->load(['category', 'unit', 'supplier', 'stockLevels.warehouse']);

    $categories = ItemCategory::active()->get();
    $suppliers = Supplier::active()->get();
    $units = Unit::active()->get();

    return view('inventory.items.edit', compact(
      'item',
      'categories',
      'suppliers',
      'units'
    ));
  }

  public function update(Request $request, InventoryItemsModal $item)
  {
    $validated = $request->validate([
      'code' => 'required|string|unique:items,code,' . $item->id,
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'category_id' => 'required|exists:item_categories,id',
      'unit_id' => 'required|exists:units,id',
      'unit_cost' => 'required|numeric|min:0',
      'unit_price' => 'required|numeric|min:0',
      'status' => 'required|in:active,inactive,discontinued',
      'barcode' => 'nullable|string|unique:items,barcode,' . $item->id,
      'manufacturer' => 'nullable|string',
      'supplier_id' => 'nullable|exists:suppliers,id',
      'weight' => 'nullable|numeric|min:0',
      'dimensions' => 'nullable|string',
      'is_stockable' => 'boolean',
      'is_purchasable' => 'boolean',
      'is_sellable' => 'boolean',
      'tax_rate' => 'nullable|numeric|min:0',
      'notes' => 'nullable|string'
    ]);

    $item->update($validated);

    // Update stock levels if item is stockable
    if ($item->is_stockable && $request->has('stock_levels')) {
      foreach ($request->stock_levels as $stockLevel) {
        $item->stockLevels()->updateOrCreate(
          ['warehouse_id' => $stockLevel['warehouse_id']],
          [
            'minimum_stock' => $stockLevel['minimum_stock'] ?? 0,
            'maximum_stock' => $stockLevel['maximum_stock'] ?? 0,
            'reorder_point' => $stockLevel['reorder_point'] ?? 0
          ]
        );
      }
    }

    return redirect()->route('inventory.items.index')
      ->with('success', 'Item updated successfully');
  }

  public function destroy(InventoryItemsModal $item)
  {
    // Check if item can be deleted
    if ($item->movements()->exists() || $item->adjustments()->exists()) {
      return redirect()->back()->with('error', 'Item cannot be deleted as it has related transactions');
    }

    $item->stockLevels()->delete();
    $item->delete();

    return redirect()->route('inventory.items.index')
      ->with('success', 'Item deleted successfully');
  }

  public function export(Request $request)
  {
    $items = InventoryItemsModal::with(['category', 'unit', 'supplier', 'stockLevels'])
      ->get()
      ->map(function ($item) {
        return [
          'Code' => $item->code,
          'Name' => $item->name,
          'Category' => $item->category->name,
          'Unit' => $item->unit->name,
          'Cost' => $item->unit_cost,
          'Price' => $item->unit_price,
          'Total Stock' => $item->total_stock_quantity,
          'Stock Value' => $item->total_stock_value,
          'Status' => $item->status
        ];
      });

    // Implementation depends on your export library

    return redirect()->back()->with('success', 'Export started successfully');
  }
}
