<div class="modal fade" id="add-value-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add KPI Value</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="add-value-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="value">Value</label>
                        <input type="number" class="form-control" id="value" name="value" required
                            step="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="measurement_date">Measurement Date</label>
                        <input type="datetime-local" class="form-control" id="measurement_date" name="measurement_date"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="additional_data">Additional Data</label>
                        <textarea class="form-control" id="additional_data" name="additional_data" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Value</button>
                </div>
            </form>
        </div>
    </div>
</div>
