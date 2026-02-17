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
        Schema::table('tales_extens', function (Blueprint $table) {
            // ✅ use useCurrent() instead of DB::raw()
            $table->dateTime('tales_datetime')
                  ->useCurrent() // sets CURRENT_TIMESTAMP as default
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tales_extens', function (Blueprint $table) {
            // Roll back to nullable without default
            $table->dateTime('tales_datetime')->nullable()->change();
        });
    }
};
