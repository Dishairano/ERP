@extends('layouts/contentNavbarLayout')

@section('title', 'View Compliance Document')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4">View Compliance Document</h4>
            <div>
                <a href="{{ route('compliance.documents.download', $document) }}" class="btn btn-primary">Download</a>
                <a href="{{ route('compliance.documents.edit', $document) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('compliance.documents.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Title:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $document->title }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Document Type:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ ucfirst($document->document_type) }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-9">
                        <span class="badge bg-{{ $document->status === 'active' ? 'success' : 'warning' }}">
                            {{ $document->status }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Expiry Date:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $document->expiry_date ? $document->expiry_date->format('Y-m-d') : 'No expiry date' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Description:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $document->description ?: 'No description provided' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Department:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $document->department }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Owner:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $document->owner }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Tags:</strong>
                    </div>
                    <div class="col-md-9">
                        @if ($document->tags)
                            @foreach (explode(',', $document->tags) as $tag)
                                <span class="badge bg-info me-1">{{ trim($tag) }}</span>
                            @endforeach
                        @else
                            No tags
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Created At:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $document->created_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Last Updated:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $document->updated_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
