<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use ZipArchive;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'app:backup-database {--keep=14 : Number of most recent backups to keep}';
    protected $description = 'Create a backup of the system database and uploaded files';

    public function handle()
    {
        $backupDir = storage_path('app/backups');
        File::ensureDirectoryExists($backupDir, 0755);

        $timestamp = date('Y-m-d_H-i-s');
        $tmpDir = storage_path("app/backups/tmp_{$timestamp}");
        File::ensureDirectoryExists($tmpDir);

        try {
            $dumpFile = $this->dumpDatabase($tmpDir);
            if (! $dumpFile) {
                return self::FAILURE;
            }

            $zipPath = "{$backupDir}/backup_{$timestamp}.zip";
            $this->createArchive($zipPath, $dumpFile);
        } finally {
            File::deleteDirectory($tmpDir);
        }

        $this->pruneOldBackups($backupDir, (int) $this->option('keep'));

        $this->info('Backup created successfully: '.basename($zipPath));

        return self::SUCCESS;
    }

    /**
     * Dump the default database connection into $dir and return the file path.
     */
    private function dumpDatabase(string $dir): ?string
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if ($config['driver'] === 'sqlite') {
            $target = "{$dir}/database.sqlite";
            File::copy($config['database'], $target);

            return $target;
        }

        if (! in_array($config['driver'], ['mysql', 'mariadb'], true)) {
            $this->error("Unsupported database driver: {$config['driver']}");

            return null;
        }

        $target = "{$dir}/database.sql";
        $binary = $config['dump_binary'] ?? 'mysqldump';

        $result = Process::env(['MYSQL_PWD' => (string) $config['password']])
            ->timeout(600)
            ->run([
                $binary,
                '--host='.$config['host'],
                '--port='.$config['port'],
                '--user='.$config['username'],
                '--single-transaction',
                '--routines',
                '--default-character-set=utf8mb4',
                '--result-file='.$target,
                $config['database'],
            ]);

        if ($result->failed()) {
            $this->error('mysqldump failed: '.trim($result->errorOutput()));

            return null;
        }

        return $target;
    }

    /**
     * Zip the database dump together with all uploaded files.
     */
    private function createArchive(string $zipPath, string $dumpFile): void
    {
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile($dumpFile, basename($dumpFile));

        foreach (['private' => storage_path('app/private'), 'public' => storage_path('app/public')] as $name => $root) {
            if (! File::isDirectory($root)) {
                continue;
            }
            foreach (File::allFiles($root) as $file) {
                if ($file->getFilename() === '.gitignore') {
                    continue;
                }
                $zip->addFile($file->getPathname(), "files/{$name}/".str_replace('\\', '/', $file->getRelativePathname()));
            }
        }

        $zip->close();
    }

    private function pruneOldBackups(string $dir, int $keep): void
    {
        $backups = collect(File::files($dir))
            ->filter(fn ($f) => str_starts_with($f->getFilename(), 'backup_'))
            ->sortByDesc(fn ($f) => $f->getMTime())
            ->values();

        $backups->slice(max($keep, 1))->each(fn ($f) => File::delete($f->getPathname()));
    }
}
