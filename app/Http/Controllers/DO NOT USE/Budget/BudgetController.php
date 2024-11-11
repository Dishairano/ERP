<?php

namespace App\Http\Controllers\Budget;

use App\Models\Budget;
use App\Models\Department;
use App\Models\Project;
use App\Models\CostCategory;
use Illuminate\Http\Request;

class BudgetController extends BaseBudgetController
{
  public function index()
  {
    $budgets = $this->getActiveBudgets()
      ->paginate(10);

    return view('budgeting.index', compact('budgets'));
  }

  public function create()
  {
    $departments = Department::orderBy('name')->get();
    $projects = Project::orderBy('name')->get();
    $costCategories = CostCategory::orderBy('name')->get();

    return view('budgeting.create', compact('departments', 'projects', 'costCategories'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'category_name' => 'required|string',
      'department_id' => 'nullable|exists:departments,id',
      'custom_department' => 'nullable|string|required_without:department_id',
      'project_id' => 'nullable|exists:projects,id',
      'custom_project' => 'nullable|string|required_without:project_id',
      'cost_category_id' => 'nullable|exists:cost_categories,id',
      'custom_cost_category' => 'nullable|string|required_without:cost_category_id',
      'planned_amount' => 'required|numeric|min:0',
      'currency' => 'required|string|in:EUR,USD,GBP',
      'alert_threshold_percentage' => 'required|numeric|min:0|max:100',
      'requires_approval' => 'required|boolean',
      'period_type' => 'required|string|in:yearly,quarterly,monthly',
      'fiscal_year' => 'required|integer|min:2000',
      'period_number' => 'nullable|integer|min:1|max:12'
    ]);

    $budget = Budget::create([
      'category_name' => $validated['category_name'],
      'department_id' => $validated['department_id'],
      'custom_department' => $validated['custom_department'],
      'project_id' => $validated['project_id'],
      'custom_project' => $validated['custom_project'],
      'cost_category_id' => $validated['cost_category_id'],
      'custom_cost_category' => $validated['custom_cost_category'],
      'planned_amount' => $validated['planned_amount'],
      'actual_amount' => 0,
      'currency' => $validated['currency'],
      'alert_threshold_percentage' => $validated['alert_threshold_percentage'],
      'requires_approval' => $validated['requires_approval'],
      'period_type' => $validated['period_type'],
      'fiscal_year' => $validated['fiscal_year'],
      'period_number' => $validated['period_number'],
      'is_active' => true
    ]);

    return redirect()->route('budgets.show', $budget)
      ->with('success', 'Budget created successfully');
  }

  public function show($id)
  {
    $budget = Budget::with(['department', 'project', 'costCategory'])
      ->findOrFail($id);

    return view('budgeting.show', compact('budget'));
  }

  public function edit($id)
  {
    $budget = Budget::with(['department', 'project', 'costCategory'])
      ->findOrFail($id);

    $departments = Department::orderBy('name')->get();
    $projects = Project::orderBy('name')->get();
    $costCategories = CostCategory::orderBy('name')->get();

    return view('budgeting.edit', compact('budget', 'departments', 'projects', 'costCategories'));
  }

  public function update(Request $request, $id)
  {
    $budget = Budget::findOrFail($id);

    $validated = $request->validate([
      'category_name' => 'required|string',
      'department_id' => 'nullable|exists:departments,id',
      'custom_department' => 'nullable|string|required_without:department_id',
      'project_id' => 'nullable|exists:projects,id',
      'custom_project' => 'nullable|string|required_without:project_id',
      'cost_category_id' => 'nullable|exists:cost_categories,id',
      'custom_cost_category' => 'nullable|string|required_without:cost_category_id',
      'planned_amount' => 'required|numeric|min:0',
      'currency' => 'required|string|in:EUR,USD,GBP',
      'alert_threshold_percentage' => 'required|numeric|min:0|max:100',
      'requires_approval' => 'required|boolean',
      'period_type' => 'required|string|in:yearly,quarterly,monthly',
      'fiscal_year' => 'required|integer|min:2000',
      'period_number' => 'nullable|integer|min:1|max:12'
    ]);

    $budget->update([
      'category_name' => $validated['category_name'],
      'department_id' => $validated['department_id'],
      'custom_department' => $validated['custom_department'],
      'project_id' => $validated['project_id'],
      'custom_project' => $validated['custom_project'],
      'cost_category_id' => $validated['cost_category_id'],
      'custom_cost_category' => $validated['custom_cost_category'],
      'planned_amount' => $validated['planned_amount'],
      'currency' => $validated['currency'],
      'alert_threshold_percentage' => $validated['alert_threshold_percentage'],
      'requires_approval' => $validated['requires_approval'],
      'period_type' => $validated['period_type'],
      'fiscal_year' => $validated['fiscal_year'],
      'period_number' => $validated['period_number']
    ]);

    return redirect()->route('budgets.show', $budget)
      ->with('success', 'Budget updated successfully');
  }

  public function destroy($id)
  {
    $budget = Budget::findOrFail($id);
    $budget->update(['is_active' => false]);

    return redirect()->route('budgets.index')
      ->with('success', 'Budget deactivated successfully');
  }
}
