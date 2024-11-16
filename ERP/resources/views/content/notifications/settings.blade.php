@extends('layouts/contentNavbarLayout')

@section('title', 'Notification Settings')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Account Settings /</span> Notifications
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Push Notifications</h5>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible mb-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible mb-3" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="notificationSettingsForm" method="POST"
                            action="{{ route('notifications.update-preferences') }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">General Notifications</h6>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            name="notification_preferences[system_updates]" id="systemUpdates"
                                            {{ $preferences['system_updates'] ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="systemUpdates">System Updates</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            name="notification_preferences[security_alerts]" id="securityAlerts"
                                            {{ $preferences['security_alerts'] ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="securityAlerts">Security Alerts</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="mb-3">Project Notifications</h6>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            name="notification_preferences[project_updates]" id="projectUpdates"
                                            {{ $preferences['project_updates'] ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="projectUpdates">Project Updates</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            name="notification_preferences[task_assignments]" id="taskAssignments"
                                            {{ $preferences['task_assignments'] ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="taskAssignments">Task Assignments</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6 class="mb-3">HR Notifications</h6>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            name="notification_preferences[leave_requests]" id="leaveRequests"
                                            {{ $preferences['leave_requests'] ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="leaveRequests">Leave Requests</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            name="notification_preferences[payroll_updates]" id="payrollUpdates"
                                            {{ $preferences['payroll_updates'] ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="payrollUpdates">Payroll Updates</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="mb-3">Finance Notifications</h6>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            name="notification_preferences[expense_approvals]" id="expenseApprovals"
                                            {{ $preferences['expense_approvals'] ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="expenseApprovals">Expense Approvals</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            name="notification_preferences[budget_alerts]" id="budgetAlerts"
                                            {{ $preferences['budget_alerts'] ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="budgetAlerts">Budget Alerts</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <h5 class="card-header">Registered Devices</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Platform</th>
                                    <th>Last Active</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($devices as $device)
                                    <tr>
                                        <td>
                                            <i
                                                class="ri-{{ $device->platform === 'ios' ? 'apple-fill' : 'android-fill' }} me-2"></i>
                                            {{ ucfirst($device->platform) }}
                                        </td>
                                        <td>{{ $device->updated_at->diffForHumans() }}</td>
                                        <td>
                                            <span class="badge bg-{{ $device->is_active ? 'success' : 'danger' }}">
                                                {{ $device->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('notifications.deactivate-device') }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <input type="hidden" name="device_token"
                                                    value="{{ $device->device_token }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to deactivate this device?')">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">
                                            <i class="ri-information-line ri-xl mb-2 d-block"></i>
                                            <p class="mb-0">No devices registered</p>
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
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle form reset
            document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to reset all notification preferences?')) {
                    document.getElementById('notificationSettingsForm').reset();
                }
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
