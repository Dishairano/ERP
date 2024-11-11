<!-- Import Suppliers Modal -->
<div class="modal fade" id="importSuppliersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leveranciers Importeren</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('suppliers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <p>Upload een Excel of CSV bestand met leveranciers data. Het bestand moet de volgende kolommen
                            bevatten:</p>
                        <ul class="mb-3">
                            <li>name (Verplicht)</li>
                            <li>email (Verplicht)</li>
                            <li>contact_person</li>
                            <li>phone</li>
                            <li>address</li>
                            <li>postal_code</li>
                            <li>city</li>
                            <li>country</li>
                            <li>tax_number</li>
                            <li>registration_number</li>
                            <li>status (active/inactive/blacklisted)</li>
                            <li>classification (strategic/tactical/operational)</li>
                        </ul>
                        <div class="mb-3">
                            <a href="{{ route('suppliers.template') }}" class="btn btn-outline-primary btn-sm">
                                <i class="ri-download-2-line me-1"></i> Download Template
                            </a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="importFile" class="form-label">Bestand Selecteren</label>
                        <input type="file" class="form-control" id="importFile" name="file"
                            accept=".csv,.xlsx,.xls" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="headerRow" name="header_row" value="1"
                            checked>
                        <label class="form-check-label" for="headerRow">
                            Bestand bevat een kopregel
                        </label>
                    </div>
                    <div class="alert alert-info">
                        <i class="ri-information-line me-1"></i>
                        Bestaande leveranciers worden bijgewerkt op basis van het email adres.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">Importeren</button>
                </div>
            </form>
        </div>
    </div>
</div>
