@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Risk Matrix</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- High Priority Risks -->
                            <div class="col-md-4">
                                <div class="card bg-danger bg-opacity-10">
                                    <div class="card-header">
                                        <h5 class="card-title text-danger">High Priority</h5>
                                        <span class="badge bg-danger">{{ $matrix['high']['count'] }}</span>
                                    </div>
                                    <div class="card-body">
                                        @if ($matrix['high']['risks']->isNotEmpty())
                                            @foreach ($matrix['high']['risks'] as $risk)
                                                <div class="alert alert-danger">
                                                    <h6>{{ $risk->title }}</h6>
                                                    <p class="mb-0">{{ Str::limit($risk->description, 100) }}</p>
                                                    <small>Project: {{ $risk->project->name }}</small>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-3">
                                                <p class="mb-0">No high priority risks</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Medium Priority Risks -->
                            <div class="col-md-4">
                                <div class="card bg-warning bg-opacity-10">
                                    <div class="card-header">
                                        <h5 class="card-title text-warning">Medium Priority</h5>
                                        <span class="badge bg-warning">{{ $matrix['medium']['count'] }}</span>
                                    </div>
                                    <div class="card-body">
                                        @if ($matrix['medium']['risks']->isNotEmpty())
                                            @foreach ($matrix['medium']['risks'] as $risk)
                                                <div class="alert alert-warning">
                                                    <h6>{{ $risk->title }}</h6>
                                                    <p class="mb-0">{{ Str::limit($risk->description, 100) }}</p>
                                                    <small>Project: {{ $risk->project->name }}</small>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-3">
                                                <p class="mb-0">No medium priority risks</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Low Priority Risks -->
                            <div class="col-md-4">
                                <div class="card bg-info bg-opacity-10">
                                    <div class="card-header">
                                        <h5 class="card-title text-info">Low Priority</h5>
                                        <span class="badge bg-info">{{ $matrix['low']['count'] }}</span>
                                    </div>
                                    <div class="card-body">
                                        @if ($matrix['low']['risks']->isNotEmpty())
                                            @foreach ($matrix['low']['risks'] as $risk)
                                                <div class="alert alert-info">
                                                    <h6>{{ $risk->title }}</h6>
                                                    <p class="mb-0">{{ Str::limit($risk->description, 100) }}</p>
                                                    <small>Project: {{ $risk->project->name }}</small>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-3">
                                                <p class="mb-0">No low priority risks</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
