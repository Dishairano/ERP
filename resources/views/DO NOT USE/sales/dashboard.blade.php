@extends('layouts/contentNavbarLayout')

@section('title', 'Sales Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Total Orders</h6>
                                <h2 class="mb-0">{{ $totalOrders }}</h2>
                            </div>
                            <div class="avatar avatar-lg">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ri-shopping-cart-line fs-3"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Total Revenue</h6>
                                <h2 class="mb-0">{{ number_format($totalRevenue, 2) }}</h2>
                            </div>
                            <div class="avatar avatar-lg">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ri-money-dollar-circle-line fs-3"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Pending Orders</h6>
                                <h2 class="mb-0">{{ $pendingOrders }}</h2>
                            </div>
                            <div class="avatar avatar-lg">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ri-time-line fs-3"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Monthly Revenue</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyRevenueChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Orders</h5>
                        <a href="{{ route('sales.orders') }}" class="btn btn-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->customer->name }}</td>
                                        <td>{{ $order->items->count() }}</td>
                                        <td>{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'processing' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthlyRevenue = @json($monthlyRevenue);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const data = Array(12).fill(0);

            monthlyRevenue.forEach(item => {
                data[item.month - 1] = item.revenue;
            });

            const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Revenue',
                        data: data,
                        borderColor: '#696cff',
                        tension: 0.4,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Revenue: ' + context.raw.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
