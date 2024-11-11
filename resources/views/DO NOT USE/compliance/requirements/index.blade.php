@extends('layouts/contentNavbarLayout')

@section('title', 'Compliance Requirements')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4">Compliance Requirements</h4>
            <a href="{{ route('compliance.requirements.create') }}" class="btn btn-primary">Add New Requirement</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Regulation Type</th>
                                <th>Status</th>
                                <th>Effective Date</th>
                                <th>Risk Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requirements as $requirement)
                                <tr>
                                    <td>{{ $requirement->title }}</td>
                                    <td>{{ $requirement->regulation_type }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $requirement->status === 'active' ? 'success' : 'warning' }}">
                                            {{ $requirement->status }}
                                        </span>
                                    </td>
                                    <td>{{ $requirement->effective_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $requirement->risk_level === 'high' ? 'danger' : ($requirement->risk_level === 'medium' ? 'warning' : 'info') }}">
                                            {{ $requirement->risk_level }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('compliance.requirements.show', $requirement) }}">
                                                    <i class="bx bx-show-alt me-1"></i> View
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('compliance.requirements.edit', $requirement) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('compliance.requirements.destroy', $requirement) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this requirement?')">
                                                        <i class="bx bx-trash me-1"></i> Delete
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
                <div class="mt-3">
                    {{ $requirements->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
