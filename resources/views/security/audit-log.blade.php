@extends('layouts/contentNavbarLayout')

@section('title', 'Security Audit Log')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Security /</span> Audit Log
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Security Audit Trail</h5>
                <div class="card-tools">
                    <a href="{{ route('audit-trail.export') }}" class="btn btn-primary">
                        <i class="ri-download-2-line"></i> Export
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Details</th>
                                <th>IP Address</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($auditLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at }}</td>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->details }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>
                                        @if ($log->status === 'success')
                                            <span class="badge bg-success">Success</span>
                                        @elseif($log->status === 'warning')
                                            <span class="badge bg-warning">Warning</span>
                                        @elseif($log->status === 'error')
                                            <span class="badge bg-danger">Error</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $log->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $auditLogs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
