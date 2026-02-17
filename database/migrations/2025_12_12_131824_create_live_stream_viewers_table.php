<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveStreamViewersTable extends Migration
{
    public function up()
    {
        Schema::create('live_stream_viewers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stream_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('joined_at');
            $table->timestamp('left_at')->nullable();
            $table->timestamps();
            
            $table->foreign('stream_id')->references('id')->on('live_streams')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->index(['stream_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('live_stream_viewers');
    }
}