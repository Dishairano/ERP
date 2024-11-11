@extends('layouts/contentNavbarLayout')

@section('title', 'Audit Trail Settings')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">System / Audit Trail /</span> Settings
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Configure Audit Trail Settings</h5>
            </div>

            <form action="{{ route('audit-trail.settings.update') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="retention_period">Retention Period (days)</label>
                                <input type="number" class="form-control" id="retention_period" name="retention_period"
                                    value="{{ old('retention_period', $settings->retention_period) }}" required>
                                <small class="text-muted">Number of days to keep audit logs before automatic
                                    deletion</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="log_level">Log Level</label>
                                <select class="form-select" id="log_level" name="log_level" required>
                                    <option value="basic" {{ $settings->log_level == 'basic' ? 'selected' : '' }}>Basic
                                    </option>
                                    <option value="detailed" {{ $settings->log_level == 'detailed' ? 'selected' : '' }}>
                                        Detailed</option>
                                    <option value="debug" {{ $settings->log_level == 'debug' ? 'selected' : '' }}>Debug
                                    </option>
                                </select>
                                <small class="text-muted">Level of detail for audit logging</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Logged Actions</label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="log_login" name="log_actions[]"
                                        value="login"
                                        {{ in_array('login', $settings->log_actions ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="log_login">User Login/Logout</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="log_crud" name="log_actions[]"
                                        value="crud"
                                        {{ in_array('crud', $settings->log_actions ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="log_crud">CRUD Operations</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="log_security" name="log_actions[]"
                                        value="security"
                                        {{ in_array('security', $settings->log_actions ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="log_security">Security Events</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
@endsection
