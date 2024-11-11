<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\DashboardComponent;
use App\Models\DashboardCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  public function index()
  {
    $user = Auth::user();
    $dashboards = Dashboard::with(['category', 'components'])
      ->where('is_active', true)
      ->orderBy('created_at', 'desc')
      ->get();

    $categories = DashboardCategory::all();

    return view('dashboard.index', compact('dashboards', 'categories'));
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

    $user = Auth::user();
    $validated['created_by'] = $user->id;
    $validated['updated_by'] = $user->id;
    $validated['is_active'] = true;

    $dashboard = Dashboard::create($validated);

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

    return redirect()->route('dashboard.index')->with('success', 'Dashboard created successfully');
  }

  public function update(Request $request, Dashboard $dashboard)
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

    $user = Auth::user();
    $validated['updated_by'] = $user->id;

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

    return redirect()->route('dashboard.index')->with('success', 'Dashboard updated successfully');
  }

  public function destroy(Dashboard $dashboard)
  {
    $dashboard->components()->delete();
    $dashboard->delete();

    return redirect()->route('dashboard.index')->with('success', 'Dashboard deleted successfully');
  }

  public function toggleActive(Dashboard $dashboard)
  {
    $user = Auth::user();
    $dashboard->update([
      'is_active' => !$dashboard->is_active,
      'updated_by' => $user->id
    ]);

    return redirect()->route('dashboard.index')->with('success', 'Dashboard status updated successfully');
  }
}
