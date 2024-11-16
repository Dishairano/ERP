@extends('layouts/contentNavbarLayout')

@section('title', 'Leave Calendar')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group">
                <a href="{{ route('leave-management.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="{{ route('leave-requests.index') }}" class="btn btn-info">
                    <i class="fas fa-list"></i> All Requests
                </a>
                @can('create', App\Models\LeaveRequest::class)
                    <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Request
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leave Calendar</h3>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-event.bg-success {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }
    .fc-event.bg-danger {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
    .fc-event.bg-warning {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
    }
    .fc-event.bg-secondary {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
    }
</style>
@endsection

@section('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            navLinks: true,
            editable: false,
            eventLimit: true,
            events: {
                url: '{{ route("leave-management.calendar.events") }}',
                error: function() {
                    alert('Error fetching leave requests');
                }
            },
            eventRender: function(event, element) {
                element.attr('title', event.extendedProps.reason);
                element.tooltip({
                    container: 'body',
                    delay: {
                        show: 50,
                        hide: 50
                    }
                });
            },
            eventClick: function(event) {
                window.location.href = '/leave-requests/' + event.id;
            },
            loading: function(bool) {
                $('#loading').toggle(bool);
            }
        });
    });
</script>
@endsection
