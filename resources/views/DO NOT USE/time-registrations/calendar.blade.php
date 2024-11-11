@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Calendar')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <style>
        .fc-event {
            cursor: pointer;
        }

        .fc-toolbar-title {
            font-size: 1.2em !important;
        }

        .fc-header-toolbar {
            margin-bottom: 1em !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Time Registration Calendar</h5>
                        <a href="{{ route('time-registrations.create') }}" class="btn btn-primary">
                            <i class="ri-add-line"></i> New Time Entry
                        </a>
                    </div>

                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($timeRegistrations),
                slotMinTime: '06:00:00',
                slotMaxTime: '22:00:00',
                allDaySlot: false,
                nowIndicator: true,
                height: 'auto',
                eventClick: function(info) {
                    window.location.href = info.event.url;
                }
            });
            calendar.render();
        });
    </script>
@endpush
