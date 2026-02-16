<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseBackupService;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RunSystemBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:run-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run system backup if scheduled';

    /**
     * Execute the console command.
     */
    public function handle(DatabaseBackupService $backupService)
    {
        $this->info('Checking backup schedule...');

        if (!Storage::exists('backup_settings.json')) {
            $this->info('No settings found. key: ' . storage_path('app/backup_settings.json'));
            return;
        }

        $settings = json_decode(Storage::get('backup_settings.json'), true);

        if (!$settings['enabled']) {
            $this->info('Automatic backups are disabled.');
            return;
        }

        $frequency = $settings['frequency'];
        $time = $settings['time'];
        
        $now = Carbon::now();
        $scheduledTime = Carbon::createFromFormat('H:i', $time);
        
        // Check if current time matches scheduled time (within this minute)
        if ($now->format('H:i') !== $scheduledTime->format('H:i')) {
            $this->info('Not time yet. Scheduled for ' . $time . ', currently ' . $now->format('H:i'));
            return;
        }

        // Frequency check
        $shouldRun = false;
        
        switch ($frequency) {
            case 'daily':
                $shouldRun = true;
                break;
            case 'weekly':
                // Run on Sunday? Or user configurable? Let's assume Sunday for now, or check generic "weekly" logic
                // For simplicity, let's say "Weekly" means "Every Monday"
                if ($now->isMonday()) {
                    $shouldRun = true;
                }
                break;
            case 'monthly':
                // Run on the 1st of the month
                if ($now->day === 1) {
                    $shouldRun = true;
                }
                break;
        }

        if ($shouldRun) {
            $this->info('Starting backup...');
            $result = $backupService->createBackup();
            
            if ($result['success']) {
                $this->info('Backup successful: ' . $result['filename']);
            } else {
                $this->error('Backup failed: ' . $result['message']);
            }
        } else {
            $this->info('Skipping. Frequency requirement not met.');
        }
    }
}
