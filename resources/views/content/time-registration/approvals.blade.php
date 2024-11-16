@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Approvals')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pending Approvals</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
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
                                        <td>{{ $registration->date->format('Y-m-d') }}</td>
                                        <td>{{ optional($registration->user)->name }}</td>
                                        <td>{{ optional($registration->project)->name }}</td>
                                        <td>{{ optional($registration->task)->title }}</td>
                                        <td>{{ number_format($registration->hours, 2) }}</td>
                                        <td>
                                            <span class="badge bg-warning">Pending Approval</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('time-registration.show', ['time_registration' => $registration]) }}"
                                                    class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-success me-1"
                                                    onclick="approveRegistration({{ $registration->id }})">
                                                    <i class="ri-check-line"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="showRejectModal({{ $registration->id }})">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>

                                            <!-- Hidden Forms -->
                                            <form id="approve-form-{{ $registration->id }}"
                                                action="{{ route('time-registration.approve', ['time_registration' => $registration]) }}"
                                                method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No pending approvals found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $pendingApprovals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Time Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reject-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection</label>
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
    function approveRegistration(id) {
        if (confirm('Are you sure you want to approve this time registration?')) {
            document.getElementById('approve-form-' + id).submit();
        }
    }

    function showRejectModal(id) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('reject-form');
        form.action = `/time-registration/${id}/reject`;

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
</script>
@endsection
