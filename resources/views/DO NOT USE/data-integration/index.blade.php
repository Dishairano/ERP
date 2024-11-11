@extends('layouts/contentNavbarLayout')

@section('title', 'Data Integration Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Integrations</h4>
                    <a href="{{ route('data-integration.create') }}" class="btn btn-primary">
                        <i data-feather="plus"></i> New Integration
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Source Type</th>
                                    <th>Connection Type</th>
                                    <th>Status</th>
                                    <th>Last Sync</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($integrations as $integration)
                                    <tr>
                                        <td>{{ $integration->name }}</td>
                                        <td>{{ $integration->source_type }}</td>
                                        <td>{{ $integration->connection_type }}</td>
                                        <td>
                                            <span class="badge badge-{{ $integration->is_active ? 'success' : 'danger' }}">
                                                {{ $integration->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $integration->last_sync ? $integration->last_sync->diffForHumans() : 'Never' }}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary sync-now"
                                                    data-id="{{ $integration->id }}">
                                                    <i data-feather="refresh-cw"></i> Sync Now
                                                </button>
                                                <a href="{{ route('data-integration.edit', $integration) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i data-feather="edit"></i> Edit
                                                </a>
                                                <a href="{{ route('data-integration.logs', $integration) }}"
                                                    class="btn btn-sm btn-secondary">
                                                    <i data-feather="list"></i> Logs
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger delete-integration"
                                                    data-id="{{ $integration->id }}">
                                                    <i data-feather="trash-2"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this integration?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            $('.sync-now').click(function() {
                const id = $(this).data('id');
                const button = $(this);
                button.prop('disabled', true);

                $.ajax({
                    url: `/data-integration/${id}/sync`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Sync failed');
                        button.prop('disabled', false);
                    }
                });
            });

            $('.delete-integration').click(function() {
                const id = $(this).data('id');
                $('#deleteForm').attr('action', `/data-integration/${id}`);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endsection
