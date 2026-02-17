<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ad_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action_type'); // signup, purchase, download, form_submit, etc.
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('country')->nullable();
            $table->string('device_type')->nullable();
            $table->decimal('value', 10, 2)->default(0); // Optional: conversion value
            $table->text('meta_data')->nullable(); // Additional data (JSON)
            $table->timestamp('action_at');
            $table->timestamps();
            
            $table->foreign('ad_id')->references('id')->on('advertisements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('set null');
            
            $table->index(['ad_id', 'action_at']);
        });
        
        // Add actions count to advertisements table
        Schema::table('advertisements', function (Blueprint $table) {
            $table->integer('actions')->default(0)->after('clicks');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ad_actions');
        
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn('actions');
        });
    }
};
