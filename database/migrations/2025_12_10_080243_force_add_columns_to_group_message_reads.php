<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ForceAddColumnsToGroupMessageReads extends Migration
{
    public function up()
    {
        // Use raw SQL to ensure columns are added
        DB::statement('ALTER TABLE `group_message_reads` ADD COLUMN IF NOT EXISTS `message_id` BIGINT UNSIGNED NULL AFTER `id`');
        DB::statement('ALTER TABLE `group_message_reads` ADD COLUMN IF NOT EXISTS `read_at` TIMESTAMP NULL AFTER `last_read_at`');
        
        // Add indexes
        try {
            DB::statement('ALTER TABLE `group_message_reads` ADD INDEX `msg_user_idx` (`message_id`, `user_id`)');
        } catch (\Exception $e) {
            // Index might already exist
        }
        
        try {
            DB::statement('ALTER TABLE `group_message_reads` ADD INDEX `group_idx` (`group_id`)');
        } catch (\Exception $e) {
            // Index might already exist
        }
    }

    public function down()
    {
        Schema::table('group_message_reads', function (Blueprint $table) {
            $table->dropIndex('msg_user_idx');
            $table->dropIndex('group_idx');
            $table->dropColumn(['message_id', 'read_at']);
        });
    }
}