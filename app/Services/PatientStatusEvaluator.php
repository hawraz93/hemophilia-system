<?php

namespace App\Services;

use App\Enums\PatientListStatus;
use App\Models\Patient;
use Carbon\Carbon;

class PatientStatusEvaluator
{
    /**
     * Evaluate and update patient list status (Green / Yellow / Red).
     */
    public static function evaluate(Patient $patient): PatientListStatus
    {
        // 1. Check if patient is marked unreachable or not contacted for over 90 days
        $ninetyDaysAgo = Carbon::now()->subDays(90);
        $isUnreachable = $patient->unreachable_flag || 
            ($patient->last_contacted_at && $patient->last_contacted_at->lt($ninetyDaysAgo));

        if ($isUnreachable) {
            $status = PatientListStatus::Red;
        } else {
            // 2. Check if essential fields are complete
            $hasCompleteForm = !empty($patient->first_name) &&
                !empty($patient->father_name) &&
                !empty($patient->grandfather_name) &&
                !empty($patient->phone) &&
                !empty($patient->dob) &&
                !empty($patient->blood_group) &&
                !empty($patient->membership_number) &&
                !empty($patient->national_id) &&
                !empty($patient->address);

            // 3. Check if documents are uploaded
            $hasUploadedDocuments = $patient->documents()->exists();

            // 4. Check if membership payment has been made
            $hasPaidMembership = $patient->membershipPayments()->exists();

            // FULL MEMBER status is GREEN when Form + Documents + Payment are all satisfied
            $hasCompleteData = $hasCompleteForm && $hasUploadedDocuments && $hasPaidMembership;

            $status = $hasCompleteData ? PatientListStatus::Green : PatientListStatus::Yellow;
        }

        // Save status if changed
        if ($patient->list_status !== $status) {
            $patient->list_status = $status;
            $patient->saveQuietly();
        }

        return $status;
    }
}
