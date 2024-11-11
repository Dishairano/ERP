@extends('layouts/contentNavbarLayout')

@section('title', 'Integration Logs')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Sync Logs for {{ $integration->name }}</h4>
                    <a href="{{ route('data-integration.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left"></i> Back to Integrations
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date/Time</th>
                                    <th>Status</th>
                                    <th>Records Processed</th>
                                    <th>Success</th>
                                    <th>Failed</th>
                                    <th>Message</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $log->status === 'success' ? 'success' : ($log->status === 'warning' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->records_processed }}</td>
                                        <td>{{ $log->records_succeeded }}</td>
                                        <td>{{ $log->records_failed }}</td>
                                        <td>{{ $log->message }}</td>
                                        <td>
                                            @if ($log->error_details)
                                                <button type="button" class="btn btn-sm btn-info view-details"
                                                    data-details="{{ json_encode($log->error_details) }}">
                                                    View Details
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Error Details Modal -->
    <div class="modal fade" id="errorDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Error Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <pre id="errorDetails" class="bg-light p-2" style="max-height: 400px; overflow-y: auto;"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            $('.view-details').click(function() {
                const details = $(this).data('details');
                $('#errorDetails').text(JSON.stringify(details, null, 2));
                $('#errorDetailsModal').modal('show');
            });
        });
    </script>
@endsection
