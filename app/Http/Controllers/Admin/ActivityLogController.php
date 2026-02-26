<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    /**
     * Export activity logs to a text file.
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        // Apply filters (Reuse logic or extract to scope/trait if used often)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        if ($action = $request->input('action')) {
            $query->byAction($action);
        }

        if ($userId = $request->input('user_id')) {
            $query->byUser($userId);
        }

        if ($from = $request->input('date_from')) {
            $query->where('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->where('created_at', '<=', $to . ' 23:59:59');
        }

        $logs = $query->get();
        $fileName = 'activity_logs_' . date('Y-m-d_H-i-s') . '.txt';

        // Build header
        $separator = str_repeat('=', 120);
        $content = "ACTIVITY LOGS EXPORT\n";
        $content .= "Generated: " . now()->format('F d, Y h:i:s A') . "\n";
        $content .= "Total Records: " . $logs->count() . "\n";
        $content .= $separator . "\n\n";

        // Column header
        $content .= str_pad('DATE', 14)
            . str_pad('TIME', 14)
            . str_pad('USER', 28)
            . str_pad('ROLE', 18)
            . str_pad('ACTION', 22)
            . str_pad('DESCRIPTION', 50)
            . 'IP ADDRESS' . "\n";
        $content .= str_repeat('-', 120) . "\n";

        // Rows
        foreach ($logs as $log) {
            $userName = $log->user ? $log->user->name : 'Unknown';
            $role     = $log->user ? $log->user->getPrimaryRole() : 'N/A';
            $date     = $log->created_at->format('Y-m-d');
            $time     = $log->created_at->format('h:i:s A');
            $action   = ucfirst(str_replace('_', ' ', $log->action));
            $desc     = $log->description ?? '';
            $ip       = $log->ip_address ?? 'N/A';

            $content .= str_pad($date, 14)
                . str_pad($time, 14)
                . str_pad(mb_substr($userName, 0, 26), 28)
                . str_pad(mb_substr($role, 0, 16), 18)
                . str_pad(mb_substr($action, 0, 20), 22)
                . str_pad(mb_substr($desc, 0, 48), 50)
                . $ip . "\n";
        }

        $content .= "\n" . $separator . "\n";
        $content .= "END OF REPORT\n";

        // Ensure temp directory exists
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Write to temporary file
        $tempFile = $tempDir . '/' . $fileName;
        file_put_contents($tempFile, $content);

        // Return file download response
        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'text/plain',
        ])->deleteFileAfterSend(true);
    }
}
