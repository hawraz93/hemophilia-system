<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('official_mails', function (Blueprint $table) {
            $table->id();
            $table->string('mail_number')->index();
            $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $table->string('direction')->default('incoming'); // incoming or outgoing
            $table->date('mail_date');
            $table->string('sender_recipient');
            $table->string('reason_subject');
            $table->string('file_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('official_mails');
    }
};
