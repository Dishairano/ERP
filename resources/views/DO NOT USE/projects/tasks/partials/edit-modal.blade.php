<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTaskForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Task Title</label>
                            <input type="text" class="form-control" name="title" id="edit_title" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Project</label>
                            <select class="form-select" name="project_id" id="edit_project_id" required>
                                <option value="">Select Project</option>
                                <!-- Projects will be populated here -->
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Assignee</label>
                            <select class="form-select" name="assignee_id" id="edit_assignee_id">
                                <option value="">Select Assignee</option>
                                <!-- Users will be populated here -->
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Due Date</label>
                            <input type="date" class="form-control" name="due_date" id="edit_due_date">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Priority</label>
                            <select class="form-select" name="priority" id="edit_priority">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status">
                                <option value="todo">To Do</option>
                                <option value="in-progress">In Progress</option>
                                <option value="review">Review</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Progress (%)</label>
                            <input type="number" class="form-control" name="progress" id="edit_progress" min="0"
                                max="100">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Dependencies</label>
                            <select class="form-select" name="dependencies[]" id="edit_dependencies" multiple>
                                <!-- Tasks will be populated here -->
                            </select>
                            <small class="text-muted">Select tasks that need to be completed before this task can
                                start</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Estimated Hours</label>
                            <input type="number" class="form-control" name="estimated_hours" id="edit_estimated_hours"
                                min="0" step="0.5">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tags</label>
                            <input type="text" class="form-control" name="tags" id="edit_tags"
                                placeholder="Enter tags separated by commas">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
