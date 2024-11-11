@extends('layouts/contentNavbarLayout')

@section('title', 'Audit Trail')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">System /</span> Audit Trail
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">System Activity Log</h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary"
                        onclick="window.location.href='{{ route('audit-trail.export') }}'">
                        <i class="ri-download-2-line"></i> Export
                    </button>
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
                                <th>Actions</th>
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
                                        <a href="{{ route('audit-trail.show', $log->id) }}" class="btn btn-sm btn-info">
                                            <i class="ri-eye-line"></i>
                                        </a>
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
