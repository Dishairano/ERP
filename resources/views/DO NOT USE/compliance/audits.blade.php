@extends('layouts/contentNavbarLayout')

@section('title', 'Compliance Audits')

@section('content')
    <h4 class="fw-bold">Compliance Audits</h4>

    <!-- Create Audit Button -->
    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#createAuditModal">
        <i class="ri-add-line me-1"></i> Schedule New Audit
    </button>

    <!-- Audits List -->
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Auditor</th>
                        <th>Audit Date</th>
                        <th>Status</th>
                        <th>Findings</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audits as $audit)
                        <tr>
                            <td>{{ $audit->title }}</td>
                            <td>{{ $audit->auditor }}</td>
                            <td>{{ $audit->audit_date->format('Y-m-d') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $audit->status === 'completed' ? 'success' : ($audit->status === 'in-progress' ? 'info' : 'warning') }}">
                                    {{ ucfirst($audit->status) }}
                                </span>
                            </td>
                            <td>{{ Str::limit($audit->findings, 50) }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#editAuditModal{{ $audit->id }}">
                                            <i class="ri-pencil-line me-2"></i> Edit
                                        </button>
                                        <form action="{{ route('compliance.audits.destroy', $audit->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"
                                                onclick="return confirm('Are you sure you want to delete this audit?')">
                                                <i class="ri-delete-bin-line me-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Audit Modal -->
                        <div class="modal fade" id="editAuditModal{{ $audit->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Audit</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('compliance.audits.update', $audit->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Title</label>
                                                <input type="text" name="title" class="form-control"
                                                    value="{{ $audit->title }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="3" required>{{ $audit->description }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Auditor</label>
                                                <input type="text" name="auditor" class="form-control"
                                                    value="{{ $audit->auditor }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Audit Date</label>
                                                <input type="date" name="audit_date" class="form-control"
                                                    value="{{ $audit->audit_date->format('Y-m-d') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="pending"
                                                        {{ $audit->status === 'pending' ? 'selected' : '' }}>Pending
                                                    </option>
                                                    <option value="in-progress"
                                                        {{ $audit->status === 'in-progress' ? 'selected' : '' }}>In
                                                        Progress</option>
                                                    <option value="completed"
                                                        {{ $audit->status === 'completed' ? 'selected' : '' }}>Completed
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Findings</label>
                                                <textarea name="findings" class="form-control" rows="3">{{ $audit->findings }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Recommendations</label>
                                                <textarea name="recommendations" class="form-control" rows="3">{{ $audit->recommendations }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update Audit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No audits found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Audit Modal -->
    <div class="modal fade" id="createAuditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule New Audit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('compliance.audits.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Auditor</label>
                            <input type="text" name="auditor" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Audit Date</label>
                            <input type="date" name="audit_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="in-progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Schedule Audit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
