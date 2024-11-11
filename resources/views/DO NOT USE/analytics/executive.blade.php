@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Analytics /</span> Executive Dashboard
        </h4>

        <!-- KPI Summary Cards -->
        <div class="row">
            @foreach ($kpis as $kpiName => $values)
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="card-info">
                                    <p class="card-text">{{ $kpiName }}</p>
                                    <div class="d-flex align-items-end mb-2">
                                        <h4 class="card-title mb-0 me-2">
                                            @if ($values->first())
                                                {{ number_format($values->first()->value, 2) }}
                                            @else
                                                0.00
                                            @endif
                                        </h4>
                                    </div>
                                    @php
                                        $previousValue = $values->skip(1)->first()?->value;
                                        $currentValue = $values->first()?->value;
                                        $change = null;
                                        if ($previousValue && $currentValue) {
                                            $change = (($currentValue - $previousValue) / $previousValue) * 100;
                                        }
                                    @endphp
                                    @if (isset($change))
                                        <small class="text-{{ $change >= 0 ? 'success' : 'danger' }}">
                                            <i class="ri-arrow-{{ $change >= 0 ? 'up' : 'down' }}-line"></i>
                                            {{ number_format(abs($change), 1) }}%
                                        </small>
                                    @endif
                                </div>
                                <div class="card-icon">
                                    <span class="badge bg-label-primary rounded p-2">
                                        <i class="ri-line-chart-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Performance Charts -->
        <div class="row">
            <!-- Revenue Trend -->
            <div class="col-12 col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title m-0">Revenue Trend</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueTrendChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Metrics -->
            <div class="col-12 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title m-0">Key Metrics</h5>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            @foreach ($kpis->take(5) as $kpiName => $values)
                                <li class="d-flex mb-4 pb-1">
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">{{ $kpiName }}</h6>
                                            <small class="text-muted">
                                                {{ $values->first()?->definition?->description ?? 'No description available' }}
                                            </small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">
                                                @if ($values->first())
                                                    {{ number_format($values->first()->value, 2) }}
                                                @else
                                                    0.00
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Trend Chart
            const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');

            // Check if Revenue data exists
            const revenueData = @json($kpis['Revenue'] ?? collect());

            if (revenueData.length > 0) {
                new Chart(revenueTrendCtx, {
                    type: 'line',
                    data: {
                        labels: revenueData.map(item => new Date(item.created_at).toLocaleDateString(
                            'en-US', {
                                month: 'short',
                                year: 'numeric'
                            })),
                        datasets: [{
                            label: 'Revenue',
                            data: revenueData.map(item => item.value),
                            borderColor: '#696cff',
                            tension: 0.1,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('en-US', {
                                            style: 'currency',
                                            currency: 'USD',
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        }).format(value);
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return new Intl.NumberFormat('en-US', {
                                            style: 'currency',
                                            currency: 'USD',
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        }).format(context.raw);
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                // If no revenue data, display a message
                const ctx = revenueTrendCtx;
                ctx.font = '14px Arial';
                ctx.fillStyle = '#666';
                ctx.textAlign = 'center';
                ctx.fillText('No revenue data available', ctx.canvas.width / 2, ctx.canvas.height / 2);
            }
        });
    </script>
@endsection
