@extends('layouts/contentNavbarLayout')

@section('title', 'Audit Log')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Audit Log</h6>
                        <div>
                            <button type="button" class="btn btn-info btn-sm" id="filterBtn">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('settings.audit-log.export') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-download"></i> Export
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Panel -->
                        <div class="collapse mb-3" id="filterPanel">
                            <div class="card card-body">
                                <form action="{{ route('settings.audit-log') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="user">User</label>
                                                <select class="form-control" id="user" name="user">
                                                    <option value="">All Users</option>
                                                    @foreach (\App\Models\User::all() as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ request('user') == $user->id ? 'selected' : '' }}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="action">Action</label>
                                                <select class="form-control" id="action" name="action">
                                                    <option value="">All Actions</option>
                                                    <option value="created"
                                                        {{ request('action') == 'created' ? 'selected' : '' }}>Created
                                                    </option>
                                                    <option value="updated"
                                                        {{ request('action') == 'updated' ? 'selected' : '' }}>Updated
                                                    </option>
                                                    <option value="deleted"
                                                        {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="start_date">Start Date</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date"
                                                    value="{{ request('start_date') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="end_date">End Date</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date"
                                                    value="{{ request('end_date') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-secondary mr-2">Reset</button>
                                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Audit Log Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Model</th>
                                        <th>Details</th>
                                        <th>IP Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>
                                                @if ($log->user)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-xs mr-2">
                                                            @if ($log->user->avatar)
                                                                <img src="{{ asset('storage/' . $log->user->avatar) }}"
                                                                    alt="{{ $log->user->name }}" class="rounded-circle">
                                                            @else
                                                                <div class="avatar-initial rounded-circle bg-primary">
                                                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        {{ $log->user->name }}
                                                    </div>
                                                @else
                                                    System
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $log->action === 'created'
                                                        ? 'success'
                                                        : ($log->action === 'updated'
                                                            ? 'info'
                                                            : ($log->action === 'deleted'
                                                                ? 'danger'
                                                                : 'secondary')) }}">
                                                    {{ ucfirst($log->action) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ class_basename($log->model_type) }}
                                                @if ($log->model_id)
                                                    #{{ $log->model_id }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($log->changes)
                                                    <button type="button" class="btn btn-sm btn-link" data-toggle="modal"
                                                        data-target="#changesModal"
                                                        data-changes="{{ json_encode($log->changes) }}">
                                                        View Changes
                                                    </button>
                                                @else
                                                    No changes recorded
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $log->ip_address }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Changes Modal -->
    <div class="modal fade" id="changesModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Changes Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Old Value</th>
                                    <th>New Value</th>
                                </tr>
                            </thead>
                            <tbody id="changesTableBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle filter panel toggle
            document.getElementById('filterBtn').addEventListener('click', function() {
                $('#filterPanel').collapse('toggle');
            });

            // Handle changes modal
            $('#changesModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const changes = button.data('changes');
                const tableBody = document.getElementById('changesTableBody');

                // Clear previous content
                tableBody.innerHTML = '';

                // Add rows for each change
                Object.entries(changes).forEach(([field, values]) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                <td>${field}</td>
                <td>${values.old ?? '<em>null</em>'}</td>
                <td>${values.new ?? '<em>null</em>'}</td>
            `;
                    tableBody.appendChild(row);
                });
            });

            // Initialize select2 for better dropdown experience
            if (typeof $.fn.select2 !== 'undefined') {
                $('#user').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
            }

            // Handle date range validation
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');

            startDate.addEventListener('change', function() {
                endDate.min = this.value;
            });

            endDate.addEventListener('change', function() {
                startDate.max = this.value;
            });
        });
    </script>
@endpush
