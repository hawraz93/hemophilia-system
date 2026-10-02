<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Association guidelines & decisions (membership conditions, expulsions, requests...) stored as PDFs
        Schema::create('guidelines', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('document_date')->index();
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guidelines');
    }
};
