<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HrmController extends Controller
{
  public function index()
  {
    return view('hrm.index');
  }

  public function employees()
  {
    return view('hrm.employees');
  }

  public function departments()
  {
    return view('hrm.departments');
  }

  public function positions()
  {
    return view('hrm.positions');
  }

  public function attendance()
  {
    return view('hrm.attendance');
  }

  public function staffManagement()
  {
    $staff = User::all();
    return view('hrm.staff-management.index', compact('staff'));
  }

  public function createStaff()
  {
    return view('hrm.staff-management.create');
  }

  public function storeStaff(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8',
    ]);

    User::create($validated);

    return redirect()->route('hrm.staff-management')->with('success', 'Staff member created successfully');
  }

  public function editStaff($id)
  {
    $staff = User::findOrFail($id);
    return view('hrm.staff-management.edit', compact('staff'));
  }

  public function updateStaff(Request $request, $id)
  {
    $staff = User::findOrFail($id);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email,' . $id,
    ]);

    $staff->update($validated);

    return redirect()->route('hrm.staff-management')->with('success', 'Staff member updated successfully');
  }

  public function destroyStaff($id)
  {
    $staff = User::findOrFail($id);
    $staff->delete();

    return redirect()->route('hrm.staff-management')->with('success', 'Staff member deleted successfully');
  }
}
