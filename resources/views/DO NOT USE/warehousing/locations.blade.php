@extends('layouts/contentNavbarLayout')

@section('title', 'Warehouse Locations')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Warehouse Locations</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createWarehouseModal">
                            Add New Location
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Manager</th>
                                        <th>Total Zones</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouses as $warehouse)
                                        <tr>
                                            <td>{{ $warehouse->code }}</td>
                                            <td>{{ $warehouse->name }}</td>
                                            <td>{{ $warehouse->address }}</td>
                                            <td>{{ $warehouse->manager->name ?? 'Not Assigned' }}</td>
                                            <td>{{ $warehouse->zones->count() }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $warehouse->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($warehouse->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editWarehouseModal{{ $warehouse->id }}">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('warehousing.zones', ['warehouse' => $warehouse->id]) }}">
                                                            <i class="ri-layout-grid-line me-1"></i> View Zones
                                                        </a>
                                                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                            onclick="confirmDelete({{ $warehouse->id }})">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $warehouses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Warehouse Modal -->
    <div class="modal fade" id="createWarehouseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Warehouse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('warehousing.locations.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" class="form-control" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Manager</label>
                            <select class="form-select" name="manager_id">
                                <option value="">Select Manager</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Warehouse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($warehouses as $warehouse)
        <!-- Edit Warehouse Modal -->
        <div class="modal fade" id="editWarehouseModal{{ $warehouse->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Warehouse</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('warehousing.locations.update', $warehouse->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Code</label>
                                <input type="text" class="form-control" name="code" value="{{ $warehouse->code }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $warehouse->name }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3" required>{{ $warehouse->address }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Manager</label>
                                <select class="form-select" name="manager_id">
                                    <option value="">Select Manager</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $warehouse->manager_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="active" {{ $warehouse->status === 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $warehouse->status === 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Warehouse</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('page-script')
    <script>
        function confirmDelete(warehouseId) {
            if (confirm('Are you sure you want to delete this warehouse?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/warehousing/locations/${warehouseId}`;
                form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
