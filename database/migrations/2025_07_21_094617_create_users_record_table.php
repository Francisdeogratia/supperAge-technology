<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('users_record', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('specialcode')->unique();
        $table->string('name');
        $table->string('username')->unique();
        $table->string('email')->unique();
        $table->string('phone', 15);
        $table->string('password');
        $table->string('gender');
        $table->date('dob');
        $table->string('continent');
        $table->string('country');
        $table->date('created');
        $table->string('status');
        $table->string('email_status');
        $table->string('phone_status');
        $table->string('unsetacct');
        $table->timestamps();
        
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_record');
    }
};
