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
        Schema::create('login_details', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_record_id');
    $table->string('specialcode');
    $table->string('ip_address')->nullable();
    $table->text('user_agent')->nullable();
    $table->timestamps();

    // Optional: enforce foreign key
    $table->foreign('user_record_id')
          ->references('id')->on('users_record')
          ->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_details');
    }
};
