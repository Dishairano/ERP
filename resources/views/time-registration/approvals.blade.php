@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Approvals')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Time Registration Approvals</h5>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Filter by Employee</label>
                        <select class="form-select" id="employee_filter">
                            <option value="">All Employees</option>
                            @foreach ($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Filter by Project</label>
                        <select class="form-select" id="project_filter">
                            <option value="">All Projects</option>
                            @foreach ($projects ?? [] as $project)
                                <option value="{{ $project->id }}"
                                    {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" onclick="applyFilters()">
                            <i class="ri-filter-3-line me-1"></i> Apply Filters
                        </button>
                    </div>
                </div>

                <!-- Pending Approvals Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Task</th>
                                <th>Hours</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingApprovals as $registration)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input registration-checkbox" type="checkbox"
                                                value="{{ $registration->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ substr($registration->user->name, 0, 2) }}
                                                </span>
                                            </div>
                                            <div>{{ $registration->user->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $registration->date->format('M d, Y') }}</td>
                                    <td>{{ $registration->project->name }}</td>
                                    <td>{{ $registration->task->name }}</td>
                                    <td>{{ number_format($registration->hours, 1) }}</td>
                                    <td>
                                        <span class="badge bg-label-warning">Pending</span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('time-registration.show', $registration) }}">
                                                    <i class="ri-eye-line me-1"></i> View Details
                                                </a>
                                                <a class="dropdown-item" href="#"
                                                    onclick="approveRegistration({{ $registration->id }})">
                                                    <i class="ri-check-line me-1"></i> Approve
                                                </a>
                                                <a class="dropdown-item text-danger" href="#"
                                                    onclick="showRejectModal({{ $registration->id }})">
                                                    <i class="ri-close-line me-1"></i> Reject
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No pending approvals found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bulk Actions -->
                @if ($pendingApprovals->isNotEmpty())
                    <div class="mt-3">
                        <button type="button" class="btn btn-success me-2" onclick="approveBulk()">
                            <i class="ri-check-line me-1"></i> Approve Selected
                        </button>
                        <button type="button" class="btn btn-danger" onclick="showBulkRejectModal()">
                            <i class="ri-close-line me-1"></i> Reject Selected
                        </button>
                    </div>
                @endif

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $pendingApprovals->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="rejectForm" action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Time Registration</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="rejection_reason">Rejection Reason</label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all checkbox functionality
            document.getElementById('selectAll').addEventListener('change', function() {
                document.querySelectorAll('.registration-checkbox').forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });

        function applyFilters() {
            const employeeId = document.getElementById('employee_filter').value;
            const projectId = document.getElementById('project_filter').value;

            const params = new URLSearchParams();
            if (employeeId) params.append('user_id', employeeId);
            if (projectId) params.append('project_id', projectId);

            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        function approveRegistration(id) {
            if (confirm('Are you sure you want to approve this time registration?')) {
                fetch(`/time-registration/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        }

        function showRejectModal(id) {
            const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
            document.getElementById('rejectForm').action = `/time-registration/${id}/reject`;
            modal.show();
        }

        function approveBulk() {
            const selectedIds = Array.from(document.querySelectorAll('.registration-checkbox:checked'))
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one time registration to approve.');
                return;
            }

            if (confirm(`Are you sure you want to approve ${selectedIds.length} time registrations?`)) {
                fetch('/time-registration/bulk-approve', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ids: selectedIds
                    })
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        }

        function showBulkRejectModal() {
            const selectedIds = Array.from(document.querySelectorAll('.registration-checkbox:checked'))
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one time registration to reject.');
                return;
            }

            const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
            document.getElementById('rejectForm').action = '/time-registration/bulk-reject';
            modal.show();
        }
    </script>
@endsection
