@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Notification Settings</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Notification Preferences</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('settings.notifications.update') }}">
                            @csrf

                            <div class="mb-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_notifications"
                                        name="email_notifications" {{ $settings['email_notifications'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_notifications">
                                        Email Notifications
                                    </label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="push_notifications"
                                        name="push_notifications" {{ $settings['push_notifications'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="push_notifications">
                                        Push Notifications
                                    </label>
                                </div>
                            </div>

                            <h6 class="mb-3">Notification Types</h6>

                            <div class="mb-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="project_updates"
                                        name="notification_types[project_updates]"
                                        {{ $settings['notification_types']['project_updates'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="project_updates">
                                        Project Updates
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="task_assignments"
                                        name="notification_types[task_assignments]"
                                        {{ $settings['notification_types']['task_assignments'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="task_assignments">
                                        Task Assignments
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="due_dates"
                                        name="notification_types[due_dates]"
                                        {{ $settings['notification_types']['due_dates'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="due_dates">
                                        Due Dates
                                    </label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="system_updates"
                                        name="notification_types[system_updates]"
                                        {{ $settings['notification_types']['system_updates'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="system_updates">
                                        System Updates
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
