@extends('layouts/contentNavbarLayout')

@section('title', 'Task Management')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Task Statistics -->
        <div class="row mb-4">
            <div class="col-lg col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Total Tasks</h5>
                                <small class="text-muted">All project tasks</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ri-task-line"></i>
                                </span>
                            </div>
                        </div>
                        <h4 class="mb-0 mt-2">{{ $statistics['total'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Completed</h5>
                                <small class="text-muted">Finished tasks</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ri-checkbox-circle-line"></i>
                                </span>
                            </div>
                        </div>
                        <h4 class="mb-0 mt-2">{{ $statistics['completed'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">In Progress</h5>
                                <small class="text-muted">Active tasks</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ri-time-line"></i>
                                </span>
                            </div>
                        </div>
                        <h4 class="mb-0 mt-2">{{ $statistics['in_progress'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Pending</h5>
                                <small class="text-muted">Not started</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ri-hourglass-line"></i>
                                </span>
                            </div>
                        </div>
                        <h4 class="mb-0 mt-2">{{ $statistics['pending'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Overdue</h5>
                                <small class="text-muted">Past due date</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="ri-error-warning-line"></i>
                                </span>
                            </div>
                        </div>
                        <h4 class="mb-0 mt-2">{{ $statistics['overdue'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks List -->
        <div class="card">
            <div class="card-header border-bottom">
                <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                    <div class="col-md-4 task_status">
                        <select id="TaskStatus" class="form-select text-capitalize">
                            <option value=""> Select Status </option>
                            <option value="all" @if (request('status') == 'all') selected @endif>All</option>
                            <option value="pending" @if (request('status') == 'pending') selected @endif>Pending</option>
                            <option value="in_progress" @if (request('status') == 'in_progress') selected @endif>In Progress
                            </option>
                            <option value="completed" @if (request('status') == 'completed') selected @endif>Completed</option>
                            <option value="on_hold" @if (request('status') == 'on_hold') selected @endif>On Hold</option>
                            <option value="cancelled" @if (request('status') == 'cancelled') selected @endif>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-4 task_priority">
                        <select id="TaskPriority" class="form-select text-capitalize">
                            <option value=""> Select Priority </option>
                            <option value="all" @if (request('priority') == 'all') selected @endif>All</option>
                            <option value="low" @if (request('priority') == 'low') selected @endif>Low</option>
                            <option value="medium" @if (request('priority') == 'medium') selected @endif>Medium</option>
                            <option value="high" @if (request('priority') == 'high') selected @endif>High</option>
                            <option value="critical" @if (request('priority') == 'critical') selected @endif>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('projects.tasks.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i> Add New Task
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-tasks table border-top">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Project</th>
                            <th>Assigned To</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('projects.tasks.show', $task) }}"
                                                class="text-body text-truncate">
                                                <span class="fw-semibold">{{ $task->name }}</span>
                                            </a>
                                            <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-truncate">{{ $task->project->name }}</span>
                                </td>
                                <td>
                                    @if ($task->assignedUser)
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="avatar-wrapper">
                                                <div class="avatar avatar-sm me-2">
                                                    @if ($task->assignedUser->avatar)
                                                        <img src="{{ $task->assignedUser->avatar }}" alt="Avatar"
                                                            class="rounded-circle">
                                                    @else
                                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                                            {{ strtoupper(substr($task->assignedUser->name, 0, 2)) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-truncate">{{ $task->assignedUser->name }}</span>
                                                <small class="text-muted">{{ $task->assignedUser->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($task->due_date)
                                        <span
                                            class="badge @if ($task->isOverdue()) bg-label-danger @else bg-label-primary @endif">
                                            {{ $task->due_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">No due date</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $priorityClass = match ($task->priority) {
                                            'critical' => 'danger',
                                            'high' => 'warning',
                                            'medium' => 'info',
                                            default => 'success',
                                        };
                                    @endphp
                                    <span class="badge bg-label-{{ $priorityClass }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <div class="progress" style="height: 8px; width: 80px;">
                                            <div class="progress-bar" style="width: {{ $task->progress_percentage }}%"
                                                role="progressbar" aria-valuenow="{{ $task->progress_percentage }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small>{{ $task->progress_percentage }}%</small>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match ($task->status) {
                                            'completed' => 'success',
                                            'in_progress' => 'warning',
                                            'on_hold' => 'info',
                                            'cancelled' => 'danger',
                                            default => 'primary',
                                        };
                                    @endphp
                                    <span class="badge bg-label-{{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-inline-block">
                                        <button class="btn btn-sm btn-icon dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="{{ route('projects.tasks.show', $task) }}" class="dropdown-item">
                                                <i class="ri-eye-line me-1"></i> View
                                            </a>
                                            <a href="{{ route('projects.tasks.edit', $task) }}" class="dropdown-item">
                                                <i class="ri-pencil-line me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('projects.tasks.destroy', $task) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Are you sure you want to delete this task?')">
                                                    <i class="ri-delete-bin-line me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="card-footer">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTables
            const taskTable = $('.datatables-tasks').DataTable({
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,
                responsive: true,
                searching: true,
                ordering: true
            });

            // Handle status filter
            $('#TaskStatus').on('change', function() {
                const status = $(this).val();
                if (status) {
                    window.location.href = updateQueryStringParameter(window.location.href, 'status',
                        status);
                }
            });

            // Handle priority filter
            $('#TaskPriority').on('change', function() {
                const priority = $(this).val();
                if (priority) {
                    window.location.href = updateQueryStringParameter(window.location.href, 'priority',
                        priority);
                }
            });

            // Helper function to update URL parameters
            function updateQueryStringParameter(uri, key, value) {
                const re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
                const separator = uri.indexOf('?') !== -1 ? "&" : "?";

                if (uri.match(re)) {
                    return uri.replace(re, '$1' + key + "=" + value + '$2');
                } else {
                    return uri + separator + key + "=" + value;
                }
            }
        });
    </script>
@endsection
