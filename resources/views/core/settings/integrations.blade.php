@extends('layouts/contentNavbarLayout')

@section('title', 'Integration Settings')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Integration Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.integrations.update') }}" method="POST">
                            @csrf

                            <div class="row" id="integrationsList">
                                @php
                                    $integrations = old(
                                        'integrations',
                                        $settings['integrations'] ?? [
                                            [
                                                'name' => 'slack',
                                                'api_key' => '',
                                                'api_secret' => '',
                                                'status' => false,
                                            ],
                                            [
                                                'name' => 'github',
                                                'api_key' => '',
                                                'api_secret' => '',
                                                'status' => false,
                                            ],
                                            [
                                                'name' => 'jira',
                                                'api_key' => '',
                                                'api_secret' => '',
                                                'status' => false,
                                            ],
                                        ],
                                    );
                                @endphp

                                @foreach ($integrations as $index => $integration)
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('assets/img/icons/' . $integration['name'] . '.png') }}"
                                                        alt="{{ ucfirst($integration['name']) }}" class="me-2"
                                                        height="24">
                                                    <h6 class="mb-0">{{ ucfirst($integration['name']) }} Integration</h6>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="integration_status_{{ $index }}"
                                                        name="integrations[{{ $index }}][status]" value="1"
                                                        {{ $integration['status'] ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" name="integrations[{{ $index }}][name]"
                                                    value="{{ $integration['name'] }}">

                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="integration_api_key_{{ $index }}">API Key</label>
                                                    <input type="text"
                                                        class="form-control @error('integrations.' . $index . '.api_key') is-invalid @enderror"
                                                        id="integration_api_key_{{ $index }}"
                                                        name="integrations[{{ $index }}][api_key]"
                                                        value="{{ $integration['api_key'] }}">
                                                    @error('integrations.' . $index . '.api_key')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="integration_api_secret_{{ $index }}">API Secret</label>
                                                    <input type="password"
                                                        class="form-control @error('integrations.' . $index . '.api_secret') is-invalid @enderror"
                                                        id="integration_api_secret_{{ $index }}"
                                                        name="integrations[{{ $index }}][api_secret]"
                                                        value="{{ $integration['api_secret'] }}">
                                                    @error('integrations.' . $index . '.api_secret')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="integration-features">
                                                    @if ($integration['name'] === 'slack')
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="mb-1">• Project notifications</li>
                                                            <li class="mb-1">• Task assignments</li>
                                                            <li>• Team collaboration</li>
                                                        </ul>
                                                    @elseif($integration['name'] === 'github')
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="mb-1">• Code repository sync</li>
                                                            <li class="mb-1">• Pull request tracking</li>
                                                            <li>• Issue management</li>
                                                        </ul>
                                                    @elseif($integration['name'] === 'jira')
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="mb-1">• Issue tracking</li>
                                                            <li class="mb-1">• Sprint management</li>
                                                            <li>• Project synchronization</li>
                                                        </ul>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    onclick="testIntegration('{{ $integration['name'] }}', {{ $index }})">
                                                    <i class="ri-link-m me-1"></i> Test Connection
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

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

@section('page-script')
    <script>
        function testIntegration(name, index) {
            const apiKey = document.getElementById(`integration_api_key_${index}`).value;
            const apiSecret = document.getElementById(`integration_api_secret_${index}`).value;

            if (!apiKey || !apiSecret) {
                alert('Please enter both API Key and API Secret before testing the connection.');
                return;
            }

            // Show testing indicator
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i> Testing...';
            button.disabled = true;

            // Simulate API test (replace with actual API test)
            setTimeout(() => {
                alert(`Successfully connected to ${name.charAt(0).toUpperCase() + name.slice(1)}!`);
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Add any additional initialization code here
        });
    </script>
@endsection
