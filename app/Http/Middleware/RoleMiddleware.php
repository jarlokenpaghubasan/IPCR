<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // If user is not authenticated, redirect to login selection
        if (!auth()->check()) {
            return redirect('/');
        }

        // If user's role doesn't match the required role, redirect to login selection
        if (auth()->user()->role !== $role) {
            return redirect('/')->withErrors(['message' => 'You do not have access to this page.']);
        }

        return $next($request);
    }
}