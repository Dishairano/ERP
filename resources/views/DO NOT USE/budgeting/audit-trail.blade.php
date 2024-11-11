@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Budget Audit Trail</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach ($budget->audit_trail as $audit)
                                <div class="time-label">
                                    <span
                                        class="bg-primary">{{ \Carbon\Carbon::parse($audit['timestamp'])->format('Y-m-d H:i') }}</span>
                                </div>
                                <div>
                                    <i class="fas fa-user bg-primary"></i>
                                    <div class="timeline-item">
                                        <span class="time">
                                            <i class="fas fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($audit['timestamp'])->diffForHumans() }}
                                        </span>
                                        <h3 class="timeline-header">
                                            {{ $audit['action'] }}
                                        </h3>
                                        <div class="timeline-body">
                                            {{ $audit['details'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
