@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registrations')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Time Registrations</h5>
                        <a href="{{ route('time-registrations.create') }}" class="btn btn-primary">
                            <i class="ri-add-line"></i> New Time Entry
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Filters -->
                        <form action="{{ route('time-registrations.index') }}" method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Project</label>
                                    <select name="project" class="form-select">
                                        <option value="">All Projects</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" @selected(request('project') == $project->id)>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                                        <option value="approved" @selected(request('status') == 'approved')>Approved</option>
                                        <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-filter-2-line"></i> Apply Filters
                                    </button>
