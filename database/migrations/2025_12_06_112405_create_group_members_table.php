<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMembersTable extends Migration
{
    public function up()
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('role', ['admin', 'moderator', 'member'])->default('member');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
            
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
            
            $table->unique(['group_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_members');
    }
}