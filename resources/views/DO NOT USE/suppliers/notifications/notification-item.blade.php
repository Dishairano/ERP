<div class="card mb-3 {{ $notification->read_at ? 'bg-light' : '' }}">
    <div class="card-body">
        <div class="d-flex align-items-start">
            <div class="flex-shrink-0">
                @switch($notification->type)
                    @case('contract_expiring')
                        <div class="avatar avatar-sm bg-label-warning">
                            <i class="ri-timer-line"></i>
                        </div>
                    @break

                    @case('performance_alert')
                        <div class="avatar avatar-sm bg-label-danger">
                            <i class="ri-alert-line"></i>
                        </div>
                    @break

                    @case('feedback_required')
                        <div class="avatar avatar-sm bg-label-info">
                            <i class="ri-message-2-line"></i>
                        </div>
                    @break

                    @case('contract_violation')
                        <div class="avatar avatar-sm bg-label-danger">
                            <i class="ri-error-warning-line"></i>
                        </div>
                    @break

                    @case('payment_due')
                        <div class="avatar avatar-sm bg-label-success">
                            <i class="ri-money-euro-box-line"></i>
                        </div>
                    @break
                @endswitch
            </div>
            <div class="flex-grow-1 ms-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">{{ $notification->title }}</h6>
                        <p class="mb-1">{{ $notification->message }}</p>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        @if ($notification->priority === 'high')
                            <span class="badge bg-danger me-2">Hoog</span>
                        @elseif($notification->priority === 'medium')
                            <span class="badge bg-warning me-2">Medium</span>
                        @endif
                        @if (!$notification->read_at)
                            <span class="badge bg-primary">Nieuw</span>
                        @endif
                    </div>
                </div>
                <div class="mt-2">
                    @switch($notification->type)
                        @case('contract_expiring')
                            <a href="/suppliers/contracts/{{ $notification->data['contract_id'] }}"
                                class="btn btn-sm btn-primary">
                                <i class="ri-eye-line me-1"></i> Bekijk Contract
                            </a>
                        @break

                        @case('performance_alert')
                            <a href="/suppliers/{{ $supplier->id }}#evaluations" class="btn btn-sm btn-primary">
                                <i class="ri-line-chart-line me-1"></i> Bekijk Performance
                            </a>
                        @break

                        @case('feedback_required')
                            <a href="/suppliers/orders/{{ $notification->data['order_id'] }}/feedback"
                                class="btn btn-sm btn-primary">
                                <i class="ri-feedback-line me-1"></i> Geef Feedback
                            </a>
                        @break

                        @case('contract_violation')
                            <a href="/suppliers/{{ $supplier->id }}#contracts" class="btn btn-sm btn-primary">
                                <i class="ri-file-warning-line me-1"></i> Bekijk Details
                            </a>
                        @break

                        @case('payment_due')
                            <a href="/suppliers/invoices/{{ $notification->data['invoice_id'] }}"
                                class="btn btn-sm btn-primary">
                                <i class="ri-bill-line me-1"></i> Bekijk Factuur
                            </a>
                        @break
                    @endswitch
                </div>
            </div>
        </div>
    </div>
</div>
