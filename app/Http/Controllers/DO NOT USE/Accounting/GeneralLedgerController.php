<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralLedgerController extends Controller
{
  public function index()
  {
    return view('accounting.general-ledger');
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
}
