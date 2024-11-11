<?php

namespace App\Http\Controllers;

use App\Models\CoreFinancePayableModal;
use App\Models\CoreFinancePayablePaymentModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinancePayablePaymentController extends Controller
{
  /**
   * Display a listing of payments.
   */
  public function index(CoreFinancePayableModal $payable)
  {
    $payments = $payable->payments()
      ->with(['creator', 'approver'])
      ->orderBy('payment_date', 'desc')
      ->paginate(10);

    return view('core.finance.payables.payments.index', compact('payable', 'payments'));
  }

  /**
   * Show the form for creating a new payment.
   */
  public function create(CoreFinancePayableModal $payable)
  {
    if (!$payable->isApproved()) {
      return back()->withErrors(['error' => 'Cannot add payments to an unapproved payable.']);
    }

    if ($payable->isFullyPaid()) {
      return back()->withErrors(['error' => 'Payable is already fully paid.']);
    }

    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->where('category', 'current_asset')
      ->orderBy('code')
      ->get();

    $paymentMethods = CoreFinancePayablePaymentModal::getPaymentMethods();

    return view('core.finance.payables.payments.create', compact('payable', 'accounts', 'paymentMethods'));
  }

  /**
   * Store a newly created payment.
   */
  public function store(Request $request, CoreFinancePayableModal $payable)
  {
    if (!$payable->isApproved()) {
      return back()->withErrors(['error' => 'Cannot add payments to an unapproved payable.']);
    }

    if ($payable->isFullyPaid()) {
      return back()->withErrors(['error' => 'Payable is already fully paid.']);
    }

    $validated = $request->validate([
      'payment_date' => 'required|date',
      'amount' => 'required|numeric|min:0|max:' . $payable->remaining_amount,
      'payment_method' => 'required|string|in:' . implode(',', CoreFinancePayablePaymentModal::getPaymentMethods()),
      'reference_number' => 'nullable|string|max:50',
      'bank_account' => 'nullable|string|max:255',
      'description' => 'nullable|string',
      'status' => 'required|string|in:draft,posted',
      'notes' => 'nullable|string',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Create payment
      $validated['payable_id'] = $payable->id;
      $validated['vendor_id'] = $payable->vendor_id;
      $validated['currency'] = $payable->currency;
      $validated['exchange_rate'] = $payable->exchange_rate;
      $validated['created_by'] = Auth::id();

      $payment = CoreFinancePayablePaymentModal::create($validated);

      // Update payable amounts
      $payable->paid_amount += $payment->amount;
      $payable->remaining_amount = $payable->amount - $payable->paid_amount;
      $payable->save();

      // Create journal entries if posted
      if ($validated['status'] === 'posted') {
        // Debit the accounts payable account
        $payment->journalEntries()->create([
          'account_id' => config('finance.accounts.payable'),
          'type' => 'debit',
          'amount' => $validated['amount'],
          'currency' => $payable->currency,
          'exchange_rate' => $payable->exchange_rate,
          'date' => $validated['payment_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        // Credit the payment account
        $payment->journalEntries()->create([
          'account_id' => $validated['account_id'],
          'type' => 'credit',
          'amount' => $validated['amount'],
          'currency' => $payable->currency,
          'exchange_rate' => $payable->exchange_rate,
          'date' => $validated['payment_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      }

      DB::commit();

      return redirect()
        ->route('finance.payables.payments.show', [$payable, $payment])
        ->with('success', 'Payment created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create payment. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified payment.
   */
  public function show(CoreFinancePayableModal $payable, CoreFinancePayablePaymentModal $payment)
  {
    $payment->load(['creator', 'approver', 'journalEntries.account']);

    return view('core.finance.payables.payments.show', compact('payable', 'payment'));
  }

  /**
   * Show the form for editing the specified payment.
   */
  public function edit(CoreFinancePayableModal $payable, CoreFinancePayablePaymentModal $payment)
  {
    if ($payment->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft payments can be edited.']);
    }

    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->where('category', 'current_asset')
      ->orderBy('code')
      ->get();

    $paymentMethods = CoreFinancePayablePaymentModal::getPaymentMethods();

    return view('core.finance.payables.payments.edit', compact('payable', 'payment', 'accounts', 'paymentMethods'));
  }

  /**
   * Update the specified payment.
   */
  public function update(Request $request, CoreFinancePayableModal $payable, CoreFinancePayablePaymentModal $payment)
  {
    if ($payment->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft payments can be updated.']);
    }

    $maxAmount = $payable->remaining_amount + $payment->amount;

    $validated = $request->validate([
      'payment_date' => 'required|date',
      'amount' => 'required|numeric|min:0|max:' . $maxAmount,
      'payment_method' => 'required|string|in:' . implode(',', CoreFinancePayablePaymentModal::getPaymentMethods()),
      'reference_number' => 'nullable|string|max:50',
      'bank_account' => 'nullable|string|max:255',
      'description' => 'nullable|string',
      'status' => 'required|string|in:draft,posted',
      'notes' => 'nullable|string',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Update payable amounts
      $payable->paid_amount = $payable->paid_amount - $payment->amount + $validated['amount'];
      $payable->remaining_amount = $payable->amount - $payable->paid_amount;
      $payable->save();

      // Update payment
      $payment->update($validated);

      // Create journal entries if posting
      if ($validated['status'] === 'posted' && $payment->status === 'draft') {
        // Debit the accounts payable account
        $payment->journalEntries()->create([
          'account_id' => config('finance.accounts.payable'),
          'type' => 'debit',
          'amount' => $validated['amount'],
          'currency' => $payable->currency,
          'exchange_rate' => $payable->exchange_rate,
          'date' => $validated['payment_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        // Credit the payment account
        $payment->journalEntries()->create([
          'account_id' => $validated['account_id'],
          'type' => 'credit',
          'amount' => $validated['amount'],
          'currency' => $payable->currency,
          'exchange_rate' => $payable->exchange_rate,
          'date' => $validated['payment_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      }

      DB::commit();

      return redirect()
        ->route('finance.payables.payments.show', [$payable, $payment])
        ->with('success', 'Payment updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update payment. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified payment.
   */
  public function destroy(CoreFinancePayableModal $payable, CoreFinancePayablePaymentModal $payment)
  {
    if ($payment->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft payments can be deleted.']);
    }

    try {
      DB::beginTransaction();

      // Update payable amounts
      $payable->paid_amount -= $payment->amount;
      $payable->remaining_amount = $payable->amount - $payable->paid_amount;
      $payable->save();

      // Delete payment
      $payment->delete();

      DB::commit();

      return redirect()
        ->route('finance.payables.payments.index', $payable)
        ->with('success', 'Payment deleted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to delete payment. ' . $e->getMessage()]);
    }
  }

  /**
   * Approve the specified payment.
   */
  public function approve(CoreFinancePayableModal $payable, CoreFinancePayablePaymentModal $payment)
  {
    if ($payment->status !== 'posted') {
      return back()->withErrors(['error' => 'Only posted payments can be approved.']);
    }

    if ($payment->isApproved()) {
      return back()->withErrors(['error' => 'Payment is already approved.']);
    }

    $payment->update([
      'approved_by' => Auth::id(),
      'approved_at' => now()
    ]);

    return redirect()
      ->route('finance.payables.payments.show', [$payable, $payment])
      ->with('success', 'Payment approved successfully');
  }
}
