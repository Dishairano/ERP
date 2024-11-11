@extends('layouts.contentNavbarLayout')

@section('title', 'Resource Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Resources Overview</h5>
                    <a href="{{ route('resources.create') }}" class="btn btn-primary">Add New Resource</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Current Utilization</th>
                                    <th>Capacity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resources as $resource)
                                    <tr>
                                        <td>{{ $resource->name }}</td>
                                        <td>{{ ucfirst($resource->type) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $resource->status === 'available' ? 'success' : ($resource->status === 'in_use' ? 'warning' : 'danger') }}">
                                                {{ ucfirst(str_replace('_', ' ', $resource->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar {{ $resource->current_utilization > 80 ? 'bg-danger' : ($resource->current_utilization > 60 ? 'bg-warning' : 'bg-success') }}"
                                                    role="progressbar" style="width: {{ $resource->current_utilization }}%"
                                                    aria-valuenow="{{ $resource->current_utilization }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ number_format($resource->current_utilization, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $resource->capacity }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('resources.show', $resource) }}">
                                                        <i class="bx bx-show-alt me-1"></i> View Details
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('resources.edit', $resource) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('resources.destroy', $resource) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Are you sure you want to delete this resource?')">
                                                            <i class="bx bx-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
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
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Upcoming Maintenance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Resource</th>
                                    <th>Type</th>
                                    <th>Scheduled Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resources->flatMap->maintenanceSchedules->where('status', '!=', 'completed')->sortBy('scheduled_date')->take(5) as $maintenance)
                                    <tr>
                                        <td>{{ $maintenance->resource->name }}</td>
                                        <td>{{ $maintenance->maintenance_type }}</td>
                                        <td>{{ $maintenance->scheduled_date->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $maintenance->status === 'scheduled' ? 'info' : ($maintenance->status === 'in_progress' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($maintenance->status) }}
                                            </span>
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
                <div class="card-header">
                    <h5 class="mb-0">Resource Utilization Overview</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="utilizationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('utilizationChart').getContext('2d');
            const resources = @json(
                $resources->map(function ($resource) {
                    return [
                        'name' => $resource->name,
                        'utilization' => $resource->current_utilization,
                    ];
                }));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: resources.map(r => r.name),
                    datasets: [{
                        label: 'Current Utilization (%)',
                        data: resources.map(r => r.utilization),
                        backgroundColor: resources.map(r => {
                            if (r.utilization > 80) return '#dc3545';
                            if (r.utilization > 60) return '#ffc107';
                            return '#28a745';
                        }),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        });
    </script>
@endsection
