@extends('layouts/contentNavbarLayout')

@section('title', 'Demand Planning')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Demand Forecasts</h5>
                        <div>
                            <a href="{{ route('demand-planning.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> New Forecast
                            </a>
                            <a href="{{ route('demand-planning.export') }}" class="btn btn-secondary">
                                <i class="bx bx-export"></i> Export
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Region</th>
                                        <th>Date</th>
                                        <th>Quantity</th>
                                        <th>Value</th>
                                        <th>Method</th>
                                        <th>Confidence</th>
                                        <th>Accuracy</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($forecasts as $forecast)
                                        <tr>
                                            <td>{{ $forecast->product->name }}</td>
                                            <td>{{ $forecast->region ? $forecast->region->name : 'All Regions' }}</td>
                                            <td>{{ $forecast->forecast_date->format('Y-m-d') }}</td>
                                            <td>{{ number_format($forecast->forecast_quantity) }}</td>
                                            <td>{{ number_format($forecast->forecast_value, 2) }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $forecast->forecast_method)) }}</td>
                                            <td>{{ number_format($forecast->confidence_level, 1) }}%</td>
                                            <td>
                                                @if ($forecast->accuracy)
                                                    {{ number_format($forecast->accuracy->accuracy_percentage, 1) }}%
                                                @else
                                                    <span class="badge bg-label-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('demand-planning.show', $forecast) }}">
                                                            <i class="bx bx-show-alt me-1"></i> View
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('demand-planning.edit', $forecast) }}">
                                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                                        </a>
                                                        <form action="{{ route('demand-planning.destroy', $forecast) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('Are you sure?')">
                                                                <i class="bx bx-trash me-1"></i> Delete
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
                            {{ $forecasts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($notifications->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Notifications</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach ($notifications as $notification)
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $notification->title }}</h6>
                                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">{{ $notification->message }}</p>
                                        <small class="text-muted">{{ ucfirst($notification->notification_type) }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
