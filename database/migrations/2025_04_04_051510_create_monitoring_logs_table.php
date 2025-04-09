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
        Schema::create('monitoring_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->integer('status_code')->nullable();
            $table->float('response_time')->nullable(); // in seconds
            $table->string('status'); // up, down, changed, warning
            $table->text('error_message')->nullable();
            $table->boolean('is_cdn_error')->default(false);
            $table->json('details')->nullable(); // any additional data
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('website_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_logs');
    }
};
