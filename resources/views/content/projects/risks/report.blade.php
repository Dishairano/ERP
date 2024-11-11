@php
    use App\Models\CoreProjectModal;
    use App\Models\CoreProjectRiskModal;
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Risk Report')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Project Risk Report</h4>
                            <small class="text-muted">Overall risk analysis across all projects</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Risk Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light-danger">
                                <div class="card-body">
                                    <h6>Critical Risks</h6>
                                    <h2 class="mb-0">
                                        {{ CoreProjectRiskModal::whereRaw('severity * likelihood >= ?', [16])->count() }}
                                    </h2>
                                    <small>Across all projects</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-warning">
                                <div class="card-body">
                                    <h6>High Risks</h6>
                                    <h2 class="mb-0">
                                        {{ CoreProjectRiskModal::whereRaw('severity * likelihood >= ? AND severity * likelihood < ?', [9, 16])->count() }}
                                    </h2>
                                    <small>Across all projects</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-info">
                                <div class="card-body">
                                    <h6>Medium Risks</h6>
                                    <h2 class="mb-0">
                                        {{ CoreProjectRiskModal::whereRaw('severity * likelihood >= ? AND severity * likelihood < ?', [4, 9])->count() }}
                                    </h2>
                                    <small>Across all projects</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-success">
                                <div class="card-body">
                                    <h6>Low Risks</h6>
                                    <h2 class="mb-0">
                                        {{ CoreProjectRiskModal::whereRaw('severity * likelihood < ?', [4])->count() }}</h2>
                                    <small>Across all projects</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Projects Risk Table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Critical Risks</th>
                                    <th>High Risks</th>
                                    <th>Medium Risks</th>
                                    <th>Low Risks</th>
                                    <th>Total Risks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (CoreProjectModal::with('risks')->get() as $project)
                                    @php
                                        $criticalRisks = $project
                                            ->risks()
                                            ->whereRaw('severity * likelihood >= ?', [16])
                                            ->count();
                                        $highRisks = $project
                                            ->risks()
                                            ->whereRaw('severity * likelihood >= ? AND severity * likelihood < ?', [
                                                9,
                                                16,
                                            ])
                                            ->count();
                                        $mediumRisks = $project
                                            ->risks()
                                            ->whereRaw('severity * likelihood >= ? AND severity * likelihood < ?', [
                                                4,
                                                9,
                                            ])
                                            ->count();
                                        $lowRisks = $project
                                            ->risks()
                                            ->whereRaw('severity * likelihood < ?', [4])
                                            ->count();
                                        $totalRisks = $project->risks()->count();
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
                                        </td>
                                        <td>
                                            @if ($criticalRisks > 0)
                                                <span
                                                    class="badge rounded-pill badge-light-danger">{{ $criticalRisks }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($highRisks > 0)
                                                <span
                                                    class="badge rounded-pill badge-light-warning">{{ $highRisks }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($mediumRisks > 0)
                                                <span class="badge rounded-pill badge-light-info">{{ $mediumRisks }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($lowRisks > 0)
                                                <span
                                                    class="badge rounded-pill badge-light-success">{{ $lowRisks }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td>{{ $totalRisks }}</td>
                                        <td>
                                            <a href="{{ route('projects.risks.matrix', $project->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i data-feather="grid"></i> Risk Matrix
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Risk Categories -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Risks by Category</h6>
                                    <div class="table-responsive mt-2">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Count</th>
                                                    <th>Critical</th>
                                                    <th>High</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (['technical', 'schedule', 'resource', 'cost', 'quality'] as $category)
                                                    @php
                                                        $risks = CoreProjectRiskModal::where('category', $category);
                                                        $count = $risks->count();
                                                        $critical = $risks
                                                            ->whereRaw('severity * likelihood >= ?', [16])
                                                            ->count();
                                                        $high = $risks
                                                            ->whereRaw(
                                                                'severity * likelihood >= ? AND severity * likelihood < ?',
                                                                [9, 16],
                                                            )
                                                            ->count();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ ucfirst($category) }}</td>
                                                        <td>{{ $count }}</td>
                                                        <td>
                                                            @if ($critical > 0)
                                                                <span
                                                                    class="badge rounded-pill badge-light-danger">{{ $critical }}</span>
                                                            @else
                                                                <span class="text-muted">0</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($high > 0)
                                                                <span
                                                                    class="badge rounded-pill badge-light-warning">{{ $high }}</span>
                                                            @else
                                                                <span class="text-muted">0</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Risks by Status</h6>
                                    <div class="table-responsive mt-2">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Count</th>
                                                    <th>Critical</th>
                                                    <th>High</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (['identified', 'assessed', 'mitigated', 'closed'] as $status)
                                                    @php
                                                        $risks = CoreProjectRiskModal::where('status', $status);
                                                        $count = $risks->count();
                                                        $critical = $risks
                                                            ->whereRaw('severity * likelihood >= ?', [16])
                                                            ->count();
                                                        $high = $risks
                                                            ->whereRaw(
                                                                'severity * likelihood >= ? AND severity * likelihood < ?',
                                                                [9, 16],
                                                            )
                                                            ->count();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ ucfirst($status) }}</td>
                                                        <td>{{ $count }}</td>
                                                        <td>
                                                            @if ($critical > 0)
                                                                <span
                                                                    class="badge rounded-pill badge-light-danger">{{ $critical }}</span>
                                                            @else
                                                                <span class="text-muted">0</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($high > 0)
                                                                <span
                                                                    class="badge rounded-pill badge-light-warning">{{ $high }}</span>
                                                            @else
                                                                <span class="text-muted">0</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
