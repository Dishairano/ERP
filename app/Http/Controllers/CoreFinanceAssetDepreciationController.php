<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceAssetModal;
use App\Models\CoreFinanceAssetDepreciationModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceAssetDepreciationController extends Controller
{
  /**
   * Display a listing of depreciation entries.
   */
  public function index(CoreFinanceAssetModal $asset)
  {
    $entries = $asset->depreciationEntries()
      ->with(['creator'])
      ->orderBy('date', 'desc')
      ->paginate(10);

    return view('core.finance.assets.depreciations.index', compact('asset', 'entries'));
  }

  /**
   * Show the form for creating a new depreciation entry.
   */
  public function create(CoreFinanceAssetModal $asset)
  {
    if ($asset->status !== 'active') {
      return back()->withErrors(['error' => 'Cannot add depreciation entries to inactive assets.']);
    }

    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'expense')
      ->orderBy('code')
      ->get();

    return view('core.finance.assets.depreciations.create', compact('asset', 'accounts'));
  }

  /**
   * Store a newly created depreciation entry.
   */
  public function store(Request $request, CoreFinanceAssetModal $asset)
  {
    if ($asset->status !== 'active') {
      return back()->withErrors(['error' => 'Cannot add depreciation entries to inactive assets.']);
    }

    $validated = $request->validate([
      'date' => 'required|date',
      'amount' => 'required|numeric|min:0',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Create depreciation entry
      $entry = $asset->depreciationEntries()->create([
        'date' => $validated['date'],
        'amount' => $validated['amount'],
        'created_by' => Auth::id()
      ]);

      // Update asset values
      $asset->accumulated_depreciation += $validated['amount'];
      $asset->current_value = $asset->purchase_cost - $asset->accumulated_depreciation;
      $asset->last_depreciation_date = $validated['date'];
      $asset->save();

      // Create journal entries
      $entry->journalEntries()->create([
        'account_id' => $validated['account_id'],
        'type' => 'debit',
        'amount' => $validated['amount'],
        'date' => $validated['date'],
        'description' => 'Asset depreciation: ' . $asset->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      $entry->journalEntries()->create([
        'account_id' => config('finance.accounts.accumulated_depreciation'),
        'type' => 'credit',
        'amount' => $validated['amount'],
        'date' => $validated['date'],
        'description' => 'Asset depreciation: ' . $asset->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      DB::commit();

      return redirect()
        ->route('finance.assets.depreciations.show', [$asset, $entry])
        ->with('success', 'Depreciation entry created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create depreciation entry. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified depreciation entry.
   */
  public function show(CoreFinanceAssetModal $asset, CoreFinanceAssetDepreciationModal $depreciation)
  {
    $depreciation->load(['creator', 'journalEntries.account']);

    return view('core.finance.assets.depreciations.show', compact('asset', 'depreciation'));
  }

  /**
   * Show the form for editing the specified depreciation entry.
   */
  public function edit(CoreFinanceAssetModal $asset, CoreFinanceAssetDepreciationModal $depreciation)
  {
    if ($asset->status !== 'active') {
      return back()->withErrors(['error' => 'Cannot edit depreciation entries of inactive assets.']);
    }

    if ($depreciation->date != $asset->last_depreciation_date) {
      return back()->withErrors(['error' => 'Only the latest depreciation entry can be edited.']);
    }

    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'expense')
      ->orderBy('code')
      ->get();

    return view('core.finance.assets.depreciations.edit', compact('asset', 'depreciation', 'accounts'));
  }

  /**
   * Update the specified depreciation entry.
   */
  public function update(Request $request, CoreFinanceAssetModal $asset, CoreFinanceAssetDepreciationModal $depreciation)
  {
    if ($asset->status !== 'active') {
      return back()->withErrors(['error' => 'Cannot edit depreciation entries of inactive assets.']);
    }

    if ($depreciation->date != $asset->last_depreciation_date) {
      return back()->withErrors(['error' => 'Only the latest depreciation entry can be edited.']);
    }

    $validated = $request->validate([
      'date' => 'required|date',
      'amount' => 'required|numeric|min:0',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Update asset values
      $asset->accumulated_depreciation = $asset->accumulated_depreciation - $depreciation->amount + $validated['amount'];
      $asset->current_value = $asset->purchase_cost - $asset->accumulated_depreciation;
      $asset->last_depreciation_date = $validated['date'];
      $asset->save();

      // Update depreciation entry
      $depreciation->update([
        'date' => $validated['date'],
        'amount' => $validated['amount']
      ]);

      // Update journal entries
      $depreciation->journalEntries()->delete();

      $depreciation->journalEntries()->create([
        'account_id' => $validated['account_id'],
        'type' => 'debit',
        'amount' => $validated['amount'],
        'date' => $validated['date'],
        'description' => 'Asset depreciation: ' . $asset->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      $depreciation->journalEntries()->create([
        'account_id' => config('finance.accounts.accumulated_depreciation'),
        'type' => 'credit',
        'amount' => $validated['amount'],
        'date' => $validated['date'],
        'description' => 'Asset depreciation: ' . $asset->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      DB::commit();

      return redirect()
        ->route('finance.assets.depreciations.show', [$asset, $depreciation])
        ->with('success', 'Depreciation entry updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to update depreciation entry. ' . $e->getMessage()]);
    }
  }

  /**
   * Remove the specified depreciation entry.
   */
  public function destroy(CoreFinanceAssetModal $asset, CoreFinanceAssetDepreciationModal $depreciation)
  {
    if ($asset->status !== 'active') {
      return back()->withErrors(['error' => 'Cannot delete depreciation entries of inactive assets.']);
    }

    if ($depreciation->date != $asset->last_depreciation_date) {
      return back()->withErrors(['error' => 'Only the latest depreciation entry can be deleted.']);
    }

    try {
      DB::beginTransaction();

      // Update asset values
      $asset->accumulated_depreciation -= $depreciation->amount;
      $asset->current_value = $asset->purchase_cost - $asset->accumulated_depreciation;
      $asset->last_depreciation_date = $asset->depreciationEntries()
        ->where('id', '!=', $depreciation->id)
        ->latest('date')
        ->value('date');
      $asset->save();

      // Delete depreciation entry and its journal entries
      $depreciation->journalEntries()->delete();
      $depreciation->delete();

      DB::commit();

      return redirect()
        ->route('finance.assets.depreciations.index', $asset)
        ->with('success', 'Depreciation entry deleted successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to delete depreciation entry. ' . $e->getMessage()]);
    }
  }
}
