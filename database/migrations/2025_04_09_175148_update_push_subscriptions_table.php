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
            // Check if old columns exist before trying to drop them
            if (Schema::hasColumn('push_subscriptions', 'keys')) {
                $table->dropColumn('keys');
            }
            if (Schema::hasColumn('push_subscriptions', 'device_info')) {
                $table->dropColumn('device_info');
            }
            if (Schema::hasColumn('push_subscriptions', 'last_used_at')) {
                $table->dropColumn('last_used_at');
            }
            
            // Check if new columns don't exist before adding them
            if (!Schema::hasColumn('push_subscriptions', 'public_key')) {
                $table->string('public_key')->nullable()->after('endpoint');
            }
            if (!Schema::hasColumn('push_subscriptions', 'auth_token')) {
                $table->string('auth_token')->nullable()->after('public_key');
            }
            if (!Schema::hasColumn('push_subscriptions', 'content_encoding')) {
                $table->string('content_encoding')->nullable()->after('auth_token');
            }
            if (!Schema::hasColumn('push_subscriptions', 'options')) {
                $table->json('options')->nullable()->after('content_encoding');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('push_subscriptions', function (Blueprint $table) {
            // Remove new columns if they exist
            if (Schema::hasColumn('push_subscriptions', 'public_key')) {
                $table->dropColumn('public_key');
            }
            if (Schema::hasColumn('push_subscriptions', 'auth_token')) {
                $table->dropColumn('auth_token');
            }
            if (Schema::hasColumn('push_subscriptions', 'content_encoding')) {
                $table->dropColumn('content_encoding');
            }
            if (Schema::hasColumn('push_subscriptions', 'options')) {
                $table->dropColumn('options');
            }
            
            // Add back old columns if they don't exist
            if (!Schema::hasColumn('push_subscriptions', 'keys')) {
                $table->text('keys')->after('endpoint');
            }
            if (!Schema::hasColumn('push_subscriptions', 'device_info')) {
                $table->text('device_info')->nullable()->after('keys');
            }
            if (!Schema::hasColumn('push_subscriptions', 'last_used_at')) {
                $table->timestamp('last_used_at')->nullable()->after('device_info');
            }
        });
    }
};
