<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('group_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->enum('privacy', ['public', 'private'])->default('public');
            $table->integer('member_count')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users_record')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('groups');
    }
}