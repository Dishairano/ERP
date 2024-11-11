@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Leveranciers</h4>
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nieuwe Leverancier
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Naam</th>
                                        <th>Classificatie</th>
                                        <th>Status</th>
                                        <th>Kwaliteit Score</th>
                                        <th>Levering Score</th>
                                        <th>Service Score</th>
                                        <th>Actieve Contracten</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suppliers as $supplier)
                                        <tr>
                                            <td>
                                                <a href="{{ route('suppliers.show', $supplier) }}">
                                                    {{ $supplier->name }}
                                                    @if ($supplier->is_critical)
                                                        <span class="badge badge-warning">Kritiek</span>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-info">{{ ucfirst($supplier->classification) }}</span>
                                            </td>
                                            <td>
                                                @if ($supplier->status === 'active')
                                                    <span class="badge badge-success">Actief</span>
                                                @else
                                                    <span class="badge badge-danger">Inactief</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar {{ $supplier->quality_score >= 60 ? 'bg-success' : 'bg-danger' }}"
                                                        role="progressbar" style="width: {{ $supplier->quality_score }}%"
                                                        aria-valuenow="{{ $supplier->quality_score }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ $supplier->quality_score }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar {{ $supplier->delivery_score >= 60 ? 'bg-success' : 'bg-danger' }}"
                                                        role="progressbar" style="width: {{ $supplier->delivery_score }}%"
                                                        aria-valuenow="{{ $supplier->delivery_score }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ $supplier->delivery_score }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar {{ $supplier->service_score >= 60 ? 'bg-success' : 'bg-danger' }}"
                                                        role="progressbar" style="width: {{ $supplier->service_score }}%"
                                                        aria-valuenow="{{ $supplier->service_score }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ $supplier->service_score }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $supplier->contracts->count() }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('suppliers.show', $supplier) }}"
                                                        class="btn btn-sm btn-info" title="Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('suppliers.edit', $supplier) }}"
                                                        class="btn btn-sm btn-warning" title="Bewerken">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('suppliers.performance', $supplier) }}"
                                                        class="btn btn-sm btn-success" title="Prestaties">
                                                        <i class="fas fa-chart-line"></i>
                                                    </a>
                                                    <a href="{{ route('suppliers.contracts', $supplier) }}"
                                                        class="btn btn-sm btn-primary" title="Contracten">
                                                        <i class="fas fa-file-contract"></i>
                                                    </a>
                                                </div>
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

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Leveranciers per Classificatie</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="suppliersByClassification"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Prestatie Overzicht</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="performanceOverview"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Suppliers by Classification Chart
                const classificationCtx = document.getElementById('suppliersByClassification').getContext('2d');
                const classificationData = {
                    strategic: {{ $suppliers->where('classification', 'strategic')->count() }},
                    tactical: {{ $suppliers->where('classification', 'tactical')->count() }},
                    operational: {{ $suppliers->where('classification', 'operational')->count() }}
                };

                new Chart(classificationCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Strategisch', 'Tactisch', 'Operationeel'],
                        datasets: [{
                            data: Object.values(classificationData),
                            backgroundColor: ['#28a745', '#17a2b8', '#ffc107']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                // Performance Overview Chart
                const performanceCtx = document.getElementById('performanceOverview').getContext('2d');
                const performanceData = {
                    quality: {{ $suppliers->avg('quality_score') }},
                    delivery: {{ $suppliers->avg('delivery_score') }},
                    service: {{ $suppliers->avg('service_score') }}
                };

                new Chart(performanceCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Kwaliteit', 'Levering', 'Service'],
                        datasets: [{
                            label: 'Gemiddelde Score',
                            data: Object.values(performanceData),
                            backgroundColor: ['#28a745', '#17a2b8', '#ffc107']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
