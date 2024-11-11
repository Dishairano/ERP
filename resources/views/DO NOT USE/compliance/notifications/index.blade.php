@extends('layouts/contentNavbarLayout')

@section('title', 'Compliance Notifications')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4">Compliance Notifications</h4>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notifications as $notification)
                                <tr class="{{ $notification->status === 'unread' ? 'table-active' : '' }}">
                                    <td>{{ $notification->title }}</td>
                                    <td>{{ $notification->type }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $notification->priority === 'high' ? 'danger' : ($notification->priority === 'medium' ? 'warning' : 'info') }}">
                                            {{ $notification->priority }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $notification->status === 'unread' ? 'danger' : 'success' }}">
                                            {{ $notification->status }}
                                        </span>
                                    </td>
                                    <td>{{ $notification->due_date ? $notification->due_date->format('Y-m-d') : 'N/A' }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @if ($notification->status === 'unread')
                                                    <form
                                                        action="{{ route('compliance.notifications.mark-as-read', $notification) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bx bx-check me-1"></i> Mark as Read
                                                        </button>
                                                    </form>
                                                @endif
                                                <form
                                                    action="{{ route('compliance.notifications.destroy', $notification) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this notification?')">
                                                        <i class="bx bx-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
