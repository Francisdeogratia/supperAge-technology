<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('group_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('sender_id');
            $table->text('message')->nullable();
            $table->text('file_path')->nullable();
            $table->string('voice_note')->nullable();
            $table->integer('voice_duration')->nullable();
            $table->unsignedBigInteger('reply_to_id')->nullable();
            $table->text('reactions')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_edited')->default(false);
            $table->timestamps();
            
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->foreign('reply_to_id')->references('id')->on('group_messages')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_messages');
    }
}