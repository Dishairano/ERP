<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Documenten</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
            <i class="ri-upload-2-line me-1"></i> Document Uploaden
        </button>
    </div>
    <div class="card-body">
        <!-- Document Categories -->
        <ul class="nav nav-pills mb-4" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all-documents" role="tab">
                    Alle Documenten
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contracts" role="tab">
                    Contracten
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#certificates" role="tab">
                    Certificaten
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#invoices" role="tab">
                    Facturen
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#other" role="tab">
                    Overig
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-documents" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Document</th>
                                <th>Type</th>
                                <th>Toegevoegd op</th>
                                <th>Toegevoegd door</th>
                                <th>Geldig tot</th>
                                <th>Status</th>
                                <th>Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supplier->documents as $document)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i
                                                class="ri-file-{{ $document->type === 'pdf'
                                                    ? 'pdf'
                                                    : ($document->type === 'doc'
                                                        ? 'word'
                                                        : ($document->type === 'xls'
                                                            ? 'excel'
                                                            : 'text')) }}-2-line fs-3 me-2"></i>
                                            <div>
                                                <h6 class="mb-0">{{ $document->name }}</h6>
                                                <small class="text-muted">{{ $document->size }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ ucfirst($document->category) }}</td>
                                    <td>{{ $document->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $document->uploader->name }}</td>
                                    <td>
                                        @if ($document->expiry_date)
                                            {{ $document->expiry_date->format('d-m-Y') }}
                                            @if ($document->is_expired)
                                                <span class="badge bg-label-danger ms-1">Verlopen</span>
                                            @elseif($document->expires_soon)
                                                <span class="badge bg-label-warning ms-1">Verloopt binnenkort</span>
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-label-{{ $document->is_active ? 'success' : 'secondary' }}">
                                            {{ $document->is_active ? 'Actief' : 'Inactief' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="ri-more-fill"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('suppliers.documents.preview', $document->id) }}"
                                                    target="_blank">
                                                    <i class="ri-eye-line me-2"></i>Bekijken
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('suppliers.documents.download', $document->id) }}">
                                                    <i class="ri-download-line me-2"></i>Downloaden
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#editDocumentModal{{ $document->id }}">
                                                    <i class="ri-pencil-line me-2"></i>Bewerken
                                                </a>
                                                @if ($document->is_active)
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deactivateDocumentModal{{ $document->id }}">
                                                        <i class="ri-close-circle-line me-2"></i>Deactiveren
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Geen documenten gevonden</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Other tabs will have similar structure but filtered by category -->
            <div class="tab-pane fade" id="contracts" role="tabpanel">
                <!-- Similar table structure but only showing contract documents -->
            </div>
            <div class="tab-pane fade" id="certificates" role="tabpanel">
                <!-- Similar table structure but only showing certificate documents -->
            </div>
            <div class="tab-pane fade" id="invoices" role="tabpanel">
                <!-- Similar table structure but only showing invoice documents -->
            </div>
            <div class="tab-pane fade" id="other" role="tabpanel">
                <!-- Similar table structure but only showing other documents -->
            </div>
        </div>
    </div>
</div>

<!-- Document Modals -->
@foreach ($supplier->documents as $document)
    @include('suppliers.modals.edit-document', ['document' => $document])
    @if ($document->is_active)
        @include('suppliers.modals.deactivate-document', ['document' => $document])
    @endif
@endforeach
