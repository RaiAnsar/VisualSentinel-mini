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
        Schema::create('screenshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->string('path'); // relative path to image
            $table->string('thumbnail_path')->nullable(); // relative path to thumbnail
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('file_size')->nullable(); // in bytes
            $table->boolean('has_changes')->default(false);
            $table->float('change_percentage')->nullable();
            $table->json('change_details')->nullable(); // regions that changed, etc.
            $table->boolean('is_baseline')->default(false); // indicates if this is the baseline screenshot
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('website_id');
            $table->index('has_changes');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screenshots');
    }
};
