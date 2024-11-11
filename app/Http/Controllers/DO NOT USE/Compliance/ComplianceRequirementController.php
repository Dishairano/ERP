<?php

namespace App\Http\Controllers\Compliance;

use App\Http\Controllers\Controller;
use App\Models\ComplianceRequirement;
use Illuminate\Http\Request;

class ComplianceRequirementController extends Controller
{
  public function index()
  {
    $requirements = ComplianceRequirement::latest()->paginate(10);
    return view('compliance.requirements.index', compact('requirements'));
  }

  public function create()
  {
    return view('compliance.requirements.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'regulation_type' => 'required|string',
      'effective_date' => 'required|date',
      'review_date' => 'required|date',
      'requirements' => 'required|string',
      'actions_needed' => 'nullable|string',
      'is_mandatory' => 'boolean',
      'risk_level' => 'required|string',
      'department_scope' => 'nullable|string'
    ]);

    ComplianceRequirement::create($validated);

    return redirect()->route('compliance.requirements.index')
      ->with('success', 'Compliance requirement created successfully.');
  }

  public function show(ComplianceRequirement $requirement)
  {
    return view('compliance.requirements.show', compact('requirement'));
  }

  public function edit(ComplianceRequirement $requirement)
  {
    return view('compliance.requirements.edit', compact('requirement'));
  }

  public function update(Request $request, ComplianceRequirement $requirement)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'regulation_type' => 'required|string',
      'effective_date' => 'required|date',
      'review_date' => 'required|date',
      'requirements' => 'required|string',
      'actions_needed' => 'nullable|string',
      'is_mandatory' => 'boolean',
      'risk_level' => 'required|string',
      'department_scope' => 'nullable|string'
    ]);

    $requirement->update($validated);

    return redirect()->route('compliance.requirements.index')
      ->with('success', 'Compliance requirement updated successfully.');
  }

  public function destroy(ComplianceRequirement $requirement)
  {
    $requirement->delete();

    return redirect()->route('compliance.requirements.index')
      ->with('success', 'Compliance requirement deleted successfully.');
  }
}
