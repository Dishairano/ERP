@extends('layouts/contentNavbarLayout')

@section('title', 'Create Task')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Create New Task</h5>
                        <a href="{{ route('projects.tasks.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to Tasks
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('projects.tasks.store') }}" method="POST" class="row g-3">
                            @csrf

                            <!-- Project Selection -->
                            <div class="col-md-6">
                                <label class="form-label" for="project_id">Project <span
                                        class="text-danger">*</span></label>
                                <select class="form-select select2" id="project_id" name="project_id" required>
                                    <option value="">Select Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            @if (old('project_id') == $project->id) selected @endif>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Parent Task Selection -->
                            <div class="col-md-6">
                                <label class="form-label" for="parent_task_id">Parent Task</label>
                                <select class="form-select select2" id="parent_task_id" name="parent_task_id">
                                    <option value="">No Parent Task</option>
                                    @if ($parentTask)
                                        <option value="{{ $parentTask->id }}" selected>{{ $parentTask->name }}</option>
                                    @endif
                                </select>
                                @error('parent_task_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Task Name -->
                            <div class="col-md-12">
                                <label class="form-label" for="name">Task Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Task Description -->
                            <div class="col-md-12">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Assigned To -->
                            <div class="col-md-6">
                                <label class="form-label" for="assigned_to">Assign To</label>
                                <select class="form-select select2" id="assigned_to" name="assigned_to">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            @if (old('assigned_to') == $user->id) selected @endif>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div class="col-md-6">
                                <label class="form-label" for="priority">Priority <span class="text-danger">*</span></label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low" @if (old('priority') == 'low') selected @endif>Low</option>
                                    <option value="medium" @if (old('priority', 'medium') == 'medium') selected @endif>Medium</option>
                                    <option value="high" @if (old('priority') == 'high') selected @endif>High</option>
                                    <option value="critical" @if (old('priority') == 'critical') selected @endif>Critical
                                    </option>
                                </select>
                                @error('priority')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Start Date -->
                            <div class="col-md-6">
                                <label class="form-label" for="start_date">Start Date</label>
                                <input type="text" class="form-control flatpickr" id="start_date" name="start_date"
                                    value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-6">
                                <label class="form-label" for="due_date">Due Date</label>
                                <input type="text" class="form-control flatpickr" id="due_date" name="due_date"
                                    value="{{ old('due_date') }}">
                                @error('due_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Estimated Hours -->
                            <div class="col-md-6">
                                <label class="form-label" for="estimated_hours">Estimated Hours</label>
                                <input type="number" class="form-control" id="estimated_hours" name="estimated_hours"
                                    value="{{ old('estimated_hours') }}" step="0.5" min="0">
                                @error('estimated_hours')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div class="col-md-6">
                                <label class="form-label" for="tags">Tags</label>
                                <select class="select2 form-select" id="tags" name="tags[]" multiple>
                                    <option value="bug" @if (in_array('bug', old('tags', []))) selected @endif>Bug</option>
                                    <option value="feature" @if (in_array('feature', old('tags', []))) selected @endif>Feature
                                    </option>
                                    <option value="enhancement" @if (in_array('enhancement', old('tags', []))) selected @endif>
                                        Enhancement</option>
                                    <option value="documentation" @if (in_array('documentation', old('tags', []))) selected @endif>
                                        Documentation</option>
                                    <option value="design" @if (in_array('design', old('tags', []))) selected @endif>Design
                                    </option>
                                </select>
                                @error('tags')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dependencies -->
                            <div class="col-md-12">
                                <label class="form-label" for="dependencies">Dependencies</label>
                                <select class="select2 form-select" id="dependencies" name="dependencies[]" multiple>
                                    <!-- Will be populated via JavaScript when project is selected -->
                                </select>
                                @error('dependencies')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Create Task</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2').select2();

            // Initialize Flatpickr
            $('.flatpickr').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true
            });

            // Handle project selection change
            $('#project_id').on('change', function() {
                const projectId = $(this).val();
                if (projectId) {
                    // Fetch tasks for the selected project
                    fetch(`/api/projects/${projectId}/tasks`)
                        .then(response => response.json())
                        .then(tasks => {
                            const dependenciesSelect = $('#dependencies');
                            dependenciesSelect.empty();

                            tasks.forEach(task => {
                                dependenciesSelect.append(new Option(task.name, task.id));
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            // Validate dates
            $('#due_date').on('change', function() {
                const startDate = $('#start_date').val();
                const dueDate = $(this).val();

                if (startDate && dueDate && new Date(dueDate) < new Date(startDate)) {
                    alert('Due date cannot be earlier than start date');
                    $(this).val('');
                }
            });
        });
    </script>
@endsection
