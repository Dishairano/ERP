@extends('layouts/contentNavbarLayout')

@section('title', 'Risk Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Risk Details</h5>
                        <div>
                            <a href="{{ route('projects.risks.edit', $risk) }}" class="btn btn-primary me-2">
                                <i class="ri-pencil-line me-1"></i> Edit Risk
                            </a>
                            <a href="{{ route('projects.risks.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Back to Risks
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h6 class="fw-semibold">Project</h6>
                                <p class="mb-0">{{ $risk->project->name }}</p>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h6 class="fw-semibold">Risk Title</h6>
                                <p class="mb-0">{{ $risk->title }}</p>
                            </div>
                            <div class="col-12 mb-4">
                                <h6 class="fw-semibold">Description</h6>
                                <p class="mb-0">{{ $risk->description }}</p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h6 class="fw-semibold">Category</h6>
                                <span class="badge bg-label-primary">{{ $risk->category }}</span>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h6 class="fw-semibold">Risk Level</h6>
                                @php
                                    $levelClass = match ($risk->risk_level) {
                                        'High' => 'danger',
                                        'Medium' => 'warning',
                                        'Low' => 'success',
                                        default => 'primary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $levelClass }}">{{ $risk->risk_level }}</span>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h6 class="fw-semibold">Status</h6>
                                @php
                                    $statusClass = match ($risk->status) {
                                        'identified' => 'info',
                                        'assessed' => 'warning',
                                        'mitigated' => 'success',
                                        'closed' => 'secondary',
                                        default => 'primary',
                                    };
                                @endphp
                                <span class="badge bg-label-{{ $statusClass }}">{{ ucfirst($risk->status) }}</span>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h6 class="fw-semibold">Severity</h6>
                                <div class="progress" style="height: 24px;">
                                    @php
                                        $severityPercentage = ($risk->severity / 5) * 100;
                                        $severityClass = match (true) {
                                            $risk->severity >= 4 => 'danger',
                                            $risk->severity >= 3 => 'warning',
                                            $risk->severity >= 2 => 'info',
                                            default => 'success',
                                        };
                                    @endphp
                                    <div class="progress-bar bg-{{ $severityClass }}" role="progressbar"
                                        style="width: {{ $severityPercentage }}%" aria-valuenow="{{ $risk->severity }}"
                                        aria-valuemin="0" aria-valuemax="5">
                                        {{ $risk->severity }} / 5
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h6 class="fw-semibold">Likelihood</h6>
                                <div class="progress" style="height: 24px;">
                                    @php
                                        $likelihoodPercentage = ($risk->likelihood / 5) * 100;
                                        $likelihoodClass = match (true) {
                                            $risk->likelihood >= 4 => 'danger',
                                            $risk->likelihood >= 3 => 'warning',
                                            $risk->likelihood >= 2 => 'info',
                                            default => 'success',
                                        };
                                    @endphp
                                    <div class="progress-bar bg-{{ $likelihoodClass }}" role="progressbar"
                                        style="width: {{ $likelihoodPercentage }}%"
                                        aria-valuenow="{{ $risk->likelihood }}" aria-valuemin="0" aria-valuemax="5">
                                        {{ $risk->likelihood }} / 5
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <h6 class="fw-semibold">Impact</h6>
                                <p class="mb-0">{{ $risk->impact }}</p>
                            </div>
                            <div class="col-12 mb-4">
                                <h6 class="fw-semibold">Mitigation Strategy</h6>
                                <p class="mb-0">{{ $risk->mitigation_strategy }}</p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h6 class="fw-semibold">Due Date</h6>
                                <p class="mb-0">{{ $risk->due_date->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h6 class="fw-semibold">Risk Owner</h6>
                                <p class="mb-0">{{ $risk->owner }}</p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h6 class="fw-semibold">Last Updated</h6>
                                <p class="mb-0">{{ $risk->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <form action="{{ route('projects.risks.destroy', $risk) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this risk?')">
                                    <i class="ri-delete-bin-line me-1"></i> Delete Risk
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
