@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Employee /</span> Directory
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Employee Directory</h5>
                <a href="{{ route('employees.create') }}" class="btn btn-primary">
                    <i class="ri-user-add-line"></i> Add Employee
                </a>
            </div>
            <div class="card-body">
                <!-- Search and Filters -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" class="form-control" placeholder="Search employees...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-2">
                            <select class="form-select w-auto">
                                <option value="">All Departments</option>
                                @foreach ($departments ?? [] as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            <select class="form-select w-auto">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="onboarding">Onboarding</option>
                                <option value="offboarding">Offboarding</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Employee List -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td>{{ $employee->full_name }}</td>
                                    <td>{{ $employee->department->name }}</td>
                                    <td>{{ $employee->position->name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>
                                        <span
                                            class="badge bg-label-{{ $employee->status === 'active'
                                                ? 'success'
                                                : ($employee->status === 'onboarding'
                                                    ? 'info'
                                                    : ($employee->status === 'offboarding'
                                                        ? 'warning'
                                                        : 'secondary')) }}">
                                            {{ ucfirst($employee->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="ri-more-fill"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('employees.show', $employee) }}">
                                                    <i class="ri-eye-line me-1"></i> View
                                                </a>
                                                <a class="dropdown-item" href="{{ route('employees.edit', $employee) }}">
                                                    <i class="ri-pencil-line me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this employee?')">
                                                        <i class="ri-delete-bin-line me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No employees found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
