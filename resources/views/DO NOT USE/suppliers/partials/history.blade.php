<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Audit Trail</h5>
    </div>
    <div class="card-body">
        <div class="timeline">
            @forelse($supplier->auditTrail as $audit)
                <div class="timeline-item">
                    <span
                        class="timeline-point timeline-point-{{ $audit->type === 'create'
                            ? 'success'
                            : ($audit->type === 'update'
                                ? 'warning'
                                : ($audit->type === 'delete'
                                    ? 'danger'
                                    : 'info')) }}"></span>
                    <div class="timeline-event">
                        <div class="timeline-header">
                            <h6 class="mb-0">{{ $audit->description }}</h6>
                            <small class="text-muted">{{ $audit->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap mt-2">
                            <div>
                                <span class="text-muted">Door: </span>
                                <span class="fw-semibold">{{ $audit->user->name }}</span>
                            </div>
                            @if ($audit->type === 'update')
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#viewChangesModal{{ $audit->id }}">
                                    <i class="ri-eye-line me-1"></i>Wijzigingen Bekijken
                                </button>
                            @endif
                        </div>
                        @if ($audit->metadata)
                            <div class="mt-2">
                                <small class="text-muted">{{ $audit->metadata }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center p-4">
                    <h6 class="text-muted mb-0">Geen audit trail beschikbaar</h6>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Changes Modals -->
@foreach ($supplier->auditTrail as $audit)
    @if ($audit->type === 'update')
        @include('suppliers.modals.view-changes', ['audit' => $audit])
    @endif
@endforeach
