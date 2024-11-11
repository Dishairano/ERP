<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string  ...$roles
   * @return mixed
   */
  public function handle(Request $request, Closure $next, ...$roles): Response
  {
    if (!$request->user()) {
      return redirect()->route('login');
    }

    // Admin role has access to everything
    if ($request->user()->hasRole('admin')) {
      return $next($request);
    }

    // Check if user has any of the required roles
    foreach ($roles as $role) {
      if ($request->user()->hasRole($role)) {
        return $next($request);
      }
    }

    abort(403, 'Unauthorized action.');
  }
}
