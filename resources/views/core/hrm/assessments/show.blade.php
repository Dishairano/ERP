@extends('layouts/contentNavbarLayout')

@section('title', $assessment->title)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Assessment Details</h4>
            <div>
                <a href="{{ route('assessments.edit', $assessment) }}" class="btn btn-primary me-2">
                    <i class="ri-edit-line"></i> Edit Assessment
                </a>
                <a href="{{ route('assessments.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Main Details -->
            <div class="col-xl-8 col-lg-7">
                <!-- Assessment Information -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Assessment Information</h5>
                        <span
                            class="badge bg-label-{{ $assessment->status === 'completed'
                                ? 'success'
                                : ($assessment->status === 'in_progress'
                                    ? 'warning'
                                    : ($assessment->status === 'scheduled'
                                        ? 'info'
                                        : ($assessment->status === 'expired'
                                            ? 'danger'
                                            : 'secondary'))) }}">
                            {{ ucfirst($assessment->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Position</h6>
                                <p>
                                    <a href="{{ route('job-postings.show', $assessment->jobPosting) }}" class="text-body">
                                        {{ $assessment->jobPosting->title }}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Department</h6>
                                <p>{{ $assessment->jobPosting->department->name }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <h6>Assessment Type</h6>
                                <p>{{ ucfirst($assessment->assessment_type) }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6>Date & Time</h6>
                                <p>
                                    {{ $assessment->scheduled_date->format('M d, Y') }}<br>
                                    {{ $assessment->scheduled_time->format('H:i') }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <h6>Duration</h6>
                                <p>{{ $assessment->duration_minutes }} minutes</p>
                            </div>
                        </div>

                        @if ($assessment->description)
                            <div class="mb-3">
                                <h6>Description</h6>
                                <p class="text-justify">{{ $assessment->description }}</p>
                            </div>
                        @endif

                        @if ($assessment->platform || $assessment->access_link)
                            <div class="row mb-3">
                                @if ($assessment->platform)
                                    <div class="col-md-6">
                                        <h6>Platform</h6>
                                        <p>{{ $assessment->platform }}</p>
                                    </div>
                                @endif
                                @if ($assessment->access_link)
                                    <div class="col-md-6">
                                        <h6>Access Link</h6>
                                        <p><a href="{{ $assessment->access_link }}" target="_blank">Open Assessment</a></p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($assessment->access_code)
                            <div class="mb-3">
                                <h6>Access Code</h6>
                                <p>{{ $assessment->access_code }}</p>
                            </div>
                        @endif

                        @if ($assessment->instructions)
                            <div class="mb-3">
                                <h6>Instructions</h6>
                                <p class="text-justify">{{ $assessment->instructions }}</p>
                            </div>
                        @endif

                        @if ($assessment->questions)
                            <div class="mb-3">
                                <h6>Questions</h6>
                                <ol class="ps-3 mb-0">
                                    @foreach ($assessment->questions as $question)
                                        <li>{{ $question }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        @endif

                        @if ($assessment->skills_evaluated)
                            <div class="mb-3">
                                <h6>Skills Evaluated</h6>
                                <div>
                                    @foreach ($assessment->skills_evaluated as $skill)
                                        <span class="badge bg-label-primary me-1">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($assessment->attachments)
                            <div class="mb-3">
                                <h6>Attachments</h6>
                                <div>
                                    @foreach ($assessment->attachments as $index => $attachment)
                                        <a href="{{ route('assessments.download-attachment', ['assessment' => $assessment, 'index' => $index]) }}"
                                            class="btn btn-outline-primary btn-sm me-2 mb-2">
                                            <i class="ri-file-download-line"></i> Download Attachment {{ $index + 1 }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Assessment Results -->
                @if ($assessment->status === 'completed')
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Assessment Results</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6>Score</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar {{ $assessment->score >= $assessment->passing_score ? 'bg-success' : 'bg-danger' }}"
                                                role="progressbar"
                                                style="width: {{ ($assessment->score / $assessment->max_score) * 100 }}%">
                                            </div>
                                        </div>
                                        <span class="ms-2">{{ $assessment->score }}/{{ $assessment->max_score }}</span>
                                    </div>
                                    <small class="text-muted">Passing Score: {{ $assessment->passing_score }}</small>
                                </div>
                                <div class="col-md-6">
                                    <h6>Result</h6>
                                    @if ($assessment->score >= $assessment->passing_score)
                                        <span class="badge bg-success">Passed</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </div>
                            </div>

                            @if ($assessment->skill_scores)
                                <div class="mb-3">
                                    <h6>Skill Scores</h6>
                                    <div class="row g-3">
                                        @foreach ($assessment->skill_scores as $skill => $score)
                                            <div class="col-md-6">
                                                <label class="form-label">{{ $skill }}</label>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1" style="height: 8px;">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $score }}%"></div>
                                                    </div>
                                                    <span class="ms-2">{{ $score }}%</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($assessment->feedback)
                                <div class="mb-3">
                                    <h6>Feedback</h6>
                                    <p class="text-justify">{{ $assessment->feedback }}</p>
                                </div>
                            @endif

                            @if ($assessment->recommendations)
                                <div class="mb-3">
                                    <h6>Recommendations</h6>
                                    <p class="text-justify">{{ $assessment->recommendations }}</p>
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
                                    {{ strtoupper(substr($assessment->candidate->first_name, 0, 1)) }}{{ strtoupper(substr($assessment->candidate->last_name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">
                                    <a href="{{ route('candidates.show', $assessment->candidate) }}" class="text-body">
                                        {{ $assessment->candidate->first_name }} {{ $assessment->candidate->last_name }}
                                    </a>
                                </h6>
                                <small>{{ $assessment->candidate->email }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6>Current Position</h6>
                            <p>
                                {{ $assessment->candidate->current_position }}
                                @if ($assessment->candidate->current_company)
                                    at {{ $assessment->candidate->current_company }}
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6>Experience</h6>
                            <p>{{ $assessment->candidate->experience_years }} years</p>
                        </div>

                        @if ($assessment->candidate->skills)
                            <div class="mb-3">
                                <h6>Skills</h6>
                                <div>
                                    @foreach ($assessment->candidate->skills as $skill)
                                        <span class="badge bg-label-primary me-1">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            @if ($assessment->candidate->resume_path)
                                <a href="{{ route('candidates.download-resume', $assessment->candidate) }}"
                                    class="btn btn-outline-primary">
                                    <i class="ri-file-text-line me-2"></i> Download Resume
                                </a>
                            @endif
                            @if ($assessment->candidate->cover_letter_path)
                                <a href="{{ route('candidates.download-cover-letter', $assessment->candidate) }}"
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
                            @if ($assessment->status === 'scheduled')
                                <a href="{{ route('assessments.start', $assessment) }}" class="btn btn-primary">
                                    <i class="ri-play-circle-line me-2"></i> Start Assessment
                                </a>
                            @endif
                            @if ($assessment->status === 'in_progress')
                                <a href="{{ route('assessments.complete', $assessment) }}" class="btn btn-success">
                                    <i class="ri-check-line me-2"></i> Complete Assessment
                                </a>
                            @endif
                            <a href="{{ route('assessments.edit', $assessment) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-2"></i> Edit Assessment
                            </a>
                            <a href="mailto:{{ $assessment->candidate->email }}" class="btn btn-secondary">
                                <i class="ri-mail-send-line me-2"></i> Send Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
