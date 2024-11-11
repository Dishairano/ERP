@extends('layouts/contentNavbarLayout')

@section('title', 'Production Scheduling')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Production /</span> Scheduling
        </h4>

        <div class="row">
            <!-- Schedule Calendar -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Production Schedule Calendar</h5>
                    </div>
                    <div class="card-body">
                        <div id="production-calendar"></div>
                    </div>
                </div>
            </div>

            <!-- Scheduled Tasks -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Scheduled Tasks</h5>
                        <div>
                            <button type="button" class="btn btn-primary">
                                <i class="ri-add-line me-1"></i> Schedule Task
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Task ID</th>
                                        <th>Description</th>
                                        <th>Work Center</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scheduledTasks as $task)
                                        <tr>
                                            <td>{{ $task->id }}</td>
                                            <td>{{ $task->description }}</td>
                                            <td>{{ $task->work_center }}</td>
                                            <td>{{ $task->start_time }}</td>
                                            <td>{{ $task->end_time }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $task->priority === 'High' ? 'danger' : ($task->priority === 'Medium' ? 'warning' : 'info') }}">
                                                    {{ $task->priority }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $task->status === 'Completed'
                                                        ? 'success'
                                                        : ($task->status === 'In Progress'
                                                            ? 'info'
                                                            : ($task->status === 'Scheduled'
                                                                ? 'warning'
                                                                : 'danger')) }}">
                                                    {{ $task->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-eye-line me-2"></i> View Details
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-pencil-line me-2"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-calendar-line me-2"></i> Reschedule
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-delete-bin-line me-2"></i> Cancel
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $scheduledTasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize production calendar
                var calendarEl = document.getElementById('production-calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: @json($calendarEvents),
                    editable: true,
                    selectable: true,
                    selectMirror: true,
                    dayMaxEvents: true
                });
                calendar.render();
            });
        </script>
    @endpush
@endsection
