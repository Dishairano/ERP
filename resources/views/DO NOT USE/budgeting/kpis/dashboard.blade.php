@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Budget KPIs Dashboard</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($kpis as $kpi)
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ $kpi->name }}</h5>
                                            <small>{{ $kpi->budget->category_name }}</small>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Target:</span>
                                                <strong>{{ number_format($kpi->target_value, 2) }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Actual:</span>
                                                <strong>{{ number_format($kpi->actual_value, 2) }}</strong>
                                            </div>
                                            <div class="progress">
                                                @php
                                                    $percentage = ($kpi->actual_value / $kpi->target_value) * 100;
                                                    $colorClass =
                                                        $percentage > 100
                                                            ? 'bg-danger'
                                                            : ($percentage > 80
                                                                ? 'bg-warning'
                                                                : 'bg-success');
                                                @endphp
                                                <div class="progress-bar {{ $colorClass }}" role="progressbar"
                                                    style="width: {{ min($percentage, 100) }}%">
                                                    {{ number_format($percentage, 1) }}%
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <span
                                                    class="badge badge-{{ $kpi->status === 'critical' ? 'danger' : ($kpi->status === 'warning' ? 'warning' : 'success') }}">
                                                    {{ ucfirst($kpi->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
