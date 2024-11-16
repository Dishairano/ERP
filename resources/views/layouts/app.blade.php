<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - ERP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @yield('styles')
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }
        .navbar {
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            background-color: #fff;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 500;
        }
        .main-content {
            flex: 1;
            padding: 2rem 0;
        }
        .footer {
            padding: 1rem 0;
            background-color: #fff;
            border-top: 1px solid #dee2e6;
        }
        .nav-link {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: all 0.2s;
        }
        .nav-link:hover {
            background-color: rgba(0,0,0,.05);
        }
        .dropdown-menu {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
            border: none;
        }
        @guest
        body {
            background-color: #f8f9fa;
        }
        .main-content {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        .footer {
            background-color: transparent;
            border: none;
        }
        @endguest
    </style>
</head>
<body>
    @auth
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">ERP System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                               href="{{ url('/dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} ERP System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>
