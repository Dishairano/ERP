<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use App\Models\User;

class LoginController extends Controller
{
  /**
   * Display the login form
   *
   * @return \Illuminate\View\View
   */
  public function showLoginForm()
  {
    return view('content.authentications.auth-login');
  }

  /**
   * Handle the login request
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function login(Request $request)
  {
    // Validate the request
    $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string',
    ]);

    // Get the remember me value
    $remember = $request->has('remember');

    // Attempt to log the user in
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
      $request->session()->regenerate();

      // Get the authenticated user
      $user = Auth::user();

      // Generate a unique code for the cookie if remember me is checked
      if ($remember) {
        $uniqueCode = Str::random(40);
        User::where('id', $user->id)->update(['remember_token' => $uniqueCode]);
        Cookie::queue(Cookie::make('user_auth', $uniqueCode, 4320, null, null, false, true));
      }

      return redirect()->intended('/');
    }

    // If unsuccessful, redirect back with an error message
    return back()
      ->withErrors([
        'email' => 'The provided credentials do not match our records.',
      ])
      ->withInput($request->only('email'));
  }

  /**
   * Handle the logout request
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function logout(Request $request)
  {
    // Clear the auth code in the database
    $user = Auth::user();
    if ($user) {
      User::where('id', $user->id)->update(['remember_token' => null]);
    }

    // Log the user out
    Auth::logout();

    // Invalidate the session
    $request->session()->invalidate();

    // Regenerate the CSRF token
    $request->session()->regenerateToken();

    // Clear the authentication cookie
    Cookie::queue(Cookie::forget('user_auth'));

    // Redirect to login page
    return redirect('/login');
  }
}
