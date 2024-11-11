@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Compliance Document')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Edit Compliance Document</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('compliance.documents.update', $document) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ $document->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="document_type" class="form-label">Document Type</label>
                        <select class="form-select" id="document_type" name="document_type" required>
                            <option value="policy" {{ $document->document_type === 'policy' ? 'selected' : '' }}>Policy
                            </option>
                            <option value="procedure" {{ $document->document_type === 'procedure' ? 'selected' : '' }}>
                                Procedure</option>
                            <option value="regulation" {{ $document->document_type === 'regulation' ? 'selected' : '' }}>
                                Regulation</option>
                            <option value="certificate" {{ $document->document_type === 'certificate' ? 'selected' : '' }}>
                                Certificate</option>
                            <option value="report" {{ $document->document_type === 'report' ? 'selected' : '' }}>Report
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label">Document File</label>
                        <input type="file" class="form-control" id="file" name="file">
                        <small class="text-muted">Leave empty to keep the current file. Max file size: 10MB</small>
                    </div>

                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiry_date" name="expiry_date"
                            value="{{ $document->expiry_date ? $document->expiry_date->format('Y-m-d') : '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ $document->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department"
                            value="{{ $document->department }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="owner" class="form-label">Owner</label>
                        <input type="text" class="form-control" id="owner" name="owner"
                            value="{{ $document->owner }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags"
                            value="{{ $document->tags }}" placeholder="Enter tags separated by commas">
                    </div>

                    <button type="submit" class="btn btn-primary">Update Document</button>
                    <a href="{{ route('compliance.documents.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
