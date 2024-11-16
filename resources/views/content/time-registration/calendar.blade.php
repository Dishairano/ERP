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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Time Registration Calendar</h5>
                    <div>
                        <a href="{{ route('time-registration.create') }}" class="btn btn-primary me-2">
                            <i class="ri-add-line me-1"></i> New Registration
                        </a>
                        <a href="{{ route('time-registration.index') }}" class="btn btn-secondary">
                            <i class="ri-list-check me-1"></i> List View
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Time Registration Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6 class="text-muted">Project - Task</h6>
                    <p id="eventTitle"></p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted">Hours</h6>
                    <p id="eventHours"></p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted">Description</h6>
                    <p id="eventDescription"></p>
                </div>
                <div>
                    <h6 class="text-muted">Status</h6>
                    <span id="eventStatus" class="badge"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="viewDetailsBtn" class="btn btn-primary">View Details</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const registrations = @json($registrations);
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: registrations,
        eventClick: function(info) {
            const event = info.event;
            const modal = document.getElementById('eventDetailsModal');

            // Update modal content
            document.getElementById('eventTitle').textContent = event.title;
            document.getElementById('eventHours').textContent = event.extendedProps.hours + ' hours';
            document.getElementById('eventDescription').textContent = event.extendedProps.description;

            const statusBadge = document.getElementById('eventStatus');
            statusBadge.textContent = event.extendedProps.status.toUpperCase();
            statusBadge.className = 'badge bg-' + getStatusClass(event.extendedProps.status);

            // Update view details link
            document.getElementById('viewDetailsBtn').href = '/time-registration/' + event.extendedProps.id;

            // Show modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    });

    calendar.render();

    function getStatusClass(status) {
        switch (status) {
            case 'approved':
                return 'success';
            case 'rejected':
                return 'danger';
            case 'submitted':
                return 'warning';
            default:
                return 'secondary';
        }
    }
});
</script>
@endsection
