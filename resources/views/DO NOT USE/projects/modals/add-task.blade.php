<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nieuwe Taak Toevoegen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('projects.tasks.store', $project) }}" method="POST">
                @csrf
                <input type="hidden" name="phase_id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="task_name">Naam</label>
                        <input type="text" class="form-control" id="task_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="task_description">Beschrijving</label>
                        <textarea class="form-control" id="task_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="task_start_date">Start Datum</label>
                                <input type="date" class="form-control" id="task_start_date" name="start_date"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="task_end_date">Eind Datum</label>
                                <input type="date" class="form-control" id="task_end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="estimated_hours">Geschatte Uren</label>
                        <input type="number" class="form-control" id="estimated_hours" name="estimated_hours" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="task_budget">Budget</label>
                        <input type="number" step="0.01" class="form-control" id="task_budget" name="budget">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="priority">Prioriteit</label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="1">Hoog</option>
                            <option value="2">Medium-Hoog</option>
                            <option value="3">Medium</option>
                            <option value="4">Medium-Laag</option>
                            <option value="5">Laag</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Toewijzen aan</label>
                        <select class="form-select" name="assigned_users[]" multiple required>
                            @foreach ($project->manager->department->users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">Taak Toevoegen</button>
                </div>
            </form>
        </div>
    </div>
</div>
