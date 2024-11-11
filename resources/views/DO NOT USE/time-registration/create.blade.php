@extends('layouts/contentNavbarLayout')

@section('title', 'Register Time')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Time Registration /</span> Register Time
        </h4>

        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Time Registration Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('time-registration.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="date">Date</label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        value="{{ old('date', now()->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="type">Type</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="regular">Regular Time</option>
                                        <option value="overtime">Overtime</option>
                                        <option value="leave">Leave</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="project_id">Project</label>
                                    <select class="form-select" id="project_id" name="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="task_id">Task</label>
                                    <select class="form-select" id="task_id" name="task_id" required>
                                        <option value="">Select Task</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="hours">Hours</label>
                                    <input type="number" class="form-control" id="hours" name="hours" step="0.5"
                                        min="0" max="24" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Describe your work"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('time-registration.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projectSelect = document.getElementById('project_id');
            const taskSelect = document.getElementById('task_id');

            projectSelect.addEventListener('change', function() {
                const projectId = this.value;

                // Clear current tasks
                taskSelect.innerHTML = '<option value="">Select Task</option>';

                if (projectId) {
                    // Fetch tasks for selected project
                    fetch(`/api/projects/${projectId}/tasks`)
                        .then(response => response.json())
                        .then(tasks => {
                            tasks.forEach(task => {
                                const option = document.createElement('option');
                                option.value = task.id;
                                option.textContent = task.name;
                                taskSelect.appendChild(option);
                            });
                        });
                }
            });
        });
    </script>
@endsection
