<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Department;
use App\Models\Project;
use App\Models\BudgetScenario;
use App\Models\BudgetKpi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
  /**
   * Display a listing of budgets.
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    $budgets = Budget::with(['department', 'project', 'creator'])
      ->latest()
      ->paginate(10);

    return view('budgets.index', compact('budgets'));
  }

  /**
   * Show the form for creating a new budget.
   *
   * @return \Illuminate\View\View
   */
  public function create()
  {
    $departments = Department::all();
    $projects = Project::active()->get();

    return view('budgets.create', compact('departments', 'projects'));
  }

  /**
   * Store a newly created budget.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|in:department,project',
      'department_id' => 'required_if:type,department|exists:departments,id',
      'project_id' => 'required_if:type,project|exists:projects,id',
      'fiscal_year' => 'required|integer',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'total_amount' => 'required|numeric|min:0',
      'categories' => 'required|array',
      'categories.*.name' => 'required|string',
      'categories.*.amount' => 'required|numeric|min:0',
      'notes' => 'nullable|string'
    ]);

    DB::transaction(function () use ($validated) {
      $budget = Budget::create([
        'name' => $validated['name'],
        'type' => $validated['type'],
        'department_id' => $validated['department_id'] ?? null,
        'project_id' => $validated['project_id'] ?? null,
        'fiscal_year' => $validated['fiscal_year'],
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
        'total_amount' => $validated['total_amount'],
        'notes' => $validated['notes'],
        'status' => 'draft',
        'created_by' => Auth::id()
      ]);

      foreach ($validated['categories'] as $category) {
        $budget->categories()->create([
          'name' => $category['name'],
          'amount' => $category['amount']
        ]);
      }
    });

    return redirect()->route('budgets.index')
      ->with('success', 'Budget created successfully.');
  }

  /**
   * Display department budgets.
   *
   * @return \Illuminate\View\View
   */
  public function departments()
  {
    $departments = Department::with(['budgets' => function ($query) {
      $query->latest();
    }])->get();

    return view('budgets.departments', compact('departments'));
  }

  /**
   * Display project budgets.
   *
   * @return \Illuminate\View\View
   */
  public function projects()
  {
    $projects = Project::with(['budgets' => function ($query) {
      $query->latest();
    }])->active()->get();

    return view('budgets.projects', compact('projects'));
  }

  /**
   * Display budget scenarios.
   *
   * @return \Illuminate\View\View
   */
  public function scenarios()
  {
    $scenarios = BudgetScenario::with(['budget', 'creator'])
      ->latest()
      ->paginate(10);

    return view('budgets.scenarios', compact('scenarios'));
  }

  /**
   * Store a new budget scenario.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeScenario(Request $request)
  {
    $validated = $request->validate([
      'budget_id' => 'required|exists:budgets,id',
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'adjustments' => 'required|array',
      'adjustments.*.category_id' => 'required|exists:budget_categories,id',
      'adjustments.*.amount' => 'required|numeric'
    ]);

    $scenario = BudgetScenario::create([
      'budget_id' => $validated['budget_id'],
      'name' => $validated['name'],
      'description' => $validated['description'],
      'created_by' => Auth::id()
    ]);

    foreach ($validated['adjustments'] as $adjustment) {
      $scenario->adjustments()->create([
        'category_id' => $adjustment['category_id'],
        'amount' => $adjustment['amount']
      ]);
    }

    return redirect()->route('budgets.scenarios')
      ->with('success', 'Budget scenario created successfully.');
  }

  /**
   * Display budget reports.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\View\View
   */
  public function reports(Request $request)
  {
    $validated = $request->validate([
      'fiscal_year' => 'nullable|integer',
      'type' => 'nullable|in:department,project',
      'department_id' => 'nullable|exists:departments,id',
      'project_id' => 'nullable|exists:projects,id'
    ]);

    $query = Budget::with(['department', 'project', 'categories', 'kpis']);

    if (isset($validated['fiscal_year'])) {
      $query->where('fiscal_year', $validated['fiscal_year']);
    }

    if (isset($validated['type'])) {
      $query->where('type', $validated['type']);
    }

    if (isset($validated['department_id'])) {
      $query->where('department_id', $validated['department_id']);
    }

    if (isset($validated['project_id'])) {
      $query->where('project_id', $validated['project_id']);
    }

    $budgets = $query->latest()->get();
    $departments = Department::all();
    $projects = Project::active()->get();

    return view('budgets.reports', compact('budgets', 'departments', 'projects'));
  }

  /**
   * Update budget status.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Budget  $budget
   * @return \Illuminate\Http\RedirectResponse
   */
  public function updateStatus(Request $request, Budget $budget)
  {
    $validated = $request->validate([
      'status' => 'required|in:draft,submitted,approved,rejected',
      'rejection_reason' => 'required_if:status,rejected|nullable|string'
    ]);

    $budget->update([
      'status' => $validated['status'],
      'rejection_reason' => $validated['rejection_reason'],
      'approved_by' => $validated['status'] === 'approved' ? Auth::id() : null,
      'approved_at' => $validated['status'] === 'approved' ? now() : null
    ]);

    return redirect()->route('budgets.index')
      ->with('success', 'Budget status updated successfully.');
  }

  /**
   * Store budget KPIs.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Budget  $budget
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeKpis(Request $request, Budget $budget)
  {
    $validated = $request->validate([
      'kpis' => 'required|array',
      'kpis.*.name' => 'required|string',
      'kpis.*.target' => 'required|numeric',
      'kpis.*.unit' => 'required|string',
      'kpis.*.frequency' => 'required|in:monthly,quarterly,yearly'
    ]);

    foreach ($validated['kpis'] as $kpi) {
      $budget->kpis()->create([
        'name' => $kpi['name'],
        'target' => $kpi['target'],
        'unit' => $kpi['unit'],
        'frequency' => $kpi['frequency']
      ]);
    }

    return redirect()->route('budgets.index')
      ->with('success', 'Budget KPIs added successfully.');
  }
}
