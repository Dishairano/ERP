@extends('layouts/contentNavbarLayout')

@section('title', 'Risk Matrix')

@section('vendor-style')
    <style>
        .risk-matrix-cell {
            width: 150px;
            height: 150px;
            border: 1px solid #e0e0e0;
            padding: 10px;
            position: relative;
        }

        .risk-matrix-cell.high {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
        }

        .risk-matrix-cell.medium {
            background-color: rgba(var(--bs-warning-rgb), 0.1);
        }

        .risk-matrix-cell.low {
            background-color: rgba(var(--bs-success-rgb), 0.1);
        }

        .risk-item {
            font-size: 0.8rem;
            margin-bottom: 5px;
            padding: 5px;
            border-radius: 4px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .axis-label {
            font-weight: 600;
            text-align: center;
            padding: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Risk Matrix</h5>
                        <a href="{{ route('projects.risks.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to Risks
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="axis-label">Severity ↓ / Likelihood →</th>
                                        @for ($l = 1; $l <= 5; $l++)
                                            <th class="axis-label">{{ $l }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($s = 5; $s >= 1; $s--)
                                        <tr>
                                            <td class="axis-label">{{ $s }}</td>
                                            @for ($l = 1; $l <= 5; $l++)
                                                @php
                                                    $score = $s * $l;
                                                    $cellClass = match (true) {
                                                        $score >= 16 => 'high',
                                                        $score >= 8 => 'medium',
                                                        default => 'low',
                                                    };
                                                    $cellRisks = $risks->get($s . '-' . $l) ?? collect();
                                                @endphp
                                                <td class="risk-matrix-cell {{ $cellClass }}">
                                                    <div style="font-size: 0.75rem; margin-bottom: 10px;">
                                                        Score: {{ $score }}
                                                    </div>
                                                    <div style="max-height: 120px; overflow-y: auto;">
                                                        @foreach ($cellRisks as $risk)
                                                            <div class="risk-item">
                                                                <a href="{{ route('projects.risks.show', $risk) }}"
                                                                    class="text-decoration-none">
                                                                    {{ Str::limit($risk->title, 30) }}
                                                                </a>
                                                                <div class="text-muted" style="font-size: 0.75rem;">
                                                                    {{ $risk->category }}
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex align-items-center">
                                            <div style="width: 20px; height: 20px;" class="risk-matrix-cell high me-2">
                                            </div>
                                            <span>High Risk (16-25)</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 20px; height: 20px;" class="risk-matrix-cell medium me-2">
                                            </div>
                                            <span>Medium Risk (8-15)</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 20px; height: 20px;" class="risk-matrix-cell low me-2"></div>
                                            <span>Low Risk (1-7)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h6>Matrix Summary</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body">
                                            <h6 class="card-title text-white">High Risk Items</h6>
                                            <h2 class="mb-0">
                                                {{ $risks->filter(fn($risk) => $risk->first()->severity * $risk->first()->likelihood >= 16)->count() }}
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body">
                                            <h6 class="card-title text-white">Medium Risk Items</h6>
                                            <h2 class="mb-0">
                                                {{ $risks->filter(
                                                        fn($risk) => $risk->first()->severity * $risk->first()->likelihood >= 8 &&
                                                            $risk->first()->severity * $risk->first()->likelihood < 16,
                                                    )->count() }}
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h6 class="card-title text-white">Low Risk Items</h6>
                                            <h2 class="mb-0">
                                                {{ $risks->filter(fn($risk) => $risk->first()->severity * $risk->first()->likelihood < 8)->count() }}
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
