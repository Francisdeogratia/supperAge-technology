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
        Schema::table('sample_posts', function (Blueprint $table) {
        $table->string('text_color', 20)->default('#000000')->after('username');
        $table->string('bgnd_color', 20)->default('#ffffff')->after('text_color');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    Schema::table('sample_posts', function (Blueprint $table) {
        $table->dropColumn(['text_color', 'bgnd_color']);
    });
    }
};
