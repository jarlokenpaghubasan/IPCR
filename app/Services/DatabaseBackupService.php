<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class DatabaseBackupService
{
    /**
     * Create a database backup.
     *
     * @return array ['success' => bool, 'message' => string, 'filename' => string|null, 'size' => string|null]
     */
    public function createBackup()
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');

            $backupDir = storage_path('app/backups');
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $filename = $dbName . '_' . date('Y-m-d_His') . '.sql';
            $filePath = $backupDir . '/' . $filename;

            // Build mysqldump command
            $mysqldumpPath = $this->findMysqldump();
            $command = "\"{$mysqldumpPath}\" --host={$dbHost} --port={$dbPort} --user={$dbUser}";

            if (!empty($dbPass)) {
                $command .= " --password=\"{$dbPass}\"";
            }

            $command .= " --single-transaction --routines --triggers {$dbName} > \"{$filePath}\" 2>&1";

            exec($command, $output, $returnCode);

            if ($returnCode !== 0 || !file_exists($filePath) || filesize($filePath) === 0) {
                // Clean up empty file
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                return [
                    'success' => false,
                    'message' => 'Backup failed. Error: ' . implode("\n", $output)
                ];
            }

            return [
                'success' => true,
                'message' => "Backup created successfully: {$filename}",
                'filename' => $filename,
                'size' => $this->formatBytes(filesize($filePath))
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Find mysqldump binary path.
     */
    private function findMysqldump(): string
    {
        // XAMPP paths (Windows)
        $paths = [
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\xampp\\mysql\\bin\\mysqldump',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            'mysqldump', // fallback to PATH
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return 'mysqldump';
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
