@extends('layouts/contentNavbarLayout')

@section('title', 'Risk Details')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">{{ $risk->title }}</h4>
                            <small class="text-muted">Project: {{ $project->name }}</small>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('projects.risks.edit', [$project->id, $risk->id]) }}"
                                class="btn btn-primary me-1">
                                <i data-feather="edit"></i> Edit Risk
                            </a>
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-secondary">
                                <i data-feather="arrow-left"></i> Back to Project
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Description</h5>
                            <p class="mb-4">{{ $risk->description }}</p>

                            <h5>Impact</h5>
                            <p class="mb-4">{{ $risk->impact }}</p>

                            <h5>Mitigation Strategy</h5>
                            <p class="mb-4">{{ $risk->mitigation_strategy }}</p>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Risk Details</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <i data-feather="tag" class="me-1"></i>
                                            <span class="fw-bold">Category:</span>
                                            <span>{{ ucfirst($risk->category) }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="alert-triangle" class="me-1"></i>
                                            <span class="fw-bold">Severity:</span>
                                            <span>Level {{ $risk->severity }} (of 5)</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="trending-up" class="me-1"></i>
                                            <span class="fw-bold">Likelihood:</span>
                                            <span>Level {{ $risk->likelihood }} (of 5)</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="activity" class="me-1"></i>
                                            <span class="fw-bold">Risk Level:</span>
                                            @php
                                                $riskLevel = $risk->severity * $risk->likelihood;
                                                $severityClass =
                                                    $riskLevel >= 16
                                                        ? 'danger'
                                                        : ($riskLevel >= 9
                                                            ? 'warning'
                                                            : ($riskLevel >= 4
                                                                ? 'info'
                                                                : 'success'));
                                            @endphp
                                            <span class="badge rounded-pill badge-light-{{ $severityClass }}">
                                                {{ $riskLevel }} / 25
                                            </span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="calendar" class="me-1"></i>
                                            <span class="fw-bold">Due Date:</span>
                                            <span>{{ $risk->due_date->format('M d, Y') }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <i data-feather="user" class="me-1"></i>
                                            <span class="fw-bold">Owner:</span>
                                            <span>{{ $risk->owner }}</span>
                                        </li>
                                        <li>
                                            <i data-feather="check-circle" class="me-1"></i>
                                            <span class="fw-bold">Status:</span>
                                            <span
                                                class="badge rounded-pill badge-light-{{ $risk->status === 'mitigated' ? 'success' : ($risk->status === 'assessed' ? 'info' : 'warning') }}">
                                                {{ ucfirst($risk->status) }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(function() {
            'use strict';

            // Initialize feather icons
            feather.replace();
        });
    </script>
@endsection
