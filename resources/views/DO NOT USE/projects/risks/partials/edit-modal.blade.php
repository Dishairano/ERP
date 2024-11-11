<div class="modal fade" id="editRiskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Risk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRiskForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Risk Title</label>
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
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" id="edit_category" required>
                                <option value="">Select Category</option>
                                <option value="technical">Technical</option>
                                <option value="schedule">Schedule</option>
                                <option value="cost">Cost</option>
                                <option value="resource">Resource</option>
                                <option value="scope">Scope</option>
                                <option value="quality">Quality</option>
                                <option value="external">External</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Probability (%)</label>
                            <input type="number" class="form-control" name="probability" id="edit_probability"
                                min="0" max="100" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Impact</label>
                            <select class="form-select" name="impact" id="edit_impact" required>
                                <option value="">Select Impact</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Risk Owner</label>
                            <select class="form-select" name="owner_id" id="edit_owner_id">
                                <option value="">Select Owner</option>
                                <!-- Users will be populated here -->
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="identified">Identified</option>
                                <option value="assessed">Assessed</option>
                                <option value="mitigated">Mitigated</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mitigation Strategy</label>
                            <textarea class="form-control" name="mitigation_strategy" id="edit_mitigation_strategy" rows="3"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Contingency Plan</label>
                            <textarea class="form-control" name="contingency_plan" id="edit_contingency_plan" rows="3"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Trigger Events</label>
                            <textarea class="form-control" name="trigger_events" id="edit_trigger_events" rows="2"></textarea>
                            <small class="text-muted">Events that may trigger this risk</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Risk Response History</label>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Action</th>
                                            <th>Status Change</th>
                                            <th>Updated by</th>
                                        </tr>
                                    </thead>
                                    <tbody id="edit_risk_history">
                                        <!-- Risk history will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Risk</button>
                </div>
            </form>
        </div>
    </div>
</div>
