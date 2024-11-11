<?php

namespace App\Http\Controllers\FinancialReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
  public function index()
  {
    return view('financial-reports.cash-flow');
  }

  public function export(Request $request)
  {
    // Export logic
  }
}
