<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cv_profiles', function (Blueprint $table) {
            $table->json('upgraded_data')->nullable()->after('parsed_data');
            $table->string('upgrade_status', 20)->default('none')->after('status');
            $table->timestamp('upgraded_at')->nullable()->after('parsed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cv_profiles', function (Blueprint $table) {
            $table->dropColumn(['upgraded_data', 'upgrade_status', 'upgraded_at']);
        });
    }
};
