<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Contracten</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContractModal">
            <i class="ri-add-line me-1"></i> Nieuw Contract
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Contract Nr.</th>
                    <th>Type</th>
                    <th>Start Datum</th>
                    <th>Eind Datum</th>
                    <th>Status</th>
                    <th>Waarde</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                @forelse($supplier->contracts as $contract)
                    <tr>
                        <td>{{ $contract->contract_number }}</td>
                        <td>{{ $contract->type }}</td>
                        <td>{{ $contract->start_date->format('d-m-Y') }}</td>
                        <td>
                            {{ $contract->end_date->format('d-m-Y') }}
                            @if ($contract->end_date->isPast())
                                <span class="badge bg-label-danger">Verlopen</span>
                            @elseif($contract->end_date->diffInDays(now()) <= 30)
                                <span class="badge bg-label-warning">Verloopt Binnenkort</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-label-{{ $contract->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($contract->status) }}
                            </span>
                        </td>
                        <td>€ {{ number_format($contract->value, 2, ',', '.') }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="ri-more-fill"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#viewContractModal{{ $contract->id }}">
                                        <i class="ri-eye-line me-2"></i>Bekijken
                                    </a>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#editContractModal{{ $contract->id }}">
                                        <i class="ri-pencil-line me-2"></i>Bewerken
                                    </a>
                                    <a class="dropdown-item"
                                        href="{{ route('suppliers.contracts.download', $contract->id) }}">
                                        <i class="ri-download-line me-2"></i>Downloaden
                                    </a>
                                    @if ($contract->status === 'active')
                                        <a class="dropdown-item text-warning" href="#" data-bs-toggle="modal"
                                            data-bs-target="#terminateContractModal{{ $contract->id }}">
                                            <i class="ri-stop-circle-line me-2"></i>Beëindigen
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Geen contracten gevonden</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Contract Details Modal -->
@foreach ($supplier->contracts as $contract)
    @include('suppliers.modals.view-contract', ['contract' => $contract])
    @include('suppliers.modals.edit-contract', ['contract' => $contract])
    @include('suppliers.modals.terminate-contract', ['contract' => $contract])
@endforeach
