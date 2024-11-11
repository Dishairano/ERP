<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class CheckUserAuthenticated
{
  public function handle(Request $request, Closure $next)
  {
    // Check if user is already authenticated
    if (Auth::check()) {
      return $next($request);
    }

    // Check if the 'user_auth' cookie exists
    if ($authCode = Cookie::get('user_auth')) {
      // Verify the auth code with the database
      $user = User::where('remember_token', $authCode)->first();

      if ($user) {
        // Authenticate the user for the current request
        Auth::login($user);
        return $next($request);
      }
    }

    // Only redirect if not already on login-related routes
    if (!$request->is('login*') && !$request->is('logout*')) {
      return redirect()->route('login');
    }

    return $next($request);
  }
}
