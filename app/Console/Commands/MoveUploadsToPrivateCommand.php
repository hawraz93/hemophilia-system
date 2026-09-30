<?php

namespace App\Console\Commands;

use App\Models\OfficialMail;
use App\Models\PatientDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MoveUploadsToPrivateCommand extends Command
{
    protected $signature = 'app:move-uploads-private';
    protected $description = 'Move previously uploaded patient documents and mail files from the public disk to the private disk';

    public function handle()
    {
        $paths = PatientDocument::pluck('file_path')
            ->merge(OfficialMail::whereNotNull('file_path')->pluck('file_path'))
            ->unique();

        $moved = 0;
        foreach ($paths as $path) {
            if (! Storage::disk('public')->exists($path) || Storage::disk('local')->exists($path)) {
                continue;
            }

            Storage::disk('local')->writeStream($path, Storage::disk('public')->readStream($path));
            Storage::disk('public')->delete($path);
            $moved++;
        }

        $this->info("Moved {$moved} files to the private disk.");

        return self::SUCCESS;
    }
}
