<!-- Add Evaluation Modal -->
<div class="modal fade" id="addEvaluationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nieuwe Beoordeling</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('suppliers.evaluations.store', $supplier) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Evaluation Type -->
                    <div class="mb-3">
                        <label class="form-label">Type Beoordeling</label>
                        <select class="form-select" name="type" required>
                            <option value="order">Order Evaluatie</option>
                            <option value="periodic">Periodieke Evaluatie</option>
                            <option value="quality">Kwaliteitscontrole</option>
                            <option value="audit">Audit</option>
                        </select>
                    </div>

                    <!-- Reference -->
                    <div class="mb-3">
                        <label class="form-label">Referentie (bijv. ordernummer)</label>
                        <input type="text" class="form-control" name="reference">
                    </div>

                    <!-- Performance Ratings -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kwaliteit</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="range" class="form-range" name="quality_rating" min="1"
                                    max="5" value="3" oninput="this.nextElementSibling.value = this.value">
                                <output>3</output>
                            </div>
                            <small class="text-muted">1 = Zeer slecht, 5 = Uitstekend</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Levertijd</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="range" class="form-range" name="delivery_time_rating" min="1"
                                    max="5" value="3" oninput="this.nextElementSibling.value = this.value">
                                <output>3</output>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Communicatie</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="range" class="form-range" name="communication_rating" min="1"
                                    max="5" value="3" oninput="this.nextElementSibling.value = this.value">
                                <output>3</output>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prijs/Kwaliteit Verhouding</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="range" class="form-range" name="price_rating" min="1"
                                    max="5" value="3" oninput="this.nextElementSibling.value = this.value">
                                <output>3</output>
                            </div>
                        </div>
                    </div>

                    <!-- Specific Criteria -->
                    <div class="mb-3">
                        <label class="form-label">Specifieke Criteria</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="criteria[]"
                                        value="documentation">
                                    <label class="form-check-label">Documentatie volledig</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="criteria[]" value="packaging">
                                    <label class="form-check-label">Verpakking conform eisen</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="criteria[]"
                                        value="specifications">
                                    <label class="form-check-label">Voldoet aan specificaties</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="criteria[]"
                                        value="certification">
                                    <label class="form-check-label">Certificering geldig</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Issues -->
                    <div class="mb-3">
                        <label class="form-label">Geconstateerde Problemen</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="issues[]"
                                        value="quality_issues">
                                    <label class="form-check-label">Kwaliteitsproblemen</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="issues[]"
                                        value="delivery_delay">
                                    <label class="form-check-label">Leveringsvertraging</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="issues[]"
                                        value="communication_issues">
                                    <label class="form-check-label">Communicatieproblemen</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="issues[]"
                                        value="documentation_issues">
                                    <label class="form-check-label">Documentatieproblemen</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="mb-3">
                        <label class="form-label">Opmerkingen</label>
                        <textarea class="form-control" name="comments" rows="3"
                            placeholder="Voeg hier eventuele opmerkingen of toelichting toe..."></textarea>
                    </div>

                    <!-- Improvement Actions -->
                    <div class="mb-3">
                        <label class="form-label">Verbeterpunten</label>
                        <textarea class="form-control" name="improvement_actions" rows="3"
                            placeholder="Beschrijf hier eventuele verbeterpunten..."></textarea>
                    </div>

                    <!-- Follow-up -->
                    <div class="mb-3">
                        <label class="form-label">Follow-up Vereist</label>
                        <select class="form-select" name="requires_followup">
                            <option value="0">Nee</option>
                            <option value="1">Ja</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Follow-up Datum</label>
                        <input type="date" class="form-control" name="followup_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">Beoordeling Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const evaluationForm = document.querySelector('#addEvaluationModal form');

        evaluationForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Calculate overall rating
            const ratings = [
                'quality_rating',
                'delivery_time_rating',
                'communication_rating',
                'price_rating'
            ].map(name => parseInt(this.querySelector(`[name="${name}"]`).value));

            const overallRating = ratings.reduce((a, b) => a + b) / ratings.length;

            // Add to form data
            const overallInput = document.createElement('input');
            overallInput.type = 'hidden';
            overallInput.name = 'overall_rating';
            overallInput.value = overallRating;
            this.appendChild(overallInput);

            // Submit the form
            this.submit();
        });
    });
</script>
