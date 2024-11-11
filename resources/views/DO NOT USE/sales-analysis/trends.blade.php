@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Sales Analysis /</span> Trends
        </h4>

        <!-- Sales Trends Chart -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">Monthly Sales Trends</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesTrendsChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Trends Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">Monthly Sales Data</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Total Sales</th>
                                        <th>Growth</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $previousTotal = 0;
                                    @endphp
                                    @forelse($salesTrends as $trend)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $trend->month)->format('F Y') }}
                                            </td>
                                            <td>{{ number_format($trend->total, 2) }}</td>
                                            <td>
                                                @if ($previousTotal > 0)
                                                    @php
                                                        $growth =
                                                            (($trend->total - $previousTotal) / $previousTotal) * 100;
                                                    @endphp
                                                    <span class="badge bg-label-{{ $growth >= 0 ? 'success' : 'danger' }}">
                                                        {{ number_format($growth, 1) }}%
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $previousTotal = $trend->total;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No sales trends data available.</td>
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
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const salesTrendsData = @json($salesTrends);

            const labels = salesTrendsData.map(data => {
                return moment(data.month).format('MMM YYYY');
            });

            const values = salesTrendsData.map(data => data.total);

            const ctx = document.getElementById('salesTrendsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Monthly Sales',
                        data: values,
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
                                        currency: 'USD'
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
                                        currency: 'USD'
                                    }).format(context.raw);
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
