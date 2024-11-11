@extends('layouts.contentNavbarLayout')

@section('title', 'Add New Resource')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add New Resource</h5>
                    <a href="{{ route('resources.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('resources.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="type">Type</label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type"
                                        name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="equipment" {{ old('type') === 'equipment' ? 'selected' : '' }}>
                                            Equipment</option>
                                        <option value="machine" {{ old('type') === 'machine' ? 'selected' : '' }}>Machine
                                        </option>
                                        <option value="workspace" {{ old('type') === 'workspace' ? 'selected' : '' }}>
                                            Workspace</option>
                                        <option value="personnel" {{ old('type') === 'personnel' ? 'selected' : '' }}>
                                            Personnel</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>
                                            Available</option>
                                        <option value="in_use" {{ old('status') === 'in_use' ? 'selected' : '' }}>In Use
                                        </option>
                                        <option value="maintenance"
                                            {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="unavailable"
                                            {{ old('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="capacity">Capacity</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                        id="capacity" name="capacity" value="{{ old('capacity', 1) }}" min="1"
                                        required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="cost_per_hour">Cost per Hour (€)</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('cost_per_hour') is-invalid @enderror" id="cost_per_hour"
                                        name="cost_per_hour" value="{{ old('cost_per_hour') }}">
                                    @error('cost_per_hour')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="cost_per_day">Cost per Day (€)</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('cost_per_day') is-invalid @enderror" id="cost_per_day"
                                        name="cost_per_day" value="{{ old('cost_per_day') }}">
                                    @error('cost_per_day')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Capabilities</label>
                                    <div id="capabilities-container">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="capabilities[]"
                                                placeholder="Enter capability">
                                            <button type="button" class="btn btn-outline-primary"
                                                onclick="addCapability()">
                                                <i class="bx bx-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Location Details</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control mb-2"
                                                name="location_details[building]" placeholder="Building"
                                                value="{{ old('location_details.building') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control mb-2"
                                                name="location_details[floor]" placeholder="Floor"
                                                value="{{ old('location_details.floor') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control mb-2" name="location_details[room]"
                                                placeholder="Room" value="{{ old('location_details.room') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Create Resource</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        function addCapability() {
            const container = document.getElementById('capabilities-container');
            const newInput = document.createElement('div');
            newInput.className = 'input-group mb-2';
            newInput.innerHTML = `
        <input type="text" class="form-control" name="capabilities[]" placeholder="Enter capability">
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
            <i class="bx bx-minus"></i>
        </button>
    `;
            container.appendChild(newInput);
        }
    </script>
@endsection
