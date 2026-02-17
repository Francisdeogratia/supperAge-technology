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
        Schema::create('referral_clicks', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('referrer_id');
    $table->string('ip_address')->nullable();
    $table->text('user_agent')->nullable();
    $table->timestamp('clicked_at')->useCurrent();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_clicks');
    }
};
