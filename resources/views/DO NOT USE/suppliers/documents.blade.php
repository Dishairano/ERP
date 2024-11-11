@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            @if (isset($supplier))
                                Documents - {{ $supplier->name }}
                            @else
                                Supplier Documents
                            @endif
                        </h4>
                        @if (isset($supplier))
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#uploadDocumentModal">
                                <i class="fas fa-upload"></i> Upload Document
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        @if (!isset($supplier))
                                            <th>Supplier</th>
                                        @endif
                                        <th>Title</th>
                                        <th>Document Type</th>
                                        <th>Description</th>
                                        <th>Valid Until</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documents as $document)
                                        <tr>
                                            @if (!isset($supplier))
                                                <td>
                                                    <a href="{{ route('suppliers.show', $document->supplier_id) }}">
                                                        {{ $document->supplier_name }}
                                                    </a>
                                                </td>
                                            @endif
                                            <td>{{ $document->title }}</td>
                                            <td>{{ $document->document_type }}</td>
                                            <td>{{ Str::limit($document->description, 50) }}</td>
                                            <td>
                                                @if ($document->valid_until)
                                                    {{ $document->valid_until->format('d-m-Y') }}
                                                    @if ($document->isExpiring())
                                                        <span class="badge badge-warning">Expiring Soon</span>
                                                    @elseif($document->isExpired())
                                                        <span class="badge badge-danger">Expired</span>
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if ($document->valid_until)
                                                    @if ($document->isExpired())
                                                        <span class="badge badge-danger">Expired</span>
                                                    @else
                                                        <span class="badge badge-success">Valid</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-info">No Expiry</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ $document->file_path }}" class="btn btn-sm btn-info"
                                                        target="_blank" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ $document->file_path }}" class="btn btn-sm btn-primary"
                                                        download title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    @if (isset($supplier))
                                                        <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                                            onclick="confirmDelete('{{ $document->id }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($supplier))
        <!-- Upload Document Modal -->
        <div class="modal fade" id="uploadDocumentModal" tabindex="-1" role="dialog"
            aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('suppliers.documents.store', $supplier) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadDocumentModalLabel">Upload Document</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="document_type">Document Type</label>
                                <select class="form-control" id="document_type" name="document_type" required>
                                    <option value="contract">Contract</option>
                                    <option value="certificate">Certificate</option>
                                    <option value="license">License</option>
                                    <option value="invoice">Invoice</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="valid_until">Valid Until</label>
                                <input type="date" class="form-control" id="valid_until" name="valid_until">
                            </div>
                            <div class="form-group">
                                <label for="document">Document File</label>
                                <input type="file" class="form-control-file" id="document" name="document" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                function confirmDelete(documentId) {
                    if (confirm('Are you sure you want to delete this document?')) {
                        // Create and submit form for document deletion
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ route('suppliers.documents.destroy', [$supplier->id, '']) }}/${documentId}`;
                        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            </script>
        @endpush
    @endif
@endsection
