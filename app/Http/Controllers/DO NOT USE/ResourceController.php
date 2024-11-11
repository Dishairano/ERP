<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceAssignment;
use App\Models\ResourceMaintenanceSchedule;
use App\Models\ResourceCost;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
  public function index()
  {
    $resources = Resource::with(['assignments', 'maintenanceSchedules'])
      ->get()
      ->map(function ($resource) {
        $resource->current_utilization = $resource->getCurrentUtilization();
        return $resource;
      });

    return view('resources.index', compact('resources'));
  }

  public function show(Resource $resource)
  {
    $resource->load(['assignments', 'maintenanceSchedules', 'costs']);
    $utilization = $resource->getCurrentUtilization();
    $upcomingMaintenance = $resource->maintenanceSchedules()
      ->where('status', '!=', 'completed')
      ->orderBy('scheduled_date')
      ->get();

    return view('resources.show', compact('resource', 'utilization', 'upcomingMaintenance'));
  }

  public function create()
  {
    return view('resources.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|string',
      'description' => 'nullable|string',
      'status' => 'required|string',
      'capabilities' => 'nullable|array',
      'cost_per_hour' => 'nullable|numeric',
      'cost_per_day' => 'nullable|numeric',
      'capacity' => 'required|integer|min:1',
      'location_details' => 'nullable|array',
    ]);

    $resource = Resource::create($validated);

    return redirect()->route('resources.show', $resource)
      ->with('success', 'Resource created successfully.');
  }

  public function edit(Resource $resource)
  {
    return view('resources.edit', compact('resource'));
  }

  public function update(Request $request, Resource $resource)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|string',
      'description' => 'nullable|string',
      'status' => 'required|string',
      'capabilities' => 'nullable|array',
      'cost_per_hour' => 'nullable|numeric',
      'cost_per_day' => 'nullable|numeric',
      'capacity' => 'required|integer|min:1',
      'location_details' => 'nullable|array',
    ]);

    $resource->update($validated);

    return redirect()->route('resources.show', $resource)
      ->with('success', 'Resource updated successfully.');
  }

  public function destroy(Resource $resource)
  {
    $resource->delete();

    return redirect()->route('resources.index')
      ->with('success', 'Resource deleted successfully.');
  }

  public function assign(Request $request, Resource $resource)
  {
    $validated = $request->validate([
      'project_id' => 'required|exists:projects,id',
      'user_id' => 'nullable|exists:users,id',
      'start_time' => 'required|date',
      'end_time' => 'required|date|after:start_time',
      'planned_hours' => 'required|numeric|min:0',
      'notes' => 'nullable|string',
    ]);

    if (!$resource->isAvailable($validated['start_time'], $validated['end_time'])) {
      return back()->with('error', 'Resource is not available for the selected time period.');
    }

    $assignment = $resource->assignments()->create([
      'project_id' => $validated['project_id'],
      'user_id' => $validated['user_id'],
      'start_time' => $validated['start_time'],
      'end_time' => $validated['end_time'],
      'planned_hours' => $validated['planned_hours'],
      'notes' => $validated['notes'],
      'status' => 'planned',
    ]);

    return redirect()->route('resources.show', $resource)
      ->with('success', 'Resource assigned successfully.');
  }

  public function scheduleMaintenance(Request $request, Resource $resource)
  {
    $validated = $request->validate([
      'maintenance_type' => 'required|string',
      'scheduled_date' => 'required|date|after:now',
      'description' => 'required|string',
      'estimated_duration_hours' => 'required|numeric|min:0',
      'cost' => 'nullable|numeric|min:0',
    ]);

    $maintenance = $resource->maintenanceSchedules()->create([
      'maintenance_type' => $validated['maintenance_type'],
      'scheduled_date' => $validated['scheduled_date'],
      'description' => $validated['description'],
      'estimated_duration_hours' => $validated['estimated_duration_hours'],
      'cost' => $validated['cost'],
      'status' => 'scheduled',
    ]);

    return redirect()->route('resources.show', $resource)
      ->with('success', 'Maintenance scheduled successfully.');
  }

  public function dashboard()
  {
    $resourceUtilization = Resource::all()->map(function ($resource) {
      return [
        'name' => $resource->name,
        'utilization' => $resource->getCurrentUtilization(),
      ];
    });

    $upcomingMaintenance = ResourceMaintenanceSchedule::with('resource')
      ->where('status', '!=', 'completed')
      ->orderBy('scheduled_date')
      ->take(5)
      ->get();

    $resourceCosts = ResourceCost::with('resource')
      ->where('date', '>=', now()->subMonths(3))
      ->get()
      ->groupBy('resource_id')
      ->map(function ($costs) {
        return $costs->sum('amount');
      });

    return view('resources.dashboard', compact('resourceUtilization', 'upcomingMaintenance', 'resourceCosts'));
  }

  public function availability()
  {
    $resources = Resource::with(['assignments' => function ($query) {
      $query->whereBetween('start_time', [now(), now()->addWeek()]);
    }])->get();

    return view('resources.availability', compact('resources'));
  }

  public function reports()
  {
    $monthlyUtilization = DB::table('resource_assignments')
      ->join('resources', 'resource_assignments.resource_id', '=', 'resources.id')
      ->select(
        'resources.name',
        DB::raw('MONTH(start_time) as month'),
        DB::raw('YEAR(start_time) as year'),
        DB::raw('SUM(actual_hours_used) as total_hours')
      )
      ->whereYear('start_time', now()->year)
      ->groupBy('resources.name', 'month', 'year')
      ->get();

    $costsByType = ResourceCost::select('cost_type', DB::raw('SUM(amount) as total'))
      ->whereYear('date', now()->year)
      ->groupBy('cost_type')
      ->get();

    $maintenanceStats = ResourceMaintenanceSchedule::select(
      'maintenance_type',
      DB::raw('COUNT(*) as total'),
      DB::raw('AVG(actual_duration_hours) as avg_duration'),
      DB::raw('SUM(cost) as total_cost')
    )
      ->whereYear('scheduled_date', now()->year)
      ->groupBy('maintenance_type')
      ->get();

    return view('resources.reports', compact('monthlyUtilization', 'costsByType', 'maintenanceStats'));
  }
}
