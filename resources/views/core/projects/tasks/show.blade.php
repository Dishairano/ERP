@extends('layouts/contentNavbarLayout')

@section('title', 'Task Details')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/timeline/timeline.css') }}" />
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Task Header -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="me-2">
                                <h5 class="mb-1">{{ $task->name }}</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-label-primary">{{ $task->project->name }}</span>
                                    @php
                                        $priorityClass = match ($task->priority) {
                                            'critical' => 'danger',
                                            'high' => 'warning',
                                            'medium' => 'info',
                                            default => 'success',
                                        };
                                        $statusClass = match ($task->status) {
                                            'completed' => 'success',
                                            'in_progress' => 'warning',
                                            'on_hold' => 'info',
                                            'cancelled' => 'danger',
                                            default => 'primary',
                                        };
                                    @endphp
                                    <span class="badge bg-label-{{ $priorityClass }}">{{ ucfirst($task->priority) }}</span>
                                    <span
                                        class="badge bg-label-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('projects.tasks.edit', $task) }}" class="btn btn-primary">
                                    <i class="ri-pencil-line me-1"></i> Edit Task
                                </a>
                                <a href="{{ route('projects.tasks.index') }}" class="btn btn-secondary">
                                    <i class="ri-arrow-left-line me-1"></i> Back to Tasks
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Details -->
            <div class="col-xl-8 col-lg-7">
                <!-- Description -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Description</h5>
                    </div>
                    <div class="card-body">
                        {{ $task->description ?? 'No description provided.' }}
                    </div>
                </div>

                <!-- Comments -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Comments</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addCommentModal">
                            <i class="ri-add-line me-1"></i> Add Comment
                        </button>
                    </div>
                    <div class="card-body">
                        @if ($task->comments && count($task->comments) > 0)
                            <div class="timeline">
                                @foreach ($task->comments as $comment)
                                    <div class="timeline-item">
                                        <span class="timeline-point timeline-point-primary"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-1">
                                                <h6 class="mb-0">{{ $comment['user_name'] ?? 'User' }}</h6>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-0">{{ $comment['comment'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No comments yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Attachments -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Attachments</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addAttachmentModal">
                            <i class="ri-upload-line me-1"></i> Upload Attachment
                        </button>
                    </div>
                    <div class="card-body">
                        @if ($task->attachments && count($task->attachments) > 0)
                            <div class="row g-3">
                                @foreach ($task->attachments as $attachment)
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="ri-file-line fs-3 me-2"></i>
                                                    <div>
                                                        <h6 class="mb-0">{{ $attachment['filename'] }}</h6>
                                                        <small
                                                            class="text-muted">{{ \Carbon\Carbon::parse($attachment['uploaded_at'])->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                                <a href="{{ Storage::url($attachment['path']) }}"
                                                    class="btn btn-outline-primary btn-sm w-100" target="_blank">
                                                    <i class="ri-download-line me-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No attachments yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Task Info Sidebar -->
            <div class="col-xl-4 col-lg-5">
                <!-- Task Progress -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Progress</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <small>Task Completion</small>
                            <small>{{ $task->progress_percentage }}%</small>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" style="width: {{ $task->progress_percentage }}%"></div>
                        </div>
                        <form action="{{ route('projects.tasks.update-progress', $task) }}" method="POST"
                            class="d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" class="form-control" name="progress_percentage" min="0"
                                max="100" value="{{ $task->progress_percentage }}">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>

                <!-- Task Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Details</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Assigned To</dt>
                            <dd class="col-sm-8">
                                @if ($task->assignedUser)
                                    <div class="d-flex align-items-center">
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
                                        {{ $task->assignedUser->name }}
                                    </div>
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Start Date</dt>
                            <dd class="col-sm-8">
                                {{ $task->start_date ? $task->start_date->format('M d, Y H:i') : 'Not set' }}
                            </dd>

                            <dt class="col-sm-4">Due Date</dt>
                            <dd class="col-sm-8">
                                @if ($task->due_date)
                                    <span class="@if ($task->isOverdue()) text-danger @endif">
                                        {{ $task->due_date->format('M d, Y H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Estimated</dt>
                            <dd class="col-sm-8">{{ $task->estimated_hours ?? 0 }} hours</dd>

                            <dt class="col-sm-4">Actual</dt>
                            <dd class="col-sm-8">{{ $task->actual_hours ?? 0 }} hours</dd>

                            <dt class="col-sm-4">Created</dt>
                            <dd class="col-sm-8">{{ $task->created_at->format('M d, Y H:i') }}</dd>

                            <dt class="col-sm-4">Last Updated</dt>
                            <dd class="col-sm-8">{{ $task->updated_at->format('M d, Y H:i') }}</dd>

                            @if ($task->completed_at)
                                <dt class="col-sm-4">Completed</dt>
                                <dd class="col-sm-8">{{ $task->completed_at->format('M d, Y H:i') }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Dependencies -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Dependencies</h5>
                    </div>
                    <div class="card-body">
                        @if ($task->dependencies && count($task->dependencies) > 0)
                            <ul class="list-group list-group-flush">
                                @foreach ($task->dependencies as $dependencyId)
                                    @php $dependency = \App\Models\CoreProjectTaskModal::find($dependencyId); @endphp
                                    @if ($dependency)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <a
                                                    href="{{ route('projects.tasks.show', $dependency) }}">{{ $dependency->name }}</a>
                                                <br>
                                                <small class="text-muted">{{ $dependency->status }}</small>
                                            </div>
                                            <span
                                                class="badge bg-primary rounded-pill">{{ $dependency->progress_percentage }}%</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No dependencies.</p>
                        @endif
                    </div>
                </div>

                <!-- Tags -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tags</h5>
                    </div>
                    <div class="card-body">
                        @if ($task->tags && count($task->tags) > 0)
                            @foreach ($task->tags as $tag)
                                <span class="badge bg-primary me-1">{{ $tag }}</span>
                            @endforeach
                        @else
                            <p class="text-muted mb-0">No tags.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Comment Modal -->
    <div class="modal fade" id="addCommentModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('projects.tasks.add-comment', $task) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Comment</label>
                        <textarea class="form-control" name="comment" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Comment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Attachment Modal -->
    <div class="modal fade" id="addAttachmentModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('projects.tasks.add-attachment', $task) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Attachment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">File</label>
                        <input type="file" class="form-control" name="attachment" required>
                        <small class="text-muted">Max file size: 10MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
@endsection
