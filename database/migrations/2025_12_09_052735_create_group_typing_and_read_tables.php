<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupTypingAndReadTables extends Migration
{
    public function up()
    {
        // Track when users last read group messages
        Schema::create('group_message_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('last_read_at');
            $table->timestamps();
            
            $table->unique(['group_id', 'user_id']);
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
        });
        
        // Track typing indicators
        Schema::create('group_typing_indicators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('last_typed_at');
            $table->timestamps();
            
            $table->unique(['group_id', 'user_id']);
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_typing_indicators');
        Schema::dropIfExists('group_message_reads');
    }
}