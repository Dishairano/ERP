<div class="dropdown">
    <button class="btn btn-outline-primary position-relative dropdown-toggle" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="ri-notification-3-line"></i>
        @if ($supplier->getUnreadNotifications()->count() > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $supplier->getUnreadNotifications()->count() }}
            </span>
        @endif
    </button>
    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 320px;">
        <li>
            <div class="dropdown-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Notificaties</h6>
                @if ($supplier->getUnreadNotifications()->count() > 0)
                    <a href="#" class="text-muted small" onclick="markAllNotificationsAsRead()">
                        Alles gelezen
                    </a>
                @endif
            </div>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        @forelse($supplier->getUnreadNotifications()->take(5) as $notification)
            <li>
                <a class="dropdown-item notification-item" href="#"
                    onclick="handleNotificationClick('{{ $notification->id }}', '{{ $notification->type }}', {{ json_encode($notification->data) }})">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            @switch($notification->type)
                                @case('contract_expiring')
                                    <i class="ri-timer-line text-warning"></i>
                                @break

                                @case('performance_alert')
                                    <i class="ri-alert-line text-danger"></i>
                                @break

                                @case('feedback_required')
                                    <i class="ri-message-2-line text-info"></i>
                                @break

                                @case('contract_violation')
                                    <i class="ri-error-warning-line text-danger"></i>
                                @break

                                @case('payment_due')
                                    <i class="ri-money-euro-box-line text-success"></i>
                                @break
                            @endswitch
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $notification->title }}</h6>
                            <p class="mb-0 text-muted small">{{ $notification->message }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        @if ($notification->priority === 'high')
                            <span class="badge bg-danger ms-2">Hoog</span>
                        @elseif($notification->priority === 'medium')
                            <span class="badge bg-warning ms-2">Medium</span>
                        @endif
                    </div>
                </a>
            </li>
            @empty
                <li>
                    <div class="text-center p-3">
                        <i class="ri-inbox-line fs-3 text-muted"></i>
                        <p class="mb-0 mt-2">Geen nieuwe notificaties</p>
                    </div>
                </li>
            @endforelse
            @if ($supplier->getUnreadNotifications()->count() > 5)
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item text-center" href="{{ route('suppliers.notifications', $supplier) }}">
                        Bekijk alle notificaties
                    </a>
                </li>
            @endif
        </ul>
    </div>

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

        function handleNotificationClick(id, type, data) {
            // Mark as read
            fetch(`/suppliers/notifications/${id}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            // Handle different notification types
            switch (type) {
                case 'contract_expiring':
                    window.location.href = `/suppliers/contracts/${data.contract_id}`;
                    break;
                case 'performance_alert':
                    window.location.href = `/suppliers/{{ $supplier->id }}#evaluations`;
                    break;
                case 'feedback_required':
                    window.location.href = `/suppliers/orders/${data.order_id}/feedback`;
                    break;
                case 'contract_violation':
                    window.location.href = `/suppliers/{{ $supplier->id }}#contracts`;
                    break;
                case 'payment_due':
                    window.location.href = `/suppliers/invoices/${data.invoice_id}`;
                    break;
            }
        }
    </script>
