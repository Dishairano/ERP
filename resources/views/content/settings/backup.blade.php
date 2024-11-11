@extends('layouts/contentNavbarLayout')

@section('title', 'Backup & Recovery')

@section('content')
    <div class="container-fluid">
        <!-- Backup Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Backup Actions</h6>
                        <form action="{{ route('settings.backup.create') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download"></i> Create New Backup
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Backups include your database and uploaded files. They are
                            encrypted and stored securely.
                        </div>

                        <!-- Backup Schedule -->
                        <div class="mb-4">
                            <h6 class="font-weight-bold">Backup Schedule</h6>
                            <form action="{{ route('settings.backup.update') }}" method="POST" class="mt-3">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="backup_frequency">Backup Frequency</label>
                                            <select class="form-control" id="backup_frequency" name="backup_frequency">
                                                <option value="daily"
                                                    {{ ($settings['backup_frequency'] ?? '') == 'daily' ? 'selected' : '' }}>
                                                    Daily</option>
                                                <option value="weekly"
                                                    {{ ($settings['backup_frequency'] ?? '') == 'weekly' ? 'selected' : '' }}>
                                                    Weekly</option>
                                                <option value="monthly"
                                                    {{ ($settings['backup_frequency'] ?? '') == 'monthly' ? 'selected' : '' }}>
                                                    Monthly</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="backup_time">Backup Time</label>
                                            <input type="time" class="form-control" id="backup_time" name="backup_time"
                                                value="{{ $settings['backup_time'] ?? '00:00' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="backup_notification"
                                            name="backup_notification" value="1"
                                            {{ $settings['backup_notification'] ?? false ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="backup_notification">
                                            Send email notification when backup is complete
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Save Schedule
                                </button>
                            </form>
                        </div>

                        <!-- Backup History -->
                        <div class="mt-4">
                            <h6 class="font-weight-bold mb-3">Backup History</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Filename</th>
                                            <th>Size</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($backups as $backup)
                                            <tr>
                                                <td>{{ basename($backup) }}</td>
                                                <td>{{ Storage::disk('backups')->size($backup) / 1024 / 1024 }} MB</td>
                                                <td>{{ Storage::disk('backups')->lastModified($backup) }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('settings.backup.download', basename($backup)) }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            data-toggle="modal" data-target="#restoreBackupModal"
                                                            data-backup="{{ basename($backup) }}">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-toggle="modal" data-target="#deleteBackupModal"
                                                            data-backup="{{ basename($backup) }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No backups found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Restore Backup Modal -->
    <div class="modal fade" id="restoreBackupModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('settings.backup.restore') }}" method="POST">
                    @csrf
                    <input type="hidden" name="backup_file" id="restore_backup_file">
                    <div class="modal-header">
                        <h5 class="modal-title">Restore Backup</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Warning!</strong> Restoring a backup will overwrite all current data. This action cannot
                            be undone.
                        </div>
                        <p>Are you sure you want to restore this backup?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Restore Backup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Backup Modal -->
    <div class="modal fade" id="deleteBackupModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('settings.backup.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="backup_file" id="delete_backup_file">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Backup</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this backup? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Backup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle restore backup modal
            $('#restoreBackupModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const backup = button.data('backup');
                const modal = $(this);
                modal.find('#restore_backup_file').val(backup);
            });

            // Handle delete backup modal
            $('#deleteBackupModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const backup = button.data('backup');
                const modal = $(this);
                modal.find('#delete_backup_file').val(backup);
            });
        });
    </script>
@endpush
