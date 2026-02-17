<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveStreamInteractionsTable extends Migration
{
    public function up()
    {
        Schema::create('live_stream_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stream_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('stream_id')->references('id')->on('live_streams')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->unique(['stream_id', 'user_id']);
        });

        Schema::create('live_stream_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stream_id');
            $table->unsignedBigInteger('user_id');
            $table->text('comment');
            $table->timestamps();
            
            $table->foreign('stream_id')->references('id')->on('live_streams')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->index(['stream_id', 'created_at']);
        });

        // Add like_count to live_streams
        Schema::table('live_streams', function (Blueprint $table) {
            $table->integer('like_count')->default(0)->after('peak_viewers');
        });
    }

    public function down()
    {
        Schema::table('live_streams', function (Blueprint $table) {
            $table->dropColumn('like_count');
        });
        
        Schema::dropIfExists('live_stream_comments');
        Schema::dropIfExists('live_stream_likes');
    }
}