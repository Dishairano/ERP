@extends('layouts/contentNavbarLayout')

@section('title', 'Integration Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Integration Settings</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.integrations.update') }}" method="POST">
                            @csrf

                            <!-- Analytics Integration -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold">Google Analytics</h6>
                                <div class="form-group">
                                    <label for="google_analytics_id">Tracking ID</label>
                                    <input type="text"
                                        class="form-control @error('google_analytics_id') is-invalid @enderror"
                                        id="google_analytics_id" name="google_analytics_id"
                                        value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}"
                                        placeholder="UA-XXXXXXXXX-X">
                                    <small class="text-muted">Enter your Google Analytics tracking ID to enable website
                                        analytics</small>
                                    @error('google_analytics_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email Configuration -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold">SMTP Configuration</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_host">SMTP Host</label>
                                            <input type="text"
                                                class="form-control @error('smtp_host') is-invalid @enderror" id="smtp_host"
                                                name="smtp_host"
                                                value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}"
                                                placeholder="smtp.example.com">
                                            @error('smtp_host')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_port">SMTP Port</label>
                                            <input type="number"
                                                class="form-control @error('smtp_port') is-invalid @enderror" id="smtp_port"
                                                name="smtp_port"
                                                value="{{ old('smtp_port', $settings['smtp_port'] ?? '') }}"
                                                placeholder="587">
                                            @error('smtp_port')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_username">SMTP Username</label>
                                            <input type="text"
                                                class="form-control @error('smtp_username') is-invalid @enderror"
                                                id="smtp_username" name="smtp_username"
                                                value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}">
                                            @error('smtp_username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_password">SMTP Password</label>
                                            <input type="password"
                                                class="form-control @error('smtp_password') is-invalid @enderror"
                                                id="smtp_password" name="smtp_password"
                                                value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}">
                                            @error('smtp_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- AWS Integration -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold">AWS Configuration</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="aws_access_key">AWS Access Key</label>
                                            <input type="text"
                                                class="form-control @error('aws_access_key') is-invalid @enderror"
                                                id="aws_access_key" name="aws_access_key"
                                                value="{{ old('aws_access_key', $settings['aws_access_key'] ?? '') }}">
                                            @error('aws_access_key')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="aws_secret_key">AWS Secret Key</label>
                                            <input type="password"
                                                class="form-control @error('aws_secret_key') is-invalid @enderror"
                                                id="aws_secret_key" name="aws_secret_key"
                                                value="{{ old('aws_secret_key', $settings['aws_secret_key'] ?? '') }}">
                                            @error('aws_secret_key')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="aws_region">AWS Region</label>
                                            <select class="form-control @error('aws_region') is-invalid @enderror"
                                                id="aws_region" name="aws_region">
                                                <option value="">Select Region</option>
                                                <option value="us-east-1"
                                                    {{ old('aws_region', $settings['aws_region'] ?? '') == 'us-east-1' ? 'selected' : '' }}>
                                                    US East (N. Virginia)</option>
                                                <option value="us-east-2"
                                                    {{ old('aws_region', $settings['aws_region'] ?? '') == 'us-east-2' ? 'selected' : '' }}>
                                                    US East (Ohio)</option>
                                                <option value="us-west-1"
                                                    {{ old('aws_region', $settings['aws_region'] ?? '') == 'us-west-1' ? 'selected' : '' }}>
                                                    US West (N. California)</option>
                                                <option value="us-west-2"
                                                    {{ old('aws_region', $settings['aws_region'] ?? '') == 'us-west-2' ? 'selected' : '' }}>
                                                    US West (Oregon)</option>
                                                <option value="eu-west-1"
                                                    {{ old('aws_region', $settings['aws_region'] ?? '') == 'eu-west-1' ? 'selected' : '' }}>
                                                    EU (Ireland)</option>
                                                <option value="eu-central-1"
                                                    {{ old('aws_region', $settings['aws_region'] ?? '') == 'eu-central-1' ? 'selected' : '' }}>
                                                    EU (Frankfurt)</option>
                                            </select>
                                            @error('aws_region')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="aws_bucket">S3 Bucket Name</label>
                                            <input type="text"
                                                class="form-control @error('aws_bucket') is-invalid @enderror"
                                                id="aws_bucket" name="aws_bucket"
                                                value="{{ old('aws_bucket', $settings['aws_bucket'] ?? '') }}">
                                            @error('aws_bucket')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-info" id="test-connections">
                                    <i class="fas fa-sync"></i> Test Connections
                                </button>
                                <button type="submit" class="btn btn-primary">Save Integration Settings</button>
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
            // Test connections button handler
            document.getElementById('test-connections').addEventListener('click', async function() {
                const button = this;
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';

                try {
                    const response = await fetch('/settings/integrations/test', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: JSON.stringify({
                            smtp: {
                                host: document.getElementById('smtp_host').value,
                                port: document.getElementById('smtp_port').value,
                                username: document.getElementById('smtp_username')
                                    .value,
                                password: document.getElementById('smtp_password').value
                            },
                            aws: {
                                access_key: document.getElementById('aws_access_key')
                                    .value,
                                secret_key: document.getElementById('aws_secret_key')
                                    .value,
                                region: document.getElementById('aws_region').value,
                                bucket: document.getElementById('aws_bucket').value
                            }
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('All connections tested successfully!');
                    } else {
                        alert('Some connections failed. Please check the settings and try again.');
                    }
                } catch (error) {
                    alert('Error testing connections. Please try again.');
                } finally {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-sync"></i> Test Connections';
                }
            });
        });
    </script>
@endpush
