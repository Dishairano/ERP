@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Approvals')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Pending Time Registration Approvals</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Project</th>
                                        <th>Activity</th>
                                        <th>Hours</th>
                                        <th>Description</th>
                                        <th>Billable</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingRegistrations as $registration)
                                        <tr>
                                            <td>{{ $registration->user->name }}</td>
                                            <td>{{ $registration->date->format('M d, Y') }}</td>
                                            <td>{{ $registration->project->name }}</td>
                                            <td>{{ $registration->activity }}</td>
                                            <td>{{ number_format($registration->hours, 1) }}</td>
                                            <td>{{ Str::limit($registration->description, 50) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $registration->billable ? 'success' : 'secondary' }}">
                                                    {{ $registration->billable ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewRegistrationModal{{ $registration->id }}">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="approveRegistration({{ $registration->id }})">
                                                            <i class="ri-checkbox-circle-line me-1"></i> Approve
                                                        </a>
                                                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                            onclick="rejectRegistration({{ $registration->id }})">
                                                            <i class="ri-close-circle-line me-1"></i> Reject
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $pendingRegistrations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($pendingRegistrations as $registration)
        <!-- View Registration Modal -->
        <div class="modal fade" id="viewRegistrationModal{{ $registration->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Time Registration Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <h6>Employee</h6>
                            <p>{{ $registration->user->name }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Project</h6>
                            <p>{{ $registration->project->name }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Activity</h6>
                            <p>{{ $registration->activity }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Date</h6>
                            <p>{{ $registration->date->format('M d, Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Hours</h6>
                            <p>{{ number_format($registration->hours, 1) }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Description</h6>
                            <p>{{ $registration->description }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Billable</h6>
                            <span class="badge bg-{{ $registration->billable ? 'success' : 'secondary' }}">
                                {{ $registration->billable ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success"
                            onclick="approveRegistration({{ $registration->id }})">
                            Approve
                        </button>
                        <button type="button" class="btn btn-danger"
                            onclick="rejectRegistration({{ $registration->id }})">
                            Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Registration Modal -->
        <div class="modal fade" id="rejectRegistrationModal{{ $registration->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Time Registration</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('time-registration.update-status', $registration) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Rejection Reason</label>
                                <textarea class="form-control" name="rejection_reason" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('page-script')
    <script>
        function approveRegistration(id) {
            if (confirm('Are you sure you want to approve this time registration?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/time-registration/${id}/status`;
                form.innerHTML = `
            @csrf
            <input type="hidden" name="status" value="approved">
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function rejectRegistration(id) {
            const modal = new bootstrap.Modal(document.getElementById(`rejectRegistrationModal${id}`));
            modal.show();
        }
    </script>
@endsection
