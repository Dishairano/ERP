@extends('layouts.contentNavbarLayout')

@section('title', 'Resource Details')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $resource->name }}</h5>
                    <div>
                        <a href="{{ route('resources.edit', $resource) }}" class="btn btn-primary me-2">
                            <i class="bx bx-edit-alt me-1"></i> Edit Resource
                        </a>
                        <a href="{{ route('resources.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Resource Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Type</th>
                                    <td>{{ ucfirst($resource->type) }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span
                                            class="badge bg-{{ $resource->status === 'available' ? 'success' : ($resource->status === 'in_use' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $resource->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Capacity</th>
                                    <td>{{ $resource->capacity }}</td>
                                </tr>
                                <tr>
                                    <th>Current Utilization</th>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $utilization > 80 ? 'bg-danger' : ($utilization > 60 ? 'bg-warning' : 'bg-success') }}"
                                                role="progressbar" style="width: {{ $utilization }}%"
                                                aria-valuenow="{{ $utilization }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($utilization, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Cost per Hour</th>
                                    <td>€{{ number_format($resource->cost_per_hour, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Cost per Day</th>
                                    <td>€{{ number_format($resource->cost_per_day, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $resource->description ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Capabilities & Location</h6>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h6>Capabilities</h6>
                                    @if ($resource->capabilities)
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($resource->capabilities as $capability)
                                                <li><i class="bx bx-check-circle text-success me-2"></i>{{ $capability }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted mb-0">No capabilities listed</p>
                                    @endif
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h6>Location Details</h6>
                                    @if ($resource->location_details)
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($resource->location_details as $key => $value)
                                                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                    {{ $value }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted mb-0">No location details available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#assignments"
                                        type="button">
                                        Assignments
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#maintenance"
                                        type="button">
                                        Maintenance Schedule
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#costs" type="button">
                                        Costs
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="assignments">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Project</th>
                                                    <th>User</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Status</th>
                                                    <th>Hours Used</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($resource->assignments as $assignment)
                                                    <tr>
                                                        <td>{{ $assignment->project->name ?? 'N/A' }}</td>
                                                        <td>{{ $assignment->user->name ?? 'N/A' }}</td>
                                                        <td>{{ $assignment->start_time->format('Y-m-d H:i') }}</td>
                                                        <td>{{ $assignment->end_time->format('Y-m-d H:i') }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $assignment->status === 'completed' ? 'success' : ($assignment->status === 'active' ? 'primary' : 'warning') }}">
                                                                {{ ucfirst($assignment->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $assignment->actual_hours_used ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="maintenance">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Scheduled Date</th>
                                                    <th>Status</th>
                                                    <th>Duration (Hours)</th>
                                                    <th>Cost</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($upcomingMaintenance as $maintenance)
                                                    <tr>
                                                        <td>{{ $maintenance->maintenance_type }}</td>
                                                        <td>{{ $maintenance->scheduled_date->format('Y-m-d H:i') }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $maintenance->status === 'completed' ? 'success' : ($maintenance->status === 'in_progress' ? 'primary' : 'warning') }}">
                                                                {{ ucfirst($maintenance->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $maintenance->estimated_duration_hours }}</td>
                                                        <td>€{{ number_format($maintenance->cost, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="costs">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Cost Type</th>
                                                    <th>Amount</th>
                                                    <th>Project</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($resource->costs as $cost)
                                                    <tr>
                                                        <td>{{ $cost->date->format('Y-m-d') }}</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $cost->cost_type)) }}</td>
                                                        <td>€{{ number_format($cost->amount, 2) }}</td>
                                                        <td>{{ $cost->project->name ?? 'N/A' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $cost->status === 'actual' ? 'success' : ($cost->status === 'planned' ? 'primary' : 'warning') }}">
                                                                {{ ucfirst($cost->status) }}
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
