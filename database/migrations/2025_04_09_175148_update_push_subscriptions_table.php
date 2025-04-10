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
        Schema::table('push_subscriptions', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn('keys');
            $table->dropColumn('device_info');
            $table->dropColumn('last_used_at');
            
            // Add new columns
            $table->string('public_key')->nullable()->after('endpoint');
            $table->string('auth_token')->nullable()->after('public_key');
            $table->string('content_encoding')->nullable()->after('auth_token');
            $table->json('options')->nullable()->after('content_encoding');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('push_subscriptions', function (Blueprint $table) {
            // Remove new columns
            $table->dropColumn('public_key');
            $table->dropColumn('auth_token');
            $table->dropColumn('content_encoding');
            $table->dropColumn('options');
            
            // Add back old columns
            $table->text('keys')->after('endpoint');
            $table->text('device_info')->nullable()->after('keys');
            $table->timestamp('last_used_at')->nullable()->after('device_info');
        });
    }
};
