<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use App\Contracts\Authorizable;

class CheckUserAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip authentication check for login routes
        if ($request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        // Check if user is already authenticated
        if (Auth::check()) {
            /** @var Authorizable $user */
            $user = Auth::user();

            // Check if route requires permission
            if ($request->route() && $request->route()->middleware()) {
                $routeMiddlewares = $request->route()->middleware();
                foreach ($routeMiddlewares as $middleware) {
                    if (strpos($middleware, 'permission:') === 0) {
                        $permission = substr($middleware, strlen('permission:'));
                        if (method_exists($user, 'hasPermission') && !$user->hasPermission($permission)) {
                            abort(403, 'Unauthorized action.');
                        }
                    }
                }
            }

            return $next($request);
        }

        // Check if the 'user_auth' cookie exists
        if ($authCode = Cookie::get('user_auth')) {
            // Verify the auth code with the database
            $user = User::where('remember_token', $authCode)->first();

            if ($user) {
                // Authenticate the user for the current request
                Auth::login($user);
                return $this->handle($request, $next);
            }
        }

        // Redirect to login if not authenticated
        return redirect()->route('login');
    }
}
