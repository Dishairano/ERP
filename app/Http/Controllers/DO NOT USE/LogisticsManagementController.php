<?php

namespace App\Http\Controllers;

use App\Models\LogisticsManagement;
use Illuminate\Http\Request;

class LogisticsManagementController extends Controller
{
  public function index()
  {
    $logistics = LogisticsManagement::latest()->paginate(10);
    return view('logistics.index', compact('logistics'));
  }

  public function create()
  {
    return view('logistics.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'shipment_number' => 'required|string|max:255|unique:logistics_management',
      'origin' => 'required|string|max:255',
      'destination' => 'required|string|max:255',
      'status' => 'required|string',
      'estimated_delivery_date' => 'required|date',
      'actual_delivery_date' => 'nullable|date',
      'tracking_number' => 'nullable|string|max:255',
      'carrier' => 'required|string|max:255',
      'notes' => 'nullable|string',
    ]);

    LogisticsManagement::create($validated);

    return redirect()->route('logistics.index')
      ->with('success', 'Shipment created successfully.');
  }

  public function show($id)
  {
    $logistics = LogisticsManagement::findOrFail($id);
    return view('logistics.show', compact('logistics'));
  }

  public function edit($id)
  {
    $logistics = LogisticsManagement::findOrFail($id);
    return view('logistics.edit', compact('logistics'));
  }

  public function update(Request $request, $id)
  {
    $logistics = LogisticsManagement::findOrFail($id);

    $validated = $request->validate([
      'shipment_number' => 'required|string|max:255|unique:logistics_management,shipment_number,' . $id,
      'origin' => 'required|string|max:255',
      'destination' => 'required|string|max:255',
      'status' => 'required|string',
      'estimated_delivery_date' => 'required|date',
      'actual_delivery_date' => 'nullable|date',
      'tracking_number' => 'nullable|string|max:255',
      'carrier' => 'required|string|max:255',
      'notes' => 'nullable|string',
    ]);

    $logistics->update($validated);

    return redirect()->route('logistics.index')
      ->with('success', 'Shipment updated successfully.');
  }

  public function destroy($id)
  {
    $logistics = LogisticsManagement::findOrFail($id);
    $logistics->delete();

    return redirect()->route('logistics.index')
      ->with('success', 'Shipment deleted successfully.');
  }
}
