<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiChatHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('ai_chat_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('role', ['user', 'assistant']);
            $table->text('message');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_chat_history');
    }
}