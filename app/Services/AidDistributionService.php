<?php

namespace App\Services;

use App\Enums\FundingSource;
use App\Models\Assistance;
use App\Models\AssistanceCampaign;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Hands out units from a received aid batch (campaign) to patients and keeps
 * the patient's own aid history in sync with the batch.
 */
class AidDistributionService
{
    /**
     * @throws RuntimeException when the batch is empty or the patient already received from it
     */
    public static function distribute(AssistanceCampaign $campaign, Patient $patient, string|Carbon|null $date = null): Assistance
    {
        $date = Carbon::parse($date ?? $campaign->distribution_date ?? now());

        return DB::transaction(function () use ($campaign, $patient, $date) {
            // Lock the batch row so two users cannot hand out the last unit at the same time
            $campaign = AssistanceCampaign::whereKey($campaign->id)->lockForUpdate()->firstOrFail();

            if ($campaign->patients()->count() >= $campaign->max_recipients) {
                throw new RuntimeException('هیچ دانەیەک لەم هاوکارییە لە کۆگادا نەماوە.');
            }

            if ($campaign->patients()->where('patient_id', $patient->id)->exists()) {
                throw new RuntimeException('ئەم نەخۆشە پێشتر لەم هاوکارییە وەریگرتووە.');
            }

            $campaign->patients()->attach($patient->id, ['received_at' => $date]);

            $assistance = Assistance::create([
                'assistance_number' => CodeGenerator::next(Assistance::class, 'assistance_number', 'AID'),
                'patient_id' => $patient->id,
                'campaign_id' => $campaign->id,
                'assistance_date' => $date->toDateString(),
                'category' => $campaign->category->value ?? $campaign->category,
                'amount' => $campaign->amount_per_patient,
                'source_funder' => $campaign->source_funder,
                'funding_source' => FundingSource::Campaign,
                'notes' => 'وەرگیراو لە کەمپینی هاوکاری گشتی: ' . $campaign->title,
                'user_id' => auth()->id(),
            ]);

            AuditLoggerService::log('assistance_campaign_patient_added', $patient, null, [
                'campaign_id' => $campaign->id,
                'campaign' => $campaign->title,
                'date' => $date->toDateString(),
            ]);

            return $assistance;
        });
    }

    /** Return a patient's unit to the store and remove the matching aid record. */
    public static function revoke(AssistanceCampaign $campaign, Patient $patient): void
    {
        DB::transaction(function () use ($campaign, $patient) {
            $campaign->patients()->detach($patient->id);
            Assistance::where('campaign_id', $campaign->id)->where('patient_id', $patient->id)->delete();

            AuditLoggerService::log('assistance_campaign_patient_removed', $patient, [
                'campaign_id' => $campaign->id,
                'campaign' => $campaign->title,
            ]);
        });
    }
}
