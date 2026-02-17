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
        // Create group_reports table
            Schema::create('group_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('group_id');
                $table->unsignedBigInteger('reporter_id');
                $table->string('reason');
                $table->text('details')->nullable();
                $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending');
                $table->timestamps();
                
                $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
                $table->foreign('reporter_id')->references('id')->on('users_record')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_reports');
    }
};
