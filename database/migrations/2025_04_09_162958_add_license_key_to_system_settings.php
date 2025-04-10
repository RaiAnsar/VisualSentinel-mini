<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert license key setting
        DB::table('system_settings')->insert([
            'key' => 'license_key',
            'value' => '',
            'group' => 'licensing',
            'type' => 'string',
            'display_name' => 'License Key',
            'description' => 'Your Visual Sentinel license key',
            'options' => json_encode([]),
            'is_public' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove license key setting
        DB::table('system_settings')->where('key', 'license_key')->delete();
    }
};
