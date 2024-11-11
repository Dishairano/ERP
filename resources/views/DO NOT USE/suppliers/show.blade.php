@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            {{ $supplier->name }}
                            @if ($supplier->is_critical)
                                <span class="badge badge-warning">Kritiek</span>
                            @endif
                        </h4>
                        <div>
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Bewerken
                            </a>
                            <a href="{{ route('suppliers.contracts', $supplier) }}" class="btn btn-primary">
                                <i class="fas fa-file-contract"></i> Contracten
                            </a>
                            <a href="{{ route('suppliers.performance', $supplier) }}" class="btn btn-success">
                                <i class="fas fa-chart-line"></i> Prestaties
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Contactgegevens</h5>
                                <table class="table">
                                    <tr>
                                        <th>Contactpersoon:</th>
                                        <td>{{ $supplier->contact_person }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $supplier->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telefoon:</th>
                                        <td>{{ $supplier->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Adres:</th>
                                        <td>
                                            {{ $supplier->address }}<br>
                                            {{ $supplier->postal_code }} {{ $supplier->city }}<br>
                                            {{ $supplier->country }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Bedrijfsgegevens</h5>
                                <table class="table">
                                    <tr>
                                        <th>BTW Nummer:</th>
                                        <td>{{ $supplier->tax_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>KvK Nummer:</th>
                                        <td>{{ $supplier->registration_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Classificatie:</th>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($supplier->classification) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if ($supplier->status === 'active')
                                                <span class="badge badge-success">Actief</span>
                                            @else
                                                <span class="badge badge-danger">Inactief</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Prestatie Metrics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6>Kwaliteit Score</h6>
                                        <div class="progress-circle" data-value="{{ $performanceMetrics['quality'] }}">
                                            <div class="progress-circle-value h2">{{ $performanceMetrics['quality'] }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6>Levering Score</h6>
                                        <div class="progress-circle" data-value="{{ $performanceMetrics['delivery'] }}">
                                            <div class="progress-circle-value h2">{{ $performanceMetrics['delivery'] }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6>Service Score</h6>
                                        <div class="progress-circle" data-value="{{ $performanceMetrics['service'] }}">
                                            <div class="progress-circle-value h2">{{ $performanceMetrics['service'] }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6>Totaal Score</h6>
                                        <div class="progress-circle" data-value="{{ $performanceMetrics['overall'] }}">
                                            <div class="progress-circle-value h2">{{ $performanceMetrics['overall'] }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Actieve Contracten</h5>
                        <a href="{{ route('suppliers.contracts', $supplier) }}" class="btn btn-sm btn-primary">
                            Alle Contracten
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Contract Nr</th>
                                        <th>Start Datum</th>
                                        <th>Eind Datum</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supplier->contracts as $contract)
                                        <tr>
                                            <td>{{ $contract->contract_number }}</td>
                                            <td>{{ $contract->start_date->format('d-m-Y') }}</td>
                                            <td>
                                                {{ $contract->end_date->format('d-m-Y') }}
                                                @if ($contract->isExpiring())
                                                    <span class="badge badge-warning">Verloopt Binnenkort</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $contract->isActive() ? 'success' : 'danger' }}">
                                                    {{ $contract->isActive() ? 'Actief' : 'Inactief' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recente Beoordelingen</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#evaluationModal">
                            Nieuwe Beoordeling
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Datum</th>
                                        <th>Order Ref</th>
                                        <th>Score</th>
                                        <th>Commentaar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supplier->evaluations as $evaluation)
                                        <tr>
                                            <td>{{ $evaluation->evaluation_date->format('d-m-Y') }}</td>
                                            <td>{{ $evaluation->order_reference }}</td>
                                            <td>{{ number_format($evaluation->overall_rating, 1) }}/5</td>
                                            <td>{{ Str::limit($evaluation->comments, 50) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluation Modal -->
    <div class="modal fade" id="evaluationModal" tabindex="-1" role="dialog" aria-labelledby="evaluationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('suppliers.evaluate', $supplier) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="evaluationModalLabel">Nieuwe Leveranciersbeoordeling</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="order_reference">Order Referentie</label>
                            <input type="text" class="form-control" id="order_reference" name="order_reference"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="delivery_time_rating">Levering Score (1-5)</label>
                            <input type="number" class="form-control" id="delivery_time_rating"
                                name="delivery_time_rating" min="1" max="5" required>
                        </div>
                        <div class="form-group">
                            <label for="quality_rating">Kwaliteit Score (1-5)</label>
                            <input type="number" class="form-control" id="quality_rating" name="quality_rating"
                                min="1" max="5" required>
                        </div>
                        <div class="form-group">
                            <label for="communication_rating">Communicatie Score (1-5)</label>
                            <input type="number" class="form-control" id="communication_rating"
                                name="communication_rating" min="1" max="5" required>
                        </div>
                        <div class="form-group">
                            <label for="price_rating">Prijs Score (1-5)</label>
                            <input type="number" class="form-control" id="price_rating" name="price_rating"
                                min="1" max="5" required>
                        </div>
                        <div class="form-group">
                            <label for="comments">Opmerkingen</label>
                            <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                        <button type="submit" class="btn btn-primary">Beoordeling Opslaan</button>
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
@endsection
