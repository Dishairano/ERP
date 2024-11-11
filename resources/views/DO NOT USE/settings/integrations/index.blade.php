@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Integration Settings</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Available Integrations</h5>
                    <div class="card-body">
                        @foreach ($integrations['available'] as $key => $integration)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">{{ $integration['name'] }}</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="{{ $key }}_status"
                                            {{ $integration['status'] ? 'checked' : '' }}
                                            onchange="toggleIntegration('{{ $key }}')">
                                    </div>
                                </div>

                                <div id="{{ $key }}_config" class="{{ $integration['status'] ? '' : 'd-none' }}">
                                    <form method="POST" action="{{ route('settings.integrations.store') }}" class="mt-3">
                                        @csrf
                                        <input type="hidden" name="integration" value="{{ $key }}">

                                        @if ($key === 'slack')
                                            <div class="mb-3">
                                                <label class="form-label">Webhook URL</label>
                                                <input type="url" class="form-control" name="config[webhook_url]"
                                                    value="{{ $integration['config']['webhook_url'] ?? '' }}">
                                            </div>
                                        @elseif($key === 'github')
                                            <div class="mb-3">
                                                <label class="form-label">Access Token</label>
                                                <input type="text" class="form-control" name="config[access_token]"
                                                    value="{{ $integration['config']['access_token'] ?? '' }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Repository</label>
                                                <input type="text" class="form-control" name="config[repository]"
                                                    value="{{ $integration['config']['repository'] ?? '' }}">
                                            </div>
                                        @elseif($key === 'jira')
                                            <div class="mb-3">
                                                <label class="form-label">Domain</label>
                                                <input type="url" class="form-control" name="config[domain]"
                                                    value="{{ $integration['config']['domain'] ?? '' }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">API Token</label>
                                                <input type="text" class="form-control" name="config[api_token]"
                                                    value="{{ $integration['config']['api_token'] ?? '' }}">
                                            </div>
                                        @endif

                                        <button type="submit" class="btn btn-primary">Save Configuration</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleIntegration(key) {
                const configDiv = document.getElementById(`${key}_config`);
                if (configDiv) {
                    configDiv.classList.toggle('d-none');
                }
            }
        </script>
    @endpush
@endsection
