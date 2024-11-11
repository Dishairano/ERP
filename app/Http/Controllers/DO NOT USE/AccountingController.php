<?php

namespace App\Http\Controllers;

use App\Models\GeneralLedger;
use App\Models\AccountsPayable;
use App\Models\AccountsReceivable;
use App\Models\FixedAsset;
use App\Models\TaxRecord;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
  /**
   * Display general ledger.
   *
   * @return \Illuminate\View\View
   */
  public function generalLedger()
  {
    $entries = GeneralLedger::with(['account', 'journal'])
      ->latest()
      ->paginate(10);

    return view('accounting.general-ledger', compact('entries'));
  }

  /**
   * Display accounts payable.
   *
   * @return \Illuminate\View\View
   */
  public function accountsPayable()
  {
    $payables = AccountsPayable::with(['supplier', 'invoice'])
      ->latest()
      ->paginate(10);

    return view('accounting.accounts-payable', compact('payables'));
  }

  /**
   * Display accounts receivable.
   *
   * @return \Illuminate\View\View
   */
  public function accountsReceivable()
  {
    $receivables = AccountsReceivable::with(['customer', 'invoice'])
      ->latest()
      ->paginate(10);

    return view('accounting.accounts-receivable', compact('receivables'));
  }

  /**
   * Display fixed assets.
   *
   * @return \Illuminate\View\View
   */
  public function fixedAssets()
  {
    $assets = FixedAsset::with(['category', 'location'])
      ->latest()
      ->paginate(10);

    return view('accounting.fixed-assets', compact('assets'));
  }

  /**
   * Display tax management.
   *
   * @return \Illuminate\View\View
   */
  public function taxManagement()
  {
    $taxRecords = TaxRecord::with(['type', 'period'])
      ->latest()
      ->paginate(10);

    return view('accounting.tax-management', compact('taxRecords'));
  }

  /**
   * Store a new general ledger entry.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeGeneralLedger(Request $request)
  {
    $validated = $request->validate([
      'account_id' => 'required|exists:accounts,id',
      'journal_id' => 'required|exists:journals,id',
      'date' => 'required|date',
      'description' => 'required|string',
      'debit' => 'required|numeric|min:0',
      'credit' => 'required|numeric|min:0',
      'reference' => 'required|string',
      'notes' => 'nullable|string'
    ]);

    GeneralLedger::create([
      ...$validated,
      'created_by' => auth()->id()
    ]);

    return redirect()->route('accounting.general-ledger')
      ->with('success', 'General ledger entry created successfully.');
  }

  /**
   * Store a new accounts payable entry.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeAccountsPayable(Request $request)
  {
    $validated = $request->validate([
      'supplier_id' => 'required|exists:suppliers,id',
      'invoice_id' => 'required|exists:invoices,id',
      'amount' => 'required|numeric|min:0',
      'due_date' => 'required|date',
      'payment_terms' => 'required|string',
      'status' => 'required|in:pending,paid,overdue',
      'notes' => 'nullable|string'
    ]);

    AccountsPayable::create([
      ...$validated,
      'created_by' => auth()->id()
    ]);

    return redirect()->route('accounting.accounts-payable')
      ->with('success', 'Accounts payable entry created successfully.');
  }

  /**
   * Store a new accounts receivable entry.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeAccountsReceivable(Request $request)
  {
    $validated = $request->validate([
      'customer_id' => 'required|exists:customers,id',
      'invoice_id' => 'required|exists:invoices,id',
      'amount' => 'required|numeric|min:0',
      'due_date' => 'required|date',
      'payment_terms' => 'required|string',
      'status' => 'required|in:pending,paid,overdue',
      'notes' => 'nullable|string'
    ]);

    AccountsReceivable::create([
      ...$validated,
      'created_by' => auth()->id()
    ]);

    return redirect()->route('accounting.accounts-receivable')
      ->with('success', 'Accounts receivable entry created successfully.');
  }

  /**
   * Store a new fixed asset.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeFixedAsset(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'category_id' => 'required|exists:asset_categories,id',
      'location_id' => 'required|exists:locations,id',
      'purchase_date' => 'required|date',
      'purchase_cost' => 'required|numeric|min:0',
      'useful_life' => 'required|integer|min:1',
      'depreciation_method' => 'required|string',
      'salvage_value' => 'required|numeric|min:0',
      'notes' => 'nullable|string'
    ]);

    FixedAsset::create([
      ...$validated,
      'status' => 'active',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('accounting.fixed-assets')
      ->with('success', 'Fixed asset created successfully.');
  }

  /**
   * Store a new tax record.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeTaxRecord(Request $request)
  {
    $validated = $request->validate([
      'type_id' => 'required|exists:tax_types,id',
      'period_id' => 'required|exists:tax_periods,id',
      'amount' => 'required|numeric|min:0',
      'due_date' => 'required|date',
      'filing_date' => 'nullable|date',
      'status' => 'required|in:pending,filed,paid',
      'notes' => 'nullable|string'
    ]);

    TaxRecord::create([
      ...$validated,
      'created_by' => auth()->id()
    ]);

    return redirect()->route('accounting.tax-management')
      ->with('success', 'Tax record created successfully.');
  }
}
