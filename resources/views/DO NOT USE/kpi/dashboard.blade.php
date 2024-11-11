@extends('layouts/contentNavbarLayout')

@section('title', 'KPI Dashboard')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">
@endsection

@section('content')
    <div class="row">
        <!-- KPI Summary Cards -->
        @foreach ($kpis as $kpi)
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $kpi->name }}</h5>
                        <small class="text-muted">{{ $kpi->category }}</small>

                        @php
                            $latestValue = $kpi->getLatestValue();
                            $currentTarget = $kpi->getCurrentTarget();
                            $progress = $currentTarget
                                ? ($latestValue->value / $currentTarget->target_value) * 100
                                : null;
                        @endphp

                        <div class="mt-2">
                            <h2 class="mb-0">
                                {{ number_format($latestValue->value, 2) }} {{ $kpi->unit }}
                            </h2>
                            @if ($currentTarget)
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ min($progress, 100) }}%"
                                        aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    Target: {{ number_format($currentTarget->target_value, 2) }} {{ $kpi->unit }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Charts Row -->
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">KPI Trends</h5>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="kpiTrendDropdown"
                            data-bs-toggle="dropdown">
                            Select KPI
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($kpis as $kpi)
                                <li>
                                    <a class="dropdown-item" href="#"
                                        onclick="updateTrendChart('{{ $kpi->id }}')">
                                        {{ $kpi->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="kpiTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Alerts</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline">
                        @forelse($notifications as $notification)
                            <li class="timeline-item">
                                <span
                                    class="timeline-point timeline-point-{{ $notification->severity === 'critical' ? 'danger' : 'warning' }}">
                                    <i class="fas fa-exclamation"></i>
                                </span>
                                <div class="timeline-event">
                                    <div class="timeline-header">
                                        <h6 class="mb-0">{{ $notification->definition->name }}</h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="d-flex flex-wrap mt-1">
                                        <p class="mb-0">{{ $notification->message }}</p>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="text-center">No recent alerts</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Performance Overview -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Performance Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
@endsection

@section('page-script')
    <script>
        let trendChart, performanceChart;

        function initCharts() {
            // Initialize trend chart
            const trendCtx = document.getElementById('kpiTrendChart').getContext('2d');
            trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Value',
                        data: [],
                        borderColor: '#7367f0',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Initialize performance chart
            const perfCtx = document.getElementById('performanceChart').getContext('2d');
            performanceChart = new Chart(perfCtx, {
                type: 'radar',
                data: {
                    labels: @json($kpis->pluck('name')),
                    datasets: [{
                        label: 'Current',
                        data: @json(
                            $kpis->map(function ($kpi) {
                                $latest = $kpi->getLatestValue();
                                $target = $kpi->getCurrentTarget();
                                return $target ? ($latest->value / $target->target_value) * 100 : 0;
                            })),
                        borderColor: '#7367f0',
                        backgroundColor: 'rgba(115, 103, 240, 0.2)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                stepSize: 20
                            }
                        }
                    }
                }
            });
        }

        function updateTrendChart(kpiId) {
            fetch(`/api/kpi/${kpiId}/trend`)
                .then(response => response.json())
                .then(data => {
                    trendChart.data.labels = data.labels;
                    trendChart.data.datasets[0].data = data.values;
                    trendChart.data.datasets[0].label = data.name;
                    trendChart.update();
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
            // Load initial trend data for first KPI
            const firstKpiId = @json($kpis->first()->id ?? null);
            if (firstKpiId) {
                updateTrendChart(firstKpiId);
            }
        });
    </script>
@endsection
