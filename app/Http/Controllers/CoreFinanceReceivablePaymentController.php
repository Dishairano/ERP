<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceReceivableModal;
use App\Models\CoreFinanceReceivablePaymentModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceReceivablePaymentController extends Controller
{
  /**
   * Display a listing of payments.
   */
  public function index(CoreFinanceReceivableModal $receivable)
  {
    $payments = $receivable->payments()
      ->with(['creator', 'approver'])
      ->orderBy('payment_date', 'desc')
      ->paginate(10);

    return view('core.finance.receivables.payments.index', compact('receivable', 'payments'));
  }

  /**
   * Show the form for creating a new payment.
   */
  public function create(CoreFinanceReceivableModal $receivable)
  {
    if (!$receivable->isApproved()) {
      return back()->withErrors(['error' => 'Cannot add payments to an unapproved receivable.']);
    }

    if ($receivable->isFullyPaid()) {
      return back()->withErrors(['error' => 'Receivable is already fully paid.']);
    }

    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->where('category', 'current_asset')
      ->orderBy('code')
      ->get();

    $paymentMethods = CoreFinanceReceivablePaymentModal::getPaymentMethods();

    return view('core.finance.receivables.payments.create', compact('receivable', 'accounts', 'paymentMethods'));
  }

  /**
   * Store a newly created payment.
   */
  public function store(Request $request, CoreFinanceReceivableModal $receivable)
  {
    if (!$receivable->isApproved()) {
      return back()->withErrors(['error' => 'Cannot add payments to an unapproved receivable.']);
    }

    if ($receivable->isFullyPaid()) {
      return back()->withErrors(['error' => 'Receivable is already fully paid.']);
    }

    $validated = $request->validate([
      'payment_date' => 'required|date',
      'amount' => 'required|numeric|min:0|max:' . $receivable->remaining_amount,
      'payment_method' => 'required|string|in:' . implode(',', CoreFinanceReceivablePaymentModal::getPaymentMethods()),
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
      $validated['receivable_id'] = $receivable->id;
      $validated['customer_id'] = $receivable->customer_id;
      $validated['currency'] = $receivable->currency;
      $validated['exchange_rate'] = $receivable->exchange_rate;
      $validated['created_by'] = Auth::id();

      $payment = CoreFinanceReceivablePaymentModal::create($validated);

      // Update receivable amounts
      $receivable->paid_amount += $payment->amount;
      $receivable->remaining_amount = $receivable->amount - $receivable->paid_amount;
      $receivable->save();

      // Create journal entries if posted
      if ($validated['status'] === 'posted') {
        // Debit the payment account
        $payment->journalEntries()->create([
          'account_id' => $validated['account_id'],
          'type' => 'debit',
          'amount' => $validated['amount'],
          'currency' => $receivable->currency,
          'exchange_rate' => $receivable->exchange_rate,
          'date' => $validated['payment_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        // Credit the accounts receivable account
        $payment->journalEntries()->create([
          'account_id' => config('finance.accounts.receivable'),
          'type' => 'credit',
          'amount' => $validated['amount'],
          'currency' => $receivable->currency,
          'exchange_rate' => $receivable->exchange_rate,
          'date' => $validated['payment_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      }

      DB::commit();

      return redirect()
        ->route('finance.receivables.payments.show', [$receivable, $payment])
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
  public function show(CoreFinanceReceivableModal $receivable, CoreFinanceReceivablePaymentModal $payment)
  {
    $payment->load(['creator', 'approver', 'journalEntries.account']);

    return view('core.finance.receivables.payments.show', compact('receivable', 'payment'));
  }

  /**
   * Show the form for editing the specified payment.
   */
  public function edit(CoreFinanceReceivableModal $receivable, CoreFinanceReceivablePaymentModal $payment)
  {
    if ($payment->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft payments can be edited.']);
    }

    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->where('category', 'current_asset')
      ->orderBy('code')
      ->get();

    $paymentMethods = CoreFinanceReceivablePaymentModal::getPaymentMethods();

    return view('core.finance.receivables.payments.edit', compact('receivable', 'payment', 'accounts', 'paymentMethods'));
  }

  /**
   * Update the specified payment.
   */
  public function update(Request $request, CoreFinanceReceivableModal $receivable, CoreFinanceReceivablePaymentModal $payment)
  {
    if ($payment->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft payments can be updated.']);
    }

    $maxAmount = $receivable->remaining_amount + $payment->amount;

    $validated = $request->validate([
      'payment_date' => 'required|date',
      'amount' => 'required|numeric|min:0|max:' . $maxAmount,
      'payment_method' => 'required|string|in:' . implode(',', CoreFinanceReceivablePaymentModal::getPaymentMethods()),
      'reference_number' => 'nullable|string|max:50',
      'bank_account' => 'nullable|string|max:255',
      'description' => 'nullable|string',
      'status' => 'required|string|in:draft,posted',
      'notes' => 'nullable|string',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Update receivable amounts
      $receivable->paid_amount = $receivable->paid_amount - $payment->amount + $validated['amount'];
      $receivable->remaining_amount = $receivable->amount - $receivable->paid_amount;
      $receivable->save();

      // Update payment
      $payment->update($validated);

      // Create journal entries if posting
      if ($validated['status'] === 'posted' && $payment->status === 'draft') {
        // Debit the payment account
        $payment->journalEntries()->create([
          'account_id' => $validated['account_id'],
          'type' => 'debit',
          'amount' => $validated['amount'],
          'currency' => $receivable->currency,
          'exchange_rate' => $receivable->exchange_rate,
          'date' => $validated['payment_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        // Credit the accounts receivable account
        $payment->journalEntries()->create([
          'account_id' => config('finance.accounts.receivable'),
          'type' => 'credit',
          'amount' => $validated['amount'],
          'currency' => $receivable->currency,
          'exchange_rate' => $receivable->exchange_rate,
          'date' => $validated['payment_date'],
          'description' => $validated['description'],
          'reference' => $validated['reference_number'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      }

      DB::commit();

      return redirect()
        ->route('finance.receivables.payments.show', [$receivable, $payment])
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
  public function destroy(CoreFinanceReceivableModal $receivable, CoreFinanceReceivablePaymentModal $payment)
  {
    if ($payment->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft payments can be deleted.']);
    }

    try {
      DB::beginTransaction();

      // Update receivable amounts
      $receivable->paid_amount -= $payment->amount;
      $receivable->remaining_amount = $receivable->amount - $receivable->paid_amount;
      $receivable->save();

      // Delete payment
      $payment->delete();

      DB::commit();

      return redirect()
        ->route('finance.receivables.payments.index', $receivable)
        ->with('success', 'Payment deleted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to delete payment. ' . $e->getMessage()]);
    }
  }

  /**
   * Approve the specified payment.
   */
  public function approve(CoreFinanceReceivableModal $receivable, CoreFinanceReceivablePaymentModal $payment)
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
      ->route('finance.receivables.payments.show', [$receivable, $payment])
      ->with('success', 'Payment approved successfully');
  }
}
