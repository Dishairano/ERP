@extends('layouts/contentNavbarLayout')

@section('title', "Interview Details - {$interview->candidate->first_name} {$interview->candidate->last_name}")

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Interview Details</h4>
            <div>
                <a href="{{ route('interviews.edit', $interview) }}" class="btn btn-primary me-2">
                    <i class="ri-edit-line"></i> Edit Interview
                </a>
                <a href="{{ route('interviews.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Main Details -->
            <div class="col-xl-8 col-lg-7">
                <!-- Interview Information -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Interview Information</h5>
                        <span
                            class="badge bg-label-{{ $interview->status === 'completed' ? 'success' : ($interview->status === 'scheduled' ? 'warning' : 'danger') }}">
                            {{ ucfirst($interview->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Position</h6>
                                <p>
                                    <a href="{{ route('job-postings.show', $interview->jobPosting) }}" class="text-body">
                                        {{ $interview->jobPosting->title }}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Department</h6>
                                <p>{{ $interview->jobPosting->department->name }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <h6>Interview Type</h6>
                                <p>{{ ucfirst($interview->interview_type) }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6>Round</h6>
                                <p>Round {{ $interview->round_number }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6>Duration</h6>
                                <p>{{ $interview->duration_minutes }} minutes</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Date & Time</h6>
                                <p>
                                    {{ $interview->scheduled_date->format('M d, Y') }}<br>
                                    {{ $interview->scheduled_time->format('H:i') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Interviewer</h6>
                                <p>{{ $interview->interviewer->name }}</p>
                            </div>
                        </div>

                        @if ($interview->location || $interview->meeting_link)
                            <div class="row mb-3">
                                @if ($interview->location)
                                    <div class="col-md-6">
                                        <h6>Location</h6>
                                        <p>{{ $interview->location }}</p>
                                    </div>
                                @endif
                                @if ($interview->meeting_link)
                                    <div class="col-md-6">
                                        <h6>Meeting Link</h6>
                                        <p><a href="{{ $interview->meeting_link }}" target="_blank">Join Meeting</a></p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($interview->meeting_id || $interview->meeting_password)
                            <div class="row mb-3">
                                @if ($interview->meeting_id)
                                    <div class="col-md-6">
                                        <h6>Meeting ID</h6>
                                        <p>{{ $interview->meeting_id }}</p>
                                    </div>
                                @endif
                                @if ($interview->meeting_password)
                                    <div class="col-md-6">
                                        <h6>Meeting Password</h6>
                                        <p>{{ $interview->meeting_password }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($interview->preparation_notes)
                            <div class="mb-3">
                                <h6>Preparation Notes</h6>
                                <p class="text-justify">{{ $interview->preparation_notes }}</p>
                            </div>
                        @endif

                        @if ($interview->questions)
                            <div class="mb-3">
                                <h6>Interview Questions</h6>
                                <ol class="ps-3 mb-0">
                                    @foreach ($interview->questions as $question)
                                        <li>{{ $question }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        @endif

                        @if ($interview->evaluation_criteria)
                            <div class="mb-3">
                                <h6>Evaluation Criteria</h6>
                                <ul class="ps-3 mb-0">
                                    @foreach ($interview->evaluation_criteria as $criterion)
                                        <li>{{ $criterion }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Interview Results -->
                @if ($interview->status === 'completed')
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Interview Results</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <h6>Technical Skills</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($interview->technical_skills_rating / 5) * 100 }}%">
                                            </div>
                                        </div>
                                        <span class="ms-2">{{ $interview->technical_skills_rating }}/5</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h6>Soft Skills</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($interview->soft_skills_rating / 5) * 100 }}%"></div>
                                        </div>
                                        <span class="ms-2">{{ $interview->soft_skills_rating }}/5</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h6>Cultural Fit</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($interview->cultural_fit_rating / 5) * 100 }}%"></div>
                                        </div>
                                        <span class="ms-2">{{ $interview->cultural_fit_rating }}/5</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h6>Overall Rating</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($interview->overall_rating / 5) * 100 }}%"></div>
                                        </div>
                                        <span class="ms-2">{{ $interview->overall_rating }}/5</span>
                                    </div>
                                </div>
                            </div>

                            @if ($interview->interviewer_notes)
                                <div class="mb-3">
                                    <h6>Interviewer Notes</h6>
                                    <p class="text-justify">{{ $interview->interviewer_notes }}</p>
                                </div>
                            @endif

                            @if ($interview->candidate_feedback)
                                <div class="mb-3">
                                    <h6>Candidate Feedback</h6>
                                    <p class="text-justify">{{ $interview->candidate_feedback }}</p>
                                </div>
                            @endif

                            @if ($interview->next_steps)
                                <div class="mb-3">
                                    <h6>Next Steps</h6>
                                    <p class="text-justify">{{ $interview->next_steps }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-5">
                <!-- Candidate Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Candidate Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-lg me-3">
                                <div class="avatar-initial rounded-circle bg-label-primary">
                                    {{ strtoupper(substr($interview->candidate->first_name, 0, 1)) }}{{ strtoupper(substr($interview->candidate->last_name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">
                                    <a href="{{ route('candidates.show', $interview->candidate) }}" class="text-body">
                                        {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
                                    </a>
                                </h6>
                                <small>{{ $interview->candidate->email }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6>Current Position</h6>
                            <p>
                                {{ $interview->candidate->current_position }}
                                @if ($interview->candidate->current_company)
                                    at {{ $interview->candidate->current_company }}
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6>Experience</h6>
                            <p>{{ $interview->candidate->experience_years }} years</p>
                        </div>

                        @if ($interview->candidate->skills)
                            <div class="mb-3">
                                <h6>Skills</h6>
                                <div>
                                    @foreach ($interview->candidate->skills as $skill)
                                        <span class="badge bg-label-primary me-1">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            @if ($interview->candidate->resume_path)
                                <a href="{{ route('candidates.download-resume', $interview->candidate) }}"
                                    class="btn btn-outline-primary">
                                    <i class="ri-file-text-line me-2"></i> Download Resume
                                </a>
                            @endif
                            @if ($interview->candidate->cover_letter_path)
                                <a href="{{ route('candidates.download-cover-letter', $interview->candidate) }}"
                                    class="btn btn-outline-primary">
                                    <i class="ri-file-text-line me-2"></i> Download Cover Letter
                                </a>
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
                            @if ($interview->status === 'scheduled')
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#completeInterviewModal">
                                    <i class="ri-check-line me-2"></i> Complete Interview
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#cancelInterviewModal">
                                    <i class="ri-close-circle-line me-2"></i> Cancel Interview
                                </button>
                            @endif
                            <a href="{{ route('interviews.edit', $interview) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-2"></i> Edit Interview
                            </a>
                            <a href="mailto:{{ $interview->candidate->email }}" class="btn btn-secondary">
                                <i class="ri-mail-send-line me-2"></i> Send Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Interview Modal -->
    <div class="modal fade" id="completeInterviewModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('interviews.complete', $interview) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Complete Interview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to mark this interview as completed?</p>
                    <p class="mb-0">You'll be able to add the interview results after completing.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Complete Interview</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cancel Interview Modal -->
    <div class="modal fade" id="cancelInterviewModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('interviews.cancel', $interview) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Interview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="cancellation_reason">Cancellation Reason <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Interview</button>
                </div>
            </form>
        </div>
    </div>
@endsection
