<div class="row">
    <!-- Performance Overview Card -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Prestatie Overzicht</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="mb-4">
                        <h6 class="mb-2">Algemene Score</h6>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $supplier->average_score }}%"
                                    aria-valuenow="{{ $supplier->average_score }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="fw-semibold">{{ $supplier->average_score }}%</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="mb-2">Kwaliteit</h6>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $supplier->quality_score }}%"
                                    aria-valuenow="{{ $supplier->quality_score }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="fw-semibold">{{ $supplier->quality_score }}%</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="mb-2">Levering</h6>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar bg-info" role="progressbar"
                                    style="width: {{ $supplier->delivery_score }}%"
                                    aria-valuenow="{{ $supplier->delivery_score }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="fw-semibold">{{ $supplier->delivery_score }}%</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="mb-2">Communicatie</h6>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar"
                                    style="width: {{ $supplier->communication_score }}%"
                                    aria-valuenow="{{ $supplier->communication_score }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="fw-semibold">{{ $supplier->communication_score }}%</span>
                        </div>
                    </div>

                    <div>
                        <h6 class="mb-2">Prijs/Kwaliteit</h6>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar"
                                    style="width: {{ $supplier->value_score }}%"
                                    aria-valuenow="{{ $supplier->value_score }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <span class="fw-semibold">{{ $supplier->value_score }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluations List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Beoordelingen</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#addEvaluationModal">
                    <i class="ri-add-line me-1"></i> Nieuwe Beoordeling
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Type</th>
                            <th>Beoordelaar</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplier->evaluations as $evaluation)
                            <tr>
                                <td>{{ $evaluation->created_at->format('d-m-Y') }}</td>
                                <td>{{ $evaluation->type }}</td>
                                <td>{{ $evaluation->evaluator->name }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $evaluation->score }}%"
                                                aria-valuenow="{{ $evaluation->score }}" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                        <span>{{ $evaluation->score }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-label-{{ $evaluation->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($evaluation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ri-more-fill"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#viewEvaluationModal{{ $evaluation->id }}">
                                                <i class="ri-eye-line me-2"></i>Bekijken
                                            </a>
                                            @if ($evaluation->status !== 'completed')
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#editEvaluationModal{{ $evaluation->id }}">
                                                    <i class="ri-pencil-line me-2"></i>Bewerken
                                                </a>
                                            @endif
                                            <a class="dropdown-item"
                                                href="{{ route('suppliers.evaluations.download', $evaluation->id) }}">
                                                <i class="ri-download-line me-2"></i>Downloaden
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Geen beoordelingen gevonden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Evaluation Modals -->
@foreach ($supplier->evaluations as $evaluation)
    @include('suppliers.modals.view-evaluation', ['evaluation' => $evaluation])
    @if ($evaluation->status !== 'completed')
        @include('suppliers.modals.edit-evaluation', ['evaluation' => $evaluation])
    @endif
@endforeach
