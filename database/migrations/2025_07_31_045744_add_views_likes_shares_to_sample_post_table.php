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
        $table->unsignedInteger('views')->default(0);
        $table->unsignedInteger('likes')->default(0);
        $table->unsignedInteger('shares')->default(0);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('sample_posts', function (Blueprint $table) {
        $table->dropColumn(['views', 'likes', 'shares']);
    });
    }
};
