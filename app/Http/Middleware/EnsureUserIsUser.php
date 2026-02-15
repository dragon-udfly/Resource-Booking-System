<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'user') {
            return $next($request);
        }

        // Redirect admins to admin dashboard, others to login or home
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin')->with('error', 'Admins cannot access the user dashboard.');
        }

        return redirect()->route('login');
    }
}
