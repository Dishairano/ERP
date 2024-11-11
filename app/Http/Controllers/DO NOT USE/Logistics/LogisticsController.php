<?php

namespace App\Http\Controllers\Logistics;

use App\Models\DistributionPlanning;
use App\Models\LogisticsManagement;
use App\Models\DemandPlanning;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogisticsController extends Controller
{
    // Distribution Planning
    public function distributionPlanningIndex()
    {
        $plans = DistributionPlanning::all();
        return view('logistics.distribution-planning.index', compact('plans'));
    }

    public function distributionPlanningCreate()
    {
        return view('logistics.distribution-planning.create');
    }

    public function distributionPlanningStore(Request $request)
    {
        $request->validate([
            'plan_name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        DistributionPlanning::create($request->all());

        return redirect()->route('logistics.distribution-planning')->with('success', 'Distribution Plan added successfully.');
    }

    public function distributionPlanningEdit($id)
    {
        $plan = DistributionPlanning::findOrFail($id);
        return view('logistics.distribution-planning.edit', compact('plan'));
    }

    public function distributionPlanningUpdate(Request $request, $id)
    {
        $request->validate([
            'plan_name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $plan = DistributionPlanning::findOrFail($id);
        $plan->update($request->all());

        return redirect()->route('logistics.distribution-planning')->with('success', 'Distribution Plan updated successfully.');
    }

    public function distributionPlanningDestroy($id)
    {
        $plan = DistributionPlanning::findOrFail($id);
        $plan->delete();

        return redirect()->route('logistics.distribution-planning')->with('success', 'Distribution Plan deleted successfully.');
    }

    // Logistics Management
    public function logisticsManagementIndex()
    {
        $logistics = LogisticsManagement::all();
        return view('logistics.logistics-management.index', compact('logistics'));
    }

    public function logisticsManagementCreate()
    {
        return view('logistics.logistics-management.create');
    }

    public function logisticsManagementStore(Request $request)
    {
        $request->validate([
            'logistics_name' => 'required',
            'description' => 'required',
        ]);

        LogisticsManagement::create($request->all());

        return redirect()->route('logistics.logistics-management')->with('success', 'Logistics Management record added successfully.');
    }

    public function logisticsManagementEdit($id)
    {
        $logistics = LogisticsManagement::findOrFail($id);
        return view('logistics.logistics-management.edit', compact('logistics'));
    }

    public function logisticsManagementUpdate(Request $request, $id)
    {
        $request->validate([
            'logistics_name' => 'required',
            'description' => 'required',
        ]);

        $logistics = LogisticsManagement::findOrFail($id);
        $logistics->update($request->all());

        return redirect()->route('logistics.logistics-management')->with('success', 'Logistics Management record updated successfully.');
    }

    public function logisticsManagementDestroy($id)
    {
        $logistics = LogisticsManagement::findOrFail($id);
        $logistics->delete();

        return redirect()->route('logistics.logistics-management')->with('success', 'Logistics Management record deleted successfully.');
    }

    // Demand Planning
    public function demandPlanningIndex()
    {
        $demands = DemandPlanning::all();
        return view('logistics.demand-planning.index', compact('demands'));
    }

    public function demandPlanningCreate()
    {
        return view('logistics.demand-planning.create');
    }

    public function demandPlanningStore(Request $request)
    {
        $request->validate([
            'demand_name' => 'required',
            'quantity' => 'required|integer',
        ]);

        DemandPlanning::create($request->all());

        return redirect()->route('logistics.demand-planning')->with('success', 'Demand Plan added successfully.');
    }

    public function demandPlanningEdit($id)
    {
        $demand = DemandPlanning::findOrFail($id);
        return view('logistics.demand-planning.edit', compact('demand'));
    }

    public function demandPlanningUpdate(Request $request, $id)
    {
        $request->validate([
            'demand_name' => 'required',
            'quantity' => 'required|integer',
        ]);

        $demand = DemandPlanning::findOrFail($id);
        $demand->update($request->all());

        return redirect()->route('logistics.demand-planning')->with('success', 'Demand Plan updated successfully.');
    }

    public function demandPlanningDestroy($id)
    {
        $demand = DemandPlanning::findOrFail($id);
        $demand->delete();

        return redirect()->route('logistics.demand-planning')->with('success', 'Demand Plan deleted successfully.');
    }
}