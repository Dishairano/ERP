@extends('layouts/contentNavbarLayout')

@section('title', $task->title)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">{{ $task->title }}</h4>
                            <small class="text-muted">
                                <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a> >
                                <a href="{{ route('projects.tasks.index', $project->id) }}">Tasks</a>
                            </small>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('projects.tasks.index', $project->id) }}"
                                class="btn btn-outline-primary me-1">
                                <i data-feather="arrow-left"></i> Back to Tasks
                            </a>
                            <a href="{{ route('projects.tasks.edit', [$project->id, $task->id]) }}"
                                class="btn btn-primary me-1">
                                <i data-feather="edit"></i> Edit Task
                            </a>
                            <form action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this task?')">
                                    <i data-feather="trash-2"></i> Delete Task
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Description</h5>
                            <p class="mb-4">{{ $task->description ?? 'No description provided' }}</p>

                            <h5>Status & Priority</h5>
                            <div class="mb-4">
                                <span
                                    class="badge rounded-pill badge-light-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'info' : 'warning') }} me-1">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                                <span
                                    class="badge rounded-pill badge-light-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'info') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>

                            <h5>Progress</h5>
                            <div class="progress mb-4" style="height: 8px">
                                <div class="progress-bar" role="progressbar" style="width: {{ $task->progress }}%"
                                    aria-valuenow="{{ $task->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            @if ($task->comments && count($task->comments) > 0)
                                <h5>Comments</h5>
                                <div class="mb-4">
                                    @foreach ($task->comments as $comment)
                                        <div class="d-flex mb-2">
                                            <div class="avatar avatar-sm me-1">
                                                <span class="avatar-initial rounded-circle bg-light-primary">
                                                    {{ substr($comment['user'] ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0"><strong>{{ $comment['user'] ?? 'Unknown User' }}</strong>
                                                </p>
                                                <p class="text-muted mb-0">{{ $comment['comment'] }}</p>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <form action="{{ route('projects.tasks.add-comment', [$project->id, $task->id]) }}"
                                method="POST" class="mt-4">
                                @csrf
                                <div class="form-group">
                                    <label for="comment">Add Comment</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary mt-1">
                                    <i data-feather="message-square"></i> Post Comment
                                </button>
                            </form>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Task Details</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <i data-feather="briefcase" class="me-1"></i>
                                            <span class="fw-bold">Project:</span>
                                            <span>{{ $project->name }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="user" class="me-1"></i>
                                            <span class="fw-bold">Assigned To:</span>
                                            <span>{{ $task->assignedTo && $task->assignedTo->name ? $task->assignedTo->name : 'Unassigned' }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="calendar" class="me-1"></i>
                                            <span class="fw-bold">Start Date:</span>
                                            <span>{{ $task->start_date ? $task->start_date->format('M d, Y') : 'Not set' }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="calendar" class="me-1"></i>
                                            <span class="fw-bold">Due Date:</span>
                                            <span>{{ $task->due_date ? $task->due_date->format('M d, Y') : 'Not set' }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="clock" class="me-1"></i>
                                            <span class="fw-bold">Estimated Hours:</span>
                                            <span>{{ $task->estimated_hours ?? 'Not set' }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="clock" class="me-1"></i>
                                            <span class="fw-bold">Actual Hours:</span>
                                            <span>{{ $task->actual_hours ?? 'Not recorded' }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="dollar-sign" class="me-1"></i>
                                            <span class="fw-bold">Actual Cost:</span>
                                            <span>${{ number_format($task->actual_cost ?? 0, 2) }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="user" class="me-1"></i>
                                            <span class="fw-bold">Created By:</span>
                                            <span>{{ $task->createdBy && $task->createdBy->name ? $task->createdBy->name : 'Unknown' }}</span>
                                        </li>
                                        <li>
                                            <i data-feather="clock" class="me-1"></i>
                                            <span class="fw-bold">Created:</span>
                                            <span>{{ $task->created_at->format('M d, Y H:i') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @if ($task->attachments && count($task->attachments) > 0)
                                <div class="card mt-2">
                                    <div class="card-body">
                                        <h6>Attachments</h6>
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($task->attachments as $attachment)
                                                <li class="mb-1">
                                                    <i data-feather="paperclip" class="me-1"></i>
                                                    <a href="{{ $attachment['url'] }}"
                                                        target="_blank">{{ $attachment['name'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
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
