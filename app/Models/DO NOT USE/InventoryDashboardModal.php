<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryDashboardModal extends Model
{
  protected $table = 'stock_levels';

  protected $fillable = [
    'item_id',
    'warehouse_id',
    'quantity',
    'minimum_stock',
    'maximum_stock',
    'reorder_point',
    'last_counted_at',
    'last_counted_by'
  ];

  protected $casts = [
    'quantity' => 'decimal:2',
    'minimum_stock' => 'decimal:2',
    'maximum_stock' => 'decimal:2',
    'reorder_point' => 'decimal:2',
    'last_counted_at' => 'datetime'
  ];

  public function item()
  {
    return $this->belongsTo(Item::class);
  }

  public function warehouse()
  {
    return $this->belongsTo(Warehouse::class);
  }

  public function counter()
  {
    return $this->belongsTo(User::class, 'last_counted_by');
  }

  public function movements()
  {
    return $this->hasMany(StockMovement::class, 'stock_level_id');
  }

  public function adjustments()
  {
    return $this->hasMany(StockAdjustment::class, 'stock_level_id');
  }

  public function getStockStatusAttribute()
  {
    if ($this->quantity <= $this->minimum_stock) {
      return 'critical';
    } elseif ($this->quantity <= $this->reorder_point) {
      return 'low';
    } elseif ($this->quantity >= $this->maximum_stock) {
      return 'excess';
    } else {
      return 'normal';
    }
  }

  public function getStockValueAttribute()
  {
    return $this->quantity * $this->item->unit_cost;
  }

  public function scopeLowStock($query)
  {
    return $query->whereRaw('quantity <= reorder_point');
  }

  public function scopeExcessStock($query)
  {
    return $query->whereRaw('quantity >= maximum_stock');
  }

  public function scopeForWarehouse($query, $warehouseId)
  {
    return $query->where('warehouse_id', $warehouseId);
  }

  public function scopeForItem($query, $itemId)
  {
    return $query->where('item_id', $itemId);
  }

  public function scopeNeedsCounting($query, $days = 30)
  {
    return $query->whereNull('last_counted_at')
      ->orWhere('last_counted_at', '<=', now()->subDays($days));
  }

  public function scopeActive($query)
  {
    return $query->whereHas('item', function ($q) {
      $q->where('status', 'active');
    });
  }

  public function hasMovementsInPeriod($startDate, $endDate)
  {
    return $this->movements()
      ->whereBetween('created_at', [$startDate, $endDate])
      ->exists();
  }

  public function calculateTurnoverRate($startDate, $endDate)
  {
    $totalOutgoing = $this->movements()
      ->where('type', 'outgoing')
      ->whereBetween('created_at', [$startDate, $endDate])
      ->sum('quantity');

    $averageStock = ($this->quantity + $this->minimum_stock) / 2;

    return $averageStock > 0 ? ($totalOutgoing / $averageStock) : 0;
  }
}
