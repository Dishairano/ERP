<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceBudgetModal;
use App\Models\CoreFinanceDepartmentModal;
use App\Models\CoreProjectModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CoreFinanceBudgetController extends Controller
{
  /**
   * Display a listing of budgets.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceBudgetModal::query()
      ->with(['department', 'project', 'creator', 'approver']);

    // Filter by fiscal year
    if ($request->has('fiscal_year')) {
      $query->where('fiscal_year', $request->fiscal_year);
    }

    // Filter by department
    if ($request->has('department_id')) {
      $query->where('department_id', $request->department_id);
    }

    // Filter by project
    if ($request->has('project_id')) {
      $query->where('project_id', $request->project_id);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    $budgets = $query->orderBy('fiscal_year', 'desc')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $departments = CoreFinanceDepartmentModal::active()->get();
    $projects = CoreProjectModal::where('status', '!=', 'completed')->get();

    return view('core.finance.budgets.index', compact('budgets', 'departments', 'projects'));
  }

  /**
   * Show the form for creating a new budget.
   */
  public function create()
  {
    $departments = CoreFinanceDepartmentModal::active()->get();
    $projects = CoreProjectModal::where('status', '!=', 'completed')->get();

    return view('core.finance.budgets.create', compact('departments', 'projects'));
  }

  /**
   * Store a newly created budget.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'fiscal_year' => 'required|integer|min:2000|max:2100',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'total_amount' => 'required|numeric|min:0',
      'department_id' => 'nullable|exists:finance_departments,id',
      'project_id' => 'nullable|exists:projects,id',
      'status' => 'required|string|in:draft,active,closed',
      'notes' => 'nullable|string'
    ]);

    // Ensure either department_id or project_id is provided, but not both
    if (($request->department_id && $request->project_id) || (!$request->department_id && !$request->project_id)) {
      return back()
        ->withInput()
        ->withErrors(['error' => 'Please specify either a department or a project, but not both.']);
    }

    $validated['created_by'] = Auth::id();
    $validated['remaining_amount'] = $validated['total_amount'];

    $budget = CoreFinanceBudgetModal::create($validated);

    return redirect()
      ->route('finance.budgets.show', $budget)
      ->with('success', 'Budget created successfully');
  }

  /**
   * Display the specified budget.
   */
  public function show(CoreFinanceBudgetModal $budget)
  {
    $budget->load([
      'department',
      'project',
      'creator',
      'approver',
      'lineItems',
      'scenarios'
    ]);

    return view('core.finance.budgets.show', compact('budget'));
  }

  /**
   * Show the form for editing the specified budget.
   */
  public function edit(CoreFinanceBudgetModal $budget)
  {
    $departments = CoreFinanceDepartmentModal::active()->get();
    $projects = CoreProjectModal::where('status', '!=', 'completed')->get();

    return view('core.finance.budgets.edit', compact('budget', 'departments', 'projects'));
  }

  /**
   * Update the specified budget.
   */
  public function update(Request $request, CoreFinanceBudgetModal $budget)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'fiscal_year' => 'required|integer|min:2000|max:2100',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'total_amount' => 'required|numeric|min:0',
      'department_id' => 'nullable|exists:finance_departments,id',
      'project_id' => 'nullable|exists:projects,id',
      'status' => 'required|string|in:draft,active,closed',
      'notes' => 'nullable|string'
    ]);

    // Ensure either department_id or project_id is provided, but not both
    if (($request->department_id && $request->project_id) || (!$request->department_id && !$request->project_id)) {
      return back()
        ->withInput()
        ->withErrors(['error' => 'Please specify either a department or a project, but not both.']);
    }

    // Update remaining amount if total amount changed
    if ($budget->total_amount !== $validated['total_amount']) {
      $validated['remaining_amount'] = $validated['total_amount'] - $budget->allocated_amount;
    }

    $budget->update($validated);

    return redirect()
      ->route('finance.budgets.show', $budget)
      ->with('success', 'Budget updated successfully');
  }

  /**
   * Remove the specified budget.
   */
  public function destroy(CoreFinanceBudgetModal $budget)
  {
    if ($budget->status !== 'draft') {
      return back()->withErrors(['error' => 'Only draft budgets can be deleted.']);
    }

    $budget->delete();

    return redirect()
      ->route('finance.budgets.index')
      ->with('success', 'Budget deleted successfully');
  }

  /**
   * Display department budgets.
   */
  public function departments(Request $request)
  {
    $query = CoreFinanceBudgetModal::query()
      ->whereNotNull('department_id')
      ->with(['department', 'creator', 'approver']);

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

    $budgets = $query->orderBy('fiscal_year', 'desc')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $departments = CoreFinanceDepartmentModal::active()->get();

    return view('core.finance.budgets.departments', compact('budgets', 'departments'));
  }

  /**
   * Display project budgets.
   */
  public function projects(Request $request)
  {
    $query = CoreFinanceBudgetModal::query()
      ->whereNotNull('project_id')
      ->with(['project', 'creator', 'approver']);

    // Filter by fiscal year
    if ($request->has('fiscal_year')) {
      $query->where('fiscal_year', $request->fiscal_year);
    }

    // Filter by project
    if ($request->has('project_id')) {
      $query->where('project_id', $request->project_id);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    $budgets = $query->orderBy('fiscal_year', 'desc')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $projects = CoreProjectModal::where('status', '!=', 'completed')->get();

    return view('core.finance.budgets.projects', compact('budgets', 'projects'));
  }

  /**
   * Display budget reports.
   */
  public function reports(Request $request)
  {
    $fiscalYear = $request->get('fiscal_year', Carbon::now()->year);
    $type = $request->get('type', 'summary'); // summary, detailed, comparison

    $budgets = CoreFinanceBudgetModal::where('fiscal_year', $fiscalYear)
      ->with(['department', 'project', 'lineItems'])
      ->get();

    return view('core.finance.budgets.reports', compact('budgets', 'fiscalYear', 'type'));
  }
}
