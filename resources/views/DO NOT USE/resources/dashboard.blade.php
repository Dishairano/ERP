@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Resource Utilization Overview -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Resource Utilization</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="utilizationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="col-md-4">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-2">Total Resources</h5>
                                        <h4 class="mb-0">{{ $resourceUtilization->count() }}</h4>
                                    </div>
                                    <div class="avatar">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="fas fa-cube"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-2">Active Assignments</h5>
                                        <h4 class="mb-0">
                                            {{ $resourceUtilization->filter(fn($r) => $r['utilization'] > 0)->count() }}
                                        </h4>
                                    </div>
                                    <div class="avatar">
                                        <span class="avatar-initial rounded bg-label-success">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Upcoming Maintenance -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Upcoming Maintenance</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#scheduleMaintenance">
                            <i class="fas fa-plus"></i> Schedule Maintenance
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Resource</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($upcomingMaintenance as $maintenance)
                                        <tr>
                                            <td>{{ $maintenance->resource->name }}</td>
                                            <td>{{ $maintenance->maintenance_type }}</td>
                                            <td>{{ $maintenance->scheduled_date->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $maintenance->status === 'scheduled' ? 'info' : ($maintenance->status === 'in_progress' ? 'warning' : 'success') }}">
                                                    {{ ucfirst($maintenance->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('resources.show', $maintenance->resource) }}"
                                                        class="btn btn-sm btn-info" title="Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if ($maintenance->status === 'scheduled')
                                                        <button type="button" class="btn btn-sm btn-success"
                                                            title="Start Maintenance">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resource Costs -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Resource Costs (Last 3 Months)</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="costsChart"></canvas>
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
            // Utilization Chart
            const utilizationCtx = document.getElementById('utilizationChart').getContext('2d');
            const utilizationData = @json($resourceUtilization);

            new Chart(utilizationCtx, {
                type: 'bar',
                data: {
                    labels: utilizationData.map(r => r.name),
                    datasets: [{
                        label: 'Current Utilization (%)',
                        data: utilizationData.map(r => r.utilization),
                        backgroundColor: utilizationData.map(r => {
                            if (r.utilization > 80) return '#dc3545';
                            if (r.utilization > 60) return '#ffc107';
                            return '#28a745';
                        }),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });

            // Costs Chart
            const costsCtx = document.getElementById('costsChart').getContext('2d');
            const costsData = @json($resourceCosts);

            new Chart(costsCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys(costsData),
                    datasets: [{
                        data: Object.values(costsData),
                        backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#dc3545', '#007bff']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        });
    </script>
@endpush
