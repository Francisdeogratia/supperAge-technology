<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow_tbl', function (Blueprint $table) {
            $table->id('follow_id'); // Primary key
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->timestamps();

            // Foreign keys pointing to users_record table
            $table->foreign('sender_id')
                  ->references('id')->on('users_record')
                  ->onDelete('cascade');

            $table->foreign('receiver_id')
                  ->references('id')->on('users_record')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_tbl');
    }
};
