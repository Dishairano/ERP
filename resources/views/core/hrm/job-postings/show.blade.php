@extends('layouts/contentNavbarLayout')

@section('title', $jobPosting->title)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Job Posting Details</h4>
            <div>
                <a href="{{ route('candidates.create', ['job_posting_id' => $jobPosting->id]) }}"
                    class="btn btn-primary me-2">
                    <i class="ri-user-add-line"></i> Add Candidate
                </a>
                <a href="{{ route('job-postings.edit', $jobPosting) }}" class="btn btn-secondary me-2">
                    <i class="ri-edit-line"></i> Edit
                </a>
                <a href="{{ route('job-postings.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Job Details Card -->
            <div class="col-xl-8 col-lg-7">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Job Details</h5>
                        <span
                            class="badge bg-label-{{ $jobPosting->status === 'published' ? 'success' : ($jobPosting->status === 'draft' ? 'secondary' : ($jobPosting->status === 'closed' ? 'danger' : 'warning')) }}">
                            {{ ucfirst($jobPosting->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Department</h6>
                                <p>{{ $jobPosting->department->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Position Type</h6>
                                <p>{{ ucfirst($jobPosting->position_type) }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Experience Level</h6>
                                <p>{{ ucfirst($jobPosting->experience_level) }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Number of Positions</h6>
                                <p>{{ $jobPosting->number_of_positions }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Location Type</h6>
                                <p>{{ ucfirst($jobPosting->location_type) }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Location</h6>
                                <p>{{ $jobPosting->location ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Salary Range</h6>
                                <p>{{ $jobPosting->salary_currency }} {{ number_format($jobPosting->salary_min, 2) }} -
                                    {{ number_format($jobPosting->salary_max, 2) }} per {{ $jobPosting->salary_period }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Application Deadline</h6>
                                <p>
                                    @if ($jobPosting->application_deadline)
                                        {{ $jobPosting->application_deadline->format('M d, Y') }}
                                        @if ($jobPosting->application_deadline->isPast())
                                            <span class="badge bg-label-danger">Expired</span>
                                        @else
                                            <span
                                                class="badge bg-label-success">{{ $jobPosting->application_deadline->diffForHumans() }}</span>
                                        @endif
                                    @else
                                        No deadline set
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6>Description</h6>
                            <p class="text-justify">{{ $jobPosting->description }}</p>
                        </div>

                        @if ($jobPosting->requirements)
                            <div class="mb-3">
                                <h6>Requirements</h6>
                                <p class="text-justify">{{ $jobPosting->requirements }}</p>
                            </div>
                        @endif

                        @if ($jobPosting->responsibilities)
                            <div class="mb-3">
                                <h6>Responsibilities</h6>
                                <p class="text-justify">{{ $jobPosting->responsibilities }}</p>
                            </div>
                        @endif

                        @if ($jobPosting->qualifications)
                            <div class="mb-3">
                                <h6>Qualifications</h6>
                                <p class="text-justify">{{ $jobPosting->qualifications }}</p>
                            </div>
                        @endif

                        @if ($jobPosting->benefits)
                            <div class="mb-3">
                                <h6>Benefits</h6>
                                <p class="text-justify">{{ $jobPosting->benefits }}</p>
                            </div>
                        @endif

                        @if ($jobPosting->skills_required)
                            <div class="mb-3">
                                <h6>Required Skills</h6>
                                <div>
                                    @foreach ($jobPosting->skills_required as $skill)
                                        <span class="badge bg-label-primary me-1">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($jobPosting->internal_notes)
                            <div class="mb-3">
                                <h6>Internal Notes</h6>
                                <p class="text-justify">{{ $jobPosting->internal_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-5">
                <!-- Statistics Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-label-primary p-2 me-2">
                                        <i class="ri-user-line"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $jobPosting->candidates->count() }}</h6>
                                        <small>Total Candidates</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-label-success p-2 me-2">
                                        <i class="ri-user-follow-line"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $jobPosting->candidates->where('status', 'hired')->count() }}
                                        </h6>
                                        <small>Hired</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-label-warning p-2 me-2">
                                        <i class="ri-user-voice-line"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">
                                            {{ $jobPosting->candidates->whereIn('status', ['screening', 'interviewing'])->count() }}
                                        </h6>
                                        <small>In Process</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-label-danger p-2 me-2">
                                        <i class="ri-user-unfollow-line"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">
                                            {{ $jobPosting->candidates->where('status', 'rejected')->count() }}</h6>
                                        <small>Rejected</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Candidates Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Recent Candidates</h5>
                        <a href="{{ route('candidates.index', ['job_posting_id' => $jobPosting->id]) }}"
                            class="btn btn-primary btn-sm">
                            View All
                        </a>
                    </div>
                    <div class="card-body">
                        @forelse($jobPosting->candidates->sortByDesc('created_at')->take(5) as $candidate)
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-sm me-3">
                                    <div class="avatar-initial rounded-circle bg-label-primary">
                                        {{ strtoupper(substr($candidate->first_name, 0, 1)) }}{{ strtoupper(substr($candidate->last_name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">
                                        <a href="{{ route('candidates.show', $candidate) }}" class="text-body">
                                            {{ $candidate->first_name }} {{ $candidate->last_name }}
                                        </a>
                                    </h6>
                                    <small>{{ $candidate->current_position }}{{ $candidate->current_company ? " at {$candidate->current_company}" : '' }}</small>
                                </div>
                                <div>
                                    <span
                                        class="badge bg-label-{{ $candidate->status === 'hired' ? 'success' : ($candidate->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($candidate->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center mb-0">No candidates yet</p>
                        @endforelse
                    </div>
                </div>

                <!-- Posting Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Posting Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Created By</h6>
                            <p>{{ $jobPosting->creator->name }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Posted On</h6>
                            <p>{{ $jobPosting->posting_date->format('M d, Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Last Updated</h6>
                            <p>{{ $jobPosting->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
