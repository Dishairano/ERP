<div {!! $attributes !!}>
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ $component->name }}</h5>
            <div class="card-actions">
                @if ($component->refresh_interval)
                    <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary refresh-btn">
                        <i class="ri-refresh-line"></i>
                    </button>
                @endif
                <div class="dropdown">
                    <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="#" class="dropdown-item" data-action="fullscreen">
                            <i class="ri-fullscreen-line me-2"></i> Fullscreen
                        </a>
                        <a href="#" class="dropdown-item" data-action="export-csv">
                            <i class="ri-file-excel-line me-2"></i> Export CSV
                        </a>
                        @can('update', $component)
                            <a href="{{ route('dashboard-components.edit', [$component->dashboard, $component]) }}"
                                class="dropdown-item">
                                <i class="ri-edit-line me-2"></i> Edit
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            @foreach ($data['columns'] as $column)
                                <th {!! $column['sortable'] ? 'class="sortable"' : '' !!}>
                                    {{ $column['label'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['rows'] as $row)
                            <tr>
                                @foreach ($data['columns'] as $column)
                                    <td>{{ $row[$column['name']] }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if ($component->description)
            <div class="card-footer">
                <small class="text-muted">{{ $component->description }}</small>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const component = new DashboardComponent('{{ $component->id }}', {!! json_encode($settings) !!});
            component.init({!! json_encode($data) !!});
        });
    </script>
@endpush
