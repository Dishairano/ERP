@extends('layouts/contentNavbarLayout')

@section('title', 'Risk Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Project Risks</h5>
                        <div>
                            <a href="{{ route('projects.risks.matrix') }}" class="btn btn-secondary me-2">
                                <i class="ri-grid-line me-1"></i> Risk Matrix
                            </a>
                            <a href="{{ route('projects.risks.report') }}" class="btn btn-info me-2">
                                <i class="ri-file-chart-line me-1"></i> Risk Report
                            </a>
                            <a href="{{ route('projects.risks.create') }}" class="btn btn-primary">
                                <i class="ri-add-line me-1"></i> Add Risk
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Project</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Risk Level</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th>Owner</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($risks as $risk)
                                        <tr>
                                            <td>{{ $risk->id }}</td>
                                            <td>{{ $risk->project->name }}</td>
                                            <td>
                                                <a href="{{ route('projects.risks.show', $risk) }}">
                                                    {{ $risk->title }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-label-primary">{{ $risk->category }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $levelClass = match ($risk->risk_level) {
                                                        'High' => 'danger',
                                                        'Medium' => 'warning',
                                                        'Low' => 'success',
                                                        default => 'primary',
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $levelClass }}">
                                                    {{ $risk->risk_level }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match ($risk->status) {
                                                        'identified' => 'info',
                                                        'assessed' => 'warning',
                                                        'mitigated' => 'success',
                                                        'closed' => 'secondary',
                                                        default => 'primary',
                                                    };
                                                @endphp
                                                <span class="badge bg-label-{{ $statusClass }}">
                                                    {{ ucfirst($risk->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $risk->due_date->format('M d, Y') }}</td>
                                            <td>{{ $risk->owner }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('projects.risks.show', $risk) }}">
                                                            <i class="ri-eye-line me-1"></i> View
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('projects.risks.edit', $risk) }}">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </a>
                                                        <form action="{{ route('projects.risks.destroy', $risk) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('Are you sure you want to delete this risk?')">
                                                                <i class="ri-delete-bin-line me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No risks found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $risks->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
