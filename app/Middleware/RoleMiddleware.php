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
        if (!auth()->guard('web')->check()) {
            return redirect()->route('login.selection');
        }

        if (auth()->guard('web')->user()->role !== $role) {
            return redirect()->route('login.selection')->withErrors(['message' => 'Unauthorized access']);
        }

        return $next($request);
    }
}