function addTask(oldData = null) {
const tasksContainer = document.getElementById('defaultTasks');
const taskCount = tasksContainer.children.length;

const taskHtml = `
<div class="task-item mb-3">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Task Name</label>
                    <input type="text" class="form-control" name="default_tasks[${taskCount}][name]"
                        value="${oldData?.name || ''}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Priority</label>
                    <select class="form-select" name="default_tasks[${taskCount}][priority]" required>
                        <option value="low" ${oldData?.priority === 'low' ? 'selected' : '' }>Low</option>
                        <option value="medium" ${oldData?.priority === 'medium' ? 'selected' : '' }>Medium</option>
                        <option value="high" ${oldData?.priority === 'high' ? 'selected' : '' }>High</option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="default_tasks[${taskCount}][description]" rows="2" required>${oldData?.description || ''}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Duration</label>
                    <input type="number" class="form-control" name="default_tasks[${taskCount}][duration]"
                        value="${oldData?.duration || ''}" min="1" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Duration Unit</label>
                    <select class="form-select" name="default_tasks[${taskCount}][duration_unit]" required>
                        <option value="hours" ${oldData?.duration_unit === 'hours' ? 'selected' : '' }>Hours
                        </option>
                        <option value="days" ${oldData?.duration_unit === 'days' ? 'selected' : '' }>Days</option>
                        <option value="weeks" ${oldData?.duration_unit === 'weeks' ? 'selected' : '' }>Weeks
                        </option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.task-item').remove()">
                <i class="ri-delete-bin-line me-1"></i> Remove Task
            </button>
        </div>
    </div>
</div>
`;

tasksContainer.insertAdjacentHTML('beforeend', taskHtml);
}

function addMilestone(oldData = null) {
const milestonesContainer = document.getElementById('defaultMilestones');
const milestoneCount = milestonesContainer.children.length;

const milestoneHtml = `
<div class="milestone-item mb-3">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Milestone Name</label>
                    <input type="text" class="form-control" name="default_milestones[${milestoneCount}][name]"
                        value="${oldData?.name || ''}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Due Day</label>
                    <input type="number" class="form-control" name="default_milestones[${milestoneCount}][due_day]"
                        value="${oldData?.due_day || ''}" min="1" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="default_milestones[${milestoneCount}][description]" rows="2" required>${oldData?.description || ''}</textarea>
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm"
                onclick="this.closest('.milestone-item').remove()">
                <i class="ri-delete-bin-line me-1"></i> Remove Milestone
            </button>
        </div>
    </div>
</div>
`;

milestonesContainer.insertAdjacentHTML('beforeend', milestoneHtml);
}
