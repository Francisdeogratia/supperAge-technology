<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToGroupMessagesAndCreateReadsTable extends Migration
{
    public function up()
    {
        // Add status column to group_messages if it doesn't exist
        if (!Schema::hasColumn('group_messages', 'status')) {
            Schema::table('group_messages', function (Blueprint $table) {
                $table->enum('status', ['sent', 'delivered', 'seen'])->default('sent')->after('is_edited');
            });
        }
        
        // Add message_type and call_id columns
        if (!Schema::hasColumn('group_messages', 'message_type')) {
            Schema::table('group_messages', function (Blueprint $table) {
                $table->string('message_type')->nullable()->after('status'); // 'text', 'call', etc.
            });
        }
        
        if (!Schema::hasColumn('group_messages', 'call_type')) {
            Schema::table('group_messages', function (Blueprint $table) {
                $table->string('call_type')->nullable()->after('message_type'); // 'audio', 'video'
            });
        }
        
        if (!Schema::hasColumn('group_messages', 'call_id')) {
            Schema::table('group_messages', function (Blueprint $table) {
                $table->unsignedBigInteger('call_id')->nullable()->after('call_type');
            });
        }
        
        if (!Schema::hasColumn('group_messages', 'call_duration')) {
            Schema::table('group_messages', function (Blueprint $table) {
                $table->integer('call_duration')->nullable()->after('call_id'); // in seconds
            });
        }
        
        // Create group_message_reads table only if it doesn't exist
        if (!Schema::hasTable('group_message_reads')) {
            Schema::create('group_message_reads', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('message_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('group_id');
                $table->timestamp('read_at');
                $table->timestamps();
                
                $table->unique(['message_id', 'user_id']);
                $table->foreign('message_id')->references('id')->on('group_messages')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
                $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('group_message_reads');
        
        if (Schema::hasColumn('group_messages', 'status')) {
            Schema::table('group_messages', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
        
        if (Schema::hasColumn('group_messages', 'message_type')) {
            Schema::table('group_messages', function (Blueprint $table) {
                $table->dropColumn(['message_type', 'call_type', 'call_id', 'call_duration']);
            });
        }
    }
}