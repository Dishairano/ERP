<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DistributionPlanning;

class DistributionPlanningController extends Controller
{
  public function index()
  {
    $plans = DistributionPlanning::all();
    return view('logistics.distribution-planning.index', compact('plans'));
  }

  public function create()
  {
    return view('logistics.distribution-planning.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'plan_name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|string|in:draft,active,completed',
    ]);

    DistributionPlanning::create($validated);

    return redirect()->route('logistics.distribution-planning.index')
      ->with('success', 'Distribution planning created successfully.');
  }

  public function show(DistributionPlanning $distributionPlanning)
  {
    return view('logistics.distribution-planning.show', compact('distributionPlanning'));
  }

  public function edit(DistributionPlanning $distributionPlanning)
  {
    return view('logistics.distribution-planning.edit', compact('distributionPlanning'));
  }

  public function update(Request $request, DistributionPlanning $distributionPlanning)
  {
    $validated = $request->validate([
      'plan_name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'status' => 'required|string|in:draft,active,completed',
    ]);

    $distributionPlanning->update($validated);

    return redirect()->route('logistics.distribution-planning.index')
      ->with('success', 'Distribution planning updated successfully.');
  }

  public function destroy(DistributionPlanning $distributionPlanning)
  {
    $distributionPlanning->delete();

    return redirect()->route('logistics.distribution-planning.index')
      ->with('success', 'Distribution planning deleted successfully.');
  }
}
