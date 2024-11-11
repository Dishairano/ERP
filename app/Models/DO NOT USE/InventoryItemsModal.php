<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItemsModal extends Model
{
  protected $table = 'items';

  protected $fillable = [
    'code',
    'name',
    'description',
    'category_id',
    'unit_id',
    'unit_cost',
    'unit_price',
    'status',
    'barcode',
    'manufacturer',
    'supplier_id',
    'weight',
    'dimensions',
    'is_stockable',
    'is_purchasable',
    'is_sellable',
    'tax_rate',
    'notes'
  ];

  protected $casts = [
    'unit_cost' => 'decimal:2',
    'unit_price' => 'decimal:2',
    'weight' => 'decimal:2',
    'tax_rate' => 'decimal:2',
    'is_stockable' => 'boolean',
    'is_purchasable' => 'boolean',
    'is_sellable' => 'boolean'
  ];

  public function category()
  {
    return $this->belongsTo(ItemCategory::class);
  }

  public function unit()
  {
    return $this->belongsTo(Unit::class);
  }

  public function supplier()
  {
    return $this->belongsTo(Supplier::class);
  }

  public function stockLevels()
  {
    return $this->hasMany(InventoryDashboardModal::class, 'item_id');
  }

  public function movements()
  {
    return $this->hasMany(StockMovement::class);
  }

  public function adjustments()
  {
    return $this->hasMany(StockAdjustment::class);
  }

  public function getTotalStockQuantityAttribute()
  {
    return $this->stockLevels()->sum('quantity');
  }

  public function getTotalStockValueAttribute()
  {
    return $this->total_stock_quantity * $this->unit_cost;
  }

  public function getMarginAttribute()
  {
    if ($this->unit_cost == 0) return 0;
    return (($this->unit_price - $this->unit_cost) / $this->unit_cost) * 100;
  }

  public function getStockStatusAttribute()
  {
    $totalStock = $this->total_stock_quantity;
    $minStock = $this->stockLevels()->min('minimum_stock') ?? 0;
    $maxStock = $this->stockLevels()->max('maximum_stock') ?? 0;
    $reorderPoint = $this->stockLevels()->min('reorder_point') ?? 0;

    if ($totalStock <= $minStock) {
      return 'critical';
    } elseif ($totalStock <= $reorderPoint) {
      return 'low';
    } elseif ($totalStock >= $maxStock) {
      return 'excess';
    } else {
      return 'normal';
    }
  }

  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  public function scopeStockable($query)
  {
    return $query->where('is_stockable', true);
  }

  public function scopePurchasable($query)
  {
    return $query->where('is_purchasable', true);
  }

  public function scopeSellable($query)
  {
    return $query->where('is_sellable', true);
  }

  public function scopeLowStock($query)
  {
    return $query->whereHas('stockLevels', function ($q) {
      $q->whereRaw('quantity <= reorder_point');
    });
  }

  public function scopeByCategory($query, $categoryId)
  {
    return $query->where('category_id', $categoryId);
  }

  public function scopeBySupplier($query, $supplierId)
  {
    return $query->where('supplier_id', $supplierId);
  }

  public function scopeSearch($query, $search)
  {
    return $query->where(function ($q) use ($search) {
      $q->where('code', 'like', "%{$search}%")
        ->orWhere('name', 'like', "%{$search}%")
        ->orWhere('barcode', 'like', "%{$search}%");
    });
  }
}
