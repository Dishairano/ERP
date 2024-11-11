<?php

namespace App\Http\Controllers\FinancialReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
  public function index()
  {
    return view('financial-reports.balance-sheet');
  }

  public function export(Request $request)
  {
    // Export logic
  }
}
