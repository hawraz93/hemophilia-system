<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Date the organisation starts handing out a received aid batch
        Schema::table('assistance_campaigns', function (Blueprint $table) {
            $table->date('distribution_date')->nullable()->after('campaign_date');
        });

        // Link individual aid records to the stock they came from, and record which fund paid for them
        Schema::table('assistances', function (Blueprint $table) {
            $table->foreignId('campaign_id')->nullable()->after('patient_id')
                ->constrained('assistance_campaigns')->cascadeOnDelete();
            $table->string('funding_source')->nullable()->after('source_funder');
        });

        // Membership fee waivers (0 IQD) for members scoring 80+ points
        Schema::table('membership_payments', function (Blueprint $table) {
            $table->boolean('is_exempt')->default(false)->after('amount_paid');
        });

        // Backfill: aid records created from a campaign before this column existed
        $campaigns = DB::table('assistance_campaigns')->select('id', 'title')->get();
        foreach ($campaigns as $campaign) {
            $patientIds = DB::table('assistance_campaign_patients')
                ->where('campaign_id', $campaign->id)
                ->pluck('patient_id');

            DB::table('assistances')
                ->whereNull('campaign_id')
                ->whereIn('patient_id', $patientIds)
                ->where('notes', 'وەرگیراو لە کەمپینی هاوکاری گشتی: ' . $campaign->title)
                ->update(['campaign_id' => $campaign->id, 'funding_source' => 'campaign']);
        }
    }

    public function down(): void
    {
        Schema::table('membership_payments', function (Blueprint $table) {
            $table->dropColumn('is_exempt');
        });

        Schema::table('assistances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('campaign_id');
            $table->dropColumn('funding_source');
        });

        Schema::table('assistance_campaigns', function (Blueprint $table) {
            $table->dropColumn('distribution_date');
        });
    }
};
