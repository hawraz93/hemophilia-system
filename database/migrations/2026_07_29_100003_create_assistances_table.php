<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistances', function (Blueprint $table) {
            $table->id();
            $table->string('assistance_number')->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->date('assistance_date');
            $table->string('category')->default('financial');
            $table->unsignedBigInteger('amount')->default(0); // IQD value/amount
            $table->string('source_funder')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistances');
    }
};
