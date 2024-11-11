@extends('layouts/contentNavbarLayout')

@section('title', 'Audit Log Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">System / Audit Trail /</span> Log Details
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Audit Log Entry</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Timestamp</label>
                            <p class="form-control-static">{{ $log->created_at }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">User</label>
                            <p class="form-control-static">{{ $log->user->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Action</label>
                            <p class="form-control-static">{{ $log->action }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">IP Address</label>
                            <p class="form-control-static">{{ $log->ip_address }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Details</label>
                            <p class="form-control-static">{{ $log->details }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">User Agent</label>
                            <p class="form-control-static">{{ $log->user_agent }}</p>
                        </div>
                    </div>
                </div>

                @if ($log->old_values || $log->new_values)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Old Values</label>
                                <pre class="form-control-static">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">New Values</label>
                                <pre class="form-control-static">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('audit-trail.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
@endsection
