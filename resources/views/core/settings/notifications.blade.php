@extends('layouts/contentNavbarLayout')

@section('title', 'Notification Settings')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Notification Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.notifications.update') }}" method="POST">
                            @csrf

                            <!-- Notification Channels -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="mb-3">Notification Channels</h6>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_notifications"
                                            name="email_notifications" value="1"
                                            {{ old('email_notifications', $settings['email_notifications'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifications">
                                            Email Notifications
                                        </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="push_notifications"
                                            name="push_notifications" value="1"
                                            {{ old('push_notifications', $settings['push_notifications'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="push_notifications">
                                            Push Notifications
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Types -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="mb-3">Notification Types</h6>
                                    <div class="row">
                                        <!-- Project Notifications -->
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="notification_types_project" name="notification_types[]"
                                                            value="project"
                                                            {{ in_array('project', old('notification_types', $settings['notification_types'] ?? [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="notification_types_project">
                                                            <h6 class="mb-0">Project Notifications</h6>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="mb-2">• New project assignments</li>
                                                        <li class="mb-2">• Project status updates</li>
                                                        <li class="mb-2">• Project deadline reminders</li>
                                                        <li>• Project completion notifications</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Task Notifications -->
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="notification_types_task" name="notification_types[]"
                                                            value="task"
                                                            {{ in_array('task', old('notification_types', $settings['notification_types'] ?? [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="notification_types_task">
                                                            <h6 class="mb-0">Task Notifications</h6>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="mb-2">• Task assignments</li>
                                                        <li class="mb-2">• Task updates and comments</li>
                                                        <li class="mb-2">• Task due date reminders</li>
                                                        <li>• Task completion notifications</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Risk Notifications -->
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="notification_types_risk" name="notification_types[]"
                                                            value="risk"
                                                            {{ in_array('risk', old('notification_types', $settings['notification_types'] ?? [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="notification_types_risk">
                                                            <h6 class="mb-0">Risk Notifications</h6>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="mb-2">• New risk identifications</li>
                                                        <li class="mb-2">• Risk assessment updates</li>
                                                        <li class="mb-2">• Risk mitigation alerts</li>
                                                        <li>• Risk status changes</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Finance Notifications -->
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="notification_types_finance" name="notification_types[]"
                                                            value="finance"
                                                            {{ in_array('finance', old('notification_types', $settings['notification_types'] ?? [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="notification_types_finance">
                                                            <h6 class="mb-0">Finance Notifications</h6>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="mb-2">• Budget updates</li>
                                                        <li class="mb-2">• Expense approvals</li>
                                                        <li class="mb-2">• Payment reminders</li>
                                                        <li>• Financial report alerts</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- HRM Notifications -->
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="notification_types_hrm" name="notification_types[]"
                                                            value="hrm"
                                                            {{ in_array('hrm', old('notification_types', $settings['notification_types'] ?? [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="notification_types_hrm">
                                                            <h6 class="mb-0">HRM Notifications</h6>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="mb-2">• Leave requests</li>
                                                        <li class="mb-2">• Performance reviews</li>
                                                        <li class="mb-2">• Training schedules</li>
                                                        <li>• HR policy updates</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('notification_types')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                                <button type="reset" class="btn btn-label-secondary">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
