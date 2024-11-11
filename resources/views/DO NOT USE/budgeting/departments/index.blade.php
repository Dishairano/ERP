@extends('layouts/contentNavbarLayout')

@section('title', 'Department Budgets')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Budgets /</span> Departments
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Department Budgets</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBudgetModal">
                    <i class="bx bx-plus me-1"></i> Add Budget
                </button>
            </div>
            <div class="card-body">
                @if ($departments->isEmpty())
                    <div class="text-center p-5">
                        <h6 class="text-muted">No department budgets found</h6>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Budget Amount</th>
                                    <th>Fiscal Year</th>
                                    <th>Spent</th>
                                    <th>Remaining</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departments as $department)
                                    <tr>
                                        <td>{{ $department->name }}</td>
                                        <td>{{ number_format($department->budget->amount ?? 0, 2) }}</td>
                                        <td>{{ $department->budget->fiscal_year ?? date('Y') }}</td>
                                        <td>{{ number_format($department->budget->spent ?? 0, 2) }}</td>
                                        <td>{{ number_format($department->budget->remaining ?? 0, 2) }}</td>
                                        <td>
                                            @if (isset($department->budget))
                                                @if ($department->budget->remaining > $department->budget->amount * 0.25)
                                                    <span class="badge bg-success">Healthy</span>
                                                @elseif($department->budget->remaining > 0)
                                                    <span class="badge bg-warning">Low</span>
                                                @else
                                                    <span class="badge bg-danger">Depleted</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">No Budget</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('budgets.departments.show', $department->id) }}">
                                                        <i class="bx bx-show-alt me-1"></i> View Details
                                                    </a>
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editBudgetModal{{ $department->id }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Edit Budget
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Budget Modal -->
    <div class="modal fade" id="createBudgetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('budgets.departments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department_id" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control" name="amount" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fiscal Year</label>
                            <input type="number" class="form-control" name="fiscal_year" value="{{ date('Y') }}"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Budget</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($departments as $department)
        <!-- Edit Budget Modal -->
        <div class="modal fade" id="editBudgetModal{{ $department->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Department Budget</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('budgets.departments.update', $department->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" value="{{ $department->name }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input type="number" class="form-control" name="amount" step="0.01"
                                    value="{{ $department->budget->amount ?? 0 }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Fiscal Year</label>
                                <input type="number" class="form-control" name="fiscal_year"
                                    value="{{ $department->budget->fiscal_year ?? date('Y') }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Budget</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
