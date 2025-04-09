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
            $table->json('notification_settings')->nullable();
            $table->string('ip_override')->nullable();
            $table->boolean('use_ip_override')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('notification_settings');
            $table->dropColumn('ip_override');
            $table->dropColumn('use_ip_override');
        });
    }
};
