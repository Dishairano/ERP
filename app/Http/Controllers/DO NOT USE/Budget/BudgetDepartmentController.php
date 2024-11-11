<?php

namespace App\Http\Controllers\Budget;

use App\Models\Department;
use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetDepartmentController extends BaseBudgetController
{
  public function index()
  {
    $departments = Department::with(['budget' => function ($query) {
      $query->where('is_active', true);
    }])->get();

    return view('budgeting.departments.index', compact('departments'));
  }

  public function show(Department $department)
  {
    $budget = $department->budget()->where('is_active', true)->first();
    $expenses = $department->expenses()->latest()->get();

    return view('budgeting.departments.show', compact('department', 'budget', 'expenses'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'department_id' => 'required|exists:departments,id',
      'amount' => 'required|numeric|min:0',
      'fiscal_year' => 'required|integer|min:2000'
    ]);

    $department = Department::findOrFail($validated['department_id']);

    // Deactivate any existing budget
    $department->budget()->where('is_active', true)->update(['is_active' => false]);

    // Create new budget
    $budget = new Budget([
      'amount' => $validated['amount'],
      'fiscal_year' => $validated['fiscal_year'],
      'is_active' => true
    ]);

    $department->budget()->save($budget);

    return redirect()->route('budgets.departments')
      ->with('success', 'Department budget created successfully');
  }

  public function update(Request $request, Department $department)
  {
    $validated = $request->validate([
      'amount' => 'required|numeric|min:0',
      'fiscal_year' => 'required|integer|min:2000'
    ]);

    $budget = $department->budget()->where('is_active', true)->first();

    if (!$budget) {
      return redirect()->route('budgets.departments')
        ->with('error', 'No active budget found for this department');
    }

    $budget->update([
      'amount' => $validated['amount'],
      'fiscal_year' => $validated['fiscal_year']
    ]);

    return redirect()->route('budgets.departments')
      ->with('success', 'Department budget updated successfully');
  }
}
