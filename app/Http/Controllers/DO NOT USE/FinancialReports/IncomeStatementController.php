<?php

namespace App\Http\Controllers\FinancialReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncomeStatementController extends Controller
{
  public function index()
  {
    return view('financial-reports.income-statement');
  }

  public function export(Request $request)
  {
    // Export logic
  }
}
