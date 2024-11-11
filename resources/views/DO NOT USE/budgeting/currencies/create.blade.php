@extends('layouts.contentNavbarLayout')

@section('title', 'Add Currency')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Budgeting / Currencies /</span> Add New
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add New Currency</h5>
                <a href="{{ route('currencies.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Back to List
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('currencies.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="code">Currency Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                            name="code" value="{{ old('code') }}" required maxlength="3" placeholder="e.g., USD">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="name">Currency Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required placeholder="e.g., US Dollar">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="symbol">Symbol</label>
                        <input type="text" class="form-control @error('symbol') is-invalid @enderror" id="symbol"
                            name="symbol" value="{{ old('symbol') }}" required placeholder="e.g., $">
                        @error('symbol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="exchange_rate">Exchange Rate</label>
                        <input type="number" step="0.0001"
                            class="form-control @error('exchange_rate') is-invalid @enderror" id="exchange_rate"
                            name="exchange_rate" value="{{ old('exchange_rate', '1.0000') }}" required>
                        @error('exchange_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1"
                                {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">Set as Default Currency</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Currency</button>
                </form>
            </div>
        </div>
    </div>
@endsection
