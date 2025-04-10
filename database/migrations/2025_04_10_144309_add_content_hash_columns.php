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
        Schema::table('monitoring_logs', function (Blueprint $table) {
            $table->string('content_hash', 32)->nullable()->after('details');
        });

        Schema::table('websites', function (Blueprint $table) {
            $table->string('content_hash', 32)->nullable()->after('last_response_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_logs', function (Blueprint $table) {
            $table->dropColumn('content_hash');
        });

        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('content_hash');
        });
    }
};
