@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Overview')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="py-3 mb-0">
                <span class="text-muted fw-light">Time Registration /</span> Overview
            </h4>
            <a href="{{ route('time-registration.create') }}" class="btn btn-primary">
                <i class="ri-add-line"></i> Register Time
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('time-registration.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Date Range</label>
                        <select class="form-select" name="date_range">
                            <option value="today">Today</option>
                            <option value="this_week">This Week</option>
                            <option value="last_week">Last Week</option>
                            <option value="this_month">This Month</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Project</label>
                        <select class="form-select" name="project_id">
                            <option value="">All Projects</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="type">
                            <option value="">All Types</option>
                            <option value="regular">Regular Time</option>
                            <option value="overtime">Overtime</option>
                            <option value="leave">Leave</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('time-registration.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Time Registrations Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Task</th>
                            <th>Description</th>
                            <th>Hours</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center">No time registrations found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Time Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="timeDistributionChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Project Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="projectBreakdownChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Time Registration Modal -->
    <div class="modal fade" id="editTimeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Time Registration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTimeForm">
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Project</label>
                            <select class="form-select" name="project_id" required>
                                <option value="">Select Project</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Task</label>
                            <select class="form-select" name="task_id" required>
                                <option value="">Select Task</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hours</label>
                            <input type="number" class="form-control" name="hours" step="0.5" min="0"
                                max="24" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveTimeBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Time Distribution Chart
            const timeCtx = document.getElementById('timeDistributionChart').getContext('2d');
            new Chart(timeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Regular Time', 'Overtime', 'Leave'],
                    datasets: [{
                        data: [0, 0, 0],
                        backgroundColor: ['#696cff', '#ff3e1d', '#71dd37']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Project Breakdown Chart
            const projectCtx = document.getElementById('projectBreakdownChart').getContext('2d');
            new Chart(projectCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Hours',
                        data: [],
                        backgroundColor: '#696cff',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Initialize edit modal
            const editTimeModal = new bootstrap.Modal(document.getElementById('editTimeModal'));

            // Add event listeners for edit buttons
            document.querySelectorAll('[data-action="edit-time"]').forEach(button => {
                button.addEventListener('click', function() {
                    const timeId = this.dataset.timeId;
                    // Load time registration details via AJAX and show modal
                    editTimeModal.show();
                });
            });
        });
    </script>
@endsection
