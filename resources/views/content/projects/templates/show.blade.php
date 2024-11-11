@extends('layouts/contentNavbarLayout')

@section('title', 'Template Details')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Template Details</h6>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#useTemplateModal">
                                <i class="fas fa-play"></i> Use Template
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editTemplateModal">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteTemplateModal">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>{{ $template->name }}</h5>
                                <p class="text-muted">{{ $template->description }}</p>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="small font-weight-bold">Created</div>
                                        <div>{{ $template->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small font-weight-bold">Last Used</div>
                                        <div>
                                            {{ $template->last_used_at ? $template->last_used_at->format('M d, Y') : 'Never' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="font-weight-bold">Template Tasks</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Task</th>
                                        <th>Priority</th>
                                        <th>Estimated Hours</th>
                                        <th>Dependencies</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($template->template_data['tasks'] as $task)
                                        <tr>
                                            <td>
                                                <div class="font-weight-bold">{{ $task['title'] }}</div>
                                                <div class="small text-muted">{{ $task['description'] }}</div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $task['priority'] === 'high' ? 'danger' : ($task['priority'] === 'medium' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($task['priority']) }}
                                                </span>
                                            </td>
                                            <td>{{ $task['estimated_hours'] }} hours</td>
                                            <td>
                                                @if (isset($task['dependencies']) && count($task['dependencies']) > 0)
                                                    @foreach ($task['dependencies'] as $dependency)
                                                        <span class="badge badge-secondary">{{ $dependency }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">None</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Use Template Modal -->
    <div class="modal fade" id="useTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('projects.templates.create', $template->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Project from Template</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="project_name">Project Name</label>
                            <input type="text" class="form-control" id="project_name" name="name" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Template Modal -->
    <div class="modal fade" id="editTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('projects.templates.update', $template->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Template</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Template Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $template->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required>{{ $template->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Template Tasks</label>
                            <div id="tasksList">
                                @foreach ($template->template_data['tasks'] as $index => $task)
                                    <div class="task-item mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                            name="template_data[tasks][{{ $index }}][title]"
                                                            value="{{ $task['title'] }}" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select class="form-control"
                                                            name="template_data[tasks][{{ $index }}][priority]"
                                                            required>
                                                            <option value="low"
                                                                {{ $task['priority'] === 'low' ? 'selected' : '' }}>Low
                                                            </option>
                                                            <option value="medium"
                                                                {{ $task['priority'] === 'medium' ? 'selected' : '' }}>
                                                                Medium</option>
                                                            <option value="high"
                                                                {{ $task['priority'] === 'high' ? 'selected' : '' }}>High
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control"
                                                            name="template_data[tasks][{{ $index }}][estimated_hours]"
                                                            value="{{ $task['estimated_hours'] }}" required>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <textarea class="form-control" name="template_data[tasks][{{ $index }}][description]" rows="2"
                                                            required>{{ $task['description'] }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" id="addTask">
                                <i class="fas fa-plus"></i> Add Task
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Template Modal -->
    <div class="modal fade" id="deleteTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('projects.templates.delete', $template->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Template</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this template?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add Task Button
            let taskCounter = {{ count($template->template_data['tasks']) }};
            document.getElementById('addTask').addEventListener('click', function() {
                const taskHtml = `
            <div class="task-item mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="template_data[tasks][${taskCounter}][title]" placeholder="Task Title" required>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="template_data[tasks][${taskCounter}][priority]" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" name="template_data[tasks][${taskCounter}][estimated_hours]" placeholder="Hours" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <textarea class="form-control" name="template_data[tasks][${taskCounter}][description]" placeholder="Task Description" rows="2" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
                document.getElementById('tasksList').insertAdjacentHTML('beforeend', taskHtml);
                taskCounter++;
            });

            // Set minimum date for start_date and end_date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').min = today;
            document.getElementById('end_date').min = today;

            // Ensure end_date is after start_date
            document.getElementById('start_date').addEventListener('change', function() {
                document.getElementById('end_date').min = this.value;
            });
        });
    </script>
@endpush
