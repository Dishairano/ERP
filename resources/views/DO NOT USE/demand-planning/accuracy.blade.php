@extends('layouts/contentNavbarLayout')

@section('title', 'Forecast Accuracy Reports')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Forecast Accuracy Reports</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Region</th>
                                        <th>Forecast Date</th>
                                        <th>Forecast Quantity</th>
                                        <th>Actual Quantity</th>
                                        <th>Accuracy</th>
                                        <th>Bias</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accuracyReports as $report)
                                        <tr>
                                            <td>{{ $report->forecast->product->name }}</td>
                                            <td>{{ $report->forecast->region ? $report->forecast->region->name : 'All Regions' }}
                                            </td>
                                            <td>{{ $report->forecast->forecast_date->format('Y-m-d') }}</td>
                                            <td>{{ number_format($report->forecast->forecast_quantity) }}</td>
                                            <td>{{ number_format($report->actual_quantity) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-label-{{ $report->accuracy_percentage >= 90 ? 'success' : ($report->accuracy_percentage >= 70 ? 'warning' : 'danger') }}">
                                                    {{ number_format($report->accuracy_percentage, 1) }}%
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-{{ $report->bias >= 0 ? 'success' : 'danger' }}">
                                                    {{ number_format($report->bias * 100, 1) }}%
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#accuracyDetailsModal{{ $report->id }}">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Accuracy Details Modal -->
                                        <div class="modal fade" id="accuracyDetailsModal{{ $report->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Accuracy Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-12 mb-3">
                                                                <h6>Forecast Information</h6>
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <th>Product:</th>
                                                                        <td>{{ $report->forecast->product->name }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Region:</th>
                                                                        <td>{{ $report->forecast->region ? $report->forecast->region->name : 'All Regions' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Date:</th>
                                                                        <td>{{ $report->forecast->forecast_date->format('Y-m-d') }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Method:</th>
                                                                        <td>{{ ucfirst(str_replace('_', ' ', $report->forecast->forecast_method)) }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>

                                                            <div class="col-12 mb-3">
                                                                <h6>Accuracy Metrics</h6>
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <th>Forecast Quantity:</th>
                                                                        <td>{{ number_format($report->forecast->forecast_quantity) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Actual Quantity:</th>
                                                                        <td>{{ number_format($report->actual_quantity) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Forecast Value:</th>
                                                                        <td>{{ number_format($report->forecast->forecast_value, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Actual Value:</th>
                                                                        <td>{{ number_format($report->actual_value, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Accuracy:</th>
                                                                        <td>{{ number_format($report->accuracy_percentage, 1) }}%
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Bias:</th>
                                                                        <td>{{ number_format($report->bias * 100, 1) }}%
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>

                                                            @if ($report->variance_reason)
                                                                <div class="col-12">
                                                                    <h6>Variance Reason</h6>
                                                                    <p class="mb-0">{{ $report->variance_reason }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $accuracyReports->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
