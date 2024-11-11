@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - Payroll')

@section('content')
    <h4 class="fw-bold">Payroll Management</h4>
    <a href="{{ route('hrm.payroll.create') }}" class="btn btn-primary mb-4">Create New Payroll</a>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Salary</th>
                        <th>Bonus</th>
                        <th>Deductions</th>
                        <th>Payment Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->user->name }}</td>
                            <td>${{ number_format($payroll->salary, 2) }}</td>
                            <td>${{ number_format($payroll->bonus ?? 0, 2) }}</td>
                            <td>${{ number_format($payroll->deductions ?? 0, 2) }}</td>
                            <td>{{ $payroll->payment_date->format('Y-m-d') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $payroll->status === 'paid' ? 'success' : ($payroll->status === 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('hrm.payroll.show', $payroll->id) }}">
                                            <i class="ri-eye-line me-2"></i> View
                                        </a>
                                        <a class="dropdown-item" href="{{ route('hrm.payroll.edit', $payroll->id) }}">
                                            <i class="ri-pencil-line me-2"></i> Edit
                                        </a>
                                        <form action="{{ route('hrm.payroll.destroy', $payroll->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"
                                                onclick="return confirm('Are you sure you want to delete this payroll record?')">
                                                <i class="ri-delete-bin-line me-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
