@extends('layouts/contentNavbarLayout')

@section('title', 'Risk Reports')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Risk Reports</h5>
                        <a href="{{ route('projects.risks.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to Risks
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Risk Overview Cards -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle text-muted">Total Risks</h6>
                                        <h2 class="card-title mb-2">{{ $risksByCategory->flatten()->count() }}</h2>
                                        <small class="text-muted">Across all categories</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h6 class="card-subtitle">High Priority Risks</h6>
                                        <h2 class="card-title mb-2">{{ $highPriorityRisks->count() }}</h2>
                                        <small>Require immediate attention</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle text-muted">Open Risks</h6>
                                        <h2 class="card-title mb-2">
                                            {{ $risksByStatus->except(['closed'])->flatten()->count() }}
                                        </h2>
                                        <small class="text-muted">Requiring action</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle text-muted">Mitigated Risks</h6>
                                        <h2 class="card-title mb-2">
                                            {{ $risksByStatus->get('mitigated', collect())->count() + $risksByStatus->get('closed', collect())->count() }}
                                        </h2>
                                        <small class="text-muted">Successfully handled</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Risks by Category -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Risks by Category</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="risksByCategoryChart" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Risk Status Distribution</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="risksByStatusChart" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- High Priority Risks Table -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">High Priority Risks</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Risk Title</th>
                                                        <th>Category</th>
                                                        <th>Project</th>
                                                        <th>Risk Score</th>
                                                        <th>Status</th>
                                                        <th>Due Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($highPriorityRisks as $risk)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ route('projects.risks.show', $risk) }}">
                                                                    {{ $risk->title }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-label-primary">
                                                                    {{ $risk->category }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $risk->project->name }}</td>
                                                            <td>
                                                                <span class="badge bg-danger">
                                                                    {{ $risk->severity * $risk->likelihood }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $statusClass = match ($risk->status) {
                                                                        'identified' => 'info',
                                                                        'assessed' => 'warning',
                                                                        'mitigated' => 'success',
                                                                        'closed' => 'secondary',
                                                                        default => 'primary',
                                                                    };
                                                                @endphp
                                                                <span class="badge bg-label-{{ $statusClass }}">
                                                                    {{ ucfirst($risk->status) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $risk->due_date->format('M d, Y') }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-center">No high priority risks
                                                                found</td>
                                                        </tr>
                                                    @endforelse
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
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Risks by Category Chart
            new ApexCharts(document.querySelector("#risksByCategoryChart"), {
                chart: {
                    type: 'pie',
                    height: 300
                },
                series: [
                    @foreach ($risksByCategory as $category => $risks)
                        {{ $risks->count() }},
                    @endforeach
                ],
                labels: [
                    @foreach ($risksByCategory as $category => $risks)
                        '{{ $category }}',
                    @endforeach
                ],
                theme: {
                    mode: document.querySelector('html').getAttribute('data-theme') || 'light'
                }
            }).render();

            // Risks by Status Chart
            new ApexCharts(document.querySelector("#risksByStatusChart"), {
                chart: {
                    type: 'donut',
                    height: 300
                },
                series: [
                    @foreach ($risksByStatus as $status => $risks)
                        {{ $risks->count() }},
                    @endforeach
                ],
                labels: [
                    @foreach ($risksByStatus as $status => $risks)
                        '{{ ucfirst($status) }}',
                    @endforeach
                ],
                theme: {
                    mode: document.querySelector('html').getAttribute('data-theme') || 'light'
                }
            }).render();
        });
    </script>
@endsection
