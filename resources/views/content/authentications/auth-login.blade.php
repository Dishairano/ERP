@extends('layouts.blankLayout')

@section('title', 'Login Basic - Pages')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6 mx-4">

      <!-- Login -->
      <div class="card p-7">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="{{ url('/') }}" class="app-brand-link gap-3">
            <span class="app-brand-logo demo">@include('_partials.macros', ["height" => 20, "withbg" => 'fill: #fff;'])</span>
            <span class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span>
          </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-1">
          <h4 class="mb-1">Welcome to {{ config('variables.templateName') }}! 👋🏻</h4>
          <p class="mb-5">Please sign in to your account and start the adventure</p>

          <form id="formAuthentication" class="mb-5" action="{{ route('login') }}" method="POST">
            @csrf <!-- CSRF protection -->
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" required autofocus>
              <label for="email">Email</label>
            </div>
            <div class="mb-5">
              <div class="form-password-toggle">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" required />
                    <label for="password">Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line ri-20px"></i></span>
                </div>
              </div>
            </div>
            <div class="mb-5 pb-2 d-flex justify-content-between pt-2 align-items-center">
              <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                <label class="form-check-label" for="remember-me">Remember Me</label>
              </div>
              <a href="{{ url('auth/forgot-password-basic') }}" class="float-end mb-1">Forgot Password?</a>
            </div>
            <div class="mb-5">
              <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
            </div>
          </form>

          <p class="text-center mb-5">
            <span>New on our platform?</span>
            <a href="{{ url('auth/register-basic') }}">
              <span>Create an account</span>
            </a>
          </p>
        </div>
      </div>
      <!-- /Login -->

      <!-- Optional images for visual appeal -->
      <img src="{{ asset('assets/img/illustrations/tree-3.png') }}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block">
      <img src="{{ asset('assets/img/illustrations/auth-basic-mask-light.png') }}" class="authentication-image d-none d-lg-block" height="172" alt="triangle-bg">
      <img src="{{ asset('assets/img/illustrations/tree.png') }}" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block">
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<!-- Include Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Include Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  // Convert server-side errors and session messages to JavaScript
  var errors = <?php echo json_encode($errors->all()); ?>; // Converts the array of errors to JSON format
  var successMessage = <?php echo json_encode(session('status')); ?>; // Converts the session status message to JSON format

  // Display Toastr notifications if there are validation errors
  if (errors.length > 0) {
    errors.forEach(function(error) {
      toastr.error(error); // Display each error using Toastr
    });
  }

  // Display success message if it exists
  if (successMessage) {
    toastr.success(successMessage); // Display success message using Toastr
  }
</script>

@endsection