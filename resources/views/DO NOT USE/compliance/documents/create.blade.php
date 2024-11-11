@extends('layouts/contentNavbarLayout')

@section('title', 'Upload Compliance Document')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Upload Compliance Document</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('compliance.documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="document_type" class="form-label">Document Type</label>
                        <select class="form-select" id="document_type" name="document_type" required>
                            <option value="policy">Policy</option>
                            <option value="procedure">Procedure</option>
                            <option value="regulation">Regulation</option>
                            <option value="certificate">Certificate</option>
                            <option value="report">Report</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label">Document File</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                        <small class="text-muted">Max file size: 10MB</small>
                    </div>

                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>

                    <div class="mb-3">
                        <label for="owner" class="form-label">Owner</label>
                        <input type="text" class="form-control" id="owner" name="owner" required>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags"
                            placeholder="Enter tags separated by commas">
                    </div>

                    <button type="submit" class="btn btn-primary">Upload Document</button>
                    <a href="{{ route('compliance.documents.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
