@extends('layouts/contentNavbarLayout')

@section('title', 'Create Analysis')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Analysis</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('data-analysis.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="name">Analysis Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="type">Analysis Type</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="sales">Sales Analysis</option>
                                        <option value="finance">Financial Analysis</option>
                                        <option value="inventory">Inventory Analysis</option>
                                        <option value="hr">HR Analysis</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <h5>Analysis Criteria</h5>
                            </div>
                        </div>

                        <div id="criteria-container">
                            <!-- Dynamic criteria fields will be loaded here based on type selection -->
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Create Analysis</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
@endsection

@section('page-script')
    <script>
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            const container = document.getElementById('criteria-container');

            // Clear existing criteria fields
            container.innerHTML = '';

            // Add criteria fields based on type
            switch (type) {
                case 'sales':
                    container.innerHTML = `
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label">Date Range</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="criteria[date_range][]">
                                <input type="date" class="form-control" name="criteria[date_range][]">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label">Region</label>
                            <select class="form-select" name="criteria[region]">
                                <option value="">All Regions</option>
                                <option value="north">North</option>
                                <option value="south">South</option>
                                <option value="east">East</option>
                                <option value="west">West</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
                    break;

                case 'finance':
                    container.innerHTML = `
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label">Date Range</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="criteria[date_range][]">
                                <input type="date" class="form-control" name="criteria[date_range][]">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="criteria[department]">
                                <option value="">All Departments</option>
                                <!-- Add departments dynamically -->
                            </select>
                        </div>
                    </div>
                </div>
            `;
                    break;

                case 'inventory':
                    container.innerHTML = `
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label">Supplier</label>
                            <select class="form-select" name="criteria[supplier]">
                                <option value="">All Suppliers</option>
                                <!-- Add suppliers dynamically -->
                            </select>
                        </div>
                    </div>
                </div>
            `;
                    break;

                case 'hr':
                    container.innerHTML = `
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label">Date Range</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="criteria[date_range][]">
                                <input type="date" class="form-control" name="criteria[date_range][]">
                            </div>
                        </div>
                    </div>
                </div>
            `;
                    break;
            }
        });
    </script>
@endsection
