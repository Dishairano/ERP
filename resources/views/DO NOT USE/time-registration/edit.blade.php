@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Time Registration')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Edit Time Registration</h4>
                                <p class="mb-0">Modify your time registration details</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <a href="{{ route('time-registration.index') }}" class="btn btn-label-secondary">
                                    <i class="ri-arrow-left-line"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Registration Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Time Registration Details</h5>
                            <span class="badge bg-label-warning">Pending Approval</span>
                        </div>
                    </div>
                    <form action="{{ route('time-registration.update', $timeRegistration ?? 1) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date"
                                        value="{{ $timeRegistration->date ?? date('Y-m-d') }}" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Project</label>
                                    <select class="form-select" name="project_id" required>
                                        <option value="">Select Project</option>
                                        <!-- Projects will be populated here -->
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Task</label>
                                    <select class="form-select" name="task_id" required>
                                        <option value="">Select Task</option>
                                        <!-- Tasks will be populated here -->
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <!-- Categories will be populated here -->
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control" name="start_time"
                                        value="{{ $timeRegistration->start_time ?? '09:00' }}" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control" name="end_time"
                                        value="{{ $timeRegistration->end_time ?? '17:00' }}" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="Describe the work done...">{{ $timeRegistration->description ?? '' }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Current Attachments</label>
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @forelse($timeRegistration->attachments ?? [] as $attachment)
                                            <div class="d-flex align-items-center border rounded p-2">
                                                <i class="ri-file-line me-2"></i>
                                                <span>{{ $attachment->name }}</span>
                                                <button type="button" class="btn btn-text-danger btn-sm ms-2">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>
                                        @empty
                                            <p class="text-muted mb-0">No attachments</p>
                                        @endforelse
                                    </div>

                                    <label class="form-label">Add New Attachments</label>
                                    <input type="file" class="form-control" name="attachments[]" multiple>
                                    <small class="text-muted">Upload any relevant files (optional)</small>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="billable" id="billable"
                                            {{ $timeRegistration->billable ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="billable">
                                            This time is billable
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="overtime" id="overtime"
                                            {{ $timeRegistration->overtime ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="overtime">
                                            This is overtime work
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Tags</label>
                                    <input type="text" class="form-control" name="tags"
                                        placeholder="Enter tags separated by commas"
                                        value="{{ $timeRegistration->tags ?? '' }}">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Comments</label>
                                    <div class="mb-3">
                                        @forelse($timeRegistration->comments ?? [] as $comment)
                                            <div class="d-flex mb-3">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="mb-1">
                                                        <span class="fw-bold">{{ $comment->user->name ?? 'User' }}</span>
                                                        <small
                                                            class="text-muted ms-2">{{ $comment->created_at ?? 'Just now' }}</small>
                                                    </div>
                                                    <p class="mb-0">{{ $comment->content ?? 'Comment text' }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted mb-0">No comments yet</p>
                                        @endforelse
                                    </div>
                                    <textarea class="form-control" name="comment" rows="2" placeholder="Add a comment..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-label-danger me-2" data-bs-toggle="modal"
                                data-bs-target="#deleteConfirmationModal">Delete</button>
                            <button type="button" class="btn btn-label-secondary me-2"
                                onclick="history.back()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Time Registration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Time Registration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this time registration? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('time-registration.destroy', $timeRegistration ?? 1) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any necessary JavaScript functionality
            // For example, you might want to add dynamic task loading based on project selection
            const projectSelect = document.querySelector('select[name="project_id"]');
            const taskSelect = document.querySelector('select[name="task_id"]');

            if (projectSelect && taskSelect) {
                projectSelect.addEventListener('change', function() {
                    // Load tasks for selected project
                    const projectId = this.value;
                    if (projectId) {
                        // Make API call to get tasks
                        // Update taskSelect options
                    }
                });
            }
        });
    </script>
@endsection
