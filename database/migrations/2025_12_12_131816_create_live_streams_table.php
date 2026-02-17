<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveStreamsTable extends Migration
{
    public function up()
    {
        Schema::create('live_streams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('stream_key', 32)->unique();
            $table->enum('status', ['live', 'ended', 'cancelled'])->default('live');
            $table->integer('viewer_count')->default(0);
            $table->integer('total_views')->default(0);
            $table->integer('peak_viewers')->default(0);
            $table->boolean('reward_claimed')->default(false);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            
            $table->foreign('creator_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->index(['status', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('live_streams');
    }
}