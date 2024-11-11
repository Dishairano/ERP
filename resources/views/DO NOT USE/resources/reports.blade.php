@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Monthly Utilization -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Monthly Resource Utilization</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlyUtilizationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Costs by Type -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Costs by Type</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="costsByTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Maintenance Statistics -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Maintenance Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Maintenance Type</th>
                                        <th>Total Count</th>
                                        <th>Average Duration (Hours)</th>
                                        <th>Total Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($maintenanceStats as $stat)
                                        <tr>
                                            <td>{{ ucfirst(str_replace('_', ' ', $stat->maintenance_type)) }}</td>
                                            <td>{{ $stat->total }}</td>
                                            <td>{{ number_format($stat->avg_duration, 2) }}</td>
                                            <td>€{{ number_format($stat->total_cost, 2) }}</td>
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Utilization Chart
            const monthlyData = @json($monthlyUtilization);
            const monthlyCtx = document.getElementById('monthlyUtilizationChart').getContext('2d');

            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(d => `${d.year}-${d.month}`),
                    datasets: [{
                        label: 'Total Hours Used',
                        data: monthlyData.map(d => d.total_hours),
                        borderColor: '#28a745',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Costs by Type Chart
            const costsData = @json($costsByType);
            const costsCtx = document.getElementById('costsByTypeChart').getContext('2d');

            new Chart(costsCtx, {
                type: 'doughnut',
                data: {
                    labels: costsData.map(c => ucfirst(c.cost_type.replace('_', ' '))),
                    datasets: [{
                        data: costsData.map(c => c.total),
                        backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#dc3545', '#007bff']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });

        function ucfirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    </script>
@endpush
