@extends('layouts/contentNavbarLayout')

@section('title', 'API Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">API Settings</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.api.update') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="api_enabled"
                                                name="api_enabled" value="1"
                                                {{ old('api_enabled', $settings['api_enabled'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="api_enabled">
                                                Enable API Access
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>API Key</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="api_key"
                                                value="{{ $settings['api_key'] ?? '' }}" readonly>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-secondary" onclick="copyApiKey()">
                                                    <i class="ri-file-copy-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <form action="{{ route('settings.api.generate-key') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            Generate New API Key
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5>Rate Limiting</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="rate_limiting_enabled"
                                                name="rate_limiting[enabled]" value="1"
                                                {{ old('rate_limiting.enabled', $settings['rate_limiting']['enabled'] ?? '') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="rate_limiting_enabled">
                                                Enable Rate Limiting
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 rate-limit-settings">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rate_limiting_attempts">Maximum Attempts</label>
                                        <input type="number"
                                            class="form-control @error('rate_limiting.attempts') is-invalid @enderror"
                                            id="rate_limiting_attempts" name="rate_limiting[attempts]"
                                            value="{{ old('rate_limiting.attempts', $settings['rate_limiting']['attempts'] ?? 60) }}"
                                            min="1">
                                        @error('rate_limiting.attempts')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rate_limiting_decay_minutes">Time Window (Minutes)</label>
                                        <input type="number"
                                            class="form-control @error('rate_limiting.decay_minutes') is-invalid @enderror"
                                            id="rate_limiting_decay_minutes" name="rate_limiting[decay_minutes]"
                                            value="{{ old('rate_limiting.decay_minutes', $settings['rate_limiting']['decay_minutes'] ?? 1) }}"
                                            min="1">
                                        @error('rate_limiting.decay_minutes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="allowed_origins">Allowed Origins (CORS)</label>
                                        <textarea class="form-control @error('allowed_origins') is-invalid @enderror" id="allowed_origins"
                                            name="allowed_origins" rows="3" placeholder="Enter one URL per line">{{ old('allowed_origins', is_array($settings['allowed_origins'] ?? null) ? implode("\n", $settings['allowed_origins']) : '') }}</textarea>
                                        <small class="form-text text-muted">Enter one URL per line. Leave empty to allow all
                                            origins.</small>
                                        @error('allowed_origins')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="webhook_url">Webhook URL</label>
                                        <input type="url"
                                            class="form-control @error('webhook_url') is-invalid @enderror" id="webhook_url"
                                            name="webhook_url"
                                            value="{{ old('webhook_url', $settings['webhook_url'] ?? '') }}">
                                        @error('webhook_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Webhook Events</label>
                                    <div class="row">
                                        @php
                                            $webhookEvents = [
                                                'user.created' => 'User Created',
                                                'user.updated' => 'User Updated',
                                                'user.deleted' => 'User Deleted',
                                                'order.created' => 'Order Created',
                                                'order.updated' => 'Order Updated',
                                                'order.deleted' => 'Order Deleted',
                                                'payment.received' => 'Payment Received',
                                                'payment.failed' => 'Payment Failed',
                                            ];
                                        @endphp

                                        @foreach ($webhookEvents as $value => $label)
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="webhook_event_{{ $value }}" name="webhook_events[]"
                                                        value="{{ $value }}"
                                                        {{ in_array($value, old('webhook_events', $settings['webhook_events'] ?? [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="webhook_event_{{ $value }}">
                                                        {{ $label }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save API Settings</button>
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
            const rateLimitingEnabled = document.getElementById('rate_limiting_enabled');
            const rateLimitSettings = document.querySelector('.rate-limit-settings');

            function toggleRateLimitSettings() {
                rateLimitSettings.style.display = rateLimitingEnabled.checked ? 'flex' : 'none';
            }

            rateLimitingEnabled.addEventListener('change', toggleRateLimitSettings);
            toggleRateLimitSettings();

            // Convert textarea content to array for submission
            document.querySelector('form').addEventListener('submit', function() {
                const allowedOrigins = document.getElementById('allowed_origins');
                const origins = allowedOrigins.value.split('\n').filter(origin => origin.trim());
                allowedOrigins.value = JSON.stringify(origins);
            });
        });

        function copyApiKey() {
            const apiKey = document.getElementById('api_key');
            apiKey.select();
            document.execCommand('copy');
            alert('API Key copied to clipboard!');
        }
    </script>
@endpush
