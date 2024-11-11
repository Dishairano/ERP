@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Shipment')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Shipment</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('logistics.logistics-management.update', $logistics->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="shipment_number">Shipment Number</label>
                                    <input type="text"
                                        class="form-control @error('shipment_number') is-invalid @enderror"
                                        id="shipment_number" name="shipment_number"
                                        value="{{ old('shipment_number', $logistics->shipment_number) }}" required>
                                    @error('shipment_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="carrier">Carrier</label>
                                    <input type="text" class="form-control @error('carrier') is-invalid @enderror"
                                        id="carrier" name="carrier" value="{{ old('carrier', $logistics->carrier) }}"
                                        required>
                                    @error('carrier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="origin">Origin</label>
                                    <input type="text" class="form-control @error('origin') is-invalid @enderror"
                                        id="origin" name="origin" value="{{ old('origin', $logistics->origin) }}"
                                        required>
                                    @error('origin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="destination">Destination</label>
                                    <input type="text" class="form-control @error('destination') is-invalid @enderror"
                                        id="destination" name="destination"
                                        value="{{ old('destination', $logistics->destination) }}" required>
                                    @error('destination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="pending"
                                            {{ old('status', $logistics->status) == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="in_transit"
                                            {{ old('status', $logistics->status) == 'in_transit' ? 'selected' : '' }}>In
                                            Transit</option>
                                        <option value="delivered"
                                            {{ old('status', $logistics->status) == 'delivered' ? 'selected' : '' }}>
                                            Delivered</option>
                                        <option value="cancelled"
                                            {{ old('status', $logistics->status) == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="tracking_number">Tracking Number</label>
                                    <input type="text"
                                        class="form-control @error('tracking_number') is-invalid @enderror"
                                        id="tracking_number" name="tracking_number"
                                        value="{{ old('tracking_number', $logistics->tracking_number) }}">
                                    @error('tracking_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="estimated_delivery_date">Estimated Delivery Date</label>
                                    <input type="date"
                                        class="form-control @error('estimated_delivery_date') is-invalid @enderror"
                                        id="estimated_delivery_date" name="estimated_delivery_date"
                                        value="{{ old('estimated_delivery_date', $logistics->estimated_delivery_date?->format('Y-m-d')) }}"
                                        required>
                                    @error('estimated_delivery_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="actual_delivery_date">Actual Delivery Date</label>
                                    <input type="date"
                                        class="form-control @error('actual_delivery_date') is-invalid @enderror"
                                        id="actual_delivery_date" name="actual_delivery_date"
                                        value="{{ old('actual_delivery_date', $logistics->actual_delivery_date?->format('Y-m-d')) }}">
                                    @error('actual_delivery_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="notes">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $logistics->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update Shipment</button>
                                    <a href="{{ route('logistics.logistics-management.index') }}"
                                        class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
