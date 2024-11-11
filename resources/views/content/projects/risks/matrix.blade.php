@extends('layouts/contentNavbarLayout')

@section('title', 'Risk Matrix')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Risk Matrix</h4>
                            <small class="text-muted">Project: {{ $project->name }}</small>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-secondary">
                                <i data-feather="arrow-left"></i> Back to Project
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 150px">Severity →<br>Likelihood ↓</th>
                                            <th class="text-center bg-light-success">1<br>Negligible</th>
                                            <th class="text-center bg-light-success">2<br>Minor</th>
                                            <th class="text-center bg-light-warning">3<br>Moderate</th>
                                            <th class="text-center bg-light-warning">4<br>Major</th>
                                            <th class="text-center bg-light-danger">5<br>Critical</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($likelihood = 5; $likelihood >= 1; $likelihood--)
                                            <tr>
                                                <td class="text-center fw-bold">
                                                    {{ $likelihood }}
                                                    @switch($likelihood)
                                                        @case(5)
                                                            <br><small>Almost Certain</small>
                                                        @break

                                                        @case(4)
                                                            <br><small>Likely</small>
                                                        @break

                                                        @case(3)
                                                            <br><small>Possible</small>
                                                        @break

                                                        @case(2)
                                                            <br><small>Unlikely</small>
                                                        @break

                                                        @case(1)
                                                            <br><small>Rare</small>
                                                        @break
                                                    @endswitch
                                                </td>
                                                @for ($severity = 1; $severity <= 5; $severity++)
                                                    @php
                                                        $riskLevel = $severity * $likelihood;
                                                        $bgClass =
                                                            $riskLevel >= 16
                                                                ? 'bg-light-danger'
                                                                : ($riskLevel >= 9
                                                                    ? 'bg-light-warning'
                                                                    : ($riskLevel >= 4
                                                                        ? 'bg-light-info'
                                                                        : 'bg-light-success'));
                                                        $risks = $project
                                                            ->risks()
                                                            ->where('severity', $severity)
                                                            ->where('likelihood', $likelihood)
                                                            ->get();
                                                    @endphp
                                                    <td class="{{ $bgClass }} align-middle" style="min-height: 100px">
                                                        @if ($risks->count() > 0)
                                                            <div class="p-1">
                                                                @foreach ($risks as $risk)
                                                                    <div class="mb-1">
                                                                        <a href="{{ route('projects.risks.show', [$project->id, $risk->id]) }}"
                                                                            class="text-body">
                                                                            {{ $risk->title }}
                                                                        </a>
                                                                        <br>
                                                                        <small
                                                                            class="text-muted">{{ $risk->category }}</small>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="text-center p-1">
                                                                <small class="text-muted">{{ $riskLevel }}</small>
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Risk Level Legend</h6>
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        <div class="badge bg-light-success">Low (1-3)</div>
                                        <div class="badge bg-light-info">Medium (4-8)</div>
                                        <div class="badge bg-light-warning">High (9-15)</div>
                                        <div class="badge bg-light-danger">Critical (16-25)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Risk Summary</h6>
                                    <div class="d-flex flex-wrap gap-3 mt-2">
                                        <div>
                                            <small class="fw-bold">Critical Risks</small>
                                            <h3 class="mb-0">
                                                {{ $project->risks()->whereRaw('severity * likelihood >= ?', [16])->count() }}
                                            </h3>
                                        </div>
                                        <div>
                                            <small class="fw-bold">High Risks</small>
                                            <h3 class="mb-0">
                                                {{ $project->risks()->whereRaw('severity * likelihood >= ? AND severity * likelihood < ?', [9, 16])->count() }}
                                            </h3>
                                        </div>
                                        <div>
                                            <small class="fw-bold">Medium Risks</small>
                                            <h3 class="mb-0">
                                                {{ $project->risks()->whereRaw('severity * likelihood >= ? AND severity * likelihood < ?', [4, 9])->count() }}
                                            </h3>
                                        </div>
                                        <div>
                                            <small class="fw-bold">Low Risks</small>
                                            <h3 class="mb-0">
                                                {{ $project->risks()->whereRaw('severity * likelihood < ?', [4])->count() }}
                                            </h3>
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

@section('page-script')
    <script>
        $(function() {
            'use strict';

            // Initialize feather icons
            feather.replace();
        });
    </script>
@endsection
