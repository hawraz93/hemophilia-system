<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'app:backup-database';
    protected $description = 'Create a backup of the system database and storage files';

    public function handle()
    {
        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $timestamp = date('Y-m-d_H-i-s');
        $dbPath = database_path('database.sqlite');
        $backupFile = "{$backupDir}/backup_{$timestamp}.sqlite";

        if (File::exists($dbPath)) {
            File::copy($dbPath, $backupFile);
            $this->info("Database backup created successfully: backup_{$timestamp}.sqlite");
        } else {
            $this->error("Database file not found at: {$dbPath}");
            return 1;
        }

        return 0;
    }
}
