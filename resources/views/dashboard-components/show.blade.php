@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Component Header -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0">{{ $component->name }}</h3>
                            <small class="text-muted">
                                Type: {{ ucfirst($component->type) }} |
                                Size: {{ ucfirst($component->size) }} |
                                Last Updated: {{ $component->updated_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('dashboard-components.index', $dashboard) }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line"></i> Back to Components
                            </a>
                            @can('manageComponents', $dashboard)
                                <a href="{{ route('dashboard-components.edit', [$dashboard, $component]) }}"
                                    class="btn btn-warning">
                                    <i class="ri-edit-line"></i> Edit Component
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">
                                    <i class="ri-delete-bin-line"></i> Delete
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Component Preview -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Preview</h4>
                                <button type="button" class="btn btn-sm btn-primary refresh-data"
                                    data-url="{{ route('dashboard-components.refresh-data', [$dashboard, $component]) }}">
                                    <i class="ri-refresh-line"></i> Refresh Data
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="component-preview" class="component-container" data-type="{{ $component->type }}"
                                    data-settings="{{ json_encode($component->settings) }}"
                                    data-styles="{{ json_encode($component->custom_styles) }}">
                                    <!-- Component will be rendered here via JavaScript -->
                                    <div class="text-center py-5" id="loading-indicator">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Component Data -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Raw Data</h4>
                            </div>
                            <div class="card-body">
                                <pre class="language-json"><code id="component-data">{{ json_encode($data, JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- Component Details -->
                    <div class="col-lg-4">
                        <!-- Basic Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Details</h4>
                            </div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4">Status</dt>
                                    <dd class="col-sm-8">
                                        @if ($component->is_enabled)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Disabled</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Position</dt>
                                    <dd class="col-sm-8">{{ $component->position + 1 }}</dd>

                                    <dt class="col-sm-4">Visibility</dt>
                                    <dd class="col-sm-8">
                                        @if ($component->is_public)
                                            <span class="badge bg-info">Public</span>
                                        @else
                                            <span class="badge bg-secondary">Private</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Data Source</dt>
                                    <dd class="col-sm-8">
                                        @if ($component->dataSource)
                                            {{ $component->dataSource->name }}
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Refresh Interval</dt>
                                    <dd class="col-sm-8">
                                        @if ($component->refresh_interval)
                                            {{ $component->refresh_interval }} seconds
                                        @else
                                            <span class="text-muted">Manual refresh only</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Cache Duration</dt>
                                    <dd class="col-sm-8">
                                        @if ($component->cache_duration)
                                            {{ $component->cache_duration }} seconds
                                        @else
                                            <span class="text-muted">No caching</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Created</dt>
                                    <dd class="col-sm-8">{{ $component->created_at->format('M d, Y H:i') }}</dd>

                                    <dt class="col-sm-4">Last Modified</dt>
                                    <dd class="col-sm-8">{{ $component->updated_at->format('M d, Y H:i') }}</dd>
                                </dl>
                            </div>
                        </div>

                        <!-- Component Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Settings</h4>
                            </div>
                            <div class="card-body">
                                <pre class="language-json"><code>{{ json_encode($component->settings, JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        </div>

                        <!-- Custom Styles -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Custom Styles</h4>
                            </div>
                            <div class="card-body">
                                <pre class="language-json"><code>{{ json_encode($component->custom_styles, JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('manageComponents', $dashboard)
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this component? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('dashboard-components.destroy', [$dashboard, $component]) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Component</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prismjs/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prismjs/1.24.1/components/prism-json.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const componentContainer = document.getElementById('component-preview');
            const loadingIndicator = document.getElementById('loading-indicator');
            const componentData = @json($data);

            // Initialize component visualization
            initializeComponent(componentContainer, componentData);

            // Handle refresh button click
            document.querySelector('.refresh-data').addEventListener('click', function() {
                const url = this.dataset.url;
                loadingIndicator.style.display = 'block';

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update the component visualization
                            initializeComponent(componentContainer, data.data);
                            // Update the raw data display
                            document.getElementById('component-data').textContent =
                                JSON.stringify(data.data, null, 2);
                            Prism.highlightAll();
                        } else {
                            throw new Error(data.error || 'Failed to refresh data');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to refresh component data: ' + error.message);
                    })
                    .finally(() => {
                        loadingIndicator.style.display = 'none';
                    });
            });

            function initializeComponent(container, data) {
                const type = container.dataset.type;
                const settings = JSON.parse(container.dataset.settings);
                const customStyles = JSON.parse(container.dataset.styles);

                // Clear previous content
                container.innerHTML = '';

                switch (type) {
                    case 'chart':
                        renderChart(container, data, settings);
                        break;
                    case 'table':
                        renderTable(container, data, settings);
                        break;
                    case 'metric':
                        renderMetric(container, data, settings);
                        break;
                    case 'list':
                        renderList(container, data, settings);
                        break;
                    case 'calendar':
                        renderCalendar(container, data, settings);
                        break;
                    case 'map':
                        renderMap(container, data, settings);
                        break;
                    default:
                        container.innerHTML = '<div class="alert alert-warning">Unsupported component type</div>';
                }

                // Apply custom styles
                Object.assign(container.style, customStyles);
            }

            // Component rendering functions
            function renderChart(container, data, settings) {
                const canvas = document.createElement('canvas');
                container.appendChild(canvas);

                new Chart(canvas, {
                    type: settings.chartType || 'line',
                    data: data,
                    options: settings.options || {}
                });
            }

            function renderTable(container, data, settings) {
                // Implementation for table rendering
            }

            function renderMetric(container, data, settings) {
                // Implementation for metric rendering
            }

            function renderList(container, data, settings) {
                // Implementation for list rendering
            }

            function renderCalendar(container, data, settings) {
                // Implementation for calendar rendering
            }

            function renderMap(container, data, settings) {
                // Implementation for map rendering
            }
        });
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prismjs/1.24.1/themes/prism-okaidia.min.css">
    <style>
        .component-container {
            min-height: 300px;
            position: relative;
        }

        pre {
            margin: 0;
            max-height: 300px;
            overflow: auto;
        }
    </style>
@endpush
