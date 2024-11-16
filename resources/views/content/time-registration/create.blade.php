@extends('layouts/contentNavbarLayout')

@section('title', 'Register Time')

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
                        <h5 class="mb-0">Register Time</h5>
                        <a href="{{ route('time-registration.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('time-registration.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Project Selection -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="project_id">Project</label>
                                    <select class="select2 form-select @error('project_id') is-invalid @enderror"
                                        id="project_id" name="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach ($projects ?? [] as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                                            <option value="{{ $task['id'] }}"
                                                {{ old('task_id') == $task['id'] ? 'selected' : '' }}>
                                                {{ $task['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('task_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="date">Date</label>
                                    <input type="text" class="form-control flatpickr-date @error('date') is-invalid @enderror"
                                        id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}"
                                        required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Start Time -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="start_time">Start Time</label>
                                    <input type="text" class="form-control flatpickr-time @error('start_time') is-invalid @enderror"
                                        id="start_time" name="start_time" value="{{ old('start_time', '09:00') }}"
                                        required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- End Time -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="end_time">End Time</label>
                                    <input type="text" class="form-control flatpickr-time @error('end_time') is-invalid @enderror"
                                        id="end_time" name="end_time" value="{{ old('end_time', '17:00') }}"
                                        required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Billable & Overtime -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="billable" name="billable"
                                            value="1" {{ old('billable', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="billable">Billable Hours</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="overtime" name="overtime"
                                            value="1" {{ old('overtime') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="overtime">Overtime</label>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                                            Draft</option>
                                        <option value="submitted" {{ old('status') == 'submitted' ? 'selected' : '' }}>
                                            Submit for Approval</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Register Time</button>
                                <button type="reset" class="btn btn-label-secondary">Reset</button>
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
            flatpickr('.flatpickr-date', {
                dateFormat: 'Y-m-d',
                maxDate: 'today'
            });

            // Initialize time pickers
            flatpickr('.flatpickr-time', {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                minuteIncrement: 15
            });

            // Initialize select2
            $('.select2').select2();

            // Update tasks when project changes
            $('#project_id').on('change', function() {
                const projectId = $(this).val();
                const taskSelect = $('#task_id');

                // Clear task select if no project selected
                if (!projectId) {
                    taskSelect.empty().append('<option value="">Select Task</option>');
                    return;
                }

                // Show loading state
                taskSelect.empty().append('<option value="">Loading tasks...</option>');

                // Make AJAX call to get project tasks
                fetch(`/api/projects/${projectId}/tasks`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(tasks => {
                        taskSelect.empty().append('<option value="">Select Task</option>');
                        tasks.forEach(task => {
                            taskSelect.append(new Option(task.name, task.id));
                        });

                        // Re-initialize select2 after updating options
                        taskSelect.trigger('change');
                    })
                    .catch(error => {
                        console.error('Error fetching tasks:', error);
                        taskSelect.empty().append('<option value="">Error loading tasks</option>');
                    });
            });

            // Trigger change event if project is pre-selected
            if ($('#project_id').val()) {
                $('#project_id').trigger('change');
            }
        });
    </script>
@endsection
