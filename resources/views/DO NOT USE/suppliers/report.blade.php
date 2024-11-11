@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6>Total Suppliers</h6>
                        <h2>{{ $report['total_suppliers'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6>Active Contracts</h6>
                        <h2>{{ $report['active_contracts'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6>Expiring Contracts</h6>
                        <h2>{{ $report['expiring_contracts'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6>Average Performance</h6>
                        <h2>{{ $report['average_performance'] }}%</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Suppliers by Classification</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="suppliersByClassification" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Critical vs Non-Critical Suppliers</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="criticalSuppliers" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Filters</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('suppliers.report') }}" method="GET" class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="classification">Classification</label>
                                    <select class="form-control" id="classification" name="classification">
                                        <option value="">All Classifications</option>
                                        <option value="strategic"
                                            {{ request('classification') == 'strategic' ? 'selected' : '' }}>Strategic
                                        </option>
                                        <option value="tactical"
                                            {{ request('classification') == 'tactical' ? 'selected' : '' }}>Tactical
                                        </option>
                                        <option value="operational"
                                            {{ request('classification') == 'operational' ? 'selected' : '' }}>Operational
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="is_critical">Critical Status</label>
                                    <select class="form-control" id="is_critical" name="is_critical">
                                        <option value="">All Suppliers</option>
                                        <option value="1" {{ request('is_critical') == '1' ? 'selected' : '' }}>
                                            Critical Only</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="performance_threshold">Performance Threshold</label>
                                    <input type="number" class="form-control" id="performance_threshold"
                                        name="performance_threshold" min="0" max="100" step="1"
                                        value="{{ request('performance_threshold') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suppliers Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Supplier Details</h5>
                        <button type="button" class="btn btn-primary" onclick="exportToExcel()">
                            <i class="fas fa-download"></i> Export to Excel
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="suppliersTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Classification</th>
                                        <th>Critical</th>
                                        <th>Active Contracts</th>
                                        <th>Quality Score</th>
                                        <th>Delivery Score</th>
                                        <th>Service Score</th>
                                        <th>Overall Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suppliers as $supplier)
                                        <tr>
                                            <td>
                                                <a href="{{ route('suppliers.show', $supplier) }}">
                                                    {{ $supplier->name }}
                                                </a>
                                            </td>
                                            <td>{{ ucfirst($supplier->classification) }}</td>
                                            <td>{{ $supplier->is_critical ? 'Yes' : 'No' }}</td>
                                            <td>{{ $supplier->contracts->where('status', 'active')->count() }}</td>
                                            <td>{{ $supplier->quality_score }}%</td>
                                            <td>{{ $supplier->delivery_score }}%</td>
                                            <td>{{ $supplier->service_score }}%</td>
                                            <td>{{ round(($supplier->quality_score + $supplier->delivery_score + $supplier->service_score) / 3) }}%
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Suppliers by Classification Chart
                const classificationCtx = document.getElementById('suppliersByClassification').getContext('2d');
                const classificationData = @json($report['suppliers_by_classification']);

                new Chart(classificationCtx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(classificationData).map(key => key.charAt(0).toUpperCase() + key
                            .slice(1)),
                        datasets: [{
                            data: Object.values(classificationData),
                            backgroundColor: ['#28a745', '#17a2b8', '#ffc107']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Critical Suppliers Chart
                const criticalCtx = document.getElementById('criticalSuppliers').getContext('2d');
                const criticalData = {
                    'Critical': {{ $report['critical_suppliers'] }},
                    'Non-Critical': {{ $report['total_suppliers'] - $report['critical_suppliers'] }}
                };

                new Chart(criticalCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(criticalData),
                        datasets: [{
                            data: Object.values(criticalData),
                            backgroundColor: ['#dc3545', '#28a745']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            });

            function exportToExcel() {
                // Get the table
                const table = document.getElementById('suppliersTable');

                // Convert table to CSV
                let csv = [];
                for (let i = 0; i < table.rows.length; i++) {
                    let row = [],
                        cols = table.rows[i].cells;
                    for (let j = 0; j < cols.length; j++) {
                        // Get the text content, removing any commas
                        row.push('"' + cols[j].textContent.trim().replace(/"/g, '""') + '"');
                    }
                    csv.push(row.join(','));
                }

                // Create CSV file
                const csvContent = csv.join('\n');
                const blob = new Blob([csvContent], {
                    type: 'text/csv;charset=utf-8;'
                });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);

                // Set up download
                link.setAttribute('href', url);
                link.setAttribute('download', 'supplier_report.csv');
                link.style.visibility = 'hidden';
                document.body.appendChild(link);

                // Start download and cleanup
                link.click();
                document.body.removeChild(link);
            }
        </script>
    @endpush
@endsection
