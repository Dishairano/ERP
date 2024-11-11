<?php

namespace App\Http\Controllers\Logistics;

use App\Models\DistributionPlanning;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $request->validate([
            'plan_name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        DistributionPlanning::create($request->all());

        return redirect()->route('logistics.distribution-planning')->with('success', 'Distribution plan added successfully.');
    }

    public function edit($id)
    {
        $plan = DistributionPlanning::findOrFail($id);
        return view('logistics.distribution-planning.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'plan_name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        $plan = DistributionPlanning::findOrFail($id);
        $plan->update($request->all());

        return redirect()->route('logistics.distribution-planning')->with('success', 'Distribution plan updated successfully.');
    }

    public function destroy($id)
    {
        $plan = DistributionPlanning::findOrFail($id);
        $plan->delete();

        return redirect()->route('logistics.distribution-planning')->with('success', 'Distribution plan deleted successfully.');
    }
}