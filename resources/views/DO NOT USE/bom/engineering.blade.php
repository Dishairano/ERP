@extends('layouts/contentNavbarLayout')

@section('title', 'BOM Engineering Changes')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Bill of Materials /</span> Engineering Changes
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Engineering Change Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ECO ID</th>
                                <th>BOM Name</th>
                                <th>Change Description</th>
                                <th>Requested By</th>
                                <th>Status</th>
                                <th>Approval Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($boms as $bom)
                                @foreach ($bom->changes as $change)
                                    <tr>
                                        <td>{{ $change->id }}</td>
                                        <td>{{ $bom->name }}</td>
                                        <td>{{ $change->description }}</td>
                                        <td>{{ $change->requested_by }}</td>
                                        <td>{{ $change->status }}</td>
                                        <td>{{ $change->approval_date }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0);">
                                                        <i class="ri-eye-line me-2"></i> View Details
                                                    </a>
                                                    <a class="dropdown-item" href="javascript:void(0);">
                                                        <i class="ri-check-line me-2"></i> Approve
                                                    </a>
                                                    <a class="dropdown-item" href="javascript:void(0);">
                                                        <i class="ri-close-line me-2"></i> Reject
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $boms->links() }}
            </div>
        </div>
    </div>
@endsection
