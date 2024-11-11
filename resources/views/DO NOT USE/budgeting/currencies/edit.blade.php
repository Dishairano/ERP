@extends('layouts.contentNavbarLayout')

@section('title', 'Edit Currency')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Budgeting / Currencies /</span> Edit
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Currency</h5>
                <a href="{{ route('currencies.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Back to List
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('currencies.update', $currency) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="code">Currency Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                            name="code" value="{{ old('code', $currency->code) }}" required maxlength="3">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="name">Currency Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $currency->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="symbol">Symbol</label>
                        <input type="text" class="form-control @error('symbol') is-invalid @enderror" id="symbol"
                            name="symbol" value="{{ old('symbol', $currency->symbol) }}" required>
                        @error('symbol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="exchange_rate">Exchange Rate</label>
                        <input type="number" step="0.0001"
                            class="form-control @error('exchange_rate') is-invalid @enderror" id="exchange_rate"
                            name="exchange_rate" value="{{ old('exchange_rate', $currency->exchange_rate) }}" required>
                        @error('exchange_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1"
                                {{ old('is_default', $currency->is_default) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">Set as Default Currency</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $currency->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Currency</button>
                </form>
            </div>
        </div>
    </div>
@endsection
