<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;

class CoreFinanceReportSectionModal extends Model
{
  use HasFactory;

  protected $table = 'finance_report_sections';

  protected $fillable = [
    'report_id',
    'name',
    'type', // accounts_list, calculation, custom_query
    'sequence',
    'accounts',
    'calculation',
    'query',
    'parameters',
    'filters',
    'grouping',
    'sorting',
    'show_subtotal',
    'subtotal_label',
    'show_total',
    'total_label',
    'notes',
    'created_by'
  ];

  protected $casts = [
    'accounts' => 'array',
    'parameters' => 'array',
    'filters' => 'array',
    'grouping' => 'array',
    'sorting' => 'array',
    'show_subtotal' => 'boolean',
    'show_total' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the report that owns the section.
   */
  public function report(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceReportModal::class, 'report_id');
  }

  /**
   * Get the user who created the section.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all available section types.
   */
  public static function getTypes(): array
  {
    return [
      'accounts_list',
      'calculation',
      'custom_query'
    ];
  }

  /**
   * Generate data for the section.
   */
  public function generateData(string $startDate, string $endDate): array
  {
    switch ($this->type) {
      case 'accounts_list':
        return $this->generateAccountsListData($startDate, $endDate);
      case 'calculation':
        return $this->generateCalculationData($startDate, $endDate);
      case 'custom_query':
        return $this->generateCustomQueryData($startDate, $endDate);
      default:
        return [];
    }
  }

  /**
   * Generate data for accounts list type section.
   */
  protected function generateAccountsListData(string $startDate, string $endDate): array
  {
    $query = CoreFinanceJournalEntryModal::query()
      ->whereIn('account_id', $this->accounts)
      ->whereBetween('date', [$startDate, $endDate]);

    // Apply filters
    if (!empty($this->filters)) {
      foreach ($this->filters as $filter) {
        $query->where($filter['field'], $filter['operator'], $filter['value']);
      }
    }

    // Apply grouping
    if (!empty($this->grouping)) {
      $query->groupBy($this->grouping);
    }

    // Apply sorting
    if (!empty($this->sorting)) {
      foreach ($this->sorting as $sort) {
        $query->orderBy($sort['field'], $sort['direction']);
      }
    }

    return $query->get()
      ->map(function ($entry) {
        return [
          'account' => $entry->account->name,
          'code' => $entry->account->code,
          'debit' => $entry->type === 'debit' ? $entry->amount : 0,
          'credit' => $entry->type === 'credit' ? $entry->amount : 0,
          'balance' => $entry->type === 'debit' ? $entry->amount : -$entry->amount
        ];
      })
      ->groupBy('account')
      ->map(function ($entries) {
        return [
          'code' => $entries->first()['code'],
          'debit' => $entries->sum('debit'),
          'credit' => $entries->sum('credit'),
          'balance' => $entries->sum('balance')
        ];
      })
      ->toArray();
  }

  /**
   * Generate data for calculation type section.
   */
  protected function generateCalculationData(string $startDate, string $endDate): array
  {
    // Parse and evaluate the calculation expression
    $expression = $this->calculation;
    $parameters = $this->parameters ?? [];

    // Replace parameter placeholders with actual values
    foreach ($parameters as $key => $value) {
      $expression = str_replace(':' . $key, $value, $expression);
    }

    // Evaluate the expression
    try {
      return ['result' => eval('return ' . $expression . ';')];
    } catch (\Exception $e) {
      return ['error' => 'Failed to evaluate calculation: ' . $e->getMessage()];
    }
  }

  /**
   * Generate data for custom query type section.
   */
  protected function generateCustomQueryData(string $startDate, string $endDate): array
  {
    $query = $this->query;
    $parameters = array_merge($this->parameters ?? [], [
      'start_date' => $startDate,
      'end_date' => $endDate
    ]);

    // Replace parameter placeholders with actual values
    foreach ($parameters as $key => $value) {
      $query = str_replace(':' . $key, $value, $query);
    }

    try {
      return DB::select($query);
    } catch (\Exception $e) {
      return ['error' => 'Failed to execute query: ' . $e->getMessage()];
    }
  }

  /**
   * Get a human-readable type name.
   */
  public function getTypeName(): string
  {
    return ucwords(str_replace('_', ' ', $this->type));
  }

  /**
   * Scope a query to only include sections of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to order by sequence.
   */
  public function scopeOrdered($query)
  {
    return $query->orderBy('sequence');
  }
}
