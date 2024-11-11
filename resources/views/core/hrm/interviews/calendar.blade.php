@extends('layouts/contentNavbarLayout')

@section('title', 'Interview Calendar')

@section('styles')
    <style>
        #interview-calendar {
            height: calc(100vh - 250px);
            min-height: 600px;
        }

        .fc-event {
            cursor: pointer;
        }

        .fc-event-title {
            font-weight: 500;
        }

        .fc-event-time {
            font-size: 0.85em;
        }

        .interview-tooltip {
            position: absolute;
            z-index: 1070;
            display: block;
            margin: 0;
            font-family: var(--bs-font-sans-serif);
            font-style: normal;
            font-weight: 400;
            line-height: 1.5;
            text-align: left;
            text-decoration: none;
            text-shadow: none;
            text-transform: none;
            letter-spacing: normal;
            word-break: normal;
            word-spacing: normal;
            white-space: normal;
            line-break: auto;
            font-size: 0.875rem;
            word-wrap: break-word;
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: var(--bs-border-radius);
            box-shadow: 0 0.25rem 1rem rgba(161, 172, 184, 0.45);
            padding: 0.5rem;
            max-width: 300px;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Interview Calendar</h4>
            <div>
                <a href="{{ route('interviews.create') }}" class="btn btn-primary me-2">
                    <i class="ri-calendar-event-line"></i> Schedule Interview
                </a>
                <a href="{{ route('interviews.index') }}" class="btn btn-secondary">
                    <i class="ri-list-check"></i> List View
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <select id="filterJobPosting" class="form-select">
                            <option value="">All Positions</option>
                            @foreach ($jobPostings as $posting)
                                <option value="{{ $posting->id }}">{{ $posting->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="filterInterviewer" class="form-select">
                            <option value="">All Interviewers</option>
                            @foreach ($interviewers as $interviewer)
                                <option value="{{ $interviewer->id }}">{{ $interviewer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="filterStatus" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="interview-calendar"></div>
            </div>
        </div>
    </div>

    <!-- Interview Details Modal -->
    <div class="modal fade" id="interviewDetailsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Interview Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6>Candidate</h6>
                        <p id="modalCandidate" class="mb-0"></p>
                    </div>
                    <div class="mb-3">
                        <h6>Position</h6>
                        <p id="modalPosition" class="mb-0"></p>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Interview Type</h6>
                            <p id="modalType" class="mb-0"></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Interviewer</h6>
                            <p id="modalInterviewer" class="mb-0"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Date & Time</h6>
                            <p id="modalDateTime" class="mb-0"></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Duration</h6>
                            <p id="modalDuration" class="mb-0"></p>
                        </div>
                    </div>
                    <div id="modalLocation" class="mb-3" style="display: none;">
                        <h6>Location</h6>
                        <p class="modalLocationText mb-0"></p>
                    </div>
                    <div id="modalMeeting" class="mb-3" style="display: none;">
                        <h6>Meeting Details</h6>
                        <p class="modalMeetingText mb-0"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="modalViewLink" class="btn btn-primary">View Details</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($events),
                slotMinTime: '08:00:00',
                slotMaxTime: '20:00:00',
                allDaySlot: false,
                slotDuration: '00:30:00',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                eventClick: function(info) {
                    const event = info.event;
                    const interview = event.extendedProps;

                    // Update modal content
                    document.getElementById('modalCandidate').textContent = interview.candidate_name;
                    document.getElementById('modalPosition').textContent = interview.position;
                    document.getElementById('modalType').textContent = interview.interview_type;
                    document.getElementById('modalInterviewer').textContent = interview.interviewer;
                    document.getElementById('modalDateTime').textContent =
                        `${event.start.toLocaleDateString()} ${event.start.toLocaleTimeString()}`;
                    document.getElementById('modalDuration').textContent =
                        `${interview.duration} minutes`;

                    // Location/Meeting details
                    const locationDiv = document.getElementById('modalLocation');
                    const meetingDiv = document.getElementById('modalMeeting');

                    if (interview.location) {
                        locationDiv.style.display = 'block';
                        locationDiv.querySelector('.modalLocationText').textContent = interview
                            .location;
                    } else {
                        locationDiv.style.display = 'none';
                    }

                    if (interview.meeting_link) {
                        meetingDiv.style.display = 'block';
                        const meetingText = [];
                        meetingText.push(`Link: ${interview.meeting_link}`);
                        if (interview.meeting_id) meetingText.push(`ID: ${interview.meeting_id}`);
                        if (interview.meeting_password) meetingText.push(
                            `Password: ${interview.meeting_password}`);
                        meetingDiv.querySelector('.modalMeetingText').innerHTML = meetingText.join(
                            '<br>');
                    } else {
                        meetingDiv.style.display = 'none';
                    }

                    // Update view link
                    document.getElementById('modalViewLink').href = interview.view_url;

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('interviewDetailsModal'));
                    modal.show();
                },
                eventDidMount: function(info) {
                    const interview = info.event.extendedProps;
                    const tooltip = new bootstrap.Tooltip(info.el, {
                        title: `${interview.candidate_name} - ${interview.interview_type}`,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                }
            });
            calendar.render();

            // Filter handling
            const filterJobPosting = document.getElementById('filterJobPosting');
            const filterInterviewer = document.getElementById('filterInterviewer');
            const filterStatus = document.getElementById('filterStatus');

            function applyFilters() {
                const jobPostingId = filterJobPosting.value;
                const interviewerId = filterInterviewer.value;
                const status = filterStatus.value;

                calendar.getEvents().forEach(event => {
                    const interview = event.extendedProps;
                    let visible = true;

                    if (jobPostingId && interview.job_posting_id != jobPostingId) visible = false;
                    if (interviewerId && interview.interviewer_id != interviewerId) visible = false;
                    if (status && interview.status != status) visible = false;

                    event.setProp('display', visible ? 'auto' : 'none');
                });
            }

            filterJobPosting.addEventListener('change', applyFilters);
            filterInterviewer.addEventListener('change', applyFilters);
            filterStatus.addEventListener('change', applyFilters);
        });
    </script>
@endsection
