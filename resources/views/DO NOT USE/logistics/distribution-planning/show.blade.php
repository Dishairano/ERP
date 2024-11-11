@extends('layouts/contentNavbarLayout')

@section('title', 'View Distribution Plan')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Distribution Plan Details</h5>
            <div>
                <a href="{{ route('logistics.distribution-planning.edit', $distributionPlanning->id) }}"
                    class="btn btn-warning">Edit</a>
                <a href="{{ route('logistics.distribution-planning.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Plan Name:</div>
                <div class="col-md-9">{{ $distributionPlanning->plan_name }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Description:</div>
                <div class="col-md-9">{{ $distributionPlanning->description ?? 'N/A' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Start Date:</div>
                <div class="col-md-9">{{ $distributionPlanning->start_date->format('Y-m-d') }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">End Date:</div>
                <div class="col-md-9">{{ $distributionPlanning->end_date->format('Y-m-d') }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Status:</div>
                <div class="col-md-9">
                    <span
                        class="badge bg-{{ $distributionPlanning->status === 'completed' ? 'success' : ($distributionPlanning->status === 'active' ? 'primary' : 'secondary') }}">
                        {{ ucfirst($distributionPlanning->status) }}
                    </span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Created At:</div>
                <div class="col-md-9">{{ $distributionPlanning->created_at->format('Y-m-d H:i:s') }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Last Updated:</div>
                <div class="col-md-9">{{ $distributionPlanning->updated_at->format('Y-m-d H:i:s') }}</div>
            </div>
        </div>
    </div>
@endsection
