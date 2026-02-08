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

        // Allow multiple roles separated by comma (e.g., 'role:faculty,admin')
        $allowedRoles = explode(',', str_replace(' ', '', $role));

        // Dean users are also allowed through faculty gates
        if (in_array('faculty', $allowedRoles) && !in_array('dean', $allowedRoles)) {
            $allowedRoles[] = 'dean';
        }

        $user = auth()->guard('web')->user();

        if (!$user->hasAnyRole($allowedRoles)) {
            abort(403, 'You do not have permission to access this resource');
        }

        return $next($request);
    }
}