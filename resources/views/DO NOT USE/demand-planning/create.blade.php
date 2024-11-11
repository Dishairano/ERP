@extends('layouts/contentNavbarLayout')

@section('title', 'Create Demand Forecast')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Create New Forecast</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('demand-planning.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="product_id">Product</label>
                                    <select class="form-select @error('product_id') is-invalid @enderror" id="product_id"
                                        name="product_id" required>
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="region_id">Region</label>
                                    <select class="form-select @error('region_id') is-invalid @enderror" id="region_id"
                                        name="region_id">
                                        <option value="">All Regions</option>
                                        @foreach ($regions as $region)
                                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('region_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="forecast_date">Forecast Date</label>
                                    <input type="date" class="form-control @error('forecast_date') is-invalid @enderror"
                                        id="forecast_date" name="forecast_date" required>
                                    @error('forecast_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="forecast_method">Forecast Method</label>
                                    <select class="form-select @error('forecast_method') is-invalid @enderror"
                                        id="forecast_method" name="forecast_method" required>
                                        @foreach ($forecastMethods as $key => $method)
                                            <option value="{{ $key }}">{{ $method }}</option>
                                        @endforeach
                                    </select>
                                    @error('forecast_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="forecast_quantity">Forecast Quantity</label>
                                    <input type="number"
                                        class="form-control @error('forecast_quantity') is-invalid @enderror"
                                        id="forecast_quantity" name="forecast_quantity" required min="0">
                                    @error('forecast_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="forecast_value">Forecast Value</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('forecast_value') is-invalid @enderror"
                                        id="forecast_value" name="forecast_value" required min="0">
                                    @error('forecast_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="confidence_level">Confidence Level (%)</label>
                                    <input type="number" step="0.1"
                                        class="form-control @error('confidence_level') is-invalid @enderror"
                                        id="confidence_level" name="confidence_level" required min="0"
                                        max="100">
                                    @error('confidence_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Market Trends</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Trend</th>
                                                    <th>Impact</th>
                                                    <th>Period</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($marketTrends as $trend)
                                                    <tr>
                                                        <td>{{ $trend->trend_name }}</td>
                                                        <td>{{ number_format($trend->impact_factor * 100, 1) }}%</td>
                                                        <td>{{ $trend->start_date->format('Y-m-d') }} to
                                                            {{ $trend->end_date->format('Y-m-d') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Active Promotions</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Event</th>
                                                    <th>Expected Lift</th>
                                                    <th>Period</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($promotions as $promotion)
                                                    <tr>
                                                        <td>{{ $promotion->event_name }}</td>
                                                        <td>{{ number_format($promotion->expected_lift, 1) }}%</td>
                                                        <td>{{ $promotion->start_date->format('Y-m-d') }} to
                                                            {{ $promotion->end_date->format('Y-m-d') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="seasonal_factors">Seasonal Factors</label>
                                    <div class="row">
                                        @foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter)
                                            <div class="col-md-3 mb-2">
                                                <div class="input-group">
                                                    <span class="input-group-text">{{ $quarter }}</span>
                                                    <input type="number" step="0.01" class="form-control"
                                                        name="seasonal_factors[{{ $quarter }}]" value="1.00">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('seasonal_factors')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Create Forecast</button>
                                    <a href="{{ route('demand-planning.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-calculate forecast value based on quantity
            const quantityInput = document.getElementById('forecast_quantity');
            const valueInput = document.getElementById('forecast_value');

            quantityInput.addEventListener('input', function() {
                // You can implement your own pricing logic here
                const quantity = parseFloat(this.value) || 0;
                // Example: value = quantity * average price
                const averagePrice = 100; // This should come from your backend
                valueInput.value = (quantity * averagePrice).toFixed(2);
            });

            // Initialize select2 for better dropdown experience
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                $('#product_id, #region_id').select2({
                    theme: 'bootstrap-5'
                });
            }
        });
    </script>
@endsection
