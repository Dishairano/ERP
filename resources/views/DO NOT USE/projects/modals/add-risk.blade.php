<div class="modal fade" id="addRiskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nieuw Risico Toevoegen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('projects.risks.store', $project) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="risk_name">Naam</label>
                        <input type="text" class="form-control" id="risk_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="risk_description">Beschrijving</label>
                        <textarea class="form-control" id="risk_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="probability">Kans (1-5)</label>
                                <select class="form-select" id="probability" name="probability" required>
                                    <option value="1">1 - Zeer Laag</option>
                                    <option value="2">2 - Laag</option>
                                    <option value="3">3 - Medium</option>
                                    <option value="4">4 - Hoog</option>
                                    <option value="5">5 - Zeer Hoog</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="impact">Impact (1-5)</label>
                                <select class="form-select" id="impact" name="impact" required>
                                    <option value="1">1 - Minimaal</option>
                                    <option value="2">2 - Klein</option>
                                    <option value="3">3 - Matig</option>
                                    <option value="4">4 - Groot</option>
                                    <option value="5">5 - Kritiek</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="mitigation_strategy">Mitigatie Strategie</label>
                        <textarea class="form-control" id="mitigation_strategy" name="mitigation_strategy" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="owner_id">Eigenaar</label>
                        <select class="form-select" id="owner_id" name="owner_id" required>
                            <option value="">Selecteer Eigenaar</option>
                            @foreach ($project->manager->department->users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">Risico Toevoegen</button>
                </div>
            </form>
        </div>
    </div>
</div>
