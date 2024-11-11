@extends('layouts/contentNavbarLayout')

@section('title', 'Create Project Template')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Create Project Template</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('projects.templates.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Template Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                        required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Default Phases</label>
                                    <div id="phases-container">
                                        <div class="phase-item mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="phases[]"
                                                    placeholder="Phase name">
                                                <button type="button" class="btn btn-outline-danger remove-phase">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-phase">
                                        <i class="ri-add-line"></i> Add Phase
                                    </button>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Default Tasks</label>
                                    <div id="tasks-container">
                                        <div class="task-item mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="tasks[]"
                                                    placeholder="Task name">
                                                <button type="button" class="btn btn-outline-danger remove-task">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-task">
                                        <i class="ri-add-line"></i> Add Task
                                    </button>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Default Milestones</label>
                                    <div id="milestones-container">
                                        <div class="milestone-item mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="milestones[]"
                                                    placeholder="Milestone name">
                                                <button type="button" class="btn btn-outline-danger remove-milestone">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-milestone">
                                        <i class="ri-add-line"></i> Add Milestone
                                    </button>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Default Team Structure</label>
                                    <div id="roles-container">
                                        <div class="role-item mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="roles[]"
                                                    placeholder="Role name">
                                                <button type="button" class="btn btn-outline-danger remove-role">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-role">
                                        <i class="ri-add-line"></i> Add Role
                                    </button>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            checked>
                                        <label class="form-check-label" for="is_active">
                                            Active Template
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Create Template</button>
                                    <a href="{{ route('projects.templates.index') }}"
                                        class="btn btn-label-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add Phase
            document.getElementById('add-phase').addEventListener('click', function() {
                const container = document.getElementById('phases-container');
                const template = container.querySelector('.phase-item').cloneNode(true);
                template.querySelector('input').value = '';
                container.appendChild(template);
            });

            // Add Task
            document.getElementById('add-task').addEventListener('click', function() {
                const container = document.getElementById('tasks-container');
                const template = container.querySelector('.task-item').cloneNode(true);
                template.querySelector('input').value = '';
                container.appendChild(template);
            });

            // Add Milestone
            document.getElementById('add-milestone').addEventListener('click', function() {
                const container = document.getElementById('milestones-container');
                const template = container.querySelector('.milestone-item').cloneNode(true);
                template.querySelector('input').value = '';
                container.appendChild(template);
            });

            // Add Role
            document.getElementById('add-role').addEventListener('click', function() {
                const container = document.getElementById('roles-container');
                const template = container.querySelector('.role-item').cloneNode(true);
                template.querySelector('input').value = '';
                container.appendChild(template);
            });

            // Remove buttons
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-phase') ||
                    e.target.classList.contains('remove-task') ||
                    e.target.classList.contains('remove-milestone') ||
                    e.target.classList.contains('remove-role')) {
                    const container = e.target.closest(
                        '.phase-item, .task-item, .milestone-item, .role-item');
                    const parent = container.parentElement;
                    if (parent.children.length > 1) {
                        container.remove();
                    }
                }
            });
        });
    </script>
@endpush
