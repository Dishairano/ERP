@extends('layouts/contentNavbarLayout')

@section('title', 'Leave Request Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group">
                <a href="{{ route('leave-management.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="{{ route('leave-requests.index') }}" class="btn btn-info">
                    <i class="fas fa-list"></i> All Requests
                </a>
                @if($leaveRequest->status === 'draft')
                    @can('update', $leaveRequest)
                        <a href="{{ route('leave-requests.edit', $leaveRequest) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Request
                        </a>
                    @endcan
                    @can('submit', $leaveRequest)
                        <form action="{{ route('leave-requests.submit', $leaveRequest) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane"></i> Submit Request
                            </button>
                        </form>
                    @endcan
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leave Request Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl>
                                <dt>Leave Type</dt>
                                <dd>{{ $leaveRequest->leaveType->name }}</dd>

                                <dt>Status</dt>
                                <dd>
                                    <span class="badge badge-{{ match($leaveRequest->status) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'submitted' => 'warning',
                                        default => 'secondary'
                                    } }}">
                                        {{ ucfirst($leaveRequest->status) }}
                                    </span>
                                </dd>

                                <dt>Start Date</dt>
                                <dd>{{ $leaveRequest->start_date->format('Y-m-d') }}</dd>

                                <dt>End Date</dt>
                                <dd>{{ $leaveRequest->end_date->format('Y-m-d') }}</dd>

                                <dt>Total Days</dt>
                                <dd>{{ $leaveRequest->total_days }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl>
                                <dt>Requested By</dt>
                                <dd>{{ $leaveRequest->user->name }}</dd>

                                <dt>Created At</dt>
                                <dd>{{ $leaveRequest->created_at->format('Y-m-d H:i') }}</dd>

                                <dt>Last Updated</dt>
                                <dd>{{ $leaveRequest->updated_at->format('Y-m-d H:i') }}</dd>

                                @if($leaveRequest->approved_by)
                                    <dt>{{ $leaveRequest->status === 'approved' ? 'Approved' : 'Rejected' }} By</dt>
                                    <dd>{{ $leaveRequest->approver->name }}</dd>

                                    <dt>{{ $leaveRequest->status === 'approved' ? 'Approval' : 'Rejection' }} Date</dt>
                                    <dd>{{ $leaveRequest->approved_at->format('Y-m-d H:i') }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Reason</h5>
                            <p>{{ $leaveRequest->reason ?: 'No reason provided.' }}</p>

                            @if($leaveRequest->status === 'rejected' && $leaveRequest->rejection_reason)
                                <h5>Rejection Reason</h5>
                                <p>{{ $leaveRequest->rejection_reason }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @if($leaveRequest->status === 'submitted')
                    @can('approve', $leaveRequest)
                        <div class="card-footer">
                            <div class="btn-group">
                                <form action="{{ route('leave-requests.approve', $leaveRequest) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </div>
                        </div>
                    @endcan
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leave Balance</h3>
                </div>
                <div class="card-body">
                    @if($leaveBalance)
                        <dl>
                            <dt>Total Days</dt>
                            <dd>{{ $leaveBalance->total_days }}</dd>

                            <dt>Used Days</dt>
                            <dd>{{ $leaveBalance->used_days }}</dd>

                            <dt>Pending Days</dt>
                            <dd>{{ $leaveBalance->pending_days }}</dd>

                            <dt>Remaining Days</dt>
                            <dd>
                                <span class="badge badge-{{ $leaveBalance->remaining_days > 0 ? 'success' : 'danger' }}">
                                    {{ $leaveBalance->remaining_days }}
                                </span>
                            </dd>
                        </dl>
                    @else
                        <p class="text-muted">No balance information available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($leaveRequest->status === 'submitted')
    @can('approve', $leaveRequest)
        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('leave-requests.reject', $leaveRequest) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModalLabel">Reject Leave Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="rejection_reason">Rejection Reason</label>
                                <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endif
@endsection

@section('scripts')
<script>
    // Add any JavaScript needed for the page here
</script>
@endsection
