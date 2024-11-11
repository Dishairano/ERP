<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceDepartmentModal;
use App\Models\CoreFinancePresetDepartmentModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CoreFinancePresetDepartmentController extends Controller
{
  /**
   * Display a listing of department presets.
   */
  public function index(Request $request)
  {
    $query = CoreFinancePresetDepartmentModal::query()
      ->with(['department', 'creator', 'approver', 'lineItems']);

    // Filter by fiscal year
    if ($request->has('fiscal_year')) {
      $query->where('fiscal_year', $request->fiscal_year);
    }

    // Filter by department
    if ($request->has('department_id')) {
      $query->where('department_id', $request->department_id);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    $presets = $query->orderBy('fiscal_year', 'desc')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $departments = CoreFinanceDepartmentModal::active()->get();

    return view('core.finance.presets.index', compact('presets', 'departments'));
  }

  /**
   * Show the form for creating a new preset.
   */
  public function create()
  {
    $departments = CoreFinanceDepartmentModal::active()->get();

    return view('core.finance.presets.create', compact('departments'));
  }

  /**
   * Store a newly created preset.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'department_id' => 'required|exists:finance_departments,id',
      'fiscal_year' => 'required|integer|min:2000|max:2100',
      'total_amount' => 'required|numeric|min:0',
      'description' => 'nullable|string',
      'status' => 'required|string|in:draft,active',
      'notes' => 'nullable|string'
    ]);

    // Check for duplicate preset
    if (CoreFinancePresetDepartmentModal::where([
      'department_id' => $validated['department_id'],
      'fiscal_year' => $validated['fiscal_year'],
      'name' => $validated['name']
    ])->exists()) {
      return back()
        ->withInput()
        ->withErrors(['error' => 'A preset with this name already exists for the selected department and fiscal year.']);
    }

    $validated['created_by'] = Auth::id();

    $preset = CoreFinancePresetDepartmentModal::create($validated);

    return redirect()
      ->route('finance.presets.show', $preset)
      ->with('success', 'Department preset created successfully');
  }

  /**
   * Display the specified preset.
   */
  public function show(CoreFinancePresetDepartmentModal $preset)
  {
    $preset->load(['department', 'creator', 'approver', 'lineItems']);

    return view('core.finance.presets.show', compact('preset'));
  }

  /**
   * Show the form for editing the specified preset.
   */
  public function edit(CoreFinancePresetDepartmentModal $preset)
  {
    if ($preset->isApproved()) {
      return back()->withErrors(['error' => 'Cannot edit an approved preset.']);
    }

    $departments = CoreFinanceDepartmentModal::active()->get();

    return view('core.finance.presets.edit', compact('preset', 'departments'));
  }

  /**
   * Update the specified preset.
   */
  public function update(Request $request, CoreFinancePresetDepartmentModal $preset)
  {
    if ($preset->isApproved()) {
      return back()->withErrors(['error' => 'Cannot edit an approved preset.']);
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'department_id' => 'required|exists:finance_departments,id',
      'fiscal_year' => 'required|integer|min:2000|max:2100',
      'total_amount' => 'required|numeric|min:0',
      'description' => 'nullable|string',
      'status' => 'required|string|in:draft,active',
      'notes' => 'nullable|string'
    ]);

    // Check for duplicate preset
    if (CoreFinancePresetDepartmentModal::where([
      'department_id' => $validated['department_id'],
      'fiscal_year' => $validated['fiscal_year'],
      'name' => $validated['name']
    ])->where('id', '!=', $preset->id)->exists()) {
      return back()
        ->withInput()
        ->withErrors(['error' => 'A preset with this name already exists for the selected department and fiscal year.']);
    }

    $preset->update($validated);

    return redirect()
      ->route('finance.presets.show', $preset)
      ->with('success', 'Department preset updated successfully');
  }

  /**
   * Remove the specified preset.
   */
  public function destroy(CoreFinancePresetDepartmentModal $preset)
  {
    if ($preset->isApproved()) {
      return back()->withErrors(['error' => 'Cannot delete an approved preset.']);
    }

    $preset->delete();

    return redirect()
      ->route('finance.presets.index')
      ->with('success', 'Department preset deleted successfully');
  }

  /**
   * Approve the specified preset.
   */
  public function approve(CoreFinancePresetDepartmentModal $preset)
  {
    if ($preset->isApproved()) {
      return back()->withErrors(['error' => 'Preset is already approved.']);
    }

    $preset->update([
      'approved_by' => Auth::id(),
      'approved_at' => now()
    ]);

    return redirect()
      ->route('finance.presets.show', $preset)
      ->with('success', 'Department preset approved successfully');
  }

  /**
   * Create a budget from the preset.
   */
  public function createBudget(CoreFinancePresetDepartmentModal $preset)
  {
    if (!$preset->isApproved()) {
      return back()->withErrors(['error' => 'Cannot create budget from an unapproved preset.']);
    }

    $budget = $preset->createBudget();

    return redirect()
      ->route('finance.budgets.show', $budget)
      ->with('success', 'Budget created successfully from preset');
  }
}
