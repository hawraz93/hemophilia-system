<?php

namespace App\Console\Commands;

use App\Models\Patient;
use App\Services\PatientStatusEvaluator;
use Illuminate\Console\Command;

class EvaluatePatientStatusesCommand extends Command
{
    protected $signature = 'app:evaluate-patient-statuses';
    protected $description = 'Re-evaluate the Green / Yellow / Red list status of every patient';

    public function handle()
    {
        $count = 0;

        Patient::query()->chunkById(200, function ($patients) use (&$count) {
            foreach ($patients as $patient) {
                PatientStatusEvaluator::evaluate($patient);
                $count++;
            }
        });

        $this->info("Evaluated {$count} patients.");

        return self::SUCCESS;
    }
}
