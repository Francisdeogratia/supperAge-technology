<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- IMPORTANT: Add this line

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. In 'up', we make the column NULLABLE (safest operation)
        Schema::table('tales_extens', function (Blueprint $table) {
            $table->text('tales_content')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations (Rollback).
     */
    public function down(): void
    {
        // 1. CRITICAL STEP: Before making it NOT NULL, update all existing NULL records
        // This prevents the "Data truncated" error (1265) during rollback.
        DB::table('tales_extens')
            ->whereNull('tales_content')
            ->update(['tales_content' => '']); // Replace NULL with an empty string

        // 2. In 'down', we revert the column to NOT NULL
        Schema::table('tales_extens', function (Blueprint $table) {
            $table->text('tales_content')->nullable(false)->change();
        });
    }
};