@extends('layouts/contentNavbarLayout')

@section('title', 'Job Postings')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Job Postings</h4>
            <a href="{{ route('job-postings.create') }}" class="btn btn-primary">
                <i class="ri-add-line"></i> Create Job Posting
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('job-postings.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select">
                                <option value="">All Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Position Type</label>
                            <select name="position_type" class="form-select">
                                <option value="">All Types</option>
                                @foreach ($positionTypes as $type)
                                    <option value="{{ $type }}" @selected(request('position_type') == $type)>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Experience Level</label>
                            <select name="experience_level" class="form-select">
                                <option value="">All Levels</option>
                                @foreach ($experienceLevels as $level)
                                    <option value="{{ $level }}" @selected(request('experience_level') == $level)>
                                        {{ ucfirst($level) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
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

                        <div class="col-md-6">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search job postings..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-filter-2-line"></i> Apply Filters
                            </button>
                            <a href="{{ route('job-postings.index') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Job Postings List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Department</th>
                                <th>Type</th>
                                <th>Experience</th>
                                <th>Location</th>
                                <th>Applications</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobPostings as $posting)
                                <tr>
                                    <td>
                                        <a href="{{ route('job-postings.show', $posting) }}" class="text-body fw-bold">
                                            {{ $posting->title }}
                                        </a>
                                    </td>
                                    <td>{{ $posting->department->name }}</td>
                                    <td>{{ ucfirst($posting->position_type) }}</td>
                                    <td>{{ ucfirst($posting->experience_level) }}</td>
                                    <td>
                                        <span class="badge bg-label-info">{{ ucfirst($posting->location_type) }}</span>
                                        @if ($posting->location)
                                            <br>
                                            <small>{{ $posting->location }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('candidates.index', ['job_posting_id' => $posting->id]) }}"
                                            class="text-body">
                                            {{ $posting->candidates_count ?? 0 }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($posting->application_deadline)
                                            @if ($posting->application_deadline->isPast())
                                                <span class="badge bg-label-danger">
                                                    Expired {{ $posting->application_deadline->diffForHumans() }}
                                                </span>
                                            @else
                                                <span class="badge bg-label-success">
                                                    {{ $posting->application_deadline->diffForHumans() }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-label-secondary">No deadline</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($posting->status)
                                            @case('draft')
                                                <span class="badge bg-label-secondary">Draft</span>
                                            @break

                                            @case('published')
                                                <span class="badge bg-label-success">Published</span>
                                            @break

                                            @case('closed')
                                                <span class="badge bg-label-danger">Closed</span>
                                            @break

                                            @case('on-hold')
                                                <span class="badge bg-label-warning">On Hold</span>
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
                                                <a class="dropdown-item" href="{{ route('job-postings.show', $posting) }}">
                                                    <i class="ri-eye-line me-2"></i> View
                                                </a>
                                                <a class="dropdown-item" href="{{ route('job-postings.edit', $posting) }}">
                                                    <i class="ri-pencil-line me-2"></i> Edit
                                                </a>
                                                @if ($posting->status === 'draft')
                                                    <form action="{{ route('job-postings.update', $posting) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="published">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ri-send-plane-line me-2"></i> Publish
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($posting->status === 'published')
                                                    <form action="{{ route('job-postings.update', $posting) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="closed">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ri-close-circle-line me-2"></i> Close
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('job-postings.destroy', $posting) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this job posting?')">
                                                        <i class="ri-delete-bin-line me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-center mb-3">
                                                <i class="ri-file-search-line" style="font-size: 48px;"></i>
                                            </div>
                                            <h6 class="fw-bold">No job postings found</h6>
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
                        {{ $jobPostings->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            // Handle filter form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const searchParams = new URLSearchParams();

                for (const [key, value] of formData.entries()) {
                    if (value) {
                        searchParams.append(key, value);
                    }
                }

                window.location.href = `${this.action}?${searchParams.toString()}`;
            });
        </script>
    @endsection
