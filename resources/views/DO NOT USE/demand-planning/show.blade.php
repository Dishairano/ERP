@extends('layouts/contentNavbarLayout')

@section('title', 'View Forecast')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Forecast Details</h5>
                        <div>
                            <a href="{{ route('demand-planning.edit', $forecast) }}" class="btn btn-primary">
                                <i class="bx bx-edit"></i> Edit
                            </a>
                            <a href="{{ route('demand-planning.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Product</th>
                                        <td>{{ $forecast->product->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Region</th>
                                        <td>{{ $forecast->region ? $forecast->region->name : 'All Regions' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Forecast Date</th>
                                        <td>{{ $forecast->forecast_date->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Forecast Method</th>
                                        <td>{{ ucfirst(str_replace('_', ' ', $forecast->forecast_method)) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Forecast Quantity</th>
                                        <td>{{ number_format($forecast->forecast_quantity) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Forecast Value</th>
                                        <td>{{ number_format($forecast->forecast_value, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Confidence Level</th>
                                        <td>{{ number_format($forecast->confidence_level, 1) }}%</td>
                                    </tr>
                                    <tr>
                                        <th>Created By</th>
                                        <td>{{ $forecast->creator->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if ($forecast->accuracy)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6>Accuracy Metrics</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Actual Quantity</th>
                                            <td>{{ number_format($forecast->accuracy->actual_quantity) }}</td>
                                            <th>Actual Value</th>
                                            <td>{{ number_format($forecast->accuracy->actual_value, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Accuracy Percentage</th>
                                            <td>{{ number_format($forecast->accuracy->accuracy_percentage, 1) }}%</td>
                                            <th>Bias</th>
                                            <td>{{ number_format($forecast->accuracy->bias * 100, 1) }}%</td>
                                        </tr>
                                        @if ($forecast->accuracy->variance_reason)
                                            <tr>
                                                <th>Variance Reason</th>
                                                <td colspan="3">{{ $forecast->accuracy->variance_reason }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6>Update Accuracy</h6>
                                        <form action="{{ route('demand-planning.update-accuracy', $forecast) }}"
                                            method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Actual Quantity</label>
                                                    <input type="number" name="actual_quantity" class="form-control"
                                                        required min="0">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Actual Value</label>
                                                    <input type="number" step="0.01" name="actual_value"
                                                        class="form-control" required min="0">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Variance Reason</label>
                                                    <input type="text" name="variance_reason" class="form-control">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Update Accuracy</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-4">
                            <div class="col-12">
                                <h6>Historical Sales</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Quantity</th>
                                                <th>Value</th>
                                                <th>Customer Segment</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($historicalSales as $sale)
                                                <tr>
                                                    <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                                                    <td>{{ number_format($sale->quantity_sold) }}</td>
                                                    <td>{{ number_format($sale->sale_value, 2) }}</td>
                                                    <td>{{ ucfirst($sale->customer_segment) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @if ($relatedTrends->count() > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6>Related Market Trends</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Trend</th>
                                                    <th>Type</th>
                                                    <th>Impact</th>
                                                    <th>Period</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($relatedTrends as $trend)
                                                    <tr>
                                                        <td>{{ $trend->trend_name }}</td>
                                                        <td>{{ ucfirst($trend->trend_type) }}</td>
                                                        <td>{{ number_format($trend->impact_factor * 100, 1) }}%</td>
                                                        <td>{{ $trend->start_date->format('Y-m-d') }} to
                                                            {{ $trend->end_date->format('Y-m-d') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
