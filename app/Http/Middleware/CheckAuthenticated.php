<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthenticated
{
  public function handle(Request $request, Closure $next)
  {
    if (!Auth::check()) {
      if ($request->expectsJson()) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
      }
      return redirect()->route('login');
    }
    return $next($request);
  }
}
