@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Inventory Planning /</span> ABC Analysis
        </h4>

        <!-- Summary Cards -->
        <div class="row">
            @foreach (['A', 'B', 'C'] as $classification)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="card-info">
                                    <p class="card-text">Class {{ $classification }} Items</p>
                                    <div class="d-flex align-items-end mb-2">
                                        <h4 class="card-title mb-0 me-2">
                                            {{ $groupedProducts[$classification]->count() ?? 0 }}
                                        </h4>
                                    </div>
                                    <small class="text-muted">
                                        {{ number_format(($groupedProducts[$classification]->sum('annual_usage_value') / $totalValue) * 100, 1) }}%
                                        of total value
                                    </small>
                                </div>
                                <div class="card-icon">
                                    <span
                                        class="badge bg-label-{{ $classification === 'A' ? 'primary' : ($classification === 'B' ? 'warning' : 'info') }} rounded p-2">
                                        <i class="ri-price-tag-3-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ABC Analysis Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">ABC Analysis Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Annual Usage Value</th>
                                <th>% of Total Value</th>
                                <th>Cumulative %</th>
                                <th>Classification</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $cumulativeValue = 0;
                            @endphp
                            @foreach ($groupedProducts as $classification => $products)
                                @foreach ($products as $product)
                                    @php
                                        $cumulativeValue += $product->annual_usage_value;
                                        $percentageOfTotal = ($product->annual_usage_value / $totalValue) * 100;
                                        $cumulativePercentage = ($cumulativeValue / $totalValue) * 100;
                                    @endphp
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ number_format($product->annual_usage_value, 2) }}</td>
                                        <td>{{ number_format($percentageOfTotal, 1) }}%</td>
                                        <td>{{ number_format($cumulativePercentage, 1) }}%</td>
                                        <td>
                                            <span
                                                class="badge bg-label-{{ $classification === 'A' ? 'primary' : ($classification === 'B' ? 'warning' : 'info') }}">
                                                Class {{ $classification }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ABC Analysis Chart -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Pareto Chart</h5>
            </div>
            <div class="card-body">
                <canvas id="paretoChart" height="300"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('paretoChart').getContext('2d');

            // Prepare data for Pareto chart
            const products = @json($groupedProducts->flatten());
            const labels = products.map(p => p.name);
            const values = products.map(p => p.annual_usage_value);

            // Calculate cumulative percentages
            let cumulative = 0;
            const cumulativePercentages = values.map(v => {
                cumulative += v;
                return (cumulative / @json($totalValue)) * 100;
            });

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Annual Usage Value',
                            data: values,
                            backgroundColor: 'rgba(105, 108, 255, 0.5)',
                            borderColor: 'rgb(105, 108, 255)',
                            borderWidth: 1
                        },
                        {
                            label: 'Cumulative %',
                            data: cumulativePercentages,
                            type: 'line',
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 2,
                            fill: false,
                            yAxisID: 'percentage'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Annual Usage Value'
                            }
                        },
                        percentage: {
                            position: 'right',
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Cumulative %'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
