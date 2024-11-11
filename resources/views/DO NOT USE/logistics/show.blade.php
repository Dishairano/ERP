@extends('layouts/contentNavbarLayout')

@section('title', 'View Shipment')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Shipment Details</h5>
                        <div>
                            <a href="{{ route('logistics.logistics-management.edit', $logistics->id) }}"
                                class="btn btn-primary">Edit</a>
                            <a href="{{ route('logistics.logistics-management.index') }}" class="btn btn-secondary">Back to
                                List</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipment Number</label>
                                <p class="form-control-static">{{ $logistics->shipment_number }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Carrier</label>
                                <p class="form-control-static">{{ $logistics->carrier }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Origin</label>
                                <p class="form-control-static">{{ $logistics->origin }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Destination</label>
                                <p class="form-control-static">{{ $logistics->destination }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <p class="form-control-static">
                                    <span class="badge bg-{{ $logistics->status === 'delivered' ? 'success' : 'warning' }}">
                                        {{ ucfirst($logistics->status) }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tracking Number</label>
                                <p class="form-control-static">{{ $logistics->tracking_number ?? 'N/A' }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estimated Delivery Date</label>
                                <p class="form-control-static">{{ $logistics->estimated_delivery_date?->format('Y-m-d') }}
                                </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Actual Delivery Date</label>
                                <p class="form-control-static">
                                    {{ $logistics->actual_delivery_date?->format('Y-m-d') ?? 'Not delivered yet' }}</p>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Notes</label>
                                <p class="form-control-static">{{ $logistics->notes ?? 'No notes available' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
