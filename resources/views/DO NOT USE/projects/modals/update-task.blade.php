<div class="modal fade" id="updateTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Taak Status Bijwerken</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateTaskForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="task_status">Status</label>
                        <select class="form-select" id="task_status" name="status" required>
                            <option value="not_started">Niet Gestart</option>
                            <option value="in_progress">In Uitvoering</option>
                            <option value="completed">Voltooid</option>
                            <option value="blocked">Geblokkeerd</option>
                            <option value="delayed">Vertraagd</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="progress_percentage">Voortgang (%)</label>
                        <input type="number" class="form-control" id="progress_percentage" name="progress_percentage"
                            min="0" max="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">Status Bijwerken</button>
                </div>
            </form>
        </div>
    </div>
</div>
