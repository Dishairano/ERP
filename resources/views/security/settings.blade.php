@extends('layouts/contentNavbarLayout')

@section('title', 'Security Settings')

@section('content')
    <h4 class="fw-bold">Security Settings</h4>

    <div class="row">
        <!-- Password Settings -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Password Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('security.settings.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Password Expiry</label>
                            <div class="input-group">
                                <input type="number" name="password_expiry_days" class="form-control"
                                    value="{{ $securitySettings->password_expiry_days ?? 90 }}" min="30"
                                    max="365">
                                <span class="input-group-text">days</span>
                            </div>
                            <small class="text-muted">Users will be prompted to change their password after this
                                period</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Maximum Login Attempts</label>
                            <input type="number" name="max_login_attempts" class="form-control"
                                value="{{ $securitySettings->max_login_attempts ?? 5 }}" min="3" max="10">
                            <small class="text-muted">Account will be locked after this many failed attempts</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Session Timeout</label>
                            <div class="input-group">
                                <input type="number" name="session_timeout_minutes" class="form-control"
                                    value="{{ $securitySettings->session_timeout_minutes ?? 30 }}" min="15"
                                    max="240">
                                <span class="input-group-text">minutes</span>
                            </div>
                            <small class="text-muted">Users will be logged out after this period of inactivity</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password History</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="require_password_history"
                                    id="requirePasswordHistory"
                                    {{ $securitySettings->require_password_history ? 'checked' : '' }}>
                                <label class="form-check-label" for="requirePasswordHistory">
                                    Prevent reuse of previous passwords
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Complexity</label>
                            <select name="password_complexity_level" class="form-select">
                                <option value="low"
                                    {{ ($securitySettings->password_complexity_level ?? '') === 'low' ? 'selected' : '' }}>
                                    Low (minimum 8 characters)
                                </option>
                                <option value="medium"
                                    {{ ($securitySettings->password_complexity_level ?? '') === 'medium' ? 'selected' : '' }}>
                                    Medium (minimum 10 characters, mixed case)
                                </option>
                                <option value="high"
                                    {{ ($securitySettings->password_complexity_level ?? 'high') === 'high' ? 'selected' : '' }}>
                                    High (minimum 12 characters, mixed case, numbers, symbols)
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Password Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Two-Factor Authentication</h5>
                </div>
                <div class="card-body">
                    @if ($securitySettings->two_factor_enabled)
                        <div class="alert alert-success">
                            <div class="d-flex">
                                <i class="ri-shield-check-line fs-4 me-2"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Two-factor authentication is enabled</h6>
                                    <span>Your account has an extra layer of security.</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Registered Phone Number</label>
                            <input type="text" class="form-control" value="{{ $securitySettings->phone_number }}"
                                readonly>
                        </div>

                        <form action="{{ route('security.2fa.disable') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to disable two-factor authentication?')">
                                Disable Two-Factor Authentication
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <div class="d-flex">
                                <i class="ri-shield-keyhole-line fs-4 me-2"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Two-factor authentication is not enabled</h6>
                                    <span>Enable 2FA to add an extra layer of security to your account.</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('security.2fa.enable') }}" class="btn btn-primary">
                            Enable Two-Factor Authentication
                        </a>
                    @endif
                </div>
            </div>

            <!-- Active Sessions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Active Sessions</h5>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Device</th>
                                <th>IP Address</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeSessions ?? [] as $session)
                                <tr>
                                    <td>
                                        <i
                                            class="ri-{{ $session->device_type === 'mobile' ? 'smartphone-line' : 'computer-line' }} me-2"></i>
                                        {{ $session->device }}
                                    </td>
                                    <td>{{ $session->ip_address }}</td>
                                    <td>{{ $session->last_activity->diffForHumans() }}</td>
                                    <td>
                                        @if ($session->is_current)
                                            <span class="badge bg-success">Current Session</span>
                                        @else
                                            <form action="{{ route('security.sessions.revoke', $session->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Revoke</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No active sessions</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
