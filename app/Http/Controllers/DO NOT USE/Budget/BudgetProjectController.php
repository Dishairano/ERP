<?php

namespace App\Http\Controllers\Budget;

use App\Models\Project;
use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetProjectController extends BaseBudgetController
{
  public function index()
  {
    $projects = Project::with(['budget' => function ($query) {
      $query->where('is_active', true);
    }])->get();

    return view('budgeting.projects.index', compact('projects'));
  }

  public function show(Project $project)
  {
    $budget = $project->budget()->where('is_active', true)->first();
    $expenses = $project->expenses()->latest()->get();

    return view('budgeting.projects.show', compact('project', 'budget', 'expenses'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'planned_amount' => 'required|numeric|min:0',
      'fiscal_year' => 'required|integer|min:2000',
      'currency' => 'required|string|in:EUR,USD,GBP',
      'alert_threshold_percentage' => 'required|numeric|min:0|max:100',
      'requires_approval' => 'required|boolean',
      'period_type' => 'required|string|in:yearly,quarterly,monthly',
      'period_number' => 'nullable|integer|min:1|max:12'
    ]);

    $project = Project::findOrFail($validated['project_id']);

    // Deactivate any existing budget
    $project->budget()->where('is_active', true)->update(['is_active' => false]);

    // Create new budget
    $budget = new Budget([
      'category_name' => 'Project Budget: ' . $project->name,
      'project_id' => $project->id,
      'planned_amount' => $validated['planned_amount'],
      'fiscal_year' => $validated['fiscal_year'],
      'currency' => $validated['currency'],
      'alert_threshold_percentage' => $validated['alert_threshold_percentage'],
      'requires_approval' => $validated['requires_approval'],
      'period_type' => $validated['period_type'],
      'period_number' => $validated['period_number'],
      'is_active' => true
    ]);

    $budget->save();

    return redirect()->route('budgets.projects')
      ->with('success', 'Project budget created successfully');
  }

  public function update(Request $request, Project $project)
  {
    $validated = $request->validate([
      'planned_amount' => 'required|numeric|min:0',
      'fiscal_year' => 'required|integer|min:2000',
      'currency' => 'required|string|in:EUR,USD,GBP',
      'alert_threshold_percentage' => 'required|numeric|min:0|max:100',
      'requires_approval' => 'required|boolean',
      'period_type' => 'required|string|in:yearly,quarterly,monthly',
      'period_number' => 'nullable|integer|min:1|max:12'
    ]);

    $budget = $project->budget()->where('is_active', true)->first();

    if (!$budget) {
      return redirect()->route('budgets.projects')
        ->with('error', 'No active budget found for this project');
    }

    $budget->update([
      'planned_amount' => $validated['planned_amount'],
      'fiscal_year' => $validated['fiscal_year'],
      'currency' => $validated['currency'],
      'alert_threshold_percentage' => $validated['alert_threshold_percentage'],
      'requires_approval' => $validated['requires_approval'],
      'period_type' => $validated['period_type'],
      'period_number' => $validated['period_number']
    ]);

    return redirect()->route('budgets.projects')
      ->with('success', 'Project budget updated successfully');
  }
}
