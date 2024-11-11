<!-- View Evaluation Modal -->
<div class="modal fade" id="viewEvaluationModal{{ $evaluation->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Beoordeling Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="mb-2">Algemene Informatie</h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Type</dt>
                            <dd class="col-sm-8">{{ ucfirst($evaluation->type) }}</dd>

                            <dt class="col-sm-4">Datum</dt>
                            <dd class="col-sm-8">{{ $evaluation->evaluation_date->format('d-m-Y') }}</dd>

                            <dt class="col-sm-4">Beoordelaar</dt>
                            <dd class="col-sm-8">{{ $evaluation->evaluator->name }}</dd>

                            <dt class="col-sm-4">Referentie</dt>
                            <dd class="col-sm-8">{{ $evaluation->reference ?? 'N/A' }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-2">Totaalscore</h6>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $evaluation->overall_rating * 20 }}%"></div>
                            </div>
                            <span class="fw-semibold">{{ number_format($evaluation->overall_rating, 1) }}/5</span>
                        </div>
                    </div>
                </div>

                <!-- Ratings -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="mb-3">Beoordelingen</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kwaliteit</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $evaluation->quality_rating * 20 }}%"></div>
                                    </div>
                                    <span>{{ $evaluation->quality_rating }}/5</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Levertijd</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: {{ $evaluation->delivery_time_rating * 20 }}%"></div>
                                    </div>
                                    <span>{{ $evaluation->delivery_time_rating }}/5</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Communicatie</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                            style="width: {{ $evaluation->communication_rating * 20 }}%"></div>
                                    </div>
                                    <span>{{ $evaluation->communication_rating }}/5</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prijs/Kwaliteit</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-danger" role="progressbar"
                                            style="width: {{ $evaluation->price_rating * 20 }}%"></div>
                                    </div>
                                    <span>{{ $evaluation->price_rating }}/5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Criteria & Issues -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="mb-2">Criteria</h6>
                        <ul class="list-unstyled mb-0">
                            @foreach ($evaluation->criteria ?? [] as $criterion)
                                <li>
                                    <i class="ri-checkbox-circle-line text-success me-2"></i>
                                    {{ ucfirst(str_replace('_', ' ', $criterion)) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-2">Geconstateerde Problemen</h6>
                        <ul class="list-unstyled mb-0">
                            @foreach ($evaluation->issues ?? [] as $issue)
                                <li>
                                    <i class="ri-error-warning-line text-warning me-2"></i>
                                    {{ ucfirst(str_replace('_', ' ', $issue)) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Comments & Actions -->
                <div class="row">
                    <div class="col-12 mb-3">
                        <h6 class="mb-2">Opmerkingen</h6>
                        <p class="mb-0">{{ $evaluation->comments ?? 'Geen opmerkingen' }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <h6 class="mb-2">Verbeterpunten</h6>
                        <p class="mb-0">{{ $evaluation->improvement_actions ?? 'Geen verbeterpunten' }}</p>
                    </div>
                    @if ($evaluation->requires_followup)
                        <div class="col-12">
                            <div class="alert alert-warning mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="ri-alert-line me-2"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Follow-up Vereist</h6>
                                        <p class="mb-0">Gepland op: {{ $evaluation->followup_date->format('d-m-Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Sluiten</button>
                @if ($evaluation->status !== 'completed')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#editEvaluationModal{{ $evaluation->id }}"
                        onclick="$('#viewEvaluationModal{{ $evaluation->id }}').modal('hide')">
                        Bewerken
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
