@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ $dashboard->name }} - Components</h3>
                        <div>
                            <a href="{{ route('dashboards.show', $dashboard) }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line"></i> Back to Dashboard
                            </a>
                            @can('manageComponents', $dashboard)
                                <a href="{{ route('dashboard-components.create', $dashboard) }}" class="btn btn-primary">
                                    <i class="ri-add-line"></i> Add Component
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        @if ($components->isEmpty())
                            <div class="text-center py-5">
                                <i class="ri-dashboard-line text-muted" style="font-size: 48px;"></i>
                                <p class="mt-3">No components found.</p>
                                @can('manageComponents', $dashboard)
                                    <a href="{{ route('dashboard-components.create', $dashboard) }}" class="btn btn-primary">
                                        Add Your First Component
                                    </a>
                                @endcan
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Position</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Size</th>
                                            <th>Data Source</th>
                                            <th>Last Updated</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="sortable"
                                        data-url="{{ route('dashboard-components.reorder', $dashboard) }}">
                                        @foreach ($components as $component)
                                            <tr data-id="{{ $component->id }}">
                                                <td>
                                                    @can('manageComponents', $dashboard)
                                                        <i class="ri-drag-move-line cursor-move"></i>
                                                    @endcan
                                                    {{ $component->position + 1 }}
                                                </td>
                                                <td>{{ $component->name }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ ucfirst($component->type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ ucfirst($component->size) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($component->dataSource)
                                                        {{ $component->dataSource->name }}
                                                    @else
                                                        <span class="text-muted">None</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $component->updated_at->diffForHumans() }}
                                                </td>
                                                <td>
                                                    @if ($component->is_enabled)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Disabled</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('dashboard-components.show', [$dashboard, $component]) }}"
                                                            class="btn btn-sm btn-info" title="View">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                        @can('manageComponents', $dashboard)
                                                            <a href="{{ route('dashboard-components.edit', [$dashboard, $component]) }}"
                                                                class="btn btn-sm btn-warning" title="Edit">
                                                                <i class="ri-edit-line"></i>
                                                            </a>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger delete-component"
                                                                data-url="{{ route('dashboard-components.destroy', [$dashboard, $component]) }}"
                                                                title="Delete">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('manageComponents', $dashboard)
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this component? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteForm" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize delete confirmation
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            const deleteButtons = document.querySelectorAll('.delete-component');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    deleteForm.action = this.dataset.url;
                    new bootstrap.Modal(deleteModal).show();
                });
            });

            // Initialize sortable if user can manage components
            @can('manageComponents', $dashboard)
                if (document.querySelector('.sortable')) {
                    new Sortable(document.querySelector('.sortable tbody'), {
                        handle: '.cursor-move',
                        animation: 150,
                        onEnd: function(evt) {
                            const url = evt.target.dataset.url;
                            const items = [...evt.target.querySelectorAll('tr')].map((tr, index) => ({
                                id: tr.dataset.id,
                                position: index
                            }));

                            fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({
                                        items
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update positions in the UI
                                        items.forEach((item, index) => {
                                            const tr = document.querySelector(
                                                `tr[data-id="${item.id}"]`);
                                            tr.querySelector('td:first-child').textContent =
                                                index + 1;
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    });
                }
            @endcan
        });
    </script>
@endpush

@push('styles')
    <style>
        .cursor-move {
            cursor: move;
        }
    </style>
@endpush
