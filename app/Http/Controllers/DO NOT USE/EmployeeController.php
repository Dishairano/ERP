<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
  /**
   * Display the employee dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function dashboard()
  {
    $totalEmployees = Employee::count();
    $activeEmployees = Employee::where('status', 'active')->count();
    $onboardingEmployees = Employee::where('status', 'onboarding')->count();
    $offboardingEmployees = Employee::where('status', 'offboarding')->count();

    return view('employees.dashboard', compact(
      'totalEmployees',
      'activeEmployees',
      'onboardingEmployees',
      'offboardingEmployees'
    ));
  }

  /**
   * Display the employee directory.
   *
   * @return \Illuminate\View\View
   */
  public function directory()
  {
    $employees = Employee::with(['department', 'position'])->paginate(10);
    return view('employees.directory', compact('employees'));
  }

  /**
   * Display the onboarding list.
   *
   * @return \Illuminate\View\View
   */
  public function onboarding()
  {
    $onboardingEmployees = Employee::where('status', 'onboarding')
      ->with(['department', 'position'])
      ->paginate(10);
    return view('employees.onboarding', compact('onboardingEmployees'));
  }

  /**
   * Display the offboarding list.
   *
   * @return \Illuminate\View\View
   */
  public function offboarding()
  {
    $offboardingEmployees = Employee::where('status', 'offboarding')
      ->with(['department', 'position'])
      ->paginate(10);
    return view('employees.offboarding', compact('offboardingEmployees'));
  }

  /**
   * Show the form for creating a new employee.
   *
   * @return \Illuminate\View\View
   */
  public function create()
  {
    return view('employees.create');
  }

  /**
   * Store a newly created employee.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|email|unique:employees,email',
      'phone' => 'nullable|string|max:20',
      'department_id' => 'required|exists:departments,id',
      'position_id' => 'required|exists:positions,id',
      'start_date' => 'required|date',
      'status' => 'required|in:active,onboarding,offboarding,inactive'
    ]);

    Employee::create($validated);

    return redirect()->route('employees.directory')
      ->with('success', 'Employee created successfully.');
  }

  /**
   * Display the specified employee.
   *
   * @param  \App\Models\Employee  $employee
   * @return \Illuminate\View\View
   */
  public function show(Employee $employee)
  {
    return view('employees.show', compact('employee'));
  }

  /**
   * Show the form for editing the specified employee.
   *
   * @param  \App\Models\Employee  $employee
   * @return \Illuminate\View\View
   */
  public function edit(Employee $employee)
  {
    return view('employees.edit', compact('employee'));
  }

  /**
   * Update the specified employee.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Employee  $employee
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, Employee $employee)
  {
    $validated = $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|email|unique:employees,email,' . $employee->id,
      'phone' => 'nullable|string|max:20',
      'department_id' => 'required|exists:departments,id',
      'position_id' => 'required|exists:positions,id',
      'start_date' => 'required|date',
      'status' => 'required|in:active,onboarding,offboarding,inactive'
    ]);

    $employee->update($validated);

    return redirect()->route('employees.directory')
      ->with('success', 'Employee updated successfully.');
  }

  /**
   * Remove the specified employee.
   *
   * @param  \App\Models\Employee  $employee
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Employee $employee)
  {
    $employee->delete();

    return redirect()->route('employees.directory')
      ->with('success', 'Employee deleted successfully.');
  }
}
