<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceJournalModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CoreFinanceJournalController extends Controller
{
  /**
   * Display a listing of journals.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceJournalModal::query()
      ->with(['creator', 'approver', 'entries.account']);

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by date range
    if ($request->has('start_date') && $request->has('end_date')) {
      $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    $journals = $query->orderBy('date', 'desc')
      ->orderBy('number', 'desc')
      ->paginate(10);

    $types = CoreFinanceJournalModal::getTypes();

    return view('core.finance.journals.index', compact('journals', 'types'));
  }

  /**
   * Show the form for creating a new journal.
   */
  public function create()
  {
    $types = CoreFinanceJournalModal::getTypes();
    $accounts = CoreFinanceAccountModal::active()->orderBy('code')->get();

    return view('core.finance.journals.create', compact('types', 'accounts'));
  }

  /**
   * Store a newly created journal.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'number' => 'required|string|max:50|unique:finance_journals,number',
      'date' => 'required|date',
      'type' => 'required|string|in:' . implode(',', CoreFinanceJournalModal::getTypes()),
      'description' => 'nullable|string',
      'reference' => 'nullable|string|max:50',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'status' => 'required|string|in:draft,posted',
      'notes' => 'nullable|string',
      'entries' => 'required|array|min:2',
      'entries.*.account_id' => 'required|exists:finance_accounts,id',
      'entries.*.type' => 'required|in:debit,credit',
      'entries.*.amount' => 'required|numeric|min:0',
      'entries.*.description' => 'nullable|string'
    ]);

    // Calculate totals
    $totalDebit = collect($validated['entries'])
      ->where('type', 'debit')
      ->sum('amount');

    $totalCredit = collect($validated['entries'])
      ->where('type', 'credit')
      ->sum('amount');

    // Check if journal is balanced
    if ($totalDebit !== $totalCredit) {
      return back()
        ->withInput()
        ->withErrors(['entries' => 'Journal entries must be balanced (total debits must equal total credits).']);
    }

    try {
      DB::beginTransaction();

      // Create journal
      $journal = CoreFinanceJournalModal::create([
        'number' => $validated['number'],
        'date' => $validated['date'],
        'type' => $validated['type'],
        'description' => $validated['description'],
        'reference' => $validated['reference'],
        'currency' => $validated['currency'],
        'exchange_rate' => $validated['exchange_rate'],
        'total_debit' => $totalDebit,
        'total_credit' => $totalCredit,
        'status' => $validated['status'],
        'notes' => $validated['notes'],
        'created_by' => Auth::id()
      ]);

      // Create entries
      foreach ($validated['entries'] as $entry) {
        $journal->entries()->create([
          'account_id' => $entry['account_id'],
          'type' => $entry['type'],
          'amount' => $entry['amount'],
          'currency' => $validated['currency'],
          'exchange_rate' => $validated['exchange_rate'],
          'date' => $validated['date'],
          'description' => $entry['description'] ?? null,
          'status' => $validated['status'],
          'created_by' => Auth::id()
        ]);
      }

      // Update account balances if journal is posted
      if ($validated['status'] === 'posted') {
        foreach ($journal->entries as $entry) {
          $entry->account->calculateBalance();
        }
      }

      DB::commit();

      return redirect()
        ->route('finance.journals.show', $journal)
        ->with('success', 'Journal created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create journal. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified journal.
   */
  public function show(CoreFinanceJournalModal $journal)
  {
    $journal->load(['creator', 'approver', 'entries.account']);

    return view('core.finance.journals.show', compact('journal'));
  }

  /**
   * Show the form for editing the specified journal.
   */
  public function edit(CoreFinanceJournalModal $journal)
  {
    if ($journal->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft journals can be edited.']);
    }

    $types = CoreFinanceJournalModal::getTypes();
    $accounts = CoreFinanceAccountModal::active()->orderBy('code')->get();

    return view('core.finance.journals.edit', compact('journal', 'types', 'accounts'));
  }

  /**
   * Update the specified journal.
   */
  public function update(Request $request, CoreFinanceJournalModal $journal)
  {
    if ($journal->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft journals can be updated.']);
    }

    $validated = $request->validate([
      'number' => 'required|string|max:50|unique:finance_journals,number,' . $journal->id,
      'date' => 'required|date',
      'type' => 'required|string|in:' . implode(',', CoreFinanceJournalModal::getTypes()),
      'description' => 'nullable|string',
      'reference' => 'nullable|string|max:50',
      'currency' => 'required|string|size:3',
      'exchange_rate' => 'required|numeric|min:0',
      'status' => 'required|string|in:draft,posted',
      'notes' => 'nullable|string',
      'entries' => 'required|array|min:2',
      'entries.*.id' => 'nullable|exists:finance_journal_entries,id',
      'entries.*.account_id' => 'required|exists:finance_accounts,id',
      'entries.*.type' => 'required|in:debit,credit',
      'entries.*.amount' => 'required|numeric|min:0',
      'entries.*.description' => 'nullable|string'
    ]);

    // Calculate totals
    $totalDebit = collect($validated['entries'])
      ->where('type', 'debit')
      ->sum('amount');

    $totalCredit = collect($validated['entries'])
      ->where('type', 'credit')
      ->sum('amount');

    // Check if journal is balanced
    if ($totalDebit !== $totalCredit) {
      return back()
        ->withInput()
        ->withErrors(['entries' => 'Journal entries must be balanced (total debits must equal total credits).']);
    }

    try {
      DB::beginTransaction();

      // Update journal
      $journal->update([
        'number' => $validated['number'],
        'date' => $validated['date'],
        'type' => $validated['type'],
        'description' => $validated['description'],
        'reference' => $validated['reference'],
        'currency' => $validated['currency'],
        'exchange_rate' => $validated['exchange_rate'],
        'total_debit' => $totalDebit,
        'total_credit' => $totalCredit,
        'status' => $validated['status'],
        'notes' => $validated['notes']
      ]);

      // Delete removed entries
      $keepEntryIds = collect($validated['entries'])
        ->pluck('id')
        ->filter()
        ->toArray();

      $journal->entries()
        ->whereNotIn('id', $keepEntryIds)
        ->delete();

      // Update or create entries
      foreach ($validated['entries'] as $entry) {
        $journal->entries()->updateOrCreate(
          ['id' => $entry['id'] ?? null],
          [
            'account_id' => $entry['account_id'],
            'type' => $entry['type'],
            'amount' => $entry['amount'],
            'currency' => $validated['currency'],
            'exchange_rate' => $validated['exchange_rate'],
            'date' => $validated['date'],
            'description' => $entry['description'] ?? null,
            'status' => $validated['status'],
            'created_by' => Auth::id()
          ]
        );
      }

      // Update account balances if journal is posted
      if ($validated['status'] === 'posted') {
        foreach ($journal->entries as $entry) {
          $entry->account->calculateBalance();
        }
      }

      DB::commit();

      return redirect()
        ->route('finance.journals.show', $journal)
        ->with('success', 'Journal updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update journal. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified journal.
   */
  public function destroy(CoreFinanceJournalModal $journal)
  {
    if ($journal->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft journals can be deleted.']);
    }

    $journal->delete();

    return redirect()
      ->route('finance.journals.index')
      ->with('success', 'Journal deleted successfully');
  }

  /**
   * Post the specified journal.
   */
  public function post(CoreFinanceJournalModal $journal)
  {
    if ($journal->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft journals can be posted.']);
    }

    try {
      DB::beginTransaction();

      $journal->update([
        'status' => 'posted'
      ]);

      $journal->entries()->update([
        'status' => 'posted'
      ]);

      // Update account balances
      foreach ($journal->entries as $entry) {
        $entry->account->calculateBalance();
      }

      DB::commit();

      return redirect()
        ->route('finance.journals.show', $journal)
        ->with('success', 'Journal posted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to post journal. ' . $e->getMessage()]);
    }
  }

  /**
   * Approve the specified journal.
   */
  public function approve(CoreFinanceJournalModal $journal)
  {
    if ($journal->status !== 'posted') {
      return back()->withErrors(['error' => 'Only posted journals can be approved.']);
    }

    if ($journal->isApproved()) {
      return back()->withErrors(['error' => 'Journal is already approved.']);
    }

    try {
      DB::beginTransaction();

      $now = now();

      $journal->update([
        'approved_by' => Auth::id(),
        'approved_at' => $now
      ]);

      $journal->entries()->update([
        'approved_by' => Auth::id(),
        'approved_at' => $now
      ]);

      DB::commit();

      return redirect()
        ->route('finance.journals.show', $journal)
        ->with('success', 'Journal approved successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to approve journal. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the general ledger report.
   */
  public function generalLedger(Request $request)
  {
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now()->endOfMonth());
    $accountId = $request->get('account_id');

    $query = CoreFinanceJournalModal::with(['entries.account'])
      ->whereBetween('date', [$startDate, $endDate])
      ->where('status', 'posted');

    if ($accountId) {
      $query->whereHas('entries', function ($q) use ($accountId) {
        $q->where('account_id', $accountId);
      });
    }

    $journals = $query->orderBy('date')
      ->orderBy('number')
      ->paginate(20);

    $accounts = CoreFinanceAccountModal::active()->orderBy('code')->get();

    return view('core.finance.journals.general-ledger', compact('journals', 'accounts', 'startDate', 'endDate'));
  }
}
