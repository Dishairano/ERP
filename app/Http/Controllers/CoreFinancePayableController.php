<?php

namespace App\Http\Controllers;

use App\Models\CoreFinancePayableModal;
use App\Models\CoreFinanceVendorModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinancePayableController extends Controller
{
  /**
   * Display a listing of payables.
   */
  public function index(Request $request)
  {
    $query = CoreFinancePayableModal::query()
      ->with(['vendor', 'creator', 'approver']);

    // Filter by vendor
    if ($request->has('vendor_id')) {
      $query->where('vendor_id', $request->vendor_id);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by date range
    if ($request->has('start_date') && $request->has('end_date')) {
      $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    // Filter overdue
    if ($request->boolean('overdue')) {
      $query->where('remaining_amount', '>', 0)
        ->where('due_date', '<', now()->startOfDay());
    }

    $payables = $query->orderBy('date', 'desc')
      ->orderBy('number', 'desc')
      ->paginate(10);

    $vendors = CoreFinanceVendorModal::active()->orderBy('name')->get();

    return view('core.finance.payables.index', compact('payables', 'vendors'));
  }

  /**
   * Show the form for creating a new payable.
   */
  public function create()
  {
    $vendors = CoreFinanceVendorModal::active()->orderBy('name')->get();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'liability')
      ->orderBy('code')
      ->get();

    return view('core.finance.payables.create', compact('vendors', 'accounts'));
  }

  /**
   * Store a newly created payable.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'number' => 'required|string|max:50|unique:finance_payables,number',
      'vendor_id' => 'required|exists:finance_vendors,id',
      'date' => 'required|date',
      'due_date' => 'required|date|after_or_equal:date',
      'amount' => 'required|numeric|min:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'description' => 'nullable|string',
      'reference' => 'nullable|string|max:50',
      'payment_terms' => 'nullable|string|max:255',
      'status' => 'required|string|in:draft,posted',
      'notes' => 'nullable|string',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Create payable
      $validated['created_by'] = Auth::id();
      $validated['remaining_amount'] = $validated['amount'];

      $payable = CoreFinancePayableModal::create($validated);

      // Create journal entries if posted
      if ($validated['status'] === 'posted') {
        // Debit the expense/asset account
        $payable->journalEntries()->create([
          'account_id' => $validated['account_id'],
          'type' => 'debit',
          'amount' => $validated['amount'],
          'currency' => $validated['currency'],
          'exchange_rate' => $validated['exchange_rate'],
          'date' => $validated['date'],
          'description' => $validated['description'],
          'reference' => $validated['reference'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        // Credit the accounts payable account
        $payable->journalEntries()->create([
          'account_id' => config('finance.accounts.payable'),
          'type' => 'credit',
          'amount' => $validated['amount'],
          'currency' => $validated['currency'],
          'exchange_rate' => $validated['exchange_rate'],
          'date' => $validated['date'],
          'description' => $validated['description'],
          'reference' => $validated['reference'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      }

      DB::commit();

      return redirect()
        ->route('finance.payables.show', $payable)
        ->with('success', 'Payable created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create payable. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified payable.
   */
  public function show(CoreFinancePayableModal $payable)
  {
    $payable->load(['vendor', 'creator', 'approver', 'payments', 'journalEntries.account']);

    return view('core.finance.payables.show', compact('payable'));
  }

  /**
   * Show the form for editing the specified payable.
   */
  public function edit(CoreFinancePayableModal $payable)
  {
    if ($payable->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft payables can be edited.']);
    }

    $vendors = CoreFinanceVendorModal::active()->orderBy('name')->get();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'liability')
      ->orderBy('code')
      ->get();

    return view('core.finance.payables.edit', compact('payable', 'vendors', 'accounts'));
  }

  /**
   * Update the specified payable.
   */
  public function update(Request $request, CoreFinancePayableModal $payable)
  {
    if ($payable->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft payables can be updated.']);
    }

    $validated = $request->validate([
      'number' => 'required|string|max:50|unique:finance_payables,number,' . $payable->id,
      'vendor_id' => 'required|exists:finance_vendors,id',
      'date' => 'required|date',
      'due_date' => 'required|date|after_or_equal:date',
      'amount' => 'required|numeric|min:0',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'description' => 'nullable|string',
      'reference' => 'nullable|string|max:50',
      'payment_terms' => 'nullable|string|max:255',
      'status' => 'required|string|in:draft,posted',
      'notes' => 'nullable|string',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Update payable
      $validated['remaining_amount'] = $validated['amount'] - $payable->paid_amount;
      $payable->update($validated);

      // Create journal entries if posting
      if ($validated['status'] === 'posted' && $payable->status === 'draft') {
        // Debit the expense/asset account
        $payable->journalEntries()->create([
          'account_id' => $validated['account_id'],
          'type' => 'debit',
          'amount' => $validated['amount'],
          'currency' => $validated['currency'],
          'exchange_rate' => $validated['exchange_rate'],
          'date' => $validated['date'],
          'description' => $validated['description'],
          'reference' => $validated['reference'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        // Credit the accounts payable account
        $payable->journalEntries()->create([
          'account_id' => config('finance.accounts.payable'),
          'type' => 'credit',
          'amount' => $validated['amount'],
          'currency' => $validated['currency'],
          'exchange_rate' => $validated['exchange_rate'],
          'date' => $validated['date'],
          'description' => $validated['description'],
          'reference' => $validated['reference'],
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      }

      DB::commit();

      return redirect()
        ->route('finance.payables.show', $payable)
        ->with('success', 'Payable updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update payable. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified payable.
   */
  public function destroy(CoreFinancePayableModal $payable)
  {
    if ($payable->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft payables can be deleted.']);
    }

    if ($payable->payments()->exists()) {
      return back()->withErrors(['error' => 'Cannot delete payable with payments.']);
    }

    $payable->delete();

    return redirect()
      ->route('finance.payables.index')
      ->with('success', 'Payable deleted successfully');
  }

  /**
   * Approve the specified payable.
   */
  public function approve(CoreFinancePayableModal $payable)
  {
    if ($payable->status !== 'posted') {
      return back()->withErrors(['error' => 'Only posted payables can be approved.']);
    }

    if ($payable->isApproved()) {
      return back()->withErrors(['error' => 'Payable is already approved.']);
    }

    $payable->update([
      'approved_by' => Auth::id(),
      'approved_at' => now()
    ]);

    return redirect()
      ->route('finance.payables.show', $payable)
      ->with('success', 'Payable approved successfully');
  }

  /**
   * Display the aging report.
   */
  public function aging()
  {
    $payables = CoreFinancePayableModal::with('vendor')
      ->where('remaining_amount', '>', 0)
      ->get()
      ->groupBy('vendor_id')
      ->map(function ($vendorPayables) {
        $aging = [
          'current' => 0,
          '1_30' => 0,
          '31_60' => 0,
          '61_90' => 0,
          'over_90' => 0
        ];

        foreach ($vendorPayables as $payable) {
          $daysOverdue = max(0, now()->startOfDay()->diffInDays($payable->due_date));

          if ($daysOverdue <= 0) {
            $aging['current'] += $payable->remaining_amount;
          } elseif ($daysOverdue <= 30) {
            $aging['1_30'] += $payable->remaining_amount;
          } elseif ($daysOverdue <= 60) {
            $aging['31_60'] += $payable->remaining_amount;
          } elseif ($daysOverdue <= 90) {
            $aging['61_90'] += $payable->remaining_amount;
          } else {
            $aging['over_90'] += $payable->remaining_amount;
          }
        }

        return [
          'vendor' => $vendorPayables->first()->vendor,
          'aging' => $aging,
          'total' => array_sum($aging)
        ];
      });

    return view('core.finance.payables.aging', compact('payables'));
  }
}
