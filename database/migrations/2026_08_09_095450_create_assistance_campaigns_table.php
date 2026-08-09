<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistance_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('food');
            $table->string('source_funder')->nullable();
            $table->unsignedBigInteger('amount_per_patient')->default(0);
            $table->unsignedInteger('max_recipients')->default(1);
            $table->date('campaign_date');
            $table->text('notes')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('assistance_campaign_patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('assistance_campaigns')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->timestamp('received_at')->useCurrent();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'patient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistance_campaign_patients');
        Schema::dropIfExists('assistance_campaigns');
    }
};
