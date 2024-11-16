@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Dashboard')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Time Registration Stats -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text">Today's Hours</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ number_format($todayHours, 2) }}</h4>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-primary rounded p-2">
                                <i class="bx bx-time bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text">Week Hours</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ number_format($weekHours, 2) }}</h4>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-success rounded p-2">
                                <i class="bx bx-calendar-week bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text">Month Hours</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ number_format($monthHours, 2) }}</h4>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-info rounded p-2">
                                <i class="bx bx-calendar bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text">Pending Approvals</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $pendingApprovals }}</h4>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-warning rounded p-2">
                                <i class="bx bx-hourglass bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('time-registration.create') }}" class="btn btn-primary d-block">
                                <i class="bx bx-plus me-1"></i> Register Time
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('time-registration.calendar') }}" class="btn btn-outline-primary d-block">
                                <i class="bx bx-calendar me-1"></i> View Calendar
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('time-registration.index') }}" class="btn btn-outline-primary d-block">
                                <i class="bx bx-list-ul me-1"></i> View All Entries
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('time-registration.approvals') }}" class="btn btn-outline-primary d-block">
                                <i class="bx bx-check-circle me-1"></i> Pending Approvals
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Time Registrations -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Time Registrations</h5>
                    <a href="{{ route('time-registration.index') }}" class="btn btn-primary btn-sm">
                        View All
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Task</th>
                                <th>Hours</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRegistrations as $registration)
                                <tr>
                                    <td>{{ $registration->date->format('M d, Y') }}</td>
                                    <td>{{ optional($registration->project)->name ?? 'N/A' }}</td>
                                    <td>{{ optional($registration->task)->title ?? 'N/A' }}</td>
                                    <td>{{ number_format($registration->hours, 2) }}</td>
                                    <td>
                                        <span class="badge bg-label-{{
                                            match($registration->status) {
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'submitted' => 'warning',
                                                default => 'secondary'
                                            }
                                        }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('time-registration.show', $registration) }}">
                                                    <i class="bx bx-show me-1"></i> View
                                                </a>
                                                @if($registration->status === 'draft')
                                                    <a class="dropdown-item" href="{{ route('time-registration.edit', $registration) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('time-registration.destroy', $registration) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure?')">
                                                            <i class="bx bx-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No recent time registrations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
