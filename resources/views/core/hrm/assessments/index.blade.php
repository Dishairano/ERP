@extends('layouts/contentNavbarLayout')

@section('title', 'Assessments')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Assessments</h4>
            <a href="{{ route('assessments.create') }}" class="btn btn-primary">
                <i class="ri-task-line"></i> Create Assessment
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('assessments.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Job Posting</label>
                            <select name="job_posting_id" class="form-select">
                                <option value="">All Positions</option>
                                @foreach ($jobPostings as $posting)
                                    <option value="{{ $posting->id }}" @selected(request('job_posting_id') == $posting->id)>
                                        {{ $posting->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Assessment Type</label>
                            <select name="assessment_type" class="form-select">
                                <option value="">All Types</option>
                                @foreach ($assessmentTypes as $type)
                                    <option value="{{ $type }}" @selected(request('assessment_type') == $type)>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(request('status') == $status)>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search assessments..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-filter-2-line"></i> Apply Filters
                            </button>
                            <a href="{{ route('assessments.index') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Assessments List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Candidate</th>
                                <th>Position</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assessments as $assessment)
                                <tr>
                                    <td>
                                        <a href="{{ route('assessments.show', $assessment) }}" class="text-body fw-bold">
                                            {{ $assessment->title }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <div class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($assessment->candidate->first_name, 0, 1)) }}{{ strtoupper(substr($assessment->candidate->last_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <a href="{{ route('candidates.show', $assessment->candidate) }}"
                                                class="text-body">
                                                {{ $assessment->candidate->first_name }}
                                                {{ $assessment->candidate->last_name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('job-postings.show', $assessment->jobPosting) }}"
                                            class="text-body">
                                            {{ $assessment->jobPosting->title }}
                                        </a>
                                    </td>
                                    <td>{{ ucfirst($assessment->assessment_type) }}</td>
                                    <td>{{ $assessment->scheduled_date->format('M d, Y') }}</td>
                                    <td>
                                        @if ($assessment->score !== null)
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1" style="height: 6px; width: 50px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ ($assessment->score / $assessment->max_score) * 100 }}%"
                                                        aria-valuenow="{{ $assessment->score }}" aria-valuemin="0"
                                                        aria-valuemax="{{ $assessment->max_score }}">
                                                    </div>
                                                </div>
                                                <span
                                                    class="ms-2">{{ $assessment->score }}/{{ $assessment->max_score }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($assessment->status)
                                            @case('scheduled')
                                                <span class="badge bg-label-warning">Scheduled</span>
                                            @break

                                            @case('in_progress')
                                                <span class="badge bg-label-info">In Progress</span>
                                            @break

                                            @case('completed')
                                                <span class="badge bg-label-success">Completed</span>
                                            @break

                                            @case('expired')
                                                <span class="badge bg-label-danger">Expired</span>
                                            @break

                                            @case('cancelled')
                                                <span class="badge bg-label-secondary">Cancelled</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('assessments.show', $assessment) }}">
                                                    <i class="ri-eye-line me-2"></i> View
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('assessments.edit', $assessment) }}">
                                                    <i class="ri-pencil-line me-2"></i> Edit
                                                </a>
                                                @if ($assessment->status === 'scheduled')
                                                    <a class="dropdown-item"
                                                        href="{{ route('assessments.start', $assessment) }}">
                                                        <i class="ri-play-circle-line me-2"></i> Start Assessment
                                                    </a>
                                                @endif
                                                @if ($assessment->status === 'in_progress')
                                                    <a class="dropdown-item"
                                                        href="{{ route('assessments.complete', $assessment) }}">
                                                        <i class="ri-check-line me-2"></i> Complete Assessment
                                                    </a>
                                                @endif
                                                <form action="{{ route('assessments.destroy', $assessment) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this assessment?')">
                                                        <i class="ri-delete-bin-line me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-center mb-3">
                                                <i class="ri-task-line" style="font-size: 48px;"></i>
                                            </div>
                                            <h6 class="fw-bold">No assessments found</h6>
                                            <p class="text-muted">Try adjusting your search or filters to find what you're
                                                looking for.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $assessments->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endsection
