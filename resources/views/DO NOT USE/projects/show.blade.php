@extends('layouts/contentNavbarLayout')

@section('title', $project->name)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h4 class="mb-1">{{ $project->name }}</h4>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-label-secondary">{{ $project->code }}</span>
                                    <span class="badge bg-{{ config("project.statuses.{$project->status}.color") }}">
                                        {{ config("project.statuses.{$project->status}.name") }}
                                    </span>
                                    <span class="badge bg-{{ $healthClass }}">{{ ucfirst($healthStatus) }}</span>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary">
                                    <i class="ri-pencil-line me-1"></i> Edit Project
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="ri-more-2-line"></i> More Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('projects.dashboard', $project) }}">
                                                <i class="ri-dashboard-line me-2"></i> Project Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('projects.tasks.create', $project) }}">
                                                <i class="ri-task-line me-2"></i> Add Task
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('projects.risks.create', $project) }}">
                                                <i class="ri-error-warning-line me-2"></i> Add Risk
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('projects.destroy', $project) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Are you sure you want to delete this project?')">
                                                    <i class="ri-delete-bin-line me-2"></i> Delete Project
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <p class="mb-0">{{ $project->description }}</p>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-semibold">Manager:</span>
                                    <span>{{ $project->manager->name }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-semibold">Start Date:</span>
                                    <span>{{ $project->start_date->format('M d, Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-semibold">End Date:</span>
                                    <span @class(['text-danger' => $project->isOverdue()])>
                                        {{ $project->end_date->format('M d, Y') }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Budget:</span>
                                    <span>${{ number_format($project->budget, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Metrics -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <h5 class="mb-1">Progress</h5>
                                <div class="d-flex align-items-center my-2">
                                    <h4 class="mb-0 me-2">{{ number_format($progress, 0) }}%</h4>
                                    <span @class([
                                        'badge',
                                        'bg-success' => $progress >= 90,
                                        'bg-warning' => $progress >= 50 && $progress < 90,
                                        'bg-danger' => $progress < 50,
                                    ])>
                                        {{ $progress >= 90 ? 'On Track' : ($progress >= 50 ? 'In Progress' : 'Behind') }}
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ri-line-chart-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <h5 class="mb-1">Budget Utilization</h5>
                                <div class="d-flex align-items-center my-2">
                                    <h4 class="mb-0 me-2">{{ number_format($budgetUtilization, 0) }}%</h4>
                                    <span @class([
                                        'badge',
                                        'bg-success' => $budgetUtilization <= 90,
                                        'bg-warning' => $budgetUtilization > 90 && $budgetUtilization <= 100,
                                        'bg-danger' => $budgetUtilization > 100,
                                    ])>
                                        {{ $budgetUtilization <= 90 ? 'Under Budget' : ($budgetUtilization <= 100 ? 'Near Limit' : 'Over Budget') }}
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div @class([
                                        'progress-bar',
                                        'bg-success' => $budgetUtilization <= 90,
                                        'bg-warning' => $budgetUtilization > 90 && $budgetUtilization <= 100,
                                        'bg-danger' => $budgetUtilization > 100,
                                    ]) role="progressbar"
                                        style="width: {{ min($budgetUtilization, 100) }}%"></div>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <h5 class="mb-1">Tasks</h5>
                                <div class="d-flex align-items-center my-2">
                                    <h4 class="mb-0 me-2">{{ $overdueTasks }}</h4>
                                    <span class="badge bg-danger">Overdue</span>
                                </div>
                                <p class="mb-0">{{ $highPriorityTasks }} High Priority</p>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ri-task-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <h5 class="mb-1">Risks</h5>
                                <div class="d-flex align-items-center my-2">
                                    <h4 class="mb-0 me-2">{{ $criticalRisks }}</h4>
                                    <span class="badge bg-danger">Critical</span>
                                </div>
                                <p class="mb-0">{{ $project->risks->where('status', 'mitigated')->count() }} Mitigated
                                </p>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="ri-error-warning-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phases and Tasks -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Project Phases</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline-vertical">
                            @foreach ($project->phases as $phase)
                                <div class="timeline-item">
                                    <div @class([
                                        'timeline-indicator timeline-indicator-primary',
                                        'border-success' => $phase->status === 'completed',
                                        'border-warning' => $phase->status === 'in_progress',
                                        'border-danger' => $phase->isOverdue(),
                                    ])>
                                        <i class="ri-checkbox-blank-circle-fill"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">{{ $phase->name }}</h6>
                                            <span @class([
                                                'badge',
                                                'bg-success' => $phase->status === 'completed',
                                                'bg-warning' => $phase->status === 'in_progress',
                                                'bg-danger' => $phase->isOverdue(),
                                            ])>
                                                {{ ucfirst($phase->status) }}
                                            </span>
                                        </div>
                                        <p class="mb-2">{{ $phase->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                {{ $phase->start_date->format('M d') }} -
                                                {{ $phase->end_date->format('M d, Y') }}
                                            </small>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress" style="width: 100px; height: 4px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $phase->getProgress() }}%"></div>
                                                </div>
                                                <small>{{ number_format($phase->getProgress(), 0) }}%</small>
                                            </div>
                                        </div>

                                        <!-- Phase Tasks -->
                                        @if ($phase->tasks->isNotEmpty())
                                            <div class="mt-3">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-borderless">
                                                        <tbody>
                                                            @foreach ($phase->tasks as $task)
                                                                <tr>
                                                                    <td style="width: 1%">
                                                                        <i @class([
                                                                            'ri-checkbox-blank-circle-fill fs-xs',
                                                                            'text-success' => $task->status === 'completed',
                                                                            'text-warning' => $task->status === 'in_progress',
                                                                            'text-danger' => $task->isOverdue(),
                                                                        ])></i>
                                                                    </td>
                                                                    <td>
                                                                        <a
                                                                            href="{{ route('projects.tasks.show', [$project, $task]) }}">
                                                                            {{ $task->name }}
                                                                        </a>
                                                                    </td>
                                                                    <td class="text-end">
                                                                        <span
                                                                            class="badge bg-label-{{ config("project.tasks.priorities.{$task->priority}.color") }}">
                                                                            {{ config("project.tasks.priorities.{$task->priority}.name") }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-end" style="width: 15%">
                                                                        {{ $task->due_date->format('M d, Y') }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline-vertical {
            position: relative;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .timeline-vertical .timeline-item {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 1.5rem;
        }

        .timeline-vertical .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-vertical .timeline-indicator {
            position: absolute;
            left: 0;
            top: 0;
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-vertical .timeline-indicator i {
            font-size: 0.75rem;
        }

        .timeline-vertical .timeline-content {
            position: relative;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
        }

        .fs-xs {
            font-size: 0.625rem;
        }
    </style>
@endpush
