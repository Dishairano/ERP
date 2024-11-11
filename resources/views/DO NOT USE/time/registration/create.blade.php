@extends('layouts/contentNavbarLayout')

@section('title', 'Register Time')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Register Time</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('time-registration.store') }}" method="POST" id="timeRegistrationForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Project</label>
                                    <select class="form-select" name="project_id" id="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Task</label>
                                    <select class="form-select" name="task_id" id="task_id" required disabled>
                                        <option value="">Select Task</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date" id="date"
                                        max="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control" name="start_time" id="start_time" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control" name="end_time" id="end_time" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3" required></textarea>
                                <div class="form-text">Describe the work performed during this time period.</div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="billable" id="billable"
                                        value="1">
                                    <label class="form-check-label" for="billable">
                                        Billable Time
                                    </label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Register Time</button>
                                <a href="{{ route('time-registration.overview') }}"
                                    class="btn btn-label-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Quick Fill Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Fill</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Recent time registrations:</p>
                        <div class="list-group">
                            @foreach ($recentRegistrations as $registration)
                                <button type="button" class="list-group-item list-group-item-action quick-fill"
                                    data-id="{{ $registration->id }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $registration->project->name }}</h6>
                                            <small>{{ Str::limit($registration->description, 50) }}</small>
                                        </div>
                                        <small class="text-muted">{{ $registration->date->format('M d') }}</small>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Help</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <h6 class="alert-heading mb-1">Tips:</h6>
                            <ul class="mb-0">
                                <li>Select a project and task</li>
                                <li>Enter the date and time period</li>
                                <li>Provide a clear description</li>
                                <li>Mark as billable if applicable</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projectSelect = document.getElementById('project_id');
            const taskSelect = document.getElementById('task_id');
            const dateInput = document.getElementById('date');
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            const form = document.getElementById('timeRegistrationForm');

            // Set max date to today
            dateInput.max = new Date().toISOString().split('T')[0];

            // Load tasks when project is selected
            projectSelect.addEventListener('change', function() {
                const projectId = this.value;
                taskSelect.disabled = !projectId;

                if (projectId) {
                    fetch(`/time-registration/tasks?project_id=${projectId}`)
                        .then(response => response.json())
                        .then(tasks => {
                            taskSelect.innerHTML = '<option value="">Select Task</option>';
                            tasks.forEach(task => {
                                taskSelect.innerHTML +=
                                    `<option value="${task.id}">${task.name}</option>`;
                            });
                        });
                } else {
                    taskSelect.innerHTML = '<option value="">Select Task</option>';
                }
            });

            // Validate time period
            function validateTimePeriod() {
                const date = dateInput.value;
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;

                if (date && startTime && endTime) {
                    fetch('/time-registration/validate-time', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                date,
                                start_time: startTime,
                                end_time: endTime
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.has_overlap) {
                                alert('This time period overlaps with an existing registration');
                            }
                        });
                }
            }

            startTimeInput.addEventListener('change', validateTimePeriod);
            endTimeInput.addEventListener('change', validateTimePeriod);

            // Quick fill functionality
            document.querySelectorAll('.quick-fill').forEach(button => {
                button.addEventListener('click', function() {
                    const registrationId = this.dataset.id;

                    fetch(`/time-registration/quick-fill?registration_id=${registrationId}`)
                        .then(response => response.json())
                        .then(data => {
                            projectSelect.value = data.project_id;
                            projectSelect.dispatchEvent(new Event('change'));

                            // Wait for tasks to load
                            setTimeout(() => {
                                taskSelect.value = data.task_id;
                            }, 500);

                            document.querySelector('textarea[name="description"]').value = data
                                .description;
                            document.getElementById('billable').checked = data.billable;
                        });
                });
            });

            // Form validation
            form.addEventListener('submit', function(e) {
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;

                if (startTime >= endTime) {
                    e.preventDefault();
                    alert('End time must be after start time');
                }
            });
        });
    </script>
@endsection
