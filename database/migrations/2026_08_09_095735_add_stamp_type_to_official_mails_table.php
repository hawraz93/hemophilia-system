<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('official_mails', function (Blueprint $table) {
            $table->string('stamp_type')->default('online')->after('direction'); // 'online' or 'manual'
            $table->text('letter_body')->nullable()->after('reason_subject');
        });
    }

    public function down(): void
    {
        Schema::table('official_mails', function (Blueprint $table) {
            $table->dropColumn(['stamp_type', 'letter_body']);
        });
    }
};
