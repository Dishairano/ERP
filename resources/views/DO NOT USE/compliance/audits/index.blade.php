@extends('layouts/contentNavbarLayout')

@section('title', 'Compliance Audits')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4">Compliance Audits</h4>
            <a href="{{ route('compliance.audits.create') }}" class="btn btn-primary">Schedule New Audit</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Audit Type</th>
                                <th>Status</th>
                                <th>Scheduled Date</th>
                                <th>Auditor</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audits as $audit)
                                <tr>
                                    <td>{{ $audit->audit_type }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $audit->status === 'completed' ? 'success' : ($audit->status === 'in_progress' ? 'warning' : 'info') }}">
                                            {{ $audit->status }}
                                        </span>
                                    </td>
                                    <td>{{ $audit->scheduled_date->format('Y-m-d') }}</td>
                                    <td>{{ $audit->auditor_name }}</td>
                                    <td>{{ $audit->department }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('compliance.audits.show', $audit) }}">
                                                    <i class="bx bx-show-alt me-1"></i> View
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('compliance.audits.edit', $audit) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('compliance.audits.destroy', $audit) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this audit?')">
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
                    {{ $audits->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
