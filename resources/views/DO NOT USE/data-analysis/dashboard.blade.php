@extends('layouts/contentNavbarLayout')

@section('title', 'Analytics Dashboard')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/extensions/dragula.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Analytics Dashboard</h4>
                    <div class="card-header-actions">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWidgetModal">
                            <i data-feather="plus"></i> Add Widget
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($dashboards as $dashboard)
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $dashboard->name }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-grid" data-dashboard-id="{{ $dashboard->id }}">
                            @foreach ($dashboard->components as $component)
                                <div class="dashboard-item" data-component-id="{{ $component->id }}"
                                    style="grid-area: {{ $component->position_y }} / {{ $component->position_x }} / span {{ $component->height }} / span {{ $component->width }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="visualization-{{ $component->visualization_id }}"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Widget Modal -->
    <div class="modal fade" id="addWidgetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Dashboard Widget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addWidgetForm" action="{{ route('data-analysis.dashboard.add-widget') }}" method="POST">
                        @csrf
                        <div class="mb-1">
                            <label class="form-label">Dashboard</label>
                            <select class="form-select" name="dashboard_id" required>
                                @foreach ($dashboards as $dashboard)
                                    <option value="{{ $dashboard->id }}">{{ $dashboard->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1">
                            <label class="form-label">Visualization</label>
                            <select class="form-select" name="visualization_id" required>
                                @foreach ($visualizations as $visualization)
                                    <option value="{{ $visualization->id }}">{{ $visualization->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-1">
                                    <label class="form-label">Position X</label>
                                    <input type="number" class="form-control" name="position_x" min="1"
                                        value="1" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-1">
                                    <label class="form-label">Position Y</label>
                                    <input type="number" class="form-control" name="position_y" min="1"
                                        value="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-1">
                                    <label class="form-label">Width</label>
                                    <input type="number" class="form-control" name="width" min="1" max="12"
                                        value="6" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-1">
                                    <label class="form-label">Height</label>
                                    <input type="number" class="form-control" name="height" min="1" value="1"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Add Widget</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset('vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/dragula.min.js') }}"></script>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize dragula for dashboard grid items
            dragula([].slice.call(document.querySelectorAll('.dashboard-grid')), {
                moves: function(el, container, handle) {
                    return handle.classList.contains('drag-handle');
                }
            }).on('drop', function(el) {
                // Update positions after drag
                const dashboardId = el.closest('.dashboard-grid').dataset.dashboardId;
                const componentId = el.dataset.componentId;
                const rect = el.getBoundingClientRect();
                const grid = el.closest('.dashboard-grid').getBoundingClientRect();

                // Calculate new position
                const x = Math.round((rect.left - grid.left) / grid.width * 12) + 1;
                const y = Math.round((rect.top - grid.top) / 50);

                // Send update to server
                fetch(`/data-analysis/dashboard/update-widget-position/${componentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        position_x: x,
                        position_y: y
                    })
                });
            });

            // Initialize visualizations
            @foreach ($dashboards as $dashboard)
                @foreach ($dashboard->components as $component)
                    initializeVisualization(
                        'visualization-{{ $component->visualization_id }}',
                        @json($component->visualization->data),
                        '{{ $component->visualization->type }}'
                    );
                @endforeach
            @endforeach
        });

        function initializeVisualization(elementId, data, type) {
            const element = document.getElementById(elementId);
            if (!element) return;

            let options = {
                chart: {
                    height: 350,
                    type: type
                },
                series: [{
                    data: data
                }]
            };

            switch (type) {
                case 'bar':
                    options.plotOptions = {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        }
                    };
                    break;
                case 'line':
                    options.stroke = {
                        curve: 'smooth'
                    };
                    break;
                case 'pie':
                    options.labels = Object.keys(data);
                    options.series = Object.values(data);
                    break;
            }

            new ApexCharts(element, options).render();
        }
    </script>
@endsection
