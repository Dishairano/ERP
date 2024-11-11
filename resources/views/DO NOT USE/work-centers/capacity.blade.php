@extends('layouts/contentNavbarLayout')

@section('title', 'Work Centers Capacity Planning')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Work Centers /</span> Capacity Planning
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Capacity Planning</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Work Center</th>
                                <th>Total Capacity</th>
                                <th>Allocated</th>
                                <th>Available</th>
                                <th>Utilization</th>
                                <th>Next Available Slot</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workCenters as $workCenter)
                                <tr>
                                    <td>{{ $workCenter->name }}</td>
                                    <td>{{ $workCenter->total_capacity }} hrs</td>
                                    <td>{{ $workCenter->allocated_capacity }} hrs</td>
                                    <td>{{ $workCenter->available_capacity }} hrs</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($workCenter->allocated_capacity / $workCenter->total_capacity) * 100 }}%"
                                                aria-valuenow="{{ ($workCenter->allocated_capacity / $workCenter->total_capacity) * 100 }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ round(($workCenter->allocated_capacity / $workCenter->total_capacity) * 100) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $workCenter->next_available_slot }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-calendar-line me-2"></i> Schedule
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-bar-chart-line me-2"></i> View Details
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-settings-line me-2"></i> Adjust Capacity
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $workCenters->links() }}
            </div>
        </div>
    </div>
@endsection
