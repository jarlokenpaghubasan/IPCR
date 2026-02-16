<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\DatabaseBackupService;
use App\Services\ActivityLogService;

class DatabaseManagementController extends Controller
{
    protected $backupService;

    public function __construct(DatabaseBackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display backup listing and database stats.
     */
    public function index()
    {
        // Get database stats
        $dbName = config('database.connections.mysql.database');

        // Get database size
        $sizeResult = DB::select("SELECT
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
            FROM information_schema.tables
            WHERE table_schema = ?", [$dbName]);
        $dbSize = $sizeResult[0]->size_mb ?? 0;

        // Get table count
        $tableCount = count(DB::select("SHOW TABLES"));

        // Get backups from storage
        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $allBackups = collect(glob($backupDir . '/*.sql'))
            ->map(function ($path) {
                return [
                    'filename' => basename($path),
                    'size' => filesize($path),
                    'size_formatted' => $this->formatBytes(filesize($path)),
                    'created_at' => date('M d, Y H:i', filemtime($path)),
                    'timestamp' => filemtime($path),
                ];
            })
            ->sortByDesc('timestamp')
            ->values();

        // Manual Pagination
        $page = request()->get('page', 1);
        $perPage = 10;
        $total = $allBackups->count();
        
        $paginatedItems = $allBackups->slice(($page - 1) * $perPage, $perPage)->values();

        $backups = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $backupCount = $total;

        // Load Settings
        $settingsPath = storage_path('app/backup_settings.json');
        $settings = file_exists($settingsPath) 
            ? json_decode(file_get_contents($settingsPath), true) 
            : ['enabled' => false, 'frequency' => 'daily', 'time' => '00:00'];

        return view('admin.database.index', compact('dbSize', 'tableCount', 'backups', 'backupCount', 'dbName', 'settings'));
    }

    /**
     * Create a database backup.
     */
    public function backup()
    {
        $result = $this->backupService->createBackup();

        if ($result['success']) {
            ActivityLogService::log('backup_created', 'Created database backup');
            return redirect()->route('admin.database.index')
                ->with('success', $result['message']);
        } else {
            return redirect()->route('admin.database.index')
                ->with('error', $result['message']);
        }
    }

    /**
     * Download a backup file.
     */
    public function download($filename)
    {
        $filePath = storage_path('app/backups/' . basename($filename));

        if (!file_exists($filePath)) {
            return redirect()->route('admin.database.index')
                ->with('error', 'Backup file not found.');
        }

        return response()->download($filePath);
    }

    /**
     * Restore database from a backup file.
     */
    public function restore($filename)
    {
        try {
            $filePath = storage_path('app/backups/' . basename($filename));

            if (!file_exists($filePath)) {
                return redirect()->route('admin.database.index')
                    ->with('error', 'Backup file not found.');
            }

            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');

            // Build mysql command
            $mysqlPath = $this->findMysql();
            $command = "\"{$mysqlPath}\" --host={$dbHost} --port={$dbPort} --user={$dbUser}";

            if (!empty($dbPass)) {
                $command .= " --password=\"{$dbPass}\"";
            }

            $command .= " {$dbName} < \"{$filePath}\" 2>&1";

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                return redirect()->route('admin.database.index')
                    ->with('error', 'Restore failed. Error: ' . implode("\n", $output));
            }

            ActivityLogService::log('backup_restored', 'Restored database from backup: ' . $filename);

            return redirect()->route('admin.database.index')
                ->with('success', "Database restored successfully from: {$filename}");

        } catch (\Exception $e) {
            return redirect()->route('admin.database.index')
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a backup file.
     */
    public function delete($filename)
    {
        $filePath = storage_path('app/backups/' . basename($filename));

        if (!file_exists($filePath)) {
            return redirect()->route('admin.database.index')
                ->with('error', 'Backup file not found.');
        }

        unlink($filePath);

        ActivityLogService::log('backup_deleted', 'Deleted backup file: ' . $filename);

        return redirect()->route('admin.database.index')
            ->with('success', "Backup deleted: {$filename}");
    }

    /**
     * Upload an external SQL file as a backup.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|max:102400', // 100MB max
        ]);

        $file = $request->file('sql_file');
        $originalName = $file->getClientOriginalName();

        // Validate it's a .sql file
        if (strtolower($file->getClientOriginalExtension()) !== 'sql') {
            return redirect()->route('admin.database.index')
                ->with('error', 'Only .sql files are allowed.');
        }

        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // Avoid filename collision
        $filename = pathinfo($originalName, PATHINFO_FILENAME) . '_uploaded_' . date('His') . '.sql';
        $file->move($backupDir, $filename);

        ActivityLogService::log('backup_uploaded', 'Uploaded backup file: ' . $filename);

        return redirect()->route('admin.database.index')
            ->with('success', "Backup uploaded: {$filename}");
    }

    /**
     * Update backup settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'nullable|in:on,1,true',
            'frequency' => 'required|in:daily,weekly,monthly',
            'time' => 'required|date_format:H:i',
        ]);

        $settings = [
            'enabled' => $request->has('enabled'),
            'frequency' => $validated['frequency'],
            'time' => $validated['time'],
        ];

        Storage::put('backup_settings.json', json_encode($settings, JSON_PRETTY_PRINT));

        ActivityLogService::log('settings_updated', 'Updated backup settings');

        return redirect()->route('admin.database.index')
            ->with('success', 'Backup settings updated successfully.');
    }

    /**
     * Find mysql binary path.
     */
    private function findMysql(): string
    {
        $paths = [
            'C:\\xampp\\mysql\\bin\\mysql.exe',
            'C:\\xampp\\mysql\\bin\\mysql',
            '/usr/bin/mysql',
            '/usr/local/bin/mysql',
            'mysql',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return 'mysql';
    }

    /**
     * Format bytes to human readable.
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
