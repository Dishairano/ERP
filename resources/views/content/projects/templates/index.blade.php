@extends('layouts/contentNavbarLayout')

@section('title', 'Project Templates')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Project Templates</h6>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#createTemplateModal">
                            <i class="fas fa-plus"></i> New Template
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Template Name</th>
                                        <th>Description</th>
                                        <th>Tasks</th>
                                        <th>Last Used</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($templates as $template)
                                        <tr>
                                            <td>{{ $template->name }}</td>
                                            <td>{{ Str::limit($template->description, 50) }}</td>
                                            <td>{{ count($template->template_data['tasks'] ?? []) }}</td>
                                            <td>{{ $template->last_used_at ? $template->last_used_at->format('M d, Y') : 'Never' }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('projects.templates.show', $template->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-toggle="modal" data-target="#useTemplateModal"
                                                        data-template-id="{{ $template->id }}"
                                                        data-template-name="{{ $template->name }}">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#deleteTemplateModal"
                                                        data-template-id="{{ $template->id }}"
                                                        data-template-name="{{ $template->name }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $templates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Template Modal -->
    <div class="modal fade" id="createTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('projects.templates.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Template</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Template Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Template Tasks</label>
                            <div id="tasksList">
                                <div class="task-item mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control"
                                                        name="template_data[tasks][0][title]" placeholder="Task Title"
                                                        required>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control" name="template_data[tasks][0][priority]"
                                                        required>
                                                        <option value="low">Low</option>
                                                        <option value="medium">Medium</option>
                                                        <option value="high">High</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" class="form-control"
                                                        name="template_data[tasks][0][estimated_hours]" placeholder="Hours"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <textarea class="form-control" name="template_data[tasks][0][description]" placeholder="Task Description" rows="2"
                                                        required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" id="addTask">
                                <i class="fas fa-plus"></i> Add Task
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Use Template Modal -->
    <div class="modal fade" id="useTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="useTemplateForm" action="" method="POST">
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
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        required>
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

    <!-- Delete Template Modal -->
    <div class="modal fade" id="deleteTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteTemplateForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Template</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the template "<span id="deleteTemplateName"></span>"?</p>
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
            let taskCounter = 1;
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

            // Use Template Modal
            const useTemplateModal = document.getElementById('useTemplateModal');
            useTemplateModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const templateId = button.getAttribute('data-template-id');
                const form = document.getElementById('useTemplateForm');
                form.action = `/projects/templates/${templateId}/create`;
            });

            // Delete Template Modal
            const deleteTemplateModal = document.getElementById('deleteTemplateModal');
            deleteTemplateModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const templateId = button.getAttribute('data-template-id');
                const templateName = button.getAttribute('data-template-name');
                const form = document.getElementById('deleteTemplateForm');
                const nameSpan = document.getElementById('deleteTemplateName');

                form.action = `/projects/templates/${templateId}`;
                nameSpan.textContent = templateName;
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
