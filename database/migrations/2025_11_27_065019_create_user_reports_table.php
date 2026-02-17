<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



    class CreateUserReportsTable extends Migration
{
    public function up()
    {
        Schema::create('user_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reporter_id');
            $table->unsignedBigInteger('reported_user_id');
            $table->text('reason');
            $table->enum('status', ['pending', 'reviewed', 'action_taken'])->default('pending');
            $table->timestamps();
            
            $table->foreign('reporter_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->foreign('reported_user_id')->references('id')->on('users_record')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_reports');
    }
}

