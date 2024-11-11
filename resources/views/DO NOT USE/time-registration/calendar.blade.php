@extends('layouts/contentNavbarLayout')

@section('title', 'Time Calendar')

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
                <h5 class="mb-0">Time Calendar</h5>
                <a href="{{ route('time-registration.create') }}" class="btn btn-primary">
                    Register Time
                </a>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- View Event Modal -->
    <div class="modal fade" id="viewEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Time Registration Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="eventDetails"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($registrations),
                eventClick: function(info) {
                    var event = info.event;
                    var content = `
                <div class="mb-3">
                    <h6>Project</h6>
                    <p>${event.title}</p>
                </div>
                <div class="mb-3">
                    <h6>Description</h6>
                    <p>${event.extendedProps.description}</p>
                </div>
                <div class="mb-3">
                    <h6>Date</h6>
                    <p>${event.start.toLocaleDateString()}</p>
                </div>
            `;
                    document.getElementById('eventDetails').innerHTML = content;
                    var modal = new bootstrap.Modal(document.getElementById('viewEventModal'));
                    modal.show();
                }
            });
            calendar.render();
        });
    </script>
@endsection
