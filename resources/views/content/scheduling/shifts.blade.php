@extends('layouts/contentNavbarLayout')

@section('title', 'Work Shifts')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group">
                @can('create_shifts')
                    <a href="{{ route('scheduling.shifts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Shift
                    </a>
                @endcan
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#filterModal">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Shift Calendar</h3>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upcoming Shifts</h3>
                </div>
                <div class="card-body">
                    @forelse($upcomingShifts as $shift)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-1">{{ $shift->typeDisplay }}</h5>
                                <span class="badge badge-{{ $shift->isActive ? 'success' : 'info' }}">
                                    {{ $shift->isActive ? 'Active' : 'Upcoming' }}
                                </span>
                            </div>
                            <p class="mb-1">
                                <i class="fas fa-user"></i> {{ $shift->user->name }}<br>
                                <i class="fas fa-clock"></i> {{ $shift->start_time->format('M d, Y H:i') }} - {{ $shift->end_time->format('H:i') }}<br>
                                @if($shift->location)
                                    <i class="fas fa-map-marker-alt"></i> {{ $shift->location }}
                                @endif
                            </p>
                            @if($shift->notes)
                                <small class="text-muted">{{ $shift->notes }}</small>
                            @endif
                        </div>
                        @unless($loop->last)
                            <hr>
                        @endunless
                    @empty
                        <p class="text-muted">No upcoming shifts found.</p>
                    @endforelse
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">My Schedule</h3>
                </div>
                <div class="card-body">
                    @forelse($userShifts as $shift)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-1">{{ $shift->typeDisplay }}</h5>
                                <span class="badge badge-{{ match($shift->type) {
                                    'morning' => 'primary',
                                    'afternoon' => 'success',
                                    'evening' => 'warning',
                                    'night' => 'info',
                                    default => 'secondary'
                                } }}">
                                    {{ $shift->duration }}h
                                </span>
                            </div>
                            <p class="mb-1">
                                <i class="fas fa-calendar"></i> {{ $shift->start_time->format('M d, Y') }}<br>
                                <i class="fas fa-clock"></i> {{ $shift->start_time->format('H:i') }} - {{ $shift->end_time->format('H:i') }}<br>
                                @if($shift->location)
                                    <i class="fas fa-map-marker-alt"></i> {{ $shift->location }}
                                @endif
                            </p>
                            @if($shift->notes)
                                <small class="text-muted">{{ $shift->notes }}</small>
                            @endif
                        </div>
                        @unless($loop->last)
                            <hr>
                        @endunless
                    @empty
                        <p class="text-muted">No scheduled shifts found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="filterForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Shifts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="filterType">Shift Type</label>
                        <select class="form-control" id="filterType" name="type">
                            <option value="">All Types</option>
                            <option value="morning">Morning</option>
                            <option value="afternoon">Afternoon</option>
                            <option value="evening">Evening</option>
                            <option value="night">Night</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filterLocation">Location</label>
                        <input type="text" class="form-control" id="filterLocation" name="location">
                    </div>
                    <div class="form-group">
                        <label>Date Range</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="filterStartDate" name="start_date">
                            <input type="date" class="form-control" id="filterEndDate" name="end_date">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
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
    .fc-event.bg-primary {
        background-color: #007bff !important;
        border-color: #007bff !important;
    }
    .fc-event.bg-success {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }
    .fc-event.bg-warning {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
    }
    .fc-event.bg-info {
        background-color: #17a2b8 !important;
        border-color: #17a2b8 !important;
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
                url: '{{ route("scheduling.shifts.events") }}',
                error: function() {
                    alert('Error fetching shifts');
                }
            },
            eventRender: function(event, element) {
                element.attr('title', event.extendedProps.notes || event.title);
                element.tooltip({
                    container: 'body',
                    delay: {
                        show: 50,
                        hide: 50
                    }
                });
            },
            eventClick: function(event) {
                window.location.href = '{{ url("/scheduling/shifts") }}/' + event.id;
            },
            loading: function(bool) {
                $('#loading').toggle(bool);
            }
        });

        // Handle filter form submission
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            var filters = $(this).serialize();
            $('#calendar').fullCalendar('refetchEvents', {
                data: filters
            });
            $('#filterModal').modal('hide');
        });
    });
</script>
@endsection
