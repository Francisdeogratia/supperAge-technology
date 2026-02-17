<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupJoinRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('group_join_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
            
            $table->unique(['group_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_join_requests');
    }
}