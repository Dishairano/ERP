@extends('layouts/contentNavbarLayout')

@section('title', 'Warehouse Zones')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Warehouse Zones</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createZoneModal">
                            Add New Zone
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Warehouse</th>
                                        <th>Capacity</th>
                                        <th>Used Space</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($zones as $zone)
                                        <tr>
                                            <td>{{ $zone->code }}</td>
                                            <td>{{ $zone->name }}</td>
                                            <td>{{ ucfirst($zone->type) }}</td>
                                            <td>{{ $zone->warehouse->name }}</td>
                                            <td>{{ $zone->capacity }}</td>
                                            <td>
                                                @php
                                                    $usedSpace = $zone->bins->sum('used_capacity');
                                                    $percentage = ($usedSpace / $zone->capacity) * 100;
                                                @endphp
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ number_format($percentage, 1) }}% used</small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $zone->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($zone->status) }}
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
                                                            data-bs-target="#editZoneModal{{ $zone->id }}">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#manageBinsModal{{ $zone->id }}">
                                                            <i class="ri-layout-grid-line me-1"></i> Manage Bins
                                                        </a>
                                                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                            onclick="confirmDelete({{ $zone->id }})">
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
                        {{ $zones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Zone Modal -->
    <div class="modal fade" id="createZoneModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Zone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('warehousing.zones.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Warehouse</label>
                            <select class="form-select" name="warehouse_id" required>
                                <option value="">Select Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" class="form-control" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="type" required>
                                <option value="storage">Storage</option>
                                <option value="picking">Picking</option>
                                <option value="receiving">Receiving</option>
                                <option value="shipping">Shipping</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Capacity</label>
                            <input type="number" class="form-control" name="capacity" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Zone</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($zones as $zone)
        <!-- Edit Zone Modal -->
        <div class="modal fade" id="editZoneModal{{ $zone->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Zone</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('warehousing.zones.update', $zone->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Warehouse</label>
                                <select class="form-select" name="warehouse_id" required>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}"
                                            {{ $zone->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Code</label>
                                <input type="text" class="form-control" name="code" value="{{ $zone->code }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $zone->name }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select class="form-select" name="type" required>
                                    <option value="storage" {{ $zone->type === 'storage' ? 'selected' : '' }}>Storage
                                    </option>
                                    <option value="picking" {{ $zone->type === 'picking' ? 'selected' : '' }}>Picking
                                    </option>
                                    <option value="receiving" {{ $zone->type === 'receiving' ? 'selected' : '' }}>
                                        Receiving</option>
                                    <option value="shipping" {{ $zone->type === 'shipping' ? 'selected' : '' }}>Shipping
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Capacity</label>
                                <input type="number" class="form-control" name="capacity"
                                    value="{{ $zone->capacity }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location"
                                    value="{{ $zone->location }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="active" {{ $zone->status === 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $zone->status === 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Zone</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Manage Bins Modal -->
        <div class="modal fade" id="manageBinsModal{{ $zone->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Manage Bins - {{ $zone->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col">
                                <button type="button" class="btn btn-primary"
                                    onclick="showAddBinForm({{ $zone->id }})">
                                    Add New Bin
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Capacity</th>
                                        <th>Used Space</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($zone->bins as $bin)
                                        <tr>
                                            <td>{{ $bin->code }}</td>
                                            <td>{{ $bin->name }}</td>
                                            <td>{{ $bin->capacity }}</td>
                                            <td>
                                                @php
                                                    $binPercentage = ($bin->used_capacity / $bin->capacity) * 100;
                                                @endphp
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $binPercentage }}%"
                                                        aria-valuenow="{{ $binPercentage }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ number_format($binPercentage, 1) }}%
                                                    used</small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $bin->status === 'empty' ? 'success' : ($bin->status === 'partial' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($bin->status) }}
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
                                                            onclick="editBin({{ $bin->id }})">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                            onclick="deleteBin({{ $bin->id }})">
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
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('page-script')
    <script>
        function confirmDelete(zoneId) {
            if (confirm('Are you sure you want to delete this zone?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/warehousing/zones/${zoneId}`;
                form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function showAddBinForm(zoneId) {
            // Implementation for showing add bin form
        }

        function editBin(binId) {
            // Implementation for editing bin
        }

        function deleteBin(binId) {
            if (confirm('Are you sure you want to delete this bin?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/warehousing/bins/${binId}`;
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
