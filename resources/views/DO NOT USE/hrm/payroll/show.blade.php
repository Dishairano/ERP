@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - View Payroll')

@section('content')
    <h4 class="fw-bold">Payroll Record Details</h4>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6 class="text-muted">Employee</h6>
                    <p class="fs-5">{{ $payroll->user->name }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="text-muted">Payment Date</h6>
                    <p class="fs-5">{{ $payroll->payment_date->format('Y-m-d') }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="text-muted">Salary</h6>
                    <p class="fs-5">${{ number_format($payroll->salary, 2) }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="text-muted">Bonus</h6>
                    <p class="fs-5">${{ number_format($payroll->bonus ?? 0, 2) }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="text-muted">Deductions</h6>
                    <p class="fs-5">${{ number_format($payroll->deductions ?? 0, 2) }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="text-muted">Net Pay</h6>
                    <p class="fs-5">
                        ${{ number_format($payroll->salary + ($payroll->bonus ?? 0) - ($payroll->deductions ?? 0), 2) }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="text-muted">Payment Period</h6>
                    <p class="fs-5">{{ $payroll->payment_period }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="text-muted">Status</h6>
                    <p>
                        <span
                            class="badge bg-{{ $payroll->status === 'paid' ? 'success' : ($payroll->status === 'pending' ? 'warning' : 'info') }} fs-6">
                            {{ ucfirst($payroll->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('hrm.payroll.edit', $payroll->id) }}" class="btn btn-primary">Edit Record</a>
                <a href="{{ route('hrm.payroll') }}" class="btn btn-secondary">Back to List</a>
                <form action="{{ route('hrm.payroll.destroy', $payroll->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this payroll record?')">
                        Delete Record
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
