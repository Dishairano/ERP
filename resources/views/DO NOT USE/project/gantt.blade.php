{{-- Move existing gantt.blade.php content here --}}
@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Project Gantt Chart</h4>
                    </div>
                    <div class="card-body">
                        <div id="gantt-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Gantt chart initialization and configuration
    </script>
@endpush
