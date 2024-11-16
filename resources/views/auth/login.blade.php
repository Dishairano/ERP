<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            color: #333;
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #6c757d;
            margin-bottom: 0;
        }
        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #696cff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.1);
        }
        .btn-login {
            background-color: #696cff;
            border-color: #696cff;
            padding: 0.75rem 1rem;
            font-weight: 500;
            width: 100%;
        }
        .btn-login:hover {
            background-color: #5f60e6;
            border-color: #5f60e6;
        }
        .form-check-input:checked {
            background-color: #696cff;
            border-color: #696cff;
        }
        .invalid-feedback {
            font-size: 0.85rem;
        }
        .alert {
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back!</h1>
            <p>Please sign in to continue</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autocomplete="email"
                       autofocus>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       required
                       autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           name="remember"
                           id="remember"
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-login btn-primary mb-3">
                Sign In
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
