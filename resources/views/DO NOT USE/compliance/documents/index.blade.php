@extends('layouts/contentNavbarLayout')

@section('title', 'Compliance Documents')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4">Compliance Documents</h4>
            <a href="{{ route('compliance.documents.create') }}" class="btn btn-primary">Upload New Document</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Document Type</th>
                                <th>Status</th>
                                <th>Department</th>
                                <th>Owner</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                                <tr>
                                    <td>{{ $document->title }}</td>
                                    <td>{{ $document->document_type }}</td>
                                    <td>
                                        <span class="badge bg-{{ $document->status === 'active' ? 'success' : 'warning' }}">
                                            {{ $document->status }}
                                        </span>
                                    </td>
                                    <td>{{ $document->department }}</td>
                                    <td>{{ $document->owner }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('compliance.documents.show', $document) }}">
                                                    <i class="bx bx-show-alt me-1"></i> View
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('compliance.documents.download', $document) }}">
                                                    <i class="bx bx-download me-1"></i> Download
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('compliance.documents.edit', $document) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('compliance.documents.destroy', $document) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this document?')">
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
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
