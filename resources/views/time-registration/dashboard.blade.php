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
        <!-- Overview Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Weekly Hours</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($weeklyTotal, 1) }}</h4>
                                    <small class="text-success">hours</small>
                                </div>
                                <small>Week {{ now()->format('W') }}</small>
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

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Monthly Hours</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($monthlyTotal, 1) }}</h4>
                                    <small class="text-success">hours</small>
                                </div>
                                <small>{{ now()->format('F Y') }}</small>
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

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Daily Target</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">8.0</h4>
                                    <small class="text-muted">hours</small>
                                </div>
                                <small>Standard workday</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-target-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Pending Approvals</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">
                                        {{ $weeklyRegistrations->where('status', 'submitted')->count() }}
                                    </h4>
                                    <small class="text-muted">entries</small>
                                </div>
                                <small>This week</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded p-2">
                                    <i class="ri-hourglass-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Hours Chart -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Weekly Time Distribution</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle"
                                data-bs-toggle="dropdown">
                                This Week
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">This Week</a></li>
                                <li><a class="dropdown-item" href="#">Last Week</a></li>
                                <li><a class="dropdown-item" href="#">Custom Range</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="weeklyHoursChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Registrations & Quick Actions -->
        <div class="row">
            <!-- Recent Time Registrations -->
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Time Registrations</h5>
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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRegistrations as $registration)
                                    <tr>
                                        <td>{{ $registration->date->format('M d, Y') }}</td>
                                        <td>{{ $registration->project->name }}</td>
                                        <td>{{ $registration->task->name }}</td>
                                        <td>{{ number_format($registration->hours, 1) }}</td>
                                        <td>
                                            @php
                                                $statusClass = match ($registration->status) {
                                                    'draft' => 'secondary',
                                                    'submitted' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                    default => 'primary',
                                                };
                                            @endphp
                                            <span class="badge bg-label-{{ $statusClass }}">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No recent time registrations</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('time-registration.create') }}"
                                class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <i class="ri-add-line me-2"></i>
                                    <div>
                                        <h6 class="mb-0">Register Time</h6>
                                        <small class="text-muted">Add new time entry</small>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('time-registration.calendar') }}"
                                class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <i class="ri-calendar-line me-2"></i>
                                    <div>
                                        <h6 class="mb-0">Calendar View</h6>
                                        <small class="text-muted">View time entries by date</small>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('time-registration.approvals') }}"
                                class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <i class="ri-check-line me-2"></i>
                                    <div>
                                        <h6 class="mb-0">Pending Approvals</h6>
                                        <small class="text-muted">Review time entries</small>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <i class="ri-file-chart-line me-2"></i>
                                    <div>
                                        <h6 class="mb-0">Generate Report</h6>
                                        <small class="text-muted">Export time data</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Weekly Hours Chart
            const weeklyData = @json($weeklyRegistrations->groupBy('date')->map->sum('hours'));
            const dates = Object.keys(weeklyData);
            const hours = Object.values(weeklyData);

            new ApexCharts(document.querySelector("#weeklyHoursChart"), {
                chart: {
                    height: 300,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '60%',
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    name: 'Hours',
                    data: hours
                }],
                xaxis: {
                    categories: dates,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    title: {
                        text: 'Hours'
                    }
                },
                colors: ['#696cff'],
                grid: {
                    borderColor: '#f1f1f1',
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    }
                },
                theme: {
                    mode: document.querySelector('html').getAttribute('data-theme') || 'light'
                }
            }).render();
        });
    </script>
@endsection
