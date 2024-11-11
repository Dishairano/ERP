@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - View Performance Evaluation')

@section('content')
    <h4 class="fw-bold">Performance Evaluation Details</h4>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Employee</h6>
                    <p class="fs-5">{{ $evaluation->employee->name }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Evaluator</h6>
                    <p class="fs-5">{{ $evaluation->evaluator->name }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Evaluation Date</h6>
                    <p class="fs-5">{{ $evaluation->evaluation_date->format('Y-m-d') }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Overall Performance Score</h6>
                    <div class="d-flex align-items-center">
                        <div class="progress flex-grow-1 me-3" style="height: 10px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ ($evaluation->performance_score / 5) * 100 }}%"
                                aria-valuenow="{{ $evaluation->performance_score }}" aria-valuemin="0" aria-valuemax="5">
                            </div>
                        </div>
                        <span class="fs-5">{{ $evaluation->performance_score }}/5</span>
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <h6 class="text-muted">Comments</h6>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($evaluation->comments)) !!}
                    </div>
                </div>

                <div class="col-12">
                    <h5 class="mb-4">Performance Criteria</h5>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Job Knowledge</h6>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ ($evaluation->job_knowledge / 5) * 100 }}%">
                                    </div>
                                </div>
                                <span>{{ $evaluation->job_knowledge }}/5</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted">Work Quality</h6>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ ($evaluation->work_quality / 5) * 100 }}%">
                                    </div>
                                </div>
                                <span>{{ $evaluation->work_quality }}/5</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted">Initiative</h6>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ ($evaluation->initiative / 5) * 100 }}%">
                                    </div>
                                </div>
                                <span>{{ $evaluation->initiative }}/5</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted">Communication Skills</h6>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ ($evaluation->communication / 5) * 100 }}%">
                                    </div>
                                </div>
                                <span>{{ $evaluation->communication }}/5</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted">Teamwork</h6>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ ($evaluation->teamwork / 5) * 100 }}%">
                                    </div>
                                </div>
                                <span>{{ $evaluation->teamwork }}/5</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('hrm.performance-evaluations.edit', $evaluation->id) }}" class="btn btn-primary">Edit
                    Evaluation</a>
                <a href="{{ route('hrm.performance-evaluations') }}" class="btn btn-secondary">Back to List</a>
                <form action="{{ route('hrm.performance-evaluations.destroy', $evaluation->id) }}" method="POST"
                    class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this evaluation?')">
                        Delete Evaluation
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
