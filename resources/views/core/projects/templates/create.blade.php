@extends('layouts/contentNavbarLayout')

@section('title', 'Create Project Template')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Create Project Template</h5>
                        <a href="{{ route('projects.templates.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to Templates
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('projects.templates.store') }}" method="POST" id="templateForm">
                            @csrf

                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="name">Template Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="category">Category</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category"
                                        name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="Development"
                                            {{ old('category') == 'Development' ? 'selected' : '' }}>Development</option>
                                        <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>
                                            Marketing</option>
                                        <option value="Research" {{ old('category') == 'Research' ? 'selected' : '' }}>
                                            Research</option>
                                        <option value="Design" {{ old('category') == 'Design' ? 'selected' : '' }}>Design
                                        </option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="estimated_duration">Estimated Duration</label>
                                    <input type="number"
                                        class="form-control @error('estimated_duration') is-invalid @enderror"
                                        id="estimated_duration" name="estimated_duration"
                                        value="{{ old('estimated_duration') }}" min="1" required>
                                    @error('estimated_duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="duration_unit">Duration Unit</label>
                                    <select class="form-select @error('duration_unit') is-invalid @enderror"
                                        id="duration_unit" name="duration_unit" required>
                                        <option value="days" {{ old('duration_unit') == 'days' ? 'selected' : '' }}>Days
                                        </option>
                                        <option value="weeks" {{ old('duration_unit') == 'weeks' ? 'selected' : '' }}>
                                            Weeks</option>
                                        <option value="months" {{ old('duration_unit') == 'months' ? 'selected' : '' }}>
                                            Months</option>
                                    </select>
                                    @error('duration_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="tags">Tags</label>
                                    <select class="select2 form-select @error('tags') is-invalid @enderror" id="tags"
                                        name="tags[]" multiple>
                                        <option value="Agile" {{ in_array('Agile', old('tags', [])) ? 'selected' : '' }}>
                                            Agile</option>
                                        <option value="Waterfall"
                                            {{ in_array('Waterfall', old('tags', [])) ? 'selected' : '' }}>Waterfall
                                        </option>
                                        <option value="Sprint" {{ in_array('Sprint', old('tags', [])) ? 'selected' : '' }}>
                                            Sprint</option>
                                        <option value="MVP" {{ in_array('MVP', old('tags', [])) ? 'selected' : '' }}>MVP
                                        </option>
                                    </select>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Default Tasks -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6>Default Tasks</h6>
                                    <div id="defaultTasks">
                                        <div class="task-item mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Task Name</label>
                                                            <input type="text" class="form-control"
                                                                name="default_tasks[0][name]" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Priority</label>
                                                            <select class="form-select" name="default_tasks[0][priority]"
                                                                required>
                                                                <option value="low">Low</option>
                                                                <option value="medium">Medium</option>
                                                                <option value="high">High</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label">Description</label>
                                                            <textarea class="form-control" name="default_tasks[0][description]" rows="2" required></textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Duration</label>
                                                            <input type="number" class="form-control"
                                                                name="default_tasks[0][duration]" min="1" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Duration Unit</label>
                                                            <select class="form-select"
                                                                name="default_tasks[0][duration_unit]" required>
                                                                <option value="hours">Hours</option>
                                                                <option value="days">Days</option>
                                                                <option value="weeks">Weeks</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addTask()">
                                        <i class="ri-add-line me-1"></i> Add Task
                                    </button>
                                </div>
                            </div>

                            <!-- Default Milestones -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6>Default Milestones</h6>
                                    <div id="defaultMilestones">
                                        <div class="milestone-item mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Milestone Name</label>
                                                            <input type="text" class="form-control"
                                                                name="default_milestones[0][name]" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Due Day</label>
                                                            <input type="number" class="form-control"
                                                                name="default_milestones[0][due_day]" min="1"
                                                                required>
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label">Description</label>
                                                            <textarea class="form-control" name="default_milestones[0][description]" rows="2" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="addMilestone()">
                                        <i class="ri-add-line me-1"></i> Add Milestone
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Create Template</button>
                                <a href="{{ route('projects.templates.index') }}"
                                    class="btn btn-label-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2').select2();

            // Restore old input for tasks and milestones if validation failed
            const oldTasks = {!! json_encode(old('default_tasks', [])) !!};
            const oldMilestones = {!! json_encode(old('default_milestones', [])) !!};

            if (oldTasks.length > 1) {
                for (let i = 1; i < oldTasks.length; i++) {
                    addTask(oldTasks[i]);
                }
            }

            if (oldMilestones.length > 1) {
                for (let i = 1; i < oldMilestones.length; i++) {
                    addMilestone(oldMilestones[i]);
                }
            }
        });

        function addTask(oldData = null) {
            const tasksContainer = document.getElementById('defaultTasks');
            const taskCount = tasksContainer.children.length;

            const taskHtml = `
        <div class="task-item mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Task Name</label>
                            <input type="text" class="form-control"
                                name="default_tasks[${taskCount}][name]"
                                value="${oldData?.name || ''}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority</label>
                            <select class="form-select" name="default_tasks[${taskCount}][priority]" required>
                                <option value="low" ${oldData?.priority === 'low' ? 'selected' : ''}>Low</option>
                                <option value="medium" ${oldData?.priority === 'medium' ? 'selected' : ''}>Medium</option>
                                <option value="high" ${oldData?.priority === 'high' ? 'selected' : ''}>High</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control"
                                name="default_tasks[${taskCount}][description]"
                                rows="2" required>${oldData?.description || ''}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duration</label>
                            <input type="number" class="form-control"
                                name="default_tasks[${taskCount}][duration]"
                                value="${oldData?.duration || ''}" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duration Unit</label>
                            <select class="form-select" name="default_tasks[${taskCount}][duration_unit]" required>
                                <option value="hours" ${oldData?.duration_unit === 'hours' ? 'selected' : ''}>Hours</option>
                                <option value="days" ${oldData?.duration_unit === 'days' ? 'selected' : ''}>Days</option>
                                <option value="weeks" ${oldData?.duration_unit === 'weeks' ? 'selected' : ''}>Weeks</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.task-item').remove()">
                        <i class="ri-delete-bin-line me-1"></i> Remove Task
                    </button>
                </div>
            </div>
        </div>
    `;

            tasksContainer.insertAdjacentHTML('beforeend', taskHtml);
        }

        function addMilestone(oldData = null) {
            const milestonesContainer = document.getElementById('defaultMilestones');
            const milestoneCount = milestonesContainer.children.length;

            const milestoneHtml = `
        <div class="milestone-item mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Milestone Name</label>
                            <input type="text" class="form-control"
                                name="default_milestones[${milestoneCount}][name]"
                                value="${oldData?.name || ''}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Due Day</label>
                            <input type="number" class="form-control"
                                name="default_milestones[${milestoneCount}][due_day]"
                                value="${oldData?.due_day || ''}" min="1" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control"
                                name="default_milestones[${milestoneCount}][description]"
                                rows="2" required>${oldData?.description || ''}</textarea>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.milestone-item').remove()">
                        <i class="ri-delete-bin-line me-1"></i> Remove Milestone
                    </button>
                </div>
            </div>
        </div>
    `;

            milestonesContainer.insertAdjacentHTML('beforeend', milestoneHtml);
        }
    </script>
@endsection
