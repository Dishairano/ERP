@extends('layouts/contentNavbarLayout')

@section('title', 'Settings')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
@endsection

@section('content')
    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="settings-nav">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('settings.general') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.general') ? 'active' : '' }}">
                                <i class="ri-settings-4-line me-2"></i>General Settings
                            </a>
                            <a href="{{ route('settings.company') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.company') ? 'active' : '' }}">
                                <i class="ri-building-line me-2"></i>Company Profile
                            </a>
                            <a href="{{ route('settings.notifications') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.notifications') ? 'active' : '' }}">
                                <i class="ri-notification-line me-2"></i>Notifications
                            </a>
                            <a href="{{ route('settings.integrations') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.integrations') ? 'active' : '' }}">
                                <i class="ri-plug-line me-2"></i>Integrations
                            </a>
                            <a href="{{ route('settings.backup') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.backup') ? 'active' : '' }}">
                                <i class="ri-database-2-line me-2"></i>Backup & Recovery
                            </a>
                            <a href="{{ route('settings.roles') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.roles') ? 'active' : '' }}">
                                <i class="ri-shield-user-line me-2"></i>Roles & Permissions
                            </a>
                            <a href="{{ route('settings.users') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.users') ? 'active' : '' }}">
                                <i class="ri-user-settings-line me-2"></i>User Management
                            </a>
                            <a href="{{ route('settings.audit-log') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.audit-log') ? 'active' : '' }}">
                                <i class="ri-file-list-3-line me-2"></i>Audit Log
                            </a>
                            <a href="{{ route('settings.security') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.security') ? 'active' : '' }}">
                                <i class="ri-shield-keyhole-line me-2"></i>Security Settings
                            </a>
                            <a href="{{ route('settings.localization') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.localization') ? 'active' : '' }}">
                                <i class="ri-global-line me-2"></i>Localization
                            </a>
                            <a href="{{ route('settings.email') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.email') ? 'active' : '' }}">
                                <i class="ri-mail-settings-line me-2"></i>Email Configuration
                            </a>
                            <a href="{{ route('settings.workflow') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.workflow') ? 'active' : '' }}">
                                <i class="ri-flow-chart me-2"></i>Workflow Settings
                            </a>
                            <a href="{{ route('settings.api') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('settings.api') ? 'active' : '' }}">
                                <i class="ri-code-line me-2"></i>API Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    @yield('settings-content')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
@endsection

@section('page-script')
    <script>
        // Initialize perfect scrollbar for the settings navigation
        const settingsNav = document.querySelector('.settings-nav');
        if (settingsNav) {
            new PerfectScrollbar(settingsNav, {
                wheelPropagation: false
            });
        }
    </script>
@endsection
