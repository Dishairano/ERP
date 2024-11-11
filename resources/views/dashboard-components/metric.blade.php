<div {!! $attributes !!}>
    <div class="card h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    @if ($data['icon'])
                        <div class="metric-icon me-2">
                            <i class="{{ $data['icon'] }}"></i>
                        </div>
                    @endif
                    <h6 class="card-subtitle text-muted mb-0">{{ $component->name }}</h6>
                </div>
                @if ($component->refresh_interval)
                    <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary refresh-btn">
                        <i class="ri-refresh-line"></i>
                    </button>
                @endif
            </div>

            <div class="metric-value">
                <h2 class="mb-0 {{ $data['color'] ? 'text-' . $data['color'] : '' }}">
                    {{ $data['value'] }}
                </h2>

                @if (!empty($data['comparison']))
                    <div class="metric-change mt-2">
                        <span class="badge bg-{{ $data['comparison']['direction'] === 'up' ? 'success' : 'danger' }}">
                            <i class="ri-arrow-{{ $data['comparison']['direction'] }}-line"></i>
                            {{ number_format($data['comparison']['percentage'], 1) }}%
                        </span>
                        <small class="text-muted ms-1">vs previous period</small>
                    </div>
                @endif
            </div>

            @if (!empty($data['sparkline']))
                <div class="metric-sparkline mt-3" style="height: {{ $data['sparkline']['height'] }}px"></div>
            @endif
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
