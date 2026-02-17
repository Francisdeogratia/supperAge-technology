<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->text('message');
            $table->enum('status', ['sent', 'delivered', 'read'])->default('sent');
            $table->boolean('is_deleted_by_sender')->default(false);
            $table->boolean('is_deleted_by_receiver')->default(false);
            $table->timestamps();
            
            $table->index('sender_id');
            $table->index('receiver_id');
            $table->index(['sender_id', 'receiver_id']);
        });
        
        // Typing indicator table
        Schema::create('typing_indicators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('typing_to_user_id');
            $table->timestamp('last_typed_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'typing_to_user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('typing_indicators');
    }
};