@extends('layouts/contentNavbarLayout')

@section('title', 'Project Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Project Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Projects</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $projectStats['total'] }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ri-folder-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">On Track</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $projectStats['onTrack'] }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-check-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Delayed</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $projectStats['delayed'] }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-error-warning-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Completed</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $projectStats['completed'] }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="ri-flag-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project List and Activities -->
        <div class="row">
            <!-- Project List -->
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Active Projects</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">View All</a></li>
                                <li><a class="dropdown-item" href="#">Add New Project</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">{{ $project->name }}</h6>
                                                        <small class="text-muted">{{ $project->manager->name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress w-100" style="height: 8px;">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $project->progress }}%"
                                                            aria-valuenow="{{ $project->progress }}" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <span class="ms-2">{{ $project->progress }}%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-label-{{ $project->status === 'On Track' ? 'success' : ($project->status === 'Delayed' ? 'warning' : 'info') }}">
                                                    {{ $project->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('projects.details', $project->id) }}">
                                                                <i class="ri-eye-line me-1"></i> View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#updateStatusModal{{ $project->id }}">
                                                                <i class="ri-edit-line me-1"></i> Update Status
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item
