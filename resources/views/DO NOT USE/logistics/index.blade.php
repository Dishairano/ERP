@extends('layouts/contentNavbarLayout')

@section('title', 'Logistics Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Logistics Management</h5>
                        <a href="{{ route('logistics.logistics-management.create') }}" class="btn btn-primary">Create New
                            Shipment</a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Shipment Number</th>
                                        <th>Origin</th>
                                        <th>Destination</th>
                                        <th>Status</th>
                                        <th>Estimated Delivery</th>
                                        <th>Carrier</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logistics as $shipment)
                                        <tr>
                                            <td>{{ $shipment->shipment_number }}</td>
                                            <td>{{ $shipment->origin }}</td>
                                            <td>{{ $shipment->destination }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $shipment->status === 'delivered' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($shipment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $shipment->estimated_delivery_date }}</td>
                                            <td>{{ $shipment->carrier }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('logistics.logistics-management.show', $shipment->id) }}">
                                                            <i class="ti ti-eye me-1"></i> View
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('logistics.logistics-management.edit', $shipment->id) }}">
                                                            <i class="ti ti-pencil me-1"></i> Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('logistics.logistics-management.destroy', $shipment->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('Are you sure you want to delete this shipment?')">
                                                                <i class="ti ti-trash me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $logistics->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
