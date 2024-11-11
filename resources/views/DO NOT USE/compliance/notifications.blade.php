@extends('layouts/contentNavbarLayout')

@section('title', 'Compliance Notifications')

@section('content')
    <h4 class="fw-bold">Compliance Notifications</h4>

    <!-- Notification Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Type</label>
                    <select class="form-select">
                        <option value="all">All Types</option>
                        <option value="requirement">Requirements</option>
                        <option value="audit">Audits</option>
                        <option value="score">Compliance Score</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Priority</label>
                    <select class="form-select">
                        <option value="all">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option value="all">All Status</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Date Range</label>
                    <select class="form-select">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="all">All Time</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card">
        <div class="card-body">
            <div class="list-group">
                @forelse($notifications ?? [] as $notification)
                    <div class="list-group-item list-group-item-action d-flex gap-3 py-3">
                        <div class="d-flex gap-2 w-100 justify-content-between align-items-center">
                            <div>
                                @switch($notification->type)
                                    @case('requirement')
                                        <i class="ri-file-list-3-line fs-4 text-primary"></i>
                                    @break

                                    @case('audit')
                                        <i class="ri-shield-check-line fs-4 text-success"></i>
                                    @break

                                    @case('score')
                                        <i class="ri-bar-chart-line fs-4 text-warning"></i>
                                    @break

                                    @default
                                        <i class="ri-notification-line fs-4 text-info"></i>
                                @endswitch
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $notification->title }}</h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $notification->message }}</p>
                                @if ($notification->action_url)
                                    <a href="{{ $notification->action_url }}" class="btn btn-sm btn-primary mt-2">
                                        View Details
                                    </a>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                @if (!$notification->read_at)
                                    <span class="badge bg-danger">New</span>
                                @endif
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if (!$notification->read_at)
                                            <form
                                                action="{{ route('compliance.notifications.mark-as-read', $notification->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="ri-check-line me-2"></i> Mark as Read
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('compliance.notifications.delete', $notification->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="ri-delete-bin-line me-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="ri-notification-off-line fs-1 text-muted mb-3"></i>
                            <p class="text-muted">No notifications found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Clear All Button -->
        @if (!empty($notifications))
            <div class="text-end mt-3">
                <form action="{{ route('compliance.notifications.clear-all') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to clear all notifications?')">
                        Clear All Notifications
                    </button>
                </form>
            </div>
        @endif
    @endsection
