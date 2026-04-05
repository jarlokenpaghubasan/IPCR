<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                $route = $this->resolveDashboardRoute($user);
                if ($route !== null) {
                    return redirect()->route($route);
                }
            }
        }

        return $next($request);
    }

    private function resolveDashboardRoute($user): ?string
    {
        if ($user->hasRole('admin') || $user->hasRole('hr')) {
            return 'admin.dashboard';
        }

        if ($user->hasRole('director')) {
            return 'director.dashboard';
        }

        if ($user->hasRole('faculty') || $user->hasRole('dean')) {
            return 'faculty.dashboard';
        }

        if ($user->hasPermission('admin.dashboard')) {
            return 'admin.dashboard';
        }

        if ($user->hasPermission('director.dashboard')) {
            return 'director.dashboard';
        }

        if ($user->hasPermission('faculty.dashboard')) {
            return 'faculty.dashboard';
        }

        return null;
    }
}
