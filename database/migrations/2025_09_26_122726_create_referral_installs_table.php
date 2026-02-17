<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('referral_installs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referrer_id'); // user who shared the link
            $table->string('device_id')->nullable();   // optional: track device
            $table->string('platform')->nullable();    // 'android', 'ios'
            $table->timestamps();

            $table->foreign('referrer_id')->references('id')->on('users_record')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_installs');
    }
};
