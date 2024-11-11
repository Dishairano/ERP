<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceAssetModal;
use App\Models\CoreFinanceAccountModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreFinanceAssetController extends Controller
{
  /**
   * Display a listing of assets.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceAssetModal::query()
      ->with(['creator', 'depreciationEntries']);

    // Filter by category
    if ($request->has('category')) {
      $query->where('category', $request->category);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by depreciation method
    if ($request->has('depreciation_method')) {
      $query->where('depreciation_method', $request->depreciation_method);
    }

    // Filter by location
    if ($request->has('location')) {
      $query->where('location', $request->location);
    }

    $assets = $query->orderBy('code')
      ->paginate(10);

    $categories = CoreFinanceAssetModal::getCategories();
    $depreciationMethods = CoreFinanceAssetModal::getDepreciationMethods();

    return view('core.finance.assets.index', compact('assets', 'categories', 'depreciationMethods'));
  }

  /**
   * Show the form for creating a new asset.
   */
  public function create()
  {
    $categories = CoreFinanceAssetModal::getCategories();
    $depreciationMethods = CoreFinanceAssetModal::getDepreciationMethods();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->orderBy('code')
      ->get();

    return view('core.finance.assets.create', compact('categories', 'depreciationMethods', 'accounts'));
  }

  /**
   * Store a newly created asset.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_assets,code',
      'name' => 'required|string|max:255',
      'category' => 'required|string|in:' . implode(',', CoreFinanceAssetModal::getCategories()),
      'purchase_date' => 'required|date',
      'purchase_cost' => 'required|numeric|min:0',
      'salvage_value' => 'required|numeric|min:0',
      'useful_life_years' => 'required|integer|min:1',
      'depreciation_method' => 'required|string|in:' . implode(',', CoreFinanceAssetModal::getDepreciationMethods()),
      'depreciation_rate' => 'nullable|numeric|between:0,100',
      'location' => 'nullable|string|max:255',
      'description' => 'nullable|string',
      'notes' => 'nullable|string',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Create asset
      $validated['created_by'] = Auth::id();
      $validated['current_value'] = $validated['purchase_cost'];

      $asset = CoreFinanceAssetModal::create($validated);

      // Create journal entries
      $asset->journalEntries()->create([
        'account_id' => $validated['account_id'],
        'type' => 'debit',
        'amount' => $validated['purchase_cost'],
        'date' => $validated['purchase_date'],
        'description' => 'Asset purchase: ' . $asset->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      $asset->journalEntries()->create([
        'account_id' => config('finance.accounts.cash'),
        'type' => 'credit',
        'amount' => $validated['purchase_cost'],
        'date' => $validated['purchase_date'],
        'description' => 'Asset purchase: ' . $asset->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      DB::commit();

      return redirect()
        ->route('finance.assets.show', $asset)
        ->with('success', 'Asset created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->withErrors(['error' => 'Failed to create asset. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified asset.
   */
  public function show(CoreFinanceAssetModal $asset)
  {
    $asset->load(['creator', 'depreciationEntries' => function ($query) {
      $query->latest()->limit(10);
    }, 'journalEntries.account']);

    return view('core.finance.assets.show', compact('asset'));
  }

  /**
   * Show the form for editing the specified asset.
   */
  public function edit(CoreFinanceAssetModal $asset)
  {
    if ($asset->status !== 'active') {
      return back()->withErrors(['error' => 'Only active assets can be edited.']);
    }

    $categories = CoreFinanceAssetModal::getCategories();
    $depreciationMethods = CoreFinanceAssetModal::getDepreciationMethods();
    $accounts = CoreFinanceAccountModal::active()
      ->where('type', 'asset')
      ->orderBy('code')
      ->get();

    return view('core.finance.assets.edit', compact('asset', 'categories', 'depreciationMethods', 'accounts'));
  }

  /**
   * Update the specified asset.
   */
  public function update(Request $request, CoreFinanceAssetModal $asset)
  {
    if ($asset->status !== 'active') {
      return back()->withErrors(['error' => 'Only active assets can be updated.']);
    }

    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_assets,code,' . $asset->id,
      'name' => 'required|string|max:255',
      'category' => 'required|string|in:' . implode(',', CoreFinanceAssetModal::getCategories()),
      'salvage_value' => 'required|numeric|min:0',
      'useful_life_years' => 'required|integer|min:1',
      'depreciation_method' => 'required|string|in:' . implode(',', CoreFinanceAssetModal::getDepreciationMethods()),
      'depreciation_rate' => 'nullable|numeric|between:0,100',
      'location' => 'nullable|string|max:255',
      'description' => 'nullable|string',
      'notes' => 'nullable|string'
    ]);

    $asset->update($validated);

    return redirect()
      ->route('finance.assets.show', $asset)
      ->with('success', 'Asset updated successfully');
  }

  /**
   * Record depreciation for the specified asset.
   */
  public function recordDepreciation(Request $request, CoreFinanceAssetModal $asset)
  {
    if ($asset->status !== 'active') {
      return back()->withErrors(['error' => 'Cannot record depreciation for inactive assets.']);
    }

    $validated = $request->validate([
      'date' => 'required|date',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Calculate and record depreciation
      $amount = $asset->calculateDepreciation($validated['date']);
      $asset->recordDepreciation($validated['date'], $amount);

      // Create journal entries
      $asset->journalEntries()->create([
        'account_id' => config('finance.accounts.depreciation_expense'),
        'type' => 'debit',
        'amount' => $amount,
        'date' => $validated['date'],
        'description' => 'Asset depreciation: ' . $asset->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      $asset->journalEntries()->create([
        'account_id' => config('finance.accounts.accumulated_depreciation'),
        'type' => 'credit',
        'amount' => $amount,
        'date' => $validated['date'],
        'description' => 'Asset depreciation: ' . $asset->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      DB::commit();

      return redirect()
        ->route('finance.assets.show', $asset)
        ->with('success', 'Depreciation recorded successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to record depreciation. ' . $e->getMessage()]);
    }
  }

  /**
   * Dispose of the specified asset.
   */
  public function dispose(Request $request, CoreFinanceAssetModal $asset)
  {
    if ($asset->status !== 'active') {
      return back()->withErrors(['error' => 'Only active assets can be disposed.']);
    }

    $validated = $request->validate([
      'date' => 'required|date',
      'value' => 'required|numeric|min:0',
      'notes' => 'nullable|string',
      'account_id' => 'required|exists:finance_accounts,id'
    ]);

    try {
      DB::beginTransaction();

      // Dispose asset
      $asset->dispose($validated['date'], $validated['value'], $validated['notes']);

      // Calculate gain/loss
      $gainLoss = $validated['value'] - $asset->current_value;

      // Create journal entries
      if ($gainLoss > 0) {
        // Gain on disposal
        $asset->journalEntries()->create([
          'account_id' => $validated['account_id'],
          'type' => 'debit',
          'amount' => $validated['value'],
          'date' => $validated['date'],
          'description' => 'Asset disposal: ' . $asset->name,
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        $asset->journalEntries()->create([
          'account_id' => config('finance.accounts.asset'),
          'type' => 'credit',
          'amount' => $asset->current_value,
          'date' => $validated['date'],
          'description' => 'Asset disposal: ' . $asset->name,
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        $asset->journalEntries()->create([
          'account_id' => config('finance.accounts.gain_on_disposal'),
          'type' => 'credit',
          'amount' => $gainLoss,
          'date' => $validated['date'],
          'description' => 'Gain on asset disposal: ' . $asset->name,
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      } else {
        // Loss on disposal
        $asset->journalEntries()->create([
          'account_id' => $validated['account_id'],
          'type' => 'debit',
          'amount' => $validated['value'],
          'date' => $validated['date'],
          'description' => 'Asset disposal: ' . $asset->name,
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        $asset->journalEntries()->create([
          'account_id' => config('finance.accounts.loss_on_disposal'),
          'type' => 'debit',
          'amount' => abs($gainLoss),
          'date' => $validated['date'],
          'description' => 'Loss on asset disposal: ' . $asset->name,
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);

        $asset->journalEntries()->create([
          'account_id' => config('finance.accounts.asset'),
          'type' => 'credit',
          'amount' => $asset->current_value,
          'date' => $validated['date'],
          'description' => 'Asset disposal: ' . $asset->name,
          'status' => 'posted',
          'created_by' => Auth::id()
        ]);
      }

      DB::commit();

      return redirect()
        ->route('finance.assets.show', $asset)
        ->with('success', 'Asset disposed successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to dispose asset. ' . $e->getMessage()]);
    }
  }

  /**
   * Write off the specified asset.
   */
  public function writeOff(Request $request, CoreFinanceAssetModal $asset)
  {
    if ($asset->status !== 'active') {
      return back()->withErrors(['error' => 'Only active assets can be written off.']);
    }

    $validated = $request->validate([
      'date' => 'required|date',
      'notes' => 'nullable|string'
    ]);

    try {
      DB::beginTransaction();

      // Write off asset
      $asset->writeOff($validated['date'], $validated['notes']);

      // Create journal entries
      $asset->journalEntries()->create([
        'account_id' => config('finance.accounts.loss_on_disposal'),
        'type' => 'debit',
        'amount' => $asset->current_value,
        'date' => $validated['date'],
        'description' => 'Asset write-off: ' . $asset->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      $asset->journalEntries()->create([
        'account_id' => config('finance.accounts.asset'),
        'type' => 'credit',
        'amount' => $asset->current_value,
        'date' => $validated['date'],
        'description' => 'Asset write-off: ' . $asset->name,
        'status' => 'posted',
        'created_by' => Auth::id()
      ]);

      DB::commit();

      return redirect()
        ->route('finance.assets.show', $asset)
        ->with('success', 'Asset written off successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to write off asset. ' . $e->getMessage()]);
    }
  }

  /**
   * Display the asset register report.
   */
  public function register(Request $request)
  {
    $query = CoreFinanceAssetModal::query()
      ->with(['depreciationEntries']);

    // Filter by category
    if ($request->has('category')) {
      $query->where('category', $request->category);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    $assets = $query->orderBy('category')
      ->orderBy('code')
      ->get()
      ->groupBy('category')
      ->map(function ($categoryAssets) {
        return [
          'assets' => $categoryAssets,
          'total_cost' => $categoryAssets->sum('purchase_cost'),
          'total_depreciation' => $categoryAssets->sum('accumulated_depreciation'),
          'total_value' => $categoryAssets->sum('current_value')
        ];
      });

    $categories = CoreFinanceAssetModal::getCategories();

    return view('core.finance.assets.register', compact('assets', 'categories'));
  }

  /**
   * Display the depreciation schedule report.
   */
  public function depreciationSchedule(Request $request)
  {
    $startDate = $request->get('start_date', now()->startOfYear());
    $endDate = $request->get('end_date', now()->endOfYear());

    $assets = CoreFinanceAssetModal::active()
      ->with(['depreciationEntries' => function ($query) use ($startDate, $endDate) {
        $query->whereBetween('date', [$startDate, $endDate]);
      }])
      ->get()
      ->map(function ($asset) use ($startDate, $endDate) {
        $monthlyDepreciation = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
          $monthKey = $currentDate->format('Y-m');
          $monthlyDepreciation[$monthKey] = $asset->depreciationEntries
            ->where('date', '>=', $currentDate->startOfMonth())
            ->where('date', '<=', $currentDate->endOfMonth())
            ->sum('amount');

          $currentDate->addMonth();
        }

        return [
          'asset' => $asset,
          'monthly_depreciation' => $monthlyDepreciation,
          'total_depreciation' => array_sum($monthlyDepreciation)
        ];
      });

    return view('core.finance.assets.depreciation-schedule', compact('assets', 'startDate', 'endDate'));
  }
}
