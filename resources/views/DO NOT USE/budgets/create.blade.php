@extends('layouts/contentNavbarLayout')

@section('title', 'Create Budget')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Create Budget</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('budgets.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Type</label>
                                    <select class="form-select @error('type') is-invalid @enderror" name="type" required
                                        onchange="toggleTypeFields(this.value)">
                                        <option value="">Select Type</option>
                                        <option value="department" {{ old('type') === 'department' ? 'selected' : '' }}>
                                            Department</option>
                                        <option value="project" {{ old('type') === 'project' ? 'selected' : '' }}>Project
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6" id="departmentField" style="display: none;">
                                    <label class="form-label">Department</label>
                                    <select class="form-select @error('department_id') is-invalid @enderror"
                                        name="department_id">
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6" id="projectField" style="display: none;">
                                    <label class="form-label">Project</label>
                                    <select class="form-select @error('project_id') is-invalid @enderror" name="project_id">
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fiscal Year</label>
                                    <input type="number" class="form-control @error('fiscal_year') is-invalid @enderror"
                                        name="fiscal_year" value="{{ old('fiscal_year', date('Y')) }}" required>
                                    @error('fiscal_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                        name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Categories</label>
                                <div id="categoryFields">
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="categories[0][name]"
                                                placeholder="Category Name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control category-amount"
                                                name="categories[0][amount]" placeholder="Amount" step="0.01" required
                                                onchange="updateTotal()">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger" onclick="removeCategory(this)">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addCategory()">
                                    Add Category
                                </button>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Total Amount</label>
                                <input type="text" class="form-control" id="totalAmount" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end">
                                <a href="{{ route('budgets.index') }}" class="btn btn-label-secondary me-1">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Budget</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        let categoryCount = 1;

        function toggleTypeFields(type) {
            const departmentField = document.getElementById('departmentField');
            const projectField = document.getElementById('projectField');

            if (type === 'department') {
                departmentField.style.display = 'block';
                projectField.style.display = 'none';
                document.querySelector('[name="project_id"]').removeAttribute('required');
                document.querySelector('[name="department_id"]').setAttribute('required', 'required');
            } else if (type === 'project') {
                departmentField.style.display = 'none';
                projectField.style.display = 'block';
                document.querySelector('[name="department_id"]').removeAttribute('required');
                document.querySelector('[name="project_id"]').setAttribute('required', 'required');
            } else {
                departmentField.style.display = 'none';
                projectField.style.display = 'none';
                document.querySelector('[name="department_id"]').removeAttribute('required');
                document.querySelector('[name="project_id"]').removeAttribute('required');
            }
        }

        function addCategory() {
            const template = `
        <div class="row mb-2">
            <div class="col-md-6">
                <input type="text" class="form-control" name="categories[${categoryCount}][name]"
                    placeholder="Category Name" required>
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control category-amount" name="categories[${categoryCount}][amount]"
                    placeholder="Amount" step="0.01" required onchange="updateTotal()">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="removeCategory(this)">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    `;
            document.getElementById('categoryFields').insertAdjacentHTML('beforeend', template);
            categoryCount++;
        }

        function removeCategory(button) {
            button.closest('.row').remove();
            updateTotal();
        }

        function updateTotal() {
            const amounts = document.getElementsByClassName('category-amount');
            let total = 0;
            for (let amount of amounts) {
                total += parseFloat(amount.value || 0);
            }
            document.getElementById('totalAmount').value = total.toFixed(2);
        }

        // Initialize type fields on page load
        document.addEventListener('DOMContentLoaded', function() {
            const type = document.querySelector('[name="type"]').value;
            if (type) {
                toggleTypeFields(type);
            }
        });
    </script>
@endsection
