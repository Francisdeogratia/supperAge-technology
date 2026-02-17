<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMessageIdToGroupMessageReadsTable extends Migration
{
    public function up()
    {
        // Check if the table exists
        if (Schema::hasTable('group_message_reads')) {
            Schema::table('group_message_reads', function (Blueprint $table) {
                // Add message_id column if it doesn't exist
                if (!Schema::hasColumn('group_message_reads', 'message_id')) {
                    $table->unsignedBigInteger('message_id')->after('id')->nullable();
                }
                
                // Add read_at column if it doesn't exist
                if (!Schema::hasColumn('group_message_reads', 'read_at')) {
                    $table->timestamp('read_at')->nullable()->after('last_read_at');
                }
                
                // Add indexes for better performance
                $table->index(['message_id', 'user_id'], 'msg_user_idx');
                $table->index('group_id', 'group_idx');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('group_message_reads')) {
            Schema::table('group_message_reads', function (Blueprint $table) {
                $table->dropIndex('msg_user_idx');
                $table->dropIndex('group_idx');
                
                if (Schema::hasColumn('group_message_reads', 'message_id')) {
                    $table->dropColumn('message_id');
                }
                
                if (Schema::hasColumn('group_message_reads', 'read_at')) {
                    $table->dropColumn('read_at');
                }
            });
        }
    }
}