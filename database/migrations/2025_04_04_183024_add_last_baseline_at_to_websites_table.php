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
        Schema::table('websites', function (Blueprint $table) {
            $table->timestamp('last_baseline_at')->nullable()->after('last_checked_at');
            $table->foreignId('baseline_screenshot_id')->nullable()->after('last_baseline_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('last_baseline_at');
            $table->dropColumn('baseline_screenshot_id');
        });
    }
};
