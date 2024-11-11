<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Inkooporders</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOrderModal">
            <i class="ri-add-line me-1"></i> Nieuwe Order
        </button>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" id="orderStatusFilter">
                    <option value="">Alle statussen</option>
                    <option value="draft">Concept</option>
                    <option value="pending">In behandeling</option>
                    <option value="confirmed">Bevestigd</option>
                    <option value="shipped">Verzonden</option>
                    <option value="delivered">Geleverd</option>
                    <option value="cancelled">Geannuleerd</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Periode</label>
                <select class="form-select" id="orderPeriodFilter">
                    <option value="7">Laatste 7 dagen</option>
                    <option value="30">Laatste 30 dagen</option>
                    <option value="90">Laatste 3 maanden</option>
                    <option value="365">Laatste jaar</option>
                    <option value="all">Alle orders</option>
                </select>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order Nr.</th>
                        <th>Datum</th>
                        <th>Producten</th>
                        <th>Totaal</th>
                        <th>Status</th>
                        <th>Leverdatum</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supplier->purchaseOrders as $order)
                        <tr>
                            <td>
                                <span class="fw-semibold">#{{ $order->order_number }}</span>
                                @if ($order->is_urgent)
                                    <span class="badge bg-label-danger ms-1">Spoed</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d-m-Y') }}</td>
                            <td>
                                <span class="fw-semibold">{{ $order->items_count }}</span> producten
                                <button class="btn btn-text-secondary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#orderItemsModal{{ $order->id }}">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </td>
                            <td>€ {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                            <td>
                                <span
                                    class="badge bg-label-{{ $order->status === 'delivered'
                                        ? 'success'
                                        : ($order->status === 'shipped'
                                            ? 'info'
                                            : ($order->status === 'confirmed'
                                                ? 'primary'
                                                : ($order->status === 'cancelled'
                                                    ? 'danger'
                                                    : 'warning'))) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                @if ($order->delivery_date)
                                    {{ $order->delivery_date->format('d-m-Y') }}
                                    @if ($order->is_late)
                                        <span class="badge bg-label-danger ms-1">Te laat</span>
                                    @endif
                                @else
                                    <span class="text-muted">Niet gepland</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="ri-more-fill"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('purchase-orders.show', $order->id) }}">
                                            <i class="ri-eye-line me-2"></i>Details
                                        </a>
                                        @if ($order->status === 'draft' || $order->status === 'pending')
                                            <a class="dropdown-item"
                                                href="{{ route('purchase-orders.edit', $order->id) }}">
                                                <i class="ri-pencil-line me-2"></i>Bewerken
                                            </a>
                                        @endif
                                        <a class="dropdown-item"
                                            href="{{ route('purchase-orders.download', $order->id) }}">
                                            <i class="ri-download-line me-2"></i>Downloaden
                                        </a>
                                        @if ($order->status === 'delivered')
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#addEvaluationModal{{ $order->id }}">
                                                <i class="ri-star-line me-2"></i>Beoordelen
                                            </a>
                                        @endif
                                        @if ($order->status === 'draft')
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                data-bs-target="#cancelOrderModal{{ $order->id }}">
                                                <i class="ri-close-circle-line me-2"></i>Annuleren
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Geen orders gevonden</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Order Items Modals -->
@foreach ($supplier->purchaseOrders as $order)
    @include('suppliers.modals.order-items', ['order' => $order])
    @if ($order->status === 'delivered')
        @include('suppliers.modals.add-evaluation', ['order' => $order])
    @endif
    @if ($order->status === 'draft')
        @include('suppliers.modals.cancel-order', ['order' => $order])
    @endif
@endforeach
