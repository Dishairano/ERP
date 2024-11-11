@extends('layouts/contentNavbarLayout')

@section('title', 'Inventory Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Stock Value</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">${{ number_format($stockValueStats->total_value, 2) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Items</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($stockValueStats->total_items) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-stack-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Low Stock Items</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $lowStockItems->count() }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-alert-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Warehouses</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($stockValueStats->total_warehouses) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="ri-building-2-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Low Stock Items -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Low Stock Items</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Warehouse</th>
                                    <th>Quantity</th>
                                    <th>Reorder Point</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lowStockItems as $stock)
                                    <tr>
                                        <td>{{ $stock->item->name }}</td>
                                        <td>{{ $stock->warehouse->name }}</td>
                                        <td>
                                            <span class="badge bg-label-danger">
                                                {{ number_format($stock->quantity) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($stock->reorder_point) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Movements -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Stock Movements</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentMovements as $movement)
                                    <tr>
                                        <td>{{ $movement->created_at->format('M d, H:i') }}</td>
                                        <td>{{ $movement->item->name }}</td>
                                        <td>
                                            <span
                                                class="badge bg-label-{{ $movement->type === 'incoming' ? 'success' : 'warning' }}">
                                                {{ ucfirst($movement->type) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($movement->quantity) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Stock by Warehouse -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Stock by Warehouse</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Warehouse</th>
                                        <th>Items</th>
                                        <th>Total Quantity</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockByWarehouse as $warehouse)
                                        <tr>
                                            <td>{{ $warehouse->name }}</td>
                                            <td>{{ number_format($warehouse->items_count) }}</td>
                                            <td>{{ number_format($warehouse->total_quantity) }}</td>
                                            <td>${{ number_format($warehouse->total_value, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Movement Trends -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Movement Trends</h5>
                    </div>
                    <div class="card-body">
                        <div id="movementTrendsChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Items Needing Count -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Items Needing Count</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Warehouse</th>
                                    <th>Last Count</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($needsCounting as $stock)
                                    <tr>
                                        <td>{{ $stock->item->name }}</td>
                                        <td>{{ $stock->warehouse->name }}</td>
                                        <td>
                                            {{ $stock->last_counted_at ? $stock->last_counted_at->diffForHumans() : 'Never' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-label-warning">
                                                Needs Count
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Adjustments -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Adjustments</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item</th>
                                    <th>Adjustment</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentAdjustments as $adjustment)
                                    <tr>
                                        <td>{{ $adjustment->created_at->format('M d, H:i') }}</td>
                                        <td>{{ $adjustment->item->name }}</td>
                                        <td>
                                            <span
                                                class="badge bg-label-{{ $adjustment->quantity > 0 ? 'success' : 'danger' }}">
                                                {{ $adjustment->quantity > 0 ? '+' : '' }}{{ number_format($adjustment->quantity) }}
                                            </span>
                                        </td>
                                        <td>{{ $adjustment->reason }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Movement Trends Chart
            const movementTrends = @json($movementTrends);
            const dates = movementTrends.map(trend => trend.date);
            const incoming = movementTrends.map(trend => trend.incoming);
            const outgoing = movementTrends.map(trend => trend.outgoing);

            const options = {
                chart: {
                    type: 'line',
                    height: 300
                },
                series: [{
                        name: 'Incoming',
                        data: incoming
                    },
                    {
                        name: 'Outgoing',
                        data: outgoing
                    }
                ],
                xaxis: {
                    categories: dates,
                    type: 'datetime'
                },
                yaxis: {
                    title: {
                        text: 'Quantity'
                    }
                },
                colors: ['#28c76f', '#ea5455'],
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                markers: {
                    size: 4
                }
            };

            const chart = new ApexCharts(document.querySelector("#movementTrendsChart"), options);
            chart.render();

            // Refresh data periodically
            setInterval(function() {
                fetch('/inventory/movement-trends')
                    .then(response => response.json())
                    .then(data => {
                        chart.updateSeries([{
                                name: 'Incoming',
                                data: data.map(trend => trend.incoming)
                            },
                            {
                                name: 'Outgoing',
                                data: data.map(trend => trend.outgoing)
                            }
                        ]);
                    });
            }, 60000); // Refresh every minute
        });
    </script>
@endsection
