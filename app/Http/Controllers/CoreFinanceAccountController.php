<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;

class CoreFinanceAccountController extends Controller
{
  /**
   * Display a listing of accounts.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceAccountModal::query()
      ->with(['parent', 'children']);

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }

    // Filter by category
    if ($request->has('category')) {
      $query->where('category', $request->category);
    }

    // Filter by active status
    if ($request->has('is_active')) {
      $query->where('is_active', $request->boolean('is_active'));
    }

    // Filter by parent
    if ($request->has('parent_id')) {
      $query->where('parent_id', $request->parent_id);
    }

    $accounts = $query->orderBy('code')
      ->paginate(10);

    $types = CoreFinanceAccountModal::getTypes();
    $categories = CoreFinanceAccountModal::getCategories();

    return view('core.finance.accounts.index', compact('accounts', 'types', 'categories'));
  }

  /**
   * Show the form for creating a new account.
   */
  public function create()
  {
    $types = CoreFinanceAccountModal::getTypes();
    $categories = CoreFinanceAccountModal::getCategories();
    $parents = CoreFinanceAccountModal::active()->orderBy('code')->get();

    return view('core.finance.accounts.create', compact('types', 'categories', 'parents'));
  }

  /**
   * Store a newly created account.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_accounts,code',
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceAccountModal::getTypes()),
      'category' => 'required|string|in:' . implode(',', CoreFinanceAccountModal::getCategories()),
      'parent_id' => 'nullable|exists:finance_accounts,id',
      'description' => 'nullable|string',
      'is_active' => 'boolean',
      'opening_balance' => 'required|numeric',
      'currency' => 'required|string|size:3',
      'tax_rate' => 'nullable|numeric|between:0,100',
      'notes' => 'nullable|string'
    ]);

    $account = CoreFinanceAccountModal::create($validated);

    // Set initial balance
    $account->balance = $account->opening_balance;
    $account->save();

    return redirect()
      ->route('finance.accounts.show', $account)
      ->with('success', 'Account created successfully');
  }

  /**
   * Display the specified account.
   */
  public function show(CoreFinanceAccountModal $account)
  {
    $account->load(['parent', 'children', 'journalEntries' => function ($query) {
      $query->latest()->limit(10);
    }]);

    return view('core.finance.accounts.show', compact('account'));
  }

  /**
   * Show the form for editing the specified account.
   */
  public function edit(CoreFinanceAccountModal $account)
  {
    $types = CoreFinanceAccountModal::getTypes();
    $categories = CoreFinanceAccountModal::getCategories();
    $parents = CoreFinanceAccountModal::where('id', '!=', $account->id)
      ->active()
      ->orderBy('code')
      ->get();

    return view('core.finance.accounts.edit', compact('account', 'types', 'categories', 'parents'));
  }

  /**
   * Update the specified account.
   */
  public function update(Request $request, CoreFinanceAccountModal $account)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_accounts,code,' . $account->id,
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', CoreFinanceAccountModal::getTypes()),
      'category' => 'required|string|in:' . implode(',', CoreFinanceAccountModal::getCategories()),
      'parent_id' => 'nullable|exists:finance_accounts,id',
      'description' => 'nullable|string',
      'is_active' => 'boolean',
      'currency' => 'required|string|size:3',
      'tax_rate' => 'nullable|numeric|between:0,100',
      'notes' => 'nullable|string'
    ]);

    // Prevent circular reference
    if ($validated['parent_id'] && $account->children()->pluck('id')->contains($validated['parent_id'])) {
      return back()
        ->withInput()
        ->withErrors(['parent_id' => 'Cannot set a child account as parent.']);
    }

    $account->update($validated);

    return redirect()
      ->route('finance.accounts.show', $account)
      ->with('success', 'Account updated successfully');
  }

  /**
   * Remove the specified account.
   */
  public function destroy(CoreFinanceAccountModal $account)
  {
    // Check if account has children
    if ($account->children()->exists()) {
      return back()->withErrors(['error' => 'Cannot delete account with child accounts.']);
    }

    // Check if account has journal entries
    if ($account->journalEntries()->exists()) {
      return back()->withErrors(['error' => 'Cannot delete account with journal entries.']);
    }

    $account->delete();

    return redirect()
      ->route('finance.accounts.index')
      ->with('success', 'Account deleted successfully');
  }

  /**
   * Display the chart of accounts.
   */
  public function chart()
  {
    $accounts = CoreFinanceAccountModal::with('children')
      ->root()
      ->orderBy('code')
      ->get();

    return view('core.finance.accounts.chart', compact('accounts'));
  }

  /**
   * Display the account balance report.
   */
  public function balances(Request $request)
  {
    $query = CoreFinanceAccountModal::query();

    // Filter by type
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }

    // Filter by category
    if ($request->has('category')) {
      $query->where('category', $request->category);
    }

    $accounts = $query->orderBy('code')->get();

    $types = CoreFinanceAccountModal::getTypes();
    $categories = CoreFinanceAccountModal::getCategories();

    return view('core.finance.accounts.balances', compact('accounts', 'types', 'categories'));
  }

  /**
   * Display the account activity report.
   */
  public function activity(CoreFinanceAccountModal $account, Request $request)
  {
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now()->endOfMonth());

    $entries = $account->journalEntries()
      ->with(['journal', 'creator'])
      ->whereBetween('date', [$startDate, $endDate])
      ->orderBy('date')
      ->orderBy('created_at')
      ->paginate(20);

    return view('core.finance.accounts.activity', compact('account', 'entries', 'startDate', 'endDate'));
  }
}
