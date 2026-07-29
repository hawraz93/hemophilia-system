<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Unique Codes & Identifiers
            $table->string('patient_code')->unique(); // e.g. PAT-2026-0001
            $table->string('hiwa_code')->nullable();
            $table->string('membership_number')->nullable()->unique();
            $table->string('national_id')->nullable();

            // Personal Information
            $table->string('first_name');
            $table->string('father_name');
            $table->string('grandfather_name');
            $table->string('full_name')->index();
            $table->string('gender')->default('male');
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->string('marital_status')->nullable();
            $table->integer('children_count')->default(0);

            // Contact & Address Information
            $table->string('phone')->index();
            $table->string('secondary_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('district')->nullable();
            $table->string('governorate')->default('سلێمانی');

            // Medical Information
            $table->string('blood_group')->nullable();
            $table->string('hemophilia_type')->default('A');
            $table->string('severity')->default('moderate');
            $table->string('inhibitor_status')->default('unknown');
            $table->string('hepatitis_b')->default('negative');
            $table->string('hepatitis_c')->default('negative');
            $table->string('hiv')->default('negative');
            $table->text('comorbidities')->nullable();
            $table->text('disability_special_needs')->nullable();
            $table->text('medical_notes')->nullable();

            // System Computed Classification (Green / Yellow / Red)
            $table->string('list_status')->default('yellow')->index();
            $table->boolean('unreachable_flag')->default(false);
            $table->timestamp('last_contacted_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
