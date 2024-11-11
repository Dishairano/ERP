@extends('layouts/contentNavbarLayout')

@section('title', 'Data Analysis')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Analysis Configurations</h4>
                    <a href="{{ route('data-analysis.create') }}" class="btn btn-primary">
                        <i data-feather="plus"></i> New Analysis
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Last Analyzed</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($configs as $config)
                                    <tr>
                                        <td>{{ $config->name }}</td>
                                        <td>{{ ucfirst($config->type) }}</td>
                                        <td>
                                            {{ $config->results->last()?->analyzed_at?->diffForHumans() ?? 'Never' }}
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('data-analysis.show', $config) }}">
                                                        <i data-feather="eye"></i> View
                                                    </a>
                                                    <form action="{{ route('data-analysis.analyze', $config) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i data-feather="refresh-cw"></i> Run Analysis
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
@endsection

@section('vendor-script')
    <script src="{{ asset('vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
@endsection
