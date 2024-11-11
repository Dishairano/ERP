@extends('layouts/contentNavbarLayout')

@section('title', 'Picking Orders')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Picking Orders</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createPickingOrderModal">
                            Create Picking Order
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Warehouse</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Picker</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pickingOrders as $order)
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                                    data-bs-target="#viewPickingOrderModal{{ $order->id }}">
                                                    #{{ $order->id }}
                                                </a>
                                            </td>
                                            <td>{{ $order->warehouse->name }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $order->priority === 'high' ? 'danger' : ($order->priority === 'medium' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($order->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                                    {{ str_replace('_', ' ', ucfirst($order->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->picker->name ?? 'Not Assigned' }}</td>
                                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewPickingOrderModal{{ $order->id }}">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                        @if ($order->status !== 'completed')
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="assignPicker({{ $order->id }})">
                                                                <i class="ri-user-line me-1"></i> Assign Picker
                                                            </a>
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="updateStatus({{ $order->id }})">
                                                                <i class="ri-refresh-line me-1"></i> Update Status
                                                            </a>
                                                        @endif
                                                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                            onclick="confirmDelete({{ $order->id }})">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $pickingOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Picking Order Modal -->
    <div class="modal fade" id="createPickingOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Picking Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('warehousing.picking.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Order</label>
                                <select class="form-select" name="order_id" required>
                                    <option value="">Select Order</option>
                                    @foreach ($orders as $order)
                                        <option value="{{ $order->id }}">#{{ $order->id }} -
                                            {{ $order->customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Warehouse</label>
                                <select class="form-select" name="warehouse_id" required>
                                    <option value="">Select Warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Priority</label>
                                <select class="form-select" name="priority" required>
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Picker</label>
                                <select class="form-select" name="picker_id">
                                    <option value="">Select Picker</option>
                                    @foreach ($pickers as $picker)
                                        <option value="{{ $picker->id }}">{{ $picker->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Items</label>
                            <div id="pickingItems">
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <select class="form-select" name="items[0][product_id]" required>
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select" name="items[0][bin_id]" required>
                                            <option value="">Select Bin</option>
                                            @foreach ($bins as $bin)
                                                <option value="{{ $bin->id }}">{{ $bin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="items[0][quantity]"
                                            placeholder="Quantity" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger" onclick="removeItem(this)">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary" onclick="addItem()">
                                Add Item
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($pickingOrders as $order)
        <!-- View Picking Order Modal -->
        <div class="modal fade" id="viewPickingOrderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Picking Order #{{ $order->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Warehouse:</strong> {{ $order->warehouse->name }}</p>
                                <p><strong>Priority:</strong> {{ ucfirst($order->priority) }}</p>
                                <p><strong>Status:</strong> {{ str_replace('_', ' ', ucfirst($order->status)) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Picker:</strong> {{ $order->picker->name ?? 'Not Assigned' }}</p>
                                <p><strong>Created:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                                <p><strong>Picked:</strong>
                                    {{ $order->picked_at ? $order->picked_at->format('M d, Y H:i') : 'Not Picked' }}</p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Bin</th>
                                        <th>Quantity</th>
                                        <th>Picked</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>{{ $item->bin->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->picked_quantity }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $item->status === 'completed' ? 'success' : ($item->status === 'partial' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($order->notes)
                            <div class="mt-3">
                                <strong>Notes:</strong>
                                <p>{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('page-script')
    <script>
        let itemCount = 1;

        function addItem() {
            const template = `
        <div class="row mb-2">
            <div class="col-md-4">
                <select class="form-select" name="items[${itemCount}][product_id]" required>
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="items[${itemCount}][bin_id]" required>
                    <option value="">Select Bin</option>
                    @foreach ($bins as $bin)
                    <option value="{{ $bin->id }}">{{ $bin->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="items[${itemCount}][quantity]" placeholder="Quantity" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="removeItem(this)">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    `;
            document.getElementById('pickingItems').insertAdjacentHTML('beforeend', template);
            itemCount++;
        }

        function removeItem(button) {
            button.closest('.row').remove();
        }

        function confirmDelete(orderId) {
            if (confirm('Are you sure you want to delete this picking order?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/warehousing/picking/${orderId}`;
                form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function assignPicker(orderId) {
            // Implementation for assigning picker
        }

        function updateStatus(orderId) {
            // Implementation for updating status
        }
    </script>
@endsection
