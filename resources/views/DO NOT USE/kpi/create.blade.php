@extends('layouts/contentNavbarLayout')

@section('title', 'Create KPI')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New KPI</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('kpi.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">KPI Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="code">KPI Code</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                        id="code" name="code" value="{{ old('code') }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="category">Category</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category"
                                        name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="financial" {{ old('category') == 'financial' ? 'selected' : '' }}>
                                            Financial</option>
                                        <option value="operational"
                                            {{ old('category') == 'operational' ? 'selected' : '' }}>Operational</option>
                                        <option value="hr" {{ old('category') == 'hr' ? 'selected' : '' }}>Human
                                            Resources</option>
                                        <option value="sales" {{ old('category') == 'sales' ? 'selected' : '' }}>Sales
                                        </option>
                                        <option value="customer" {{ old('category') == 'customer' ? 'selected' : '' }}>
                                            Customer</option>
                                        <option value="project" {{ old('category') == 'project' ? 'selected' : '' }}>
                                            Project</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="unit">Unit</label>
                                    <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                        id="unit" name="unit" value="{{ old('unit') }}" required>
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
                                        <option value="sum" {{ old('calculation_method') == 'sum' ? 'selected' : '' }}>
                                            Sum</option>
                                        <option value="average"
                                            {{ old('calculation_method') == 'average' ? 'selected' : '' }}>Average</option>
                                        <option value="count"
                                            {{ old('calculation_method') == 'count' ? 'selected' : '' }}>Count</option>
                                        <option value="percentage"
                                            {{ old('calculation_method') == 'percentage' ? 'selected' : '' }}>Percentage
                                        </option>
                                        <option value="custom"
                                            {{ old('calculation_method') == 'custom' ? 'selected' : '' }}>Custom Formula
                                        </option>
                                    </select>
                                    @error('calculation_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="data_source">Data Source</label>
                                    <input type="text" class="form-control @error('data_source') is-invalid @enderror"
                                        id="data_source" name="data_source" value="{{ old('data_source') }}" required>
                                    @error('data_source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="frequency">Update Frequency</label>
                                    <select class="form-select @error('frequency') is-invalid @enderror" id="frequency"
                                        name="frequency" required>
                                        <option value="">Select Frequency</option>
                                        <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Daily
                                        </option>
                                        <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly
                                        </option>
                                        <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>
                                            Monthly</option>
                                        <option value="quarterly" {{ old('frequency') == 'quarterly' ? 'selected' : '' }}>
                                            Quarterly</option>
                                        <option value="yearly" {{ old('frequency') == 'yearly' ? 'selected' : '' }}>Yearly
                                        </option>
                                    </select>
                                    @error('frequency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5 class="mt-4">Initial Thresholds</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="thresholds[warning_threshold]">Warning
                                                Threshold</label>
                                            <input type="number" step="0.01" class="form-control"
                                                id="thresholds[warning_threshold]" name="thresholds[warning_threshold]"
                                                value="{{ old('thresholds.warning_threshold') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="thresholds[critical_threshold]">Critical
                                                Threshold</label>
                                            <input type="number" step="0.01" class="form-control"
                                                id="thresholds[critical_threshold]" name="thresholds[critical_threshold]"
                                                value="{{ old('thresholds.critical_threshold') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="thresholds[comparison_operator]">Comparison
                                                Operator</label>
                                            <select class="form-select" id="thresholds[comparison_operator]"
                                                name="thresholds[comparison_operator]">
                                                <option value="greater_than"
                                                    {{ old('thresholds.comparison_operator') == 'greater_than' ? 'selected' : '' }}>
                                                    Greater Than</option>
                                                <option value="less_than"
                                                    {{ old('thresholds.comparison_operator') == 'less_than' ? 'selected' : '' }}>
                                                    Less Than</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-1">Create KPI</button>
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
                // Suggest default units based on category
                switch (this.value) {
                    case 'financial':
                        unitInput.value = unitInput.value || 'EUR';
                        break;
                    case 'operational':
                        unitInput.value = unitInput.value || 'hours';
                        break;
                    case 'hr':
                        unitInput.value = unitInput.value || 'percentage';
                        break;
                    case 'sales':
                        unitInput.value = unitInput.value || 'EUR';
                        break;
                    case 'customer':
                        unitInput.value = unitInput.value || 'rating';
                        break;
                    case 'project':
                        unitInput.value = unitInput.value || 'percentage';
                        break;
                }
            });
        });
    </script>
@endsection
