<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
  public function index()
  {
    $payrolls = Payroll::with('user')->get();
    return view('hrm.payroll.index', compact('payrolls'));
  }

  public function create()
  {
    $employees = User::all();
    return view('hrm.payroll.create', compact('employees'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'user_id' => 'required|exists:users,id',
      'salary' => 'required|numeric|min:0',
      'bonus' => 'nullable|numeric|min:0',
      'deductions' => 'nullable|numeric|min:0',
      'payment_date' => 'required|date',
      'payment_period' => 'required|string',
      'status' => 'required|string|in:pending,processed,paid'
    ]);

    Payroll::create($validated);

    return redirect()->route('hrm.payroll')->with('success', 'Payroll record created successfully');
  }

  public function show($id)
  {
    $payroll = Payroll::with('user')->findOrFail($id);
    return view('hrm.payroll.show', compact('payroll'));
  }

  public function edit($id)
  {
    $payroll = Payroll::findOrFail($id);
    $employees = User::all();
    return view('hrm.payroll.edit', compact('payroll', 'employees'));
  }

  public function update(Request $request, $id)
  {
    $payroll = Payroll::findOrFail($id);

    $validated = $request->validate([
      'user_id' => 'required|exists:users,id',
      'salary' => 'required|numeric|min:0',
      'bonus' => 'nullable|numeric|min:0',
      'deductions' => 'nullable|numeric|min:0',
      'payment_date' => 'required|date',
      'payment_period' => 'required|string',
      'status' => 'required|string|in:pending,processed,paid'
    ]);

    $payroll->update($validated);

    return redirect()->route('hrm.payroll')->with('success', 'Payroll record updated successfully');
  }

  public function destroy($id)
  {
    $payroll = Payroll::findOrFail($id);
    $payroll->delete();

    return redirect()->route('hrm.payroll')->with('success', 'Payroll record deleted successfully');
  }
}
