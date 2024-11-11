<div class="modal fade" id="timeRegistrationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tijd Registreren</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="timeRegistrationForm" method="POST">
                @csrf
                <input type="hidden" name="task_id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="date">Datum</label>
                        <input type="date" class="form-control" id="date" name="date" required
                            value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="hours">Aantal Uren</label>
                        <input type="number" class="form-control" id="hours" name="hours" min="0.5"
                            step="0.5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="description">Beschrijving</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">Tijd Registreren</button>
                </div>
            </form>
        </div>
    </div>
</div>
