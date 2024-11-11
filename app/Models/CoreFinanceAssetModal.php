<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class CoreFinanceAssetModal extends Model
{
  use HasFactory;

  protected $table = 'finance_assets';

  protected $fillable = [
    'code',
    'name',
    'category',
    'purchase_date',
    'purchase_cost',
    'salvage_value',
    'useful_life_years',
    'depreciation_method', // straight_line, declining_balance, sum_of_years_digits
    'depreciation_rate',
    'last_depreciation_date',
    'accumulated_depreciation',
    'current_value',
    'location',
    'status', // active, disposed, written_off
    'disposal_date',
    'disposal_value',
    'description',
    'notes',
    'created_by'
  ];

  protected $casts = [
    'purchase_date' => 'date',
    'purchase_cost' => 'decimal:2',
    'salvage_value' => 'decimal:2',
    'useful_life_years' => 'integer',
    'depreciation_rate' => 'decimal:4',
    'last_depreciation_date' => 'date',
    'accumulated_depreciation' => 'decimal:2',
    'current_value' => 'decimal:2',
    'disposal_date' => 'date',
    'disposal_value' => 'decimal:2',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the user who created the asset.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the depreciation entries for the asset.
   */
  public function depreciationEntries(): HasMany
  {
    return $this->hasMany(CoreFinanceAssetDepreciationModal::class, 'asset_id');
  }

  /**
   * Get the journal entries for the asset.
   */
  public function journalEntries(): HasMany
  {
    return $this->hasMany(CoreFinanceJournalEntryModal::class, 'reference_id')
      ->where('reference_type', 'asset');
  }

  /**
   * Get all available asset categories.
   */
  public static function getCategories(): array
  {
    return [
      'land',
      'buildings',
      'machinery',
      'equipment',
      'vehicles',
      'furniture',
      'computers',
      'software',
      'other'
    ];
  }

  /**
   * Get all available depreciation methods.
   */
  public static function getDepreciationMethods(): array
  {
    return [
      'straight_line',
      'declining_balance',
      'sum_of_years_digits'
    ];
  }

  /**
   * Calculate depreciation for a period.
   */
  public function calculateDepreciation(string $date): float
  {
    $depreciableAmount = $this->purchase_cost - $this->salvage_value;
    $monthsSinceLastDepreciation = $this->last_depreciation_date
      ? now()->diffInMonths($this->last_depreciation_date)
      : now()->diffInMonths($this->purchase_date);

    switch ($this->depreciation_method) {
      case 'straight_line':
        return $this->calculateStraightLineDepreciation($depreciableAmount, $monthsSinceLastDepreciation);

      case 'declining_balance':
        return $this->calculateDecliningBalanceDepreciation($depreciableAmount, $monthsSinceLastDepreciation);

      case 'sum_of_years_digits':
        return $this->calculateSumOfYearsDigitsDepreciation($depreciableAmount, $monthsSinceLastDepreciation);

      default:
        return 0;
    }
  }

  /**
   * Calculate straight line depreciation.
   */
  private function calculateStraightLineDepreciation(float $depreciableAmount, int $months): float
  {
    $annualDepreciation = $depreciableAmount / $this->useful_life_years;
    return ($annualDepreciation / 12) * $months;
  }

  /**
   * Calculate declining balance depreciation.
   */
  private function calculateDecliningBalanceDepreciation(float $depreciableAmount, int $months): float
  {
    $remainingValue = $this->current_value - $this->salvage_value;
    $monthlyRate = $this->depreciation_rate / 12;
    return $remainingValue * $monthlyRate * $months;
  }

  /**
   * Calculate sum of years digits depreciation.
   */
  private function calculateSumOfYearsDigitsDepreciation(float $depreciableAmount, int $months): float
  {
    $sumOfYears = ($this->useful_life_years * ($this->useful_life_years + 1)) / 2;
    $yearsRemaining = $this->useful_life_years - (now()->diffInYears($this->purchase_date));
    $annualDepreciation = ($depreciableAmount * $yearsRemaining) / $sumOfYears;
    return ($annualDepreciation / 12) * $months;
  }

  /**
   * Record depreciation for a period.
   */
  public function recordDepreciation(string $date, float $amount): void
  {
    $this->depreciationEntries()->create([
      'date' => $date,
      'amount' => $amount,
      'created_by' => Auth::id()
    ]);

    $this->accumulated_depreciation += $amount;
    $this->current_value = $this->purchase_cost - $this->accumulated_depreciation;
    $this->last_depreciation_date = $date;
    $this->save();
  }

  /**
   * Dispose of the asset.
   */
  public function dispose(string $date, float $value, string $notes = null): void
  {
    $this->status = 'disposed';
    $this->disposal_date = $date;
    $this->disposal_value = $value;
    $this->notes = $notes;
    $this->save();
  }

  /**
   * Write off the asset.
   */
  public function writeOff(string $date, string $notes = null): void
  {
    $this->status = 'written_off';
    $this->disposal_date = $date;
    $this->disposal_value = 0;
    $this->notes = $notes;
    $this->save();
  }

  /**
   * Format the purchase cost as currency.
   */
  public function getFormattedPurchaseCost(): string
  {
    return number_format($this->purchase_cost, 2);
  }

  /**
   * Format the current value as currency.
   */
  public function getFormattedCurrentValue(): string
  {
    return number_format($this->current_value, 2);
  }

  /**
   * Format the accumulated depreciation as currency.
   */
  public function getFormattedAccumulatedDepreciation(): string
  {
    return number_format($this->accumulated_depreciation, 2);
  }

  /**
   * Get a human-readable category name.
   */
  public function getCategoryName(): string
  {
    return ucfirst($this->category);
  }

  /**
   * Get a human-readable depreciation method name.
   */
  public function getDepreciationMethodName(): string
  {
    return ucwords(str_replace('_', ' ', $this->depreciation_method));
  }

  /**
   * Get a human-readable status name.
   */
  public function getStatusName(): string
  {
    return ucwords(str_replace('_', ' ', $this->status));
  }

  /**
   * Scope a query to only include active assets.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include disposed assets.
   */
  public function scopeDisposed($query)
  {
    return $query->where('status', 'disposed');
  }

  /**
   * Scope a query to only include written off assets.
   */
  public function scopeWrittenOff($query)
  {
    return $query->where('status', 'written_off');
  }

  /**
   * Scope a query to only include assets in a specific category.
   */
  public function scopeInCategory($query, $category)
  {
    return $query->where('category', $category);
  }

  /**
   * Scope a query to only include assets using a specific depreciation method.
   */
  public function scopeUsingDepreciationMethod($query, $method)
  {
    return $query->where('depreciation_method', $method);
  }

  /**
   * Scope a query to only include assets that need depreciation.
   */
  public function scopeNeedsDepreciation($query)
  {
    return $query->where('status', 'active')
      ->where(function ($q) {
        $q->whereNull('last_depreciation_date')
          ->orWhereRaw('DATEDIFF(NOW(), last_depreciation_date) >= 30');
      });
  }
}
