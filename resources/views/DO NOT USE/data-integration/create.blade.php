@extends('layouts/contentNavbarLayout')

@section('title', 'Create Data Integration')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">New Data Integration</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('data-integration.store') }}" method="POST" id="integration-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Integration Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="source_type">Source Type</label>
                                    <select class="form-control" id="source_type" name="source_type" required>
                                        <option value="crm">CRM</option>
                                        <option value="accounting">Accounting Software</option>
                                        <option value="external_db">External Database</option>
                                        <option value="api">External API</option>
                                        <option value="file">File Import</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="connection_type">Connection Type</label>
                                    <select class="form-control" id="connection_type" name="connection_type" required>
                                        <option value="api">API</option>
                                        <option value="database">Database</option>
                                        <option value="file_import">File Import</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sync_interval">Sync Interval (minutes)</label>
                                    <input type="number" class="form-control" id="sync_interval" name="sync_interval">
                                </div>
                            </div>
                        </div>

                        <!-- API Configuration Section -->
                        <div id="api-config" class="connection-config">
                            <h5 class="mt-2">API Configuration</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="api_key">API Key</label>
                                        <input type="text" class="form-control" id="api_key"
                                            name="api_configuration[api_key]">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="api_secret">API Secret</label>
                                        <input type="password" class="form-control" id="api_secret"
                                            name="api_configuration[api_secret]">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="endpoint_url">Endpoint URL</label>
                                        <input type="url" class="form-control" id="endpoint_url"
                                            name="api_configuration[endpoint_url]">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="auth_type">Authentication Type</label>
                                        <select class="form-control" id="auth_type" name="api_configuration[auth_type]">
                                            <option value="basic">Basic Auth</option>
                                            <option value="oauth">OAuth</option>
                                            <option value="api_key">API Key</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Database Configuration Section -->
                        <div id="db-config" class="connection-config" style="display: none;">
                            <h5 class="mt-2">Database Configuration</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_driver">Database Driver</label>
                                        <select class="form-control" id="db_driver" name="database_connection[driver]">
                                            <option value="mysql">MySQL</option>
                                            <option value="postgresql">PostgreSQL</option>
                                            <option value="sqlserver">SQL Server</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_host">Host</label>
                                        <input type="text" class="form-control" id="db_host"
                                            name="database_connection[host]">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_name">Database Name</label>
                                        <input type="text" class="form-control" id="db_name"
                                            name="database_connection[database]">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_port">Port</label>
                                        <input type="number" class="form-control" id="db_port"
                                            name="database_connection[port]">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_username">Username</label>
                                        <input type="text" class="form-control" id="db_username"
                                            name="database_connection[username]">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="db_password">Password</label>
                                        <input type="password" class="form-control" id="db_password"
                                            name="database_connection[password]">
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
                                            <option value="hourly">Hourly</option>
                                            <option value="daily">Daily</option>
                                            <option value="custom">Custom</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="preferred_time">Preferred Time</label>
                                        <input type="time" class="form-control" id="preferred_time"
                                            name="schedule[preferred_time]">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Create Integration</button>
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
