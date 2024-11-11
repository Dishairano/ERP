<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceReceivableModal;
use App\Models\CoreFinanceCustomerModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceReceivableController extends Controller
{
  /**
   * Display a listing of receivables.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceReceivableModal::query()
      ->with(['customer', 'creator', 'approver']);

    // Filter by customer
    if ($request->has('customer_id')) {
      $query->where('customer_id', $request->customer_id);
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

    $receivables = $query->orderBy('date', 'desc')
      ->orderBy('number', 'desc')
      ->paginate(10);

    $customers = CoreFinanceCustomerModal::active()->orderBy('name')->get();

    return view('core.finance.receivables.index', compact('receivables', 'customers'));
  }

  /**
   * Show the form for creating a new receivable.
   */
  public function create()
  {
    $customers = CoreFinanceCustomerModal::active()->orderBy('name')->get();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->orderBy('code')
      ->get();

    return view('core.finance.receivables.create', compact('customers', 'accounts'));
  }

  /**
   * Store a newly created receivable.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'number' => 'required|string|max:50|unique:finance_receivables,number',
      'customer_id' => 'required|exists:finance_customers,id',
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

      // Create receivable
      $validated['created_by'] = Auth::id();
      $validated['remaining_amount'] = $validated['amount'];

      $receivable = CoreFinanceReceivableModal::create($validated);

      // Create journal entries if posted
      if ($validated['status'] === 'posted') {
        // Debit the accounts receivable account
        $receivable->journalEntries()->create([
          'account_id' => config('finance.accounts.receivable'),
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

        // Credit the revenue account
        $receivable->journalEntries()->create([
          'account_id' => $validated['account_id'],
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
        ->route('finance.receivables.show', $receivable)
        ->with('success', 'Receivable created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create receivable. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified receivable.
   */
  public function show(CoreFinanceReceivableModal $receivable)
  {
    $receivable->load(['customer', 'creator', 'approver', 'payments', 'journalEntries.account']);

    return view('core.finance.receivables.show', compact('receivable'));
  }

  /**
   * Show the form for editing the specified receivable.
   */
  public function edit(CoreFinanceReceivableModal $receivable)
  {
    if ($receivable->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft receivables can be edited.']);
    }

    $customers = CoreFinanceCustomerModal::active()->orderBy('name')->get();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->orderBy('code')
      ->get();

    return view('core.finance.receivables.edit', compact('receivable', 'customers', 'accounts'));
  }

  /**
   * Update the specified receivable.
   */
  public function update(Request $request, CoreFinanceReceivableModal $receivable)
  {
    if ($receivable->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft receivables can be updated.']);
    }

    $validated = $request->validate([
      'number' => 'required|string|max:50|unique:finance_receivables,number,' . $receivable->id,
      'customer_id' => 'required|exists:finance_customers,id',
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

      // Update receivable
      $validated['remaining_amount'] = $validated['amount'] - $receivable->paid_amount;
      $receivable->update($validated);

      // Create journal entries if posting
      if ($validated['status'] === 'posted' && $receivable->status === 'draft') {
        // Debit the accounts receivable account
        $receivable->journalEntries()->create([
          'account_id' => config('finance.accounts.receivable'),
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

        // Credit the revenue account
        $receivable->journalEntries()->create([
          'account_id' => $validated['account_id'],
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
        ->route('finance.receivables.show', $receivable)
        ->with('success', 'Receivable updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update receivable. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified receivable.
   */
  public function destroy(CoreFinanceReceivableModal $receivable)
  {
    if ($receivable->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft receivables can be deleted.']);
    }

    if ($receivable->payments()->exists()) {
      return back()->withErrors(['error' => 'Cannot delete receivable with payments.']);
    }

    $receivable->delete();

    return redirect()
      ->route('finance.receivables.index')
      ->with('success', 'Receivable deleted successfully');
  }

  /**
   * Approve the specified receivable.
   */
  public function approve(CoreFinanceReceivableModal $receivable)
  {
    if ($receivable->status !== 'posted') {
      return back()->withErrors(['error' => 'Only posted receivables can be approved.']);
    }

    if ($receivable->isApproved()) {
      return back()->withErrors(['error' => 'Receivable is already approved.']);
    }

    $receivable->update([
      'approved_by' => Auth::id(),
      'approved_at' => now()
    ]);

    return redirect()
      ->route('finance.receivables.show', $receivable)
      ->with('success', 'Receivable approved successfully');
  }

  /**
   * Display the aging report.
   */
  public function aging()
  {
    $receivables = CoreFinanceReceivableModal::with('customer')
      ->where('remaining_amount', '>', 0)
      ->get()
      ->groupBy('customer_id')
      ->map(function ($customerReceivables) {
        $aging = [
          'current' => 0,
          '1_30' => 0,
          '31_60' => 0,
          '61_90' => 0,
          'over_90' => 0
        ];

        foreach ($customerReceivables as $receivable) {
          $daysOverdue = max(0, now()->startOfDay()->diffInDays($receivable->due_date));

          if ($daysOverdue <= 0) {
            $aging['current'] += $receivable->remaining_amount;
          } elseif ($daysOverdue <= 30) {
            $aging['1_30'] += $receivable->remaining_amount;
          } elseif ($daysOverdue <= 60) {
            $aging['31_60'] += $receivable->remaining_amount;
          } elseif ($daysOverdue <= 90) {
            $aging['61_90'] += $receivable->remaining_amount;
          } else {
            $aging['over_90'] += $receivable->remaining_amount;
          }
        }

        return [
          'customer' => $customerReceivables->first()->customer,
          'aging' => $aging,
          'total' => array_sum($aging)
        ];
      });

    return view('core.finance.receivables.aging', compact('receivables'));
  }
}
