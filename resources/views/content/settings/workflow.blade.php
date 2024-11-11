@extends('layouts/contentNavbarLayout')

@section('title', 'Workflow Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Workflow Settings</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.workflow.update') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="default_approval_chain">Default Approval Chain</label>
                                        <select class="form-control @error('default_approval_chain') is-invalid @enderror"
                                            id="default_approval_chain" name="default_approval_chain" required>
                                            @foreach ($settings['approval_chains'] ?? [] as $chain)
                                                <option value="{{ $chain['name'] }}"
                                                    {{ old('default_approval_chain', $settings['default_approval_chain'] ?? '') == $chain['name'] ? 'selected' : '' }}>
                                                    {{ $chain['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('default_approval_chain')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="auto_approval_threshold">Auto-Approval Threshold</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number"
                                                class="form-control @error('auto_approval_threshold') is-invalid @enderror"
                                                id="auto_approval_threshold" name="auto_approval_threshold"
                                                value="{{ old('auto_approval_threshold', $settings['auto_approval_threshold'] ?? '') }}"
                                                step="0.01" min="0">
                                        </div>
                                        <small class="form-text text-muted">Requests below this amount will be
                                            auto-approved</small>
                                        @error('auto_approval_threshold')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="escalation_time">Escalation Time (Hours)</label>
                                        <input type="number"
                                            class="form-control @error('escalation_time') is-invalid @enderror"
                                            id="escalation_time" name="escalation_time"
                                            value="{{ old('escalation_time', $settings['escalation_time'] ?? '') }}"
                                            min="1">
                                        <small class="form-text text-muted">Time before escalating to next approver</small>
                                        @error('escalation_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="reminder_frequency">Reminder Frequency (Hours)</label>
                                        <input type="number"
                                            class="form-control @error('reminder_frequency') is-invalid @enderror"
                                            id="reminder_frequency" name="reminder_frequency"
                                            value="{{ old('reminder_frequency', $settings['reminder_frequency'] ?? '') }}"
                                            min="1">
                                        <small class="form-text text-muted">How often to send reminder notifications</small>
                                        @error('reminder_frequency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5>Approval Chains</h5>
                                    <div id="approval-chains">
                                        @foreach ($settings['approval_chains'] ?? [] as $index => $chain)
                                            <div class="card mb-3 approval-chain">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Chain Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="approval_chains[{{ $index }}][name]"
                                                                    value="{{ $chain['name'] }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-chain">
                                                                Remove Chain
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="approval-steps mt-3">
                                                        @foreach ($chain['steps'] as $stepIndex => $step)
                                                            <div class="row mb-2 approval-step">
                                                                <div class="col-md-5">
                                                                    <select class="form-control"
                                                                        name="approval_chains[{{ $index }}][steps][{{ $stepIndex }}][role]"
                                                                        required>
                                                                        @foreach ($roles ?? [] as $role)
                                                                            <option value="{{ $role->id }}"
                                                                                {{ $step['role'] == $role->id ? 'selected' : '' }}>
                                                                                {{ $role->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-step">
                                                                        ×
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <button type="button" class="btn btn-info btn-sm mt-2 add-step">
                                                        Add Step
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="button" class="btn btn-success" id="add-chain">
                                        Add Approval Chain
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save Workflow Settings</button>
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
            const approvalChains = document.getElementById('approval-chains');
            const addChainBtn = document.getElementById('add-chain');

            // Template for new approval chain
            const chainTemplate = `
                <div class="card mb-3 approval-chain">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Chain Name</label>
                                    <input type="text" class="form-control" name="approval_chains[{index}][name]" required>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-danger btn-sm remove-chain">
                                    Remove Chain
                                </button>
                            </div>
                        </div>
                        <div class="approval-steps mt-3">
                        </div>
                        <button type="button" class="btn btn-info btn-sm mt-2 add-step">
                            Add Step
                        </button>
                    </div>
                </div>
            `;

            // Template for new approval step
            const stepTemplate = `
                <div class="row mb-2 approval-step">
                    <div class="col-md-5">
                        <select class="form-control" name="approval_chains[{chainIndex}][steps][{stepIndex}][role]" required>
                            @foreach ($roles ?? [] as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-step">×</button>
                    </div>
                </div>
            `;

            // Add new approval chain
            addChainBtn.addEventListener('click', function() {
                const chainIndex = document.querySelectorAll('.approval-chain').length;
                const newChain = chainTemplate.replace(/{index}/g, chainIndex);
                approvalChains.insertAdjacentHTML('beforeend', newChain);
            });

            // Remove approval chain
            approvalChains.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-chain')) {
                    e.target.closest('.approval-chain').remove();
                }
            });

            // Add approval step
            approvalChains.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-step')) {
                    const chain = e.target.closest('.approval-chain');
                    const chainIndex = Array.from(document.querySelectorAll('.approval-chain')).indexOf(
                        chain);
                    const stepsContainer = chain.querySelector('.approval-steps');
                    const stepIndex = stepsContainer.querySelectorAll('.approval-step').length;
                    const newStep = stepTemplate
                        .replace(/{chainIndex}/g, chainIndex)
                        .replace(/{stepIndex}/g, stepIndex);
                    stepsContainer.insertAdjacentHTML('beforeend', newStep);
                }
            });

            // Remove approval step
            approvalChains.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-step')) {
                    e.target.closest('.approval-step').remove();
                }
            });
        });
    </script>
@endpush
