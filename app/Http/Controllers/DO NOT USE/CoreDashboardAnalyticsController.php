<?php

namespace App\Http\Controllers;

use App\Models\CoreDashboardAnalyticsModal;
use App\Models\DashboardComponent;
use App\Models\DashboardCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoreDashboardAnalyticsController extends Controller
{
  public function index()
  {
    $dashboards = CoreDashboardAnalyticsModal::with(['category', 'components'])
      ->where('is_active', true)
      ->orderBy('created_at', 'desc')
      ->get();

    $categories = DashboardCategory::all();

    return view('core.dashboard.analytics', compact('dashboards', 'categories'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'type' => 'required|string',
      'data_source' => 'required|string',
      'refresh_interval' => 'required|integer|min:0',
      'layout_config' => 'required|json',
      'category_id' => 'required|exists:dashboard_categories,id'
    ]);

    $validated['created_by'] = Auth::id();
    $validated['updated_by'] = Auth::id();
    $validated['is_active'] = true;

    $dashboard = CoreDashboardAnalyticsModal::create($validated);

    if ($request->has('components')) {
      foreach ($request->components as $component) {
        DashboardComponent::create([
          'dashboard_id' => $dashboard->id,
          'type' => $component['type'],
          'config' => $component['config'],
          'position' => $component['position']
        ]);
      }
    }

    return redirect()->route('dashboard.analytics')->with('success', 'Dashboard created successfully');
  }

  public function update(Request $request, CoreDashboardAnalyticsModal $dashboard)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'type' => 'required|string',
      'data_source' => 'required|string',
      'refresh_interval' => 'required|integer|min:0',
      'layout_config' => 'required|json',
      'category_id' => 'required|exists:dashboard_categories,id'
    ]);

    $validated['updated_by'] = Auth::id();

    $dashboard->update($validated);

    if ($request->has('components')) {
      // Remove existing components
      $dashboard->components()->delete();

      // Add new components
      foreach ($request->components as $component) {
        DashboardComponent::create([
          'dashboard_id' => $dashboard->id,
          'type' => $component['type'],
          'config' => $component['config'],
          'position' => $component['position']
        ]);
      }
    }

    return redirect()->route('dashboard.analytics')->with('success', 'Dashboard updated successfully');
  }

  public function destroy(CoreDashboardAnalyticsModal $dashboard)
  {
    $dashboard->components()->delete();
    $dashboard->delete();

    return redirect()->route('dashboard.analytics')->with('success', 'Dashboard deleted successfully');
  }

  public function toggleActive(CoreDashboardAnalyticsModal $dashboard)
  {
    $dashboard->update([
      'is_active' => !$dashboard->is_active,
      'updated_by' => Auth::id()
    ]);

    return redirect()->route('dashboard.analytics')->with('success', 'Dashboard status updated successfully');
  }
}
