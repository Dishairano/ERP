@extends('layouts/contentNavbarLayout')

@section('title', 'Demand Planning Scenarios')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Demand Planning Scenarios</h5>
                        <a href="{{ route('demand-planning.scenarios.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> New Scenario
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Scenario Name</th>
                                        <th>Description</th>
                                        <th>Created By</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scenarios as $scenario)
                                        <tr>
                                            <td>{{ $scenario->scenario_name }}</td>
                                            <td>{{ Str::limit($scenario->description, 50) }}</td>
                                            <td>{{ $scenario->creator->name }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-label-{{ $scenario->is_active ? 'success' : 'secondary' }}">
                                                    {{ $scenario->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $scenario->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#viewScenarioModal{{ $scenario->id }}">
                                                            <i class="bx bx-show-alt me-1"></i> View Details
                                                        </button>
                                                        @if (!$scenario->is_active)
                                                            <form
                                                                action="{{ route('demand-planning.scenarios.activate', $scenario) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="bx bx-play-circle me-1"></i> Activate
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Scenario Details Modal -->
                                        <div class="modal fade" id="viewScenarioModal{{ $scenario->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Scenario Details:
                                                            {{ $scenario->scenario_name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-12 mb-3">
                                                                <h6>Description</h6>
                                                                <p>{{ $scenario->description }}</p>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <h6>Scenario Factors</h6>
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Factor</th>
                                                                                <th>Value</th>
                                                                                <th>Impact</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($scenario->scenario_factors as $factor)
                                                                                <tr>
                                                                                    <td>{{ $factor['name'] }}</td>
                                                                                    <td>{{ $factor['value'] }}</td>
                                                                                    <td>{{ number_format($factor['impact'] * 100, 1) }}%
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <h6>Results</h6>
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Metric</th>
                                                                                <th>Base Value</th>
                                                                                <th>Scenario Value</th>
                                                                                <th>Change</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($scenario->results as $result)
                                                                                <tr>
                                                                                    <td>{{ $result['metric'] }}</td>
                                                                                    <td>{{ number_format($result['base_value'], 2) }}
                                                                                    </td>
                                                                                    <td>{{ number_format($result['scenario_value'], 2) }}
                                                                                    </td>
                                                                                    <td>
                                                                                        @php
                                                                                            $change =
                                                                                                (($result[
                                                                                                    'scenario_value'
                                                                                                ] -
                                                                                                    $result[
                                                                                                        'base_value'
                                                                                                    ]) /
                                                                                                    $result[
                                                                                                        'base_value'
                                                                                                    ]) *
                                                                                                100;
                                                                                        @endphp
                                                                                        <span
                                                                                            class="text-{{ $change >= 0 ? 'success' : 'danger' }}">
                                                                                            {{ number_format($change, 1) }}%
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
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
                            {{ $scenarios->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
