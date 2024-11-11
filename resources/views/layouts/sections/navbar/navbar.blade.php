@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    $containerNav = $containerNav ?? 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';
    $authUser = Auth::user(); // Define the auth user
@endphp

<!-- Navbar -->
@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
        id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{ $containerNav }}">
@endif

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-6">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20])</span>
            <span class="app-brand-text demo menu-text fw-semibold ms-1">{{ config('variables.templateName') }}</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
            <i class="ri-close-fill align-middle"></i>
        </a>
    </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="ri-menu-fill ri-24px"></i>
        </a>
    </div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse"
    style="padding-left:25px;padding-right:25px">
    <!-- Search -->
    <div class="navbar-nav align-items-center">
        <div class="nav-item d-flex align-items-center">
            <i class="ri-search-line ri-22px me-1_5"></i>
            <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2 ms-50" placeholder="Search..."
                aria-label="Search...">
        </div>
    </div>
    <!-- /Search -->

    <ul class="navbar-nav flex-row align-items-center ms-auto">

        <!-- Theme Toggle Button -->
        <a class="nav-link px-2 ms-2" href="javascript:void(0);" id="theme-toggle" style="margin-right:10px;">
            <i class="ri-sun-line ri-22px theme-icon-light"></i>
            <i class="ri-moon-line ri-22px theme-icon-dark d-none"></i>
        </a>
        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle">
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                <li>
                    <a class="dropdown-item" href="javascript:void(0);">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <div class="avatar avatar-online">
                                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt
                                        class="w-px-40 h-auto rounded-circle">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 small">{{ $authUser->first_name }}
                                    {{ $authUser->last_name }}</h6>
                                <small class="text-muted">
                                    <-- Placeholder -->
                                </small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ url('/pages/pages-account-settings-account') }}">
                        <i class="ri-user-3-line ri-22px me-2"></i>
                        <span class="align-middle">My Profile</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ url('/account/settings') }}">
                        <i class='ri-settings-4-line ri-22px me-2'></i>
                        <span class="align-middle">Settings</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="javascript:void(0);">
                        <span class="d-flex align-items-center align-middle">
                            <i class="flex-shrink-0 ri-file-text-line ri-22px me-3"></i>
                            <span class="flex-grow-1 align-middle">Billing</span>
                            <span
                                class="flex-shrink-0 badge badge-center rounded-pill bg-danger h-px-20 d-flex align-items-center justify-content-center">4</span>
                        </span>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <div class="d-grid px-4 pt-2 pb-1">
                        <a class="btn btn-danger d-flex" href="javascript:void(0);"
                            onclick="document.getElementById('logout-form').submit();">
                            <small class="align-middle">Logout</small>
                            <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </li>
        <!--/ User -->
    </ul>
</div>

@if (!isset($navbarDetached))
    </div>
@endif
</nav>
<!-- / Navbar -->

<script>
    // Theme toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        const lightIcon = themeToggle.querySelector('.theme-icon-light');
        const darkIcon = themeToggle.querySelector('.theme-icon-dark');

        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);
        updateIcons(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcons(newTheme);
        });

        function updateIcons(theme) {
            if (theme === 'light') {
                lightIcon.classList.remove('d-none');
                darkIcon.classList.add('d-none');
            } else {
                lightIcon.classList.add('d-none');
                darkIcon.classList.remove('d-none');
            }
        }
    });
</script>

<style>
    /* Light theme variables */
    :root {
        --bg-color: #f5f5f9;
        --text-color: #566a7f;
        --heading-color: #32325d;
        --card-bg: #ffffff;
        --border-color: rgba(67, 89, 113, 0.1);
        --nav-bg: #ffffff;
        --input-bg: #ffffff;
        --input-text: #566a7f;
        --shadow-color: rgba(67, 89, 113, 0.1);
        --menu-bg: #ffffff;
        --menu-text: #566a7f;
        --menu-active-bg: #696cff;
        --menu-active-text: #ffffff;
        --link-color: #696cff;
        --muted-text: #8592a3;
        --card-header: #32325d;
        --stats-text: #566a7f;
        --stats-number: #32325d;
        --chart-text: #566a7f;
        --chart-grid: #eceef1;
    }

    /* Dark theme variables with enhanced contrast */
    [data-theme="dark"] {
        --bg-color: #1a1b2e;
        --text-color: #ffffff;
        --heading-color: #ffffff;
        --card-bg: #2b2c40;
        --border-color: #444564;
        --nav-bg: #2b2c40;
        --input-bg: #2b2c40;
        --input-text: #ffffff;
        --shadow-color: rgba(0, 0, 0, 0.2);
        --menu-bg: #2b2c40;
        --menu-text: #ffffff;
        --menu-active-bg: #696cff;
        --menu-active-text: #ffffff;
        --link-color: #9d9eff;
        --muted-text: #b4b5d2;
        --card-header: #ffffff;
        --stats-text: #ffffff;
        --stats-number: #ffffff;
        --chart-text: #ffffff;
        --chart-grid: #444564;
    }

    /* Global styles */
    body {
        background-color: var(--bg-color);
        color: var(--text-color);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .h1,
    .h2,
    .h3,
    .h4,
    .h5,
    .h6 {
        color: var(--heading-color);
        font-weight: 600;
    }

    /* Card styles with enhanced shadows */
    .card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        box-shadow: 0 0.25rem 1.125rem var(--shadow-color);
        transition: all 0.3s ease;
    }

    .card-header {
        color: var(--heading-color);
        font-weight: 600;
    }

    /* Stats and numbers with enhanced visibility */
    .stats-number {
        color: var(--stats-number) !important;
        font-weight: 700;
    }

    .stats-text {
        color: var(--stats-text) !important;
        font-weight: 500;
    }

    /* Navbar styles with better contrast */
    .bg-navbar-theme {
        background-color: var(--nav-bg) !important;
        color: var(--text-color) !important;
        box-shadow: 0 1px 0 var(--border-color);
        transition: all 0.3s ease;
    }

    /* Form control styles with improved readability */
    .form-control {
        background-color: var(--input-bg);
        color: var(--input-text);
        border-color: var(--border-color);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background-color: var(--input-bg);
        color: var(--input-text);
        border-color: #696cff;
    }

    .form-control::placeholder {
        color: var(--muted-text);
        opacity: 0.8;
    }

    /* Dropdown styles with enhanced visibility */
    .dropdown-menu {
        background-color: var(--card-bg);
        border-color: var(--border-color);
        box-shadow: 0 0.25rem 1rem var(--shadow-color);
    }

    .dropdown-item {
        color: var(--text-color);
        font-weight: 500;
    }

    .dropdown-item:hover {
        background-color: var(--menu-active-bg);
        color: var(--menu-active-text);
    }

    /* Menu styles with better contrast */
    .menu {
        background-color: var(--menu-bg);
        color: var(--menu-text);
    }

    .menu-item.active {
        background-color: var(--menu-active-bg);
        color: var(--menu-active-text);
        font-weight: 600;
    }

    /* Theme toggle button styles */
    #theme-toggle {
        color: var(--text-color);
        transition: color 0.3s ease;
        font-size: 1.25rem;
    }

    #theme-toggle:hover {
        color: var(--link-color);
    }

    /* Table styles with improved readability */
    .table {
        color: var(--text-color);
    }

    .table th {
        color: var(--heading-color);
        font-weight: 600;
    }

    /* Badge styles */
    .badge {
        color: #fff;
        font-weight: 600;
    }

    /* Button styles with enhanced visibility */
    .btn {
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #696cff;
        border-color: #696cff;
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: #5f60e6;
        border-color: #5f60e6;
        color: #ffffff;
    }

    /* Text colors with better contrast */
    .text-muted {
        color: var(--muted-text) !important;
    }

    .text-primary {
        color: var(--link-color) !important;
    }

    /* Links with enhanced visibility */
    a {
        color: var(--link-color);
        transition: color 0.3s ease;
        font-weight: 500;
    }

    a:hover {
        color: var(--link-color);
        opacity: 0.9;
    }

    /* Specific text elements with improved contrast */
    .congratulations-text {
        color: var(--heading-color) !important;
        font-weight: 700;
    }

    .growth-text {
        color: var(--text-color) !important;
        font-weight: 500;
    }

    .amount-text {
        color: var(--stats-number) !important;
        font-weight: 700;
    }

    .percentage-text {
        color: var(--stats-text) !important;
        font-weight: 600;
    }

    /* Chart and graph text */
    .apexcharts-text {
        fill: var(--chart-text) !important;
    }

    .apexcharts-grid line {
        stroke: var(--chart-grid) !important;
    }

    /* Enhanced focus styles for better accessibility */
    *:focus {
        outline: 2px solid var(--link-color);
        outline-offset: 2px;
    }

    /* Small text enhancement */
    small {
        color: var(--muted-text);
        font-weight: 500;
    }
</style>
