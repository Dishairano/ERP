@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            @if (isset($supplier))
                                Notifications - {{ $supplier->name }}
                            @else
                                All Supplier Notifications
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        @if (!isset($supplier))
                                            <th>Supplier</th>
                                        @endif
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Priority</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $notification)
                                        <tr class="{{ $notification->read_at ? '' : 'table-warning' }}">
                                            @if (!isset($supplier))
                                                <td>
                                                    <a href="{{ route('suppliers.show', $notification->supplier) }}">
                                                        {{ $notification->supplier->name }}
                                                    </a>
                                                </td>
                                            @endif
                                            <td>
                                                @switch($notification->type)
                                                    @case('contract_expiring')
                                                        <span class="badge badge-warning">Contract Expiring</span>
                                                    @break

                                                    @case('performance_alert')
                                                        <span class="badge badge-danger">Performance Alert</span>
                                                    @break

                                                    @case('feedback_required')
                                                        <span class="badge badge-info">Feedback Required</span>
                                                    @break

                                                    @case('contract_violation')
                                                        <span class="badge badge-danger">Contract Violation</span>
                                                    @break

                                                    @case('payment_due')
                                                        <span class="badge badge-warning">Payment Due</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-secondary">{{ $notification->type }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $notification->title }}</td>
                                            <td>{{ $notification->message }}</td>
                                            <td>
                                                @switch($notification->priority)
                                                    @case('high')
                                                        <span class="badge badge-danger">High</span>
                                                    @break

                                                    @case('medium')
                                                        <span class="badge badge-warning">Medium</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-info">Low</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $notification->created_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                @if ($notification->read_at)
                                                    <span class="badge badge-success">Read</span>
                                                @else
                                                    <span class="badge badge-warning">Unread</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!$notification->read_at)
                                                    @if (isset($supplier))
                                                        <form
                                                            action="{{ route('suppliers.notifications.read', [$supplier, $notification]) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i> Mark as Read
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form
                                                            action="{{ route('suppliers.notifications.read', [$notification->supplier, $notification]) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i> Mark as Read
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                                @if ($notification->data)
                                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                                        data-target="#notificationModal-{{ $notification->id }}">
                                                        <i class="fas fa-eye"></i> Details
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Detail Modals -->
    @foreach ($notifications as $notification)
        @if ($notification->data)
            <div class="modal fade" id="notificationModal-{{ $notification->id }}" tabindex="-1" role="dialog"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $notification->title }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-sm">
                                @foreach ($notification->data as $key => $value)
                                    <tr>
                                        <th>{{ ucwords(str_replace('_', ' ', $key)) }}:</th>
                                        <td>
                                            @if ($value instanceof \Carbon\Carbon)
                                                {{ $value->format('d-m-Y') }}
                                            @else
                                                {{ is_array($value) ? json_encode($value) : $value }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
