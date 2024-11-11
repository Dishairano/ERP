@extends('layouts/contentNavbarLayout')

@section('title', 'BOM Versions')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Bill of Materials /</span> Versions
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">BOM Version History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Version</th>
                                <th>Created Date</th>
                                <th>Created By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($boms as $bom)
                                <tr>
                                    <td>{{ $bom->id }}</td>
                                    <td>{{ $bom->name }}</td>
                                    <td>{{ $bom->version }}</td>
                                    <td>{{ $bom->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $bom->created_by }}</td>
                                    <td>{{ $bom->status }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-eye-line me-2"></i> View
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-git-branch-line me-2"></i> Create New Version
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-history-line me-2"></i> View History
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $boms->links() }}
            </div>
        </div>
    </div>
@endsection
