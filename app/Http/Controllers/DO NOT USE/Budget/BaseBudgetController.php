<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use App\Models\Budget;

class BaseBudgetController extends Controller
{
  protected function getActiveBudgets()
  {
    return Budget::with(['department', 'project', 'costCategory'])
      ->where('is_active', true)
      ->latest();
  }

  protected function formatAmount($amount)
  {
    return number_format($amount, 2);
  }

  protected function calculateSpentPercentage($planned, $actual)
  {
    if (!$planned) {
      return 0;
    }
    return round(($actual / $planned) * 100, 2);
  }
}
