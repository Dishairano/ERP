@extends('layouts/contentNavbarLayout')

@section('title', 'Edit KPI')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit KPI</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('kpi.update', $kpi) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">KPI Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $kpi->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="category">Category</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category"
                                        name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="financial"
                                            {{ old('category', $kpi->category) == 'financial' ? 'selected' : '' }}>Financial
                                        </option>
                                        <option value="operational"
                                            {{ old('category', $kpi->category) == 'operational' ? 'selected' : '' }}>
                                            Operational</option>
                                        <option value="hr"
                                            {{ old('category', $kpi->category) == 'hr' ? 'selected' : '' }}>Human Resources
                                        </option>
                                        <option value="sales"
                                            {{ old('category', $kpi->category) == 'sales' ? 'selected' : '' }}>Sales
                                        </option>
                                        <option value="customer"
                                            {{ old('category', $kpi->category) == 'customer' ? 'selected' : '' }}>Customer
                                        </option>
                                        <option value="project"
                                            {{ old('category', $kpi->category) == 'project' ? 'selected' : '' }}>Project
                                        </option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="unit">Unit</label>
                                    <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                        id="unit" name="unit" value="{{ old('unit', $kpi->unit) }}" required>
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="calculation_method">Calculation Method</label>
                                    <select class="form-select @error('calculation_method') is-invalid @enderror"
                                        id="calculation_method" name="calculation_method" required>
                                        <option value="">Select Method</option>
                                        <option value="sum"
                                            {{ old('calculation_method', $kpi->calculation_method) == 'sum' ? 'selected' : '' }}>
                                            Sum</option>
                                        <option value="average"
                                            {{ old('calculation_method', $kpi->calculation_method) == 'average' ? 'selected' : '' }}>
                                            Average</option>
                                        <option value="count"
                                            {{ old('calculation_method', $kpi->calculation_method) == 'count' ? 'selected' : '' }}>
                                            Count</option>
                                        <option value="percentage"
                                            {{ old('calculation_method', $kpi->calculation_method) == 'percentage' ? 'selected' : '' }}>
                                            Percentage</option>
                                        <option value="custom"
                                            {{ old('calculation_method', $kpi->calculation_method) == 'custom' ? 'selected' : '' }}>
                                            Custom Formula</option>
                                    </select>
                                    @error('calculation_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="data_source">Data Source</label>
                                    <input type="text" class="form-control @error('data_source') is-invalid @enderror"
                                        id="data_source" name="data_source"
                                        value="{{ old('data_source', $kpi->data_source) }}" required>
                                    @error('data_source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="frequency">Update Frequency</label>
                                    <select class="form-select @error('frequency') is-invalid @enderror" id="frequency"
                                        name="frequency" required>
                                        <option value="">Select Frequency</option>
                                        <option value="daily"
                                            {{ old('frequency', $kpi->frequency) == 'daily' ? 'selected' : '' }}>Daily
                                        </option>
                                        <option value="weekly"
                                            {{ old('frequency', $kpi->frequency) == 'weekly' ? 'selected' : '' }}>Weekly
                                        </option>
                                        <option value="monthly"
                                            {{ old('frequency', $kpi->frequency) == 'monthly' ? 'selected' : '' }}>Monthly
                                        </option>
                                        <option value="quarterly"
                                            {{ old('frequency', $kpi->frequency) == 'quarterly' ? 'selected' : '' }}>
                                            Quarterly</option>
                                        <option value="yearly"
                                            {{ old('frequency', $kpi->frequency) == 'yearly' ? 'selected' : '' }}>Yearly
                                        </option>
                                    </select>
                                    @error('frequency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" required>{{ old('description', $kpi->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5 class="mt-4">Thresholds</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="thresholds[warning_threshold]">Warning
                                                Threshold</label>
                                            <input type="number" step="0.01" class="form-control"
                                                id="thresholds[warning_threshold]" name="thresholds[warning_threshold]"
                                                value="{{ old('thresholds.warning_threshold', $kpi->thresholds->first()->warning_threshold ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="thresholds[critical_threshold]">Critical
                                                Threshold</label>
                                            <input type="number" step="0.01" class="form-control"
                                                id="thresholds[critical_threshold]" name="thresholds[critical_threshold]"
                                                value="{{ old('thresholds.critical_threshold', $kpi->thresholds->first()->critical_threshold ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="thresholds[comparison_operator]">Comparison
                                                Operator</label>
                                            <select class="form-select" id="thresholds[comparison_operator]"
                                                name="thresholds[comparison_operator]">
                                                <option value="greater_than"
                                                    {{ old('thresholds.comparison_operator', $kpi->thresholds->first()->comparison_operator ?? '') == 'greater_than' ? 'selected' : '' }}>
                                                    Greater Than</option>
                                                <option value="less_than"
                                                    {{ old('thresholds.comparison_operator', $kpi->thresholds->first()->comparison_operator ?? '') == 'less_than' ? 'selected' : '' }}>
                                                    Less Than</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-1">Update KPI</button>
                                <a href="{{ route('kpi.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add any client-side validation or dynamic form behavior here
            const categorySelect = document.getElementById('category');
            const unitInput = document.getElementById('unit');

            categorySelect.addEventListener('change', function() {
                if (!unitInput.value) {
                    // Only suggest default units if the unit field is empty
                    switch (this.value) {
                        case 'financial':
                            unitInput.value = 'EUR';
                            break;
                        case 'operational':
                            unitInput.value = 'hours';
                            break;
                        case 'hr':
                            unitInput.value = 'percentage';
                            break;
                        case 'sales':
                            unitInput.value = 'EUR';
                            break;
                        case 'customer':
                            unitInput.value = 'rating';
                            break;
                        case 'project':
                            unitInput.value = 'percentage';
                            break;
                    }
                }
            });
        });
    </script>
@endsection
