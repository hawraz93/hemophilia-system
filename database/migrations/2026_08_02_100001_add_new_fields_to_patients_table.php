<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('membership_type')->default('ordinary')->after('membership_number');
            $table->string('party_affiliation')->nullable()->after('membership_type');
            $table->string('voting_card_number')->nullable()->after('national_id');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['membership_type', 'party_affiliation', 'voting_card_number']);
        });
    }
};
