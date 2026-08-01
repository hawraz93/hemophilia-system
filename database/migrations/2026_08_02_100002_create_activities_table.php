<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('activity_type')->default('seminar'); // e.g. workshop, seminar, medical_campaign, assistance_distribution, meeting, awareness
            $table->date('activity_date');
            $table->string('location')->nullable();
            $table->string('organizer')->nullable();
            $table->integer('participants_count')->default(0);
            $table->unsignedBigInteger('budget')->default(0);
            $table->string('status')->default('completed'); // planned, completed, cancelled
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
