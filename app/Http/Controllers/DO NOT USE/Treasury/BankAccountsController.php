<?php

namespace App\Http\Controllers\Treasury;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BankAccountsController extends Controller
{
  public function index()
  {
    return view('treasury.bank-accounts');
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

  public function importTransactions(Request $request)
  {
    // Import transactions logic
  }

  public function reconcile(Request $request)
  {
    // Reconcile logic
  }
}
