@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Add Component to {{ $dashboard->name }}</h3>
                        <a href="{{ route('dashboard-components.index', $dashboard) }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line"></i> Back to Components
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('dashboard-components.store', $dashboard) }}" method="POST"
                            id="componentForm">
                            @csrf

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <h4 class="mb-4">Basic Information</h4>

                                    <div class="mb-3">
                                        <label for="name" class="form-label required">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="type" class="form-label required">Component Type</label>
                                        <select class="form-select @error('type') is-invalid @enderror" id="type"
                                            name="type" required>
                                            <option value="">Select Type</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type }}"
                                                    {{ old('type') == $type ? 'selected' : '' }}>
                                                    {{ ucfirst($type) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="size" class="form-label required">Size</label>
                                        <select class="form-select @error('size') is-invalid @enderror" id="size"
                                            name="size" required>
                                            <option value="small" {{ old('size') == 'small' ? 'selected' : '' }}>Small
                                            </option>
                                            <option value="medium"
                                                {{ old('size', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="large" {{ old('size') == 'large' ? 'selected' : '' }}>Large
                                            </option>
                                        </select>
                                        @error('size')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="position" class="form-label">Position</label>
                                        <input type="number" class="form-control @error('position') is-invalid @enderror"
                                            id="position" name="position" value="{{ old('position') }}" min="0">
                                        @error('position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Leave empty for automatic positioning</small>
                                    </div>
                                </div>

                                <!-- Data Configuration -->
                                <div class="col-md-6">
                                    <h4 class="mb-4">Data Configuration</h4>

                                    <div class="mb-3">
                                        <label for="data_source" class="form-label">Data Source</label>
                                        <select class="form-select @error('data_source') is-invalid @enderror"
                                            id="data_source" name="data_source">
                                            <option value="">No Data Source</option>
                                            @foreach ($dataSources as $source)
                                                <option value="{{ $source->id }}"
                                                    {{ old('data_source') == $source->id ? 'selected' : '' }}>
                                                    {{ $source->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('data_source')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="visualization_type" class="form-label required">Visualization
                                            Type</label>
                                        <select class="form-select @error('visualization_type') is-invalid @enderror"
                                            id="visualization_type" name="visualization_type" required>
                                            <option value="">Select Visualization</option>
                                            <!-- These options will be dynamically updated based on component type -->
                                        </select>
                                        @error('visualization_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="refresh_interval" class="form-label">Refresh Interval (seconds)</label>
                                        <input type="number"
                                            class="form-control @error('refresh_interval') is-invalid @enderror"
                                            id="refresh_interval" name="refresh_interval"
                                            value="{{ old('refresh_interval', 300) }}" min="0">
                                        @error('refresh_interval')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="cache_duration" class="form-label">Cache Duration (seconds)</label>
                                        <input type="number"
                                            class="form-control @error('cache_duration') is-invalid @enderror"
                                            id="cache_duration" name="cache_duration"
                                            value="{{ old('cache_duration', 300) }}" min="0">
                                        @error('cache_duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Advanced Settings -->
                                <div class="col-12 mt-4">
                                    <h4 class="mb-4">Advanced Settings</h4>

                                    <div class="mb-3">
                                        <label for="settings" class="form-label">Component Settings (JSON)</label>
                                        <textarea class="form-control @error('settings') is-invalid @enderror" id="settings" name="settings" rows="4">{{ old('settings', '{}') }}</textarea>
                                        @error('settings')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="custom_styles" class="form-label">Custom Styles (JSON)</label>
                                        <textarea class="form-control @error('custom_styles') is-invalid @enderror" id="custom_styles" name="custom_styles"
                                            rows="4">{{ old('custom_styles', '{}') }}</textarea>
                                        @error('custom_styles')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_public"
                                                name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_public">Make Component Public</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line"></i> Create Component
                                </button>
                                <a href="{{ route('dashboard-components.index', $dashboard) }}"
                                    class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const visualizationSelect = document.getElementById('visualization_type');
            const settingsTextarea = document.getElementById('settings');

            // Visualization options for each component type
            const visualizationOptions = {
                chart: ['line', 'bar', 'pie', 'donut', 'area', 'scatter'],
                table: ['basic', 'sortable', 'filterable', 'paginated'],
                metric: ['single', 'multi', 'trend'],
                list: ['basic', 'ordered', 'grid', 'timeline'],
                calendar: ['month', 'week', 'agenda', 'timeline'],
                map: ['markers', 'heatmap', 'choropleth'],
                custom: ['custom']
            };

            // Default settings templates for each component type
            const defaultSettings = {
                chart: {
                    title: '',
                    subtitle: '',
                    xAxis: {
                        title: ''
                    },
                    yAxis: {
                        title: ''
                    },
                    legend: {
                        show: true,
                        position: 'bottom'
                    }
                },
                table: {
                    columns: [],
                    pagination: {
                        perPage: 10
                    },
                    sorting: {
                        enabled: true
                    },
                    filtering: {
                        enabled: true
                    }
                },
                metric: {
                    prefix: '',
                    suffix: '',
                    decimals: 0,
                    comparison: {
                        show: true,
                        type: 'previous_period'
                    }
                },
                list: {
                    itemTemplate: '',
                    sorting: {
                        enabled: true
                    },
                    filtering: {
                        enabled: true
                    }
                },
                calendar: {
                    defaultView: 'month',
                    firstDay: 1,
                    timeFormat: '24h'
                },
                map: {
                    center: {
                        lat: 0,
                        lng: 0
                    },
                    zoom: 2,
                    type: 'roadmap'
                },
                custom: {}
            };

            // Update visualization options when component type changes
            typeSelect.addEventListener('change', function() {
                const type = this.value;
                visualizationSelect.innerHTML = '<option value="">Select Visualization</option>';

                if (type && visualizationOptions[type]) {
                    visualizationOptions[type].forEach(option => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option;
                        optionElement.textContent = option.charAt(0).toUpperCase() + option.slice(
                            1);
                        visualizationSelect.appendChild(optionElement);
                    });

                    // Set default settings template
                    settingsTextarea.value = JSON.stringify(defaultSettings[type], null, 2);
                }
            });

            // Initialize JSON editors
            const settingsEditor = CodeMirror.fromTextArea(settingsTextarea, {
                mode: {
                    name: 'javascript',
                    json: true
                },
                theme: 'monokai',
                lineNumbers: true,
                autoCloseBrackets: true,
                matchBrackets: true,
                indentUnit: 2
            });

            const customStylesEditor = CodeMirror.fromTextArea(document.getElementById('custom_styles'), {
                mode: {
                    name: 'javascript',
                    json: true
                },
                theme: 'monokai',
                lineNumbers: true,
                autoCloseBrackets: true,
                matchBrackets: true,
                indentUnit: 2
            });

            // Form validation
            document.getElementById('componentForm').addEventListener('submit', function(e) {
                try {
                    JSON.parse(settingsEditor.getValue());
                    JSON.parse(customStylesEditor.getValue());
                } catch (error) {
                    e.preventDefault();
                    alert('Invalid JSON in settings or custom styles');
                }
            });
        });
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
    <style>
        .required:after {
            content: " *";
            color: red;
        }

        .CodeMirror {
            height: auto;
            min-height: 100px;
        }
    </style>
@endpush
