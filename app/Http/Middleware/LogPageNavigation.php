<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogPageNavigation
{
    /**
     * Pages to log navigation for (route names).
     */
    protected array $trackedRoutes = [
        'faculty.dashboard'       => 'Faculty Dashboard',
        'faculty.my-ipcrs'        => 'My IPCRs',
        'faculty.profile'         => 'Faculty Profile',
        'dean.dashboard'          => 'Dean Dashboard',
        'dean.review.faculty-submissions' => 'Faculty Submissions Review',
        'dean.review.dean-submissions'    => 'Dean Submissions Review',
        'director.dashboard'      => 'Director Dashboard',
        'admin.dashboard'         => 'Admin Dashboard',
        'admin.users.index'       => 'User Management',
        'admin.users.create'      => 'Create User',
        'admin.users.edit'        => 'Edit User',
        'admin.users.show'        => 'View User',
        'admin.database.index'    => 'Database Management',
        'admin.activity-logs.index' => 'Activity Logs',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log GET requests for authenticated users that return HTML pages
        if (
            $request->isMethod('GET') &&
            auth()->check() &&
            !$request->ajax() &&
            !$request->wantsJson() &&
            $response->getStatusCode() === 200
        ) {
            $routeName = $request->route()?->getName();

            if ($routeName && isset($this->trackedRoutes[$routeName])) {
                $pageName = $this->trackedRoutes[$routeName];

                ActivityLogService::log(
                    'page_visited',
                    "Visited {$pageName}",
                    null,
                    ['route' => $routeName, 'url' => $request->fullUrl()]
                );
            }
        }

        return $response;
    }
}
