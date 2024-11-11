<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nieuwe Leverancier Toevoegen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h6>Basis Informatie</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bedrijfsnaam</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contactpersoon</label>
                            <input type="text" class="form-control" name="contact_person">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefoon</label>
                            <input type="text" class="form-control" name="phone">
                        </div>

                        <!-- Address Information -->
                        <div class="col-12">
                            <h6 class="mt-3">Adres Informatie</h6>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adres</label>
                            <input type="text" class="form-control" name="address">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Postcode</label>
                            <input type="text" class="form-control" name="postal_code">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Plaats</label>
                            <input type="text" class="form-control" name="city">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Land</label>
                            <input type="text" class="form-control" name="country">
                        </div>

                        <!-- Company Information -->
                        <div class="col-12">
                            <h6 class="mt-3">Bedrijfs Informatie</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">BTW Nummer</label>
                            <input type="text" class="form-control" name="tax_number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">KvK Nummer</label>
                            <input type="text" class="form-control" name="registration_number">
                        </div>

                        <!-- Classification -->
                        <div class="col-12">
                            <h6 class="mt-3">Classificatie</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="active">Actief</option>
                                <option value="inactive">Inactief</option>
                                <option value="blacklisted">Blacklist</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Classificatie</label>
                            <select class="form-select" name="classification">
                                <option value="">Selecteer classificatie</option>
                                <option value="strategic">Strategisch</option>
                                <option value="tactical">Tactisch</option>
                                <option value="operational">Operationeel</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_critical" value="1"
                                    id="criticalSupplier">
                                <label class="form-check-label" for="criticalSupplier">
                                    Dit is een kritieke leverancier
                                </label>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label class="form-label">Opmerkingen</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">Leverancier Toevoegen</button>
                </div>
            </form>
        </div>
    </div>
</div>
