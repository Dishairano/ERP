@extends('layouts/contentNavbarLayout')

@section('title', 'Leave Requests')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group">
                @can('create-leave-requests')
                    <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Request
                    </a>
                @endcan
                <a href="{{ route('leave-management.calendar') }}" class="btn btn-info">
                    <i class="fas fa-calendar"></i> Calendar View
                </a>
                <a href="{{ route('leave-management.balances') }}" class="btn btn-success">
                    <i class="fas fa-balance-scale"></i> Leave Balances
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leave Requests</h3>
                    <div class="card-tools">
                        <form class="form-inline" method="GET">
                            <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <div class="input-group">
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveRequests as $request)
                                <tr>
                                    <td>{{ $request->leaveType->name }}</td>
                                    <td>{{ $request->start_date->format('Y-m-d') }}</td>
                                    <td>{{ $request->end_date->format('Y-m-d') }}</td>
                                    <td>{{ $request->total_days }}</td>
                                    <td>
                                        <span class="badge badge-{{ match($request->status) {
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'submitted' => 'warning',
                                            default => 'secondary'
                                        } }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('leave-requests.show', $request) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($request->status === 'draft')
                                                <a href="{{ route('leave-requests.edit', $request) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('leave-requests.submit', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($request->status === 'submitted' && Auth::user()->can('approve-leave-requests'))
                                                <form action="{{ route('leave-requests.approve', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('leave-requests.reject', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No leave requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($leaveRequests->hasPages())
                    <div class="card-footer">
                        {{ $leaveRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
