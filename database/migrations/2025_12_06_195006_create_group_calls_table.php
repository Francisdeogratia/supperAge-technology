<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupCallsTable extends Migration
{
    public function up()
    {
        Schema::create('group_calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('initiated_by');
            $table->enum('call_type', ['audio', 'video']);
            $table->enum('status', ['ringing', 'ongoing', 'ended'])->default('ringing');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration')->nullable(); // in seconds
            $table->timestamps();
            
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('initiated_by')->references('id')->on('users_record')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_calls');
    }
}