@extends('layouts/contentNavbarLayout')

@section('title', "{$candidate->first_name} {$candidate->last_name}")

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Candidate Details</h4>
            <div>
                <a href="{{ route('interviews.create', ['candidate_id' => $candidate->id]) }}" class="btn btn-primary me-2">
                    <i class="ri-calendar-event-line"></i> Schedule Interview
                </a>
                <a href="{{ route('assessments.create', ['candidate_id' => $candidate->id]) }}" class="btn btn-primary me-2">
                    <i class="ri-task-line"></i> Create Assessment
                </a>
                <a href="{{ route('candidates.edit', $candidate) }}" class="btn btn-secondary me-2">
                    <i class="ri-edit-line"></i> Edit
                </a>
                <a href="{{ route('candidates.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Main Details -->
            <div class="col-xl-8 col-lg-7">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Full Name</h6>
                                <p>{{ $candidate->first_name }} {{ $candidate->last_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Email</h6>
                                <p><a href="mailto:{{ $candidate->email }}">{{ $candidate->email }}</a></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Phone</h6>
                                <p>{{ $candidate->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Location</h6>
                                <p>
                                    @if ($candidate->city || $candidate->state || $candidate->country)
                                        {{ collect([$candidate->city, $candidate->state, $candidate->country])->filter()->join(', ') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6>Address</h6>
                                <p>{{ $candidate->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Professional Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Current Company</h6>
                                <p>{{ $candidate->current_company ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Current Position</h6>
                                <p>{{ $candidate->current_position ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Experience</h6>
                                <p>{{ $candidate->experience_years }} years</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Education</h6>
                                <p>
                                    {{ ucfirst(str_replace('_', ' ', $candidate->education_level)) }}
                                    @if ($candidate->field_of_study)
                                        in {{ $candidate->field_of_study }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if ($candidate->skills)
                            <div class="mb-3">
                                <h6>Skills</h6>
                                <div>
                                    @foreach ($candidate->skills as $skill)
                                        <span class="badge bg-label-primary me-1">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4">
                                <h6>Portfolio</h6>
                                <p>
                                    @if ($candidate->portfolio_url)
                                        <a href="{{ $candidate->portfolio_url }}" target="_blank">View Portfolio</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <h6>LinkedIn</h6>
                                <p>
                                    @if ($candidate->linkedin_url)
                                        <a href="{{ $candidate->linkedin_url }}" target="_blank">View Profile</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <h6>GitHub</h6>
                                <p>
                                    @if ($candidate->github_url)
                                        <a href="{{ $candidate->github_url }}" target="_blank">View Profile</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Timeline -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Application Timeline</h5>
                    </div>
                    <div class="card-body">
                        <ul class="timeline">
                            <li class="timeline-item">
                                <span class="timeline-point timeline-point-primary"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header">
                                        <h6 class="mb-0">Application Submitted</h6>
                                        <small
                                            class="text-muted">{{ $candidate->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    <div class="mt-2">
                                        Applied for <strong>{{ $candidate->jobPosting->title }}</strong>
                                    </div>
                                </div>
                            </li>

                            @foreach ($candidate->interviews->sortBy('scheduled_date') as $interview)
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-warning"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header">
                                            <h6 class="mb-0">{{ ucfirst($interview->interview_type) }} Interview</h6>
                                            <small
                                                class="text-muted">{{ $interview->scheduled_date->format('M d, Y') }}</small>
                                        </div>
                                        <div class="mt-2">
                                            <p class="mb-0">
                                                Interviewer: {{ $interview->interviewer->name }}<br>
                                                Status: <span
                                                    class="badge bg-label-{{ $interview->status === 'completed' ? 'success' : ($interview->status === 'scheduled' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($interview->status) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                            @foreach ($candidate->assessments->sortBy('scheduled_date') as $assessment)
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-info"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header">
                                            <h6 class="mb-0">{{ $assessment->title }}</h6>
                                            <small
                                                class="text-muted">{{ $assessment->scheduled_date->format('M d, Y') }}</small>
                                        </div>
                                        <div class="mt-2">
                                            <p class="mb-0">
                                                Type: {{ ucfirst($assessment->assessment_type) }}<br>
                                                Status: <span
                                                    class="badge bg-label-{{ $assessment->status === 'completed' ? 'success' : ($assessment->status === 'scheduled' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($assessment->status) }}
                                                </span>
                                                @if ($assessment->score)
                                                    <br>Score: {{ $assessment->score }}/{{ $assessment->max_score }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                            @if ($candidate->status === 'hired' || $candidate->status === 'rejected')
                                <li class="timeline-item">
                                    <span
                                        class="timeline-point timeline-point-{{ $candidate->status === 'hired' ? 'success' : 'danger' }}"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header">
                                            <h6 class="mb-0">{{ $candidate->status === 'hired' ? 'Hired' : 'Rejected' }}
                                            </h6>
                                            <small
                                                class="text-muted">{{ $candidate->updated_at->format('M d, Y') }}</small>
                                        </div>
                                        @if ($candidate->rejection_reason)
                                            <div class="mt-2">
                                                <p class="mb-0">{{ $candidate->rejection_reason }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-5">
                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="mb-0 me-2">Application Status</h5>
                            <span
                                class="badge bg-label-{{ $candidate->status === 'hired' ? 'success' : ($candidate->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($candidate->status) }}
                            </span>
                        </div>
                        <p class="mb-0">Applied for: <strong>{{ $candidate->jobPosting->title }}</strong></p>
                        <small class="text-muted">{{ $candidate->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <!-- Documents Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Documents</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if ($candidate->resume_path)
                                <a href="{{ route('candidates.download-resume', $candidate) }}"
                                    class="btn btn-outline-primary">
                                    <i class="ri-file-text-line me-2"></i> Download Resume
                                </a>
                            @else
                                <button class="btn btn-outline-secondary" disabled>
                                    <i class="ri-file-text-line me-2"></i> No Resume Available
                                </button>
                            @endif

                            @if ($candidate->cover_letter_path)
                                <a href="{{ route('candidates.download-cover-letter', $candidate) }}"
                                    class="btn btn-outline-primary">
                                    <i class="ri-file-text-line me-2"></i> Download Cover Letter
                                </a>
                            @else
                                <button class="btn btn-outline-secondary" disabled>
                                    <i class="ri-file-text-line me-2"></i> No Cover Letter Available
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('interviews.create', ['candidate_id' => $candidate->id]) }}"
                                class="btn btn-primary">
                                <i class="ri-calendar-event-line me-2"></i> Schedule Interview
                            </a>
                            <a href="{{ route('assessments.create', ['candidate_id' => $candidate->id]) }}"
                                class="btn btn-primary">
                                <i class="ri-task-line me-2"></i> Create Assessment
                            </a>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#updateStatusModal">
                                <i class="ri-user-follow-line me-2"></i> Update Status
                            </button>
                            <a href="mailto:{{ $candidate->email }}" class="btn btn-secondary">
                                <i class="ri-mail-send-line me-2"></i> Send Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('candidates.update', $candidate) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Update Candidate Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            @foreach (['applied', 'screening', 'interviewing', 'offered', 'hired', 'rejected', 'withdrawn'] as $status)
                                <option value="{{ $status }}" @selected($candidate->status === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="rejectionReasonGroup" style="display: none;">
                        <label class="form-label" for="rejection_reason">Rejection Reason</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3">{{ $candidate->rejection_reason }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Show/hide rejection reason based on status
        document.getElementById('status').addEventListener('change', function() {
            const rejectionReasonGroup = document.getElementById('rejectionReasonGroup');
            const rejectionReason = document.getElementById('rejection_reason');

            if (this.value === 'rejected') {
                rejectionReasonGroup.style.display = 'block';
                rejectionReason.setAttribute('required', 'required');
            } else {
                rejectionReasonGroup.style.display = 'none';
                rejectionReason.removeAttribute('required');
            }
        });

        // Trigger change event on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('status').dispatchEvent(new Event('change'));
        });
    </script>
@endsection
