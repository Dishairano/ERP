@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <!-- Task Statistics -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Total Tasks</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2">{{ $taskStats['total'] }}</h3>
                                    <span class="badge bg-label-primary">Active</span>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ri-task-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Completion Rate</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2">{{ $taskStats['completion_rate'] }}%</h3>
                                    <span class="badge bg-label-success">Completed</span>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ri-check-double-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Overdue Tasks</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2">{{ $taskStats['overdue'] }}</h3>
                                    <span class="badge bg-label-danger">Delayed</span>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="ri-error-warning-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Avg. Completion Time</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2">{{ round($taskStats['average_completion_time']) }}</h3>
                                    <span class="badge bg-label-info">Days</span>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ri-time-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="card mb-4">
            <div class="card-body
