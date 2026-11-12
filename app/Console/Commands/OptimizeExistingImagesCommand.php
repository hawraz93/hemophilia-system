<?php

namespace App\Console\Commands;

use App\Models\OfficialMail;
use App\Models\PatientDocument;
use App\Services\UploadedFileStorageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeExistingImagesCommand extends Command
{
    protected $signature = 'app:optimize-existing-images {--dry-run : Report potential savings without modifying files or database}';

    protected $description = 'Resize and compress existing uploaded images on local/public disks to reduce storage usage';

    public function handle(UploadedFileStorageService $storageService): int
    {
        if (! extension_loaded('gd')) {
            $this->error('PHP GD extension is not loaded.');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $optimizedCount = 0;
        $savedBytes = 0;

        PatientDocument::query()->orderBy('id')->chunkById(50, function ($documents) use ($storageService, $dryRun, &$optimizedCount, &$savedBytes) {
            foreach ($documents as $document) {
                $result = $this->processPath($document->file_path, $storageService, $dryRun);
                if ($result === null) {
                    continue;
                }

                [$oldSize, $newSize] = $result;
                $optimizedCount++;
                $savedBytes += ($oldSize - $newSize);

                if (! $dryRun) {
                    $document->update(['file_size' => $newSize]);
                }
            }
        });

        OfficialMail::query()->whereNotNull('file_path')->orderBy('id')->chunkById(50, function ($mails) use ($storageService, $dryRun, &$optimizedCount, &$savedBytes) {
            foreach ($mails as $mail) {
                $result = $this->processPath($mail->file_path, $storageService, $dryRun);
                if ($result === null) {
                    continue;
                }

                [$oldSize, $newSize] = $result;
                $optimizedCount++;
                $savedBytes += ($oldSize - $newSize);
            }
        });

        $savedMb = number_format($savedBytes / (1024 * 1024), 2);
        $prefix = $dryRun ? '[DRY RUN] Would optimize' : 'Optimized';
        $this->info("{$prefix} {$optimizedCount} image(s), saving {$savedMb} MB.");

        return self::SUCCESS;
    }

    /**
     * @return array{int, int}|null
     */
    private function processPath(string $path, UploadedFileStorageService $storageService, bool $dryRun): ?array
    {
        foreach (['local', 'public'] as $disk) {
            if (! Storage::disk($disk)->exists($path)) {
                continue;
            }

            $oldSize = (int) Storage::disk($disk)->size($path);
            $newSize = $storageService->optimizeStoredImage($path, $disk, $dryRun);

            if ($newSize !== null && $newSize < $oldSize) {
                return [$oldSize, $newSize];
            }

            return null;
        }

        return null;
    }
}

