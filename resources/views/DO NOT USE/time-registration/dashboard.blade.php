@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Time Management /</span> Dashboard
        </h4>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Hours Today</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($stats['hours_today'], 1) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ri-time-line"></i>
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
                                <p class="card-text">Hours This Week</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($stats['hours_this_week'], 1) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="ri-calendar-line"></i>
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
                                    <h4 class="card-title mb-0 me-2">{{ $stats['pending_approvals'] }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-timer-line"></i>
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
                                <p class="card-text">Leave Balance</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $stats['leave_balance'] }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-calendar-event-line"></i>
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
                                    <i class="ri-time-line me-2"></i> Register Time
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('leave-requests.create') }}" class="btn btn-info d-block">
                                    <i class="ri-calendar-event-line me-2"></i> Request Leave
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('time-registration.calendar') }}" class="btn btn-success d-block">
                                    <i class="ri-calendar-line me-2"></i> View Calendar
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('time-registration.export') }}" class="btn btn-warning d-block">
                                    <i class="ri-file-chart-line me-2"></i> View Reports
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
                        <h5 class="card-title mb-0">Recent Time Registrations</h5>
                        <a href="{{ route('time-registration.index') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
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
                                        <td>{{ $registration->date->format('Y-m-d') }}</td>
                                        <td>{{ $registration->project->name }}</td>
                                        <td>{{ $registration->task->name }}</td>
                                        <td>{{ number_format($registration->hours, 1) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-label-{{ $registration->status === 'approved' ? 'success' : ($registration->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">
                                                        <i class="ri-eye-line me-2"></i> View
                                                    </a>
                                                    @if ($registration->status === 'pending')
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-pencil-line me-2"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item text-danger" href="#">
                                                            <i class="ri-delete-bin-line me-2"></i> Delete
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No recent time registrations</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Overview Chart -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Weekly Overview</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="weeklyOverviewChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Weekly Overview Chart
            const ctx = document.getElementById('weeklyOverviewChart').getContext('2d');
            const weeklyData = @json($weeklyData);
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            const data = days.map(day => weeklyData[day] || 0);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Hours Worked',
                        data: data,
                        backgroundColor: '#696cff',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 12
                        }
                    }
                }
            });
        });
    </script>
@endsection
