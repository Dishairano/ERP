@extends('layouts/contentNavbarLayout')

@section('title', 'Interviews')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Interviews</h4>
            <a href="{{ route('interviews.create') }}" class="btn btn-primary">
                <i class="ri-calendar-event-line"></i> Schedule Interview
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('interviews.index') }}">
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
                            <label class="form-label">Interview Type</label>
                            <select name="interview_type" class="form-select">
                                <option value="">All Types</option>
                                @foreach ($interviewTypes as $type)
                                    <option value="{{ $type }}" @selected(request('interview_type') == $type)>
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
                            <input type="text" name="search" class="form-control" placeholder="Search candidates..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-filter-2-line"></i> Apply Filters
                            </button>
                            <a href="{{ route('interviews.index') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Calendar View -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Interview Calendar</h5>
                <a href="{{ route('interviews.calendar') }}" class="btn btn-primary btn-sm">
                    <i class="ri-calendar-line"></i> Full Calendar View
                </a>
            </div>
            <div class="card-body">
                <div id="interview-calendar"></div>
            </div>
        </div>

        <!-- Interviews List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Position</th>
                                <th>Type</th>
                                <th>Interviewer</th>
                                <th>Date & Time</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($interviews as $interview)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($interview->candidate->first_name, 0, 1)) }}{{ strtoupper(substr($interview->candidate->last_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ route('candidates.show', $interview->candidate) }}"
                                                    class="text-body fw-bold">
                                                    {{ $interview->candidate->first_name }}
                                                    {{ $interview->candidate->last_name }}
                                                </a>
                                                <br>
                                                <small>Round {{ $interview->round_number }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('job-postings.show', $interview->jobPosting) }}"
                                            class="text-body">
                                            {{ $interview->jobPosting->title }}
                                        </a>
                                    </td>
                                    <td>{{ ucfirst($interview->interview_type) }}</td>
                                    <td>{{ $interview->interviewer->name }}</td>
                                    <td>
                                        {{ $interview->scheduled_date->format('M d, Y') }}<br>
                                        <small>{{ $interview->scheduled_time->format('H:i') }}</small>
                                    </td>
                                    <td>{{ $interview->duration_minutes }} mins</td>
                                    <td>
                                        @switch($interview->status)
                                            @case('scheduled')
                                                <span class="badge bg-label-warning">Scheduled</span>
                                            @break

                                            @case('completed')
                                                <span class="badge bg-label-success">Completed</span>
                                            @break

                                            @case('cancelled')
                                                <span class="badge bg-label-danger">Cancelled</span>
                                            @break

                                            @case('no-show')
                                                <span class="badge bg-label-danger">No Show</span>
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
                                                <a class="dropdown-item" href="{{ route('interviews.show', $interview) }}">
                                                    <i class="ri-eye-line me-2"></i> View
                                                </a>
                                                <a class="dropdown-item" href="{{ route('interviews.edit', $interview) }}">
                                                    <i class="ri-pencil-line me-2"></i> Edit
                                                </a>
                                                @if ($interview->status === 'scheduled')
                                                    <a class="dropdown-item"
                                                        href="{{ route('interviews.complete', $interview) }}">
                                                        <i class="ri-check-line me-2"></i> Mark as Completed
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('interviews.cancel', $interview) }}">
                                                        <i class="ri-close-circle-line me-2"></i> Cancel
                                                    </a>
                                                @endif
                                                <form action="{{ route('interviews.destroy', $interview) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this interview?')">
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
                                                <i class="ri-calendar-todo-line" style="font-size: 48px;"></i>
                                            </div>
                                            <h6 class="fw-bold">No interviews found</h6>
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
                        {{ $interviews->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('interview-calendar');
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridWeek,dayGridDay'
                    },
                    events: @json($calendarEvents),
                    eventClick: function(info) {
                        window.location.href = info.event.url;
                    }
                });
                calendar.render();
            });
        </script>
    @endsection
