@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <!-- Supplier Selection -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Compare Suppliers</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('suppliers.compare') }}" method="GET" class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="supplier_ids">Select Suppliers to Compare</label>
                                    <select class="form-control select2" id="supplier_ids" name="supplier_ids[]" multiple
                                        required>
                                        @foreach (\App\Models\Supplier::orderBy('name')->get() as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ in_array($supplier->id, request('supplier_ids', [])) ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">Compare</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (!empty($comparison))
            <!-- Performance Comparison Chart -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Performance Comparison</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="performanceComparison" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Comparison -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Detailed Comparison</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Metric</th>
                                            @foreach ($comparison as $supplierId => $data)
                                                <th>{{ $data['name'] }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Classification</th>
                                            @foreach ($comparison as $data)
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ ucfirst($data['classification']) }}
                                                    </span>
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Critical Supplier</th>
                                            @foreach ($comparison as $data)
                                                <td>
                                                    @if ($data['is_critical'])
                                                        <span class="badge badge-warning">Yes</span>
                                                    @else
                                                        <span class="badge badge-secondary">No</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Quality Score</th>
                                            @foreach ($comparison as $data)
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar {{ $data['performance']['quality'] >= 60 ? 'bg-success' : 'bg-danger' }}"
                                                            role="progressbar"
                                                            style="width: {{ $data['performance']['quality'] }}%">
                                                            {{ $data['performance']['quality'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Delivery Score</th>
                                            @foreach ($comparison as $data)
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar {{ $data['performance']['delivery'] >= 60 ? 'bg-success' : 'bg-danger' }}"
                                                            role="progressbar"
                                                            style="width: {{ $data['performance']['delivery'] }}%">
                                                            {{ $data['performance']['delivery'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Service Score</th>
                                            @foreach ($comparison as $data)
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar {{ $data['performance']['service'] >= 60 ? 'bg-success' : 'bg-danger' }}"
                                                            role="progressbar"
                                                            style="width: {{ $data['performance']['service'] }}%">
                                                            {{ $data['performance']['service'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Overall Score</th>
                                            @foreach ($comparison as $data)
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar {{ $data['performance']['overall'] >= 60 ? 'bg-success' : 'bg-danger' }}"
                                                            role="progressbar"
                                                            style="width: {{ $data['performance']['overall'] }}%">
                                                            {{ $data['performance']['overall'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Active Contracts</th>
                                            @foreach ($comparison as $data)
                                                <td>{{ $data['active_contracts'] }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Total Evaluations</th>
                                            @foreach ($comparison as $data)
                                                <td>{{ $data['total_evaluations'] }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Average Rating</th>
                                            @foreach ($comparison as $data)
                                                <td>{{ number_format($data['average_rating'], 1) }}/5</td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Select2
                $('.select2').select2({
                    placeholder: 'Select suppliers to compare',
                    maximumSelectionLength: 5
                });

                @if (!empty($comparison))
                    // Performance Comparison Chart
                    const ctx = document.getElementById('performanceComparison').getContext('2d');
                    const comparisonData = @json($comparison);

                    new Chart(ctx, {
                        type: 'radar',
                        data: {
                            labels: ['Quality', 'Delivery', 'Service', 'Overall'],
                            datasets: Object.values(comparisonData).map((data, index) => ({
                                label: data.name,
                                data: [
                                    data.performance.quality,
                                    data.performance.delivery,
                                    data.performance.service,
                                    data.performance.overall
                                ],
                                borderColor: getColor(index),
                                backgroundColor: getColor(index, 0.2)
                            }))
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                r: {
                                    min: 0,
                                    max: 100,
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 20
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                @endif
            });

            function getColor(index, alpha = 1) {
                const colors = [
                    `rgba(255, 99, 132, ${alpha})`,
                    `rgba(54, 162, 235, ${alpha})`,
                    `rgba(255, 206, 86, ${alpha})`,
                    `rgba(75, 192, 192, ${alpha})`,
                    `rgba(153, 102, 255, ${alpha})`
                ];
                return colors[index % colors.length];
            }
        </script>

        <style>
            .select2-container {
                width: 100% !important;
            }
        </style>
    @endpush
@endsection
