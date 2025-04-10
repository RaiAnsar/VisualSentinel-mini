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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('notify_downtime')->default(true);
            $table->boolean('notify_visual_changes')->default(true);
            $table->boolean('notify_ssl')->default(true);
            $table->boolean('notify_performance')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();

            // Each user can only have one notification settings record
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
