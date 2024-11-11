<div class="modal fade" id="createRiskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Risk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('projects.risks.store', ['project' => ':project_id']) }}" method="POST"
                id="createRiskForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Risk Title</label>
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
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" required>
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
                            <input type="number" class="form-control" name="probability" min="0" max="100"
                                required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Impact</label>
                            <select class="form-select" name="impact" required>
                                <option value="">Select Impact</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Risk Owner</label>
                            <select class="form-select" name="owner_id">
                                <option value="">Select Owner</option>
                                @foreach ($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="identified">Identified</option>
                                <option value="assessed">Assessed</option>
                                <option value="mitigated">Mitigated</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mitigation Strategy</label>
                            <textarea class="form-control" name="mitigation_strategy" rows="3"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Contingency Plan</label>
                            <textarea class="form-control" name="contingency_plan" rows="3"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Trigger Events</label>
                            <textarea class="form-control" name="trigger_events" rows="2"></textarea>
                            <small class="text-muted">Events that may trigger this risk</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Risk</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createRiskForm = document.getElementById('createRiskForm');
            const projectSelect = document.getElementById('projectSelect');

            projectSelect.addEventListener('change', function() {
                const projectId = this.value;
                const action = createRiskForm.getAttribute('action').replace(':project_id', projectId);
                createRiskForm.setAttribute('action', action);
            });

            // If project_id is provided in the URL, pre-select it
            const urlParams = new URLSearchParams(window.location.search);
            const projectId = urlParams.get('project_id');
            if (projectId) {
                projectSelect.value = projectId;
                projectSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endpush
