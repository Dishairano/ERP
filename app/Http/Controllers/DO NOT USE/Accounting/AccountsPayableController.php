<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountsPayableController extends Controller
{
  public function index()
  {
    return view('accounting.accounts-payable');
  }

  public function store(Request $request)
  {
    // Store logic
  }

  public function show($id)
  {
    // Show logic
  }

  public function update(Request $request, $id)
  {
    // Update logic
  }

  public function destroy($id)
  {
    // Delete logic
  }

  public function recordPayment(Request $request)
  {
    // Record payment logic
  }
}
