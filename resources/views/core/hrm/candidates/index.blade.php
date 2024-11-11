@extends('layouts/contentNavbarLayout')

@section('title', 'Candidates')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Candidates</h4>
            <a href="{{ route('candidates.create') }}" class="btn btn-primary">
                <i class="ri-user-add-line"></i> Add Candidate
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('candidates.index') }}">
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
                            <label class="form-label">Education Level</label>
                            <select name="education_level" class="form-select">
                                <option value="">All Levels</option>
                                @foreach ($educationLevels as $level)
                                    <option value="{{ $level }}" @selected(request('education_level') == $level)>
                                        {{ ucfirst(str_replace('_', ' ', $level)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Experience (Years)</label>
                            <input type="number" name="experience_years" class="form-control"
                                value="{{ request('experience_years') }}" min="0" step="0.5">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by name, email, company..." value="{{ request('search') }}">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-filter-2-line"></i> Apply Filters
                            </button>
                            <a href="{{ route('candidates.index') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Candidates List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position Applied</th>
                                <th>Experience</th>
                                <th>Current Company</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candidates as $candidate)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($candidate->first_name, 0, 1)) }}{{ strtoupper(substr($candidate->last_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ route('candidates.show', $candidate) }}"
                                                    class="text-body fw-bold">
                                                    {{ $candidate->first_name }} {{ $candidate->last_name }}
                                                </a>
                                                <br>
                                                <small>{{ $candidate->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('job-postings.show', $candidate->jobPosting) }}"
                                            class="text-body">
                                            {{ $candidate->jobPosting->title }}
                                        </a>
                                    </td>
                                    <td>{{ $candidate->experience_years }} years</td>
                                    <td>{{ $candidate->current_company ?? 'N/A' }}</td>
                                    <td>
                                        @switch($candidate->status)
                                            @case('applied')
                                                <span class="badge bg-label-primary">Applied</span>
                                            @break

                                            @case('screening')
                                                <span class="badge bg-label-info">Screening</span>
                                            @break

                                            @case('interviewing')
                                                <span class="badge bg-label-warning">Interviewing</span>
                                            @break

                                            @case('offered')
                                                <span class="badge bg-label-info">Offered</span>
                                            @break

                                            @case('hired')
                                                <span class="badge bg-label-success">Hired</span>
                                            @break

                                            @case('rejected')
                                                <span class="badge bg-label-danger">Rejected</span>
                                            @break

                                            @case('withdrawn')
                                                <span class="badge bg-label-secondary">Withdrawn</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td>{{ $candidate->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('candidates.show', $candidate) }}">
                                                    <i class="ri-eye-line me-2"></i> View
                                                </a>
                                                <a class="dropdown-item" href="{{ route('candidates.edit', $candidate) }}">
                                                    <i class="ri-pencil-line me-2"></i> Edit
                                                </a>
                                                @if ($candidate->resume_path)
                                                    <a class="dropdown-item"
                                                        href="{{ route('candidates.download-resume', $candidate) }}">
                                                        <i class="ri-file-download-line me-2"></i> Download Resume
                                                    </a>
                                                @endif
                                                @if ($candidate->status === 'applied')
                                                    <a class="dropdown-item"
                                                        href="{{ route('interviews.create', ['candidate_id' => $candidate->id]) }}">
                                                        <i class="ri-calendar-event-line me-2"></i> Schedule Interview
                                                    </a>
                                                @endif
                                                <form action="{{ route('candidates.destroy', $candidate) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this candidate?')">
                                                        <i class="ri-delete-bin-line me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-center mb-3">
                                                <i class="ri-user-search-line" style="font-size: 48px;"></i>
                                            </div>
                                            <h6 class="fw-bold">No candidates found</h6>
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
                        {{ $candidates->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endsection
