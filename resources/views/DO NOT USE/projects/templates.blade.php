@extends('layouts/contentNavbarLayout')

@section('title', 'Project Templates')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Project Templates</h5>
                        <a href="{{ route('projects.templates.create') }}" class="btn btn-primary">
                            <i class="ri-add-line"></i> New Template
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Template Name</th>
                                        <th>Description</th>
                                        <th>Tasks</th>
                                        <th>Phases</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($templates as $template)
                                        <tr>
                                            <td>
                                                <a href="{{ route('projects.show', $template) }}">
                                                    {{ $template->name }}
                                                </a>
                                            </td>
                                            <td>{{ Str::limit($template->description, 50) }}</td>
                                            <td>{{ $template->tasks_count }}</td>
                                            <td>{{ $template->phases_count }}</td>
                                            <td>{{ $template->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('projects.show', $template) }}">
                                                            <i class="ri-eye-line me-1"></i> View
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('projects.edit', $template) }}">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('projects.create') }}?template_id={{ $template->id }}">
                                                            <i class="ri-file-copy-line me-1"></i> Use Template
                                                        </a>
                                                        <form action="{{ route('projects.destroy', $template) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('Are you sure you want to delete this template?')">
                                                                <i class="ri-delete-bin-line me-1"></i> Delete
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                responsive: true,
                order: [
                    [0, 'asc']
                ],
                pageLength: 25
            });
        });
    </script>
@endsection
