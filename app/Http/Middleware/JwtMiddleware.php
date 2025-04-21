<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
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
        // Check if user is logged in and has a refresh token
        if (Auth::check() && session()->has('refresh_token')) {
            return $next($request);
        }

        // If no token is found or user isn't authenticated, redirect to login
        Auth::logout();
        return redirect()->route('login')
            ->withErrors(['email' => 'Please login to access this page.']);
    }
}
