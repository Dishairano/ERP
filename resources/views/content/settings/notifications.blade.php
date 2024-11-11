@extends('layouts/contentNavbarLayout')

@section('title', 'Notification Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Notification Settings</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.notifications.update') }}" method="POST">
                            @csrf

                            <!-- Notification Channels -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold">Notification Channels</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="email_notifications"
                                                name="email_notifications" value="1"
                                                {{ old('email_notifications', $settings['email_notifications'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="email_notifications">Email
                                                Notifications</label>
                                        </div>
                                        <small class="text-muted d-block">Receive notifications via email</small>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="push_notifications"
                                                name="push_notifications" value="1"
                                                {{ old('push_notifications', $settings['push_notifications'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="push_notifications">Push
                                                Notifications</label>
                                        </div>
                                        <small class="text-muted d-block">Receive browser push notifications</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Frequency -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold">Notification Frequency</h6>
                                <div class="form-group">
                                    <select class="form-control @error('notification_frequency') is-invalid @enderror"
                                        id="notification_frequency" name="notification_frequency" required>
                                        <option value="instant"
                                            {{ old('notification_frequency', $settings['notification_frequency'] ?? '') == 'instant' ? 'selected' : '' }}>
                                            Instant - Send notifications immediately
                                        </option>
                                        <option value="hourly"
                                            {{ old('notification_frequency', $settings['notification_frequency'] ?? '') == 'hourly' ? 'selected' : '' }}>
                                            Hourly - Group notifications and send hourly
                                        </option>
                                        <option value="daily"
                                            {{ old('notification_frequency', $settings['notification_frequency'] ?? '') == 'daily' ? 'selected' : '' }}>
                                            Daily - Send a daily digest
                                        </option>
                                        <option value="weekly"
                                            {{ old('notification_frequency', $settings['notification_frequency'] ?? '') == 'weekly' ? 'selected' : '' }}>
                                            Weekly - Send a weekly summary
                                        </option>
                                    </select>
                                    @error('notification_frequency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Notification Types -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold">Notification Types</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="notification_types_tasks" name="notification_types[tasks]"
                                                value="1"
                                                {{ old('notification_types.tasks', $settings['notification_types']['tasks'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="notification_types_tasks">Task
                                                Updates</label>
                                        </div>

                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="notification_types_projects" name="notification_types[projects]"
                                                value="1"
                                                {{ old('notification_types.projects', $settings['notification_types']['projects'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="notification_types_projects">Project
                                                Updates</label>
                                        </div>

                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="notification_types_risks" name="notification_types[risks]"
                                                value="1"
                                                {{ old('notification_types.risks', $settings['notification_types']['risks'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="notification_types_risks">Risk
                                                Alerts</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="notification_types_team"
                                                name="notification_types[team]" value="1"
                                                {{ old('notification_types.team', $settings['notification_types']['team'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="notification_types_team">Team
                                                Updates</label>
                                        </div>

                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="notification_types_system" name="notification_types[system]"
                                                value="1"
                                                {{ old('notification_types.system', $settings['notification_types']['system'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="notification_types_system">System
                                                Notifications</label>
                                        </div>

                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="notification_types_security" name="notification_types[security]"
                                                value="1"
                                                {{ old('notification_types.security', $settings['notification_types']['security'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="notification_types_security">Security
                                                Alerts</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save Notification Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle push notification permission
            const pushNotificationSwitch = document.getElementById('push_notifications');
            pushNotificationSwitch.addEventListener('change', function(e) {
                if (this.checked && Notification.permission !== 'granted') {
                    Notification.requestPermission().then(function(permission) {
                        if (permission !== 'granted') {
                            e.preventDefault();
                            pushNotificationSwitch.checked = false;
                            alert(
                                'Push notifications permission is required to enable this feature.');
                        }
                    });
                }
            });
        });
    </script>
@endpush
