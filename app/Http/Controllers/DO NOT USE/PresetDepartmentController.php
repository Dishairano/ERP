<?php

namespace App\Http\Controllers;

use App\Models\PresetDepartment;
use Illuminate\Http\Request;

class PresetDepartmentController extends Controller
{
  public function index()
  {
    $departments = PresetDepartment::orderBy('name')->paginate(10);
    return view('preset-departments.index', compact('departments'));
  }

  public function create()
  {
    return view('preset-departments.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|unique:preset_departments,name',
      'description' => 'nullable|string',
      'is_active' => 'boolean'
    ]);

    PresetDepartment::create($validated);

    return redirect()->route('preset-departments.index')
      ->with('success', 'Department preset created successfully');
  }

  public function edit(PresetDepartment $presetDepartment)
  {
    return view('preset-departments.edit', compact('presetDepartment'));
  }

  public function update(Request $request, PresetDepartment $presetDepartment)
  {
    $validated = $request->validate([
      'name' => 'required|string|unique:preset_departments,name,' . $presetDepartment->id,
      'description' => 'nullable|string',
      'is_active' => 'boolean'
    ]);

    $presetDepartment->update($validated);

    return redirect()->route('preset-departments.index')
      ->with('success', 'Department preset updated successfully');
  }

  public function destroy(PresetDepartment $presetDepartment)
  {
    $presetDepartment->delete();

    return redirect()->route('preset-departments.index')
      ->with('success', 'Department preset deleted successfully');
  }

  public function getActive()
  {
    $departments = PresetDepartment::active()->orderBy('name')->get();
    return response()->json($departments);
  }
}
