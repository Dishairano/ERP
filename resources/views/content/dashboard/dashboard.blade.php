@extends('layouts/contentNavbarLayout')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Quick Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card bg-primary text-white h-100 stats-card">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Total Revenue</p>
                            <h3 class="mb-0 fw-bold total-revenue">${{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                        <div class="icon-box">
                            <i class="ri-money-dollar-circle-line ri-xl"></i>
                        </div>
                    </div>
                    <div class="mt-auto pt-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="progress">
                                    <div class="progress-bar" style="width: 75%"></div>
                                </div>
                            </div>
                            <span class="ms-2 small">+12.5%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card bg-success text-white h-100 stats-card">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Active Projects</p>
                            <h3 class="mb-0 fw-bold active-projects">{{ $activeProjects }}</h3>
                        </div>
                        <div class="icon-box">
                            <i class="ri-folder-line ri-xl"></i>
                        </div>
                    </div>
                    <div class="mt-auto pt-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="progress">
                                    <div class="progress-bar" style="width: 65%"></div>
                                </div>
                            </div>
                            <span class="ms-2 small">+8.2%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card bg-info text-white h-100 stats-card">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Team Members</p>
                            <h3 class="mb-0 fw-bold team-members">{{ $teamMembers }}</h3>
                        </div>
                        <div class="icon-box">
                            <i class="ri-team-line ri-xl"></i>
                        </div>
                    </div>
                    <div class="mt-auto pt-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="progress">
                                    <div class="progress-bar" style="width: 82%"></div>
                                </div>
                            </div>
                            <span class="ms-2 small">+5.3%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card bg-warning text-white h-100 stats-card">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Pending Tasks</p>
                            <h3 class="mb-0 fw-bold pending-tasks">{{ $pendingTasks }}</h3>
                        </div>
                        <div class="icon-box">
                            <i class="ri-task-line ri-xl"></i>
                        </div>
                    </div>
                    <div class="mt-auto pt-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="progress">
                                    <div class="progress-bar" style="width: 45%"></div>
                                </div>
                            </div>
                            <span class="ms-2 small">-3.8%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Project Timeline -->
        <div class="col-12 col-xxl-8">
            <div class="card h-100">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h5 class="card-title mb-0">Project Timeline</h5>
                        <small class="text-muted">Track project milestones</small>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-sm active" data-period="week">Week</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-period="month">Month</button>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="refreshMetrics('timeline')">
                                    <i class="ri-refresh-line me-2"></i>Refresh</a>
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportData('timeline')">
                                    <i class="ri-download-line me-2"></i>Export</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="timeline-wrapper" id="project-timeline">
                        @forelse($projectTimeline as $event)
                        <div class="timeline-item">
                            <div class="timeline-indicator bg-{{ $event['type'] }}"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="mb-0">{{ $event['title'] }}</h6>
                                    <span class="badge bg-{{ $event['type'] }}">{{ $event['type'] }}</span>
                                </div>
                                <p class="mb-2 text-muted">{{ $event['description'] }}</p>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <div class="avatar-group">
                                        @foreach($event['team'] as $member)
                                        <div class="avatar avatar-xs" data-bs-toggle="tooltip" title="{{ $member['name'] }} ({{ $member['role'] }})">
                                            @if($member['avatar'])
                                                <img src="{{ $member['avatar'] }}" alt="{{ $member['name'] }}" class="rounded-circle">
                                            @else
                                                <span class="avatar-initial rounded-circle bg-{{ $event['type'] }} bg-opacity-25">
                                                    {{ substr($member['name'], 0, 1) }}
                                                </span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted ms-auto">{{ $event['date'] }}</small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="ri-information-line ri-2x mb-2 d-block"></i>
                            <p class="mb-0">No project updates available</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Stats -->
        <div class="col-12 col-md-6 col-xxl-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Project Stats</h5>
                        <small class="text-muted">Monthly performance</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="dropdown">
                            <i class="ri-filter-line me-1"></i>Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="filterProjects('all')">All Projects</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="filterProjects('active')">Active Only</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="filterProjects('completed')">Completed</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-4" id="project-stats">
                        @forelse($projectStats as $project)
                        <div class="project-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">{{ $project['name'] }}</h6>
                                <span class="badge bg-{{ $project['color'] }}">{{ $project['status'] }}</span>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-{{ $project['color'] }}"
                                     style="width: {{ $project['progress'] }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="avatar-group">
                                    @foreach($project['team'] as $member)
                                    <div class="avatar avatar-xs" data-bs-toggle="tooltip" title="{{ $member['name'] }} ({{ $member['role'] }})">
                                        @if($member['avatar'])
                                            <img src="{{ $member['avatar'] }}" alt="{{ $member['name'] }}" class="rounded-circle">
                                        @else
                                            <span class="avatar-initial rounded-circle bg-{{ $project['color'] }} bg-opacity-25">
                                                {{ substr($member['name'], 0, 1) }}
                                            </span>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-semibold">{{ $project['progress'] }}%</span>
                                    <small class="text-{{ $project['trend'] }}">
                                        <i class="ri-arrow-{{ strpos($project['change'], '+') !== false ? 'up' : 'down' }}-line"></i>
                                        {{ $project['change'] }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="ri-information-line ri-2x mb-2 d-block"></i>
                            <p class="mb-0">No active projects found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Overview -->
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Department Overview</h5>
                        <small class="text-muted">Performance and budget analysis</small>
                    </div>
                    <button class="btn btn-outline-primary btn-sm" onclick="refreshMetrics('departments')">
                        <i class="ri-refresh-line me-1"></i>Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th class="text-end">Budget</th>
                                    <th>Progress</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody id="department-stats">
                                @forelse($departmentStats as $dept)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar avatar-sm bg-{{ $dept['color'] }} bg-opacity-10">
                                                <i class="ri-{{ $dept['icon'] }} text-{{ $dept['color'] }}"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $dept['name'] }}</h6>
                                                <small class="text-muted">{{ $dept['manager'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span>${{ number_format($dept['budget']) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1">
                                                <div class="progress-bar bg-{{ $dept['color'] }}"
                                                     style="width: {{ $dept['progress'] }}%"></div>
                                            </div>
                                            <small>{{ $dept['progress'] }}%</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $dept['status_color'] }}">{{ $dept['status'] }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="ri-information-line ri-2x mb-2 d-block"></i>
                                        <p class="mb-0">No departments found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
// Previous JavaScript content remains exactly the same
</script>
@endsection
