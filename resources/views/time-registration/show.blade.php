@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Time Registration Details</h5>
                        <div>
                            @if ($registration->status === 'draft')
                                <a href="{{ route('time-registration.edit', $registration) }}" class="btn btn-primary me-2">
                                    <i class="ri-pencil-line me-1"></i> Edit
                                </a>
                            @endif
                            <a href="{{ route('time-registration.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Status Badge -->
                            <div class="col-12 mb-4">
                                @php
                                    $statusClass = match ($registration->status) {
                                        'draft' => 'secondary',
                                        'submitted' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'primary',
                                    };
                                @endphp
                                <div class="badge bg-label-{{ $statusClass }} p-2">
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    {{ ucfirst($registration->status) }}
                                </div>
                            </div>

                            <!-- Basic Information -->
                            <div class="col-md-6 mb-4">
                                <h6 class="text-muted mb-3">Basic Information</h6>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Project</label>
                                        <p class="h6">{{ $registration->project->name }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Task</label>
                                        <p class="h6">{{ $registration->task->name }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Description</label>
                                        <p>{{ $registration->description }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Time Details -->
                            <div class="col-md-6 mb-4">
                                <h6 class="text-muted mb-3">Time Details</h6>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label text-muted">Date</label>
                                        <p class="h6">{{ $registration->date->format('M d, Y') }}</p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label text-muted">Hours</label>
                                        <p class="h6">{{ number_format($registration->hours, 1) }}</p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label text-muted">Billable</label>
                                        <p>
                                            @if ($registration->billable)
                                                <span class="badge bg-label-success">Yes</span>
                                            @else
                                                <span class="badge bg-label-secondary">No</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label text-muted">Overtime</label>
                                        <p>
                                            @if ($registration->overtime)
                                                <span class="badge bg-label-warning">Yes</span>
                                            @else
                                                <span class="badge bg-label-secondary">No</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-12">
                                <div class="card bg-label-secondary">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label text-muted">Registered By</label>
                                                <p class="mb-0">{{ $registration->user->name }}</p>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label text-muted">Created At</label>
                                                <p class="mb-0">{{ $registration->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label text-muted">Last Updated</label>
                                                <p class="mb-0">{{ $registration->updated_at->format('M d, Y H:i') }}</p>
                                            </div>
                                            @if ($registration->status === 'rejected')
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label text-muted">Rejection Reason</label>
                                                    <p class="mb-0 text-danger">{{ $registration->rejection_reason }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4">
                            @if ($registration->status === 'draft')
                                <form action="{{ route('time-registration.submit', $registration) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="ri-send-plane-line me-1"></i> Submit for Approval
                                    </button>
                                </form>
                                <form action="{{ route('time-registration.destroy', $registration) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this time registration?')">
                                        <i class="ri-delete-bin-line me-1"></i> Delete
                                    </button>
                                </form>
                            @elseif($registration->status === 'submitted' && auth()->user()->can('approve-time'))
                                <form action="{{ route('time-registration.approve', $registration) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success me-2">
                                        <i class="ri-check-line me-1"></i> Approve
                                    </button>
                                </form>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">
                                    <i class="ri-close-line me-1"></i> Reject
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    @if ($registration->status === 'submitted' && auth()->user()->can('approve-time'))
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('time-registration.reject', $registration) }}" method="POST">
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
                            <button type="button" class="btn btn-label-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
