<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\PayrollComponent;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
  /**
   * Display payroll processing page.
   *
   * @return \Illuminate\View\View
   */
  public function processing()
  {
    $payrolls = Payroll::with(['employee', 'components'])
      ->latest()
      ->paginate(10);

    return view('payroll.processing', compact('payrolls'));
  }

  /**
   * Display salary management page.
   *
   * @return \Illuminate\View\View
   */
  public function salary()
  {
    $employees = Employee::with(['position', 'department'])
      ->paginate(10);

    return view('payroll.salary', compact('employees'));
  }

  /**
   * Display benefits management page.
   *
   * @return \Illuminate\View\View
   */
  public function benefits()
  {
    $employees = Employee::with(['benefits'])
      ->paginate(10);

    return view('payroll.benefits', compact('employees'));
  }

  /**
   * Display tax management page.
   *
   * @return \Illuminate\View\View
   */
  public function taxes()
  {
    $employees = Employee::with(['taxInformation'])
      ->paginate(10);

    return view('payroll.taxes', compact('employees'));
  }

  /**
   * Display payroll reports.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\View\View
   */
  public function reports(Request $request)
  {
    $startDate = $request->input('start_date', now()->startOfMonth());
    $endDate = $request->input('end_date', now()->endOfMonth());

    $payrolls = Payroll::whereBetween('period', [$startDate, $endDate])
      ->with(['employee', 'components'])
      ->get();

    return view('payroll.reports', compact('payrolls', 'startDate', 'endDate'));
  }

  /**
   * Process payroll for a specific period.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function process(Request $request)
  {
    $validated = $request->validate([
      'period' => 'required|date',
      'employee_ids' => 'required|array',
      'employee_ids.*' => 'exists:employees,id'
    ]);

    foreach ($validated['employee_ids'] as $employeeId) {
      $employee = Employee::find($employeeId);

      // Calculate base salary
      $baseSalary = $employee->position->salary_range_min;

      // Create payroll record
      $payroll = Payroll::create([
        'employee_id' => $employeeId,
        'period' => $validated['period'],
        'base_salary' => $baseSalary,
        'status' => 'draft'
      ]);

      // Add payroll components (allowances, deductions, etc.)
      $this->calculatePayrollComponents($payroll);
    }

    return redirect()->route('payroll.processing')
      ->with('success', 'Payroll processed successfully.');
  }

  /**
   * Calculate payroll components.
   *
   * @param  \App\Models\Payroll  $payroll
   * @return void
   */
  private function calculatePayrollComponents(Payroll $payroll)
  {
    // Calculate allowances
    $allowances = $this->calculateAllowances($payroll->employee);
    foreach ($allowances as $name => $amount) {
      PayrollComponent::create([
        'payroll_id' => $payroll->id,
        'name' => $name,
        'type' => 'allowance',
        'amount' => $amount
      ]);
    }

    // Calculate deductions
    $deductions = $this->calculateDeductions($payroll->employee);
    foreach ($deductions as $name => $amount) {
      PayrollComponent::create([
        'payroll_id' => $payroll->id,
        'name' => $name,
        'type' => 'deduction',
        'amount' => $amount
      ]);
    }

    // Update total amounts
    $payroll->update([
      'total_allowances' => collect($allowances)->sum(),
      'total_deductions' => collect($deductions)->sum(),
      'net_salary' => $payroll->base_salary + collect($allowances)->sum() - collect($deductions)->sum()
    ]);
  }

  /**
   * Calculate employee allowances.
   *
   * @param  \App\Models\Employee  $employee
   * @return array
   */
  private function calculateAllowances($employee)
  {
    return [
      'Housing' => $employee->position->housing_allowance ?? 0,
      'Transport' => $employee->position->transport_allowance ?? 0,
      'Meal' => $employee->position->meal_allowance ?? 0
    ];
  }

  /**
   * Calculate employee deductions.
   *
   * @param  \App\Models\Employee  $employee
   * @return array
   */
  private function calculateDeductions($employee)
  {
    return [
      'Tax' => $this->calculateTax($employee),
      'Insurance' => $this->calculateInsurance($employee),
      'Pension' => $this->calculatePension($employee)
    ];
  }

  /**
   * Calculate employee tax.
   *
   * @param  \App\Models\Employee  $employee
   * @return float
   */
  private function calculateTax($employee)
  {
    // Implement tax calculation logic
    return 0;
  }

  /**
   * Calculate employee insurance.
   *
   * @param  \App\Models\Employee  $employee
   * @return float
   */
  private function calculateInsurance($employee)
  {
    // Implement insurance calculation logic
    return 0;
  }

  /**
   * Calculate employee pension.
   *
   * @param  \App\Models\Employee  $employee
   * @return float
   */
  private function calculatePension($employee)
  {
    // Implement pension calculation logic
    return 0;
  }
}
