<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoreFinanceAccountModal extends Model
{
  use HasFactory;

  protected $table = 'finance_accounts';

  protected $fillable = [
    'code',
    'name',
    'type',
    'category',
    'parent_id',
    'description',
    'is_active',
    'balance',
    'opening_balance',
    'currency',
    'tax_rate',
    'notes'
  ];

  protected $casts = [
    'is_active' => 'boolean',
    'balance' => 'decimal:2',
    'opening_balance' => 'decimal:2',
    'tax_rate' => 'decimal:2',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the parent account.
   */
  public function parent(): BelongsTo
  {
    return $this->belongsTo(self::class, 'parent_id');
  }

  /**
   * Get the child accounts.
   */
  public function children(): HasMany
  {
    return $this->hasMany(self::class, 'parent_id');
  }

  /**
   * Get the journal entries for this account.
   */
  public function journalEntries(): HasMany
  {
    return $this->hasMany(CoreFinanceJournalEntryModal::class, 'account_id');
  }

  /**
   * Get all available account types.
   */
  public static function getTypes(): array
  {
    return [
      'asset',
      'liability',
      'equity',
      'revenue',
      'expense'
    ];
  }

  /**
   * Get all available account categories.
   */
  public static function getCategories(): array
  {
    return [
      'current_asset',
      'fixed_asset',
      'current_liability',
      'long_term_liability',
      'owner_equity',
      'operating_revenue',
      'other_revenue',
      'operating_expense',
      'other_expense',
      'tax_expense'
    ];
  }

  /**
   * Calculate the current balance.
   */
  public function calculateBalance(): void
  {
    $credits = $this->journalEntries()->where('type', 'credit')->sum('amount');
    $debits = $this->journalEntries()->where('type', 'debit')->sum('amount');

    $this->balance = $this->opening_balance + ($credits - $debits);
    $this->save();
  }

  /**
   * Get the full account code (including parent codes).
   */
  public function getFullCode(): string
  {
    $codes = [$this->code];
    $parent = $this->parent;

    while ($parent) {
      array_unshift($codes, $parent->code);
      $parent = $parent->parent;
    }

    return implode('.', $codes);
  }

  /**
   * Get the full account name (including parent names).
   */
  public function getFullName(): string
  {
    $names = [$this->name];
    $parent = $this->parent;

    while ($parent) {
      array_unshift($names, $parent->name);
      $parent = $parent->parent;
    }

    return implode(' > ', $names);
  }

  /**
   * Format the balance as currency.
   */
  public function getFormattedBalance(): string
  {
    return number_format($this->balance, 2);
  }

  /**
   * Format the opening balance as currency.
   */
  public function getFormattedOpeningBalance(): string
  {
    return number_format($this->opening_balance, 2);
  }

  /**
   * Get a human-readable type name.
   */
  public function getTypeName(): string
  {
    return ucfirst($this->type);
  }

  /**
   * Get a human-readable category name.
   */
  public function getCategoryName(): string
  {
    return ucfirst(str_replace('_', ' ', $this->category));
  }

  /**
   * Scope a query to only include active accounts.
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  /**
   * Scope a query to only include accounts of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include accounts of a specific category.
   */
  public function scopeInCategory($query, $category)
  {
    return $query->where('category', $category);
  }

  /**
   * Scope a query to only include root accounts (no parent).
   */
  public function scopeRoot($query)
  {
    return $query->whereNull('parent_id');
  }
}
