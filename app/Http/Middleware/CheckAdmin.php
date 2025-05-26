<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has the 'admin' role
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        // If not, redirect to the home page or show an unauthorized message
        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}
