<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupCallParticipantsTable extends Migration
{
    public function up()
    {
        Schema::create('group_call_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('call_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['ringing', 'joined', 'declined', 'left'])->default('ringing');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->timestamps();
            
            $table->foreign('call_id')->references('id')->on('group_calls')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_call_participants');
    }
}