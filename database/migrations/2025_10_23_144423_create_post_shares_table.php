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
        Schema::create('post_shares', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('post_id');
        $table->string('platform')->nullable(); // e.g. WhatsApp, Twitter, etc.
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
        $table->foreign('post_id')->references('id')->on('sample_posts')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_shares');
    }
};
