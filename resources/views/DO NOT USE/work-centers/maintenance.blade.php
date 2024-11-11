@extends('layouts/contentNavbarLayout')

@section('title', 'Work Centers Maintenance')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Work Centers /</span> Maintenance
        </h4>

        <div class="row">
            <!-- Scheduled Maintenance -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Scheduled Maintenance</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Work Center</th>
                                        <th>Maintenance Type</th>
                                        <th>Scheduled Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Technician</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($maintenanceRecords as $record)
                                        <tr>
                                            <td>{{ $record->workCenter->name }}</td>
                                            <td>{{ $record->maintenance_type }}</td>
                                            <td>{{ $record->scheduled_date }}</td>
                                            <td>{{ $record->duration }} hrs</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $record->status === 'Completed' ? 'success' : ($record->status === 'Scheduled' ? 'info' : 'warning') }}">
                                                    {{ $record->status }}
                                                </span>
                                            </td>
                                            <td>{{ $record->technician }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-eye-line me-2"></i> View Details
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-edit-line me-2"></i> Update Status
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-calendar-line me-2"></i> Reschedule
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $maintenanceRecords->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
