<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceReportModal extends Model
{
  use HasFactory;

  protected $table = 'finance_reports';

  protected $fillable = [
    'name',
    'type', // balance_sheet, income_statement, cash_flow, custom
    'template',
    'parameters',
    'filters',
    'grouping',
    'sorting',
    'date_range_type', // monthly, quarterly, yearly, custom
    'start_date',
    'end_date',
    'comparison_type', // previous_period, previous_year, budget, none
    'comparison_date_range_type',
    'comparison_start_date',
    'comparison_end_date',
    'show_percentages',
    'show_variances',
    'include_zero_balances',
    'notes',
    'is_template',
    'status',
    'created_by'
  ];

  protected $casts = [
    'parameters' => 'array',
    'filters' => 'array',
    'grouping' => 'array',
    'sorting' => 'array',
    'start_date' => 'date',
    'end_date' => 'date',
    'comparison_start_date' => 'date',
    'comparison_end_date' => 'date',
    'show_percentages' => 'boolean',
    'show_variances' => 'boolean',
    'include_zero_balances' => 'boolean',
    'is_template' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the user who created the report.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the report sections.
   */
  public function sections(): HasMany
  {
    return $this->hasMany(CoreFinanceReportSectionModal::class, 'report_id');
  }

  /**
   * Get all available report types.
   */
  public static function getTypes(): array
  {
    return [
      'balance_sheet',
      'income_statement',
      'cash_flow',
      'custom'
    ];
  }

  /**
   * Get all available date range types.
   */
  public static function getDateRangeTypes(): array
  {
    return [
      'monthly',
      'quarterly',
      'yearly',
      'custom'
    ];
  }

  /**
   * Get all available comparison types.
   */
  public static function getComparisonTypes(): array
  {
    return [
      'previous_period',
      'previous_year',
      'budget',
      'none'
    ];
  }

  /**
   * Generate the report data.
   */
  public function generateData(): array
  {
    switch ($this->type) {
      case 'balance_sheet':
        return $this->generateBalanceSheet();
      case 'income_statement':
        return $this->generateIncomeStatement();
      case 'cash_flow':
        return $this->generateCashFlow();
      case 'custom':
        return $this->generateCustomReport();
      default:
        return [];
    }
  }

  /**
   * Generate balance sheet data.
   */
  protected function generateBalanceSheet(): array
  {
    $data = [
      'assets' => $this->getAssets(),
      'liabilities' => $this->getLiabilities(),
      'equity' => $this->getEquity()
    ];

    if ($this->comparison_type !== 'none') {
      $data['comparison'] = [
        'assets' => $this->getComparisonAssets(),
        'liabilities' => $this->getComparisonLiabilities(),
        'equity' => $this->getComparisonEquity()
      ];
    }

    return $data;
  }

  /**
   * Generate income statement data.
   */
  protected function generateIncomeStatement(): array
  {
    $data = [
      'revenue' => $this->getRevenue(),
      'expenses' => $this->getExpenses(),
      'other_income' => $this->getOtherIncome(),
      'other_expenses' => $this->getOtherExpenses()
    ];

    if ($this->comparison_type !== 'none') {
      $data['comparison'] = [
        'revenue' => $this->getComparisonRevenue(),
        'expenses' => $this->getComparisonExpenses(),
        'other_income' => $this->getComparisonOtherIncome(),
        'other_expenses' => $this->getComparisonOtherExpenses()
      ];
    }

    return $data;
  }

  /**
   * Generate cash flow data.
   */
  protected function generateCashFlow(): array
  {
    $data = [
      'operating' => $this->getOperatingCashFlow(),
      'investing' => $this->getInvestingCashFlow(),
      'financing' => $this->getFinancingCashFlow()
    ];

    if ($this->comparison_type !== 'none') {
      $data['comparison'] = [
        'operating' => $this->getComparisonOperatingCashFlow(),
        'investing' => $this->getComparisonInvestingCashFlow(),
        'financing' => $this->getComparisonFinancingCashFlow()
      ];
    }

    return $data;
  }

  /**
   * Generate custom report data.
   */
  protected function generateCustomReport(): array
  {
    return $this->sections->map(function ($section) {
      return [
        'name' => $section->name,
        'data' => $section->generateData($this->start_date, $this->end_date),
        'comparison' => $this->comparison_type !== 'none' ?
          $section->generateData($this->comparison_start_date, $this->comparison_end_date) :
          null
      ];
    })->toArray();
  }

  /**
   * Get a human-readable type name.
   */
  public function getTypeName(): string
  {
    return ucwords(str_replace('_', ' ', $this->type));
  }

  /**
   * Get a human-readable date range type name.
   */
  public function getDateRangeTypeName(): string
  {
    return ucwords(str_replace('_', ' ', $this->date_range_type));
  }

  /**
   * Get a human-readable comparison type name.
   */
  public function getComparisonTypeName(): string
  {
    return ucwords(str_replace('_', ' ', $this->comparison_type));
  }

  /**
   * Scope a query to only include reports of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include templates.
   */
  public function scopeTemplates($query)
  {
    return $query->where('is_template', true);
  }

  /**
   * Scope a query to only include active reports.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  // Protected methods for generating specific report sections
  protected function getAssets()
  { /* Implementation */
  }
  protected function getLiabilities()
  { /* Implementation */
  }
  protected function getEquity()
  { /* Implementation */
  }
  protected function getRevenue()
  { /* Implementation */
  }
  protected function getExpenses()
  { /* Implementation */
  }
  protected function getOtherIncome()
  { /* Implementation */
  }
  protected function getOtherExpenses()
  { /* Implementation */
  }
  protected function getOperatingCashFlow()
  { /* Implementation */
  }
  protected function getInvestingCashFlow()
  { /* Implementation */
  }
  protected function getFinancingCashFlow()
  { /* Implementation */
  }
  protected function getComparisonAssets()
  { /* Implementation */
  }
  protected function getComparisonLiabilities()
  { /* Implementation */
  }
  protected function getComparisonEquity()
  { /* Implementation */
  }
  protected function getComparisonRevenue()
  { /* Implementation */
  }
  protected function getComparisonExpenses()
  { /* Implementation */
  }
  protected function getComparisonOtherIncome()
  { /* Implementation */
  }
  protected function getComparisonOtherExpenses()
  { /* Implementation */
  }
  protected function getComparisonOperatingCashFlow()
  { /* Implementation */
  }
  protected function getComparisonInvestingCashFlow()
  { /* Implementation */
  }
  protected function getComparisonFinancingCashFlow()
  { /* Implementation */
  }
}
