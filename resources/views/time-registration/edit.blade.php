@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Time Registration')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Time Registration</h5>
                        <a href="{{ route('time-registration.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('time-registration.update', $registration) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Project Selection -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="project_id">Project</label>
                                    <select class="select2 form-select @error('project_id') is-invalid @enderror"
                                        id="project_id" name="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach ($projects ?? [] as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('project_id', $registration->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Task Selection -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="task_id">Task</label>
                                    <select class="select2 form-select @error('task_id') is-invalid @enderror"
                                        id="task_id" name="task_id" required>
                                        <option value="">Select Task</option>
                                        @foreach ($tasks ?? [] as $task)
                                            <option value="{{ $task->id }}"
                                                {{ old('task_id', $registration->task_id) == $task->id ? 'selected' : '' }}>
                                                {{ $task->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('task_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="date">Date</label>
                                    <input type="text" class="form-control flatpickr @error('date') is-invalid @enderror"
                                        id="date" name="date"
                                        value="{{ old('date', $registration->date->format('Y-m-d')) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Hours -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="hours">Hours</label>
                                    <input type="number" class="form-control @error('hours') is-invalid @enderror"
                                        id="hours" name="hours" value="{{ old('hours', $registration->hours) }}"
                                        step="0.25" min="0.25" max="24" required>
                                    @error('hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Enter time in hours (minimum 0.25)</small>
                                </div>

                                <!-- Description -->
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" required>{{ old('description', $registration->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Billable & Overtime -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="billable" name="billable"
                                            value="1" {{ old('billable', $registration->billable) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="billable">Billable Hours</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="overtime" name="overtime"
                                            value="1" {{ old('overtime', $registration->overtime) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="overtime">Overtime</label>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="draft"
                                            {{ old('status', $registration->status) == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="submitted"
                                            {{ old('status', $registration->status) == 'submitted' ? 'selected' : '' }}>
                                            Submit for Approval</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Update Time Registration</button>
                                <a href="{{ route('time-registration.index') }}" class="btn btn-label-secondary">Cancel</a>
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
            // Initialize date picker
            flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d',
                maxDate: 'today'
            });

            // Initialize select2
            $('.select2').select2();

            // Update tasks when project changes
            $('#project_id').on('change', function() {
                const projectId = $(this).val();
                if (projectId) {
                    // Make AJAX call to get project tasks
                    fetch(`/api/projects/${projectId}/tasks`)
                        .then(response => response.json())
                        .then(tasks => {
                            const taskSelect = $('#task_id');
                            taskSelect.empty().append('<option value="">Select Task</option>');
                            tasks.forEach(task => {
                                taskSelect.append(new Option(task.name, task.id));
                            });
                        });
                }
            });
        });
    </script>
@endsection
