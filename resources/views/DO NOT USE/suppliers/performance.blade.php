@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            @if (isset($supplier))
                                Performance - {{ $supplier->name }}
                            @else
                                Supplier Performance Overview
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        @if (isset($supplier))
                            <!-- Single Supplier Performance -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h6>Quality Score</h6>
                                            <div class="progress-circle"
                                                data-value="{{ $performanceData['quality']['current'] }}">
                                                <div class="progress-circle-value h2">
                                                    {{ $performanceData['quality']['current'] }}%</div>
                                            </div>
                                            <small class="text-muted">
                                                Trend: {{ number_format($performanceData['quality']['trend'], 1) }}%
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h6>Delivery Score</h6>
                                            <div class="progress-circle"
                                                data-value="{{ $performanceData['delivery']['current'] }}">
                                                <div class="progress-circle-value h2">
                                                    {{ $performanceData['delivery']['current'] }}%</div>
                                            </div>
                                            <small class="text-muted">
                                                Trend: {{ number_format($performanceData['delivery']['trend'], 1) }}%
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h6>Service Score</h6>
                                            <div class="progress-circle"
                                                data-value="{{ $performanceData['service']['current'] }}">
                                                <div class="progress-circle-value h2">
                                                    {{ $performanceData['service']['current'] }}%</div>
                                            </div>
                                            <small class="text-muted">
                                                Trend: {{ number_format($performanceData['service']['trend'], 1) }}%
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h6>Overall Score</h6>
                                            <div class="progress-circle"
                                                data-value="{{ ($performanceData['quality']['current'] + $performanceData['delivery']['current'] + $performanceData['service']['current']) / 3 }}">
                                                <div class="progress-circle-value h2">
                                                    {{ number_format(($performanceData['quality']['current'] + $performanceData['delivery']['current'] + $performanceData['service']['current']) / 3, 1) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Evaluations History -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Evaluation History</h5>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#evaluationModal">
                                                New Evaluation
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Order Ref</th>
                                                            <th>Quality</th>
                                                            <th>Delivery</th>
                                                            <th>Service</th>
                                                            <th>Price</th>
                                                            <th>Overall</th>
                                                            <th>Comments</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($evaluations as $evaluation)
                                                            <tr>
                                                                <td>{{ $evaluation->evaluation_date->format('d-m-Y') }}
                                                                </td>
                                                                <td>{{ $evaluation->order_reference }}</td>
                                                                <td>{{ number_format($evaluation->quality_rating, 1) }}/5
                                                                </td>
                                                                <td>{{ number_format($evaluation->delivery_time_rating, 1) }}/5
                                                                </td>
                                                                <td>{{ number_format($evaluation->communication_rating, 1) }}/5
                                                                </td>
                                                                <td>{{ number_format($evaluation->price_rating, 1) }}/5
                                                                </td>
                                                                <td>{{ number_format($evaluation->overall_rating, 1) }}/5
                                                                </td>
                                                                <td>{{ Str::limit($evaluation->comments, 50) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            {{ $evaluations->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- All Suppliers Performance Overview -->
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Supplier</th>
                                            <th>Quality Score</th>
                                            <th>Delivery Score</th>
                                            <th>Service Score</th>
                                            <th>Overall Score</th>
                                            <th>Recent Evaluations</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($performanceData as $data)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('suppliers.show', $data['supplier']) }}">
                                                        {{ $data['supplier']->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar {{ $data['metrics']['quality'] >= 60 ? 'bg-success' : 'bg-danger' }}"
                                                            role="progressbar"
                                                            style="width: {{ $data['metrics']['quality'] }}%">
                                                            {{ $data['metrics']['quality'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar {{ $data['metrics']['delivery'] >= 60 ? 'bg-success' : 'bg-danger' }}"
                                                            role="progressbar"
                                                            style="width: {{ $data['metrics']['delivery'] }}%">
                                                            {{ $data['metrics']['delivery'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar {{ $data['metrics']['service'] >= 60 ? 'bg-success' : 'bg-danger' }}"
                                                            role="progressbar"
                                                            style="width: {{ $data['metrics']['service'] }}%">
                                                            {{ $data['metrics']['service'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar {{ $data['metrics']['overall'] >= 60 ? 'bg-success' : 'bg-danger' }}"
                                                            role="progressbar"
                                                            style="width: {{ $data['metrics']['overall'] }}%">
                                                            {{ $data['metrics']['overall'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $data['evaluations']->count() }}</td>
                                                <td>
                                                    <a href="{{ route('suppliers.performance', $data['supplier']) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-chart-line"></i> Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($supplier))
        <!-- Evaluation Modal -->
        <div class="modal fade" id="evaluationModal" tabindex="-1" role="dialog" aria-labelledby="evaluationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('suppliers.evaluate', $supplier) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="evaluationModalLabel">New Supplier Evaluation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="order_reference">Order Reference</label>
                                <input type="text" class="form-control" id="order_reference" name="order_reference"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="delivery_time_rating">Delivery Score (1-5)</label>
                                <input type="number" class="form-control" id="delivery_time_rating"
                                    name="delivery_time_rating" min="1" max="5" required>
                            </div>
                            <div class="form-group">
                                <label for="quality_rating">Quality Score (1-5)</label>
                                <input type="number" class="form-control" id="quality_rating" name="quality_rating"
                                    min="1" max="5" required>
                            </div>
                            <div class="form-group">
                                <label for="communication_rating">Communication Score (1-5)</label>
                                <input type="number" class="form-control" id="communication_rating"
                                    name="communication_rating" min="1" max="5" required>
                            </div>
                            <div class="form-group">
                                <label for="price_rating">Price Score (1-5)</label>
                                <input type="number" class="form-control" id="price_rating" name="price_rating"
                                    min="1" max="5" required>
                            </div>
                            <div class="form-group">
                                <label for="comments">Comments</label>
                                <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Evaluation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize progress circles
                    document.querySelectorAll('.progress-circle').forEach(function(circle) {
                        const value = circle.dataset.value;
                        const color = value >= 60 ? '#28a745' : '#dc3545';
                        const radius = 40;
                        const circumference = 2 * Math.PI * radius;
                        const offset = circumference - (value / 100 * circumference);

                        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                        svg.setAttribute('class', 'progress-ring');
                        svg.setAttribute('width', '100');
                        svg.setAttribute('height', '100');

                        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        circle.setAttribute('class', 'progress-ring__circle');
                        circle.setAttribute('stroke', color);
                        circle.setAttribute('stroke-width', '8');
                        circle.setAttribute('fill', 'transparent');
                        circle.setAttribute('r', radius);
                        circle.setAttribute('cx', '50');
                        circle.setAttribute('cy', '50');
                        circle.style.strokeDasharray = `${circumference} ${circumference}`;
                        circle.style.strokeDashoffset = offset;

                        svg.appendChild(circle);
                        circle.parentNode.insertBefore(svg, circle);
                    });
                });
            </script>

            <style>
                .progress-circle {
                    position: relative;
                    display: inline-block;
                }

                .progress-ring__circle {
                    transition: stroke-dashoffset 0.35s;
                    transform: rotate(-90deg);
                    transform-origin: 50% 50%;
                }

                .progress-circle-value {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                }
            </style>
        @endpush
    @endif
@endsection
