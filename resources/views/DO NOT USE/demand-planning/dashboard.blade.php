@extends('layouts/contentNavbarLayout')

@section('title', 'Demand Planning Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Forecast Accuracy</h5>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <h2 class="mb-0">{{ number_format($accuracyStats->avg_accuracy ?? 0, 1) }}%</h2>
                                <small class="text-muted">Average Accuracy</small>
                            </div>
                            <div>
                                <h2 class="mb-0">{{ number_format($accuracyStats->avg_bias * 100 ?? 0, 1) }}%</h2>
                                <small class="text-muted">Average Bias</small>
                            </div>
                        </div>
                        <div class="progress mt-3">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $accuracyStats->avg_accuracy ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Active Promotions</h5>
                        <div class="list-group">
                            @forelse($activePromotions->take(3) as $promotion)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $promotion->event_name }}</h6>
                                        <small>+{{ number_format($promotion->expected_lift, 1) }}%</small>
                                    </div>
                                    <small class="text-muted">
                                        {{ $promotion->start_date->format('M d') }} -
                                        {{ $promotion->end_date->format('M d') }}
                                    </small>
                                </div>
                            @empty
                                <div class="text-center py-3">
                                    <p class="mb-0">No active promotions</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Trending Products</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trendingProducts as $product)
                                        <tr>
                                            <td>{{ $product->product->name }}</td>
                                            <td>{{ number_format($product->total_quantity) }}</td>
                                            <td>{{ number_format($product->total_value, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Forecasts</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Region</th>
                                        <th>Date</th>
                                        <th>Quantity</th>
                                        <th>Value</th>
                                        <th>Accuracy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentForecasts as $forecast)
                                        <tr>
                                            <td>{{ $forecast->product->name }}</td>
                                            <td>{{ $forecast->region ? $forecast->region->name : 'All Regions' }}</td>
                                            <td>{{ $forecast->forecast_date->format('Y-m-d') }}</td>
                                            <td>{{ number_format($forecast->forecast_quantity) }}</td>
                                            <td>{{ number_format($forecast->forecast_value, 2) }}</td>
                                            <td>
                                                @if ($forecast->accuracy)
                                                    <span
                                                        class="badge bg-label-{{ $forecast->accuracy->accuracy_percentage >= 90 ? 'success' : ($forecast->accuracy->accuracy_percentage >= 70 ? 'warning' : 'danger') }}">
                                                        {{ number_format($forecast->accuracy->accuracy_percentage, 1) }}%
                                                    </span>
                                                @else
                                                    <span class="badge bg-label-secondary">Pending</span>
                                                @endif
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
    </div>
@endsection
