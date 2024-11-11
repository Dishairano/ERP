@extends('layouts/contentNavbarLayout')

@section('title', 'Security Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Security Settings</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.security.update') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5>Password Policy</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_policy_min_length">Minimum Password Length</label>
                                        <input type="number"
                                            class="form-control @error('password_policy.min_length') is-invalid @enderror"
                                            id="password_policy_min_length" name="password_policy[min_length]"
                                            value="{{ old('password_policy.min_length', $settings['password_policy']['min_length'] ?? 8) }}"
                                            min="8" required>
                                        @error('password_policy.min_length')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password Requirements</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                id="password_policy_require_uppercase"
                                                name="password_policy[require_uppercase]" value="1"
                                                {{ old('password_policy.require_uppercase', $settings['password_policy']['require_uppercase'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="password_policy_require_uppercase">
                                                Require Uppercase Letters
                                            </label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                id="password_policy_require_numbers" name="password_policy[require_numbers]"
                                                value="1"
                                                {{ old('password_policy.require_numbers', $settings['password_policy']['require_numbers'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="password_policy_require_numbers">
                                                Require Numbers
                                            </label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                id="password_policy_require_symbols" name="password_policy[require_symbols]"
                                                value="1"
                                                {{ old('password_policy.require_symbols', $settings['password_policy']['require_symbols'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="password_policy_require_symbols">
                                                Require Special Characters
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_expiry_days">Password Expiry (Days)</label>
                                        <input type="number"
                                            class="form-control @error('password_expiry_days') is-invalid @enderror"
                                            id="password_expiry_days" name="password_expiry_days"
                                            value="{{ old('password_expiry_days', $settings['password_expiry_days'] ?? '') }}"
                                            min="0">
                                        <small class="form-text text-muted">Leave empty or 0 for no expiry</small>
                                        @error('password_expiry_days')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="max_login_attempts">Maximum Login Attempts</label>
                                        <input type="number"
                                            class="form-control @error('max_login_attempts') is-invalid @enderror"
                                            id="max_login_attempts" name="max_login_attempts"
                                            value="{{ old('max_login_attempts', $settings['max_login_attempts'] ?? 5) }}"
                                            min="1" required>
                                        @error('max_login_attempts')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lockout_duration">Account Lockout Duration (Minutes)</label>
                                        <input type="number"
                                            class="form-control @error('lockout_duration') is-invalid @enderror"
                                            id="lockout_duration" name="lockout_duration"
                                            value="{{ old('lockout_duration', $settings['lockout_duration'] ?? 30) }}"
                                            min="1" required>
                                        @error('lockout_duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="session_timeout">Session Timeout (Minutes)</label>
                                        <input type="number"
                                            class="form-control @error('session_timeout') is-invalid @enderror"
                                            id="session_timeout" name="session_timeout"
                                            value="{{ old('session_timeout', $settings['session_timeout'] ?? 120) }}"
                                            min="1" required>
                                        @error('session_timeout')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="two_factor_auth"
                                                name="two_factor_auth" value="1"
                                                {{ old('two_factor_auth', $settings['two_factor_auth'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="two_factor_auth">
                                                Enable Two-Factor Authentication
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="ip_whitelist">IP Whitelist</label>
                                        <textarea class="form-control @error('ip_whitelist') is-invalid @enderror" id="ip_whitelist" name="ip_whitelist"
                                            rows="3" placeholder="Enter one IP address per line">{{ old('ip_whitelist', is_array($settings['ip_whitelist'] ?? null) ? implode("\n", $settings['ip_whitelist']) : '') }}</textarea>
                                        <small class="form-text text-muted">Enter one IP address per line. Leave empty to
                                            allow all IPs.</small>
                                        @error('ip_whitelist')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save Security Settings</button>
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
            // Convert textarea content to array for submission
            document.querySelector('form').addEventListener('submit', function() {
                const ipWhitelist = document.getElementById('ip_whitelist');
                const ips = ipWhitelist.value.split('\n').filter(ip => ip.trim());
                ipWhitelist.value = JSON.stringify(ips);
            });
        });
    </script>
@endpush
