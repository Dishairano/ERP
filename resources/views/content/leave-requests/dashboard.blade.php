@extends('layouts/contentNavbarLayout')

@section('title', 'Leave Requests Dashboard')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Recent Requests -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Leave Requests</h5>
                    <div>
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i> New Request
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRequests as $request)
                                    <tr>
                                        <td>{{ $request->leaveType->name }}</td>
                                        <td>{{ $request->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $request->end_date->format('Y-m-d') }}</td>
                                        <td>{{ $request->days }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : ($request->status === 'submitted' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('leave-requests.show', $request) }}"
                                                class="btn btn-sm btn-icon btn-label-primary me-1">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            @if($request->canEdit())
                                            <a href="{{ route('leave-requests.edit', $request) }}"
                                                class="btn btn-sm btn-icon btn-label-warning me-1">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No recent leave requests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        @if(count($pendingApprovals) > 0)
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
                                    <th>Employee</th>
                                    <th>Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingApprovals as $request)
                                    <tr>
                                        <td>{{ $request->user->name }}</td>
                                        <td>{{ $request->leaveType->name }}</td>
                                        <td>{{ $request->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $request->end_date->format('Y-m-d') }}</td>
                                        <td>{{ $request->days }}</td>
                                        <td>
                                            <a href="{{ route('leave-requests.show', $request) }}"
                                                class="btn btn-sm btn-icon btn-label-primary me-1">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            @if($request->canApprove())
                                            <form action="{{ route('leave-requests.approve', $request) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-icon btn-label-success me-1">
                                                    <i class="ri-check-line"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-icon btn-label-danger"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                                <i class="ri-close-line"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form class="modal-content" action="{{ route('leave-requests.reject', $request) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Leave Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Rejection Reason</label>
                                                        <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
