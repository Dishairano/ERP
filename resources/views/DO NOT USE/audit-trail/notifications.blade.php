@extends('layouts/contentNavbarLayout')

@section('title', 'Audit Trail Notifications')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">System / Audit Trail /</span> Notifications
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Configure Audit Notifications</h5>
            </div>

            <form action="{{ route('audit-trail.notifications.update') }}" method="POST">
                @csrf
                <div class="card-body">
                    @foreach ($notifications as $notification)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="notifications[{{ $loop->index }}][event]"
                                        value="{{ $notification->event }}">
                                    <input type="checkbox" class="form-check-input" id="notification_{{ $loop->index }}"
                                        name="notifications[{{ $loop->index }}][enabled]" value="1"
                                        {{ $notification->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notification_{{ $loop->index }}">
                                        {{ $notification->description }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">{{ $notification->details }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Save Notifications</button>
                </div>
            </form>
        </div>
    </div>
@endsection
