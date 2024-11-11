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
                            @if ($timeRegistration->isEditable())
                                <a href="{{ route('time-registrations.edit', $timeRegistration) }}" class="btn btn-primary">
                                    <i class="ri-edit-line"></i> Edit
                                </a>
                                <form action="{{ route('time-registrations.destroy', $timeRegistration) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this time registration?')">
                                        <i class="ri-delete-bin-line"></i> Delete
                                    </button>
                                </form>
                            @endif
                            @if (auth()->user()->canApproveTimeRegistrations() && $timeRegistration->status === 'pending')
                                <form action="{{ route('time-registrations.approve', $timeRegistration) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="ri-check-line"></i> Approve
                                    </button>
                                </form>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">
                                    <i class="ri-close-line"></i> Reject
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Basic Information</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Project</dt>
                                    <dd class="col-sm-8">{{ $timeRegistration->project?->name ?? 'No Project' }}</dd>

                                    <dt class="col-sm-4">Task</dt>
                                    <dd class="col-sm-8">{{ $timeRegistration->task?->name ?? 'No Task' }}</dd>

                                    <dt class="col-sm-4">Category</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge"
                                            style="background-color: {{ $timeRegistration->category->color }}">
                                            {{ $timeRegistration->category->name }}
                                        </span>
                                    </dd>

                                    <dt class="col-sm-4">Status</dt>
                                    <dd class="col-sm-8">
                                        @if ($timeRegistration->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($timeRegistration->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </dd>
                                </dl>
                            </div>

                            <div class="col-md-6">
                                <h6>Time Information</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Start Time</dt>
                                    <dd class="col-sm-8">{{ $timeRegistration->start_time->format('Y-m-d H:i') }}</dd>

                                    <dt class="col-sm-4">End Time</dt>
                                    <dd class="col-sm-8">{{ $timeRegistration->end_time->format('Y-m-d H:i') }}</dd>

                                    <dt class="col-sm-4">Duration</dt>
                                    <dd class="col-sm-8">{{ $timeRegistration->duration_minutes }} minutes</dd>

                                    <dt class="col-sm-4">Break Duration</dt>
                                    <dd class="col-sm-8">{{ $timeRegistration->break_duration_minutes }} minutes</dd>

                                    <dt class="col-sm-4">Total Hours</dt>
                                    <dd class="col-sm-8">{{ $timeRegistration->total_hours }} hours</dd>
                                </dl>
                            </div>

                            <div class="col-12 mt-4">
                                <h6>Billing Information</h6>
                                <dl class="row">
                                    <dt class="col-sm-2">Billable</dt>
                                    <dd class="col-sm-10">{{ $timeRegistration->is_billable ? 'Yes' : 'No' }}</dd>

                                    @if ($timeRegistration->is_billable)
                                        <dt class="col-sm-2">Hourly Rate</dt>
                                        <dd class="col-sm-10">${{ number_format($timeRegistration->hourly_rate, 2) }}</dd>

                                        <dt class="col-sm-2">Total Amount</dt>
                                        <dd class="col-sm-10">${{ number_format($timeRegistration->billable_amount, 2) }}
                                        </dd>
                                    @endif
                                </dl>
                            </div>

                            @if ($timeRegistration->description)
                                <div class="col-12 mt-4">
                                    <h6>Description</h6>
                                    <p class="mb-0">{{ $timeRegistration->description }}</p>
                                </div>
                            @endif

                            @if ($timeRegistration->attachments->count() > 0)
                                <div class="col-12 mt-4">
                                    <h6>Attachments</h6>
                                    <div class="list-group">
                                        @foreach ($timeRegistration->attachments as $attachment)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ $attachment->file_name }}</span>
                                                <div>
                                                    <a href="{{ Storage::url($attachment->file_path) }}"
                                                        class="btn btn-sm btn-info" target="_blank">
                                                        <i class="ri-download-line"></i>
                                                    </a>
                                                    @if ($timeRegistration->isEditable())
                                                        <form
                                                            action="{{ route('time-registrations.attachments.destroy', [$timeRegistration, $attachment]) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="col-12 mt-4">
                                <h6>Comments</h6>
                                <div class="card">
                                    <div class="card-body">
                                        @if ($timeRegistration->comments->count() > 0)
                                            <div class="timeline mb-3">
                                                @foreach ($timeRegistration->comments as $comment)
                                                    <div class="timeline-item">
                                                        <div class="timeline-point"></div>
                                                        <div class="timeline-content">
                                                            <h6 class="mb-1">{{ $comment->user->name }}</h6>
                                                            <p class="mb-0">{{ $comment->comment }}</p>
                                                            <small
                                                                class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">No comments yet.</p>
                                        @endif

                                        <form action="{{ route('time-registrations.comments.store', $timeRegistration) }}"
                                            method="POST" class="mt-3">
                                            @csrf
                                            <div class="form-group">
                                                <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="2"
                                                    placeholder="Add a comment..."></textarea>
                                                @error('comment')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-2">
                                                <i class="ri-send-plane-line"></i> Add Comment
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                <form action="{{ route('time-registrations.reject', $timeRegistration) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Time Registration</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rejection Reason</label>
                            <textarea name="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror" rows="3"
                                required></textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
