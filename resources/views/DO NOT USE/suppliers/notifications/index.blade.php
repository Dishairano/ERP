@extends('layouts/contentNavbarLayout')

@section('title', 'Leverancier Notificaties')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Notificaties voor {{ $supplier->name }}</h5>
                        <div>
                            <button class="btn btn-primary" onclick="markAllNotificationsAsRead()">
                                <i class="ri-check-double-line me-1"></i> Alles gelezen
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-4" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all" type="button">
                                    Alle
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#unread" type="button">
                                    Ongelezen
                                    @if ($unreadCount > 0)
                                        <span class="badge rounded-pill bg-danger ms-1">{{ $unreadCount }}</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contracts" type="button">
                                    Contracten
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#performance" type="button">
                                    Performance
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#feedback" type="button">
                                    Feedback
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="all">
                                @foreach ($notifications as $notification)
                                    @include('suppliers.notifications.notification-item', [
                                        'notification' => $notification,
                                    ])
                                @endforeach
                            </div>

                            <div class="tab-pane fade" id="unread">
                                @foreach ($unreadNotifications as $notification)
                                    @include('suppliers.notifications.notification-item', [
                                        'notification' => $notification,
                                    ])
                                @endforeach
                            </div>

                            <div class="tab-pane fade" id="contracts">
                                @foreach ($contractNotifications as $notification)
                                    @include('suppliers.notifications.notification-item', [
                                        'notification' => $notification,
                                    ])
                                @endforeach
                            </div>

                            <div class="tab-pane fade" id="performance">
                                @foreach ($performanceNotifications as $notification)
                                    @include('suppliers.notifications.notification-item', [
                                        'notification' => $notification,
                                    ])
                                @endforeach
                            </div>

                            <div class="tab-pane fade" id="feedback">
                                @foreach ($feedbackNotifications as $notification)
                                    @include('suppliers.notifications.notification-item', [
                                        'notification' => $notification,
                                    ])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        function markAllNotificationsAsRead() {
            fetch(`/suppliers/{{ $supplier->id }}/notifications/mark-all-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                window.location.reload();
            });
        }
    </script>
@endsection
