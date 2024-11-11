<div class="modal fade" id="createTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('projects.tasks.store', ['project' => ':project_id']) }}" method="POST"
                id="createTaskForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Task Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Project</label>
                            <select class="form-select" name="project_id" required id="projectSelect">
                                <option value="">Select Project</option>
                                @foreach ($projects ?? [] as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Assignee</label>
                            <select class="form-select" name="assignee_id">
                                <option value="">Select Assignee</option>
                                @foreach ($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Due Date</label>
                            <input type="date" class="form-control" name="due_date">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Priority</label>
                            <select class="form-select" name="priority">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Dependencies</label>
                            <select class="form-select" name="dependencies[]" multiple>
                                @foreach ($tasks ?? [] as $task)
                                    <option value="{{ $task->id }}">{{ $task->title }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Select tasks that need to be completed before this task can
                                start</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Estimated Hours</label>
                            <input type="number" class="form-control" name="estimated_hours" min="0"
                                step="0.5">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tags</label>
                            <input type="text" class="form-control" name="tags"
                                placeholder="Enter tags separated by commas">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createTaskForm = document.getElementById('createTaskForm');
            const projectSelect = document.getElementById('projectSelect');

            projectSelect.addEventListener('change', function() {
                const projectId = this.value;
                const action = createTaskForm.getAttribute('action').replace(':project_id', projectId);
                createTaskForm.setAttribute('action', action);
            });
        });
    </script>
@endpush
