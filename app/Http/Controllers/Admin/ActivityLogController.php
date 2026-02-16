<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display paginated activity logs with filters.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by action
        if ($action = $request->input('action')) {
            $query->byAction($action);
        }

        // Filter by user
        if ($userId = $request->input('user_id')) {
            $query->byUser($userId);
        }

        // Date range
        if ($from = $request->input('date_from')) {
            $query->where('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->where('created_at', '<=', $to . ' 23:59:59');
        }

        $logs = $query->paginate(25)->withQueryString();

        // Stats
        $totalLogs    = ActivityLog::count();
        $todayLogs    = ActivityLog::whereDate('created_at', today())->count();
        $uniqueToday  = ActivityLog::whereDate('created_at', today())->distinct('user_id')->count('user_id');

        // Dropdown data
        $actions = ActivityLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $users   = User::orderBy('name')->get(['id', 'name']);

        return view('admin.activity-logs.index', compact(
            'logs', 'totalLogs', 'todayLogs', 'uniqueToday',
            'actions', 'users'
        ));
    }
}
