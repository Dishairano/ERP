@extends('layouts/contentNavbarLayout')

@section('title', 'Create Demand Planning Scenario')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Create New Scenario</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('demand-planning.scenarios.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="scenario_name">Scenario Name</label>
                                    <input type="text" class="form-control @error('scenario_name') is-invalid @enderror"
                                        id="scenario_name" name="scenario_name" required>
                                    @error('scenario_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" required></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <h6>Scenario Factors</h6>
                                    <div id="scenario-factors">
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <label class="form-label">Factor Name</label>
                                                <input type="text" name="scenario_factors[0][name]" class="form-control"
                                                    required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Value</label>
                                                <input type="number" step="0.01" name="scenario_factors[0][value]"
                                                    class="form-control" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Impact (%)</label>
                                                <input type="number" step="0.1" name="scenario_factors[0][impact]"
                                                    class="form-control" required>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-factor"
                                                    style="display: none;">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-sm" id="add-factor">
                                        <i class="bx bx-plus"></i> Add Factor
                                    </button>
                                </div>

                                <div class="col-12 mb-3">
                                    <h6>Market Trends</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Include</th>
                                                    <th>Trend</th>
                                                    <th>Impact</th>
                                                    <th>Period</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($marketTrends as $trend)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="included_trends[]"
                                                                value="{{ $trend->id }}" class="form-check-input">
                                                        </td>
                                                        <td>{{ $trend->trend_name }}</td>
                                                        <td>{{ number_format($trend->impact_factor * 100, 1) }}%</td>
                                                        <td>{{ $trend->start_date->format('Y-m-d') }} to
                                                            {{ $trend->end_date->format('Y-m-d') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Create Scenario</button>
                                    <a href="{{ route('demand-planning.scenarios') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scenarioFactors = document.getElementById('scenario-factors');
            const addFactorBtn = document.getElementById('add-factor');
            let factorCount = 1;

            addFactorBtn.addEventListener('click', function() {
                const factorRow = document.createElement('div');
                factorRow.className = 'row mb-2';
                factorRow.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Factor Name</label>
                <input type="text" name="scenario_factors[${factorCount}][name]" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Value</label>
                <input type="number" step="0.01" name="scenario_factors[${factorCount}][value]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Impact (%)</label>
                <input type="number" step="0.1" name="scenario_factors[${factorCount}][impact]" class="form-control" required>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-factor">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        `;
                scenarioFactors.appendChild(factorRow);
                factorCount++;

                // Show remove button for first factor if there's more than one
                if (factorCount > 1) {
                    scenarioFactors.querySelector('.remove-factor').style.display = 'block';
                }
            });

            scenarioFactors.addEventListener('click', function(e) {
                if (e.target.closest('.remove-factor')) {
                    e.target.closest('.row').remove();
                    factorCount--;

                    // Hide remove button for first factor if it's the only one
                    if (factorCount === 1) {
                        scenarioFactors.querySelector('.remove-factor').style.display = 'none';
                    }
                }
            });
        });
    </script>
@endsection
