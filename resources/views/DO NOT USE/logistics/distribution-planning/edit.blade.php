@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Distribution Plan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Edit Distribution Plan</h5>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('logistics.distribution-planning.update', $distributionPlanning->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="plan_name" class="form-label">Plan Name</label>
                    <input type="text" class="form-control" id="plan_name" name="plan_name"
                        value="{{ old('plan_name', $distributionPlanning->plan_name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $distributionPlanning->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="{{ old('start_date', $distributionPlanning->start_date->format('Y-m-d')) }}" required>
                </div>

                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="{{ old('end_date', $distributionPlanning->end_date->format('Y-m-d')) }}" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="draft"
                            {{ old('status', $distributionPlanning->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active"
                            {{ old('status', $distributionPlanning->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed"
                            {{ old('status', $distributionPlanning->status) == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Plan</button>
                    <a href="{{ route('logistics.distribution-planning.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
