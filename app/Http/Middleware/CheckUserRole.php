<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AccessControlList;

class CheckUserRole
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @param  string  ...$roles
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
   */
  public function handle(Request $request, Closure $next, ...$roles)
  {
    $user = Auth::user();

    if (!$user) {
      return redirect('/login');
    }

    // First check direct role field in users table
    if (in_array($user->role, $roles)) {
      return $next($request);
    }

    // Then check ACL roles
    $hasRole = AccessControlList::where('user_id', $user->id)
      ->whereIn('role', $roles)
      ->where(function ($query) {
        $query->whereNull('expires_at')
          ->orWhere('expires_at', '>', now());
      })
      ->exists();

    if (!$hasRole) {
      return redirect('/')->with('error', 'You do not have permission to access this resource.');
    }

    return $next($request);
  }
}
