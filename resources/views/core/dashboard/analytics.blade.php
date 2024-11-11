@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Analytics')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-12">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="card-title m-0">Dashboard Analytics</h4>
                                    <button class="btn btn-primary" onclick="refreshMetrics()">
                                        <i class="ri-refresh-line me-1"></i> Refresh Metrics
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Metrics -->
        <div class="row">
            @forelse ($kpiMetrics as $metric)
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="card-info">
                                    <p class="card-text">{{ $metric->metric_name }}</p>
                                    <div class="d-flex align-items-end mb-2">
                                        <h4 class="card-title mb-0 me-2">{{ number_format($metric->metric_value, 2) }}</h4>
                                        @if ($metric->percentage_change)
                                            <small
                                                class="text-{{ $metric->percentage_change >= 0 ? 'success' : 'danger' }}">
                                                {{ $metric->percentage_change >= 0 ? '+' : '' }}{{ number_format($metric->percentage_change, 1) }}%
                                            </small>
                                        @endif
                                    </div>
                                    <small>{{ $metric->time_period }}</small>
                                </div>
                                <div class="card-icon">
                                    <span
                                        class="badge bg-label-{{ $metric->status === 'positive' ? 'success' : ($metric->status === 'negative' ? 'danger' : 'primary') }} rounded p-2">
                                        <i class="ri-line-chart-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ri-bar-chart-2-line fs-3 text-muted mb-3"></i>
                            <h5>No KPI Metrics Available</h5>
                            <p class="text-muted">There are currently no KPI metrics to display.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Chart Metrics -->
        <div class="row">
            @forelse ($chartMetrics as $metric)
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">{{ $metric->metric_name }}</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="chart{{ $metric->id }}Options"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="chart{{ $metric->id }}Options">
                                    <a class="dropdown-item" href="javascript:void(0);">View Details</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Download Report</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="chart{{ $metric->id }}" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ri-line-chart-line fs-3 text-muted mb-3"></i>
                            <h5>No Chart Metrics Available</h5>
                            <p class="text-muted">There are currently no chart metrics to display.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Trend Metrics -->
        <div class="row">
            @forelse ($trendMetrics as $metric)
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">{{ $metric->metric_name }}</h5>
                            <small class="text-muted">{{ $metric->time_period }}</small>
                        </div>
                        <div class="card-body">
                            <div id="trend{{ $metric->id }}" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ri-line-chart-line fs-3 text-muted mb-3"></i>
                            <h5>No Trend Metrics Available</h5>
                            <p class="text-muted">There are currently no trend metrics to display.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        function refreshMetrics() {
            fetch('/core/dashboard/analytics/refresh', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        // Show success message
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize charts for chart metrics
            @foreach ($chartMetrics as $metric)
                @if ($metric->chart_data)
                    new ApexCharts(document.querySelector("#chart{{ $metric->id }}"), {
                        ...{!! json_encode($metric->chart_data) !!},
                        chart: {
                            height: 300,
                            type: '{{ $metric->chart_data['type'] ?? 'line' }}',
                            toolbar: {
                                show: true
                            }
                        },
                        theme: {
                            mode: document.querySelector('html').getAttribute('data-theme') || 'light'
                        }
                    }).render();
                @endif
            @endforeach

            // Initialize charts for trend metrics
            @foreach ($trendMetrics as $metric)
                @if ($metric->chart_data)
                    new ApexCharts(document.querySelector("#trend{{ $metric->id }}"), {
                        ...{!! json_encode($metric->chart_data) !!},
                        chart: {
                            height: 200,
                            type: 'area',
                            toolbar: {
                                show: false
                            },
                            sparkline: {
                                enabled: true
                            }
                        },
                        theme: {
                            mode: document.querySelector('html').getAttribute('data-theme') || 'light'
                        }
                    }).render();
                @endif
            @endforeach
        });
    </script>
@endsection
