<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreFinanceAssetDepreciationModal extends Model
{
  use HasFactory;

  protected $table = 'finance_asset_depreciations';

  protected $fillable = [
    'asset_id',
    'date',
    'amount',
    'created_by'
  ];

  protected $casts = [
    'date' => 'date',
    'amount' => 'decimal:2',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the asset that owns the depreciation entry.
   */
  public function asset(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceAssetModal::class, 'asset_id');
  }

  /**
   * Get the user who created the depreciation entry.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the journal entries for the depreciation.
   */
  public function journalEntries()
  {
    return $this->hasMany(CoreFinanceJournalEntryModal::class, 'reference_id')
      ->where('reference_type', 'asset_depreciation');
  }

  /**
   * Format the amount as currency.
   */
  public function getFormattedAmount(): string
  {
    return number_format($this->amount, 2);
  }

  /**
   * Scope a query to only include entries within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include entries for a specific asset.
   */
  public function scopeForAsset($query, $assetId)
  {
    return $query->where('asset_id', $assetId);
  }
}
