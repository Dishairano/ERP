@extends('layouts/contentNavbarLayout')

@section('title', $project->name)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">{{ $project->name }}</h4>
                            <small class="text-muted">Project Overview</small>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary me-1">
                                <i data-feather="edit"></i> Edit Project
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Description</h5>
                            <p class="mb-4">{{ $project->description }}</p>

                            <!-- Project Status -->
                            <div class="mb-4">
                                <h5>Status</h5>
                                <span
                                    class="badge rounded-pill badge-light-{{ $project->status === 'completed' ? 'success' : ($project->status === 'in_progress' ? 'info' : 'warning') }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                                <span
                                    class="badge rounded-pill badge-light-{{ $project->priority === 'high' ? 'danger' : ($project->priority === 'medium' ? 'warning' : 'info') }} ms-1">
                                    {{ ucfirst($project->priority) }} Priority
                                </span>
                            </div>

                            <!-- Tasks Section -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Tasks</h5>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#createTaskModal">
                                        <i data-feather="plus"></i> New Task
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Assigned To</th>
                                                <th>Due Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($project->tasks()->latest()->take(5)->get() as $task)
                                                <tr>
                                                    <td>
                                                        <a
                                                            href="{{ route('projects.tasks.show', [$project->id, $task->id]) }}">{{ $task->title }}</a>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge rounded-pill badge-light-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'info' : 'warning') }}">
                                                            {{ ucfirst($task->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $task->assignedTo?->name ?? 'Unassigned' }}
                                                    </td>
                                                    <td>
                                                        {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                class="btn btn-sm dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('projects.tasks.edit', [$project->id, $task->id]) }}">
                                                                    <i data-feather="edit-2" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a>
                                                                <form
                                                                    action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item"
                                                                        onclick="return confirm('Are you sure?')">
                                                                        <i data-feather="trash" class="me-50"></i>
                                                                        <span>Delete</span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No tasks yet</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if ($project->tasks()->count() > 5)
                                    <div class="text-center mt-2">
                                        <a href="{{ route('projects.tasks.index', $project->id) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            View All Tasks
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Risks Section -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Risks</h5>
                                    <div>
                                        <a href="{{ route('projects.risks.matrix', $project->id) }}"
                                            class="btn btn-outline-primary btn-sm me-1">
                                            <i data-feather="grid"></i> Risk Matrix
                                        </a>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#createRiskModal">
                                            <i data-feather="plus"></i> New Risk
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Category</th>
                                                <th>Severity</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($project->risks()->latest()->take(5)->get() as $risk)
                                                <tr>
                                                    <td>
                                                        <a
                                                            href="{{ route('projects.risks.show', [$project->id, $risk->id]) }}">{{ $risk->title }}</a>
                                                    </td>
                                                    <td>{{ $risk->category }}</td>
                                                    <td>
                                                        @php
                                                            $riskLevel = $risk->severity * $risk->likelihood;
                                                            $severityClass =
                                                                $riskLevel >= 16
                                                                    ? 'danger'
                                                                    : ($riskLevel >= 9
                                                                        ? 'warning'
                                                                        : ($riskLevel >= 4
                                                                            ? 'info'
                                                                            : 'success'));
                                                        @endphp
                                                        <span class="badge rounded-pill badge-light-{{ $severityClass }}">
                                                            Level {{ $risk->severity }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge rounded-pill badge-light-{{ $risk->status === 'mitigated' ? 'success' : ($risk->status === 'assessed' ? 'info' : 'warning') }}">
                                                            {{ ucfirst($risk->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                class="btn btn-sm dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('projects.risks.edit', [$project->id, $risk->id]) }}">
                                                                    <i data-feather="edit-2" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a>
                                                                <form
                                                                    action="{{ route('projects.risks.destroy', [$project->id, $risk->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item"
                                                                        onclick="return confirm('Are you sure?')">
                                                                        <i data-feather="trash" class="me-50"></i>
                                                                        <span>Delete</span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No risks identified yet</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if ($project->risks()->count() > 5)
                                    <div class="text-center mt-2">
                                        <a href="{{ route('projects.risks.index', $project->id) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            View All Risks
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Project Details</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <i data-feather="user" class="me-1"></i>
                                            <span class="fw-bold">Manager:</span>
                                            <span>{{ $project->manager?->name ?? 'Not assigned' }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="calendar" class="me-1"></i>
                                            <span class="fw-bold">Start Date:</span>
                                            <span>{{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="calendar" class="me-1"></i>
                                            <span class="fw-bold">End Date:</span>
                                            <span>{{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not set' }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="dollar-sign" class="me-1"></i>
                                            <span class="fw-bold">Budget:</span>
                                            <span>${{ number_format($project->budget ?? 0, 2) }}</span>
                                        </li>
                                        <li>
                                            <i data-feather="percent" class="me-1"></i>
                                            <span class="fw-bold">Progress:</span>
                                            <div class="progress mt-1" style="height: 6px">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $project->progress }}%"
                                                    aria-valuenow="{{ $project->progress }}" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Project Stats -->
                            <div class="card mt-2">
                                <div class="card-body">
                                    <h6>Quick Stats</h6>
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <small class="fw-bold">Tasks</small>
                                            <h3 class="mb-0">{{ $project->tasks()->count() }}</h3>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <small class="fw-bold">Risks</small>
                                            <h3 class="mb-0">{{ $project->risks()->count() }}</h3>
                                        </div>
                                        <div class="col-6">
                                            <small class="fw-bold">High Priority Tasks</small>
                                            <h3 class="mb-0">{{ $project->tasks()->where('priority', 'high')->count() }}
                                            </h3>
                                        </div>
                                        <div class="col-6">
                                            <small class="fw-bold">High Impact Risks</small>
                                            <h3 class="mb-0">
                                                {{ $project->risks()->where('severity', '>=', 4)->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Task Modal -->
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('projects.tasks.store', $project->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="assigned_to">Assign To</label>
                            <select class="form-select" id="assigned_to" name="assigned_to" required>
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="due_date">Due Date</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="priority">Priority</label>
                                    <select class="form-select" id="priority" name="priority" required>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="estimated_hours">Estimated Hours</label>
                            <input type="number" class="form-control" id="estimated_hours" name="estimated_hours"
                                min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Risk Modal -->
    <div class="modal fade" id="createRiskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Risk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('projects.risks.store', $project->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="risk_title">Title</label>
                            <input type="text" class="form-control" id="risk_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="risk_description">Description</label>
                            <textarea class="form-control" id="risk_description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="technical">Technical</option>
                                <option value="schedule">Schedule</option>
                                <option value="resource">Resource</option>
                                <option value="cost">Cost</option>
                                <option value="quality">Quality</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="severity">Severity (1-5)</label>
                                    <input type="number" class="form-control" id="severity" name="severity"
                                        min="1" max="5" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="likelihood">Likelihood (1-5)</label>
                                    <input type="number" class="form-control" id="likelihood" name="likelihood"
                                        min="1" max="5" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="impact">Impact</label>
                            <textarea class="form-control" id="impact" name="impact" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="mitigation_strategy">Mitigation Strategy</label>
                            <textarea class="form-control" id="mitigation_strategy" name="mitigation_strategy" rows="2" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="due_date">Due Date</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="owner">Owner</label>
                                    <input type="text" class="form-control" id="owner" name="owner" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="identified">Identified</option>
                                <option value="assessed">Assessed</option>
                                <option value="mitigated">Mitigated</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Risk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(function() {
            'use strict';

            // Initialize feather icons
            feather.replace();
        });
    </script>
@endsection
