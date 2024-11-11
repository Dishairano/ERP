@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Data Integration')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Integration: {{ $integration->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('data-integration.update', $integration) }}" method="POST" id="integration-form">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Integration Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $integration->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="source_type">Source Type</label>
                                    <select class="form-control" id="source_type" name="source_type" required>
                                        <option value="crm" {{ $integration->source_type === 'crm' ? 'selected' : '' }}>
                                            CRM</option>
                                        <option value="accounting"
                                            {{ $integration->source_type === 'accounting' ? 'selected' : '' }}>Accounting
                                            Software</option>
                                        <option value="external_db"
                                            {{ $integration->source_type === 'external_db' ? 'selected' : '' }}>External
                                            Database</option>
                                        <option value="api" {{ $integration->source_type === 'api' ? 'selected' : '' }}>
                                            External API</option>
                                        <option value="file" {{ $integration->source_type === 'file' ? 'selected' : '' }}>
                                            File Import</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="connection_type">Connection Type</label>
                                    <select class="form-control" id="connection_type" name="connection_type" required>
                                        <option value="api"
                                            {{ $integration->connection_type === 'api' ? 'selected' : '' }}>API</option>
                                        <option value="database"
                                            {{ $integration->connection_type === 'database' ? 'selected' : '' }}>Database
                                        </option>
                                        <option value="file_import"
                                            {{ $integration->connection_type === 'file_import' ? 'selected' : '' }}>File
                                            Import</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sync_interval">Sync Interval (minutes)</label>
                                    <input type="number" class="form-control" id="sync_interval" name="sync_interval"
                                        value="{{ $integration->sync_interval }}">
                                </div>
                            </div>
                        </div>

                        <!-- API Configuration Section -->
                        <div id="api-config" class="connection-config"
                            style="{{ $integration->connection_type === 'api' ? '' : 'display: none;' }}">
                            <h5 class="mt-2">API Configuration</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="api_key">API Key</label>
                                        <input type="text" class="form-control" id="api_key"
                                            name="api_configuration[api_key]"
                                            value="{{ optional($integration->apiConfiguration)->api_key }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="api_secret">API Secret</label>
                                        <input type="password" class="form-control" id="api_secret"
                                            name="api_configuration[api_secret]"
                                            placeholder="Leave blank to keep current secret">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="endpoint_url">Endpoint URL</label>
                                        <input type="url" class="form-control" id="endpoint_url"
                                            name="api_configuration[endpoint_url]"
                                            value="{{ optional($integration->apiConfiguration)->endpoint_url }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="auth_type">Authentication Type</label>
                                        <select class="form-control" id="auth_type" name="api_configuration[auth_type]">
                                            <option value="basic"
                                                {{ optional($integration->apiConfiguration)->auth_type === 'basic' ? 'selected' : '' }}>
                                                Basic Auth</option>
                                            <option value="oauth"
                                                {{ optional($integration->apiConfiguration)->auth_type === 'oauth' ? 'selected' : '' }}>
                                                OAuth</option>
                                            <option value="api_key"
                                                {{ optional($integration->apiConfiguration)->auth_type === 'api_key' ? 'selected' : '' }}>
                                                API Key</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Database Configuration Section -->
                        <div id="db-config" class="connection-config"
                            style="{{ $integration->connection_type === 'database' ? '' : 'display: none;' }}">
                            <h5 class="mt-2">Database Configuration</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_driver">Database Driver</label>
                                        <select class="form-control" id="db_driver" name="database_connection[driver]">
                                            <option value="mysql"
                                                {{ optional($integration->databaseConnection)->driver === 'mysql' ? 'selected' : '' }}>
                                                MySQL</option>
                                            <option value="postgresql"
                                                {{ optional($integration->databaseConnection)->driver === 'postgresql' ? 'selected' : '' }}>
                                                PostgreSQL</option>
                                            <option value="sqlserver"
                                                {{ optional($integration->databaseConnection)->driver === 'sqlserver' ? 'selected' : '' }}>
                                                SQL Server</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_host">Host</label>
                                        <input type="text" class="form-control" id="db_host"
                                            name="database_connection[host]"
                                            value="{{ optional($integration->databaseConnection)->host }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_name">Database Name</label>
                                        <input type="text" class="form-control" id="db_name"
                                            name="database_connection[database]"
                                            value="{{ optional($integration->databaseConnection)->database }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_port">Port</label>
                                        <input type="number" class="form-control" id="db_port"
                                            name="database_connection[port]"
                                            value="{{ optional($integration->databaseConnection)->port }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_username">Username</label>
                                        <input type="text" class="form-control" id="db_username"
                                            name="database_connection[username]"
                                            value="{{ optional($integration->databaseConnection)->username }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_password">Password</label>
                                        <input type="password" class="form-control" id="db_password"
                                            name="database_connection[password]"
                                            placeholder="Leave blank to keep current password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Configuration -->
                        <div class="mt-2">
                            <h5>Schedule Configuration</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="frequency">Sync Frequency</label>
                                        <select class="form-control" id="frequency" name="schedule[frequency]">
                                            <option value="hourly"
                                                {{ optional($integration->schedule)->frequency === 'hourly' ? 'selected' : '' }}>
                                                Hourly</option>
                                            <option value="daily"
                                                {{ optional($integration->schedule)->frequency === 'daily' ? 'selected' : '' }}>
                                                Daily</option>
                                            <option value="custom"
                                                {{ optional($integration->schedule)->frequency === 'custom' ? 'selected' : '' }}>
                                                Custom</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="preferred_time">Preferred Time</label>
                                        <input type="time" class="form-control" id="preferred_time"
                                            name="schedule[preferred_time]"
                                            value="{{ optional($integration->schedule)->preferred_time ? $integration->schedule->preferred_time->format('H:i') : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Update Integration</button>
                            <a href="{{ route('data-integration.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            // Handle connection type change
            $('#connection_type').change(function() {
                const type = $(this).val();
                $('.connection-config').hide();

                if (type === 'api') {
                    $('#api-config').show();
                } else if (type === 'database') {
                    $('#db-config').show();
                }
            });

            // Handle frequency change
            $('#frequency').change(function() {
                const frequency = $(this).val();
                if (frequency === 'daily') {
                    $('#preferred_time').prop('disabled', false);
                } else if (frequency === 'hourly') {
                    $('#preferred_time').prop('disabled', true);
                }
            });

            // Form validation
            $('#integration-form').submit(function(e) {
                const connectionType = $('#connection_type').val();

                if (connectionType === 'api') {
                    if (!$('#endpoint_url').val()) {
                        e.preventDefault();
                        toastr.error('Endpoint URL is required for API connections');
                    }
                } else if (connectionType === 'database') {
                    if (!$('#db_host').val() || !$('#db_name').val() || !$('#db_username').val()) {
                        e.preventDefault();
                        toastr.error('All database connection fields are required');
                    }
                }
            });
        });
    </script>
@endsection
