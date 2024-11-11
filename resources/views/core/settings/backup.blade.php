@extends('layouts/contentNavbarLayout')

@section('title', 'Backup Settings')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Backup & Recovery</h5>
                        <form action="{{ route('settings.backup.create') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> Create New Backup
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <!-- Backup Information -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h6 class="card-title text-white">Last Backup</h6>
                                        @if (count($backups) > 0)
                                            <p class="card-text mb-0">
                                                {{ \Carbon\Carbon::createFromTimestamp($backups->last()['date'])->format('M d, Y H:i:s') }}
                                            </p>
                                        @else
                                            <p class="card-text mb-0">No backups available</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h6 class="card-title text-white">Total Backups</h6>
                                        <p class="card-text mb-0">{{ count($backups) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="card-title text-white">Storage Used</h6>
                                        <p class="card-text mb-0">
                                            {{ formatBytes($backups->sum('size')) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Backup List -->
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Backup File</th>
                                            <th>Date</th>
                                            <th>Size</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($backups->sortByDesc('date') as $backup)
                                            <tr>
                                                <td>
                                                    <i class="ri-file-zip-line me-2"></i>
                                                    {{ basename($backup['name']) }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromTimestamp($backup['date'])->format('M d, Y H:i:s') }}
                                                </td>
                                                <td>{{ formatBytes($backup['size']) }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                            data-bs-toggle="dropdown">
                                                            <i class="ri-more-2-fill"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item"
                                                                href="{{ route('settings.backup.download', basename($backup['name'])) }}">
                                                                <i class="ri-download-line me-1"></i> Download
                                                            </a>
                                                            <form
                                                                action="{{ route('settings.backup.delete', basename($backup['name'])) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this backup?')">
                                                                    <i class="ri-delete-bin-line me-1"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No backup files found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Backup Settings -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">Backup Settings</h6>
                            </div>
                            <div class="card-body">
                                <form id="backupSettingsForm">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Automatic Backup</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="automaticBackup">
                                                <label class="form-check-label" for="automaticBackup">Enable automatic
                                                    backups</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="backupFrequency">Backup Frequency</label>
                                            <select class="form-select" id="backupFrequency">
                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="retentionPeriod">Retention Period</label>
                                            <select class="form-select" id="retentionPeriod">
                                                <option value="7">7 days</option>
                                                <option value="30">30 days</option>
                                                <option value="90">90 days</option>
                                                <option value="365">365 days</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Notification</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="backupNotification">
                                                <label class="form-check-label" for="backupNotification">
                                                    Send email notification after backup
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
@endphp

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize form with saved settings (if any)
            const savedSettings = localStorage.getItem('backupSettings');
            if (savedSettings) {
                const settings = JSON.parse(savedSettings);
                document.getElementById('automaticBackup').checked = settings.automaticBackup;
                document.getElementById('backupFrequency').value = settings.backupFrequency;
                document.getElementById('retentionPeriod').value = settings.retentionPeriod;
                document.getElementById('backupNotification').checked = settings.backupNotification;
            }

            // Save settings when changed
            const form = document.getElementById('backupSettingsForm');
            const formElements = form.querySelectorAll('input, select');
            formElements.forEach(element => {
                element.addEventListener('change', function() {
                    const settings = {
                        automaticBackup: document.getElementById('automaticBackup').checked,
                        backupFrequency: document.getElementById('backupFrequency').value,
                        retentionPeriod: document.getElementById('retentionPeriod').value,
                        backupNotification: document.getElementById('backupNotification')
                            .checked
                    };
                    localStorage.setItem('backupSettings', JSON.stringify(settings));
                });
            });
        });
    </script>
@endsection
