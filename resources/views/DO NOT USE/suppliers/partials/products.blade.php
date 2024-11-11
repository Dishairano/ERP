<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Producten & Diensten</h5>
        <div>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="ri-add-line me-1"></i> Nieuw Product
            </button>
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#importProductsModal">
                <i class="ri-upload-2-line me-1"></i> Importeren
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label class="form-label">Categorie</label>
                <select class="form-select" id="productCategoryFilter">
                    <option value="">Alle categorieën</option>
                    @foreach ($productCategories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" id="productStatusFilter">
                    <option value="">Alle statussen</option>
                    <option value="active">Actief</option>
                    <option value="inactive">Inactief</option>
                    <option value="discontinued">Niet meer leverbaar</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Voorraad</label>
                <select class="form-select" id="stockFilter">
                    <option value="">Alle</option>
                    <option value="in_stock">Op voorraad</option>
                    <option value="low_stock">Bijna op</option>
                    <option value="out_of_stock">Niet op voorraad</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Sorteren</label>
                <select class="form-select" id="productSort">
                    <option value="name_asc">Naam (A-Z)</option>
                    <option value="name_desc">Naam (Z-A)</option>
                    <option value="price_asc">Prijs (Laag-Hoog)</option>
                    <option value="price_desc">Prijs (Hoog-Laag)</option>
                </select>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            @forelse($supplier->products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100">
                        @if ($product->image)
                            <img class="card-img-top" src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="card-title mb-1">{{ $product->name }}</h6>
                                    <small class="text-muted">{{ $product->code }}</small>
                                </div>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="ri-more-fill"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#editProductModal{{ $product->id }}">
                                            <i class="ri-pencil-line me-2"></i>Bewerken
                                        </a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#productHistoryModal{{ $product->id }}">
                                            <i class="ri-history-line me-2"></i>Historie
                                        </a>
                                        @if ($product->status === 'active')
                                            <a class="dropdown-item text-warning" href="#" data-bs-toggle="modal"
                                                data-bs-target="#deactivateProductModal{{ $product->id }}">
                                                <i class="ri-stop-circle-line me-2"></i>Deactiveren
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <span
                                    class="badge bg-label-{{ $product->status === 'active' ? 'success' : 'secondary' }} me-1">
                                    {{ ucfirst($product->status) }}
                                </span>
                                <span
                                    class="badge bg-label-{{ $product->stock_status === 'in_stock' ? 'success' : ($product->stock_status === 'low_stock' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($product->stock_status) }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-semibold">€ {{ number_format($product->price, 2, ',', '.') }}</span>
                                <span class="text-muted">Voorraad: {{ $product->stock_quantity }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#orderProductModal{{ $product->id }}">
                                    <i class="ri-shopping-cart-line me-1"></i>Bestellen
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs
