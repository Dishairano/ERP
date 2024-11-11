@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Employee /</span> Dashboard
        </h4>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Employees</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $totalEmployees }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ri-team-line"></i>
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
                                <p class="card-text">Active Employees</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $activeEmployees }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-user-follow-line"></i>
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
                                <p class="card-text">Onboarding</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $onboardingEmployees }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="ri-user-add-line"></i>
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
                                <p class="card-text">Offboarding</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $offboardingEmployees }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-user-unfollow-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <!-- Recent Employees -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">Recent Employees</h5>
                        <a href="{{ route('employees.directory') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        @forelse($recentEmployees ?? [] as $employee)
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $employee->full_name }}</h6>
                                    <small class="text-muted">{{ $employee->position->name }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No recent employees found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Upcoming Onboarding -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">Upcoming Onboarding</h5>
                        <a href="{{ route('employees.onboarding') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        @forelse($upcomingOnboarding ?? [] as $employee)
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $employee->full_name }}</h6>
                                    <small class="text-muted">Starting:
                                        {{ $employee->start_date->format('M d, Y') }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No upcoming onboarding.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Upcoming Offboarding -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">Upcoming Offboarding</h5>
                        <a href="{{ route('employees.offboarding') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        @forelse($upcomingOffboarding ?? [] as $employee)
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $employee->full_name }}</h6>
                                    <small class="text-muted">Ending: {{ $employee->end_date->format('M d, Y') }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No upcoming offboarding.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
