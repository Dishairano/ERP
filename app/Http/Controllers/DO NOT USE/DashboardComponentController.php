<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\DashboardComponent;
use App\Models\DataSource;
use App\Services\DashboardComponentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class DashboardComponentController extends BaseController
{
  use AuthorizesRequests, ValidatesRequests;

  protected $componentService;

  public function __construct(DashboardComponentService $componentService)
  {
    $this->middleware('auth');
    $this->componentService = $componentService;
  }

  public function index(Dashboard $dashboard)
  {
    $this->authorize('view', $dashboard);

    $components = $dashboard->components()
      ->with('dataSource')
      ->orderBy('position')
      ->get();

    return view('dashboard-components.index', compact('dashboard', 'components'));
  }

  public function create(Dashboard $dashboard)
  {
    $this->authorize('manageComponents', $dashboard);

    $dataSources = DataSource::active()->get();
    $types = $this->componentService->getAvailableComponentTypes();

    return view('dashboard-components.create', compact('dashboard', 'dataSources', 'types'));
  }

  public function store(Request $request, Dashboard $dashboard)
  {
    $this->authorize('manageComponents', $dashboard);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => ['required', Rule::in($this->componentService->getAvailableComponentTypes())],
      'settings' => 'required|json',
      'position' => 'nullable|integer|min:0',
      'size' => ['required', Rule::in(['small', 'medium', 'large'])],
      'refresh_interval' => 'nullable|integer|min:0',
      'data_source' => 'nullable|exists:data_sources,id',
      'visualization_type' => 'required|string',
      'custom_styles' => 'nullable|json',
      'is_public' => 'boolean'
    ]);

    try {
      DB::beginTransaction();

      // Create component
      $component = new DashboardComponent();
      $component->dashboard_id = $dashboard->id;
      $component->user_id = Auth::id();
      $component->name = $validated['name'];
      $component->type = $validated['type'];
      $component->settings = json_decode($validated['settings'], true);
      $component->position = $validated['position'] ?? $dashboard->components()->max('position') + 1;
      $component->size = $validated['size'];
      $component->refresh_interval = $validated['refresh_interval'];
      $component->data_source_id = $validated['data_source'];
      $component->visualization_type = $validated['visualization_type'];
      $component->custom_styles = json_decode($validated['custom_styles'] ?? '{}', true);
      $component->is_public = $validated['is_public'] ?? false;
      $component->is_enabled = true;
      $component->cache_duration = $request->input('cache_duration', 300);

      // Merge with default settings
      $component->settings = $this->componentService->mergeWithDefaultSettings(
        $validated['type'],
        $component->settings
      );

      $component->save();

      // Process any type-specific setup
      $handler = $this->componentService->getComponentTypeHandler($component->type);
      $handler->handleSetup($component);

      DB::commit();

      return redirect()
        ->route('dashboard-components.show', [$dashboard, $component])
        ->with('success', 'Component created successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->with('error', 'Failed to create component: ' . $e->getMessage());
    }
  }

  public function show(Dashboard $dashboard, DashboardComponent $component)
  {
    $this->authorize('view', $dashboard);

    $component->load('dataSource');

    // Fetch component data
    $data = $this->componentService->getComponentData($component);

    return view('dashboard-components.show', compact('dashboard', 'component', 'data'));
  }

  public function edit(Dashboard $dashboard, DashboardComponent $component)
  {
    $this->authorize('manageComponents', $dashboard);

    $dataSources = DataSource::active()->get();
    $types = $this->componentService->getAvailableComponentTypes();

    return view('dashboard-components.edit', compact('dashboard', 'component', 'dataSources', 'types'));
  }

  public function update(Request $request, Dashboard $dashboard, DashboardComponent $component)
  {
    $this->authorize('manageComponents', $dashboard);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => ['required', Rule::in($this->componentService->getAvailableComponentTypes())],
      'settings' => 'required|json',
      'position' => 'nullable|integer|min:0',
      'size' => ['required', Rule::in(['small', 'medium', 'large'])],
      'refresh_interval' => 'nullable|integer|min:0',
      'data_source' => 'nullable|exists:data_sources,id',
      'visualization_type' => 'required|string',
      'custom_styles' => 'nullable|json',
      'is_public' => 'boolean'
    ]);

    try {
      DB::beginTransaction();

      // Update component
      $component->name = $validated['name'];
      $component->type = $validated['type'];
      $component->settings = json_decode($validated['settings'], true);
      $component->position = $validated['position'] ?? $component->position;
      $component->size = $validated['size'];
      $component->refresh_interval = $validated['refresh_interval'];
      $component->data_source_id = $validated['data_source'];
      $component->visualization_type = $validated['visualization_type'];
      $component->custom_styles = json_decode($validated['custom_styles'] ?? '{}', true);
      $component->is_public = $validated['is_public'] ?? false;
      $component->cache_duration = $request->input('cache_duration', 300);

      // Merge with default settings
      $component->settings = $this->componentService->mergeWithDefaultSettings(
        $validated['type'],
        $component->settings
      );

      $component->save();

      // Clear component cache
      $this->componentService->clearComponentCache($component);

      // Process any type-specific updates
      $handler = $this->componentService->getComponentTypeHandler($component->type);
      $handler->handleUpdate($component);

      DB::commit();

      return redirect()
        ->route('dashboard-components.show', [$dashboard, $component])
        ->with('success', 'Component updated successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()
        ->withInput()
        ->with('error', 'Failed to update component: ' . $e->getMessage());
    }
  }

  public function destroy(Dashboard $dashboard, DashboardComponent $component)
  {
    $this->authorize('manageComponents', $dashboard);

    try {
      DB::beginTransaction();

      // Remove component shares
      $component->shares()->delete();

      // Delete the component
      $component->delete();

      // Reorder remaining components
      $this->reorderComponents($dashboard);

      DB::commit();

      return redirect()
        ->route('dashboards.show', $dashboard)
        ->with('success', 'Component deleted successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Failed to delete component: ' . $e->getMessage());
    }
  }

  public function refreshData(Dashboard $dashboard, DashboardComponent $component)
  {
    $this->authorize('view', $dashboard);

    try {
      // Clear component cache
      $this->componentService->clearComponentCache($component);

      // Fetch fresh data
      $data = $this->componentService->getComponentData($component, true);

      return response()->json([
        'success' => true,
        'data' => $data
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage()
      ], 500);
    }
  }

  protected function reorderComponents(Dashboard $dashboard)
  {
    $components = $dashboard->components()->orderBy('position')->get();
    foreach ($components as $index => $component) {
      $component->update(['position' => $index]);
    }
  }
}
