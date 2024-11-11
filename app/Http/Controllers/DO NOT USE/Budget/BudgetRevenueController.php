<?php

namespace App\Http\Controllers\Budget;

use App\Models\RevenueStream;
use Illuminate\Http\Request;

class BudgetRevenueController extends BaseBudgetController
{
  public function index()
  {
    $streams = RevenueStream::with(['budgets' => function ($query) {
      $query->where('is_active', true);
    }])->get();
    return view('budgeting.revenue-streams.index', compact('streams'));
  }

  public function show(RevenueStream $stream)
  {
    $budgets = $this->getActiveBudgets()
      ->where('revenue_stream_id', $stream->id)
      ->get();
    return view('budgeting.revenue-streams.show', compact('stream', 'budgets'));
  }
}
