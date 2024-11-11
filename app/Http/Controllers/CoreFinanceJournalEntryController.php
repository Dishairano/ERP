<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceJournalModal;
use App\Models\CoreFinanceJournalEntryModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceJournalEntryController extends Controller
{
  /**
   * Display a listing of journal entries.
   */
  public function index(CoreFinanceJournalModal $journal)
  {
    $entries = $journal->entries()
      ->with(['account', 'creator', 'approver'])
      ->orderBy('created_at')
      ->paginate(10);

    return view('core.finance.journals.entries.index', compact('journal', 'entries'));
  }

  /**
   * Show the form for creating a new entry.
   */
  public function create(CoreFinanceJournalModal $journal)
  {
    if ($journal->status !== 'draft') {
      return back()->withErrors(['error' => 'Cannot add entries to a non-draft journal.']);
    }

    $accounts = CoreFinanceAccountModal::active()->orderBy('code')->get();

    return view('core.finance.journals.entries.create', compact('journal', 'accounts'));
  }

  /**
   * Store a newly created entry.
   */
  public function store(Request $request, CoreFinanceJournalModal $journal)
  {
    if ($journal->status !== 'draft') {
      return back()->withErrors(['error' => 'Cannot add entries to a non-draft journal.']);
    }

    $validated = $request->validate([
      'account_id' => 'required|exists:finance_accounts,id',
      'type' => 'required|in:debit,credit',
      'amount' => 'required|numeric|min:0',
      'description' => 'nullable|string',
      'reference' => 'nullable|string|max:50'
    ]);

    try {
      DB::beginTransaction();

      // Create entry
      $entry = $journal->entries()->create([
        'account_id' => $validated['account_id'],
        'type' => $validated['type'],
        'amount' => $validated['amount'],
        'currency' => $journal->currency,
        'exchange_rate' => $journal->exchange_rate,
        'date' => $journal->date,
        'description' => $validated['description'],
        'reference' => $validated['reference'],
        'status' => $journal->status,
        'created_by' => Auth::id()
      ]);

      // Update journal totals
      $journal->calculateTotals();

      DB::commit();

      return redirect()
        ->route('finance.journals.entries.index', $journal)
        ->with('success', 'Journal entry created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create journal entry. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified entry.
   */
  public function show(CoreFinanceJournalModal $journal, CoreFinanceJournalEntryModal $entry)
  {
    $entry->load(['account', 'creator', 'approver']);

    return view('core.finance.journals.entries.show', compact('journal', 'entry'));
  }

  /**
   * Show the form for editing the specified entry.
   */
  public function edit(CoreFinanceJournalModal $journal, CoreFinanceJournalEntryModal $entry)
  {
    if ($journal->status !== 'draft') {
      return back()->withErrors(['error' => 'Cannot edit entries of a non-draft journal.']);
    }

    $accounts = CoreFinanceAccountModal::active()->orderBy('code')->get();

    return view('core.finance.journals.entries.edit', compact('journal', 'entry', 'accounts'));
  }

  /**
   * Update the specified entry.
   */
  public function update(Request $request, CoreFinanceJournalModal $journal, CoreFinanceJournalEntryModal $entry)
  {
    if ($journal->status !== 'draft') {
      return back()->withErrors(['error' => 'Cannot edit entries of a non-draft journal.']);
    }

    $validated = $request->validate([
      'account_id' => 'required|exists:finance_accounts,id',
      'type' => 'required|in:debit,credit',
      'amount' => 'required|numeric|min:0',
      'description' => 'nullable|string',
      'reference' => 'nullable|string|max:50'
    ]);

    try {
      DB::beginTransaction();

      // Update entry
      $entry->update([
        'account_id' => $validated['account_id'],
        'type' => $validated['type'],
        'amount' => $validated['amount'],
        'description' => $validated['description'],
        'reference' => $validated['reference']
      ]);

      // Update journal totals
      $journal->calculateTotals();

      DB::commit();

      return redirect()
        ->route('finance.journals.entries.index', $journal)
        ->with('success', 'Journal entry updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update journal entry. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified entry.
   */
  public function destroy(CoreFinanceJournalModal $journal, CoreFinanceJournalEntryModal $entry)
  {
    if ($journal->status !== 'draft') {
      return back()->withErrors(['error' => 'Cannot delete entries from a non-draft journal.']);
    }

    try {
      DB::beginTransaction();

      $entry->delete();

      // Update journal totals
      $journal->calculateTotals();

      DB::commit();

      return redirect()
        ->route('finance.journals.entries.index', $journal)
        ->with('success', 'Journal entry deleted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to delete journal entry. ' . $e->getMessage()]);
    }
  }

  /**
   * Bulk update entries.
   */
  public function bulkUpdate(Request $request, CoreFinanceJournalModal $journal)
  {
    if ($journal->status !== 'draft') {
      return back()->withErrors(['error' => 'Cannot update entries of a non-draft journal.']);
    }

    $validated = $request->validate([
      'entries' => 'required|array',
      'entries.*.id' => 'required|exists:finance_journal_entries,id',
      'entries.*.account_id' => 'required|exists:finance_accounts,id',
      'entries.*.type' => 'required|in:debit,credit',
      'entries.*.amount' => 'required|numeric|min:0',
      'entries.*.description' => 'nullable|string'
    ]);

    try {
      DB::beginTransaction();

      foreach ($validated['entries'] as $entryData) {
        $entry = CoreFinanceJournalEntryModal::find($entryData['id']);
        $entry->update([
          'account_id' => $entryData['account_id'],
          'type' => $entryData['type'],
          'amount' => $entryData['amount'],
          'description' => $entryData['description']
        ]);
      }

      // Update journal totals
      $journal->calculateTotals();

      DB::commit();

      return back()->with('success', 'Journal entries updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to update journal entries. ' . $e->getMessage()]);
    }
  }
}
