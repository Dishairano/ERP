@extends('layouts/contentNavbarLayout')

@section('title', $project->name . ' - Tasks')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">{{ $project->name }} - Tasks</h4>
                            <small class="text-muted">Manage and track project tasks</small>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-primary me-1">
                                <i data-feather="arrow-left"></i> Back to Project
                            </a>
                            <a href="{{ route('projects.tasks.create', $project->id) }}" class="btn btn-primary">
                                <i data-feather="plus"></i> New Task
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Assigned To</th>
                                    <th>Due Date</th>
                                    <th>Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>
                                            <a
                                                href="{{ route('projects.tasks.show', [$project->id, $task->id]) }}">{{ $task->title }}</a>
                                        </td>
                                        <td>
                                            <span
                                                class="badge rounded-pill badge-light-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'info' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge rounded-pill badge-light-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'info') }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($task->assignedTo && $task->assignedTo->name)
                                                {{ $task->assignedTo->name }}
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($task->due_date)
                                                {{ $task->due_date->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">No due date</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 6px">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $task->progress }}%"
                                                    aria-valuenow="{{ $task->progress }}" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
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
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Are you sure you want to delete this task?')">
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
                                        <td colspan="7" class="text-center">No tasks found for this project</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($tasks->hasPages())
                        <div class="d-flex justify-content-center mt-2">
                            {{ $tasks->links() }}
                        </div>
                    @endif
                </div>
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
