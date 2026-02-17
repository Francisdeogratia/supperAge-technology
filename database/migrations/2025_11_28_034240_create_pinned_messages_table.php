<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pinned_messages', function (Blueprint $table) {
            // User IDs representing the unique chat thread, always stored low_id, high_id
            $table->unsignedBigInteger('user_1_id');
            $table->unsignedBigInteger('user_2_id');
            
            // ID of the message that is pinned
            $table->foreignId('message_id')->constrained('messages')->onDelete('cascade'); 
            
            $table->timestamps();

            // The combination of the two user IDs forms the primary key for the chat thread
            $table->primary(['user_1_id', 'user_2_id']);
            
            // Add foreign keys (assuming your user table is 'user_records')
            $table->foreign('user_1_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->foreign('user_2_id')->references('id')->on('users_record')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinned_messages');
    }
};