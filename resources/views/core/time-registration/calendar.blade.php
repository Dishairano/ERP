@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Calendar')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Time Registration Calendar</h5>
                <div>
                    <button class="btn btn-primary me-2" id="changeView" data-view="timeGridWeek">
                        <i class="ri-calendar-line me-1"></i> <span>Week View</span>
                    </button>
                    <a href="{{ route('time-registration.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> Register Time
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Calendar Legend -->
                <div class="mb-4">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-label-secondary p-2 me-1"><i class="ri-time-line"></i></span>
                            <span>Draft</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-label-warning p-2 me-1"><i class="ri-time-line"></i></span>
                            <span>Submitted</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-label-success p-2 me-1"><i class="ri-time-line"></i></span>
                            <span>Approved</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-label-danger p-2 me-1"><i class="ri-time-line"></i></span>
                            <span>Rejected</span>
                        </div>
                    </div>
                </div>

                <!-- Calendar -->
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Event Click Modal -->
        <div class="modal fade" id="eventModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Time Registration Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted">Project & Task</label>
                                <p class="h6" id="eventTitle"></p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label text-muted">Date</label>
                                <p class="h6" id="eventDate"></p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label text-muted">Hours</label>
                                <p class="h6" id="eventHours"></p>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted">Description</label>
                                <p id="eventDescription"></p>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted">Status</label>
                                <p><span class="badge" id="eventStatus"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="#" class="btn btn-primary" id="viewDetailsBtn">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize calendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($registrations),
                eventClick: function(info) {
                    const event = info.event;
                    const modal = new bootstrap.Modal(document.getElementById('eventModal'));

                    // Update modal content
                    document.getElementById('eventTitle').textContent = event.title;
                    document.getElementById('eventDate').textContent = event.start.toLocaleDateString();
                    document.getElementById('eventHours').textContent = event.extendedProps.hours +
                        ' hours';
                    document.getElementById('eventDescription').textContent = event.extendedProps
                        .description;

                    const statusBadge = document.getElementById('eventStatus');
                    statusBadge.textContent = event.extendedProps.status.charAt(0).toUpperCase() +
                        event.extendedProps.status.slice(1);
                    statusBadge.className = 'badge bg-label-' + getStatusClass(event.extendedProps
                        .status);

                    // Update view details button
                    document.getElementById('viewDetailsBtn').href = `/time-registration/${event.id}`;

                    modal.show();
                },
                eventDidMount: function(info) {
                    // Add tooltip
                    const tooltip = new bootstrap.Tooltip(info.el, {
                        title: `${info.event.title} (${info.event.extendedProps.hours} hours)`,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                }
            });
            calendar.render();

            // Toggle calendar view
            document.getElementById('changeView').addEventListener('click', function() {
                const button = this;
                const currentView = button.getAttribute('data-view');

                if (currentView === 'timeGridWeek') {
                    calendar.changeView('dayGridMonth');
                    button.setAttribute('data-view', 'dayGridMonth');
                    button.querySelector('span').textContent = 'Month View';
                } else {
                    calendar.changeView('timeGridWeek');
                    button.setAttribute('data-view', 'timeGridWeek');
                    button.querySelector('span').textContent = 'Week View';
                }
            });
        });

        function getStatusClass(status) {
            return {
                'draft': 'secondary',
                'submitted': 'warning',
                'approved': 'success',
                'rejected': 'danger'
            } [status] || 'primary';
        }
    </script>
@endsection
